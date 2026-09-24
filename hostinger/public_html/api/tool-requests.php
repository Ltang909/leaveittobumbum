<?php
// Public "request a tool / feature" endpoint for the static site.
// (The Next.js /api/tool-requests route does not exist in the static export,
// so the request modal posts here instead.) Delivers via Resend to
// hello@leaveittobumbum.com (overridable with 'tool_request_email' in the
// server config). Needs 'resend' => ['api_key' => 're_...'] in the config.
require __DIR__ . '/_bootstrap.php';

function ensureToolRequestTables(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS tool_requests (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL DEFAULT '',
        email VARCHAR(190) NOT NULL DEFAULT '',
        problem TEXT NOT NULL,
        outcome TEXT NOT NULL,
        status ENUM('requested','planned','building','shipped','completed','cancelled') NOT NULL DEFAULT 'requested',
        votes INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        KEY idx_status_votes (status, votes)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    db()->exec("CREATE TABLE IF NOT EXISTS tool_request_votes (
        request_id INT UNSIGNED NOT NULL,
        voter_key VARCHAR(80) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (request_id, voter_key)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    // Tables created before completed/cancelled existed get the wider enum.
    try {
        $col = db()->query("SHOW COLUMNS FROM tool_requests LIKE 'status'")->fetch();
        if ($col && strpos((string) $col['Type'], "'completed'") === false) {
            db()->exec("ALTER TABLE tool_requests MODIFY status ENUM('requested','planned','building','shipped','completed','cancelled') NOT NULL DEFAULT 'requested'");
        }
    } catch (Throwable $e) { error_log('tool_requests status enum migration failed: ' . $e->getMessage()); }
}

// Public queue: anyone can read the anonymized list of requested tools.
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && ($_GET['action'] ?? '') === 'list') {
    ensureToolRequestTables();
    $rows = db()->query("SELECT id, problem, outcome, status, votes, created_at FROM tool_requests ORDER BY votes DESC, created_at DESC LIMIT 200")->fetchAll();
    jsonResponse(['requests' => $rows ?: []]);
}

requirePost();
$input = body();
ensureToolRequestTables();

// Upvote a request: one vote per signed-in user, or one per IP for guests.
if (($input['action'] ?? '') === 'vote') {
    $requestId = (int) ($input['request_id'] ?? 0);
    if ($requestId <= 0) jsonResponse(['error' => 'Pick a request to vote for.'], 400);
    $user = currentUser();
    $ip = (string) ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $ip = trim(explode(',', $ip)[0]);
    $voterKey = $user ? 'u:' . (int) $user['id'] : 'a:' . sha1($ip);
    try {
        $stmt = db()->prepare('INSERT IGNORE INTO tool_request_votes (request_id, voter_key) VALUES (?, ?)');
        $stmt->execute([$requestId, $voterKey]);
        if ($stmt->rowCount() > 0) {
            db()->prepare('UPDATE tool_requests SET votes = votes + 1 WHERE id = ?')->execute([$requestId]);
        }
    } catch (Throwable $e) {
        jsonResponse(['error' => 'Voting is taking a nap. Try again soon.'], 500);
    }
    $votes = (int) db()->query('SELECT votes FROM tool_requests WHERE id = ' . $requestId)->fetchColumn();
    jsonResponse(['ok' => true, 'votes' => $votes]);
}

// Admin only: change a request's status (requested, planned, building,
// shipped, completed, cancelled).
if (($input['action'] ?? '') === 'set_status') {
    requireAdmin(currentUser());
    requireCsrf($input);
    $requestId = (int) ($input['request_id'] ?? 0);
    $status = (string) ($input['status'] ?? '');
    $allowed = ['requested', 'planned', 'building', 'shipped', 'completed', 'cancelled'];
    if ($requestId <= 0 || !in_array($status, $allowed, true)) jsonResponse(['error' => 'Pick a valid status.'], 422);
    $stmt = db()->prepare('UPDATE tool_requests SET status = ? WHERE id = ?');
    $stmt->execute([$status, $requestId]);
    if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Request not found.'], 404);
    jsonResponse(['ok' => true, 'status' => $status]);
}

// Honeypot: bots that fill the hidden field get a fake success.
if (!empty($input['website'])) jsonResponse(['ok' => true]);

// Light per-IP throttle: 5 requests/hour.
$ip = (string) ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown');
$ip = trim(explode(',', $ip)[0]);
$throttleFile = sys_get_temp_dir() . '/bb_tool_requests.json';
$attempts = is_file($throttleFile) ? (json_decode((string) file_get_contents($throttleFile), true) ?: []) : [];
$now = time();
$attempts = array_values(array_filter($attempts, fn($a) => $now - (int) ($a['t'] ?? 0) < 3600));
$ipCount = 0;
foreach ($attempts as $a) if (($a['ip'] ?? '') === $ip) $ipCount++;
if ($ipCount >= 5) jsonResponse(['error' => 'Too many requests. Try again later.'], 429);

$name = trim((string) ($input['name'] ?? ''));
$email = trim((string) ($input['email'] ?? ''));
$problem = trim((string) ($input['problem'] ?? ''));
$outcome = trim((string) ($input['outcome'] ?? ''));
if ($name === '' || $email === '' || $problem === '' || $outcome === '') jsonResponse(['error' => 'Please complete every field.'], 400);
if (mb_strlen($name) > 120 || mb_strlen($email) > 190 || mb_strlen($problem) > 5000 || mb_strlen($outcome) > 5000) jsonResponse(['error' => 'One of the fields is too long.'], 400);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) jsonResponse(['error' => 'Enter a valid email.'], 400);

