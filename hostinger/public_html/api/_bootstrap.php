<?php
declare(strict_types=1);

const PLAN_LIMITS = ['free' => 75, 'helper' => 1500, 'operator' => 6000];
const PLAN_SEATS = ['free' => 0, 'helper' => 3, 'operator' => 10];

// Guest actions: visitors can use the tools with no account. Every visitor
// gets a server-minted guest identity (signed cookie + DB row) and a small
// lifetime allowance. At the limit they are asked to sign up, and everything
// they made merges into their new account.
const GUEST_ACTION_LIMIT = 15;
const GUEST_COOKIE_NAME = 'bb_guest';
const GUEST_MINTS_PER_IP_PER_DAY = 20;

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
    // Persistent login: 30-day session cookie + matching server-side
    // lifetime so users stay logged in across browser restarts.
    $persist = 30 * 24 * 3600;
    ini_set('session.gc_maxlifetime', (string) $persist);
    session_set_cookie_params(['lifetime' => $persist, 'httponly' => true, 'secure' => true, 'samesite' => 'Lax', 'path' => '/']);
    session_start();
    // PHP only sends the session cookie for new/regenerated sessions, so
    // re-send it here to upgrade resumed sessions to the persistent expiry
    // without forcing everyone to log in again.
    if (!headers_sent()) {
        setcookie(session_name(), session_id(), ['expires' => time() + $persist, 'httponly' => true, 'secure' => true, 'samesite' => 'Lax', 'path' => '/']);
    }
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
        status ENUM('open','delivered','overdue_credited','cancelled') NOT NULL DEFAULT 'open',
        requested_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        deadline_at DATETIME NOT NULL,
        delivered_at DATETIME NULL,
        credit_owed TINYINT(1) NOT NULL DEFAULT 0,
        stripe_credit_id VARCHAR(80) NULL,
        queue_id INT UNSIGNED NULL,
        INDEX idx_user (user_id),
        INDEX idx_status_deadline (status, deadline_at),
        INDEX idx_queue (queue_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $want = [
        'user_id' => 'INT UNSIGNED NOT NULL',
        'title' => 'VARCHAR(180) NOT NULL',
        'details' => 'TEXT NOT NULL',
        'requested_at' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP',
        'deadline_at' => 'DATETIME NOT NULL',
        'delivered_at' => 'DATETIME NULL',
        'credit_owed' => 'TINYINT(1) NOT NULL DEFAULT 0',
        'stripe_credit_id' => 'VARCHAR(80) NULL',
        'queue_id' => 'INT UNSIGNED NULL',
    ];
    try {
        $have = [];
        foreach (db()->query('SHOW COLUMNS FROM custom_requests')->fetchAll() as $c) $have[$c['Field']] = $c['Type'];
        foreach ($want as $col => $def) {
            if (!isset($have[$col])) db()->exec("ALTER TABLE custom_requests ADD COLUMN $col $def");
        }
        // Widen the status enum on older tables so admin cancellations have
        // somewhere to land. ADD COLUMN above never touches existing columns.
        if (isset($have['status']) && strpos((string) $have['status'], "'cancelled'") === false) {
            db()->exec("ALTER TABLE custom_requests MODIFY status ENUM('open','delivered','overdue_credited','cancelled') NOT NULL DEFAULT 'open'");
        }
        if (!isset($have['queue_id'])) {
            try { db()->exec('ALTER TABLE custom_requests ADD INDEX idx_queue (queue_id)'); }
            catch (Throwable $e) { error_log('custom_requests queue index failed: ' . $e->getMessage()); }
        }
    } catch (Throwable $e) { error_log('custom_requests heal failed: ' . $e->getMessage()); }
    // Old tables were created with utf8mb4_general_ci while the connection
    // uses utf8mb4_unicode_ci, so string comparisons (e.g. status = 'open')
    // fail with "Illegal mix of collations". Normalize once.
    try {
        db()->exec('ALTER TABLE custom_requests CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    } catch (Throwable $e) { error_log('custom_requests collation fix failed: ' . $e->getMessage()); }
    backfillCustomRequestQueueLinks();
}

// One-time (and ongoing, for rows whose mirror insert failed) linking of
// Operator custom_requests to their mirrored community-queue rows. Matches
// on title, preferring the closest timestamp within a generous window —
// mirrors are usually inserted in the same request, but manual or backfilled
// mirrors can be days apart. Also applies any already-terminal queue status
// the admin set before the link existed (no emails here — only fresh admin
// actions notify).
function backfillCustomRequestQueueLinks(): void {
    try {
        $unlinked = db()->query('SELECT id, title, requested_at FROM custom_requests WHERE queue_id IS NULL')->fetchAll();
        if (!$unlinked) return;
        ensureToolRequestTables();
        $mirrors = db()->query('SELECT id, problem, created_at, status FROM tool_requests WHERE is_operator = 1')->fetchAll();
        if (!$mirrors) return;
        $used = [];
        foreach (db()->query('SELECT queue_id FROM custom_requests WHERE queue_id IS NOT NULL')->fetchAll(PDO::FETCH_COLUMN) as $qid) {
            $used[(int) $qid] = true;
        }
        $link = db()->prepare('UPDATE custom_requests SET queue_id = ? WHERE id = ?');
        foreach ($unlinked as $cr) {
            $best = null;
            $bestDiff = 7 * 24 * 3600;
            foreach ($mirrors as $m) {
                if (isset($used[(int) $m['id']])) continue;
                if (trim((string) $m['problem']) !== trim((string) $cr['title'])) continue;
                $diff = abs(strtotime((string) $m['created_at']) - strtotime((string) $cr['requested_at']));
                if ($diff <= $bestDiff) { $best = $m; $bestDiff = $diff; }
            }
            if (!$best) continue;
            $link->execute([(int) $best['id'], (int) $cr['id']]);
            $used[(int) $best['id']] = true;
            propagateQueueStatus((int) $best['id'], (string) $best['status']);
        }
    } catch (Throwable $e) { error_log('custom_requests queue backfill failed: ' . $e->getMessage()); }
}

// Maps a community-queue admin status onto the linked Operator custom
// request. Only transitions out of 'open' (a credited or delivered request
// keeps its terminal state). Returns the custom_requests id that changed.
function propagateQueueStatus(int $queueId, string $queueStatus): ?int {
    $map = ['shipped' => 'delivered', 'completed' => 'delivered', 'cancelled' => 'cancelled'];
    if (!isset($map[$queueStatus])) return null;
    try {
        $row = db()->prepare('SELECT id, status FROM custom_requests WHERE queue_id = ? LIMIT 1');
        $row->execute([$queueId]);
        $cr = $row->fetch();
        if (!$cr || $cr['status'] !== 'open') return null;
        $new = $map[$queueStatus];
        // Explicit COLLATE: older tables may still be utf8mb4_general_ci on
        // a unicode_ci connection (see note in ensureCustomRequestTables).
        $upd = db()->prepare("UPDATE custom_requests SET status = ?, delivered_at = CASE WHEN ? = 'delivered' THEN NOW() ELSE delivered_at END WHERE id = ? AND status = 'open' COLLATE utf8mb4_unicode_ci");
        $upd->execute([$new, $new, (int) $cr['id']]);
        return $upd->rowCount() > 0 ? (int) $cr['id'] : null;
    } catch (Throwable $e) { error_log('propagateQueueStatus failed: ' . $e->getMessage()); return null; }
}

// Best-effort transactional email via Resend. Needs 'resend' => ['api_key']
// in the server config; without it the send is skipped and logged.
// A failed or skipped send never throws — callers treat mail as notify-only.
function bbSendEmail(string $to, string $subject, string $text, ?string $from = null): bool {
    try { $cfg = config(); } catch (Throwable $e) { $cfg = []; }
    $apiKey = $cfg['resend']['api_key'] ?? $cfg['resend_api_key'] ?? null;
    if (!$apiKey || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        error_log('bbSendEmail skipped: ' . (!$apiKey ? 'no Resend API key in server config' : 'invalid recipient'));
        return false;
    }
    $ch = curl_init('https://api.resend.com/emails');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $apiKey, 'Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode([
            'from' => $from ?: 'Bum Bum <hello@leaveittobumbum.com>',
            'to' => [$to],
            'subject' => $subject,
            'text' => $text,
        ]),
    ]);
    $resp = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);
    if ($resp === false || $code < 200 || $code >= 300) {
        error_log('bbSendEmail failed: ' . ($curlErr ?: $code . ' ' . substr((string) $resp, 0, 200)));
        return false;
    }
    return true;
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

