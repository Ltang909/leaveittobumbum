<?php
// Receipt Reader API: OCR runs entirely in the visitor's browser (Tesseract.js),
// so this endpoint only meters the scan itself. One action per scan.
require dirname(__DIR__) . '/_bootstrap.php';

requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
$bill = billingUser($user);

$action = (string) ($input['action'] ?? '');
if ($action === 'scan') {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) {
        jsonResponse(['error' => 'Invalid request identifier.'], 422);
    }
    $count = consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), 'receipt-reader', 'receipt-scan-' . $key);
    if (!empty($count['limit_reached'])) {
        jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $count], 402);
    }
    jsonResponse(['ok' => true, 'usage' => usageFor($bill), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
}

jsonResponse(['error' => 'Unknown action.'], 400);
