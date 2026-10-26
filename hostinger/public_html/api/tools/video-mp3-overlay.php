<?php
// Video MP3 Overlay API.
// The mix runs entirely in the visitor's browser (ffmpeg.wasm): the video and
// the MP3 are NEVER uploaded. This endpoint meters renders (1 action each).
require dirname(__DIR__) . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$subject = requireSubject();
$isGuest = $subject['kind'] === 'guest';
$action = (string) ($input['action'] ?? '');
if ($action === 'status') {
    jsonResponse(['ok' => true, 'usage' => subjectUsage($subject)]);
}
if ($action === 'render') {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) {
        jsonResponse(['error' => 'Invalid request identifier.'], 422);
    }
    $count = consumeSubjectAction($subject, 'video-mp3-overlay', 'video-mp3-overlay-' . $key);
    if (!empty($count['limit_reached'])) {
        limitReachedResponse($subject, $count);
    }
    jsonResponse(['ok' => true, 'usage' => subjectUsage($subject), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
}
jsonResponse(['error' => 'Unknown action.'], 400);
