<?php
// Public "request a tool / feature" endpoint for the static site.
// (The Next.js /api/tool-requests route does not exist in the static export,
// so the request modal posts here instead.) Delivers via Resend to
// hello@leaveittobumbum.com (overridable with 'tool_request_email' in the
// server config). Needs 'resend' => ['api_key' => 're_...'] in the config.
require __DIR__ . '/_bootstrap.php';

requirePost();
$input = body();

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
if (!$apiKey) jsonResponse(['error' => 'Tool requests are not configured yet.'], 503);

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
    jsonResponse(['error' => 'The request could not be sent.'], 500);
}
$attempts[] = ['ip' => $ip, 't' => $now];
@file_put_contents($throttleFile, json_encode(array_slice($attempts, -200)));
jsonResponse(['ok' => true]);
