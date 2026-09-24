<?php
// Bum Bum Invoice Chaser API. Track who owes you what, generate escalating
// chase drafts, and email yourself a reminder digest. Scoped to the signed-in user.
// One metered action per invoice added and per reminder email sent (one charged
// reminder per day; same-day repeats are free). Drafts, updates, marking paid,
// and deleting are free so the chase loop stays frictionless.
require dirname(__DIR__) . '/_bootstrap.php';
require dirname(__DIR__, 2) . '/includes/smtp-mail.php';

const CHASER_CURRENCIES = ['USD', 'CAD', 'EUR', 'GBP'];
const CHASER_SYMBOLS = ['USD' => '$', 'CAD' => 'CA$', 'EUR' => '€', 'GBP' => '£'];

function ensureChaserSchema(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS chaser_invoices (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        client_name VARCHAR(191) NOT NULL,
        amount DECIMAL(12,2) NOT NULL,
        currency CHAR(3) NOT NULL DEFAULT 'USD',
        invoice_no VARCHAR(64) NOT NULL DEFAULT '',
        due_date DATE NOT NULL,
        notes TEXT NULL,
        status VARCHAR(16) NOT NULL DEFAULT 'open',
        paid_at TIMESTAMP NULL,
        reminded_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        KEY idx_user (user_id),
        KEY idx_user_status_due (user_id, status, due_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function chaser_idempotency(array $input): string {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
    return $key;
}

function chaser_clean_date($v) {
    $v = trim((string) ($v ?? ''));
    if ($v === '') return null;
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) return false;
    [$y, $m, $d] = array_map('intval', explode('-', $v));
    return checkdate($m, $d, $y) ? $v : false;
}

function chaser_days_overdue(string $dueDate): int {
    $due = new DateTime($dueDate);
    $today = new DateTime(date('Y-m-d'));
    $diff = $today->diff($due);
    $days = (int) $diff->format('%a');
    return $diff->invert ? $days : -$days;
}

function chaser_money($amount, string $currency): string {
    $sym = CHASER_SYMBOLS[$currency] ?? ($currency . ' ');
    return $sym . number_format((float) $amount, 2);
}

function chaser_public_invoice(array $row): array {
    $days = chaser_days_overdue($row['due_date']);
    return [
        'id' => (int) $row['id'],
        'client_name' => $row['client_name'],
        'amount' => (float) $row['amount'],
        'currency' => $row['currency'],
        'amount_display' => chaser_money($row['amount'], $row['currency']),
        'invoice_no' => $row['invoice_no'],
        'due_date' => $row['due_date'],
        'notes' => $row['notes'] ?? '',
        'status' => $row['status'],
        'days_overdue' => $days,
        'paid_at' => $row['paid_at'],
        'created_at' => $row['created_at'],
    ];
}

function chaser_draft(array $inv): array {
    $client = $inv['client_name'];
    $amount = $inv['amount_display'];
    $ref = $inv['invoice_no'] !== '' ? " (invoice {$inv['invoice_no']})" : '';
    $due = $inv['due_date'];
    $days = $inv['days_overdue'];

    if ($days < 0) {
        $subject = "Heads-up: {$amount} due {$due}";
        $body = "Hi {$client},\n\nQuick heads-up that {$amount}{$ref} is due on {$due}.\n\nLet me know if you need anything from me to get it processed on time.\n\nThanks!";
    } elseif ($days <= 14) {
        $subject = "Gentle nudge: {$amount} was due {$due}";
        $body = "Hi {$client},\n\nHope you're well! Just floating this to the top of your inbox: {$amount}{$ref} was due on {$due} ({$days} day" . ($days === 1 ? '' : 's') . " ago). Totally get how these slip through the cracks.\n\nCould you let me know when I can expect it? Happy to resend the invoice if helpful.\n\nThanks!";
    } elseif ($days <= 30) {
        $subject = "Following up: {$amount} overdue{$ref}";
        $body = "Hi {$client},\n\nFollowing up on {$amount}{$ref}, which was due on {$due} ({$days} days ago). I haven't seen it come through yet.\n\nPlease let me know the status and when I can expect payment. If there's an issue with the invoice itself, I'd rather hear about it now so we can sort it out.\n\nThanks!";
    } else {
        $subject = "Final notice: {$amount} is {$days} days overdue";
        $body = "Hi {$client},\n\nThis is a final notice regarding {$amount}{$ref}, due on {$due} ({$days} days overdue).\n\nI need this resolved this week. Please confirm payment timing by end of day, or let me know right away if something is blocking it.\n\nThanks!";
    }
    return ['subject' => $subject, 'body' => $body];
}

requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
$bill = billingUser($user);
$userId = (int) $user['id'];
ensureChaserSchema();
$pdo = db();
$action = (string) ($input['action'] ?? 'list');

if ($action === 'list') {
    $stmt = $pdo->prepare("SELECT * FROM chaser_invoices WHERE user_id = ? ORDER BY (status = 'open') DESC, due_date ASC, id DESC LIMIT 500");
    $stmt->execute([$userId]);
    $invoices = [];
    $outstanding = 0.0;
    $overdue = 0.0;
    $countOpen = 0;
    $countOverdue = 0;
    foreach ($stmt->fetchAll() as $row) {
        $inv = chaser_public_invoice($row);
        $invoices[] = $inv;
        if ($inv['status'] === 'open') {
            $countOpen++;
            $outstanding += $inv['amount'];
            if ($inv['days_overdue'] > 0) {
                $countOverdue++;
                $overdue += $inv['amount'];
            }
        }
    }
    jsonResponse([
        'invoices' => $invoices,
        'stats' => [
            'outstanding' => round($outstanding, 2),
            'overdue' => round($overdue, 2),
            'open' => $countOpen,
            'overdue_count' => $countOverdue,
        ],
        'today' => date('Y-m-d'),
        'currencies' => CHASER_CURRENCIES,
    ]);
}

if ($action === 'draft') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT * FROM chaser_invoices WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    $row = $stmt->fetch();
    if (!$row) jsonResponse(['error' => 'Invoice not found.'], 404);
    if ($row['status'] !== 'open') jsonResponse(['error' => 'This invoice is already paid. Nice.'], 422);
    jsonResponse(chaser_draft(chaser_public_invoice($row)));
}

if ($action === 'add') {
    $client = trim((string) ($input['client_name'] ?? ''));
    $amountRaw = trim((string) ($input['amount'] ?? ''));
    $currency = strtoupper(trim((string) ($input['currency'] ?? 'USD')));
    $invoiceNo = trim((string) ($input['invoice_no'] ?? ''));
    $dueDate = chaser_clean_date($input['due_date'] ?? null);
    $notes = trim((string) ($input['notes'] ?? ''));
    if ($client === '') jsonResponse(['error' => 'Who owes you? Give the client a name.'], 422);
    if (mb_strlen($client) > 191) jsonResponse(['error' => 'Keep the client name under 191 characters.'], 422);
    $amount = filter_var($amountRaw, FILTER_VALIDATE_FLOAT);
    if ($amount === false || $amount <= 0 || $amount > 100000000) jsonResponse(['error' => 'Enter an amount greater than zero.'], 422);
    if (!in_array($currency, CHASER_CURRENCIES, true)) jsonResponse(['error' => 'Pick a valid currency.'], 422);
    if (mb_strlen($invoiceNo) > 64) jsonResponse(['error' => 'Keep the invoice number under 64 characters.'], 422);
    if ($dueDate === null) jsonResponse(['error' => 'When is it due? Pick a due date.'], 422);
    if ($dueDate === false) jsonResponse(['error' => 'Pick a valid due date.'], 422);
    if (mb_strlen($notes) > 2000) jsonResponse(['error' => 'Keep notes under 2000 characters.'], 422);
    $idempotency = chaser_idempotency($input);
    $count = consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), 'invoice-chaser', $idempotency);
    if (!empty($count['limit_reached'])) jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $count], 402);
    $invoice = null;
    if (empty($count['duplicate'])) {
        $stmt = $pdo->prepare('INSERT INTO chaser_invoices (user_id, client_name, amount, currency, invoice_no, due_date, notes) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $client, $amount, $currency, $invoiceNo, $dueDate, $notes === '' ? null : $notes]);
        $id = (int) $pdo->lastInsertId();
        $stmt = $pdo->prepare('SELECT * FROM chaser_invoices WHERE id = ?');
        $stmt->execute([$id]);
        $invoice = chaser_public_invoice($stmt->fetch());
    }
    jsonResponse(['invoice' => $invoice, 'usage' => usageFor($bill), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
}

if ($action === 'update') {
    $id = (int) ($input['id'] ?? 0);
    $fields = [];
    $params = [];
    if (array_key_exists('status', $input)) {
        $status = (string) $input['status'];
        if (!in_array($status, ['open', 'paid'], true)) jsonResponse(['error' => 'Pick a valid status.'], 422);
        $fields[] = 'status = ?';
        $params[] = $status;
        $fields[] = $status === 'paid' ? 'paid_at = NOW()' : 'paid_at = NULL';
    }
    if (array_key_exists('client_name', $input)) {
        $client = trim((string) $input['client_name']);
        if ($client === '' || mb_strlen($client) > 191) jsonResponse(['error' => 'Give the client a valid name.'], 422);
        $fields[] = 'client_name = ?';
        $params[] = $client;
    }
    if (array_key_exists('amount', $input)) {
        $amount = filter_var(trim((string) $input['amount']), FILTER_VALIDATE_FLOAT);
        if ($amount === false || $amount <= 0 || $amount > 100000000) jsonResponse(['error' => 'Enter an amount greater than zero.'], 422);
        $fields[] = 'amount = ?';
        $params[] = $amount;
    }
    if (array_key_exists('currency', $input)) {
        $currency = strtoupper(trim((string) $input['currency']));
        if (!in_array($currency, CHASER_CURRENCIES, true)) jsonResponse(['error' => 'Pick a valid currency.'], 422);
        $fields[] = 'currency = ?';
        $params[] = $currency;
    }
    if (array_key_exists('invoice_no', $input)) {
        $invoiceNo = trim((string) $input['invoice_no']);
        if (mb_strlen($invoiceNo) > 64) jsonResponse(['error' => 'Keep the invoice number under 64 characters.'], 422);
        $fields[] = 'invoice_no = ?';
        $params[] = $invoiceNo;
    }
    if (array_key_exists('due_date', $input)) {
        $dueDate = chaser_clean_date($input['due_date']);
        if ($dueDate === null || $dueDate === false) jsonResponse(['error' => 'Pick a valid due date.'], 422);
        $fields[] = 'due_date = ?';
        $params[] = $dueDate;
    }
    if (array_key_exists('notes', $input)) {
        $notes = trim((string) $input['notes']);
        if (mb_strlen($notes) > 2000) jsonResponse(['error' => 'Keep notes under 2000 characters.'], 422);
        $fields[] = 'notes = ?';
        $params[] = $notes === '' ? null : $notes;
    }
    if (!$fields) jsonResponse(['error' => 'Nothing to update.'], 422);
    $params[] = $id;
    $params[] = $userId;
    $stmt = $pdo->prepare('UPDATE chaser_invoices SET ' . implode(', ', $fields) . ' WHERE id = ? AND user_id = ?');
    $stmt->execute($params);
    if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Invoice not found.'], 404);
    jsonResponse(['ok' => true]);
}

if ($action === 'delete') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM chaser_invoices WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Invoice not found.'], 404);
    jsonResponse(['ok' => true]);
}

if ($action === 'remind') {
    $email = trim((string) ($user['email'] ?? ''));
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(['error' => 'Your account has no email address to send to.'], 422);
    }
    if (!bb_mail_configured()) {
        jsonResponse(['error' => 'Email reminders are not set up on this server yet. Your invoices are safe; the email part needs the mail settings first.'], 503);
    }
    $stmt = $pdo->prepare("SELECT * FROM chaser_invoices WHERE user_id = ? AND status = 'open' ORDER BY due_date ASC LIMIT 200");
    $stmt->execute([$userId]);
    $rows = $stmt->fetchAll();
    if (!$rows) jsonResponse(['error' => 'Nothing to chase. All clear!'], 422);

    // One charged reminder per day; same-day repeats ride free on the first send.
    $dayKey = 'chaser-remind-' . $userId . '-' . date('Y-m-d');
    $count = consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), 'invoice-chaser', $dayKey);
    if (!empty($count['limit_reached'])) jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $count], 402);
    $alreadySent = !empty($count['duplicate']);

    $overdueLines = [];
    $upcomingLines = [];
    foreach ($rows as $row) {
        $inv = chaser_public_invoice($row);
        $ref = $inv['invoice_no'] !== '' ? " (invoice {$inv['invoice_no']})" : '';
        if ($inv['days_overdue'] > 0) {
            $overdueLines[] = "- {$inv['client_name']}: {$inv['amount_display']}, {$inv['days_overdue']} day" . ($inv['days_overdue'] === 1 ? '' : 's') . " overdue{$ref}";
        } else {
            $when = $inv['days_overdue'] === 0 ? 'due today' : "due {$inv['due_date']}";
            $upcomingLines[] = "- {$inv['client_name']}: {$inv['amount_display']}, {$when}{$ref}";
        }
    }
    $nOverdue = count($overdueLines);
    $subject = $nOverdue > 0
        ? "Bum Bum: {$nOverdue} overdue invoice" . ($nOverdue === 1 ? '' : 's') . ' — your chase list is ready'
        : 'Bum Bum: your upcoming invoices — chase list inside';
    $appUrl = rtrim((string) (config()['app_url'] ?? ''), '/');
    $lines = ["Hey! Your chase list from Bum Bum:", ''];
    if ($overdueLines) {
        $lines[] = 'OVERDUE (' . $nOverdue . '):';
        $lines = array_merge($lines, $overdueLines);
        $lines[] = '';
    }
    if ($upcomingLines) {
        $lines[] = 'UPCOMING (' . count($upcomingLines) . '):';
        $lines = array_merge($lines, $upcomingLines);
        $lines[] = '';
    }
    $lines[] = 'Open Invoice Chaser to copy a chase draft for any of these:';
    $lines[] = $appUrl . '/tools/invoice-chaser/';
    $lines[] = '';
    $lines[] = 'Get that money.';
    $lines[] = '- Bum Bum';

    [$sent, $mailError] = $alreadySent ? [true, ''] : bb_send_mail($email, $subject, implode("\n", $lines));
    if (!$sent) {
        jsonResponse(['error' => 'The email could not be sent right now. Your invoices are safe; try again in a bit.'], 502);
    }
    $stmt = $pdo->prepare("UPDATE chaser_invoices SET reminded_at = NOW() WHERE user_id = ? AND status = 'open'");
    $stmt->execute([$userId]);
    jsonResponse(['ok' => true, 'sent_to' => $email, 'overdue' => $nOverdue, 'duplicate' => $alreadySent, 'usage' => usageFor($bill)]);
}

jsonResponse(['error' => 'Unknown action.'], 400);
