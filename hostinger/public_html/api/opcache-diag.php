<?php
// TEMPORARY diagnostic: reports the signed-in user's email/admin state and
// whether PHP opcache is serving stale copies of key API files. Any signed-in
// user may open it; it reveals nothing about other users. Delete after use.
require __DIR__ . '/_bootstrap.php';
$user = requireUser();

$info = [
    'your_email' => $user['email'] ?? null,
    'is_admin' => isAdmin($user),
    'expected_admin' => 'hello@leaveittobumbum.com',
    'opcache' => [
        'enabled' => ini_get('opcache.enable'),
        'validate_timestamps' => ini_get('opcache.validate_timestamps'),
        'revalidate_freq' => ini_get('opcache.revalidate_freq'),
    ],
    'files' => [],
];
$status = function_exists('opcache_get_status') ? @opcache_get_status(true) : false;
foreach (['requests.php', '_bootstrap.php', 'tool-requests.php'] as $f) {
    $path = __DIR__ . '/' . $f;
    $mtime = file_exists($path) ? filemtime($path) : null;
    $cachedTs = ($status && isset($status['scripts'][$path])) ? $status['scripts'][$path]['timestamp'] : null;
    $info['files'][$f] = [
        'mtime_now' => $mtime ? date('c', $mtime) : null,
        'cached_mtime' => $cachedTs ? date('c', $cachedTs) : null,
        'stale' => ($cachedTs !== null && $mtime !== null) ? ($cachedTs < $mtime) : null,
    ];
}
// Force the suspects to recompile on their next request.
foreach (['requests.php', '_bootstrap.php'] as $f) {
    if (function_exists('opcache_invalidate')) @opcache_invalidate(__DIR__ . '/' . $f, true);
}
$info['invalidated_requests_and_bootstrap'] = true;
jsonResponse($info);
