<?php
// Cutline renewal reminders. Triggered by cron:
//   GET /api/tools/cutline-reminders.php?secret=CRON_SECRET
// Reuses the Cutline schema + renewal math from cutline.php (requiring it does
// NOT run its API dispatch; that only runs when cutline.php is hit directly).
require __DIR__ . '/cutline.php';

$secret = (string) ($_GET['secret'] ?? '');
$expected = (string) (config()['cron_secret'] ?? '');
if ($expected === '' || $expected === 'replace_me' || !hash_equals($expected, $secret)) {
    jsonResponse(['error' => 'Forbidden.'], 403);
}

ensureCutlineSchema();
$pdo = db();
$sent = 0;
$skipped = 0;
$errors = [];

$mailCfg = config()['mail'] ?? [];
$mailConfigured = is_string($mailCfg['host'] ?? null) && $mailCfg['host'] !== '' && $mailCfg['host'] !== 'replace_me'
    && is_string($mailCfg['user'] ?? null) && $mailCfg['user'] !== '' && $mailCfg['user'] !== 'replace_me';

// Dependency-free SMTP client (AUTH LOGIN + STARTTLS), ported from the
// original Cutline mailer. Settings come from config()['mail'].
// Returns [bool $ok, string $error].
function cutline_send_mail(string $to, string $subject, string $body): array {
    $mail = config()['mail'] ?? [];
    $host = (string) ($mail['host'] ?? '');
    $port = (int) ($mail['port'] ?? 587);
    $smtpUser = (string) ($mail['user'] ?? '');
    $smtpPass = (string) ($mail['pass'] ?? '');
    $fromEmail = (string) ($mail['from_email'] ?? '');
    $fromName = (string) ($mail['from_name'] ?? '');
    if ($host === '' || $host === 'replace_me' || $smtpUser === '' || $smtpUser === 'replace_me') {
        return [false, 'mail not configured'];
    }
    if ($fromEmail === '' || $fromEmail === 'replace_me') $fromEmail = $smtpUser;

    $fp = @stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, 15);
    if (!$fp) return [false, "connect failed: {$errstr} ({$errno})"];
    stream_set_timeout($fp, 15);

    $expect = function (array $codes) use ($fp): array {
        $line = '';
        while (($l = fgets($fp, 1024)) !== false) {
            $line .= $l;
            if (preg_match('/^\d{3} /', $l)) break;
        }
        $code = (int) substr($line, 0, 3);
        return [in_array($code, $codes, true), trim($line)];
    };
    $cmd = function (string $c, array $codes) use ($fp, $expect): array {
        fwrite($fp, $c . "\r\n");
        return $expect($codes);
    };

    [$ok, $greet] = $expect([220]);
    if (!$ok) { fclose($fp); return [false, "greeting failed: {$greet}"]; }
    $ehloHost = gethostname() ?: 'localhost';
    [$ok, $msg] = $cmd("EHLO {$ehloHost}", [250]);
    if (!$ok) { fclose($fp); return [false, "EHLO failed: {$msg}"]; }
    [$ok, $msg] = $cmd('STARTTLS', [220]);
    if (!$ok) { fclose($fp); return [false, "STARTTLS failed: {$msg}"]; }
    if (!@stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
        fclose($fp);
        return [false, 'TLS negotiation failed'];
    }
    [$ok, $msg] = $cmd("EHLO {$ehloHost}", [250]);
    if (!$ok) { fclose($fp); return [false, "EHLO (after TLS) failed: {$msg}"]; }
    [$ok, $msg] = $cmd('AUTH LOGIN', [334]);
    if (!$ok) { fclose($fp); return [false, "AUTH LOGIN rejected: {$msg}"]; }
    [$ok, $msg] = $cmd(base64_encode($smtpUser), [334]);
    if (!$ok) { fclose($fp); return [false, 'SMTP username rejected']; }
    [$ok, $msg] = $cmd(base64_encode($smtpPass), [235]);
    if (!$ok) { fclose($fp); return [false, 'SMTP authentication failed']; }
    [$ok, $msg] = $cmd("MAIL FROM:<{$fromEmail}>", [250]);
    if (!$ok) { fclose($fp); return [false, "MAIL FROM rejected: {$msg}"]; }
    [$ok, $msg] = $cmd("RCPT TO:<{$to}>", [250, 251]);
    if (!$ok) { fclose($fp); return [false, "RCPT TO rejected: {$msg}"]; }
    [$ok, $msg] = $cmd('DATA', [354]);
    if (!$ok) { fclose($fp); return [false, "DATA rejected: {$msg}"]; }

    $safeBody = preg_replace('/^\./m', '..', $body);
    $fromHeader = $fromName !== '' && $fromName !== 'replace_me' ? "{$fromName} <{$fromEmail}>" : $fromEmail;
    $headers = "From: {$fromHeader}\r\n"
        . "To: {$to}\r\n"
        . "Subject: {$subject}\r\n"
        . "Content-Type: text/plain; charset=UTF-8\r\n"
        . "MIME-Version: 1.0\r\n"
        . 'Date: ' . date('r') . "\r\n";
    fwrite($fp, $headers . "\r\n" . $safeBody . "\r\n.\r\n");
    [$ok, $msg] = $expect([250]);
    $cmd('QUIT', [221]);
    fclose($fp);
    if (!$ok) return [false, "message rejected: {$msg}"];
    return [true, ''];
}

