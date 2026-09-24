<?php
declare(strict_types=1);

const PLAN_LIMITS = ['free' => 75, 'helper' => 1500, 'operator' => 6000];
const PLAN_SEATS = ['free' => 0, 'helper' => 3, 'operator' => 10];

function isStagingHost(): bool {
    $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
    return str_starts_with($host, 'staging.');
}

function config(): array {
    static $config;
    if ($config) return $config;
    $docroot = (string) $_SERVER['DOCUMENT_ROOT'];
    if (isStagingHost()) {
        // Staging lives in a subfolder of the main site's web root, so its
        // config sits next to the domain folder, outside the web root.
        $path = getenv('LITBB_CONFIG') ?: dirname($docroot, 2) . '/leaveittobumbum-config-staging.php';
    } else {
        $path = getenv('LITBB_CONFIG') ?: dirname($docroot) . '/leaveittobumbum-config.php';
    }
    if (!is_file($path)) throw new RuntimeException('Server configuration is missing.');
    $config = require $path;
    return $config;
}

function db(): PDO {
    static $pdo;
    if ($pdo) return $pdo;
    $c = config()['db'];
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $c['host'], $c['port'], $c['name']);
    $pdo = new PDO($dsn, $c['user'], $c['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function startSecureSession(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    session_set_cookie_params(['httponly' => true, 'secure' => true, 'samesite' => 'Lax', 'path' => '/']);
    session_start();
}

function jsonResponse(array $payload, int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit;
}

function body(): array {
    $decoded = json_decode(file_get_contents('php://input') ?: '{}', true);
    return is_array($decoded) ? $decoded : [];
}

function requirePost(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Method not allowed.'], 405);
}

function csrfToken(): string {
    startSecureSession();
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(24));
    return $_SESSION['csrf'];
}

function requireCsrf(array $input): void {
    if (!hash_equals(csrfToken(), (string) ($input['csrf'] ?? ''))) jsonResponse(['error' => 'Please refresh and try again.'], 403);
}

// Admin: full access, including unlimited actions and managing the community
// request queue. Email-based so no DB migration is needed; override with
// 'admin_emails' => [...] in the server config.
function adminEmails(): array {
    try {
        $cfg = config()['admin_emails'] ?? null;
        if (is_array($cfg) && $cfg) return array_values(array_unique(array_map('strtolower', array_map('trim', $cfg))));
    } catch (Throwable $e) {}
    return ['hello@leaveittobumbum.com'];
}

function isAdmin(?array $user): bool {
    if (!$user || empty($user['email'])) return false;
    return in_array(strtolower(trim((string) $user['email'])), adminEmails(), true);
}

function requireAdmin(?array $user): array {
    if (!isAdmin($user)) jsonResponse(['error' => 'Not allowed.'], 403);
    return $user;
}

