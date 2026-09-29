<?php
// Purr Code API. QR codes are rendered entirely in the visitor's browser
// (canvas); this endpoint meters exports and manages tracked short links.
//
//   POST /api/tools/purr-code.php  action=download   text=<link>  idempotencyKey=<uuid>
//     -> { "ok": true, "usage": {...} }                       (1 action per export)
//
//   POST action=create_link  text=<https-url>  idempotencyKey=<uuid>
//     -> { "ok": true, "code": "...", "shortUrl": "...", "usage": {...} }
//     (1 action per tracked link; exports of a tracked QR are then free)
//
//   POST action=my_links  -> { "ok": true, "links": [{code, target_url, scans, shortUrl, created_at}] }
//
// Previewing is free and unlimited. Raw URLs are never fetched server-side,
// so there is no SSRF surface here. Tracked targets must be http(s) URLs
// because scanners are redirected to them.
require dirname(__DIR__) . '/_bootstrap.php';
require dirname(__DIR__, 2) . '/includes/purr-code-lib.php';

requirePost();
$input = body();
$action = (string) ($input['action'] ?? 'download');
requireCsrf($input);
$subject = requireSubject();

function purrcode_require_key(array $input): string {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
    return $key;
}

function purrcode_require_quota(array $subject): void {
    $pre = subjectUsage($subject);
    if (empty($pre['unlimited']) && ($pre['remaining'] ?? 0) <= 0) {
        limitReachedResponse($subject, $pre);
    }
}

if ($action === 'download') {
    $text = trim((string) ($input['text'] ?? ''));
    if ($text === '' || mb_strlen($text) > 2048) jsonResponse(['error' => 'Give me a link first.'], 422);
    $key = purrcode_require_key($input);
    purrcode_require_quota($subject);
    $count = consumeSubjectAction($subject, 'purr-code', $key);
    if (!empty($count['limit_reached'])) limitReachedResponse($subject, $count);
    jsonResponse([
        'ok' => true,
        'usage' => subjectUsage($subject),
        'duplicate' => (bool) ($count['duplicate'] ?? false),
    ]);
}

if ($action === 'create_link') {
    if (!purrcode_tables_ready()) {
        jsonResponse(['error' => 'Scan tracking is not set up yet.'], 503);
    }
    $text = trim((string) ($input['text'] ?? ''));
    if (!preg_match('#^https?://#i', $text) && preg_match('#^[a-z0-9.-]+\.[a-z]{2,}([/:?#]|$)#i', $text)) {
        $text = 'https://' . $text;
    }
    if (mb_strlen($text) > 2048 || !filter_var($text, FILTER_VALIDATE_URL) || !preg_match('#^https?://#i', $text)) {
        jsonResponse(['error' => 'Tracked codes need a real link starting with https://.'], 422);
    }
    $key = purrcode_require_key($input);
    purrcode_require_quota($subject);
    $link = purrcode_create_link($subject, $text);
    $count = consumeSubjectAction($subject, 'purr-code', $key);
    if (!empty($count['limit_reached'])) limitReachedResponse($subject, $count);
    jsonResponse([
        'ok' => true,
        'code' => $link['code'],
        'target' => $link['target'],
        'shortUrl' => $link['shortUrl'],
        'usage' => subjectUsage($subject),
        'duplicate' => (bool) ($count['duplicate'] ?? false),
    ]);
}

if ($action === 'my_links') {
    jsonResponse(['ok' => true, 'links' => purrcode_my_links($subject)]);
}

jsonResponse(['error' => 'Unknown action.'], 400);
