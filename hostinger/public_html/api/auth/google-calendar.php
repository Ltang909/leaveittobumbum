<?php
declare(strict_types=1);
// Starts the optional Google Calendar connection for Day Log. Separate
// from sign-in: asks only for the calendar.events scope, with offline
// access so the morning cron can create the top-list event.
require __DIR__ . '/../_bootstrap.php';
require dirname(__DIR__, 2) . '/includes/google-calendar.php';

startSecureSession();
$user = currentUser();
if (!$user) {
    header('Location: /account/?next=' . urlencode('/tools/day-log/'));
    exit;
}
if (!gcal_configured()) {
    header('Location: /tools/day-log/?gcal=unavailable');
    exit;
}

$state = bin2hex(random_bytes(16));
$_SESSION['gcal_state'] = $state;
$params = [
    'response_type' => 'code',
    'client_id' => (string) (config()['google_client_id'] ?? ''),
    'redirect_uri' => gcal_callback_url(),
    'scope' => 'https://www.googleapis.com/auth/calendar.events',
    'access_type' => 'offline',
    'prompt' => 'consent',
    'include_granted_scopes' => 'true',
    'state' => $state,
];
header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params));
exit;
