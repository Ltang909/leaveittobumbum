<?php
// Stat Cat shared library: Google OAuth2, encrypted token storage, GA4 and
// Search Console API clients, and the Groq traffic-summary writer.
//
// Auth model: Stat Cat requires a signed-in Bum Bum account. Google OAuth
// refresh tokens are long-lived credentials, so they are tied to a user id,
// never to an anonymous guest cookie. Tokens are encrypted at rest with
// AES-256-GCM; the key is derived from the server guest_secret, which differs
// per environment, so tokens are namespaced by env ('production'/'staging')
// and one environment can never read the other's.
//
// Server config keys (leaveittobumbum-config.php, never in the repo):
//   'statcat_google_client_id' / 'statcat_google_client_secret'
//     - Google OAuth web client for Stat Cat. Falls back to the env vars
//       GOOGLE_OAUTH_CLIENT_ID / GOOGLE_OAUTH_CLIENT_SECRET, then to the
//       sign-in app's 'google_client_id' / 'google_client_secret' (the same
//       Google Cloud project can serve both; register both redirect URIs).
//   'statcat_redirect_uri' - optional override for the OAuth redirect URI.
//   'groq_api_key' - already used by other tools; 'statcat_model' optionally
//     overrides the Groq chat model (default openai/gpt-oss-120b).

require_once dirname(__DIR__) . '/api/_bootstrap.php';

const STATCAT_SCOPES = 'openid email https://www.googleapis.com/auth/analytics.readonly https://www.googleapis.com/auth/webmasters.readonly';
const STATCAT_TOOL_KEY = 'stat-cat';

function statcat_env(): string {
    return isStagingHost() ? 'staging' : 'production';
}