// ---------------------------------------------------------------------------
// Guest actions: use the tools with no account.
//
// A guest is a server-minted identity: a UUID stored in the `guests` table,
// carried in a long-lived HMAC-signed cookie. Guests are metered in their own
// tables (`guest_usage`, `guest_ledger`) so the user metering tables are never
// touched. At the limit the API answers 402 with `signup_required: true`; on
// signup/login everything merges into the new account.
//
// Deliberately NOT used: browser fingerprinting (creepy, unreliable) and IP
// as identity (shared networks). Clearing cookies resets the allowance; that
// is accepted — the goal is conversion, not DRM.
// ---------------------------------------------------------------------------

function guestSecret(): ?string {
    try {
        $s = trim((string) (config()['guest_secret'] ?? ''));
        return $s !== '' ? $s : null;
    } catch (Throwable $e) {
        return null;
    }
}

function guestActionsEnabled(): bool {
    return guestSecret() !== null;
}

// Lazy table creation, same pattern as ensureToolRequestTables(): the first
// guest request creates them, so no manual migration is needed on deploy.
function ensureGuestTables(): void {
    static $done = false;
    if ($done) return;
    $done = true;
    $pdo = db();
    $pdo->exec("CREATE TABLE IF NOT EXISTS guests (
        id CHAR(36) NOT NULL PRIMARY KEY,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        last_seen_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        ip_hash CHAR(64) NOT NULL DEFAULT '',
        ua_hash CHAR(64) NOT NULL DEFAULT '',
        converted_user_id BIGINT UNSIGNED NULL DEFAULT NULL,
        KEY idx_guests_ip_created (ip_hash, created_at),
        KEY idx_guests_seen (converted_user_id, last_seen_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $pdo->exec("CREATE TABLE IF NOT EXISTS guest_usage (
        guest_id CHAR(36) NOT NULL,
        period_key VARCHAR(16) NOT NULL,
        used_actions INT UNSIGNED NOT NULL DEFAULT 0,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (guest_id, period_key)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $pdo->exec("CREATE TABLE IF NOT EXISTS guest_ledger (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        guest_id CHAR(36) NOT NULL,
        period_key VARCHAR(16) NOT NULL,
        tool_key VARCHAR(64) NOT NULL,
        idempotency_key VARCHAR(128) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_guest_idem (guest_id, idempotency_key),
        KEY idx_guest_period (guest_id, period_key)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function newGuestId(): string {
    $b = random_bytes(16);
    $b[6] = chr((ord($b[6]) & 0x0f) | 0x40);
    $b[8] = chr((ord($b[8]) & 0x3f) | 0x80);
    $h = bin2hex($b);
    return substr($h, 0, 8) . '-' . substr($h, 8, 4) . '-' . substr($h, 12, 4) . '-' . substr($h, 16, 4) . '-' . substr($h, 20, 12);
}

function signGuestId(string $id, string $secret): string {
    return $id . '.' . hash_hmac('sha256', $id, $secret);
}

// Validate the signed cookie. Returns the guest id or null. Never mints:
// safe to call on plain page views.
function guestIdFromCookie(): ?string {
    $secret = guestSecret();
    if ($secret === null) return null;
    $raw = (string) ($_COOKIE[GUEST_COOKIE_NAME] ?? '');
    if (!preg_match('/^([0-9a-f-]{36})\.([0-9a-f]{64})$/', $raw, $m)) return null;
    if (!hash_equals(hash_hmac('sha256', $m[1], $secret), $m[2])) return null;
    return $m[1];
}

// Mint a fresh guest: DB row + signed cookie. The velocity guard rejects
// more than GUEST_MINTS_PER_IP_PER_DAY new guests per IP per day.
function mintGuest(): string {
    ensureGuestTables();
    $secret = guestSecret();
    if ($secret === null) jsonResponse(['error' => 'Sign in to continue.'], 401);
    $pdo = db();
    $ipHash = hash('sha256', 'bb-guest-ip:' . (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
    $recent = $pdo->prepare("SELECT COUNT(*) FROM guests WHERE ip_hash = ? AND created_at > DATE_SUB(UTC_TIMESTAMP(), INTERVAL 24 HOUR)");
    $recent->execute([$ipHash]);
    if ((int) $recent->fetchColumn() >= GUEST_MINTS_PER_IP_PER_DAY) {
        jsonResponse(['error' => 'Too many visits from this network right now. Try again tomorrow.'], 429);
    }
    $id = newGuestId();
    $uaHash = hash('sha256', 'bb-guest-ua:' . (string) ($_SERVER['HTTP_USER_AGENT'] ?? ''));
    $pdo->prepare('INSERT INTO guests (id, ip_hash, ua_hash) VALUES (?, ?, ?)')->execute([$id, $ipHash, $uaHash]);
    $signed = signGuestId($id, $secret);
    if (!headers_sent()) {
        setcookie(GUEST_COOKIE_NAME, $signed, ['expires' => time() + 365 * 24 * 3600, 'httponly' => true, 'secure' => true, 'samesite' => 'Lax', 'path' => '/']);
    }
    $_COOKIE[GUEST_COOKIE_NAME] = $signed;
    posthogCapture('guest_created', $id, []);
    return $id;
}

// Who is making this request? Tool pages use pageSubject() (mints a guest on
// first view so the meter can read "15 of 15 actions left" before first use);
// API endpoints use requireSubject() (mints on first use). Shapes:
//   ['kind' => 'user', 'user' => $userRow, 'bill' => $billingRow]
//   ['kind' => 'guest', 'guest_id' => $uuid]
//   ['kind' => 'none']
function pageSubject(): array {
    $subject = currentSubject();
    if ($subject['kind'] !== 'none') return $subject;
    if (!guestActionsEnabled()) return $subject;
    // Mint, but never break the page: if the velocity guard trips or the DB
    // hiccups, the visitor simply sees the tool with no guest meter yet.
    try {
        ensureGuestTables();
        $pdo = db();
        $ipHash = hash('sha256', 'bb-guest-ip:' . (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        $recent = $pdo->prepare("SELECT COUNT(*) FROM guests WHERE ip_hash = ? AND created_at > DATE_SUB(UTC_TIMESTAMP(), INTERVAL 24 HOUR)");
        $recent->execute([$ipHash]);
        if ((int) $recent->fetchColumn() >= GUEST_MINTS_PER_IP_PER_DAY) return $subject;
        return ['kind' => 'guest', 'guest_id' => mintGuest()];
    } catch (Throwable $error) {
        return $subject;
    }
}
function currentSubject(): array {
    $user = currentUser();
    if ($user) return ['kind' => 'user', 'user' => $user, 'bill' => billingUser($user)];
    $gid = guestIdFromCookie();
    if ($gid !== null) {
        try {
            db()->prepare('UPDATE guests SET last_seen_at = CURRENT_TIMESTAMP WHERE id = ?')->execute([$gid]);
        } catch (Throwable $e) {}
        return ['kind' => 'guest', 'guest_id' => $gid];
    }
    return ['kind' => 'none'];
}

// For API endpoints: like requireUser(), but signed-out visitors get a guest
// instead of a 401. Set $mint=false for endpoints that must not create guests.
function requireSubject(bool $mint = true): array {
    $subject = currentSubject();
    if ($subject['kind'] !== 'none') return $subject;
    if (!$mint || !guestActionsEnabled()) jsonResponse(['error' => 'Sign in to continue.'], 401);
    return ['kind' => 'guest', 'guest_id' => mintGuest(), 'fresh' => true];
}

// Guests get a fixed lifetime allowance, not a monthly reset. One row per
// guest, period_key = 'lifetime'.
function guestPeriodKey(): string {
    return 'lifetime';
}

function guestUsage(string $guestId): array {
    ensureGuestTables();
    $period = guestPeriodKey();
    $stmt = db()->prepare('SELECT used_actions FROM guest_usage WHERE guest_id = ? AND period_key = ?');
    $stmt->execute([$guestId, $period]);
    $used = (int) ($stmt->fetchColumn() ?: 0);
    return [
        'period' => $period,
        'used' => $used,
        'limit' => GUEST_ACTION_LIMIT,
        'remaining' => max(0, GUEST_ACTION_LIMIT - $used),
        'unlimited' => false,
        'is_guest' => true,
    ];
}

// Usage for either subject kind. Drop-in replacement for usageFor($bill).
function subjectUsage(array $subject): array {
    if ($subject['kind'] === 'guest') return guestUsage($subject['guest_id']);
    return usageFor($subject['bill']) + ['is_guest' => false];
}

// Consume one action for either subject kind. Drop-in replacement for
// consumeAction((int)$bill['id'], (string)$bill['plan'], periodKey($bill), $tool, $idem).
function consumeSubjectAction(array $subject, string $tool, string $idempotency): array {
    if ($subject['kind'] === 'guest') return consumeGuestAction($subject['guest_id'], $tool, $idempotency);
    $bill = $subject['bill'];
    return consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), $tool, $idempotency);
}

function consumeGuestAction(string $guestId, string $tool, string $idempotency): array {
    ensureGuestTables();
    $pdo = db();
    $period = guestPeriodKey();
    $pdo->beginTransaction();
    try {
        $existing = $pdo->prepare('SELECT id FROM guest_ledger WHERE guest_id = ? AND idempotency_key = ?');
        $existing->execute([$guestId, $idempotency]);
        if ($existing->fetch()) {
            $pdo->commit();
            return ['counted' => false, 'duplicate' => true, 'is_guest' => true];
        }
        $pdo->prepare('INSERT IGNORE INTO guest_usage (guest_id, period_key, used_actions) VALUES (?, ?, 0)')->execute([$guestId, $period]);
        $lock = $pdo->prepare('SELECT used_actions FROM guest_usage WHERE guest_id = ? AND period_key = ? FOR UPDATE');
        $lock->execute([$guestId, $period]);
        $used = (int) $lock->fetchColumn();
        if ($used >= GUEST_ACTION_LIMIT) {
            $pdo->rollBack();
            return ['counted' => false, 'limit_reached' => true, 'used' => $used, 'limit' => GUEST_ACTION_LIMIT, 'remaining' => 0, 'is_guest' => true];
        }
        $pdo->prepare('INSERT INTO guest_ledger (guest_id, period_key, tool_key, idempotency_key) VALUES (?, ?, ?, ?)')->execute([$guestId, $period, $tool, $idempotency]);
        $pdo->prepare('UPDATE guest_usage SET used_actions = used_actions + 1 WHERE guest_id = ? AND period_key = ?')->execute([$guestId, $period]);
        $pdo->commit();
        posthogCapture('guest_action', $guestId, ['tool' => $tool, 'used' => $used + 1, 'limit' => GUEST_ACTION_LIMIT]);
        return ['counted' => true, 'used' => $used + 1, 'limit' => GUEST_ACTION_LIMIT, 'remaining' => GUEST_ACTION_LIMIT - $used - 1, 'is_guest' => true];
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $error;
    }
}

// The limit response. Guests get signup_required: true and signup copy;
// users keep the existing upgrade copy.
function limitReachedResponse(array $subject, array $usage): never {
    if ($subject['kind'] === 'guest') {
        posthogCapture('guest_limit_reached', $subject['guest_id'], ['used' => $usage['used'] ?? GUEST_ACTION_LIMIT, 'limit' => GUEST_ACTION_LIMIT]);
        jsonResponse([
            'error' => 'You have used all ' . GUEST_ACTION_LIMIT . ' free actions. Create a free account to keep going.',
            'usage' => $usage,
            'signup_required' => true,
        ], 402);
    }
    jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $usage], 402);
}

// Merge a guest into a user on signup/login: the guest's lifetime used
// actions move into the user's current period row, ledger rows move with
// INSERT IGNORE (a colliding idempotency key keeps the user's row), guest
// rows are retired. $bill is the billing user row (from billingUser()).
function mergeGuestIntoUser(string $guestId, array $bill): void {
    ensureGuestTables();
    $userId = (int) ($bill['id'] ?? 0);
    if (!preg_match('/^[0-9a-f-]{36}$/', $guestId) || $userId <= 0) return;
    $period = periodKey($bill);
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $sumStmt = $pdo->prepare('SELECT COALESCE(SUM(used_actions), 0) FROM guest_usage WHERE guest_id = ?');
        $sumStmt->execute([$guestId]);
        $moved = (int) $sumStmt->fetchColumn();
        if ($moved > 0) {
            $planLimit = PLAN_LIMITS[$bill['plan'] ?? 'free'] ?? PLAN_LIMITS['free'];
            $pdo->prepare('INSERT INTO usage_periods (user_id, period_key, used_actions, included_actions) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE used_actions = used_actions + VALUES(used_actions)')->execute([$userId, $period, $moved, $planLimit]);
        }
        $pdo->prepare('INSERT IGNORE INTO action_ledger (user_id, period_key, tool_key, idempotency_key) SELECT ?, ?, tool_key, idempotency_key FROM guest_ledger WHERE guest_id = ?')->execute([$userId, $period, $guestId]);
        $pdo->prepare('DELETE FROM guest_ledger WHERE guest_id = ?')->execute([$guestId]);
        $pdo->prepare('DELETE FROM guest_usage WHERE guest_id = ?')->execute([$guestId]);
        $pdo->prepare('UPDATE guests SET converted_user_id = ? WHERE id = ?')->execute([$userId, $guestId]);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $error;
    }
    posthogCapture('guest_converted', $guestId, ['user_id' => $userId, 'guest_actions_used' => $moved]);
    posthogAlias($guestId, 'user-' . $userId);
}

// Expire the guest cookie (after conversion, so the old allowance cannot be
// reused alongside the new account).
function expireGuestCookie(): void {
    unset($_COOKIE[GUEST_COOKIE_NAME]);
    if (!headers_sent()) {
        setcookie(GUEST_COOKIE_NAME, '', ['expires' => time() - 3600, 'httponly' => true, 'secure' => true, 'samesite' => 'Lax', 'path' => '/']);
    }
}

// Called from signup/login after the user session is established: merge the
// pending guest (captured from the cookie before auth) into the new account
// and retire the guest cookie. Safe to call with null.
function mergePendingGuest(?string $guestId): void {
    if ($guestId === null) return;
    $user = currentUser();
    if (!$user) return;
    mergeGuestIntoUser($guestId, billingUser($user));
    expireGuestCookie();
}

// Delete unconverted guests idle for 90+ days, with their usage rows.
// Called from the daily cron. Returns the number purged.
function purgeOldGuests(): int {
    ensureGuestTables();
    $pdo = db();
    $ids = $pdo->query("SELECT id FROM guests WHERE converted_user_id IS NULL AND last_seen_at < DATE_SUB(UTC_TIMESTAMP(), INTERVAL 90 DAY) LIMIT 1000")->fetchAll(PDO::FETCH_COLUMN);
    if (!$ids) return 0;
    $in = implode(',', array_fill(0, count($ids), '?'));
    $pdo->prepare("DELETE FROM guest_ledger WHERE guest_id IN ($in)")->execute($ids);
    $pdo->prepare("DELETE FROM guest_usage WHERE guest_id IN ($in)")->execute($ids);
    $pdo->prepare("DELETE FROM guests WHERE id IN ($in)")->execute($ids);
    return count($ids);
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

// Link a guest's pre-signup events to their new user id so the funnel stays
// one journey. Best-effort: failures are logged, never fatal.
function posthogAlias(string $guestId, string $userDistinctId): void {
    try {
        $ph = config()['posthog'] ?? [];
        $apiKey = (string) ($ph['api_key'] ?? '');
        if ($apiKey === '' || $apiKey === 'phx_replace_me') return;
        $host = rtrim((string) ($ph['host'] ?? 'https://us.i.posthog.com'), '/');
        $payload = json_encode([
            'api_key' => $apiKey,
            'event' => '$create_alias',
            'distinct_id' => $userDistinctId,
            'properties' => ['alias' => $guestId, 'env' => isStagingHost() ? 'staging' : 'production'],
        ]);
        $curl = curl_init($host . '/capture/');
        curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => $payload, CURLOPT_HTTPHEADER => ['Content-Type: application/json'], CURLOPT_TIMEOUT => 3]);
        if (curl_exec($curl) === false) error_log('PostHog alias failed: ' . curl_error($curl));
        curl_close($curl);
    } catch (Throwable $e) {}
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
