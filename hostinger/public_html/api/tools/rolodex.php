<?php
// Bum Bum Rolodex API. Tiny CRM for solopreneurs: contacts, pipeline stages,
// follow-up queue, interaction timeline. Scoped to the signed-in user.
// One metered action per new contact; updates and logging are free so the
// follow-up loop stays frictionless.
require dirname(__DIR__) . '/_bootstrap.php';

const ROLODEX_STAGES = ['new', 'talking', 'quoted', 'won', 'lost'];
const ROLODEX_ACTIVE = ['new', 'talking', 'quoted'];

function ensureRolodexSchema(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS rolodex_contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        name VARCHAR(80) NOT NULL,
        contact_info VARCHAR(191) NOT NULL DEFAULT '',
        source VARCHAR(191) NOT NULL DEFAULT '',
        deal_value DECIMAL(12,2) NULL,
        stage VARCHAR(16) NOT NULL DEFAULT 'new',
        follow_up_date DATE NULL,
        last_touch_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        KEY idx_user (user_id),
        KEY idx_user_followup (user_id, follow_up_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    db()->exec("CREATE TABLE IF NOT EXISTS rolodex_notes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        contact_id INT NOT NULL,
        user_id INT NOT NULL,
        body MEDIUMTEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        KEY idx_contact (contact_id),
        KEY idx_user (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function rolodex_idempotency(array $input): string {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
    return $key;
}

function rolodex_clean_date($v) {
    $v = trim((string) ($v ?? ''));
    if ($v === '') return null;
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) ? $v : false;
}

function rolodex_public_contact(array $row): array {
    return [
        'id' => (int) $row['id'],
        'name' => $row['name'],
        'contact_info' => $row['contact_info'],
        'source' => $row['source'],
        'deal_value' => $row['deal_value'] === null ? null : (float) $row['deal_value'],
        'stage' => $row['stage'],
        'follow_up_date' => $row['follow_up_date'],
        'last_touch_at' => $row['last_touch_at'],
        'created_at' => $row['created_at'],
    ];
}

function rolodex_draft(array $c): string {
    $name = $c['name'];
    $src = trim($c['source']) !== '' ? ' at ' . trim($c['source']) : '';
    switch ($c['stage']) {
        case 'quoted':
            return "Hi {$name}, just checking in on the proposal I sent over. Happy to walk through anything or tweak the scope. What works for a quick call this week?";
        case 'talking':
            return "Hi {$name}, circling back on our conversation{$src}. Any thoughts on next steps? No rush, just didn't want this one to slip.";
        default:
            return "Hi {$name}, loved meeting you{$src}! Wanted to follow up and see if there's a good time to chat more. Happy to work around your schedule.";
    }
}

requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
$bill = billingUser($user);
$userId = (int) $user['id'];
ensureRolodexSchema();
$pdo = db();
$action = (string) ($input['action'] ?? 'list');

if ($action === 'list') {
    $stmt = $pdo->prepare('SELECT * FROM rolodex_contacts WHERE user_id = ? ORDER BY follow_up_date IS NULL, follow_up_date ASC, updated_at DESC LIMIT 500');
    $stmt->execute([$userId]);
    $contacts = [];
    foreach ($stmt->fetchAll() as $row) $contacts[] = rolodex_public_contact($row);
    $today = date('Y-m-d');
    $due = [];
    $quiet = [];
    foreach ($contacts as $c) {
        if (!in_array($c['stage'], ROLODEX_ACTIVE, true)) continue;
        if ($c['follow_up_date'] !== null && $c['follow_up_date'] <= $today) {
            $c['draft'] = rolodex_draft($c);
            $due[] = $c;
        } elseif (($c['follow_up_date'] === null) && in_array($c['stage'], ['talking', 'quoted'], true)) {
            $touch = $c['last_touch_at'] ?? $c['created_at'];
            if ($touch && (time() - strtotime($touch)) > 14 * 86400) {
                $c['draft'] = rolodex_draft($c);
                $quiet[] = $c;
            }
        }
    }
    $counts = array_fill_keys(ROLODEX_STAGES, 0);
    foreach ($contacts as $c) $counts[$c['stage']]++;
    jsonResponse(['contacts' => $contacts, 'followUpDue' => $due, 'goneQuiet' => $quiet, 'counts' => $counts, 'today' => $today]);
}

if ($action === 'get') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT * FROM rolodex_contacts WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    $row = $stmt->fetch();
    if (!$row) jsonResponse(['error' => 'Contact not found.'], 404);
    $stmt = $pdo->prepare('SELECT id, body, created_at FROM rolodex_notes WHERE contact_id = ? AND user_id = ? ORDER BY id DESC LIMIT 100');
    $stmt->execute([$id, $userId]);
    $notes = [];
    foreach ($stmt->fetchAll() as $n) $notes[] = ['id' => (int) $n['id'], 'body' => $n['body'], 'created_at' => $n['created_at']];
    $contact = rolodex_public_contact($row);
    $contact['draft'] = rolodex_draft($contact);
    jsonResponse(['contact' => $contact, 'notes' => $notes]);
}

if ($action === 'add') {
    $name = trim((string) ($input['name'] ?? ''));
    $contactInfo = trim((string) ($input['contact_info'] ?? ''));
    $source = trim((string) ($input['source'] ?? ''));
    $dealRaw = trim((string) ($input['deal_value'] ?? ''));
    $stage = (string) ($input['stage'] ?? 'new');
    $followUp = rolodex_clean_date($input['follow_up_date'] ?? null);
    if ($name === '') jsonResponse(['error' => 'Give the contact a name.'], 422);
    if (mb_strlen($name) > 80) jsonResponse(['error' => 'Keep the name under 80 characters.'], 422);
    if (mb_strlen($contactInfo) > 191 || mb_strlen($source) > 191) jsonResponse(['error' => 'Keep contact info and source under 191 characters each.'], 422);
    if (!in_array($stage, ROLODEX_STAGES, true)) jsonResponse(['error' => 'Pick a valid stage.'], 422);
    if ($followUp === false) jsonResponse(['error' => 'Pick a valid follow-up date.'], 422);
    $dealValue = null;
    if ($dealRaw !== '') {
        $dealValue = filter_var($dealRaw, FILTER_VALIDATE_FLOAT);
        if ($dealValue === false || $dealValue < 0 || $dealValue > 100000000) jsonResponse(['error' => 'Enter a valid deal value of zero or more.'], 422);
    }
    $idempotency = rolodex_idempotency($input);
    $count = consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), 'rolodex', $idempotency);
    if (!empty($count['limit_reached'])) jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $count], 402);
    $contact = null;
    if (empty($count['duplicate'])) {
        $stmt = $pdo->prepare('INSERT INTO rolodex_contacts (user_id, name, contact_info, source, deal_value, stage, follow_up_date, last_touch_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([$userId, $name, $contactInfo, $source, $dealValue, $stage, $followUp]);
        $id = (int) $pdo->lastInsertId();
        $stmt = $pdo->prepare('SELECT * FROM rolodex_contacts WHERE id = ?');
        $stmt->execute([$id]);
        $contact = rolodex_public_contact($stmt->fetch());
    }
    jsonResponse(['contact' => $contact, 'usage' => usageFor($bill), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
}

if ($action === 'import') {
    $csv = (string) ($input['csv'] ?? '');
    if (trim($csv) === '') jsonResponse(['error' => 'The file looks empty.'], 422);
    if (strlen($csv) > 1000000) jsonResponse(['error' => 'Keep the CSV under 1 MB.'], 422);
    $batchKey = rolodex_idempotency($input);
    $stream = fopen('php://memory', 'r+');
    fwrite($stream, $csv);
    rewind($stream);
    $aliases = [
        'name' => 'name', 'full name' => 'name',
        'contact_info' => 'contact_info', 'contact' => 'contact_info', 'email' => 'contact_info', 'phone' => 'contact_info',
        'source' => 'source', 'where you met' => 'source', 'where we met' => 'source',
        'deal_value' => 'deal_value', 'value' => 'deal_value', 'deal' => 'deal_value',
        'stage' => 'stage',
        'follow_up_date' => 'follow_up_date', 'follow up' => 'follow_up_date', 'followup' => 'follow_up_date',
    ];
    $header = fgetcsv($stream);
    if (!$header) jsonResponse(['error' => 'Could not read the CSV header row.'], 422);
    $cols = [];
    foreach ($header as $h) {
        $k = strtolower(trim((string) $h));
        $cols[] = $aliases[$k] ?? null;
    }
    if (!in_array('name', $cols, true)) jsonResponse(['error' => 'The CSV needs a "name" column.', 'hint' => 'Columns: name, contact_info, source, deal_value, stage, follow_up_date'], 422);
    $imported = 0;
    $updated = 0;
    $skipped = 0;
    $errors = [];
    $rows = 0;
    $limitHit = false;
    while (($row = fgetcsv($stream)) !== false && $rows < 200) {
        $rows++;
        $rec = ['name' => '', 'contact_info' => '', 'source' => '', 'deal_value' => '', 'stage' => '', 'follow_up_date' => ''];
        foreach ($cols as $i => $field) {
            if ($field === null) continue;
            $rec[$field] = trim((string) ($row[$i] ?? ''));
        }
        if ($rec['name'] === '' || mb_strlen($rec['name']) > 80) { $skipped++; if (count($errors) < 20) $errors[] = "Row $rows: missing or too-long name."; continue; }
        if (mb_strlen($rec['contact_info']) > 191 || mb_strlen($rec['source']) > 191) { $skipped++; if (count($errors) < 20) $errors[] = "Row $rows: contact info or source too long."; continue; }
        $stage = strtolower($rec['stage']);
        $stageValid = in_array($stage, ROLODEX_STAGES, true);
        $dealValue = null;
        $dealRaw = $rec['deal_value'];
        if ($dealRaw !== '') {
            $dealValue = filter_var($dealRaw, FILTER_VALIDATE_FLOAT);
            if ($dealValue === false || $dealValue < 0 || $dealValue > 100000000) { $skipped++; if (count($errors) < 20) $errors[] = "Row $rows: invalid deal value."; continue; }
        }
        $followUp = $rec['follow_up_date'] === '' ? null : rolodex_clean_date($rec['follow_up_date']);
        if ($followUp === false) { $skipped++; if (count($errors) < 20) $errors[] = "Row $rows: invalid follow-up date (use YYYY-MM-DD)."; continue; }
        // Match existing contact by name (case-insensitive). Empty cells leave existing values alone.
        $stmt = $pdo->prepare('SELECT id FROM rolodex_contacts WHERE user_id = ? AND LOWER(name) = LOWER(?) LIMIT 1');
        $stmt->execute([$userId, $rec['name']]);
        $existing = $stmt->fetch();
        if ($existing) {
            $sets = [];
            $params = [];
            if ($rec['contact_info'] !== '') { $sets[] = 'contact_info = ?'; $params[] = $rec['contact_info']; }
            if ($rec['source'] !== '') { $sets[] = 'source = ?'; $params[] = $rec['source']; }
            if ($dealRaw !== '') { $sets[] = 'deal_value = ?'; $params[] = $dealValue; }
            if ($stageValid) { $sets[] = 'stage = ?'; $params[] = $stage; if (in_array($stage, ['won', 'lost'], true)) $sets[] = 'follow_up_date = NULL'; }
            if ($followUp !== null) { $sets[] = 'follow_up_date = ?'; $params[] = $followUp; }
            if ($sets) {
                $params[] = $existing['id'];
                $params[] = $userId;
                $stmt = $pdo->prepare('UPDATE rolodex_contacts SET ' . implode(', ', $sets) . ' WHERE id = ? AND user_id = ?');
                $stmt->execute($params);
            }
            $updated++;
            continue;
        }
        $count = consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), 'rolodex', $batchKey . '-' . $rows);
        if (!empty($count['limit_reached'])) { $limitHit = true; break; }
        if (empty($count['duplicate'])) {
            $stmt = $pdo->prepare('INSERT INTO rolodex_contacts (user_id, name, contact_info, source, deal_value, stage, follow_up_date, last_touch_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())');
            $stmt->execute([$userId, $rec['name'], $rec['contact_info'], $rec['source'], $dealValue, $stageValid ? $stage : 'new', $followUp]);
            $imported++;
        }
    }
    fclose($stream);
    if ($limitHit) jsonResponse(['error' => 'You ran out of actions partway through. ' . $imported . ' new contacts added, ' . $updated . ' updated, ' . $skipped . ' skipped.', 'imported' => $imported, 'updated' => $updated, 'skipped' => $skipped, 'usage' => usageFor($bill)], 402);
    jsonResponse(['imported' => $imported, 'updated' => $updated, 'skipped' => $skipped, 'errors' => $errors, 'usage' => usageFor($bill)]);
}

