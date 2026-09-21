<?php
// Custom tool requests (Operator plan). GET lists mine, POST submits a new one.
require __DIR__ . '/_bootstrap.php';
$user = requireUser();
$userId = (int) $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = db()->prepare('SELECT id, title, status, requested_at, deadline_at, delivered_at FROM custom_requests WHERE user_id = ? ORDER BY requested_at DESC LIMIT 20');
        $stmt->execute([$userId]);
        jsonResponse($stmt->fetchAll());
    } catch (PDOException $error) {
        jsonResponse(['error' => 'Requests are not set up yet.'], 503);
    }
}

requirePost();
$input = body();
requireCsrf($input);
if ($user['plan'] !== 'operator' || !in_array($user['subscription_status'], ['active', 'trialing', 'past_due'], true)) {
    jsonResponse(['error' => 'Custom tool requests are an Operator perk.'], 403);
}
$title = trim((string) ($input['title'] ?? ''));
$details = trim((string) ($input['details'] ?? ''));
if ($title === '' || mb_strlen($title) > 180) jsonResponse(['error' => 'Give the request a short name.'], 422);
if ($details === '' || mb_strlen($details) > 5000) jsonResponse(['error' => 'Describe the task and what done looks like.'], 422);
$period = periodKey($user);
try {
    $open = db()->prepare("SELECT id FROM custom_requests WHERE user_id = ? AND status = 'open' AND DATE_FORMAT(requested_at, '%Y-%m') = ? LIMIT 1");
    $open->execute([$userId, $period]);
    if ($open->fetch()) jsonResponse(['error' => 'One request per month. Your current one is still in progress.'], 409);
    $stmt = db()->prepare('INSERT INTO custom_requests (user_id, title, details, deadline_at) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 36 HOUR))');
    $stmt->execute([$userId, $title, $details]);
    $id = (int) db()->lastInsertId();
    $row = db()->prepare('SELECT id, title, status, requested_at, deadline_at FROM custom_requests WHERE id = ?');
    $row->execute([$id]);
    jsonResponse(['request' => $row->fetch()], 201);
} catch (PDOException $error) {
    error_log('Request submit failed: ' . $error->getMessage());
    jsonResponse(['error' => 'Requests are not set up yet.'], 503);
}
