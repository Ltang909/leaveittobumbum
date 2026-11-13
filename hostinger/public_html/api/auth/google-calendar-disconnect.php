<?php
declare(strict_types=1);
// Disconnects Google Calendar for the signed-in user (Day Log settings).
require __DIR__ . '/../_bootstrap.php';
require dirname(__DIR__, 2) . '/includes/google-calendar.php';

requirePost();
$input = body();
requireCsrf($input);
$user = currentUser();
if (!$user) jsonResponse(['error' => 'Sign in first.'], 401);
gcal_disconnect(db(), (int) $user['id']);
jsonResponse(['ok' => true]);
