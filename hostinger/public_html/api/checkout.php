<?php
// The 36-hour Promise checkout: one scoped tool request, $49 one-time,
// paid upfront when the request is sent (Leon's call, 2026-11-23).
// Creates the custom_requests row first (status pending_payment), then a
// hosted Stripe Checkout session carrying the price inline, so no
// pre-created Stripe price or server config entry is needed. The webhook
// marks the request paid when checkout.session.completed lands; the
// 36-hour clock itself starts later, when scope is agreed (admin action
// in requests.php). Refund rule, per the site copy: if the request cannot
// be scoped, or the 36 hours are missed, the $49 comes back.
require __DIR__ . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
$bill = billingUser($user);
$billId = (int) $bill['id'];
ensureCustomRequestTables();

$requestId = (int) ($input['request_id'] ?? 0);
if ($requestId > 0) {
    // Resuming payment for an existing unpaid Promise request (e.g. the
    // first Stripe session expired). Only its owner, only while unpaid.
    $stmt = db()->prepare("SELECT id, title, details FROM custom_requests WHERE id = ? AND user_id = ? AND payment_status IN ('pending', 'expired')");
    $stmt->execute([$requestId, $billId]);
    $row = $stmt->fetch();
    if (!$row) jsonResponse(['error' => 'That request is not waiting for payment.'], 404);
    $title = (string) $row['title'];
} else {
    $title = trim((string) ($input['title'] ?? ''));
    $details = trim((string) ($input['details'] ?? ''));
    if ($title === '' || mb_strlen($title) > 180) jsonResponse(['error' => 'Give the request a short name.'], 422);
    if ($details === '' || mb_strlen($details) > 5000) jsonResponse(['error' => 'Describe the task and what done looks like.'], 422);
    $stmt = db()->prepare("INSERT INTO custom_requests (user_id, title, details, status, payment_status, deadline_at) VALUES (?, ?, ?, 'pending_payment', 'pending', NULL)");
    $stmt->execute([$billId, $title, $details]);
    $requestId = (int) db()->lastInsertId();
}

$params = [
    'mode' => 'payment',
    'line_items' => [[
        'price_data' => [
            'currency' => 'usd',
            'unit_amount' => 4900,
            'product_data' => [
                'name' => 'The 36-Hour Promise: one scoped tool request',
                'description' => 'One scoped custom tool, built within 36 hours of agreed scope. If we cannot scope it, or we miss the 36 hours, the $49 is refunded.',
            ],
        ],
        'quantity' => 1,
    ]],
    'success_url' => config()['app_url'] . '/checkout/complete.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => config()['app_url'] . '/account/?checkout=cancelled#requests',
    'metadata' => ['kind' => 'promise', 'request_id' => (string) $requestId, 'user_id' => (string) $billId],
    'payment_intent_data' => ['metadata' => ['kind' => 'promise', 'request_id' => (string) $requestId, 'user_id' => (string) $billId]],
    'integration_identifier' => 'litbb_web_qkzmpvra',
];
if (!empty($bill['stripe_customer_id'])) $params['customer'] = $bill['stripe_customer_id'];
else $params['customer_email'] = (string) ($bill['email'] ?? $user['email'] ?? '');

try {
    $session = stripeRequest('POST', '/v1/checkout/sessions', $params);
    db()->prepare("UPDATE custom_requests SET stripe_session_id = ?, payment_status = 'pending', status = 'pending_payment' WHERE id = ?")->execute([(string) ($session['id'] ?? ''), $requestId]);
    jsonResponse(['url' => (string) ($session['url'] ?? ''), 'request_id' => $requestId]);
} catch (Throwable $error) {
    error_log('Promise checkout failed for request ' . $requestId . ': ' . $error->getMessage());
    jsonResponse(['error' => 'Checkout is unavailable right now. Your request is saved; you can pay from your account page.'], 502);
}
