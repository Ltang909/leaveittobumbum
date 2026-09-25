<?php
require __DIR__ . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
$plan = (string) ($input['plan'] ?? '');
if (!in_array($plan, ['helper', 'operator'], true)) jsonResponse(['error' => 'Choose a valid plan.'], 422);
// Never stack a second subscription: paid users switch plans from the account page.
$own = db()->prepare('SELECT plan, subscription_status, stripe_subscription_id FROM users WHERE id = ?');
$own->execute([(int) $user['id']]);
$ownRow = $own->fetch() ?: [];
if (!empty($ownRow['stripe_subscription_id']) && in_array((string) ($ownRow['subscription_status'] ?? ''), ['active', 'trialing', 'past_due'], true)) {
    jsonResponse(['error' => (string) ($ownRow['plan'] ?? '') === $plan ? 'You are already on ' . ucfirst($plan) . '.' : 'You already have an active subscription. Switch plans from your account page instead of checking out again.'], 422);
}
$stripe = config()['stripe'];
$price = $plan === 'helper' ? $stripe['helper_price'] : $stripe['operator_price'];
$params = [
    'mode' => 'subscription',
    'ui_mode' => 'embedded_page',
    'line_items' => [['price' => $price, 'quantity' => 1]],
    'return_url' => config()['app_url'] . '/checkout/complete.php?session_id={CHECKOUT_SESSION_ID}',
    'allow_promotion_codes' => 'true',
    'metadata' => ['user_id' => (string) $user['id'], 'plan' => $plan],
    'subscription_data' => ['metadata' => ['user_id' => (string) $user['id'], 'plan' => $plan]],
    'integration_identifier' => 'litbb_web_qkzmpvra',
];
if ($user['stripe_customer_id']) $params['customer'] = $user['stripe_customer_id'];
else $params['customer_email'] = $user['email'];
try {
    $session = stripeRequest('POST', '/v1/checkout/sessions', $params);
    jsonResponse(['clientSecret' => $session['client_secret']]);
} catch (Throwable $error) {
    error_log($error->getMessage());
    jsonResponse(['error' => 'Checkout is unavailable right now.'], 502);
}
