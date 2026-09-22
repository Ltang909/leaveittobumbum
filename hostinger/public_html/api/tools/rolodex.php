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
