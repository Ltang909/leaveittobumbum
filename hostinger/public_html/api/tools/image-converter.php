<?php
// Image Converter API.
// Conversion runs entirely in the visitor's browser (canvas): image files are
// NEVER uploaded. This endpoint meters conversions (1 action each).
require dirname(__DIR__) . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$subject = requireSubject();
$isGuest = $subject['kind'] === 'guest';
$action = (string) ($input['action'] ?? '');
if ($action === 'convert') {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) {
        jsonResponse(['error' => 'Invalid request identifier.'], 422);
    }
    $count = consumeSubjectAction($subject, 'image-converter', 'image-convert-' . $key);
    if (!empty($count['limit_reached'])) {
        limitReachedResponse($subject, $count);
    }
    jsonResponse(['ok' => true, 'usage' => subjectUsage($subject), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
}
jsonResponse(['error' => 'Unknown action.'], 400);
