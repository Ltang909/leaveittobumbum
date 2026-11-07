<?php
declare(strict_types=1);
// Optional Google Calendar delivery for Day Log. A user who connects
// Google Calendar gets "Today's Top List" as an 8:00 AM event on recap
// mornings, alongside the recap email. The connection is separate from
// Google sign-in: it reuses the same OAuth client (google_client_id /
// google_client_secret in the server config) but asks only for the
// calendar.events scope, and only when a user chooses to connect.

function gcal_install(PDO $pdo): void {
    static $done = false;
    if ($done) return;
    $done = true;
    $pdo->exec("CREATE TABLE IF NOT EXISTS user_google_calendar (
        user_id BIGINT UNSIGNED PRIMARY KEY,
        refresh_token TEXT NOT NULL,
        connected_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_event_date DATE NULL,
        last_event_id VARCHAR(128) NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function gcal_configured(): bool {
    $cfg = config();
    return (string) ($cfg['google_client_id'] ?? '') !== '' && (string) ($cfg['google_client_secret'] ?? '') !== '';
}

function gcal_callback_url(): string {
    return rtrim((string) (config()['app_url'] ?? ''), '/') . '/api/auth/google-calendar-callback.php';
}

function gcal_connection(PDO $pdo, int $userId): ?array {
    gcal_install($pdo);
    $stmt = $pdo->prepare('SELECT user_id, connected_at, last_event_date, last_event_id FROM user_google_calendar WHERE user_id = ?');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    return $row ?: null;
}

// Returns [httpCode, decodedJson].
function gcal_http(string $url, array $opts): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, $opts + [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 20]);
    $raw = curl_exec($ch);
    $http = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $json = json_decode((string) $raw, true);
    return [$http, is_array($json) ? $json : []];
}

// Returns [accessToken, error, invalidGrant].
function gcal_access_token(string $refreshToken): array {
    $cfg = config();
    [$http, $tok] = gcal_http('https://oauth2.googleapis.com/token', [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => (string) ($cfg['google_client_id'] ?? ''),
            'client_secret' => (string) ($cfg['google_client_secret'] ?? ''),
        ]),
    ]);
    $access = (string) ($tok['access_token'] ?? '');
    if ($http === 200 && $access !== '') return [$access, '', false];
    $err = (string) ($tok['error'] ?? 'token exchange failed');
    return ['', $err, $err === 'invalid_grant'];
}

// Creates today's 8 AM "Today's Top List" event for a connected user.
// Returns a short status string for the caller's log; never throws.
function gcal_create_top_list_event(PDO $pdo, int $userId, string $today, array $topList, bool $stamp = true): string {
    try {
        gcal_install($pdo);
        $stmt = $pdo->prepare('SELECT refresh_token, last_event_date FROM user_google_calendar WHERE user_id = ?');
        $stmt->execute([$userId]);
        $conn = $stmt->fetch();
        if (!$conn) return 'not connected';
        if ($stamp && (string) ($conn['last_event_date'] ?? '') === $today) return 'already created';
        $items = array_values(array_filter(array_map(fn($i) => trim((string) $i), $topList), fn($i) => $i !== ''));
        if (!$items) return 'no top list';
        [$access, $err, $invalidGrant] = gcal_access_token((string) $conn['refresh_token']);
        if ($access === '') {
            if ($invalidGrant) {
                $pdo->prepare('DELETE FROM user_google_calendar WHERE user_id = ?')->execute([$userId]);
                return 'connection expired, removed';
            }
            return 'error: ' . $err;
        }
        $desc = implode("\n", array_map(fn($i) => '- ' . $i, $items)) . "\n\nFrom your Day Log on Leave It To Bum Bum.";
        [$http, $event] = gcal_http('https://www.googleapis.com/calendar/v3/calendars/primary/events', [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $access, 'Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode([
                'summary' => "Today's Top List",
                'description' => $desc,
                'start' => ['dateTime' => $today . 'T08:00:00', 'timeZone' => 'America/Toronto'],
                'end' => ['dateTime' => $today . 'T08:15:00', 'timeZone' => 'America/Toronto'],
            ]),
        ]);
        $eventId = (string) ($event['id'] ?? '');
        if (($http === 200 || $http === 201) && $eventId !== '') {
            if ($stamp) {
                $pdo->prepare('UPDATE user_google_calendar SET last_event_date = ?, last_event_id = ? WHERE user_id = ?')
                    ->execute([$today, $eventId, $userId]);
            }
            return 'created';
        }
        return 'error: calendar insert failed (' . $http . ')';
    } catch (Throwable $e) {
        error_log('gcal event failed for user ' . $userId . ': ' . $e->getMessage());
        return 'error';
    }
}

function gcal_disconnect(PDO $pdo, int $userId): void {
    gcal_install($pdo);
    $stmt = $pdo->prepare('SELECT refresh_token FROM user_google_calendar WHERE user_id = ?');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    if ($row) {
        // Best-effort revoke at Google; deleting our copy is what matters.
        gcal_http('https://oauth2.googleapis.com/revoke', [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(['token' => (string) $row['refresh_token']]),
        ]);
    }
    $pdo->prepare('DELETE FROM user_google_calendar WHERE user_id = ?')->execute([$userId]);
}