function statcat_ensure_schema(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS statcat_tokens (
        user_id INT UNSIGNED NOT NULL,
        env VARCHAR(16) NOT NULL DEFAULT 'production',
        refresh_token_enc TEXT NOT NULL,
        access_token_enc TEXT NULL,
        access_expires_at INT UNSIGNED NULL,
        ga4_property_id VARCHAR(64) NULL,
        ga4_property_name VARCHAR(190) NULL,
        sc_site_url VARCHAR(255) NULL,
        granted_at TIMESTAMP NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (user_id, env)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    db()->exec("CREATE TABLE IF NOT EXISTS statcat_digests (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NOT NULL,
        env VARCHAR(16) NOT NULL DEFAULT 'production',
        summary MEDIUMTEXT NOT NULL,
        data_json MEDIUMTEXT NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        KEY idx_user_env (user_id, env, created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

// Guardrail: no em dashes, ever. Same rule as the other tools.
function statcat_clean(string $s): string {
    $s = str_replace(' — ', ', ', $s);
    $s = str_replace('—', ',', $s);
    $s = str_replace('–', '-', $s);
    return $s;
}

// ---------------------------------------------------------------------------
// Token encryption. Key is derived from the server guest_secret (per env),
// so encrypted tokens are opaque to anyone reading the database.
// ---------------------------------------------------------------------------

function statcat_enc_key(): string {
    $secret = guestSecret();
    if ($secret === null) throw new RuntimeException('Server guest secret is missing.');
    return hash_hmac('sha256', 'statcat-token-encryption-v1:' . statcat_env(), $secret, true);
}

function statcat_encrypt(string $plain): string {
    $iv = random_bytes(12);
    $tag = '';
    $ct = openssl_encrypt($plain, 'aes-256-gcm', statcat_enc_key(), OPENSSL_RAW_DATA, $iv, $tag);
    if ($ct === false) throw new RuntimeException('Token encryption failed.');
    return base64_encode($iv . $tag . $ct);
}

function statcat_decrypt(string $blob): ?string {
    try {
        $raw = base64_decode($blob, true);
        if ($raw === false || strlen($raw) < 28) return null;
        $pt = openssl_decrypt(substr($raw, 28), 'aes-256-gcm', statcat_enc_key(), OPENSSL_RAW_DATA, substr($raw, 0, 12), substr($raw, 12, 16));
        return $pt === false ? null : $pt;
    } catch (Throwable $e) {
        return null;
    }
}

// ---------------------------------------------------------------------------
// OAuth client configuration.
// ---------------------------------------------------------------------------

function statcat_oauth_client(): array {
    $cfg = [];
    try { $cfg = config(); } catch (Throwable $e) {}
    $id = trim((string) ($cfg['statcat_google_client_id'] ?? ''));
    $secret = trim((string) ($cfg['statcat_google_client_secret'] ?? ''));
    if ($id === '' || $id === 'replace_me') $id = trim((string) (getenv('GOOGLE_OAUTH_CLIENT_ID') ?: ''));
    if ($secret === '' || $secret === 'replace_me') $secret = trim((string) (getenv('GOOGLE_OAUTH_CLIENT_SECRET') ?: ''));
    // Last resort: the sign-in Google app. The same Google Cloud project can
    // serve both Stat Cat and sign-in; both redirect URIs must be registered.
    if ($id === '' || $id === 'replace_me') $id = trim((string) ($cfg['google_client_id'] ?? ''));
    if ($secret === '' || $secret === 'replace_me') $secret = trim((string) ($cfg['google_client_secret'] ?? ''));
    if ($id === 'replace_me') $id = '';
    if ($secret === 'replace_me') $secret = '';
    return [$id, $secret];
}

function statcat_oauth_configured(): bool {
    [$id, $secret] = statcat_oauth_client();
    return $id !== '' && $secret !== '';
}

function statcat_redirect_uri(): string {
    try { $cfg = config(); } catch (Throwable $e) { $cfg = []; }
    $override = trim((string) ($cfg['statcat_redirect_uri'] ?? ''));
    if ($override !== '') return $override;
    $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    // /api/stat-cat-oauth-callback.php -> base ''; /staging/api/... -> '/staging'.
    $base = (string) preg_replace('#/api/[^/]*$#', '', $script);
    return 'https://' . $host . $base . '/api/stat-cat-oauth-callback.php';
}

function statcat_tool_url(): string {
    $uri = statcat_redirect_uri();
    return (string) preg_replace('#/api/stat-cat-oauth-callback\.php$#', '/tools/stat-cat/', $uri);
}

// ---------------------------------------------------------------------------
// Token storage.
// ---------------------------------------------------------------------------

function statcat_token_row(int $userId): ?array {
    statcat_ensure_schema();
    $stmt = db()->prepare('SELECT * FROM statcat_tokens WHERE user_id = ? AND env = ?');
    $stmt->execute([$userId, statcat_env()]);
    return $stmt->fetch() ?: null;
}

function statcat_save_tokens(int $userId, string $refreshToken, ?string $accessToken, ?int $expiresIn): void {
    statcat_ensure_schema();
    $accessEnc = $accessToken !== null ? statcat_encrypt($accessToken) : null;
    $expiresAt = $expiresIn !== null ? time() + $expiresIn : null;
    db()->prepare('INSERT INTO statcat_tokens (user_id, env, refresh_token_enc, access_token_enc, access_expires_at, granted_at)
        VALUES (?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE refresh_token_enc = VALUES(refresh_token_enc), access_token_enc = VALUES(access_token_enc),
        access_expires_at = VALUES(access_expires_at), granted_at = VALUES(granted_at)')->execute([
        $userId, statcat_env(), statcat_encrypt($refreshToken), $accessEnc, $expiresAt,
    ]);
}

function statcat_delete_tokens(int $userId): void {
    statcat_ensure_schema();
    db()->prepare('DELETE FROM statcat_tokens WHERE user_id = ? AND env = ?')->execute([$userId, statcat_env()]);
}

function statcat_save_selection(int $userId, ?string $ga4Id, ?string $ga4Name, ?string $scSite): void {
    statcat_ensure_schema();
    db()->prepare('UPDATE statcat_tokens SET ga4_property_id = ?, ga4_property_name = ?, sc_site_url = ? WHERE user_id = ? AND env = ?')
        ->execute([$ga4Id, $ga4Name, $scSite, $userId, statcat_env()]);
}

// Returns a usable access token, refreshing silently when expired.
// Returns null when there is no token or the refresh token is dead
// (in which case the row is deleted so the UI shows "reconnect").
function statcat_access_token(int $userId): ?string {
    $row = statcat_token_row($userId);
    if (!$row) return null;
    if (!empty($row['access_token_enc']) && (int) ($row['access_expires_at'] ?? 0) > time() + 60) {
        $cached = statcat_decrypt((string) $row['access_token_enc']);
        if ($cached !== null) return $cached;
    }
    $refresh = statcat_decrypt((string) $row['refresh_token_enc']);
    if ($refresh === null) { statcat_delete_tokens($userId); return null; }
    [$id, $secret] = statcat_oauth_client();
    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh,
            'client_id' => $id,
            'client_secret' => $secret,
        ]),
        CURLOPT_TIMEOUT => 20,
    ]);
    $raw = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $data = json_decode((string) $raw, true) ?: [];
    if ($code < 200 || $code >= 300 || empty($data['access_token'])) {
        // invalid_grant means the user revoked access or the token rotted.
        statcat_delete_tokens($userId);
        return null;
    }
    $access = (string) $data['access_token'];
    $expiresIn = isset($data['expires_in']) ? (int) $data['expires_in'] : 3600;
    db()->prepare('UPDATE statcat_tokens SET access_token_enc = ?, access_expires_at = ? WHERE user_id = ? AND env = ?')
        ->execute([statcat_encrypt($access), time() + $expiresIn, $userId, statcat_env()]);
    return $access;
}

