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

// Stored datetimes are UTC; the browser parses bare "YYYY-MM-DD HH:MM:SS"
// as local time, which skews countdowns by the UTC offset. Emit ISO 8601.
function isoUtc(?string $dt): ?string {
    if (!$dt) return null;
    try { return (new DateTime((string) $dt, new DateTimeZone('UTC')))->format('c'); }
    catch (Throwable $e) { return (string) $dt; }
}
function requestRow(array $r): array {
    foreach (['requested_at', 'deadline_at', 'delivered_at'] as $k) {
        if (array_key_exists($k, $r)) $r[$k] = isoUtc($r[$k]);
    }
    return $r;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        ensureToolRequestTables();
        $stmt = db()->prepare('SELECT cr.id, cr.title, cr.status, cr.requested_at, cr.deadline_at, cr.delivered_at, tr.status AS queue_status FROM custom_requests cr LEFT JOIN tool_requests tr ON tr.id = cr.queue_id WHERE cr.user_id = ? ORDER BY cr.requested_at DESC LIMIT 20');
        $stmt->execute([$billId]);
        $monthStart = periodKey($bill) . '-01 00:00:00';
        $used = db()->prepare('SELECT id FROM custom_requests WHERE user_id = ? AND requested_at >= ? LIMIT 1');
        $used->execute([$billId, $monthStart]);
        jsonResponse(['requests' => array_map('requestRow', $stmt->fetchAll()), 'slot_used' => (bool) $used->fetch()]);
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
$monthStart = periodKey($bill) . '-01 00:00:00';
try {
    // NOTE: compare requested_at as a datetime range, never via
    // DATE_FORMAT(requested_at, ...) = ?. DATE_FORMAT() on a column returns
    // utf8mb4_general_ci here, which clashes with the connection collation.
    $open = db()->prepare("SELECT id FROM custom_requests WHERE user_id = ? AND status = 'open' COLLATE utf8mb4_unicode_ci AND requested_at >= ? LIMIT 1");
    $open->execute([$billId, $monthStart]);
    if ($open->fetch()) jsonResponse(['error' => 'One request per month. The current one is still in progress.'], 409);
    // Monthly slot already used (previous request delivered or credited):
    // accept it into the community queue only, with no 36-hour promise.
    $prior = db()->prepare('SELECT id FROM custom_requests WHERE user_id = ? AND requested_at >= ? LIMIT 1');
    $prior->execute([$billId, $monthStart]);
    if ($prior->fetch()) {
        ensureToolRequestTables();
        $queue = db()->prepare('INSERT INTO tool_requests (name, email, problem, outcome, status, is_operator) VALUES (?, ?, ?, ?, ?, 0)');
        $queue->execute(['', (string) ($user['email'] ?? ''), $title, $details, 'requested']);
        jsonResponse(['queued' => true, 'id' => (int) db()->lastInsertId()], 201);
    }
    $stmt = db()->prepare('INSERT INTO custom_requests (user_id, title, details, deadline_at) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 36 HOUR))');
    $stmt->execute([$billId, $title, $details]);
    $id = (int) db()->lastInsertId();
    // Mirror into the public community queue with the operator flag. This is
    // best-effort: a mirror failure must never block the real request.
    try {
        ensureToolRequestTables();
        $mirror = db()->prepare('INSERT INTO tool_requests (name, email, problem, outcome, status, is_operator) VALUES (?, ?, ?, ?, ?, 1)');
        $mirror->execute(['', (string) ($user['email'] ?? ''), $title, $details, 'requested']);
        // Link the mirror to the custom request so admin queue statuses can
        // propagate back to the account page.
        $queueId = (int) db()->lastInsertId();
        if ($queueId > 0) {
            db()->prepare('UPDATE custom_requests SET queue_id = ? WHERE id = ?')->execute([$queueId, $id]);
        }
    } catch (Throwable $mirrorError) {
        error_log('Operator queue mirror failed: ' . $mirrorError->getMessage());
    }
    $row = db()->prepare('SELECT id, title, status, requested_at, deadline_at FROM custom_requests WHERE id = ?');
    $row->execute([$id]);
    jsonResponse(['request' => requestRow($row->fetch())], 201);
} catch (PDOException $error) {
    error_log('Request submit failed: ' . $error->getMessage());
    $detail = isAdmin($user) ? ' Admin detail: ' . $error->getMessage() : '';
    jsonResponse(['error' => 'Requests are not set up yet.' . $detail], 503);
}
