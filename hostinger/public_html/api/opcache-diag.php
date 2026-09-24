<?php
// TEMPORARY diagnostic: inspects PHP opcache state and force-invalidates
// specific API files. Admin only. Delete after use.
require __DIR__ . '/_bootstrap.php';
requireAdmin(requireUser());

$files = ['requests.php', '_bootstrap.php', 'tool-requests.php'];
$info = [
    'opcache_enabled' => ini_get('opcache.enable'),
    'validate_timestamps' => ini_get('opcache.validate_timestamps'),
    'revalidate_freq' => ini_get('opcache.revalidate_freq'),
    'php_version' => PHP_VERSION,
];
foreach ($files as $f) {
    $path = __DIR__ . '/' . $f;
    $info[$f] = [
        'exists' => file_exists($path),
        'mtime' => file_exists($path) ? date('c', filemtime($path)) : null,
        'cached' => function_exists('opcache_is_script_cached') ? opcache_is_script_cached($path) : null,
    ];
}
// Force-invalidate the two suspect files so the next request compiles fresh.
foreach (['requests.php', '_bootstrap.php'] as $f) {
    $path = __DIR__ . '/' . $f;
    $info[$f]['invalidated'] = function_exists('opcache_invalidate') ? @opcache_invalidate($path, true) : null;
    $info[$f]['cached_after'] = function_exists('opcache_is_script_cached') ? opcache_is_script_cached($path) : null;
}
jsonResponse($info);
