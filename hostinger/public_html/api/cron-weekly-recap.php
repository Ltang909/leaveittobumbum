<?php
// Weekly recap email. One email per registered user, Monday morning
// (America/Toronto), gathering everything their trackers say is coming
// due that week: subscription renewals (Cutline), open invoices (Invoice
// Chaser), client follow-ups (Purrsuit) and job follow-ups (Corporate
// Bum Bum). Quiet weeks get a short "all quiet" note instead, at most
// once every two weeks, rotating through six variants.
//
// Call: GET /api/cron-weekly-recap.php?secret=<cron_secret>
//   (no params)        dry run: reports who would get what, sends nothing
//   &send=1            real run, gated to Monday 07:00-10:59 Toronto time
//   &send=1&only=EMAIL test run for one account, bypasses the time gate
//
// HOSTINGER CRON: schedule this daily; the endpoint itself decides
// whether today is a send morning. NEVER schedule it on staging:
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
$dow = (int) $now->format('N'); // 1 = Monday
$monday = (clone $now)->modify('-' . ($dow - 1) . ' days')->format('Y-m-d');
$sunday = (clone $now)->modify('+' . (7 - $dow) . ' days')->format('Y-m-d');
$weekLabel = $now->format('M j') . ' to ' . (clone $now)->modify('+' . (7 - $dow) . ' days')->format('M j');

if ($sendMode && $onlyEmail === '') {
    $hour = (int) $now->format('G');
    if ($dow !== 1 || $hour < 7 || $hour > 10) {
        jsonResponse(['ok' => true, 'sent' => 0, 'note' => 'Outside the Monday morning send window (Toronto).', 'toronto_now' => $now->format('Y-m-d H:i')]);
    }
}

