<?php
// Weekly note unsubscribe. The token in the link is the credential:
// one random token per user, minted by cron-weekly-recap.php and stored
// in weekly_email_state. No login needed; flipping the flag is all
// this page does.
require __DIR__ . '/_bootstrap.php';

$userId = (int) ($_GET['u'] ?? 0);
$token = (string) ($_GET['t'] ?? '');
$done = false;
$valid = false;

if ($userId > 0 && $token !== '') {
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
    $stmt = $pdo->prepare('SELECT unsub_token, unsubscribed FROM weekly_email_state WHERE user_id = ?');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    if ($row && (string) $row['unsub_token'] !== '' && hash_equals((string) $row['unsub_token'], $token)) {
        $valid = true;
        if ((int) $row['unsubscribed'] !== 1) {
            $pdo->prepare('UPDATE weekly_email_state SET unsubscribed = 1 WHERE user_id = ?')->execute([$userId]);
        }
        $done = true;
    }
}

$appUrl = rtrim((string) (config()['app_url'] ?? 'https://leaveittobumbum.com'), '/');
$headline = $done ? 'Done. No more weekly notes.' : 'That link did not check out.';
$line = $done
    ? 'Bum Bum has taken you off the weekly note. Your trackers are all still right where you left them, and you can rejoin the note any time from your account page.'
    : 'The unsubscribe link looks incomplete or expired. If you keep getting the weekly note, forward one to hello@leaveittobumbum.com and Bum Bum will sort it out by paw.';

header('Content-Type: text/html; charset=UTF-8');
echo '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Weekly note · Leave It To Bum Bum</title></head>'
    . '<body style="margin:0;background:#f6f0df;font-family:Arial,sans-serif;color:#142015;">'
    . '<div style="max-width:560px;margin:48px auto;padding:0 16px;">'
    . '<div style="background:#fff;border:2px solid #1a291c;border-radius:22px;padding:36px 32px;text-align:center;">'
    . '<img src="' . $appUrl . '/bum-bum.png" width="104" alt="Bum Bum" style="border:0;">'
    . '<h1 style="font-family:Georgia,serif;font-size:28px;margin:14px 0 10px;">' . $headline . '</h1>'
    . '<p style="font-size:16px;line-height:1.55;margin:0 0 20px;">' . $line . '</p>'
    . '<a href="' . $appUrl . '/tools/" style="display:inline-block;background:#d8ff62;border:2px solid #1a291c;border-radius:999px;padding:11px 22px;font-weight:900;color:#142015;text-decoration:none;">Back to the toolbox</a>'
    . '</div></div></body></html>';
