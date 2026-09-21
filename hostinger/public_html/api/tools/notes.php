<?php
require dirname(__DIR__) . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
$chars = filter_var($input['transcriptChars'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
$idempotency = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
if ($chars === false) jsonResponse(['error' => 'Invalid transcript length.'], 422);
if (strlen($idempotency) < 16 || strlen($idempotency) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
$count = consumeAction((int) $user['id'], (string) $user['plan'], periodKey($user), 'notes', $idempotency);
if (!empty($count['limit_reached'])) jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $count], 402);
jsonResponse(['usage' => usageFor($user), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
