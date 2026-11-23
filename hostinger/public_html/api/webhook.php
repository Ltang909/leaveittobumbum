<?php
require __DIR__ . '/_bootstrap.php';
$raw = file_get_contents('php://input') ?: '';
$signature = (string) ($_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '');
$secret = (string) config()['stripe']['webhook_secret'];

function validStripeSignature(string $payload, string $header, string $secret): bool {
    $parts = [];
    foreach (explode(',', $header) as $item) {
        [$key, $value] = array_pad(explode('=', trim($item), 2), 2, '');
        $parts[$key][] = $value;
    }
    $timestamp = (int) ($parts['t'][0] ?? 0);
    if (!$timestamp || abs(time() - $timestamp) > 300) return false;
    $expected = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);
    foreach ($parts['v1'] ?? [] as $candidate) if (hash_equals($expected, $candidate)) return true;
    return false;
}

if (!validStripeSignature($raw, $signature, $secret)) jsonResponse(['error' => 'Invalid signature.'], 400);
$event = json_decode($raw, true);
if (!is_array($event) || empty($event['id'])) jsonResponse(['error' => 'Invalid event.'], 400);
// Guard: staging shares the production DB. Ignore events whose live/test mode
// does not match this server's Stripe keys, so a test-mode checkout can never
// overwrite live billing IDs (or vice versa).
$keyLive = (bool) preg_match('/^(sk|rk)_live_/', (string) (config()['stripe']['secret_key'] ?? ''));
$eventLive = !empty($event['livemode']);
if ($eventLive !== $keyLive) {
    error_log('webhook ignored mode mismatch: type=' . $event['type'] . ' id=' . $event['id'] . ' event_mode=' . ($eventLive ? 'live' : 'test') . ' key_mode=' . ($keyLive ? 'live' : 'test'));
    jsonResponse(['received' => true, 'ignored' => 'mode_mismatch']);
}
$pdo = db();
try {
    $pdo->prepare('INSERT INTO webhook_events (stripe_event_id, event_type) VALUES (?, ?)')->execute([$event['id'], $event['type']]);
} catch (PDOException $error) {
    if ((string) $error->getCode() !== '23000') throw $error;
    // Duplicate delivery (e.g. a manual resend from the Stripe dashboard).
    // checkout.session.completed is idempotent (it just re-links the same
    // customer/subscription IDs), so let it reprocess; skip anything else.
    if ($event['type'] !== 'checkout.session.completed') jsonResponse(['received' => true, 'duplicate' => true]);
}