// Community tool-request queue tables. Called lazily so the endpoints work
// even if the tables were never created by hand.
function ensureToolRequestTables(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS tool_requests (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL DEFAULT '',
        email VARCHAR(190) NOT NULL DEFAULT '',
        problem TEXT NOT NULL,
        outcome TEXT NOT NULL,
        status ENUM('requested','planned','building','shipped','completed','cancelled') NOT NULL DEFAULT 'requested',
        votes INT NOT NULL DEFAULT 0,
        is_operator TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        KEY idx_status_votes (status, votes)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    db()->exec("CREATE TABLE IF NOT EXISTS tool_request_votes (
        request_id INT UNSIGNED NOT NULL,
        voter_key VARCHAR(80) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (request_id, voter_key)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    try {
        // Tables created before completed/cancelled existed get the wider enum.
        $col = db()->query("SHOW COLUMNS FROM tool_requests LIKE 'status'")->fetch();
        if ($col && strpos((string) $col['Type'], "'completed'") === false) {
            db()->exec("ALTER TABLE tool_requests MODIFY status ENUM('requested','planned','building','shipped','completed','cancelled') NOT NULL DEFAULT 'requested'");
        }
        // Older tables lack the operator flag.
        $op = db()->query("SHOW COLUMNS FROM tool_requests LIKE 'is_operator'")->fetch();
        if (!$op) db()->exec("ALTER TABLE tool_requests ADD COLUMN is_operator TINYINT(1) NOT NULL DEFAULT 0");
        // Same collation normalization as custom_requests: connection is
        // utf8mb4_unicode_ci, older tables may be utf8mb4_general_ci.
        db()->exec('ALTER TABLE tool_requests CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        db()->exec('ALTER TABLE tool_request_votes CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    } catch (Throwable $e) { error_log('tool_requests migration failed: ' . $e->getMessage()); }
}

// Operator custom-request tables (36-hour guarantee). Self-healing: creates
// the table and backfills any columns an older schema is missing, so the
// account-page form works even if the table was created by hand long ago.
function ensureCustomRequestTables(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS custom_requests (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NOT NULL,
        title VARCHAR(180) NOT NULL,
        details TEXT NOT NULL,
        status ENUM('open','delivered','overdue_credited') NOT NULL DEFAULT 'open',
        requested_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        deadline_at DATETIME NOT NULL,
        delivered_at DATETIME NULL,
        credit_owed TINYINT(1) NOT NULL DEFAULT 0,
        stripe_credit_id VARCHAR(80) NULL,
        INDEX idx_user (user_id),
        INDEX idx_status_deadline (status, deadline_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $want = [
        'user_id' => 'INT UNSIGNED NOT NULL',
        'title' => 'VARCHAR(180) NOT NULL',
        'details' => 'TEXT NOT NULL',
        'status' => "ENUM('open','delivered','overdue_credited') NOT NULL DEFAULT 'open'",
        'requested_at' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP',
        'deadline_at' => 'DATETIME NOT NULL',
        'delivered_at' => 'DATETIME NULL',
        'credit_owed' => 'TINYINT(1) NOT NULL DEFAULT 0',
        'stripe_credit_id' => 'VARCHAR(80) NULL',
    ];
    try {
        $have = [];
        foreach (db()->query('SHOW COLUMNS FROM custom_requests')->fetchAll() as $c) $have[$c['Field']] = true;
        foreach ($want as $col => $def) {
            if (!isset($have[$col])) db()->exec("ALTER TABLE custom_requests ADD COLUMN $col $def");
        }
    } catch (Throwable $e) { error_log('custom_requests heal failed: ' . $e->getMessage()); }
    // Old tables were created with utf8mb4_general_ci while the connection
    // uses utf8mb4_unicode_ci, so string comparisons (e.g. status = 'open')
    // fail with "Illegal mix of collations". Normalize once.
    try {
        db()->exec('ALTER TABLE custom_requests CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    } catch (Throwable $e) { error_log('custom_requests collation fix failed: ' . $e->getMessage()); }
}

// OAuth sign-in (Sign in with Google / LinkedIn). Columns are added lazily
// so no manual migration is needed; both are nullable-unique (MySQL allows
// many NULLs in a UNIQUE column).
function ensureOAuthColumns(): void {
    try {
        $have = [];
        foreach (db()->query('SHOW COLUMNS FROM users')->fetchAll() as $c) $have[$c['Field']] = true;
        foreach (['oauth_google_sub', 'oauth_linkedin_sub'] as $col) {
            if (!isset($have[$col])) db()->exec("ALTER TABLE users ADD COLUMN $col VARCHAR(64) NULL UNIQUE");
        }
    } catch (Throwable $e) { error_log('oauth columns heal failed: ' . $e->getMessage()); }
}

// Provider definitions for OAuth sign-in. Client id/secret live in the
// server config (outside the repo): google_client_id, google_client_secret,
// linkedin_client_id, linkedin_client_secret.
function oauthProviders(): array {
    $cfg = config();
    $callback = rtrim((string) ($cfg['app_url'] ?? ''), '/') . '/api/auth/oauth-callback.php';
    return [
        'google' => [
            'label' => 'Google',
            'client_id' => (string) ($cfg['google_client_id'] ?? ''),
            'client_secret' => (string) ($cfg['google_client_secret'] ?? ''),
            'callback' => $callback,
            'authorize' => 'https://accounts.google.com/o/oauth2/v2/auth',
            'token' => 'https://oauth2.googleapis.com/token',
            'userinfo' => 'https://www.googleapis.com/oauth2/v3/userinfo',
            'scope' => 'openid email profile',
            'extra_auth' => ['prompt' => 'select_account'],
        ],
        'linkedin' => [
            'label' => 'LinkedIn',
            'client_id' => (string) ($cfg['linkedin_client_id'] ?? ''),
            'client_secret' => (string) ($cfg['linkedin_client_secret'] ?? ''),
            'callback' => $callback,
            'authorize' => 'https://www.linkedin.com/oauth/v2/authorization',
            'token' => 'https://www.linkedin.com/oauth/v2/accessToken',
            'userinfo' => 'https://api.linkedin.com/v2/userinfo',
            'scope' => 'openid profile email',
            'extra_auth' => [],
        ],
    ];
}

function oauthEnabled(string $provider): bool {
    $p = oauthProviders()[$provider] ?? null;
    return $p !== null && $p['client_id'] !== '' && $p['client_secret'] !== '';
}

function currentUser(): ?array {
    startSecureSession();
    if (empty($_SESSION['user_id'])) return null;
    $stmt = db()->prepare('SELECT id, email, stripe_customer_id, plan, subscription_status, period_start, period_end FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

function requireUser(): array {
    $user = currentUser();
    if (!$user) jsonResponse(['error' => 'Sign in to continue.'], 401);
    return $user;
}

function periodKey(array $user): string {
    return !empty($user['period_start']) ? substr((string) $user['period_start'], 0, 7) : gmdate('Y-m');
}

// Team the user belongs to as an active member, or null. Safe before the
// team_members migration has run (returns null so everything degrades to solo).
function teamMembership(int $userId): ?array {
    try {
        $stmt = db()->prepare("SELECT tm.*, u.email AS owner_email, u.plan AS owner_plan FROM team_members tm JOIN users u ON u.id = tm.owner_user_id WHERE tm.member_user_id = ? AND tm.status = 'active' LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: null;
    } catch (Throwable $e) {
        return null;
    }
}

// Billing user id for metering: the team owner's id when this user is an
// active member, otherwise the user's own id.
function billingUserId(int $userId): int {
    $m = teamMembership($userId);
    return $m ? (int) $m['owner_user_id'] : $userId;
}

// The user row whose plan and usage meter apply: the team owner when this
// user is an active member, otherwise the user themself. Adds team_role,
// team_owner_id and team_owner_email keys for the UI.
function billingUser(array $user): array {
    $membership = teamMembership((int) $user['id']);
    if (!$membership) return $user + ['team_role' => 'owner', 'team_owner_id' => null, 'team_owner_email' => null];
    $stmt = db()->prepare('SELECT id, email, plan, subscription_status, period_start, period_end FROM users WHERE id = ?');
    $stmt->execute([(int) $membership['owner_user_id']]);
    $owner = $stmt->fetch();
    if (!$owner) return $user + ['team_role' => 'owner', 'team_owner_id' => null, 'team_owner_email' => null];
    $owner['team_role'] = 'member';
    $owner['team_owner_id'] = (int) $membership['owner_user_id'];
    $owner['team_owner_email'] = (string) $membership['owner_email'];
    return $owner;
}

function teamSeats(int $ownerId, string $plan): array {
    $limit = PLAN_SEATS[$plan] ?? 0;
    try {
        $stmt = db()->prepare("SELECT COUNT(*) FROM team_members WHERE owner_user_id = ? AND status IN ('invited','active')");
        $stmt->execute([$ownerId]);
        $used = (int) $stmt->fetchColumn();
    } catch (Throwable $e) {
        $used = 0;
    }
    return ['used' => $used, 'limit' => $limit, 'remaining' => max(0, $limit - $used)];
}

function usageFor(array $user): array {
    $period = periodKey($user);
    if (isAdmin($user)) {
        $stmt = db()->prepare('SELECT used_actions FROM usage_periods WHERE user_id = ? AND period_key = ?');
        $stmt->execute([$user['id'], $period]);
        $used = (int) ($stmt->fetchColumn() ?: 0);
        return ['period' => $period, 'used' => $used, 'limit' => 0, 'remaining' => 0, 'unlimited' => true];
    }
    $limit = PLAN_LIMITS[$user['plan']] ?? PLAN_LIMITS['free'];
    $stmt = db()->prepare('SELECT used_actions FROM usage_periods WHERE user_id = ? AND period_key = ?');
    $stmt->execute([$user['id'], $period]);
    $used = (int) ($stmt->fetchColumn() ?: 0);
    return ['period' => $period, 'used' => $used, 'limit' => $limit, 'remaining' => max(0, $limit - $used)];
}

function consumeAction(int $userId, string $plan, string $period, string $tool, string $idempotency): array {
    $limit = PLAN_LIMITS[$plan] ?? PLAN_LIMITS['free'];
    $pdo = db();
    // Admins never run out of actions. Usage is still recorded so the
    // activity history stays accurate; only the limit check is skipped.
    $unlimited = false;
    try {
        $emailStmt = $pdo->prepare('SELECT email FROM users WHERE id = ?');
        $emailStmt->execute([$userId]);
        $unlimited = isAdmin(['email' => (string) $emailStmt->fetchColumn()]);
    } catch (Throwable $e) { $unlimited = false; }
    $pdo->beginTransaction();
    try {
        $existing = $pdo->prepare('SELECT id FROM action_ledger WHERE user_id = ? AND idempotency_key = ?');
        $existing->execute([$userId, $idempotency]);
        if ($existing->fetch()) {
            $pdo->commit();
            $dup = ['counted' => false, 'duplicate' => true];
            if ($unlimited) $dup['unlimited'] = true;
            return $dup;
        }
        $upsert = $pdo->prepare('INSERT INTO usage_periods (user_id, period_key, used_actions, included_actions) VALUES (?, ?, 0, ?) ON DUPLICATE KEY UPDATE included_actions = VALUES(included_actions)');
        $upsert->execute([$userId, $period, $limit]);
        $lock = $pdo->prepare('SELECT used_actions FROM usage_periods WHERE user_id = ? AND period_key = ? FOR UPDATE');
        $lock->execute([$userId, $period]);
        $used = (int) $lock->fetchColumn();
        if (!$unlimited && $used >= $limit) {
            $pdo->rollBack();
            return ['counted' => false, 'limit_reached' => true, 'used' => $used, 'limit' => $limit];
        }
        $pdo->prepare('INSERT INTO action_ledger (user_id, period_key, tool_key, idempotency_key) VALUES (?, ?, ?, ?)')->execute([$userId, $period, $tool, $idempotency]);
        $pdo->prepare('UPDATE usage_periods SET used_actions = used_actions + 1 WHERE user_id = ? AND period_key = ?')->execute([$userId, $period]);
        $pdo->commit();
        $out = ['counted' => true, 'used' => $used + 1, 'limit' => $limit];
        if ($unlimited) { $out['limit'] = 0; $out['remaining'] = 0; $out['unlimited'] = true; }
        return $out;
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $error;
    }
}

function posthogCapture(string $event, string $distinctId, array $properties = []): void {
    $ph = config()['posthog'] ?? [];
    $apiKey = (string) ($ph['api_key'] ?? '');
    if ($apiKey === '' || $apiKey === 'phx_replace_me') return;
    $host = rtrim((string) ($ph['host'] ?? 'https://us.i.posthog.com'), '/');
    $properties['env'] = isStagingHost() ? 'staging' : 'production';
    $payload = json_encode(['api_key' => $apiKey, 'event' => $event, 'distinct_id' => $distinctId, 'properties' => $properties]);
    $curl = curl_init($host . '/capture/');
    curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => $payload, CURLOPT_HTTPHEADER => ['Content-Type: application/json'], CURLOPT_TIMEOUT => 3]);
    $ok = curl_exec($curl);
    if ($ok === false) error_log('PostHog capture failed: ' . curl_error($curl));
    curl_close($curl);
}

function stripeRequest(string $method, string $path, array $params = []): array {
    $stripe = config()['stripe'];
    $curl = curl_init('https://api.stripe.com' . $path);
    $headers = ['Authorization: Bearer ' . $stripe['secret_key'], 'Stripe-Version: 2026-07-29.dahlia'];
    curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => true, CURLOPT_CUSTOMREQUEST => $method, CURLOPT_HTTPHEADER => $headers, CURLOPT_TIMEOUT => 20]);
    if ($params) curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    $raw = curl_exec($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    if ($raw === false) throw new RuntimeException('Stripe connection failed.');
    $data = json_decode($raw, true) ?: [];
    if ($status >= 400) throw new RuntimeException((string) ($data['error']['message'] ?? 'Stripe request failed.'));
    return $data;
}
