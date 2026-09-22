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

function notes_audio_dir(): string {
    // Outside the web root (mirrors the server-config path logic in _bootstrap.php).
    $docroot = (string) $_SERVER['DOCUMENT_ROOT'];
    $base = isStagingHost() ? dirname($docroot, 2) : dirname($docroot);
    $dir = $base . '/notes-audio';
    if (!is_dir($dir)) @mkdir($dir, 0750, true);
    return $dir;
}

function notes_audio_ids(int $userId): array {
    $ids = [];
    foreach (glob(notes_audio_dir() . '/' . $userId . '_*.*') ?: [] as $file) {
        if (preg_match('/_(\d+)\.[a-z0-9]+$/i', basename($file), $m)) $ids[(int) $m[1]] = true;
    }
    return $ids;
}

function notes_delete_audio(int $userId, int $entryId): void {
    foreach (glob(notes_audio_dir() . '/' . $userId . '_' . $entryId . '.*') ?: [] as $file) @unlink($file);
}

function notes_idempotency(array $input): string {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
    return $key;
}

requirePost();
$input = body();
$action = (string) ($input['action'] ?? $_POST['action'] ?? 'finalize');
if ($action === 'upload-audio') {
    requireCsrf($_POST);
} else {
    requireCsrf($input);
}
$user = requireUser();
$bill = billingUser($user);
$userId = (int) $user['id'];
ensureNotesSchema();
$pdo = db();

if ($action === 'upload-audio') {
    $id = (int) ($_POST['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT id FROM notes_entries WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    if (!$stmt->fetch()) jsonResponse(['error' => 'Note not found.'], 404);
    $file = $_FILES['audio'] ?? null;
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) jsonResponse(['error' => 'No audio received.'], 422);
    if ($file['size'] <= 0 || $file['size'] > 25 * 1024 * 1024) jsonResponse(['error' => 'Audio must be under 25MB.'], 422);
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $ext = ['audio/webm' => 'webm', 'audio/mp4' => 'm4a', 'audio/ogg' => 'ogg', 'audio/wav' => 'wav', 'audio/x-wav' => 'wav'][$mime] ?? null;
    if (!$ext) jsonResponse(['error' => 'Unsupported audio format.'], 422);
    notes_delete_audio($userId, $id);
    $dest = notes_audio_dir() . '/' . $userId . '_' . $id . '.' . $ext;
    if (!@move_uploaded_file($file['tmp_name'], $dest)) jsonResponse(['error' => 'Could not store audio.'], 500);
    @chmod($dest, 0640);
    jsonResponse(['ok' => true, 'has_audio' => true]);
}

if ($action === 'list') {
    $stmt = $pdo->prepare('SELECT id, title, transcript, created_at FROM notes_entries WHERE user_id = ? ORDER BY id DESC LIMIT 200');
    $stmt->execute([$userId]);
    $audioIds = notes_audio_ids($userId);
    $entries = [];
    foreach ($stmt->fetchAll() as $row) {
        $entry = notes_public_row($row, false);
        $entry['has_audio'] = isset($audioIds[$entry['id']]);
        $entries[] = $entry;
    }
    jsonResponse(['entries' => $entries]);
}

if ($action === 'get') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT id, title, transcript, created_at FROM notes_entries WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    $row = $stmt->fetch();
    if (!$row) jsonResponse(['error' => 'Note not found.'], 404);
    $entry = notes_public_row($row, true);
    $entry['has_audio'] = isset(notes_audio_ids($userId)[$entry['id']]);
    jsonResponse(['entry' => $entry]);
}

if ($action === 'delete') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM notes_entries WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Note not found.'], 404);
    notes_delete_audio($userId, $id);
    jsonResponse(['ok' => true]);
}

if ($action === 'update') {
    $id = (int) ($input['id'] ?? 0);
    $transcript = (string) ($input['transcript'] ?? '');
    if (mb_strlen($transcript) > 100000) jsonResponse(['error' => 'That note is too long to save.'], 422);
    if (trim($transcript) === '') jsonResponse(['error' => 'Cannot save an empty note.'], 422);
    $title = mb_substr(preg_replace('/\s+/', ' ', trim($transcript)), 0, 60);
    $stmt = $pdo->prepare('UPDATE notes_entries SET title = ?, transcript = ? WHERE id = ? AND user_id = ?');
    $stmt->execute([$title, $transcript, $id, $userId]);
    if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Note not found.'], 404);
    jsonResponse(['ok' => true, 'title' => $title]);
}

if ($action === 'finalize') {
    $transcript = (string) ($input['transcript'] ?? '');
    if (mb_strlen($transcript) > 100000) jsonResponse(['error' => 'That note is too long to save.'], 422);
    $idempotency = notes_idempotency($input);
    $count = consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), 'notes', $idempotency);
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
        'usage' => usageFor($bill),
        'duplicate' => (bool) ($count['duplicate'] ?? false),
    ]);
}

jsonResponse(['error' => 'Unknown action.'], 400);
