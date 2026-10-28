<?php
// Day Log morning emails. The rules (Leon's spec, Oct 28):
//   - Emails only ever start after a user has filed a log. No log, no loop.
//   - Sends happen on weekday mornings only (Mon-Fri, America/Toronto).
//   - The recap covers the user's most recent un-emailed log dated before
//     today, so Monday morning's run recaps Friday's log.
//   - If there is no new log to recap, the user gets ONE nudge. If they
//     still have not logged by that evening, no more emails go out until
//     they log again (the next recap re-arms the single nudge).
//
// The recap wears the Monday note's layout (card sections, same shell)
// and leads with today's top list. Subject: "Your Day at a Glance:
// <today's date>".
//
// Call: GET /api/cron-day-log-email.php?secret=<cron_secret>
//   (no params)        dry run: reports who would get what, sends nothing
//   &send=1            real run, gated to Mon-Fri 07:00-10:59 Toronto time
//   &send=1&only=EMAIL test run for one account, bypasses the time gate
//
// HOSTINGER CRON: schedule this hourly; the endpoint itself decides
// whether this is a send morning. NEVER schedule it on staging:
// staging shares the production database and would email real users.
require __DIR__ . '/tools/cutline.php';
require dirname(__DIR__) . '/includes/smtp-mail.php';

$secret = (string) ($_GET['secret'] ?? '');
$expected = (string) (config()['cron_secret'] ?? '');
if ($expected === '' || $expected === 'replace_me' || !hash_equals($expected, $secret)) {
    jsonResponse(['error' => 'Forbidden.'], 403);
}
$sendMode = (string) ($_GET['send'] ?? '') === '1';
if ($sendMode && !bb_mail_configured()) {
    jsonResponse(['error' => 'Mail is not configured on this server.'], 503);
}
$onlyEmail = strtolower(trim((string) ($_GET['only'] ?? '')));
// Test mode (only=) also previews today's log and never writes state,
// so a test send does not disturb the real weekday-morning loop.
$testMode = $onlyEmail !== '';

$tz = new DateTimeZone('America/Toronto');
$now = new DateTime('now', $tz);
$today = $now->format('Y-m-d');
$dow = (int) $now->format('N'); // 1 = Monday, 7 = Sunday
$hour = (int) $now->format('G');
$latestCutoff = $testMode ? date('Y-m-d', strtotime($today . ' +1 day')) : $today;

if ($sendMode && $onlyEmail === '') {
    if ($dow > 5 || $hour < 7 || $hour > 10) {
        jsonResponse(['ok' => true, 'sent' => 0, 'note' => 'Outside the weekday morning send window (Toronto).', 'toronto_now' => $now->format('Y-m-d H:i')]);
    }
}

