<?php
require __DIR__ . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
if (!$user['stripe_customer_id']) jsonResponse(['error' => 'No billing account is connected yet.'], 409);
$params = ['customer' => $user['stripe_customer_id'], 'return_url' => config()['app_url'] . '/account/'];
$portalConfig = (string) (config()['stripe']['portal_configuration'] ?? '');
if ($portalConfig) $params['configuration'] = $portalConfig;
try {
    $session = stripeRequest('POST', '/v1/billing_portal/sessions', $params);
    jsonResponse(['url' => $session['url']]);
} catch (Throwable $error) {
    error_log($error->getMessage());
    jsonResponse(['error' => 'Billing management is unavailable right now.'], 502);
}
