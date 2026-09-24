<?php
// Bum Bum Corporate Bum Bum API. Job application tracker: applications,
// pipeline stages, follow-up queue, interaction timeline. Scoped to the
// signed-in user. One metered action per new application; updates and
// logging are free so the follow-up loop stays frictionless.
require dirname(__DIR__) . '/_bootstrap.php';

const JOBTRACK_STAGES = ['wishlist', 'applied', 'screening', 'interview', 'final', 'offer', 'accepted', 'rejected', 'withdrawn'];
const JOBTRACK_CURRENCIES = ['CAD', 'USD', 'EUR', 'GBP'];
const JOBTRACK_TERMINAL = ['accepted', 'rejected', 'withdrawn'];
const JOBTRACK_ACTIVE = ['wishlist', 'applied', 'screening', 'interview', 'final', 'offer'];

function ensureJobtrackSchema(): void {
    $pdo = db();
    $pdo->exec("CREATE TABLE IF NOT EXISTS jobtrack_contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        company VARCHAR(191) NOT NULL,
        role VARCHAR(191) NOT NULL DEFAULT '',
        contact_name VARCHAR(191) NOT NULL DEFAULT '',
        contact_email VARCHAR(191) NOT NULL DEFAULT '',
        job_url VARCHAR(500) NOT NULL DEFAULT '',
        location VARCHAR(191) NOT NULL DEFAULT '',
        salary_min DECIMAL(12,2) NULL,
        salary_max DECIMAL(12,2) NULL,
        currency VARCHAR(3) NULL,
        source VARCHAR(191) NOT NULL DEFAULT '',
        date_applied DATE NULL,
        stage VARCHAR(16) NOT NULL DEFAULT 'applied',
        follow_up_date DATE NULL,
        notes MEDIUMTEXT NOT NULL DEFAULT '',
        last_touch_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        KEY idx_user (user_id),
        KEY idx_user_followup (user_id, follow_up_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    // Migration for tables created before the notes column existed.
    $hasNotes = $pdo->query("SHOW COLUMNS FROM jobtrack_contacts LIKE 'notes'")->fetch();
    if (!$hasNotes) $pdo->exec("ALTER TABLE jobtrack_contacts ADD COLUMN notes MEDIUMTEXT NOT NULL DEFAULT ''");
    // Migration for tables created before the currency column existed.
    // Backfill existing rows as CAD per the user's call: everything is CAD now.
    $hasCurrency = $pdo->query("SHOW COLUMNS FROM jobtrack_contacts LIKE 'currency'")->fetch();
    if (!$hasCurrency) {
        $pdo->exec("ALTER TABLE jobtrack_contacts ADD COLUMN currency VARCHAR(3) NULL");
        $pdo->exec("UPDATE jobtrack_contacts SET currency = 'CAD' WHERE currency IS NULL");
    }
    $pdo->exec("CREATE TABLE IF NOT EXISTS jobtrack_notes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        contact_id INT NOT NULL,
        user_id INT NOT NULL,
        body MEDIUMTEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        KEY idx_contact (contact_id),
        KEY idx_user (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function jobtrack_idempotency(array $input): string {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
    return $key;
}

function jobtrack_clean_date($v) {
    $v = trim((string) ($v ?? ''));
    if ($v === '') return null;
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) ? $v : false;
}

function jobtrack_clean_currency($v) {
    $v = strtoupper(trim((string) ($v ?? '')));
    return in_array($v, JOBTRACK_CURRENCIES, true) ? $v : 'CAD';
}

function jobtrack_clean_salary($v) {
    $v = trim((string) ($v ?? ''));
    if ($v === '') return null;
    $n = filter_var($v, FILTER_VALIDATE_FLOAT);
    if ($n === false || $n < 0 || $n > 100000000) return false;
    return $n;
}

function jobtrack_public_contact(array $row): array {
    return [
        'id' => (int) $row['id'],
        'company' => $row['company'],
        'role' => $row['role'] ?? '',
        'contact_name' => $row['contact_name'] ?? '',
        'contact_email' => $row['contact_email'] ?? '',
        'job_url' => $row['job_url'] ?? '',
        'location' => $row['location'] ?? '',
        'salary_min' => $row['salary_min'] === null ? null : (float) $row['salary_min'],
        'salary_max' => $row['salary_max'] === null ? null : (float) $row['salary_max'],
        'currency' => $row['currency'] ?? 'CAD',
        'source' => $row['source'],
        'date_applied' => $row['date_applied'],
        'stage' => $row['stage'],
        'follow_up_date' => $row['follow_up_date'],
        'notes' => $row['notes'] ?? '',
        'last_touch_at' => $row['last_touch_at'],
        'created_at' => $row['created_at'],
    ];
}

function jobtrack_greeting(array $c): string {
    $name = trim($c['contact_name']);
    return $name !== '' ? "Hi {$name}," : 'Hi there,';
}

function jobtrack_draft(array $c): string {
    $greet = jobtrack_greeting($c);
    $role = trim($c['role']) !== '' ? 'the ' . trim($c['role']) . ' role' : 'the role';
    $co = trim($c['company']);
    switch ($c['stage']) {
        case 'wishlist':
            return "{$greet} I came across {$role} at {$co} and it looks like a strong match for my background. Would you be open to a quick chat about what you are looking for?";
        case 'applied':
            return "{$greet} I just applied for {$role} at {$co} and wanted to put a face to the application. Happy to share anything helpful as you review candidates.";
        case 'screening':
            return "{$greet} thanks for the great screening conversation about {$role}. I am excited about {$co} and the team. Let me know if there is anything else I can share.";
        case 'interview':
        case 'final':
            return "{$greet} thank you for taking the time to speak with me about {$role}. I really enjoyed our conversation and I am excited about the possibility of joining {$co}.";
        case 'offer':
            return "{$greet} thank you so much for the offer for {$role}. I am thrilled about {$co}. I would love to discuss a couple of details before I sign.";
        default:
            return "{$greet} just circling back on {$role} at {$co}. Still very interested. Any updates on your end?";
    }
}

requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
$bill = billingUser($user);
$userId = (int) $user['id'];
ensureJobtrackSchema();
$pdo = db();
$action = (string) ($input['action'] ?? 'list');

if ($action === 'list') {
    $stmt = $pdo->prepare('SELECT * FROM jobtrack_contacts WHERE user_id = ? ORDER BY follow_up_date IS NULL, follow_up_date ASC, updated_at DESC LIMIT 500');
    $stmt->execute([$userId]);
    $contacts = [];
    foreach ($stmt->fetchAll() as $row) $contacts[] = jobtrack_public_contact($row);
    $today = date('Y-m-d');
    $due = [];
    $quiet = [];
    foreach ($contacts as $c) {
        if (!in_array($c['stage'], JOBTRACK_ACTIVE, true)) continue;
        if ($c['follow_up_date'] !== null && $c['follow_up_date'] <= $today) {
            $c['draft'] = jobtrack_draft($c);
            $due[] = $c;
        } elseif (($c['follow_up_date'] === null) && in_array($c['stage'], ['applied', 'screening', 'interview', 'final', 'offer'], true)) {
            $touch = $c['last_touch_at'] ?? $c['created_at'];
            if ($touch && (time() - strtotime($touch)) > 14 * 86400) {
                $c['draft'] = jobtrack_draft($c);
                $quiet[] = $c;
            }
        }
    }
    $counts = array_fill_keys(JOBTRACK_STAGES, 0);
    foreach ($contacts as $c) $counts[$c['stage']]++;
    jsonResponse(['contacts' => $contacts, 'followUpDue' => $due, 'goneQuiet' => $quiet, 'counts' => $counts, 'today' => $today]);
}

if ($action === 'get') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT * FROM jobtrack_contacts WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    $row = $stmt->fetch();
    if (!$row) jsonResponse(['error' => 'Application not found.'], 404);
    $stmt = $pdo->prepare('SELECT id, body, created_at FROM jobtrack_notes WHERE contact_id = ? AND user_id = ? ORDER BY id DESC LIMIT 100');
    $stmt->execute([$id, $userId]);
    $notes = [];
    foreach ($stmt->fetchAll() as $n) $notes[] = ['id' => (int) $n['id'], 'body' => $n['body'], 'created_at' => $n['created_at']];
    $contact = jobtrack_public_contact($row);
    $contact['draft'] = jobtrack_draft($contact);
    jsonResponse(['contact' => $contact, 'notes' => $notes]);
}

if ($action === 'add') {
    $company = trim((string) ($input['company'] ?? ''));
    $role = trim((string) ($input['role'] ?? ''));
    $contactName = trim((string) ($input['contact_name'] ?? ''));
    $contactEmail = trim((string) ($input['contact_email'] ?? ''));
    $jobUrl = trim((string) ($input['job_url'] ?? ''));
    $location = trim((string) ($input['location'] ?? ''));
    $salaryMin = jobtrack_clean_salary($input['salary_min'] ?? null);
    $salaryMax = jobtrack_clean_salary($input['salary_max'] ?? null);
    $currency = jobtrack_clean_currency($input['currency'] ?? 'CAD');
    $source = trim((string) ($input['source'] ?? ''));
    $dateApplied = jobtrack_clean_date($input['date_applied'] ?? null);
    $stage = (string) ($input['stage'] ?? 'applied');
    $followUp = jobtrack_clean_date($input['follow_up_date'] ?? null);
    $notes = trim((string) ($input['notes'] ?? ''));
    if ($company === '') jsonResponse(['error' => 'Give the application a company.'], 422);
    if (mb_strlen($company) > 191 || mb_strlen($role) > 191 || mb_strlen($contactName) > 191 || mb_strlen($contactEmail) > 191 || mb_strlen($location) > 191 || mb_strlen($source) > 191) jsonResponse(['error' => 'Keep company, role, contact, location and source under 191 characters each.'], 422);
    if (mb_strlen($jobUrl) > 500) jsonResponse(['error' => 'That job URL is too long.'], 422);
    if ($jobUrl !== '' && !preg_match('/^https?:\/\//i', $jobUrl)) $jobUrl = 'https://' . $jobUrl;
    if (mb_strlen($notes) > 5000) jsonResponse(['error' => 'Keep the note under 5000 characters.'], 422);
    if (!in_array($stage, JOBTRACK_STAGES, true)) jsonResponse(['error' => 'Pick a valid stage.'], 422);
    if ($followUp === false) jsonResponse(['error' => 'Pick a valid follow-up date.'], 422);
    if ($dateApplied === false) jsonResponse(['error' => 'Pick a valid applied date.'], 422);
    if ($salaryMin === false || $salaryMax === false) jsonResponse(['error' => 'Enter valid salary numbers.'], 422);
    $idempotency = jobtrack_idempotency($input);
    $count = consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), 'corporate-bum-bum', $idempotency);
    if (!empty($count['limit_reached'])) jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $count], 402);
    $contact = null;
    if (empty($count['duplicate'])) {
        $stmt = $pdo->prepare('INSERT INTO jobtrack_contacts (user_id, company, role, contact_name, contact_email, job_url, location, salary_min, salary_max, currency, source, date_applied, stage, follow_up_date, notes, last_touch_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([$userId, $company, $role, $contactName, $contactEmail, $jobUrl, $location, $salaryMin, $salaryMax, $currency, $source, $dateApplied, $stage, $followUp, $notes]);
        $id = (int) $pdo->lastInsertId();
        $stmt = $pdo->prepare('SELECT * FROM jobtrack_contacts WHERE id = ?');
        $stmt->execute([$id]);
        $contact = jobtrack_public_contact($stmt->fetch());
    }
    jsonResponse(['contact' => $contact, 'usage' => usageFor($bill), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
}

if ($action === 'import') {
    $csv = (string) ($input['csv'] ?? '');
    if (trim($csv) === '') jsonResponse(['error' => 'The file looks empty.'], 422);
    if (strlen($csv) > 1000000) jsonResponse(['error' => 'Keep the CSV under 1 MB.'], 422);
    $batchKey = jobtrack_idempotency($input);
    $stream = fopen('php://memory', 'r+');
    fwrite($stream, $csv);
    rewind($stream);
    $aliases = [
        'company' => 'company', 'employer' => 'company', 'organization' => 'company', 'organisation' => 'company',
        'role' => 'role', 'job title' => 'role', 'title' => 'role', 'position' => 'role',
        'contact name' => 'contact_name', 'name' => 'contact_name', 'contact' => 'contact_name', 'hiring manager' => 'contact_name', 'recruiter' => 'contact_name',
        'contact email' => 'contact_email', 'email' => 'contact_email',
        'job url' => 'job_url', 'posting url' => 'job_url', 'url' => 'job_url', 'link' => 'job_url',
        'location' => 'location', 'where' => 'location',
        'salary min' => 'salary_min', 'min salary' => 'salary_min', 'salary' => 'salary_min',
        'salary max' => 'salary_max', 'max salary' => 'salary_max',
        'currency' => 'currency', 'curr' => 'currency',
        'where found' => 'source', 'where you found it' => 'source', 'source' => 'source',
        'date applied' => 'date_applied', 'applied' => 'date_applied', 'applied on' => 'date_applied',
        'stage' => 'stage',
        'follow up' => 'follow_up_date', 'follow_up_date' => 'follow_up_date', 'followup' => 'follow_up_date', 'follow up date' => 'follow_up_date',
        'notes' => 'notes', 'note' => 'notes',
    ];
    $header = fgetcsv($stream);
    if (!$header) jsonResponse(['error' => 'Could not read the CSV header row.'], 422);
    $cols = [];
    foreach ($header as $h) {
        $k = strtolower(trim((string) $h));
        $cols[] = $aliases[$k] ?? null;
    }
    if (!in_array('company', $cols, true)) jsonResponse(['error' => 'The CSV needs a "company" column.', 'hint' => 'Columns: company, role, contact name, contact email, job url, location, salary min, salary max, where found, date applied, stage, follow up, notes'], 422);
    $imported = 0;
    $updated = 0;
    $skipped = 0;
    $errors = [];
    $rows = 0;
    $limitHit = false;
    while (($row = fgetcsv($stream)) !== false && $rows < 200) {
        $rows++;
        $rec = ['company' => '', 'role' => '', 'contact_name' => '', 'contact_email' => '', 'job_url' => '', 'location' => '', 'salary_min' => '', 'salary_max' => '', 'currency' => '', 'source' => '', 'date_applied' => '', 'stage' => '', 'follow_up_date' => '', 'notes' => ''];
        foreach ($cols as $i => $field) {
            if ($field === null) continue;
            $rec[$field] = trim((string) ($row[$i] ?? ''));
        }
        if ($rec['company'] === '' || mb_strlen($rec['company']) > 191) { $skipped++; if (count($errors) < 20) $errors[] = "Row $rows: missing or too-long company."; continue; }
        if (mb_strlen($rec['role']) > 191 || mb_strlen($rec['contact_name']) > 191 || mb_strlen($rec['contact_email']) > 191 || mb_strlen($rec['location']) > 191 || mb_strlen($rec['source']) > 191) { $skipped++; if (count($errors) < 20) $errors[] = "Row $rows: a text field is too long."; continue; }
        if (mb_strlen($rec['notes']) > 5000) { $skipped++; if (count($errors) < 20) $errors[] = "Row $rows: notes too long (5000 max)."; continue; }
        $stage = strtolower($rec['stage']);
        $stageValid = in_array($stage, JOBTRACK_STAGES, true);
        $salaryMin = $rec['salary_min'] === '' ? null : jobtrack_clean_salary($rec['salary_min']);
        $salaryMax = $rec['salary_max'] === '' ? null : jobtrack_clean_salary($rec['salary_max']);
        if ($salaryMin === false || $salaryMax === false) { $skipped++; if (count($errors) < 20) $errors[] = "Row $rows: invalid salary."; continue; }
        $dateApplied = $rec['date_applied'] === '' ? null : jobtrack_clean_date($rec['date_applied']);
        if ($dateApplied === false) { $skipped++; if (count($errors) < 20) $errors[] = "Row $rows: invalid applied date (use YYYY-MM-DD)."; continue; }
        $followUp = $rec['follow_up_date'] === '' ? null : jobtrack_clean_date($rec['follow_up_date']);
        if ($followUp === false) { $skipped++; if (count($errors) < 20) $errors[] = "Row $rows: invalid follow-up date (use YYYY-MM-DD)."; continue; }
        // Match existing application by company + role (case-insensitive). Empty cells leave existing values alone.
        $stmt = $pdo->prepare('SELECT id FROM jobtrack_contacts WHERE user_id = ? AND LOWER(company) = LOWER(?) AND LOWER(role) = LOWER(?) LIMIT 1');
        $stmt->execute([$userId, $rec['company'], $rec['role']]);
        $existing = $stmt->fetch();
        if ($existing) {
            $sets = [];
            $params = [];
            if ($rec['contact_name'] !== '') { $sets[] = 'contact_name = ?'; $params[] = $rec['contact_name']; }
            if ($rec['contact_email'] !== '') { $sets[] = 'contact_email = ?'; $params[] = $rec['contact_email']; }
            if ($rec['job_url'] !== '') { $sets[] = 'job_url = ?'; $params[] = $rec['job_url']; }
            if ($rec['location'] !== '') { $sets[] = 'location = ?'; $params[] = $rec['location']; }
            if ($rec['salary_min'] !== '') { $sets[] = 'salary_min = ?'; $params[] = $salaryMin; }
            if ($rec['salary_max'] !== '') { $sets[] = 'salary_max = ?'; $params[] = $salaryMax; }
            if ($rec['currency'] !== '') { $sets[] = 'currency = ?'; $params[] = jobtrack_clean_currency($rec['currency']); }
            if ($rec['source'] !== '') { $sets[] = 'source = ?'; $params[] = $rec['source']; }
            if ($dateApplied !== null) { $sets[] = 'date_applied = ?'; $params[] = $dateApplied; }
            if ($stageValid) { $sets[] = 'stage = ?'; $params[] = $stage; if (in_array($stage, JOBTRACK_TERMINAL, true)) $sets[] = 'follow_up_date = NULL'; }
            if ($followUp !== null) { $sets[] = 'follow_up_date = ?'; $params[] = $followUp; }
            if ($rec['notes'] !== '') { $sets[] = 'notes = ?'; $params[] = $rec['notes']; }
            if ($sets) {
                $params[] = $existing['id'];
                $params[] = $userId;
                $stmt = $pdo->prepare('UPDATE jobtrack_contacts SET ' . implode(', ', $sets) . ' WHERE id = ? AND user_id = ?');
                $stmt->execute($params);
            }
            $updated++;
            continue;
        }
        $count = consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), 'corporate-bum-bum', $batchKey . '-' . $rows);
        if (!empty($count['limit_reached'])) { $limitHit = true; break; }
        if (empty($count['duplicate'])) {
            $stmt = $pdo->prepare('INSERT INTO jobtrack_contacts (user_id, company, role, contact_name, contact_email, job_url, location, salary_min, salary_max, currency, source, date_applied, stage, follow_up_date, notes, last_touch_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
            $stmt->execute([$userId, $rec['company'], $rec['role'], $rec['contact_name'], $rec['contact_email'], $rec['job_url'], $rec['location'], $salaryMin, $salaryMax, jobtrack_clean_currency($rec['currency']), $rec['source'], $dateApplied, $stageValid ? $stage : 'applied', $followUp, $rec['notes']]);
            $imported++;
        }
    }
    fclose($stream);
    if ($limitHit) jsonResponse(['error' => 'You ran out of actions partway through. ' . $imported . ' new applications added, ' . $updated . ' updated, ' . $skipped . ' skipped.', 'imported' => $imported, 'updated' => $updated, 'skipped' => $skipped, 'usage' => usageFor($bill)], 402);
    jsonResponse(['imported' => $imported, 'updated' => $updated, 'skipped' => $skipped, 'errors' => $errors, 'usage' => usageFor($bill)]);
}

if ($action === 'export') {
    $stmt = $pdo->prepare('SELECT * FROM jobtrack_contacts WHERE user_id = ? ORDER BY company ASC LIMIT 2000');
    $stmt->execute([$userId]);
    $contacts = $stmt->fetchAll();
    $stream = fopen('php://memory', 'r+');
    fputcsv($stream, ['company', 'role', 'contact name', 'contact email', 'job url', 'location', 'salary min', 'salary max', 'currency', 'where found', 'date applied', 'stage', 'follow up', 'notes']);
    foreach ($contacts as $row) {
        fputcsv($stream, [
            $row['company'],
            $row['role'],
            $row['contact_name'],
            $row['contact_email'],
            $row['job_url'],
            $row['location'],
            $row['salary_min'] === null ? '' : (string) (float) $row['salary_min'],
            $row['salary_max'] === null ? '' : (string) (float) $row['salary_max'],
            $row['currency'] ?? '',
            $row['source'],
            $row['date_applied'] ?? '',
            $row['stage'],
            $row['follow_up_date'] ?? '',
            $row['notes'] ?? '',
        ]);
    }
    rewind($stream);
    $csv = stream_get_contents($stream);
    fclose($stream);
    jsonResponse(['csv' => $csv, 'filename' => 'corporate-bum-bum-export-' . date('Y-m-d') . '.csv']);
}

if ($action === 'update') {
    $id = (int) ($input['id'] ?? 0);
    $fields = [];
    $params = [];
    if (array_key_exists('stage', $input)) {
        $stage = (string) $input['stage'];
        if (!in_array($stage, JOBTRACK_STAGES, true)) jsonResponse(['error' => 'Pick a valid stage.'], 422);
        $fields[] = 'stage = ?';
        $params[] = $stage;
        if (in_array($stage, JOBTRACK_TERMINAL, true)) { $fields[] = 'follow_up_date = NULL'; }
    }
    if (array_key_exists('follow_up_date', $input)) {
        $followUp = jobtrack_clean_date($input['follow_up_date']);
        if ($followUp === false) jsonResponse(['error' => 'Pick a valid follow-up date.'], 422);
        $fields[] = 'follow_up_date = ?';
        $params[] = $followUp;
    }
    if (array_key_exists('notes', $input)) {
        $notes = trim((string) $input['notes']);
        if (mb_strlen($notes) > 5000) jsonResponse(['error' => 'Keep the notes under 5000 characters.'], 422);
        $fields[] = 'notes = ?';
        $params[] = $notes;
    }
    if (array_key_exists('currency', $input)) {
        $fields[] = 'currency = ?';
        $params[] = jobtrack_clean_currency($input['currency']);
    }
    if (array_key_exists('source', $input)) {
        $v = trim((string) $input['source']);
        if (mb_strlen($v) > 191) jsonResponse(['error' => 'Keep where found under 191 characters.'], 422);
        $fields[] = 'source = ?';
        $params[] = $v;
    }
    if (array_key_exists('company', $input)) {
        $company = trim((string) $input['company']);
        if ($company === '' || mb_strlen($company) > 191) jsonResponse(['error' => 'Give the application a valid company.'], 422);
        $fields[] = 'company = ?';
        $params[] = $company;
    }
    if (array_key_exists('role', $input)) {
        $role = trim((string) $input['role']);
        if (mb_strlen($role) > 191) jsonResponse(['error' => 'Keep the role under 191 characters.'], 422);
        $fields[] = 'role = ?';
        $params[] = $role;
    }
    if (array_key_exists('contact_name', $input)) {
        $v = trim((string) $input['contact_name']);
        if (mb_strlen($v) > 191) jsonResponse(['error' => 'Keep the contact name under 191 characters.'], 422);
        $fields[] = 'contact_name = ?';
        $params[] = $v;
    }
    if (array_key_exists('contact_email', $input)) {
        $v = trim((string) $input['contact_email']);
        if (mb_strlen($v) > 191) jsonResponse(['error' => 'Keep the contact email under 191 characters.'], 422);
        $fields[] = 'contact_email = ?';
        $params[] = $v;
    }
    if (array_key_exists('job_url', $input)) {
        $v = trim((string) $input['job_url']);
        if (mb_strlen($v) > 500) jsonResponse(['error' => 'That job URL is too long.'], 422);
        if ($v !== '' && !preg_match('/^https?:\/\//i', $v)) $v = 'https://' . $v;
        $fields[] = 'job_url = ?';
        $params[] = $v;
    }
    if (array_key_exists('location', $input)) {
        $v = trim((string) $input['location']);
        if (mb_strlen($v) > 191) jsonResponse(['error' => 'Keep the location under 191 characters.'], 422);
        $fields[] = 'location = ?';
        $params[] = $v;
    }
    if (array_key_exists('salary_min', $input)) {
        $v = jobtrack_clean_salary($input['salary_min']);
        if ($v === false) jsonResponse(['error' => 'Enter a valid minimum salary.'], 422);
        $fields[] = 'salary_min = ?';
        $params[] = $v;
    }
    if (array_key_exists('salary_max', $input)) {
        $v = jobtrack_clean_salary($input['salary_max']);
        if ($v === false) jsonResponse(['error' => 'Enter a valid maximum salary.'], 422);
        $fields[] = 'salary_max = ?';
        $params[] = $v;
    }
    if (array_key_exists('date_applied', $input)) {
        $v = jobtrack_clean_date($input['date_applied']);
        if ($v === false) jsonResponse(['error' => 'Pick a valid applied date.'], 422);
        $fields[] = 'date_applied = ?';
        $params[] = $v;
    }
    if (!$fields) jsonResponse(['error' => 'Nothing to update.'], 422);
    $params[] = $id;
    $params[] = $userId;
    $stmt = $pdo->prepare('UPDATE jobtrack_contacts SET ' . implode(', ', $fields) . ' WHERE id = ? AND user_id = ?');
    $stmt->execute($params);
    if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Application not found.'], 404);
    jsonResponse(['ok' => true]);
}

if ($action === 'log') {
    $id = (int) ($input['id'] ?? 0);
    $body = trim((string) ($input['body'] ?? ''));
    if ($body === '') jsonResponse(['error' => 'Write a note about the interaction.'], 422);
    if (mb_strlen($body) > 5000) jsonResponse(['error' => 'Keep the note under 5000 characters.'], 422);
    $stmt = $pdo->prepare('SELECT id FROM jobtrack_contacts WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    if (!$stmt->fetch()) jsonResponse(['error' => 'Application not found.'], 404);
    $stmt = $pdo->prepare('INSERT INTO jobtrack_notes (contact_id, user_id, body) VALUES (?, ?, ?)');
    $stmt->execute([$id, $userId, $body]);
    if (array_key_exists('follow_up_date', $input)) {
        // An explicit date (or an empty value to clear the reminder) wins;
        // when the key is absent the existing date stays untouched.
        $nextFollowUp = jobtrack_clean_date($input['follow_up_date']);
        if ($nextFollowUp === false) jsonResponse(['error' => 'Pick a valid follow-up date.'], 422);
        $stmt = $pdo->prepare('UPDATE jobtrack_contacts SET follow_up_date = ?, last_touch_at = NOW() WHERE id = ? AND user_id = ?');
        $stmt->execute([$nextFollowUp, $id, $userId]);
    } else {
        // No new date given: the reminder that prompted this log is handled,
        // so clear it if it was already due. A future date stays untouched.
        $stmt = $pdo->prepare('UPDATE jobtrack_contacts SET last_touch_at = NOW(), follow_up_date = CASE WHEN follow_up_date IS NOT NULL AND follow_up_date <= CURDATE() THEN NULL ELSE follow_up_date END WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
    }
    jsonResponse(['ok' => true, 'note_id' => (int) $pdo->lastInsertId()]);
}

if ($action === 'delete') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM jobtrack_notes WHERE contact_id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    $stmt = $pdo->prepare('DELETE FROM jobtrack_contacts WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Application not found.'], 404);
    jsonResponse(['ok' => true]);
}

if ($action === 'delete_note') {
    $noteId = (int) ($input['note_id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM jobtrack_notes WHERE id = ? AND user_id = ?');
    $stmt->execute([$noteId, $userId]);
    if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Note not found.'], 404);
    jsonResponse(['ok' => true]);
}

jsonResponse(['error' => 'Unknown action.'], 400);
