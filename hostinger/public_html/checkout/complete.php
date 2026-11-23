<?php
// Promise checkout return page. Looks the request up by Stripe session id;
// the webhook may still be landing, so "confirming" is honest until the
// row reads paid.
require dirname(__DIR__) . '/api/_bootstrap.php';
$sessionId = (string) ($_GET['session_id'] ?? '');
$title = '';
$paid = false;
if ($sessionId !== '') {
    try {
        ensureCustomRequestTables();
        $stmt = db()->prepare('SELECT title, payment_status FROM custom_requests WHERE stripe_session_id = ?');
        $stmt->execute([$sessionId]);
        if ($row = $stmt->fetch()) {
            $title = (string) $row['title'];
            $paid = (string) ($row['payment_status'] ?? '') === 'paid';
        }
    } catch (Throwable $e) {
        error_log('Checkout complete lookup failed: ' . $e->getMessage());
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Bum Bum is on it</title><link rel="stylesheet" href="/app.css?v=11"></head><body><header class="shell"><a class="brand" href="/">BB · Leave It to Bum Bum</a></header><main class="shell"><p class="eyebrow">Checkout complete</p><h1>Bum Bum is on it.</h1><?php if ($title !== ''): ?><p class="lede">Your $49 is <?= $paid ? 'confirmed' : 'being confirmed' ?> for <strong><?= htmlspecialchars($title) ?></strong>. We will email you within a day to agree the tiny, useful version we can build, and the 36-hour clock starts when scope and access are confirmed. If we cannot scope it, the $49 comes straight back.</p><?php else: ?><p class="lede">Your payment is being confirmed. It normally appears in your account within a few seconds.</p><?php endif; ?><a class="button" href="/account/">View your account</a></main></body></html>
