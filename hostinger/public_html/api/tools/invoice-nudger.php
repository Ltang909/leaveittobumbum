<?php
require dirname(__DIR__) . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
$bill = billingUser($user);

$yourName = trim((string) ($input['yourName'] ?? ''));
$clientName = trim((string) ($input['clientName'] ?? ''));
$invoiceNumber = trim((string) ($input['invoiceNumber'] ?? ''));
$amount = filter_var($input['amount'] ?? null, FILTER_VALIDATE_FLOAT);
$dueDate = trim((string) ($input['dueDate'] ?? ''));
$idempotency = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));

if ($yourName === '' || $clientName === '' || $invoiceNumber === '') jsonResponse(['error' => 'Fill in your name, the client name, and the invoice number.'], 422);
if (strlen($yourName) > 80 || strlen($clientName) > 80 || strlen($invoiceNumber) > 40) jsonResponse(['error' => 'Keep names under 80 characters and the invoice number under 40.'], 422);
if ($amount === false || $amount <= 0 || $amount > 10000000) jsonResponse(['error' => 'Enter an invoice amount greater than zero.'], 422);
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dueDate)) jsonResponse(['error' => 'Pick a valid due date.'], 422);
if (strlen($idempotency) < 16 || strlen($idempotency) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);

$due = DateTimeImmutable::createFromFormat('Y-m-d', $dueDate);
if (!$due) jsonResponse(['error' => 'Pick a valid due date.'], 422);
$today = new DateTimeImmutable('today');
$daysOverdue = max(0, (int) $due->diff($today)->format('%r%a'));

$money = '$' . number_format($amount, 2);
$niceDate = $due->format('F j, Y');
$overdueLine = $daysOverdue === 0 ? 'due ' . $niceDate : $daysOverdue . ($daysOverdue === 1 ? ' day' : ' days') . ' overdue';

if ($daysOverdue < 7) $suggested = 'friendly';
elseif ($daysOverdue < 30) $suggested = 'firm';
else $suggested = 'final';

$emails = [
  'friendly' => [
    'label' => 'The friendly nudge',
    'hint' => 'For invoices that just slipped through the cracks.',
    'subject' => 'Quick check-in on invoice #' . $invoiceNumber,
    'body' => "Hi {$clientName},\n\nHope you're doing well! Just floating invoice #{$invoiceNumber} for {$money} back to the top of your inbox. It was {$overdueLine}, so no stress at all, just making sure it didn't get lost in the shuffle.\n\nThanks a bunch!\n{$yourName}",
  ],
  'firm' => [
    'label' => 'The firm reminder',
    'hint' => 'For invoices that need a real answer.',
    'subject' => "Following up: invoice #{$invoiceNumber} ({$overdueLine})",
    'body' => "Hi {$clientName},\n\nI'm following up on invoice #{$invoiceNumber} for {$money}, which was due on {$niceDate} and is now {$overdueLine}.\n\nCould you let me know when I can expect payment? If anything looks off on the invoice itself, I'm happy to sort it out.\n\nThanks,\n{$yourName}",
  ],
  'final' => [
    'label' => 'The final notice',
    'hint' => 'For invoices that have gone quiet too long.',
    'subject' => 'Final notice: invoice #' . $invoiceNumber,
    'body' => "Hi {$clientName},\n\nThis is a final notice for invoice #{$invoiceNumber} for {$money}, due on {$niceDate} and now {$overdueLine}.\n\nPlease arrange payment within 7 days. If I don't hear from you by then, I'll have to take next steps, which may include late fees or collections. I'd much rather we sort this out directly.\n\n{$yourName}",
  ],
];

$count = consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), 'nudge', $idempotency);
if (!empty($count['limit_reached'])) jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $count], 402);
jsonResponse(['result' => ['emails' => $emails, 'daysOverdue' => $daysOverdue, 'suggested' => $suggested], 'usage' => usageFor($bill), 'duplicate' => (bool) ($count['duplicate'] ?? false)]);