$pdo = db();
$pdo->exec("CREATE TABLE IF NOT EXISTS day_log_email_state (
    user_id BIGINT UNSIGNED PRIMARY KEY,
    last_recap_log_date DATE NULL,
    last_recap_sent_on DATE NULL,
    last_nudge_on DATE NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

/* ---------- The recap email, in the Monday note's layout ---------- */

function dlm_shell(string $headline, string $innerHtml): string {
    $appUrl = rtrim((string) (config()['app_url'] ?? 'https://leaveittobumbum.com'), '/');
    $style = '<style>'
        . 'body{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;}'
        . 'table{border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;}'
        . '@media only screen and (max-width:640px){'
        . '.em-outer{padding:16px 6px !important;}'
        . '.em-pad{padding-left:16px !important;padding-right:16px !important;}'
        . '.em-cardpad{padding:14px 14px 10px 14px !important;}'
        . '.em-headline{font-size:26px !important;}'
        . '}'
        . '@media (prefers-color-scheme: dark){'
        . '.em-outer{background:#f6f0df !important;}'
        . '.em-shell{background:#ffffff !important;}'
        . '.em-card{background:#fdfaf1 !important;}'
        . '.em-ink{color:#142015 !important;}'
        . '.em-mute{color:#667064 !important;}'
        . '.em-chip-lime{background:#d8ff62 !important;}'
        . '}'
        . '</style>';
    return '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
    . '<meta name="color-scheme" content="light"><meta name="supported-color-schemes" content="light">'
    . $style . '</head>'
    . '<body style="margin:0;padding:0;background:#f6f0df;">'
    . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="em-outer" style="background:#f6f0df;padding:28px 12px;"><tr><td align="center">'
    . '<table role="presentation" width="600" cellpadding="0" cellspacing="0" class="em-shell" style="max-width:600px;width:100%;background:#ffffff;border:2px solid #1a291c;border-radius:22px;">'
    . '<tr><td class="em-pad" style="padding:28px 32px 8px 32px;" align="center">'
    . '<img src="' . $appUrl . '/bum-bum.png" width="104" alt="Bum Bum" style="display:block;border:0;">'
    . '<div class="em-mute" style="font-family:Arial,sans-serif;font-size:11px;font-weight:800;letter-spacing:2px;color:#667064;text-transform:uppercase;padding-top:10px;">Leave It To Bum Bum</div>'
    . '<div class="em-headline em-ink" style="font-family:Georgia,serif;font-size:30px;font-weight:900;color:#142015;line-height:1.15;padding-top:6px;">' . $headline . '</div>'
    . '</td></tr>'
    . $innerHtml
    . '<tr><td class="em-pad" style="padding:20px 32px 28px 32px;">'
    . '<div class="em-mute" style="border-top:2px dashed #1a291c;padding-top:16px;font-family:Arial,sans-serif;font-size:12px;line-height:1.6;color:#667064;" align="center">'
    . 'These recaps follow your own Day Log filings, on weekday mornings. Stop filing and they stop.<br>'
    . 'Leave It To Bum Bum · Aurora, ON L4G, Canada · <a href="mailto:hello@leaveittobumbum.com" style="color:#667064;">hello@leaveittobumbum.com</a>'
    . '</div></td></tr>'
    . '</table></td></tr></table></body></html>';
}

function dlm_card(string $title, string $subline, string $rowsHtml): string {
    $body = '<div class="em-ink" style="font-family:Arial,sans-serif;font-size:13px;font-weight:800;letter-spacing:1.5px;color:#142015;text-transform:uppercase;padding-bottom:4px;">' . $title . '</div>';
    if ($subline !== '') {
        $body .= '<div class="em-mute" style="font-family:Arial,sans-serif;font-size:13px;color:#667064;padding-bottom:2px;">' . $subline . '</div>';
    }
    $body .= $rowsHtml;
    return '<tr><td class="em-pad" style="padding:16px 32px 0 32px;">'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="em-card" style="background:#fdfaf1;border:2px solid #1a291c;border-radius:18px;"><tr><td class="em-cardpad" style="padding:18px 20px 14px 20px;">'
        . $body
        . '</td></tr></table></td></tr>';
}

function dlm_bullet_rows(array $items): string {
    $h = '';
    foreach ($items as $i) {
        $h .= '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #ddd3b8;"><tr>'
            . '<td class="em-ink" style="padding:10px 0;font-family:Arial,sans-serif;font-size:15px;line-height:1.5;color:#142015;">'
            . htmlspecialchars((string) $i, ENT_QUOTES, 'UTF-8') . '</td></tr></table>';
    }
    return $h;
}

function dlm_score_rows(array $refl): string {
    $map = [
        'done' => ['Done', '#d8ff62', 'em-chip-lime'],
        'partial' => ['Partial', '#f2e3b3', ''],
        'not_mentioned' => ['Not mentioned', '#e9e2cd', ''],
    ];
    $h = '';
    foreach ($refl as $r) {
        [$label, $bg, $cls] = $map[(string) ($r['status'] ?? '')] ?? $map['not_mentioned'];
        $pill = '<span class="' . $cls . ' em-ink" style="display:inline-block;white-space:nowrap;background:' . $bg . ';border:2px solid #1a291c;border-radius:999px;padding:3px 10px;font-family:Arial,sans-serif;font-size:12px;font-weight:800;color:#142015;">' . $label . '</span>';
        $h .= '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #ddd3b8;"><tr>'
            . '<td class="em-ink" style="padding:10px 8px 10px 0;font-family:Arial,sans-serif;font-size:15px;line-height:1.5;color:#142015;">'
            . htmlspecialchars((string) ($r['item'] ?? ''), ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td align="right" width="132" style="padding:10px 0;">' . $pill . '</td></tr></table>';
    }
    return $h;
}

function dlm_render(array $s, string $logDate, string $today): array {
    $appUrl = rtrim((string) (config()['app_url'] ?? 'https://leaveittobumbum.com'), '/');
    $logLabel = date('D M j', strtotime($logDate));
    $intro = 'Here is your log from ' . $logLabel . ', with today\'s top list up first.';
    $html = '<tr><td class="em-pad" style="padding:16px 32px 0 32px;"><div class="em-ink" style="font-family:Arial,sans-serif;font-size:16px;line-height:1.55;color:#142015;">'
        . $intro . '</div></td></tr>';
    $text = $intro . "\n";

    $sections = [];
    if (!empty($s['tomorrow'])) $sections[] = ['Today\'s Top List', 'What you said you\'d do yesterday', dlm_bullet_rows($s['tomorrow']), 'TODAY\'S TOP LIST', $s['tomorrow']];
    if (!empty($s['done'])) $sections[] = ['Done Yesterday', '', dlm_bullet_rows($s['done']), 'DONE YESTERDAY', $s['done']];
    if (!empty($s['reflection'])) $sections[] = ['Yesterday\'s List, Scored', '', dlm_score_rows($s['reflection']), 'YESTERDAY\'S LIST, SCORED', $s['reflection']];
    if (!empty($s['missed'])) $sections[] = ['Set Out But Did Not Happen', '', dlm_bullet_rows($s['missed']), 'SET OUT BUT DID NOT HAPPEN', $s['missed']];
    if (!empty($s['challenges'])) $sections[] = ['Yesterday\'s Challenges', '', dlm_bullet_rows($s['challenges']), 'YESTERDAY\'S CHALLENGES', $s['challenges']];
    if (!empty($s['overcome'])) $sections[] = ['Beating Them', '', dlm_bullet_rows($s['overcome']), 'BEATING THEM', $s['overcome']];
    if (!empty($s['learnings'])) $sections[] = ['Yesterday\'s Learnings', '', dlm_bullet_rows($s['learnings']), 'YESTERDAY\'S LEARNINGS', $s['learnings']];

    foreach ($sections as [$title, $subline, $rowsHtml, $textTitle, $items]) {
        $html .= dlm_card($title, $subline, $rowsHtml);
        $text .= "\n" . $textTitle . ($subline !== '' ? ' (' . $subline . ')' : '') . "\n";
        foreach ($items as $i) {
            if (is_array($i)) {
                $tag = $i['status'] === 'done' ? 'done' : ($i['status'] === 'partial' ? 'partial' : 'not mentioned');
                $text .= '- [' . $tag . '] ' . $i['item'] . "\n";
            } else {
                $text .= '- ' . $i . "\n";
            }
        }
    }

    $html .= '<tr><td class="em-pad" style="padding:18px 32px 4px 32px;" align="center">'
        . '<a class="em-chip-lime em-ink" href="' . $appUrl . '/tools/day-log/" style="display:inline-block;background:#d8ff62;border:2px solid #1a291c;border-radius:999px;padding:10px 20px;font-family:Arial,sans-serif;font-size:14px;font-weight:900;color:#142015;text-decoration:none;">Open Day Log</a></td></tr>';
    $text .= "\nOpen Day Log: " . $appUrl . "/tools/day-log/\n"
        . "We keep your words, never your voice: recordings are transcribed, then discarded.\n";

    return [
        'subject' => 'Your Day at a Glance: ' . date('D M j', strtotime($today)),
        'html' => dlm_shell('Your day at a glance.', $html),
        'text' => $text,
    ];
}

function dle_nudge_body(): string {
    return implode("\n", [
        'Morning.',
        '',
        'No day log yesterday, so there is no recap this morning. File tonight and the weekday morning recaps pick right back up.',
        '',
        'File tonight: https://leaveittobumbum.com/tools/day-log/',
        '',
        'This is the only nudge. No log, no more emails.',
        '',
        'Filed with Day Log on Leave It To Bum Bum.',
    ]);
}

// Multipart (text + HTML) send over the same SMTP settings as
// includes/smtp-mail.php. Mirrors the weekly recap's sender, including
// the base64 HTML part (raw long-line HTML gets folded by SMTP near
// 1000 chars, which once split a closing tag and broke the layout).
function dlm_send_mail(string $to, string $subject, string $html, string $text): array {
    $mail = config()['mail'] ?? [];
    $host = (string) ($mail['host'] ?? '');
    $port = (int) ($mail['port'] ?? 587);
    $smtpUser = (string) ($mail['user'] ?? '');
    $smtpPass = (string) ($mail['pass'] ?? '');
    $fromEmail = (string) ($mail['from_email'] ?? $smtpUser);
    $fromName = (string) ($mail['from_name'] ?? 'Bum Bum');
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) return [false, 'invalid recipient'];
    $subject = trim(preg_replace('/[\r\n]+/', ' ', $subject));
    $boundary = 'bb_' . bin2hex(random_bytes(12));
    $body = "--{$boundary}\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n" . $text
        . "\r\n--{$boundary}\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n"
        . chunk_split(base64_encode($html))
        . "--{$boundary}--";
    $fp = @stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, 15);
    if (!$fp) return [false, "connect failed: {$errstr} ({$errno})"];
    stream_set_timeout($fp, 15);
    $expect = function (array $codes) use ($fp): array {
        $line = '';
        while (($l = fgets($fp, 1024)) !== false) { $line .= $l; if (preg_match('/^\d{3} /', $l)) break; }
        return [in_array((int) substr($line, 0, 3), $codes, true), trim($line)];
    };
    $cmd = function (string $c, array $codes) use ($fp, $expect): array { fwrite($fp, $c . "\r\n"); return $expect($codes); };
    $fail = function (string $m) use ($fp): array { fclose($fp); return [false, $m]; };
    [$ok, $g] = $expect([220]); if (!$ok) return $fail("greeting failed: {$g}");
    $ehlo = gethostname() ?: 'localhost';
    [$ok, $m] = $cmd("EHLO {$ehlo}", [250]); if (!$ok) return $fail("EHLO failed: {$m}");
    [$ok, $m] = $cmd('STARTTLS', [220]); if (!$ok) return $fail("STARTTLS failed: {$m}");
    if (!@stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) return $fail('TLS negotiation failed');
    [$ok, $m] = $cmd("EHLO {$ehlo}", [250]); if (!$ok) return $fail("EHLO (TLS) failed: {$m}");
    [$ok, $m] = $cmd('AUTH LOGIN', [334]); if (!$ok) return $fail("AUTH LOGIN rejected: {$m}");
    [$ok, $m] = $cmd(base64_encode($smtpUser), [334]); if (!$ok) return $fail('SMTP username rejected');
    [$ok, $m] = $cmd(base64_encode($smtpPass), [235]); if (!$ok) return $fail('SMTP authentication failed');
    [$ok, $m] = $cmd("MAIL FROM:<{$fromEmail}>", [250]); if (!$ok) return $fail("MAIL FROM rejected: {$m}");
    [$ok, $m] = $cmd("RCPT TO:<{$to}>", [250, 251]); if (!$ok) return $fail("RCPT TO rejected: {$m}");
    [$ok, $m] = $cmd('DATA', [354]); if (!$ok) return $fail("DATA rejected: {$m}");
    $headers = "From: {$fromName} <{$fromEmail}>\r\nTo: {$to}\r\nSubject: {$subject}\r\n"
        . "MIME-Version: 1.0\r\nContent-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n"
        . 'Date: ' . date('r') . "\r\n";
    $safe = preg_replace('/^\./m', '..', $headers . "\r\n" . $body);
    fwrite($fp, $safe . "\r\n.\r\n");
    [$ok, $m] = $expect([250]);
    $cmd('QUIT', [221]);
    fclose($fp);
    if (!$ok) return [false, "message rejected: {$m}"];
    return [true, ''];
}

