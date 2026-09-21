<?php
declare(strict_types=1);

const PLAN_LIMITS = ['free' => 75, 'helper' => 1500, 'operator' => 6000];

function config(): array {
    static $config;
    if ($config) return $config;
    $path = getenv('LITBB_CONFIG') ?: dirname((string) $_SERVER['DOCUMENT_ROOT']) . '/leaveittobumbum-config.php';
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

function usageFor(array $user): array {
    $period = periodKey($user);
    $limit = PLAN_LIMITS[$user['plan']] ?? PLAN_LIMITS['free'];
    $stmt = db()->prepare('SELECT used_actions FROM usage_periods WHERE user_id = ? AND period_key = ?');
    $stmt->execute([$user['id'], $period]);
    $used = (int) ($stmt->fetchColumn() ?: 0);
    return ['period' => $period, 'used' => $used, 'limit' => $limit, 'remaining' => max(0, $limit - $used)];
}

function consumeAction(int $userId, string $plan, string $period, string $tool, string $idempotency): array {
    $limit = PLAN_LIMITS[$plan] ?? PLAN_LIMITS['free'];
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $existing = $pdo->prepare('SELECT id FROM action_ledger WHERE user_id = ? AND idempotency_key = ?');
        $existing->execute([$userId, $idempotency]);
        if ($existing->fetch()) {
            $pdo->commit();
            return ['counted' => false, 'duplicate' => true];
        }
        $upsert = $pdo->prepare('INSERT INTO usage_periods (user_id, period_key, used_actions, included_actions) VALUES (?, ?, 0, ?) ON DUPLICATE KEY UPDATE included_actions = VALUES(included_actions)');
        $upsert->execute([$userId, $period, $limit]);
        $lock = $pdo->prepare('SELECT used_actions FROM usage_periods WHERE user_id = ? AND period_key = ? FOR UPDATE');
        $lock->execute([$userId, $period]);
        $used = (int) $lock->fetchColumn();
        if ($used >= $limit) {
            $pdo->rollBack();
            return ['counted' => false, 'limit_reached' => true, 'used' => $used, 'limit' => $limit];
        }
        $pdo->prepare('INSERT INTO action_ledger (user_id, period_key, tool_key, idempotency_key) VALUES (?, ?, ?, ?)')->execute([$userId, $period, $tool, $idempotency]);
        $pdo->prepare('UPDATE usage_periods SET used_actions = used_actions + 1 WHERE user_id = ? AND period_key = ?')->execute([$userId, $period]);
        $pdo->commit();
        return ['counted' => true, 'used' => $used + 1, 'limit' => $limit];
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
