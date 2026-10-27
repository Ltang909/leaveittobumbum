<?php
// Toggle for the Monday note (the weekly recap email) from the account
// page. Writes the same weekly_email_state.unsubscribed flag that
// cron-weekly-recap.php reads and weekly-unsubscribe.php flips, so all
// three surfaces stay in agreement.
require __DIR__ . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();

$want = !empty($input['subscribed']);
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
$stmt = $pdo->prepare('INSERT INTO weekly_email_state (user_id, unsubscribed, unsub_token) VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE unsubscribed = VALUES(unsubscribed),
    unsub_token = IF(unsub_token = \'\', VALUES(unsub_token), unsub_token)');
$stmt->execute([(int) $user['id'], $want ? 0 : 1, bin2hex(random_bytes(16))]);

posthogCapture('weekly_note_toggled', 'user_' . $user['id'], ['subscribed' => $want]);
jsonResponse(['ok' => true, 'subscribed' => $want]);
