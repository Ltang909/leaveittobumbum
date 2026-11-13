<?php
// PDF Merger API.
// Merging runs entirely in the visitor's browser (pdf-lib): PDF files are
// NEVER uploaded. This endpoint meters merges (1 action each).
require dirname(__DIR__) . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$subject = requireSubject();
$isGuest = $subject['kind'] === 'guest';
$action = (string) ($input['action'] ?? '');
if ($action === 'merge') {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) {
        jsonResponse(['error' => 'Invalid request identifier.'], 422);
    }
    $count = consumeSubjectAction($subject, 'pdf-merger', 'pdf-merge-' . $key);
    if (!empty($count['limit_reached'])) {
        limitReachedResponse($subject, $count);
    }
    jsonResponse(['ok' => true, 'usage' => subjectUsage($subject), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
}
jsonResponse(['error' => 'Unknown action.'], 400);