// ---------------------------------------------------------------------------
// Google API helpers. Return [httpCode, decodedBody].
// ---------------------------------------------------------------------------

function statcat_google_request(string $method, string $url, string $token, $body = null): array {
    $ch = curl_init($url);
    $headers = ['Authorization: Bearer ' . $token, 'Accept: application/json'];
    $opts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
    ];
    if ($body !== null) {
        $headers[] = 'Content-Type: application/json';
        $opts[CURLOPT_HTTPHEADER] = $headers;
        $opts[CURLOPT_POSTFIELDS] = json_encode($body);
    }
    curl_setopt_array($ch, $opts);
    $raw = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);
    if ($raw === false) return [0, ['error' => 'Google connection failed: ' . $curlErr]];
    return [$code, json_decode((string) $raw, true) ?: []];
}

function statcat_google_error(array $data, int $code): string {
    $msg = (string) ($data['error']['message'] ?? '');
    if ($msg === '') $msg = 'Google said no (' . $code . ').';
    // Keep it short and human; never leak raw API payloads.
    if (stripos($msg, 'has not enabled') !== false || stripos($msg, 'API has not been used') !== false) {
        return 'That Google API is not enabled on the Google Cloud project yet.';
    }
    if ($code === 403 && stripos($msg, 'permission') !== false) {
        return 'Google refused: this account cannot see that property or site.';
    }
    return mb_substr($msg, 0, 160);
}

// GA4: list accounts and properties via the Analytics Admin API.
function statcat_list_ga4_properties(string $token): array {
    $props = [];
    $pageToken = null;
    do {
        $url = 'https://analyticsadmin.googleapis.com/v1beta/accountSummaries?pageSize=200' . ($pageToken ? '&pageToken=' . urlencode($pageToken) : '');
        [$code, $data] = statcat_google_request('GET', $url, $token);
        if ($code < 200 || $code >= 300) return ['error' => statcat_google_error($data, $code)];
        foreach ($data['accountSummaries'] ?? [] as $acct) {
            foreach ($acct['propertySummaries'] ?? [] as $p) {
                $props[] = [
                    'id' => (string) ($p['property'] ?? ''),
                    'name' => (string) ($p['displayName'] ?? ''),
                    'account' => (string) ($acct['displayName'] ?? ''),
                ];
            }
        }
        $pageToken = $data['nextPageToken'] ?? null;
    } while ($pageToken);
    return ['properties' => $props];
}

// Search Console: list verified sites.
function statcat_list_sc_sites(string $token): array {
    [$code, $data] = statcat_google_request('GET', 'https://www.googleapis.com/webmasters/v3/sites', $token);
    if ($code < 200 || $code >= 300) return ['error' => statcat_google_error($data, $code)];
    $sites = [];
    foreach ($data['siteEntry'] ?? [] as $s) {
        $sites[] = [
            'siteUrl' => (string) ($s['siteUrl'] ?? ''),
            'permissionLevel' => (string) ($s['permissionLevel'] ?? ''),
        ];
    }
    return ['sites' => $sites];
}

function statcat_ga4_property_number(string $propertyId): string {
    return (string) preg_replace('#^properties/#', '', $propertyId);
}

function statcat_ga4_report(string $token, string $propertyId, array $report): array {
    $num = statcat_ga4_property_number($propertyId);
    if ($num === '') return ['error' => 'Pick a Google Analytics property first.'];
    [$code, $data] = statcat_google_request(
        'POST',
        'https://analyticsdata.googleapis.com/v1beta/properties/' . urlencode($num) . ':runReport',
        $token,
        $report
    );
    if ($code < 200 || $code >= 300) return ['error' => statcat_google_error($data, $code)];
    return ['report' => $data];
}

