<?php
declare(strict_types=1);
// Starts OAuth sign-in ("Continue with Google / LinkedIn").
// GET params: provider=google|linkedin, optional next=/local/path
require __DIR__ . '/../_bootstrap.php';

$provider = (string) ($_GET['provider'] ?? '');
if (!oauthEnabled($provider)) {
    header('Location: /account/?oauth=unavailable');
    exit;
}
$p = oauthProviders()[$provider];

$next = (string) ($_GET['next'] ?? '/account/');
if (!str_starts_with($next, '/') || str_starts_with($next, '//')) $next = '/account/';

startSecureSession();
$state = bin2hex(random_bytes(16));
$_SESSION['oauth_state'] = $state;
$_SESSION['oauth_provider'] = $provider;
$_SESSION['oauth_next'] = $next;

$params = array_merge([
    'response_type' => 'code',
    'client_id' => $p['client_id'],
    'redirect_uri' => $p['callback'],
    'scope' => $p['scope'],
    'state' => $state,
], $p['extra_auth']);

header('Location: ' . $p['authorize'] . '?' . http_build_query($params));
exit;