$object = $event['data']['object'] ?? [];
if ($event['type'] === 'checkout.session.completed') {
    $meta = $object['metadata'] ?? [];
    if (($meta['kind'] ?? '') === 'promise') {
        // The 36-hour Promise: a one-time $49 payment, not a subscription.
        // Mark the request paid; plan and subscription fields stay untouched.
        $requestId = (int) ($meta['request_id'] ?? 0);
        $userId = (int) ($meta['user_id'] ?? 0);
        if ($requestId) {
            try {
                ensureCustomRequestTables();
                $upd = db()->prepare("UPDATE custom_requests SET payment_status = 'paid', status = 'paid', stripe_payment_intent = ?, paid_at = NOW() WHERE id = ? AND payment_status IN ('pending', 'expired')");
                $upd->execute([(string) ($object['payment_intent'] ?? ''), $requestId]);
                if ($upd->rowCount() > 0) {
                    if ($userId && !empty($object['customer'])) {
                        db()->prepare("UPDATE users SET stripe_customer_id = ? WHERE id = ? AND (stripe_customer_id IS NULL OR stripe_customer_id = '')")->execute([(string) $object['customer'], $userId]);
                    }
                    $row = db()->prepare('SELECT r.title, r.details, u.email FROM custom_requests r JOIN users u ON u.id = r.user_id WHERE r.id = ?');
                    $row->execute([$requestId]);
                    if ($req = $row->fetch()) {
                        // Mirror into the public queue with the promise
                        // badge. Best-effort: a mirror failure must never
                        // look like a lost payment.
                        try {
                            ensureToolRequestTables();
                            $mirror = db()->prepare('INSERT INTO tool_requests (name, email, problem, outcome, status, is_operator) VALUES (?, ?, ?, ?, ?, 1)');
                            $mirror->execute(['', (string) $req['email'], (string) $req['title'], (string) $req['details'], 'requested']);
                            db()->prepare('UPDATE custom_requests SET queue_id = ? WHERE id = ?')->execute([(int) db()->lastInsertId(), $requestId]);
                        } catch (Throwable $mirrorError) {
                            error_log('Promise queue mirror failed: ' . $mirrorError->getMessage());
                        }
                        bbSendEmail('hello@leaveittobumbum.com', 'New paid Promise request: ' . mb_substr((string) $req['title'], 0, 80), "A 36-hour Promise request just paid its \$49.\n\nFrom: {$req['email']}\nTitle: {$req['title']}\n\n{$req['details']}\n\nNext step: reply to scope it, then start the clock from the Promise panel on your account page.");
                        bbSendEmail((string) $req['email'], 'We got your Promise request: ' . mb_substr((string) $req['title'], 0, 80), "Hi!\n\nYour \$49 is in and your request \"{$req['title']}\" is with Bum Bum.\n\nWhat happens next: we reply within a day to agree the tiny, useful version we can build. The 36-hour clock starts when scope and access are confirmed. If we cannot scope it, the \$49 comes straight back to you.\n\nFollow along any time: https://leaveittobumbum.com/account/\n\n— Bum Bum");
                    }
                    if ($userId) posthogCapture('promise_purchased', 'user_' . $userId, ['request_id' => $requestId, 'amount_cents' => (int) ($object['amount_total'] ?? 4900)]);
                }
            } catch (Throwable $promiseError) {
                error_log('Promise payment handling failed: ' . $promiseError->getMessage());
            }
        }
    } else {
        $userId = (int) ($object['metadata']['user_id'] ?? 0);
        $plan = (string) ($object['metadata']['plan'] ?? 'free');
        if ($userId) db()->prepare('UPDATE users SET stripe_customer_id = ?, stripe_subscription_id = ?, plan = ?, subscription_status = ? WHERE id = ?')->execute([$object['customer'] ?? null, $object['subscription'] ?? null, $plan, 'active', $userId]);
        if ($userId && $plan !== 'free') posthogCapture('subscription_created', 'user_' . $userId, ['plan' => $plan]);
    }
}
if ($event['type'] === 'checkout.session.expired') {
    $meta = $object['metadata'] ?? [];
    if (($meta['kind'] ?? '') === 'promise' && (int) ($meta['request_id'] ?? 0) > 0) {
        try {
            db()->prepare("UPDATE custom_requests SET payment_status = 'expired', status = 'expired' WHERE id = ? AND payment_status = 'pending'")->execute([(int) $meta['request_id']]);
        } catch (Throwable $expireError) {
            error_log('Promise session-expiry handling failed: ' . $expireError->getMessage());
        }
    }
}
if ($event['type'] === 'charge.refunded') {
    // A manual refund in Stripe (e.g. a request that could not be scoped)
    // keeps the request row honest without anyone editing the database.
    $paymentIntent = (string) ($object['payment_intent'] ?? '');
    if ($paymentIntent !== '') {
        try {
            ensureCustomRequestTables();
            db()->prepare("UPDATE custom_requests SET payment_status = 'refunded', status = 'refunded' WHERE stripe_payment_intent = ? AND payment_status = 'paid'")->execute([$paymentIntent]);
        } catch (Throwable $refundError) {
            error_log('Promise refund sync failed: ' . $refundError->getMessage());
        }
    }
}
if (str_starts_with((string) $event['type'], 'customer.subscription.')) {
    $status = (string) ($object['status'] ?? 'none');
    $active = in_array($status, ['active', 'trialing', 'past_due'], true);
    $plan = $active ? (string) ($object['metadata']['plan'] ?? 'free') : 'free';
    $start = !empty($object['current_period_start']) ? gmdate('Y-m-d H:i:s', (int) $object['current_period_start']) : null;
    $end = !empty($object['current_period_end']) ? gmdate('Y-m-d H:i:s', (int) $object['current_period_end']) : null;
    db()->prepare('UPDATE users SET plan = ?, subscription_status = ?, period_start = ?, period_end = ? WHERE stripe_subscription_id = ? OR stripe_customer_id = ?')->execute([$plan, $status, $start, $end, $object['id'] ?? '', $object['customer'] ?? '']);
    if ($event['type'] === 'customer.subscription.deleted') {
        $row = db()->prepare('SELECT id FROM users WHERE stripe_subscription_id = ? OR stripe_customer_id = ?');
        $row->execute([$object['id'] ?? '', $object['customer'] ?? '']);
        if ($found = $row->fetch()) posthogCapture('subscription_cancelled', 'user_' . $found['id'], ['plan' => $plan]);
    }
}
jsonResponse(['received' => true]);