// 1) Send reminders for subscriptions renewing exactly notify_days_before out.
$due = $pdo->query(
    'SELECT s.id, s.user_id, s.custom_name, s.tier_name, s.price_cents, s.currency,
            s.cadence, s.next_renewal_on, u.email, u.plan, u.period_start, p.notify_days_before
     FROM cutline_subscriptions s
     JOIN users u ON u.id = s.user_id
     JOIN cutline_prefs p ON p.user_id = s.user_id
     WHERE s.status = \'active\'
       AND s.next_renewal_on = DATE_ADD(CURDATE(), INTERVAL p.notify_days_before DAY)'
)->fetchAll();

$dedupe = $pdo->prepare('SELECT 1 FROM cutline_reminder_log WHERE subscription_id = ? AND renewal_date_at_send = ?');
$logSent = $pdo->prepare('INSERT INTO cutline_reminder_log (subscription_id, renewal_date_at_send) VALUES (?, ?)');

foreach ($due as $sub) {
    $subId = (int) $sub['id'];
    $renewalDate = $sub['next_renewal_on'];
    $dedupe->execute([$subId, $renewalDate]);
    if ($dedupe->fetch()) { $skipped++; continue; }
    if (!$mailConfigured) { $skipped++; continue; }

    // One action per email. If the user is out of actions, skip the email
    // rather than sending unmetered mail.
    $count = consumeAction(
        (int) $sub['user_id'],
        (string) $sub['plan'],
        periodKey($sub),
        'cutline',
        "cutline-reminder-{$subId}-{$renewalDate}"
    );
    if (!empty($count['limit_reached'])) { $skipped++; continue; }

    $name = ($sub['custom_name'] !== null && $sub['custom_name'] !== '') ? $sub['custom_name'] : ($sub['tier_name'] ?: 'A subscription');
    $price = number_format(((int) $sub['price_cents']) / 100, 2);
    $days = (int) $sub['notify_days_before'];
    $subject = "Heads up: {$name} renews in {$days} day" . ($days === 1 ? '' : 's');
    $appUrl = rtrim((string) (config()['app_url'] ?? ''), '/');
    $body = "Hey! Friendly nudge from Bum Bum: {$name} ({$price} {$sub['currency']} / {$sub['cadence']}) renews on {$renewalDate}.\n\n"
        . "Still getting your money's worth? Lovely, do nothing.\n"
        . "Barely using it anymore? Cancel before the renewal and keep the cash.\n\n"
        . "See everything in one place: {$appUrl}/tools/cutline/\n\n"
        . 'Your pal, Bum Bum';

    [$ok, $mailError] = cutline_send_mail((string) $sub['email'], $subject, $body);
    if ($ok) {
        $logSent->execute([$subId, $renewalDate]);
        $sent++;
    } else {
        $errors[] = "sub {$subId}: {$mailError}";
    }
}

// 2) Roll past-due renewal dates forward so they always point at the next
// upcoming charge. Loops until the date is in the future: a single step per
// run would leave long-overdue rows looking wrong if the cron ever missed.
$past = $pdo->query(
    "SELECT id, next_renewal_on, cadence FROM cutline_subscriptions
     WHERE status = 'active' AND next_renewal_on <= CURDATE()"
)->fetchAll();
$advance = $pdo->prepare('UPDATE cutline_subscriptions SET next_renewal_on = ? WHERE id = ?');
$advanced = 0;
foreach ($past as $row) {
    $next = cutline_roll_forward($row['next_renewal_on'], $row['cadence']);
    $advance->execute([$next, (int) $row['id']]);
    $advanced++;
}

jsonResponse([
    'sent' => $sent,
    'skipped' => $skipped,
    'errors' => $errors,
    'advanced' => $advanced,
    'mail_configured' => $mailConfigured,
]);
