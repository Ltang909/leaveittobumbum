<?php
// Streams a saved note's recording. Session-authenticated (the <audio> tag
// sends cookies); the entry must belong to the signed-in user. Files live
// outside the web root so they are never directly reachable by URL.
require dirname(__DIR__) . '/_bootstrap.php';

$user = requireUser();
$userId = (int) $user['id'];
$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) jsonResponse(['error' => 'Missing id.'], 400);

$stmt = db()->prepare('SELECT id FROM notes_entries WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $userId]);
if (!$stmt->fetch()) jsonResponse(['error' => 'Note not found.'], 404);

$docroot = (string) $_SERVER['DOCUMENT_ROOT'];
$base = isStagingHost() ? dirname($docroot, 2) : dirname($docroot);
$matches = glob($base . '/notes-audio/' . $userId . '_' . $id . '.*') ?: [];
if (!$matches) jsonResponse(['error' => 'No audio for this note.'], 404);
$path = $matches[0];

$size = filesize($path);
$mime = (new finfo(FILEINFO_MIME_TYPE))->file($path) ?: 'audio/webm';
$start = 0;
$end = $size - 1;
$status = 200;
if (isset($_SERVER['HTTP_RANGE']) && preg_match('/bytes=(\d*)-(\d*)/', (string) $_SERVER['HTTP_RANGE'], $m)) {
    $start = $m[1] === '' ? max(0, $size - (int) $m[2]) : (int) $m[1];
    $end = $m[2] === '' ? $size - 1 : min((int) $m[2], $size - 1);
    if ($start <= $end && $start < $size) {
        $status = 206;
        header("Content-Range: bytes $start-$end/$size");
    } else {
        $start = 0;
        $end = $size - 1;
    }
}
http_response_code($status);
header('Content-Type: ' . $mime);
header('Content-Length: ' . ($end - $start + 1));
header('Accept-Ranges: bytes');
header('Cache-Control: private, max-age=86400');
$fp = fopen($path, 'rb');
if (!$fp) jsonResponse(['error' => 'Could not read audio.'], 500);
fseek($fp, $start);
$remaining = $end - $start + 1;
while ($remaining > 0 && !feof($fp)) {
    $chunk = fread($fp, min(8192, $remaining));
    if ($chunk === false) break;
    echo $chunk;
    $remaining -= strlen($chunk);
}
fclose($fp);
