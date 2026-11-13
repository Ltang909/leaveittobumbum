<?php
// PDF Compressor API.
// Compression runs entirely in the visitor's browser (pdf.js + pdf-lib):
// PDF files are NEVER uploaded. This endpoint meters compressions
// (1 action per PDF), and the page calls it only after the rebuilt PDF
// turned out smaller than the original.
require dirname(__DIR__) . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$subject = requireSubject();
$isGuest = $subject['kind'] === 'guest';
$action = (string) ($input['action'] ?? '');
if ($action === 'compress') {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) {
        jsonResponse(['error' => 'Invalid request identifier.'], 422);
    }
    $count = consumeSubjectAction($subject, 'pdf-compressor', 'pdf-compress-' . $key);
    if (!empty($count['limit_reached'])) {
        limitReachedResponse($subject, $count);
    }
    jsonResponse(['ok' => true, 'usage' => subjectUsage($subject), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
}
jsonResponse(['error' => 'Unknown action.'], 400);
