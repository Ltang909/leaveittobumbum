<?php
// TEMPORARY staging diagnostic: checks whether the signed-in admin's Stripe
// IDs in the shared DB resolve in TEST mode. If they do, production's live
// keys cannot see them, which explains portal + plan-change 502s in prod.
// Read-only. Tombstone with HTTP 410 after use.
require __DIR__ . '/_bootstrap.php';
$user = requireUser();
requireAdmin($user);

$stmt = db()->prepare('SELECT plan, subscription_status, stripe_customer_id, stripe_subscription_id FROM users WHERE id = ?');
$stmt->execute([(int) $user['id']]);
$me = $stmt->fetch() ?: [];

function maskId($v) {
    $v = (string) ($v ?? '');
    if ($v === '') return '(empty)';
    if (strlen($v) <= 16) return substr($v, 0, 4) . '...';
    return substr($v, 0, 12) . '...' . substr($v, -4);
}

$subId = (string) ($me['stripe_subscription_id'] ?? '');
$testLookup = 'skipped';
if ($subId !== '') {
    try {
        $sub = stripeRequest('GET', '/v1/subscriptions/' . $subId);
        $testLookup = 'FOUND in test mode (status=' . ($sub['status'] ?? '?') . ')';
    } catch (Throwable $e) {
        $testLookup = 'not found in test mode: ' . $e->getMessage();
    }
}

jsonResponse([
    'plan' => (string) ($me['plan'] ?? ''),
    'subscription_status' => (string) ($me['subscription_status'] ?? ''),
    'stripe_customer_id' => maskId($me['stripe_customer_id'] ?? ''),
    'stripe_subscription_id' => maskId($subId),
    'test_mode_lookup' => $testLookup,
]);
