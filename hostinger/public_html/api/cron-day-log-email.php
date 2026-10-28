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

$tz = new DateTimeZone('America/Toronto');
$now = new DateTime('now', $tz);
$today = $now->format('Y-m-d');
$dow = (int) $now->format('N'); // 1 = Monday, 7 = Sunday
$hour = (int) $now->format('G');

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

function dle_body(array $structured, string $dateLabel): string {
    $lines = ['Your day, filed: ' . $dateLabel, ''];
    $section = function (string $title, array $items) use (&$lines) {
        if (!count($items)) return;
        $lines[] = $title;
        foreach ($items as $i) $lines[] = '- ' . $i;
        $lines[] = '';
    };
    $refl = $structured['reflection'] ?? [];
    if (count($refl)) {
        $lines[] = 'YESTERDAY\'S LIST, SCORED';
        foreach ($refl as $r) {
            $tag = $r['status'] === 'done' ? 'done' : ($r['status'] === 'partial' ? 'partial' : 'not mentioned');
            $lines[] = '- [' . $tag . '] ' . $r['item'];
        }
        $lines[] = '';
    }
    $section('DONE TODAY', $structured['done'] ?? []);
    $section('SET OUT BUT DID NOT HAPPEN', $structured['missed'] ?? []);
    $section('CHALLENGES', $structured['challenges'] ?? []);
    $section('BEATING THEM', $structured['overcome'] ?? []);
    $section('TOMORROW\'S TOP LIST', $structured['tomorrow'] ?? []);
    $section('LEARNINGS', $structured['learnings'] ?? []);
    $lines[] = 'Filed with Day Log on Leave It To Bum Bum.';
    $lines[] = 'We keep your words, never your voice: recordings are transcribed, then discarded.';
    return implode("\n", $lines);
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
    $latestStmt->execute([$userId, $today]);
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

    $decisions[] = ['user_id' => $userId, 'action' => $action, 'log_date' => $latestDate];
    $counts[$action === 'skip' ? 'skip' : $action]++;

    if (!$sendMode) continue;

    if ($action === 'recap') {
        $dateLabel = date('D M j', strtotime($latestDate));
        $res = bb_send_mail($email, 'Your day, filed: ' . $dateLabel, dle_body($structured, $dateLabel));
        if (!empty($res['ok'])) {
            $saveState->execute([$userId, $latestDate, $today, $st['last_nudge_on']]);
        }
    } elseif ($action === 'nudge') {
        $res = bb_send_mail($email, 'Bum Bum kept your seat warm', dle_nudge_body());
        if (!empty($res['ok'])) {
            $saveState->execute([$userId, $st['last_recap_log_date'], $st['last_recap_sent_on'], $today]);
        }
    } elseif ($action === 'armed') {
        $saveState->execute([$userId, $latestDate, $today, $today]);
    }
}

jsonResponse([
    'ok' => true,
    'mode' => $sendMode ? 'send' : 'dry-run',
    'toronto_now' => $now->format('Y-m-d H:i'),
    'counts' => $counts,
    'decisions' => $decisions,
]);