function statcat_sc_query(string $token, string $siteUrl, array $query): array {
    if ($siteUrl === '') return ['error' => 'Pick a Search Console site first.'];
    [$code, $data] = statcat_google_request(
        'POST',
        'https://www.googleapis.com/webmasters/v3/sites/' . urlencode($siteUrl) . '/searchAnalytics/query',
        $token,
        $query
    );
    if ($code < 200 || $code >= 300) return ['error' => statcat_google_error($data, $code)];
    return ['result' => $data];
}

// ---------------------------------------------------------------------------
// Digest builder: GA4 totals + top pages + channels, Search Console totals +
// top queries + top pages, all for the last 28 days.
// ---------------------------------------------------------------------------

function statcat_build_digest(string $token, ?string $ga4Id, ?string $ga4Name, ?string $scSite): array {
    $digest = ['generated_at' => gmdate('Y-m-d H:i:s'), 'ga4' => null, 'sc' => null];
    $errors = [];

    if ($ga4Id) {
        $totals = statcat_ga4_report($token, $ga4Id, [
            'dateRanges' => [
                ['startDate' => '28daysAgo', 'endDate' => 'today'],
                ['startDate' => '56daysAgo', 'endDate' => '29daysAgo'],
            ],
            'metrics' => [['name' => 'sessions'], ['name' => 'totalUsers'], ['name' => 'screenPageViews']],
        ]);
        if (isset($totals['error'])) {
            $errors[] = 'Analytics: ' . $totals['error'];
        } else {
            $rep = $totals['report'];
            $rows = $rep['rows'] ?? [];
            $m = function (int $r, int $i) use ($rows): float {
                return (float) ($rows[$r]['metricValues'][$i]['value'] ?? 0);
            };
            $ga = [
                'property_name' => $ga4Name ?: $ga4Id,
                'sessions' => (int) $m(0, 0), 'sessions_prev' => (int) $m(1, 0),
                'users' => (int) $m(0, 1), 'pageviews' => (int) $m(0, 2),
                'top_pages' => [], 'channels' => [],
            ];
            $pages = statcat_ga4_report($token, $ga4Id, [
                'dateRanges' => [['startDate' => '28daysAgo', 'endDate' => 'today']],
                'dimensions' => [['name' => 'pagePath'], ['name' => 'pageTitle']],
                'metrics' => [['name' => 'sessions']],
                'orderBys' => [['metric' => ['metricName' => 'sessions'], 'desc' => true]],
                'limit' => 5,
            ]);
            if (!isset($pages['error'])) {
                foreach ($pages['report']['rows'] ?? [] as $r) {
                    $ga['top_pages'][] = [
                        'path' => (string) ($r['dimensionValues'][0]['value'] ?? ''),
                        'title' => (string) ($r['dimensionValues'][1]['value'] ?? ''),
                        'sessions' => (int) ($r['metricValues'][0]['value'] ?? 0),
                    ];
                }
            }
            $chans = statcat_ga4_report($token, $ga4Id, [
                'dateRanges' => [['startDate' => '28daysAgo', 'endDate' => 'today']],
                'dimensions' => [['name' => 'sessionDefaultChannelGroup']],
                'metrics' => [['name' => 'sessions']],
                'orderBys' => [['metric' => ['metricName' => 'sessions'], 'desc' => true]],
                'limit' => 6,
            ]);
            if (!isset($chans['error'])) {
                foreach ($chans['report']['rows'] ?? [] as $r) {
                    $ga['channels'][] = [
                        'channel' => (string) ($r['dimensionValues'][0]['value'] ?? ''),
                        'sessions' => (int) ($r['metricValues'][0]['value'] ?? 0),
                    ];
                }
            }
            $digest['ga4'] = $ga;
        }
    }

    if ($scSite) {
        // Search Console data lags a couple of days, so end two days back.
        $end = gmdate('Y-m-d', time() - 2 * 86400);
        $start = gmdate('Y-m-d', time() - 30 * 86400);
        $totals = statcat_sc_query($token, $scSite, ['startDate' => $start, 'endDate' => $end]);
        if (isset($totals['error'])) {
            $errors[] = 'Search Console: ' . $totals['error'];
        } else {
            $trows = $totals['result']['rows'] ?? [];
            $t = $trows[0] ?? ['clicks' => 0, 'impressions' => 0, 'ctr' => 0, 'position' => 0];
            $sc = [
                'site_url' => $scSite,
                'clicks' => (int) ($t['clicks'] ?? 0),
                'impressions' => (int) ($t['impressions'] ?? 0),
                'ctr' => round((float) ($t['ctr'] ?? 0) * 100, 2),
                'avg_position' => round((float) ($t['position'] ?? 0), 1),
                'top_queries' => [],
                'top_pages' => [],
            ];
            $queries = statcat_sc_query($token, $scSite, [
                'startDate' => $start, 'endDate' => $end,
                'dimensions' => ['query'], 'rowLimit' => 10,
            ]);
            if (!isset($queries['error'])) {
                foreach ($queries['result']['rows'] ?? [] as $r) {
                    $sc['top_queries'][] = [
                        'query' => (string) ($r['keys'][0] ?? ''),
                        'clicks' => (int) ($r['clicks'] ?? 0),
                        'impressions' => (int) ($r['impressions'] ?? 0),
                        'position' => round((float) ($r['position'] ?? 0), 1),
                    ];
                }
            }
            $spages = statcat_sc_query($token, $scSite, [
                'startDate' => $start, 'endDate' => $end,
                'dimensions' => ['page'], 'rowLimit' => 5,
            ]);
            if (!isset($spages['error'])) {
                foreach ($spages['result']['rows'] ?? [] as $r) {
                    $sc['top_pages'][] = [
                        'page' => (string) ($r['keys'][0] ?? ''),
                        'clicks' => (int) ($r['clicks'] ?? 0),
                        'impressions' => (int) ($r['impressions'] ?? 0),
                    ];
                }
            }
            $digest['sc'] = $sc;
        }
    }

    if ($digest['ga4'] === null && $digest['sc'] === null) {
        return ['error' => $errors ? implode(' ', $errors) : 'No data came back. Check the connection and try again.'];
    }
    if ($errors) $digest['warnings'] = $errors;
    return ['digest' => $digest];
}

