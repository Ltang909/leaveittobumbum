<?php
// Stat Cat: Google OAuth2 callback. Validates the state token (bound to the
// signed-in user), exchanges the code for tokens, stores the refresh token
// encrypted, then sends the user back to the tool to pick their property.
//
//   GET /api/stat-cat-oauth-callback.php?code=...&state=...
require dirname(__DIR__) . '/includes/stat-cat-lib.php';

function statcat_back(string $query): void {
    try { $base = statcat_tool_url(); }
    catch (Throwable $e) { $base = '/tools/stat-cat/'; }
    header('Location: ' . $base . $query);
    exit;
}

startSecureSession();
$saved = $_SESSION['statcat_oauth'] ?? null;
unset($_SESSION['statcat_oauth']);

$user = currentUser();
$state = (string) ($_GET['state'] ?? '');
$code = (string) ($_GET['code'] ?? '');
$googleError = (string) ($_GET['error'] ?? '');

if ($googleError !== '') statcat_back('?error=google_denied');
if (!$user) statcat_back('?error=signed_out');
if (!is_array($saved) || empty($saved['state']) || !hash_equals((string) $saved['state'], $state)) statcat_back('?error=bad_state');
if ((int) ($saved['exp'] ?? 0) < time()) statcat_back('?error=expired');
if ((int) ($saved['user_id'] ?? 0) !== (int) $user['id']) statcat_back('?error=bad_state');
if ($code === '') statcat_back('?error=no_code');
if (!statcat_oauth_configured()) statcat_back('?error=not_configured');

[$clientId, $clientSecret] = statcat_oauth_client();
$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'grant_type' => 'authorization_code',
        'code' => $code,
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'redirect_uri' => statcat_redirect_uri(),
    ]),
    CURLOPT_TIMEOUT => 20,
]);
$raw = curl_exec($ch);
$httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$data = json_decode((string) $raw, true) ?: [];

if ($httpCode < 200 || $httpCode >= 300 || empty($data['access_token'])) {
    error_log('stat-cat token exchange failed: HTTP ' . $httpCode);
    statcat_back('?error=exchange_failed');
}

// Google only returns a refresh token on first consent (we use
// prompt=consent so it should be here). If it is missing but we already
// have one stored, keep the old one rather than wiping it.
$newRefresh = (string) ($data['refresh_token'] ?? '');
$existing = statcat_token_row((int) $user['id']);
if ($newRefresh !== '') {
    statcat_save_tokens(
        (int) $user['id'],
        $newRefresh,
        (string) $data['access_token'],
        isset($data['expires_in']) ? (int) $data['expires_in'] : null
    );
} elseif ($existing) {
    // Keep stored refresh token, update the access token cache.
    $access = (string) $data['access_token'];
    $expiresIn = isset($data['expires_in']) ? (int) $data['expires_in'] : 3600;
    db()->prepare('UPDATE statcat_tokens SET access_token_enc = ?, access_expires_at = ?, granted_at = NOW() WHERE user_id = ? AND env = ?')
        ->execute([statcat_encrypt($access), time() + $expiresIn, (int) $user['id'], statcat_env()]);
} else {
    statcat_back('?error=no_refresh_token');
}

statcat_back('?connected=1');
