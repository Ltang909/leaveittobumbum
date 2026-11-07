<?php
declare(strict_types=1);
// Manual test for the Day Log Google Calendar connection: creates the
// all-day top-list event for tomorrow from the user's most recent log,
// without touching the per-day delivery stamp, so the real recap
// morning flow is unaffected.
require __DIR__ . '/../_bootstrap.php';
require dirname(__DIR__, 2) . '/includes/google-calendar.php';

requirePost();
$input = body();
requireCsrf($input);
$user = currentUser();
if (!$user) jsonResponse(['error' => 'Sign in first.'], 401);

$userId = (int) $user['id'];
$stmt = db()->prepare('SELECT structured FROM day_logs WHERE user_id = ? ORDER BY log_date DESC LIMIT 1');
$stmt->execute([$userId]);
$row = $stmt->fetch();
$structured = $row ? json_decode((string) $row['structured'], true) : null;
$topList = is_array($structured) && is_array($structured['tomorrow'] ?? null) ? $structured['tomorrow'] : [];

$tomorrow = (new DateTime('tomorrow', new DateTimeZone('America/Toronto')))->format('Y-m-d');
$status = gcal_create_top_list_event(db(), $userId, $tomorrow, $topList, false);
jsonResponse(['ok' => true, 'status' => $status, 'event_date' => $tomorrow]);