// ---------------------------------------------------------------------------
// Groq: turn the numbers into a plain-English summary, Stat Cat voice.
// ---------------------------------------------------------------------------

function statcat_summarize(array $digest): string {
    $key = '';
    try { $key = (string) (config()['groq_api_key'] ?? ''); } catch (Throwable $e) {}
    if ($key === '' || $key === 'replace_me') {
        return 'Stat Cat fetched your numbers, but the storytelling engine is not set up on the server yet. The tables below still tell the story.';
    }
    $system = 'You are Stat Cat, the analytics translator at Leave It to Bum Bum, a toolbox of tiny tools for busy businesses. '
        . 'You explain website numbers in plain, friendly English with a light playful cat-executive voice, like a clever cat CFO reporting to the team. '
        . 'Rules: 1) Use ONLY the numbers provided. Never invent numbers, trends, or facts. '
        . '2) 120 to 180 words. 3) Cover: the overall direction (up or down vs the previous 28 days), what is carrying the traffic (top pages or channels), '
        . 'one interesting search query insight if Search Console data is present, and one concrete suggestion for next week. '
        . '4) NEVER use em dashes or en dashes anywhere. Use commas, colons, or parentheses instead. '
        . '5) Plain text only, no markdown, no headings.';
    $model = '';
    try { $model = trim((string) (config()['statcat_model'] ?? '')); } catch (Throwable $e) {}
    if ($model === '') $model = 'openai/gpt-oss-120b';
    $payload = [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => 'Here is the last 28 days of traffic data as JSON. Write the summary.\n' . json_encode($digest, JSON_UNESCAPED_SLASHES)],
        ],
        'temperature' => 0.7,
        'max_tokens' => 600,
    ];
    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $key],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 60,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $raw = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($raw === false || $code < 200 || $code >= 300) {
        return 'Stat Cat fetched your numbers, but the summary writer tripped over its own paws. The tables below still tell the story.';
    }
    $data = json_decode((string) $raw, true);
    $content = trim((string) ($data['choices'][0]['message']['content'] ?? ''));
    if ($content === '') {
        return 'Stat Cat fetched your numbers, but the summary came back empty. The tables below still tell the story.';
    }
    return statcat_clean($content);
}

// ---------------------------------------------------------------------------
// Auth helper: Stat Cat needs a signed-in user (tokens are per-user).
// ---------------------------------------------------------------------------

function statcat_require_user(): array {
    $user = currentUser();
    if (!$user) jsonResponse(['error' => 'Sign in to use Stat Cat.'], 401);
    return [$user, billingUser($user)];
}

function statcat_user_subject(array $user, array $bill): array {
    return ['kind' => 'user', 'user' => $user, 'bill' => $bill];
}

function statcat_idempotency(array $input): string {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
    return $key;
}
