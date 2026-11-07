<?php
declare(strict_types=1);
// Callback for the optional Google Calendar connection. Stores the
// refresh token against the signed-in account; never signs anyone in
// or out, and never creates an account.
require __DIR__ . '/../_bootstrap.php';
require dirname(__DIR__, 2) . '/includes/google-calendar.php';

function gcalFail(string $why): never {
    error_log('Google Calendar connect failed: ' . $why);
    header('Location: /tools/day-log/?gcal=error');
    exit;
}

startSecureSession();
$user = currentUser();
if (!$user) gcalFail('not signed in');
$state = (string) ($_GET['state'] ?? '');
$code = (string) ($_GET['code'] ?? '');
$expected = (string) ($_SESSION['gcal_state'] ?? '');
unset($_SESSION['gcal_state']);
if ($state === '' || $code === '' || $expected === '' || !hash_equals($expected, $state)) {
    gcalFail('bad state');
}
if (!gcal_configured()) gcalFail('not configured');

$cfg = config();
[$http, $tok] = gcal_http('https://oauth2.googleapis.com/token', [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => gcal_callback_url(),
        'client_id' => (string) ($cfg['google_client_id'] ?? ''),
        'client_secret' => (string) ($cfg['google_client_secret'] ?? ''),
    ]),
]);
$refresh = (string) ($tok['refresh_token'] ?? '');
if ($http !== 200 || $refresh === '') gcalFail('token exchange failed');

gcal_install(db());
db()->prepare('INSERT INTO user_google_calendar (user_id, refresh_token) VALUES (?, ?) ON DUPLICATE KEY UPDATE refresh_token = VALUES(refresh_token)')
    ->execute([(int) $user['id'], $refresh]);
header('Location: /tools/day-log/?gcal=connected');
exit;
