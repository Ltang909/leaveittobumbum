<?php
// Daily cron: find custom tool requests past their 36-hour deadline and
// apply the guarantee. Legacy Operator requests get the "next month free"
// customer-balance credit; $49 Promise requests get an actual refund of
// the payment, which is what the current site copy promises.
// Call: GET /api/cron-check-requests.php?secret=CRON_SECRET
// Schedule once daily. Safe to re-run: settled requests change status.
require __DIR__ . '/_bootstrap.php';
$secret = (string) ($_GET['secret'] ?? '');
$expected = (string) (config()['cron_secret'] ?? '');
if ($expected === '' || $expected === 'replace_me' || !hash_equals($expected, $secret)) {
    jsonResponse(['error' => 'Forbidden.'], 403);
}
$stripe = config()['stripe'];
$checked = 0;
$credited = 0;
$errors = [];
try {
    $rows = db()->query("SELECT r.id, r.user_id, r.stripe_payment_intent, u.plan, u.stripe_customer_id FROM custom_requests r JOIN users u ON u.id = r.user_id WHERE r.status = 'open' COLLATE utf8mb4_unicode_ci AND r.deadline_at < NOW()")->fetchAll();
} catch (PDOException $error) {
    jsonResponse(['error' => 'Requests query failed: ' . $error->getMessage()], 503);
}
foreach ($rows as $row) {
    $checked++;
    $requestId = (int) $row['id'];
    $customerId = (string) ($row['stripe_customer_id'] ?? '');
    // Promise requests carry a payment intent; Operator requests do not.
    // A missed Promise deadline refunds the $49 outright.
    if (!empty($row['stripe_payment_intent'])) {
        try {
            $refund = stripeRequest('POST', '/v1/refunds', [
                'payment_intent' => (string) $row['stripe_payment_intent'],
                'reason' => 'requested_by_customer',
            ]);
            db()->prepare("UPDATE custom_requests SET status = 'overdue_refunded', payment_status = 'refunded', credit_owed = 0, stripe_credit_id = ? WHERE id = ?")->execute([(string) ($refund['id'] ?? ''), $requestId]);
            posthogCapture('promise_refunded', 'user_' . $row['user_id'], ['request_id' => $requestId, 'amount_cents' => 4900, 'reason' => 'deadline_missed']);
            $credited++;
        } catch (Throwable $error) {
            error_log('Guarantee refund failed for request ' . $requestId . ': ' . $error->getMessage());
            $errors[] = $requestId;
        }
        continue;
    }
    if (($row['plan'] ?? '') !== 'operator') { $errors[] = 'Request ' . $requestId . ' belongs to a non-Operator plan; skipped.'; continue; }
    $priceId = $stripe['operator_price'];
    try {
        if ($customerId === '') throw new RuntimeException('No Stripe customer for user ' . $row['user_id']);
        $price = stripeRequest('GET', '/v1/prices/' . urlencode((string) $priceId));
        $amount = (int) ($price['unit_amount'] ?? 0);
        if ($amount <= 0) throw new RuntimeException('Could not read plan price.');
        $txn = stripeRequest('POST', '/v1/customers/' . urlencode($customerId) . '/balance_transactions', [
            'amount' => (string) (-$amount),
            'currency' => 'usd',
            'description' => 'Leave It to Bum Bum 36-hour guarantee credit',
        ]);
        db()->prepare("UPDATE custom_requests SET status = 'overdue_credited', credit_owed = 0, stripe_credit_id = ? WHERE id = ?")->execute([(string) ($txn['id'] ?? ''), $requestId]);
        posthogCapture('guarantee_credit_applied', 'user_' . $row['user_id'], ['request_id' => $requestId, 'amount_cents' => $amount]);
        $credited++;
    } catch (Throwable $error) {
        error_log('Guarantee credit failed for request ' . $requestId . ': ' . $error->getMessage());
        $errors[] = $requestId;
    }
}
// Housekeeping: retire unconverted guest identities idle for 90+ days.
$purgedGuests = 0;
try {
    $purgedGuests = purgeOldGuests();
} catch (Throwable $error) {
    error_log('Guest purge failed: ' . $error->getMessage());
}
jsonResponse(['checked' => $checked, 'credited' => $credited, 'errors' => $errors, 'guests_purged' => $purgedGuests]);
