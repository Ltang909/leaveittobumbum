<?php
// Purr Code API. QR codes are rendered entirely in the visitor's browser
// (canvas); this endpoint only meters downloads, one action each. Previewing
// is free and unlimited. No URL is ever fetched server-side, so there is no
// SSRF surface here.
//
//   POST /api/tools/purr-code.php  action=download  text=<link>  idempotencyKey=<uuid>
//   -> { "ok": true, "usage": {...} }
require dirname(__DIR__) . '/_bootstrap.php';

requirePost();
$input = body();
$action = (string) ($input['action'] ?? 'download');
requireCsrf($input);
$subject = requireSubject();

if ($action !== 'download') jsonResponse(['error' => 'Unknown action.'], 400);
$text = trim((string) ($input['text'] ?? ''));
if ($text === '' || mb_strlen($text) > 2048) jsonResponse(['error' => 'Give me a link first.'], 422);
$key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
if (strlen($key) < 16 || strlen($key) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
$pre = subjectUsage($subject);
if (empty($pre['unlimited']) && ($pre['remaining'] ?? 0) <= 0) {
    limitReachedResponse($subject, $pre);
}
$count = consumeSubjectAction($subject, 'purr-code', $key);
if (!empty($count['limit_reached'])) limitReachedResponse($subject, $count);
jsonResponse([
    'ok' => true,
    'usage' => subjectUsage($subject),
    'duplicate' => (bool) ($count['duplicate'] ?? false),
]);
