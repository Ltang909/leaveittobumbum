<?php
// Receipt Reader API.
// OCR runs entirely in the visitor's browser (Tesseract.js): receipt photos are
// NEVER uploaded or stored. This endpoint meters scans (1 action each) and keeps
// the structured receipt log (vendor, date, line items, totals) in a DB table.
require dirname(__DIR__) . '/_bootstrap.php';

function ensureReceiptSchema(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS receipt_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        vendor VARCHAR(191) NOT NULL DEFAULT '',
        receipt_date DATE NULL,
        currency CHAR(3) NOT NULL DEFAULT 'USD',
        subtotal DECIMAL(12,2) NULL,
        tax DECIMAL(12,2) NULL,
        tax_label VARCHAR(64) NOT NULL DEFAULT 'Tax',
        total DECIMAL(12,2) NULL,
        items JSON NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        KEY idx_user (user_id),
        KEY idx_user_date (user_id, receipt_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function receipt_public(array $row): array {
    $items = json_decode($row['items'] ?? '[]', true);
    if (!is_array($items)) $items = [];
    return [
        'id' => (int) $row['id'],
        'vendor' => (string) $row['vendor'],
        'date' => $row['receipt_date'] ?: '',
        'currency' => (string) $row['currency'],
        'subtotal' => $row['subtotal'] !== null ? (string) $row['subtotal'] : '',
        'tax' => $row['tax'] !== null ? (string) $row['tax'] : '',
        'taxLabel' => (string) $row['tax_label'],
        'total' => $row['total'] !== null ? (string) $row['total'] : '',
        'items' => array_values(array_filter(array_map(function ($it) {
            if (!is_array($it)) return null;
            return ['name' => mb_substr((string) ($it['name'] ?? ''), 0, 120), 'amount' => (string) ($it['amount'] ?? '')];
        }, $items))),
        'created_at' => (string) $row['created_at'],
    ];
}

function clean_amount($v) {
    if ($v === null || $v === '') return null;
    $v = preg_replace('/[^0-9.]/', '', (string) $v);
    if ($v === '' || !is_numeric($v)) return null;
    return round((float) $v, 2);
}

requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
$bill = billingUser($user);
$userId = (int) $user['id'];
ensureReceiptSchema();
$pdo = db();
$action = (string) ($input['action'] ?? '');

if ($action === 'scan') {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) {
        jsonResponse(['error' => 'Invalid request identifier.'], 422);
    }
    $count = consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), 'receipt-reader', 'receipt-scan-' . $key);
    if (!empty($count['limit_reached'])) {
        jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $count], 402);
    }
    jsonResponse(['ok' => true, 'usage' => usageFor($bill), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
}

if ($action === 'list') {
    $stmt = $pdo->prepare("SELECT * FROM receipt_logs WHERE user_id = ? ORDER BY created_at DESC, id DESC LIMIT 200");
    $stmt->execute([$userId]);
    jsonResponse(['logs' => array_map('receipt_public', $stmt->fetchAll())]);
}

if ($action === 'save') {
    $vendor = mb_substr(trim((string) ($input['vendor'] ?? '')), 0, 191);
    $date = trim((string) ($input['date'] ?? ''));
    $date = preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) ? $date : null;
    $currency = strtoupper(trim((string) ($input['currency'] ?? 'USD')));
    if (!in_array($currency, ['USD', 'CAD', 'EUR', 'GBP'], true)) $currency = 'USD';
    $items = is_array($input['items'] ?? null) ? array_slice($input['items'], 0, 200) : [];
    $cleanItems = [];
    foreach ($items as $it) {
        if (!is_array($it)) continue;
        $name = mb_substr(trim((string) ($it['name'] ?? '')), 0, 120);
        $amount = clean_amount($it['amount'] ?? '');
        if ($name === '' && $amount === null) continue;
        $cleanItems[] = ['name' => $name, 'amount' => $amount === null ? '0.00' : number_format($amount, 2, '.', '')];
    }
    $taxLabel = mb_substr(trim((string) ($input['taxLabel'] ?? 'Tax')), 0, 64) ?: 'Tax';
    $stmt = $pdo->prepare("INSERT INTO receipt_logs (user_id, vendor, receipt_date, currency, subtotal, tax, tax_label, total, items) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $userId, $vendor, $date, $currency,
        clean_amount($input['subtotal'] ?? ''), clean_amount($input['tax'] ?? ''),
        $taxLabel, clean_amount($input['total'] ?? ''),
        json_encode($cleanItems, JSON_UNESCAPED_UNICODE),
    ]);
    $id = (int) $pdo->lastInsertId();
    $stmt = $pdo->prepare("SELECT * FROM receipt_logs WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    jsonResponse(['ok' => true, 'log' => receipt_public($row)]);
}

if ($action === 'delete') {
    $id = (int) ($input['id'] ?? 0);
    if ($id <= 0) jsonResponse(['error' => 'Missing receipt id.'], 422);
    $stmt = $pdo->prepare("DELETE FROM receipt_logs WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
    jsonResponse(['ok' => true, 'deleted' => $stmt->rowCount() > 0]);
}

jsonResponse(['error' => 'Unknown action.'], 400);