$pdo = db();
$pdo->exec("CREATE TABLE IF NOT EXISTS weekly_email_state (
    user_id BIGINT UNSIGNED PRIMARY KEY,
    last_sent_on DATE NULL,
    last_kind VARCHAR(8) NOT NULL DEFAULT '',
    quiet_index INT NOT NULL DEFAULT 0,
    unsubscribed TINYINT NOT NULL DEFAULT 0,
    unsub_token CHAR(32) NOT NULL DEFAULT '',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

const RECAP_JOBTRACK_ACTIVE = ['new', 'applied', 'screening', 'interview_booked', 'waiting_next', 'offer'];
const RECAP_ROLODEX_ACTIVE = ['new', 'talking', 'quoted'];
const RECAP_ITEM_CAP = 6;

function recap_money(float $amount, string $currency): string {
    $s = '$' . number_format($amount, 2);
    if ($currency !== '' && strtoupper($currency) !== 'USD') $s .= ' ' . strtoupper($currency);
    return $s;
}
function recap_day(string $date, string $today): array {
    $d = new DateTime($date);
    if ($date < $today) {
        $days = (int) (new DateTime($today))->diff($d)->format('%a');
        return ['label' => 'Overdue', 'detail' => $d->format('D, M j'), 'overdue' => true, 'days' => $days];
    }
    if ($date === $today) return ['label' => 'Today', 'detail' => $d->format('D, M j'), 'overdue' => false, 'days' => 0];
    $days = (int) (new DateTime($today))->diff($d)->format('%a');
    if ($days === 1) return ['label' => 'Tomorrow', 'detail' => $d->format('D, M j'), 'overdue' => false, 'days' => 1];
    return ['label' => $d->format('D'), 'detail' => $d->format('D, M j'), 'overdue' => false, 'days' => $days];
}

// ---------- gather one user's week ----------
function recap_gather(PDO $pdo, int $userId, string $today, string $sunday, string $monday): array {
    $sections = [];

    // Subscriptions renewing (rolled forward so stale dates read as their
    // next real renewal), plus free trials ending inside the window.
    $stmt = $pdo->prepare("SELECT custom_name, category, tier_name, price_cents, currency, cadence, next_renewal_on, is_free_trial, trial_ends_on
        FROM cutline_subscriptions WHERE user_id = ? AND status = 'active' ORDER BY next_renewal_on ASC");
    $stmt->execute([$userId]);
    $items = [];
    $totalByCurrency = [];
    foreach ($stmt->fetchAll() as $s) {
        $name = trim((string) ($s['custom_name'] ?? ''));
        if ($name === '') $name = ucwords(str_replace('_', ' ', (string) $s['category']));
        if (!empty($s['tier_name'])) $name .= ' (' . $s['tier_name'] . ')';
        if ((int) $s['is_free_trial'] === 1 && !empty($s['trial_ends_on']) && $s['trial_ends_on'] >= $monday && $s['trial_ends_on'] <= $sunday) {
            $items[] = ['name' => $name, 'meta' => 'Free trial ends', 'amount' => null, 'date' => $s['trial_ends_on']];
            continue;
        }
        $renewal = cutline_roll_forward((string) $s['next_renewal_on'], (string) $s['cadence']);
        if ($renewal >= $monday && $renewal <= $sunday) {
            $amount = ((int) $s['price_cents']) / 100;
            $cur = strtoupper((string) ($s['currency'] ?: 'USD'));
            $totalByCurrency[$cur] = ($totalByCurrency[$cur] ?? 0) + $amount;
            $items[] = ['name' => $name, 'meta' => 'Renews', 'amount' => $amount, 'currency' => $cur, 'date' => $renewal];
        }
    }
    if ($items) {
        $foot = [];
        foreach ($totalByCurrency as $cur => $sum) $foot[] = recap_money($sum, $cur);
        $sections[] = ['key' => 'subscriptions', 'title' => 'Subscriptions renewing', 'tool' => 'Subscription Tracker',
            'url' => '/tools/cutline/', 'icon' => '/bum/icons/cutline.png', 'items' => $items,
            'foot' => $foot ? 'Renewing this week: ' . implode(' + ', $foot) : ''];
    }

    // Open invoices due this week or already overdue.
    $stmt = $pdo->prepare("SELECT client_name, amount, currency, invoice_no, due_date
        FROM chaser_invoices WHERE user_id = ? AND status = 'open' AND due_date <= ?
        ORDER BY due_date ASC");
    $stmt->execute([$userId, $sunday]);
    $items = [];
    foreach ($stmt->fetchAll() as $inv) {
        $name = (string) $inv['client_name'];
        if (!empty($inv['invoice_no'])) $name .= ' · #' . $inv['invoice_no'];
        $items[] = ['name' => $name, 'meta' => 'Invoice due', 'amount' => (float) $inv['amount'],
            'currency' => strtoupper((string) ($inv['currency'] ?: 'USD')), 'date' => (string) $inv['due_date']];
    }
    if ($items) {
        $sections[] = ['key' => 'invoices', 'title' => 'Invoices to chase', 'tool' => 'Invoice Chaser',
            'url' => '/tools/invoice-chaser/', 'icon' => '/bum/icons/invoice-chaser.png', 'items' => $items, 'foot' => ''];
    }

    // Client follow-ups due this week or overdue.
    $in = implode(',', array_fill(0, count(RECAP_ROLODEX_ACTIVE), '?'));
    $stmt = $pdo->prepare("SELECT name, company, follow_up_date FROM rolodex_contacts
        WHERE user_id = ? AND stage IN ($in) AND follow_up_date IS NOT NULL AND follow_up_date <= ?
        ORDER BY follow_up_date ASC");
    $stmt->execute(array_merge([$userId], RECAP_ROLODEX_ACTIVE, [$sunday]));
    $items = [];
    foreach ($stmt->fetchAll() as $c) {
        $name = (string) $c['name'];
        if (!empty($c['company'])) $name .= ' · ' . $c['company'];
        $items[] = ['name' => $name, 'meta' => 'Follow up', 'amount' => null, 'date' => (string) $c['follow_up_date']];
    }
    if ($items) {
        $sections[] = ['key' => 'clients', 'title' => 'Client follow-ups', 'tool' => 'Client Follow-Up Tracker',
            'url' => '/tools/purrsuit/', 'icon' => '/bum/icons/purrsuit.png', 'items' => $items, 'foot' => ''];
    }

    // Job application follow-ups due this week or overdue.
    $in = implode(',', array_fill(0, count(RECAP_JOBTRACK_ACTIVE), '?'));
    $stmt = $pdo->prepare("SELECT company, role, follow_up_date FROM jobtrack_contacts
        WHERE user_id = ? AND stage IN ($in) AND follow_up_date IS NOT NULL AND follow_up_date <= ?
        ORDER BY follow_up_date ASC");
    $stmt->execute(array_merge([$userId], RECAP_JOBTRACK_ACTIVE, [$sunday]));
    $items = [];
    foreach ($stmt->fetchAll() as $j) {
        $name = (string) $j['company'];
        if (!empty($j['role'])) $name = $j['role'] . ' · ' . $name;
        $items[] = ['name' => $name, 'meta' => 'Follow up', 'amount' => null, 'date' => (string) $j['follow_up_date']];
    }
    if ($items) {
        $sections[] = ['key' => 'jobs', 'title' => 'Job applications', 'tool' => 'Job Application Tracker',
            'url' => '/tools/corporate-bum-bum/', 'icon' => '/bum/icons/corporate-bum-bum.png', 'items' => $items, 'foot' => ''];
    }

    foreach ($sections as &$sec) {
        foreach ($sec['items'] as &$it) {
            $it['day'] = recap_day($it['date'], $today);
            // A renewal inside the send week is never "overdue", even on
            // a mid-week test run where its date has just passed.
            if ($sec['key'] === 'subscriptions') $it['day']['overdue'] = false;
        }
        unset($it);
    }
    unset($sec);
    return $sections;
}

// ---------- email rendering ----------
function recap_shell(string $headline, string $innerHtml, string $unsubUrl): string {
    $appUrl = rtrim((string) (config()['app_url'] ?? 'https://leaveittobumbum.com'), '/');
    $style = '<style>'
        . 'body{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;}'
        . 'table{border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;}'
        . '.em-mob{display:none;}'
        . '@media only screen and (max-width:640px){'
        . '.em-outer{padding:16px 6px !important;}'
        . '.em-pad{padding-left:16px !important;padding-right:16px !important;}'
        . '.em-cardpad{padding:14px 14px 10px 14px !important;}'
        . '.em-headline{font-size:26px !important;}'
        . '.em-desk{display:none !important;}'
        . '.em-mob{display:block !important;}'
        . '}'
        . '@media (prefers-color-scheme: dark){'
        . '.em-outer{background:#f6f0df !important;}'
        . '.em-shell{background:#ffffff !important;}'
        . '.em-card{background:#fdfaf1 !important;}'
        . '.em-ink{color:#142015 !important;}'
        . '.em-mute{color:#667064 !important;}'
        . '.em-chip-lime{background:#d8ff62 !important;}'
        . '.em-chip-red{background:#ff7448 !important;}'
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
    . 'One note a week, only from your own trackers. Nothing else, ever.<br>'
    . 'Leave It To Bum Bum · Aurora, ON L4G, Canada · <a href="mailto:hello@leaveittobumbum.com" style="color:#667064;">hello@leaveittobumbum.com</a><br>'
    . '<a href="' . $unsubUrl . '" style="color:#667064;text-decoration:underline;">Unsubscribe from the weekly note</a>'
    . '</div></td></tr>'
    . '</table></td></tr></table></body></html>';
}

function recap_render(array $sections, string $unsubUrl, string $today): array {
    $appUrl = rtrim((string) (config()['app_url'] ?? 'https://leaveittobumbum.com'), '/');
    $total = 0;
    foreach ($sections as $s) $total += count($s['items']);
    $html = '<tr><td class="em-pad" style="padding:16px 32px 0 32px;"><div class="em-ink" style="font-family:Arial,sans-serif;font-size:16px;line-height:1.55;color:#142015;">'
        . 'Here is everything your trackers say needs you this week, in one place, so none of it sneaks up on you.</div></td></tr>';
    $text = "Here is everything your trackers say needs you this week:\n";
    foreach ($sections as $sec) {
        $body = '<div class="em-ink" style="font-family:Arial,sans-serif;font-size:13px;font-weight:800;letter-spacing:1.5px;color:#142015;text-transform:uppercase;padding-bottom:4px;">' . $sec['title'] . '</div>';
        $text .= "\n" . strtoupper($sec['title']) . "\n";
        $shown = array_slice($sec['items'], 0, RECAP_ITEM_CAP);
        foreach ($shown as $it) {
            $day = $it['day'];
            $chipBg = $day['overdue'] ? '#ff7448' : '#d8ff62';
            $chipText = $day['overdue'] ? 'Overdue' : $day['detail'];
            $chipClass = $day['overdue'] ? 'em-chip-red' : 'em-chip-lime';
            $meta = $it['meta'] . ($day['overdue'] ? ' · was due ' . $day['detail'] : '');
            $amt = $it['amount'] !== null ? ' · ' . recap_money((float) $it['amount'], (string) ($it['currency'] ?? 'USD')) : '';
            $pill = '<span class="' . $chipClass . ' em-ink" style="display:inline-block;white-space:nowrap;background:' . $chipBg . ';border:2px solid #1a291c;border-radius:999px;padding:3px 10px;font-family:Arial,sans-serif;font-size:12px;font-weight:800;color:#142015;">' . $chipText . '</span>';
            $nameHtml = htmlspecialchars($it['name'], ENT_QUOTES, 'UTF-8');
            $body .= '<div class="em-desk"><table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #ddd3b8;"><tr>'
                . '<td class="em-ink" style="padding:10px 8px 10px 0;font-family:Arial,sans-serif;font-size:15px;color:#142015;"><b>' . $nameHtml . '</b>'
                . '<span class="em-mute" style="color:#667064;"> · ' . $meta . $amt . '</span></td>'
                . '<td align="right" width="104" style="padding:10px 0;">' . $pill . '</td></tr></table></div>'
                . '<div class="em-mob" style="display:none;"><table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #ddd3b8;"><tr><td style="padding:10px 0;font-family:Arial,sans-serif;">'
                . '<div class="em-ink" style="font-size:15px;color:#142015;"><b>' . $nameHtml . '</b></div>'
                . '<div class="em-mute" style="font-size:14px;color:#667064;padding-top:2px;">' . $meta . $amt . '</div>'
                . '<div style="padding-top:7px;">' . $pill . '</div>'
                . '</td></tr></table></div>';
            $text .= '- ' . $it['name'] . ' · ' . $it['meta'] . $amt . ' · ' . ($day['overdue'] ? 'OVERDUE ' : '') . $day['detail'] . "\n";
        }
        if (count($sec['items']) > RECAP_ITEM_CAP) {
            $more = count($sec['items']) - RECAP_ITEM_CAP;
            $body .= '<div class="em-mute" style="font-family:Arial,sans-serif;font-size:13px;color:#667064;padding:6px 0 0 0;">Plus ' . $more . ' more in the ' . $sec['tool'] . '.</div>';
            $text .= 'Plus ' . $more . " more in the " . $sec['tool'] . ".\n";
        }
        $footCell = $sec['foot'] !== ''
            ? '<td align="right" valign="middle" class="em-ink" style="font-family:Arial,sans-serif;font-size:14px;font-weight:800;color:#142015;">' . $sec['foot'] . '</td>'
            : '<td></td>';
        if ($sec['foot'] !== '') $text .= $sec['foot'] . "\n";
        $btnStyle = 'background:#d8ff62;border:2px solid #1a291c;border-radius:999px;font-family:Arial,sans-serif;font-size:14px;font-weight:900;color:#142015;text-decoration:none;';
        $body .= '<div class="em-desk"><table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #ddd3b8;"><tr>'
            . '<td style="padding:12px 0 2px 0;"><a class="em-chip-lime em-ink" href="' . $appUrl . $sec['url'] . '" style="display:inline-block;padding:9px 18px;' . $btnStyle . '">Open the ' . $sec['tool'] . '</a></td>'
            . $footCell . '</tr></table></div>'
            . '<div class="em-mob" style="display:none;"><table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #ddd3b8;"><tr><td style="padding:12px 0 2px 0;">'
            . ($sec['foot'] !== '' ? '<div class="em-ink" style="font-family:Arial,sans-serif;font-size:14px;font-weight:800;color:#142015;padding-bottom:10px;">' . $sec['foot'] . '</div>' : '')
            . '<a class="em-chip-lime em-ink" href="' . $appUrl . $sec['url'] . '" style="display:block;text-align:center;padding:10px 18px;' . $btnStyle . '">Open the ' . $sec['tool'] . '</a>'
            . '</td></tr></table></div>';
        $text .= 'Open the ' . $sec['tool'] . ': ' . $appUrl . $sec['url'] . "\n";
        $html .= '<tr><td class="em-pad" style="padding:16px 32px 0 32px;">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="em-card" style="background:#fdfaf1;border:2px solid #1a291c;border-radius:18px;"><tr><td class="em-cardpad" style="padding:18px 20px 14px 20px;">'
            . $body
            . '</td></tr></table></td></tr>';
    }
    $subject = 'Your week, handled: ' . $total . ($total === 1 ? ' thing' : ' things') . ' coming up';
    return ['subject' => $subject, 'html' => recap_shell('Your week, handled.', $html, $unsubUrl), 'text' => $text];
}

function recap_quiet_variants(): array {
    return [
        ['All quiet this week.', 'Nothing renewing, nothing due, nobody waiting on a follow-up. Bum Bum checked everything twice. Enjoy the silence.'],
        ['Nothing on your plate.', 'Your trackers are empty for the week ahead, in the good way. Bum Bum will keep watch so it stays that way.'],
        ['A rare empty week.', 'No renewals, no deadlines, no nudges owed. If anything pops up, it lands in next week\'s note.'],
        ['Still quiet out there.', 'Bum Bum did the rounds: subscriptions, invoices, follow-ups, applications. All calm, all handled.'],
        ['Zero fires this week.', 'Nothing needs you. Bum Bum is using the downtime to nap professionally.'],
        ['Quiet week, handled.', 'There was nothing to handle, so Bum Bum handled it beautifully. Same time next week.'],
    ];
}
function recap_render_quiet(int $idx, string $unsubUrl): array {
    $variants = recap_quiet_variants();
    [$headline, $line] = $variants[$idx % count($variants)];
    $appUrl = rtrim((string) (config()['app_url'] ?? 'https://leaveittobumbum.com'), '/');
    $html = '<tr><td class="em-pad"><div class="em-ink" align="center" style="padding:10px 32px 4px 32px;font-family:Arial,sans-serif;font-size:16px;line-height:1.55;color:#142015;">' . $line . '</div></td></tr>'
        . '<tr><td class="em-pad" style="padding:14px 32px 6px 32px;" align="center"><a class="em-chip-lime em-ink" href="' . $appUrl . '/tools/" style="display:inline-block;background:#d8ff62;border:2px solid #1a291c;border-radius:999px;padding:10px 20px;font-family:Arial,sans-serif;font-size:14px;font-weight:900;color:#142015;text-decoration:none;">See the toolbox</a></td></tr>';
    return ['subject' => $headline . ' Your Bum Bum note', 'html' => recap_shell($headline, $html, $unsubUrl),
        'text' => $line . "\n\nSee the toolbox: " . $appUrl . "/tools/\n"];
}

// Multipart (text + HTML) send over the same SMTP settings as
// includes/smtp-mail.php. Kept local so the shared plain-text mailer,
// which other tools depend on, stays untouched.
function recap_send_mail(string $to, string $subject, string $html, string $text): array {
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
    // The HTML part goes out base64-encoded. Sent raw, it is a single
    // unbroken line of many KB, and SMTP folds lines near 1000 chars;
    // a fold that lands inside a tag corrupts it (one send reached the
    // inbox with a '</table>' split across the fold, printed as text
    // and breaking that section's table). Base64 chunks at 76 chars
    // make that impossible.
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

// ---------- run ----------
$appUrl = rtrim((string) (config()['app_url'] ?? 'https://leaveittobumbum.com'), '/');
$userSql = 'SELECT id, email, created_at FROM users';
$userArgs = [];
if ($onlyEmail !== '') { $userSql .= ' WHERE LOWER(email) = ?'; $userArgs[] = $onlyEmail; }
$userStmt = $pdo->prepare($userSql);
$userStmt->execute($userArgs);
$users = $userStmt->fetchAll();

$stateGet = $pdo->prepare('SELECT * FROM weekly_email_state WHERE user_id = ?');
$statePut = $pdo->prepare('INSERT INTO weekly_email_state (user_id, last_sent_on, last_kind, quiet_index, unsubscribed, unsub_token)
    VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE last_sent_on = VALUES(last_sent_on),
    last_kind = VALUES(last_kind), quiet_index = VALUES(quiet_index), unsub_token = VALUES(unsub_token)');

$report = ['ok' => true, 'mode' => $sendMode ? 'send' : 'dry-run', 'window' => $today . ' to ' . $sunday,
    'week' => $weekLabel, 'users' => count($users), 'recaps' => 0, 'quiet' => 0, 'skipped' => 0, 'errors' => [], 'detail' => []];

foreach ($users as $u) {
    $userId = (int) $u['id'];
    $email = (string) $u['email'];
    $stateGet->execute([$userId]);
    $state = $stateGet->fetch() ?: ['last_sent_on' => null, 'last_kind' => '', 'quiet_index' => 0, 'unsubscribed' => 0, 'unsub_token' => ''];
    $token = (string) ($state['unsub_token'] ?? '');
    if ($token === '') $token = bin2hex(random_bytes(16));
    $unsubUrl = $appUrl . '/api/weekly-unsubscribe.php?u=' . $userId . '&t=' . $token;

    if ((int) ($state['unsubscribed'] ?? 0) === 1 && $onlyEmail === '') { $report['skipped']++; continue; }
    if ($onlyEmail === '' && (string) ($state['last_sent_on'] ?? '') === $today) { $report['skipped']++; continue; }

    $sections = recap_gather($pdo, $userId, $today, $sunday, $monday);
    $itemCount = 0;
    foreach ($sections as $s) $itemCount += count($s['items']);

    if ($itemCount > 0) {
        $mail = recap_render($sections, $unsubUrl, $today);
        $kind = 'recap';
        $report['recaps']++;
    } else {
        // Quiet path: at most one note every 13 days, and not in the
        // account's first week.
        $lastSent = (string) ($state['last_sent_on'] ?? '');
        $accountAgeOk = (string) $u['created_at'] < $today;
        $gapOk = $lastSent === '' || (new DateTime($lastSent))->diff(new DateTime($today))->days >= 13;
        if ($onlyEmail === '' && (!$gapOk || !$accountAgeOk)) { $report['skipped']++; continue; }
        $mail = recap_render_quiet((int) ($state['quiet_index'] ?? 0), $unsubUrl);
        $kind = 'quiet';
        $report['quiet']++;
    }

    $entry = ['user_id' => $userId, 'kind' => $kind, 'items' => $itemCount, 'subject' => $mail['subject']];
    if ($onlyEmail !== '') $entry['html'] = $mail['html'];
    if ($sendMode) {
        [$sent, $err] = recap_send_mail($email, $mail['subject'], $mail['html'], $mail['text']);
        if ($sent) {
            $statePut->execute([$userId, $today, $kind,
                (int) ($state['quiet_index'] ?? 0) + ($kind === 'quiet' ? 1 : 0),
                (int) ($state['unsubscribed'] ?? 0), $token]);
            $entry['sent'] = true;
        } else {
            $entry['sent'] = false;
            $report['errors'][] = 'user ' . $userId . ': ' . $err;
        }
    }
    $report['detail'][] = $entry;
}
jsonResponse($report);