if ($action === 'export') {
    $stmt = $pdo->prepare('SELECT name, contact_info, source, deal_value, stage, follow_up_date FROM rolodex_contacts WHERE user_id = ? ORDER BY name ASC LIMIT 2000');
    $stmt->execute([$userId]);
    $stream = fopen('php://memory', 'r+');
    fputcsv($stream, ['name', 'contact_info', 'source', 'deal_value', 'stage', 'follow_up_date']);
    foreach ($stmt->fetchAll() as $row) {
        fputcsv($stream, [
            $row['name'],
            $row['contact_info'],
            $row['source'],
            $row['deal_value'] === null ? '' : (string) (float) $row['deal_value'],
            $row['stage'],
            $row['follow_up_date'] ?? '',
        ]);
    }
    rewind($stream);
    $csv = stream_get_contents($stream);
    fclose($stream);
    jsonResponse(['csv' => $csv, 'filename' => 'rolodex-export-' . date('Y-m-d') . '.csv']);
}

if ($action === 'update') {
    $id = (int) ($input['id'] ?? 0);
    $fields = [];
    $params = [];
    if (array_key_exists('stage', $input)) {
        $stage = (string) $input['stage'];
        if (!in_array($stage, ROLODEX_STAGES, true)) jsonResponse(['error' => 'Pick a valid stage.'], 422);
        $fields[] = 'stage = ?';
        $params[] = $stage;
        if (in_array($stage, ['won', 'lost'], true)) { $fields[] = 'follow_up_date = NULL'; }
    }
    if (array_key_exists('follow_up_date', $input)) {
        $followUp = rolodex_clean_date($input['follow_up_date']);
        if ($followUp === false) jsonResponse(['error' => 'Pick a valid follow-up date.'], 422);
        $fields[] = 'follow_up_date = ?';
        $params[] = $followUp;
    }
    if (array_key_exists('name', $input)) {
        $name = trim((string) $input['name']);
        if ($name === '' || mb_strlen($name) > 80) jsonResponse(['error' => 'Give the contact a valid name.'], 422);
        $fields[] = 'name = ?';
        $params[] = $name;
    }
    if (array_key_exists('deal_value', $input)) {
        $dealRaw = trim((string) $input['deal_value']);
        $dealValue = null;
        if ($dealRaw !== '') {
            $dealValue = filter_var($dealRaw, FILTER_VALIDATE_FLOAT);
            if ($dealValue === false || $dealValue < 0 || $dealValue > 100000000) jsonResponse(['error' => 'Enter a valid deal value.'], 422);
        }
        $fields[] = 'deal_value = ?';
        $params[] = $dealValue;
    }
    if (!$fields) jsonResponse(['error' => 'Nothing to update.'], 422);
    $params[] = $id;
    $params[] = $userId;
    $stmt = $pdo->prepare('UPDATE rolodex_contacts SET ' . implode(', ', $fields) . ' WHERE id = ? AND user_id = ?');
    $stmt->execute($params);
    if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Contact not found.'], 404);
    jsonResponse(['ok' => true]);
}

