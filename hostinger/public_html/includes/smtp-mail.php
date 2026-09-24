<?php
// Shared dependency-free SMTP mailer (AUTH LOGIN + STARTTLS).
// Settings come from config()['mail']:
//   'mail' => ['host' => ..., 'port' => 587, 'user' => ..., 'pass' => ...,
//              'from_email' => ..., 'from_name' => ...]
// Ported from the Cutline renewal mailer so every tool sends mail the same way.
// Returns [bool $ok, string $error]. Never throws for config problems:
// check bb_mail_configured() first and show an honest message instead.
function bb_mail_configured(): bool {
    $mail = function_exists('config') ? (config()['mail'] ?? []) : [];
    return is_string($mail['host'] ?? null) && $mail['host'] !== '' && $mail['host'] !== 'replace_me'
        && is_string($mail['user'] ?? null) && $mail['user'] !== '' && $mail['user'] !== 'replace_me';
}

function bb_send_mail(string $to, string $subject, string $body, string $replyTo = ''): array {
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
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return [false, 'invalid recipient address'];
    }
    // Header-safe subject: strip CR/LF to block header injection.
    $subject = trim(preg_replace('/[\r\n]+/', ' ', $subject));
    if ($replyTo !== '' && !filter_var($replyTo, FILTER_VALIDATE_EMAIL)) $replyTo = '';

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
        . ($replyTo !== '' ? "Reply-To: {$replyTo}\r\n" : "")
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
