<?php
// Temporary diagnostic: why does ltang9090@gmail.com still resolve as admin?
// Public; exposes only booleans and mtimes. Tombstone after use.
require __DIR__ . '/_bootstrap.php';
$cfg = null;
try { $cfg = config()['admin_emails'] ?? null; } catch (Throwable $e) {}
$list = adminEmails();
$bs = __DIR__ . '/_bootstrap.php';
$cachedTs = null;
$ocEnabled = false;
if (function_exists('opcache_get_status')) {
    $st = @opcache_get_status(false);
    if (is_array($st)) {
        $ocEnabled = true;
        if (!empty($st['scripts'][$bs]['timestamp'])) $cachedTs = $st['scripts'][$bs]['timestamp'];
    }
}
header('Content-Type: application/json');
echo json_encode([
    'config_override_set' => is_array($cfg) && count($cfg) > 0,
    'gmail_is_admin' => in_array('ltang9090@gmail.com', $list, true),
    'hello_is_admin' => in_array('hello@leaveittobumbum.com', $list, true),
    'bootstrap_mtime' => @filemtime($bs),
    'bootstrap_cached_mtime' => $cachedTs,
    'opcache_enabled' => $ocEnabled,
    'file_has_revert' => strpos((string) @file_get_contents($bs), 'ltang9090@gmail.com') === false,
]);
