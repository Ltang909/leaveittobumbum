<?php
// Emoji Mosaic export: one action per downloaded or copied mosaic.
// The mosaic is built entirely in the visitor's browser (canvas): the photo
// is NEVER uploaded. This endpoint only meters the export.
require dirname(__DIR__) . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$subject = requireSubject();
$isGuest = $subject['kind'] === 'guest';
$idempotency = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
if (strlen($idempotency) < 16 || strlen($idempotency) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
$count = consumeSubjectAction($subject, 'emoji-mosaic', $idempotency);
if (!empty($count['limit_reached'])) limitReachedResponse($subject, $count);
jsonResponse(['usage' => subjectUsage($subject), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
