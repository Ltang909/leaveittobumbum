<?php
// Stat Cat: begin Google OAuth2. The user must be signed in to Bum Bum;
// the OAuth state is bound to their user id so one user cannot complete
// another user's flow.
//
//   GET /api/stat-cat-oauth-start.php  -> 302 to Google
require dirname(__DIR__) . '/includes/stat-cat-lib.php';

function statcat_tool_url_safe(): string {
    try { return statcat_tool_url() . '?error=not_configured'; }
    catch (Throwable $e) { return '/tools/stat-cat/?error=not_configured'; }
}

$user = currentUser();
if (!$user) {
    header('Location: /account/?next=' . urlencode('/tools/stat-cat/'));
    exit;
}
if (!statcat_oauth_configured()) {
    header('Location: ' . statcat_tool_url_safe());
    exit;
}

[$clientId] = statcat_oauth_client();
startSecureSession();
$state = bin2hex(random_bytes(24));
$_SESSION['statcat_oauth'] = [
    'state' => $state,
    'user_id' => (int) $user['id'],
    'exp' => time() + 600,
];

$params = [
    'client_id' => $clientId,
    'redirect_uri' => statcat_redirect_uri(),
    'response_type' => 'code',
    'scope' => STATCAT_SCOPES,
    'access_type' => 'offline',   // we need a refresh token
    'prompt' => 'consent',        // consent every time so Google re-issues one
    'state' => $state,
];
header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params));
exit;