if ($action === 'log') {
    $id = (int) ($input['id'] ?? 0);
    $body = trim((string) ($input['body'] ?? ''));
    if ($body === '') jsonResponse(['error' => 'Write a note about the interaction.'], 422);
    if (mb_strlen($body) > 5000) jsonResponse(['error' => 'Keep the note under 5000 characters.'], 422);
    $stmt = $pdo->prepare('SELECT id FROM rolodex_contacts WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    if (!$stmt->fetch()) jsonResponse(['error' => 'Contact not found.'], 404);
    $stmt = $pdo->prepare('INSERT INTO rolodex_notes (contact_id, user_id, body) VALUES (?, ?, ?)');
    $stmt->execute([$id, $userId, $body]);
    $nextFollowUp = null;
    if (array_key_exists('follow_up_date', $input)) {
        $nextFollowUp = rolodex_clean_date($input['follow_up_date']);
        if ($nextFollowUp === false) jsonResponse(['error' => 'Pick a valid follow-up date.'], 422);
    }
    if ($nextFollowUp !== null) {
        $stmt = $pdo->prepare('UPDATE rolodex_contacts SET follow_up_date = ?, last_touch_at = NOW() WHERE id = ? AND user_id = ?');
        $stmt->execute([$nextFollowUp, $id, $userId]);
    } else {
        $stmt = $pdo->prepare('UPDATE rolodex_contacts SET last_touch_at = NOW() WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
    }
    jsonResponse(['ok' => true, 'note_id' => (int) $pdo->lastInsertId()]);
}

if ($action === 'delete') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM rolodex_notes WHERE contact_id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    $stmt = $pdo->prepare('DELETE FROM rolodex_contacts WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Contact not found.'], 404);
    jsonResponse(['ok' => true]);
}

jsonResponse(['error' => 'Unknown action.'], 400);
