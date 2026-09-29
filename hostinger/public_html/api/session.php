<?php
require __DIR__ . '/_bootstrap.php';
// Never mints a guest: plain page views must not create guest rows for bots.
// Tool endpoints mint on first use via requireSubject().
$subject = currentSubject();
if ($subject['kind'] === 'user') {
    $user = $subject['user'];
    jsonResponse([
        'authenticated' => true,
        'user' => $user,
        'is_admin' => isAdmin($user),
        'usage' => subjectUsage($subject),
        'guest' => null,
        'csrf' => csrfToken(),
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
    ]);
}
jsonResponse([
    'authenticated' => false,
    'user' => null,
    'is_admin' => false,
    'usage' => null,
    'guest' => null,
    'csrf' => csrfToken(),
]);
