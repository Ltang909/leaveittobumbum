<?php
// Email Signature Maker export: one action on the first copy or download of a visit.
require dirname(__DIR__) . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$subject = requireSubject();
$isGuest = $subject['kind'] === 'guest';
$idempotency = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
if (strlen($idempotency) < 16 || strlen($idempotency) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
$count = consumeSubjectAction($subject, 'email-signature-generator', $idempotency);
if (!empty($count['limit_reached'])) limitReachedResponse($subject, $count);
jsonResponse(['usage' => subjectUsage($subject), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
