<?php
// Image Converter API.
// Conversion runs entirely in the visitor's browser (canvas): image files are
// NEVER uploaded. This endpoint meters conversions (1 action each).
require dirname(__DIR__) . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
$bill = billingUser($user);
$action = (string) ($input['action'] ?? '');
if ($action === 'convert') {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) {
        jsonResponse(['error' => 'Invalid request identifier.'], 422);
    }
    $count = consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), 'image-converter', 'image-convert-' . $key);
    if (!empty($count['limit_reached'])) {
        jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $count], 402);
    }
    jsonResponse(['ok' => true, 'usage' => usageFor($bill), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
}
jsonResponse(['error' => 'Unknown action.'], 400);