/* ---------- Decisions ---------- */

try {
    $users = $pdo->query("SELECT id, email FROM users WHERE email <> '' AND id IN (SELECT DISTINCT user_id FROM day_logs) ORDER BY id")->fetchAll();
} catch (Throwable $e) {
    jsonResponse(['ok' => true, 'sent' => 0, 'note' => 'No day logs exist yet.']);
}

$stateStmt = $pdo->prepare('SELECT last_recap_log_date, last_recap_sent_on, last_nudge_on FROM day_log_email_state WHERE user_id = ?');
$latestStmt = $pdo->prepare('SELECT log_date, structured FROM day_logs WHERE user_id = ? AND log_date < ? ORDER BY log_date DESC LIMIT 1');
$saveState = $pdo->prepare('INSERT INTO day_log_email_state (user_id, last_recap_log_date, last_recap_sent_on, last_nudge_on) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE last_recap_log_date = VALUES(last_recap_log_date), last_recap_sent_on = VALUES(last_recap_sent_on), last_nudge_on = VALUES(last_nudge_on)');

$counts = ['recap' => 0, 'nudge' => 0, 'armed' => 0, 'skip' => 0];
$decisions = [];

foreach ($users as $u) {
    $userId = (int) $u['id'];
    $email = (string) $u['email'];
    if ($onlyEmail !== '' && strtolower($email) !== $onlyEmail) continue;

    $stateStmt->execute([$userId]);
    $st = $stateStmt->fetch() ?: ['last_recap_log_date' => null, 'last_recap_sent_on' => null, 'last_nudge_on' => null];
    $latestStmt->execute([$userId, $latestCutoff]);
    $latest = $latestStmt->fetch();
    if (!$latest) { $counts['skip']++; continue; }
    $latestDate = (string) $latest['log_date'];
    $structured = json_decode((string) $latest['structured'], true);
    if (!is_array($structured)) $structured = [];

    $action = 'skip';
    if ($st['last_recap_log_date'] === null) {
        // First contact: recap only if the log is fresh (inside the Fri-Mon
        // gap). Older logs arm the loop silently so nobody gets a stale
        // recap or a nudge pile-up on day one.
        if ($latestDate >= date('Y-m-d', strtotime($today . ' -4 days'))) {
            $action = 'recap';
        } else {
            $action = 'armed';
        }
    } elseif ($latestDate > (string) $st['last_recap_log_date']) {
        $action = 'recap';
    } elseif ($st['last_nudge_on'] === null || (string) $st['last_nudge_on'] < (string) $st['last_recap_sent_on']) {
        $action = 'nudge';
    }

    $entry = ['user_id' => $userId, 'action' => $action, 'log_date' => $latestDate];
    $counts[$action === 'skip' ? 'skip' : $action]++;

    if ($sendMode) {
        if ($action === 'recap') {
            $mail = dlm_render($structured, $latestDate, $today);
            $entry['subject'] = $mail['subject'];
            [$sent, $err] = dlm_send_mail($email, $mail['subject'], $mail['html'], $mail['text']);
            $entry['sent'] = $sent;
            if (!$sent) $entry['error'] = $err;
            if ($sent && !$testMode) {
                $saveState->execute([$userId, $latestDate, $today, $st['last_nudge_on']]);
            }
        } elseif ($action === 'nudge') {
            $res = bb_send_mail($email, 'Bum Bum kept your seat warm', dle_nudge_body());
            $entry['sent'] = !empty($res['ok']);
            if (!empty($res['ok']) && !$testMode) {
                $saveState->execute([$userId, $st['last_recap_log_date'], $st['last_recap_sent_on'], $today]);
            }
        } elseif ($action === 'armed' && !$testMode) {
            $saveState->execute([$userId, $latestDate, $today, $today]);
        }
    }
    $decisions[] = $entry;
}

jsonResponse([
    'ok' => true,
    'mode' => $sendMode ? 'send' : 'dry-run',
    'toronto_now' => $now->format('Y-m-d H:i'),
    'counts' => $counts,
    'decisions' => $decisions,
]);