try {
    $cfg = config();
} catch (Throwable $e) {
    $cfg = [];
}
$apiKey = $cfg['resend']['api_key'] ?? $cfg['resend_api_key'] ?? null;
$to = $cfg['tool_request_email'] ?? 'hello@leaveittobumbum.com';

// Duplicate guard: same email + same problem text within 5 minutes = retry.
// Checked before inserting so a retried submit does not create a second row.
try {
    $dup = db()->prepare("SELECT id FROM tool_requests WHERE email = ? AND problem = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) LIMIT 1");
    $dup->execute([$email, $problem]);
    if ($dup->fetchColumn()) {
        $attempts[] = ['ip' => $ip, 't' => $now];
        @file_put_contents($throttleFile, json_encode(array_slice($attempts, -200)));
        jsonResponse(['ok' => true, 'duplicate' => true]);
    }
} catch (Throwable $e) {
    error_log('Tool request dedupe check failed: ' . $e->getMessage());
}

// Save to the community queue first: storage is the source of truth, and
// the notification email is best-effort. A failed email must never look like
// a lost request.
try {
    $stmt = db()->prepare('INSERT INTO tool_requests (name, email, problem, outcome) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $email, $problem, $outcome]);
} catch (Throwable $e) {
    error_log('Tool request DB save failed: ' . $e->getMessage());
    jsonResponse(['error' => 'Your request could not be saved. Try again in a moment.'], 500);
}

if (!$apiKey) {
    $attempts[] = ['ip' => $ip, 't' => $now];
    @file_put_contents($throttleFile, json_encode(array_slice($attempts, -200)));
    jsonResponse(['ok' => true, 'email' => 'pending']);
}

$ch = curl_init('https://api.resend.com/emails');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $apiKey, 'Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode([
        'from' => 'Bum Bum <requests@leaveittobumbum.com>',
        'to' => [$to],
        'reply_to' => $email,
        'subject' => 'Tool request from ' . mb_substr($name, 0, 80),
        'text' => "Name: $name\nEmail: $email\n\nAnnoying task:\n$problem\n\nDone looks like:\n$outcome",
    ]),
]);
$resp = curl_exec($ch);
$status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
$curlErr = curl_error($ch);
curl_close($ch);
if ($resp === false || $status < 200 || $status >= 300) {
    error_log('Tool request email failed: ' . ($curlErr ?: $status . ' ' . substr((string) $resp, 0, 200)));
    $attempts[] = ['ip' => $ip, 't' => $now];
    @file_put_contents($throttleFile, json_encode(array_slice($attempts, -200)));
    jsonResponse(['ok' => true, 'email' => 'pending']);
}
$attempts[] = ['ip' => $ip, 't' => $now];
@file_put_contents($throttleFile, json_encode(array_slice($attempts, -200)));
jsonResponse(['ok' => true]);
