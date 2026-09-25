<?php
// Switch a paid subscription between Helper and Operator via Stripe's
// subscription-update API. Proration is automatic: upgrades charge the
// prorated difference and downgrades credit unused time, both applied to
// the next invoice. The billing cycle date does not move.
require __DIR__ . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();

// Only the billing owner can change the plan. Team members manage nothing here.
if (teamMembership((int) $user['id'])) jsonResponse(['error' => 'Plan changes happen on your team owner\'s account, not yours.'], 403);

$target = (string) ($input['plan'] ?? '');
if (!in_array($target, ['helper', 'operator'], true)) jsonResponse(['error' => 'Choose a valid plan.'], 422);

$stmt = db()->prepare('SELECT id, plan, subscription_status, stripe_customer_id, stripe_subscription_id FROM users WHERE id = ?');
$stmt->execute([(int) $user['id']]);
$me = $stmt->fetch() ?: [];
$current = (string) ($me['plan'] ?? 'free');
$subId = (string) ($me['stripe_subscription_id'] ?? '');
$status = (string) ($me['subscription_status'] ?? '');

if ($target === $current) jsonResponse(['error' => 'You are already on ' . ucfirst($target) . '.'], 422);
if ($subId === '' || !in_array($status, ['active', 'trialing', 'past_due'], true)) {
    jsonResponse(['error' => 'No active subscription to switch. Subscribe first, then switch plans any time.'], 422);
}

$stripe = config()['stripe'];
$newPrice = $target === 'helper' ? (string) $stripe['helper_price'] : (string) $stripe['operator_price'];
if ($newPrice === '') jsonResponse(['error' => 'Plan pricing is not configured.'], 500);

try {
    $sub = stripeRequest('GET', '/v1/subscriptions/' . $subId);
    $items = $sub['items']['data'] ?? [];
    if (count($items) !== 1 || empty($items[0]['id'])) {
        jsonResponse(['error' => 'Your subscription looks unusual. Please use Manage billing instead.'], 422);
    }
    $itemId = (string) $items[0]['id'];
    if ((string) ($items[0]['price']['id'] ?? '') === $newPrice) {
        jsonResponse(['error' => 'You are already on ' . ucfirst($target) . '.'], 422);
    }
    $updated = stripeRequest('POST', '/v1/subscriptions/' . $subId, [
        'items[0][id]' => $itemId,
        'items[0][price]' => $newPrice,
        'proration_behavior' => 'create_prorations',
        'metadata[plan]' => $target,
    ]);
} catch (Throwable $error) {
    error_log('plan-change failed for user ' . $user['id'] . ': ' . $error->getMessage());
    jsonResponse(['error' => 'The plan switch did not go through. Please try again or use Manage billing.'], 502);
}

// Stripe fires customer.subscription.updated and the webhook syncs the same
// values, but update the row now so the account page reflects the new plan
// immediately instead of waiting on the webhook.
$newStatus = (string) ($updated['status'] ?? $status);
db()->prepare('UPDATE users SET plan = ?, subscription_status = ? WHERE id = ?')->execute([$target, $newStatus, $user['id']]);

$note = '';
if ($target === 'helper') {
    $seats = teamSeats((int) $user['id'], 'helper');
    if ($seats['used'] > $seats['limit']) {
        $note = ' Heads up: you have ' . $seats['used'] . ' team members but Helper includes ' . $seats['limit'] . ' seats. Remove extras from the Team panel when you are ready.';
    }
}
posthogCapture('plan_changed', 'user_' . $user['id'], ['from' => $current, 'to' => $target]);
jsonResponse([
    'ok' => true,
    'plan' => $target,
    'message' => $target === 'operator'
        ? 'Switched to Operator. The prorated difference lands on your next bill.' . $note
        : 'Switched to Helper. Unused Operator time becomes credit on your next bill.' . $note,
]);
