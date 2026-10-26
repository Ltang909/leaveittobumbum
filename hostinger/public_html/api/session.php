<?php
require __DIR__ . '/_bootstrap.php';
// Never mints a guest: plain page views must not create guest rows for bots.
// Tool endpoints mint on first use via requireSubject().
$subject = currentSubject();
// The PostHog project key is public (it is printed into every PHP page by
// includes/analytics.php); exposing it here lets the static marketing pages
// send the same events the PHP pages send via bbTrack().
$phOut = null;
try {
    $phCfg = config()['posthog'] ?? [];
    $phKey = (string) ($phCfg['key'] ?? '');
    if ($phKey !== '' && $phKey !== 'phc_replace_me') {
        $phOut = ['key' => $phKey, 'host' => rtrim((string) ($phCfg['host'] ?? 'https://us.i.posthog.com'), '/')];
    }
} catch (Throwable $e) {}
if ($subject['kind'] === 'user') {
    $user = $subject['user'];
    jsonResponse([
        'authenticated' => true,
        'user' => $user,
        'is_admin' => isAdmin($user),
        'usage' => subjectUsage($subject),
        'guest' => null,
        'csrf' => csrfToken(),
        'posthog' => $phOut,
    ]);
}
if ($subject['kind'] === 'guest') {
    jsonResponse([
        'authenticated' => false,
        'user' => null,
        'is_admin' => false,
        'usage' => subjectUsage($subject),
        'guest' => ['id' => $subject['guest_id']],
        'csrf' => csrfToken(),
        'posthog' => $phOut,
    ]);
}
jsonResponse([
    'authenticated' => false,
    'user' => null,
    'is_admin' => false,
    'usage' => null,
    'guest' => null,
    'csrf' => csrfToken(),
    'posthog' => $phOut,
]);
