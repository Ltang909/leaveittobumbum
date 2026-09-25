<?php
require __DIR__ . '/_bootstrap.php';
require __DIR__ . '/../includes/smtp-mail.php';
// Owner inbox for signup/cancellation pings.
const BB_OWNER_EMAIL = 'hello@leaveittobumbum.com';
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

// Billing notification emails (welcome on signup, confirmation on cancel,
// plus an owner ping for each). Defensive: if the server config has no
// 'mail' section yet, this logs and skips instead of breaking the webhook.
function bb_billing_mail(string $to, string $subject, string $body): void {
    if (!bb_mail_configured()) {
        error_log('billing email skipped (mail not configured): to=' . $to . ' subject=' . $subject);
        return;
    }
    [$sent, $mailError] = bb_send_mail($to, $subject, $body);
    if (!$sent) error_log('billing email failed: to=' . $to . ' error=' . $mailError);
}
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
    $userId = (int) ($object['metadata']['user_id'] ?? 0);
    $plan = (string) ($object['metadata']['plan'] ?? 'free');
    if ($userId) db()->prepare('UPDATE users SET stripe_customer_id = ?, stripe_subscription_id = ?, plan = ?, subscription_status = ? WHERE id = ?')->execute([$object['customer'] ?? null, $object['subscription'] ?? null, $plan, 'active', $userId]);
    if ($userId && $plan !== 'free') {
        posthogCapture('subscription_created', 'user_' . $userId, ['plan' => $plan]);
        $u = db()->prepare('SELECT email FROM users WHERE id = ?');
        $u->execute([$userId]);
        $customerEmail = (string) (($u->fetch() ?: [])['email'] ?? '');
        if ($customerEmail !== '') {
            $planName = ucfirst($plan);
            $actions = $plan === 'operator' ? '6,000' : '1,500';
            $seats = $plan === 'operator' ? '10' : '3';
            bb_billing_mail(
                $customerEmail,
                "Welcome to Bum Bum {$planName}! \xF0\x9F\x8E\x89",
                "Hi there!\n\nYour {$planName} subscription is live — welcome aboard. \xF0\x9F\x90\xB1\n\n" .
                "Here's what you unlocked:\n" .
                "- {$actions} actions/month\n" .
                "- All current tools\n" .
                "- {$seats} team member seats\n" .
                "- Email support\n\n" .
                "Start putting Bum Bum to work: https://leaveittobumbum.com/tools/\n\n" .
                "Need anything? Just reply to this email.\n\n— Bum Bum"
            );
            bb_billing_mail(
                BB_OWNER_EMAIL,
                "\xF0\x9F\x8E\x89 New {$planName} signup: {$customerEmail}",
                "New paid signup via Stripe checkout.\n\nEmail: {$customerEmail}\nPlan: {$planName}\nUser ID: {$userId}\nTime: " . gmdate('Y-m-d H:i:s') . " UTC"
            );
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
        $row = db()->prepare('SELECT id, email FROM users WHERE stripe_subscription_id = ? OR stripe_customer_id = ?');
        $row->execute([$object['id'] ?? '', $object['customer'] ?? '']);
        if ($found = $row->fetch()) {
            posthogCapture('subscription_cancelled', 'user_' . $found['id'], ['plan' => $plan]);
            $customerEmail = (string) ($found['email'] ?? '');
            if ($customerEmail !== '') {
                bb_billing_mail(
                    $customerEmail,
                    'Your Bum Bum subscription has been cancelled',
                    "Hi there,\n\nYour subscription has been cancelled — no more charges from here on. " .
                    "Your account is back on the free plan (75 actions/month, all tools).\n\n" .
                    "Sorry to see you go! If something wasn't working for you, I'd genuinely love to hear what — just reply to this email.\n\n" .
                    "You're always welcome back: https://leaveittobumbum.com/pricing/\n\n— Bum Bum"
                );
                bb_billing_mail(
                    BB_OWNER_EMAIL,
                    "\xF0\x9F\x91\x8B Cancelled: {$customerEmail}",
                    "A subscription was cancelled.\n\nEmail: {$customerEmail}\nUser ID: {$found['id']}\nTime: " . gmdate('Y-m-d H:i:s') . " UTC"
                );
            }
        }
    }
}
jsonResponse(['received' => true]);
