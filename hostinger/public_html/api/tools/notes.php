<?php
// Bum Bum Notes API. Transcripts are hosted in the shared DB, scoped to the
// signed-in user. One metered action covers transcribe + save.
require dirname(__DIR__) . '/_bootstrap.php';

function ensureNotesSchema(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS notes_entries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(191) NOT NULL,
        transcript MEDIUMTEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        KEY idx_user (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function notes_public_row(array $row, bool $full): array {
    $text = (string) $row['transcript'];
    $out = [
        'id' => (int) $row['id'],
        'title' => $row['title'],
        'created_at' => $row['created_at'],
    ];
    $out[$full ? 'transcript' : 'preview'] = $full ? $text : mb_substr($text, 0, 140);
    return $out;
}

function notes_idempotency(array $input): string {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
    return $key;
}

requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
$userId = (int) $user['id'];
ensureNotesSchema();
$pdo = db();
$action = (string) ($input['action'] ?? 'finalize');

if ($action === 'list') {
    $stmt = $pdo->prepare('SELECT id, title, transcript, created_at FROM notes_entries WHERE user_id = ? ORDER BY id DESC LIMIT 200');
    $stmt->execute([$userId]);
    $entries = [];
    foreach ($stmt->fetchAll() as $row) $entries[] = notes_public_row($row, false);
    jsonResponse(['entries' => $entries]);
}

if ($action === 'get') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT id, title, transcript, created_at FROM notes_entries WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    $row = $stmt->fetch();
    if (!$row) jsonResponse(['error' => 'Note not found.'], 404);
    jsonResponse(['entry' => notes_public_row($row, true)]);
}

if ($action === 'delete') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM notes_entries WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Note not found.'], 404);
    jsonResponse(['ok' => true]);
}

if ($action === 'finalize') {
    $transcript = (string) ($input['transcript'] ?? '');
    if (mb_strlen($transcript) > 100000) jsonResponse(['error' => 'That note is too long to save.'], 422);
    $idempotency = notes_idempotency($input);
    $count = consumeAction($userId, (string) $user['plan'], periodKey($user), 'notes', $idempotency);
    if (!empty($count['limit_reached'])) jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $count], 402);
    $entry = null;
    $text = trim($transcript);
    if (empty($count['duplicate']) && $text !== '') {
        $title = mb_substr(preg_replace('/\s+/', ' ', $text), 0, 60);
        $stmt = $pdo->prepare('INSERT INTO notes_entries (user_id, title, transcript) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $title, $transcript]);
        $entry = ['id' => (int) $pdo->lastInsertId(), 'title' => $title];
    }
    jsonResponse([
        'entry' => $entry,
        'saved' => $entry !== null,
        'usage' => usageFor($user),
        'duplicate' => (bool) ($count['duplicate'] ?? false),
    ]);
}

jsonResponse(['error' => 'Unknown action.'], 400);
