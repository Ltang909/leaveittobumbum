<?php
// Per-user toolbox selection for the account dashboard. Stores which tool
// cards a user wants on their dashboard. Not metered.
require dirname(__DIR__) . '/_bootstrap.php';

const DASHBOARD_TOOL_KEYS = ['clips', 'notes', 'cutline', 'purrsuit'];

function ensureToolPrefsSchema(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS user_tool_prefs (
        user_id BIGINT UNSIGNED PRIMARY KEY,
        tool_keys TEXT NOT NULL,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT tool_prefs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function sanitizeToolKeys($raw): array {
    if (!is_array($raw)) return [];
    $clean = array_map('strval', $raw);
    return array_values(array_intersect(DASHBOARD_TOOL_KEYS, $clean));
}

requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
$userId = (int) $user['id'];
ensureToolPrefsSchema();
$pdo = db();
$action = (string) ($input['action'] ?? 'get');

if ($action === 'get') {
    $stmt = $pdo->prepare('SELECT tool_keys FROM user_tool_prefs WHERE user_id = ?');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    $keys = $row ? sanitizeToolKeys(json_decode((string) $row['tool_keys'], true)) : [];
    if (!$keys) $keys = DASHBOARD_TOOL_KEYS;
    jsonResponse(['keys' => $keys]);
}

if ($action === 'save') {
    $keys = sanitizeToolKeys($input['keys'] ?? null);
    if (!$keys) jsonResponse(['error' => 'Pick at least one tool for your dashboard.'], 422);
    $pdo->prepare('INSERT INTO user_tool_prefs (user_id, tool_keys) VALUES (?, ?) ON DUPLICATE KEY UPDATE tool_keys = VALUES(tool_keys)')
        ->execute([$userId, json_encode($keys)]);
    jsonResponse(['ok' => true, 'keys' => $keys]);
}

jsonResponse(['error' => 'Unknown action.'], 400);
