<?php
// Custom tool requests (Operator plan). GET lists the billing account's
// requests, POST submits a new one against the billing account's monthly slot.
// Team members draw on the team owner's Operator perk and shared allowance.
// Every Operator request is also mirrored into the public community queue,
// flagged as an operator request, so everyone can follow along.
require __DIR__ . '/_bootstrap.php';
$user = requireUser();
$bill = billingUser($user);
$billId = (int) $bill['id'];
ensureCustomRequestTables();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = db()->prepare('SELECT id, title, status, requested_at, deadline_at, delivered_at FROM custom_requests WHERE user_id = ? ORDER BY requested_at DESC LIMIT 20');
        $stmt->execute([$billId]);
        jsonResponse($stmt->fetchAll());
    } catch (PDOException $error) {
        error_log('Request list failed: ' . $error->getMessage());
        $detail = isAdmin($user) ? ' Admin detail: ' . $error->getMessage() : '';
        jsonResponse(['error' => 'Requests are not set up yet.' . $detail], 503);
    }
}

requirePost();
$input = body();
requireCsrf($input);
if ($bill['plan'] !== 'operator' || !in_array($bill['subscription_status'], ['active', 'trialing', 'past_due'], true)) {
    jsonResponse(['error' => 'Custom tool requests are an Operator perk.'], 403);
}
$title = trim((string) ($input['title'] ?? ''));
$details = trim((string) ($input['details'] ?? ''));
if ($title === '' || mb_strlen($title) > 180) jsonResponse(['error' => 'Give the request a short name.'], 422);
if ($details === '' || mb_strlen($details) > 5000) jsonResponse(['error' => 'Describe the task and what done looks like.'], 422);
$period = periodKey($bill);
try {
    $open = db()->prepare("SELECT id FROM custom_requests WHERE user_id = ? AND status = 'open' COLLATE utf8mb4_unicode_ci AND DATE_FORMAT(requested_at, '%Y-%m') = ? LIMIT 1");
    $open->execute([$billId, $period]);
    if ($open->fetch()) jsonResponse(['error' => 'One request per month. The current one is still in progress.'], 409);
    $stmt = db()->prepare('INSERT INTO custom_requests (user_id, title, details, deadline_at) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 36 HOUR))');
    $stmt->execute([$billId, $title, $details]);
    $id = (int) db()->lastInsertId();
    // Mirror into the public community queue with the operator flag. This is
    // best-effort: a mirror failure must never block the real request.
    try {
        ensureToolRequestTables();
        $mirror = db()->prepare('INSERT INTO tool_requests (name, email, problem, outcome, status, is_operator) VALUES (?, ?, ?, ?, ?, 1)');
        $mirror->execute(['', (string) ($user['email'] ?? ''), $title, $details, 'requested']);
    } catch (Throwable $mirrorError) {
        error_log('Operator queue mirror failed: ' . $mirrorError->getMessage());
    }
    $row = db()->prepare('SELECT id, title, status, requested_at, deadline_at FROM custom_requests WHERE id = ?');
    $row->execute([$id]);
    jsonResponse(['request' => $row->fetch()], 201);
} catch (PDOException $error) {
    error_log('Request submit failed: ' . $error->getMessage());
    $detail = isAdmin($user) ? ' Admin detail: ' . $error->getMessage() : '';
    jsonResponse(['error' => 'Requests are not set up yet.' . $detail], 503);
}
