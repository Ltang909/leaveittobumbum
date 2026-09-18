<?php
require __DIR__ . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$action = (string) ($input['action'] ?? '');

if ($action === 'logout') {
    startSecureSession();
    $_SESSION = [];
    session_destroy();
    jsonResponse(['ok' => true]);
}

$email = strtolower(trim((string) ($input['email'] ?? '')));
$password = (string) ($input['password'] ?? '');
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 10) jsonResponse(['error' => 'Use a valid email and a password of at least 10 characters.'], 422);

if ($action === 'register') {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = db()->prepare('INSERT INTO users (email, password_hash) VALUES (?, ?)');
        $stmt->execute([$email, $hash]);
        startSecureSession();
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) db()->lastInsertId();
        jsonResponse(['ok' => true]);
    } catch (PDOException $error) {
        if ((string) $error->getCode() === '23000') jsonResponse(['error' => 'An account already exists for that email.'], 409);
        throw $error;
    }
}

if ($action === 'login') {
    $ipHash = hash('sha256', (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
    $count = db()->prepare('SELECT COUNT(*) FROM login_attempts WHERE email = ? AND ip_hash = ? AND attempted_at > DATE_SUB(UTC_TIMESTAMP(), INTERVAL 15 MINUTE)');
    $count->execute([$email, $ipHash]);
    if ((int) $count->fetchColumn() >= 10) jsonResponse(['error' => 'Too many attempts. Try again in 15 minutes.'], 429);
    db()->prepare('INSERT INTO login_attempts (email, ip_hash) VALUES (?, ?)')->execute([$email, $ipHash]);
    $stmt = db()->prepare('SELECT id, password_hash FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) jsonResponse(['error' => 'Email or password is incorrect.'], 401);
    startSecureSession();
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $user['id'];
    jsonResponse(['ok' => true]);
}

jsonResponse(['error' => 'Unknown action.'], 400);
