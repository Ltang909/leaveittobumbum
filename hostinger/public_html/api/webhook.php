<?php
require __DIR__ . '/_bootstrap.php';
$raw = file_get_contents('php://input') ?: '';
$signature = (string) ($_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '');
$secret = (string) config()['stripe']['webhook_secret'];

function validStripeSignature(string $payload, string $header, string $secret): bool {
    $parts = [];
    foreach (explode(',', $header) as $item) {
        [$key, $value] = array_pad(explode('=', trim($item), 2), 2, '');
        $parts[$key][] = $value;
    }
    $timestamp = (int) ($parts['t'][0] ?? 0);
    if (!$timestamp || abs(time() - $timestamp) > 300) return false;
    $expected = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);
    foreach ($parts['v1'] ?? [] as $candidate) if (hash_equals($expected, $candidate)) return true;
    return false;
}

if (!validStripeSignature($raw, $signature, $secret)) jsonResponse(['error' => 'Invalid signature.'], 400);
$event = json_decode($raw, true);
if (!is_array($event) || empty($event['id'])) jsonResponse(['error' => 'Invalid event.'], 400);
// Guard: staging shares the production DB. Ignore events whose live/test mode
// does not match this server's Stripe keys, so a test-mode checkout can never
// overwrite live billing IDs (or vice versa).
$keyLive = str_starts_with((string) (config()['stripe']['secret_key'] ?? ''), 'sk_live_');
$eventLive = !empty($event['livemode']);
if ($eventLive !== $keyLive) {
    error_log('webhook ignored mode mismatch: type=' . $event['type'] . ' id=' . $event['id'] . ' event_mode=' . ($eventLive ? 'live' : 'test') . ' key_mode=' . ($keyLive ? 'live' : 'test'));
    jsonResponse(['received' => true, 'ignored' => 'mode_mismatch']);
}
$pdo = db();
try {
    $pdo->prepare('INSERT INTO webhook_events (stripe_event_id, event_type) VALUES (?, ?)')->execute([$event['id'], $event['type']]);
} catch (PDOException $error) {
    if ((string) $error->getCode() === '23000') jsonResponse(['received' => true, 'duplicate' => true]);
    throw $error;
}

$object = $event['data']['object'] ?? [];
if ($event['type'] === 'checkout.session.completed') {
    $userId = (int) ($object['metadata']['user_id'] ?? 0);
    $plan = (string) ($object['metadata']['plan'] ?? 'free');
    if ($userId) db()->prepare('UPDATE users SET stripe_customer_id = ?, stripe_subscription_id = ?, plan = ?, subscription_status = ? WHERE id = ?')->execute([$object['customer'] ?? null, $object['subscription'] ?? null, $plan, 'active', $userId]);
    if ($userId && $plan !== 'free') posthogCapture('subscription_created', 'user_' . $userId, ['plan' => $plan]);
}
if (str_starts_with((string) $event['type'], 'customer.subscription.')) {
    $status = (string) ($object['status'] ?? 'none');
    $active = in_array($status, ['active', 'trialing', 'past_due'], true);
    $plan = $active ? (string) ($object['metadata']['plan'] ?? 'free') : 'free';
    $start = !empty($object['current_period_start']) ? gmdate('Y-m-d H:i:s', (int) $object['current_period_start']) : null;
    $end = !empty($object['current_period_end']) ? gmdate('Y-m-d H:i:s', (int) $object['current_period_end']) : null;
    db()->prepare('UPDATE users SET plan = ?, subscription_status = ?, period_start = ?, period_end = ? WHERE stripe_subscription_id = ? OR stripe_customer_id = ?')->execute([$plan, $status, $start, $end, $object['id'] ?? '', $object['customer'] ?? '']);
    if ($event['type'] === 'customer.subscription.deleted') {
        $row = db()->prepare('SELECT id FROM users WHERE stripe_subscription_id = ? OR stripe_customer_id = ?');
        $row->execute([$object['id'] ?? '', $object['customer'] ?? '']);
        if ($found = $row->fetch()) posthogCapture('subscription_cancelled', 'user_' . $found['id'], ['plan' => $plan]);
    }
}
jsonResponse(['received' => true]);
