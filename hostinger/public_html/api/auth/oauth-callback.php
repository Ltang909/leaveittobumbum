<?php
declare(strict_types=1);
// OAuth callback for "Continue with Google / LinkedIn". Verifies the state
// token, exchanges the code, fetches the verified profile, then finds the
// user by OAuth subject, links a matching verified email, or creates a new
// account. Password login stays impossible for OAuth-created accounts: their
// password hash is random and unknowable.
require __DIR__ . '/../_bootstrap.php';

function oauthFail(string $why): never {
    error_log('OAuth callback failed: ' . $why);
    header('Location: /account/?oauth=error');
    exit;
}

startSecureSession();
$state = (string) ($_GET['state'] ?? '');
$code = (string) ($_GET['code'] ?? '');
$provider = (string) ($_SESSION['oauth_provider'] ?? '');
$expected = (string) ($_SESSION['oauth_state'] ?? '');
unset($_SESSION['oauth_state']);
if ($state === '' || $code === '' || $provider === '' || $expected === '' || !hash_equals($expected, $state)) {
    oauthFail('bad state');
}
$p = oauthProviders()[$provider] ?? null;
if ($p === null || !oauthEnabled($provider)) oauthFail('provider not configured');

// Exchange the authorization code for tokens.
$ch = curl_init($p['token']);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
    CURLOPT_POSTFIELDS => http_build_query([
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $p['callback'],
        'client_id' => $p['client_id'],
        'client_secret' => $p['client_secret'],
    ]),
]);
$tokenRaw = curl_exec($ch);
$tokenHttp = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$token = json_decode((string) $tokenRaw, true);
$access = is_array($token) ? (string) ($token['access_token'] ?? '') : '';
if ($tokenHttp !== 200 || $access === '') oauthFail('token exchange failed');

// Fetch the verified profile.
$ch = curl_init($p['userinfo']);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $access],
]);
$infoRaw = curl_exec($ch);
$infoHttp = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$decoded = json_decode((string) $infoRaw, true);
$info = is_array($decoded) ? $decoded : [];
$sub = (string) ($info['sub'] ?? '');
$email = strtolower(trim((string) ($info['email'] ?? '')));
$emailVerified = filter_var($info['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN);
if ($infoHttp !== 200 || $sub === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$emailVerified) {
    oauthFail('userinfo failed');
}

ensureOAuthColumns();
// $provider is whitelisted against oauthProviders() keys above, so the
// column name interpolation is safe.
$subCol = 'oauth_' . $provider . '_sub';

// 1. Known OAuth identity: log straight in.
$stmt = db()->prepare("SELECT id FROM users WHERE $subCol = ?");
$stmt->execute([$sub]);
$user = $stmt->fetch();
if (!$user) {
    // 2. Same email already registered (e.g. with a password): link it,
    // since the provider verified the address.
    $stmt = db()->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user) {
        db()->prepare("UPDATE users SET $subCol = ? WHERE id = ?")->execute([$sub, $user['id']]);
    } else {
        // 3. Brand-new account.
        $hash = password_hash('oauth:' . $provider . ':' . bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
        db()->prepare("INSERT INTO users (email, password_hash, $subCol) VALUES (?, ?, ?)")
            ->execute([$email, $hash, $sub]);
        $user = ['id' => (int) db()->lastInsertId()];
    }
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];
$next = (string) ($_SESSION['oauth_next'] ?? '/account/');
unset($_SESSION['oauth_next'], $_SESSION['oauth_provider']);
if (!str_starts_with($next, '/') || str_starts_with($next, '//')) $next = '/account/';
header('Location: ' . $next);
exit;
