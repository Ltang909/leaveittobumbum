<?php
require dirname(__DIR__) . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
$duration = filter_var($input['durationSeconds'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 14400]]);
$idempotency = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
if ($duration === false) jsonResponse(['error' => 'Tell us how long the recording was, in seconds.'], 422);
if (strlen($idempotency) < 16 || strlen($idempotency) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
$count = consumeAction((int) $user['id'], (string) $user['plan'], periodKey($user), 'clips', $idempotency);
if (!empty($count['limit_reached'])) jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $count], 402);
jsonResponse(['usage' => usageFor($user), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
