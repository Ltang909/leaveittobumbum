<?php
// TEMPORARY diagnostic: pinpoints which string comparison in the operator
// request flow hits the collation clash. Any signed-in user. Delete after use.
require __DIR__ . '/_bootstrap.php';
$user = requireUser();
$bill = billingUser($user);
$billId = (int) $bill['id'];
$out = [];

function t($label, $fn) {
    global $out;
    try { $fn(); $out[$label] = 'OK'; }
    catch (Throwable $e) { $out[$label] = 'FAIL: ' . $e->getMessage(); }
}

// 1. Per-column collations.
try {
    $cols = [];
    foreach (db()->query('SHOW FULL COLUMNS FROM custom_requests')->fetchAll() as $c) {
        $cols[$c['Field']] = $c['Collation'];
    }
    $out['column_collations'] = $cols;
} catch (Throwable $e) { $out['column_collations'] = 'FAIL: ' . $e->getMessage(); }

// 2. What collation does DATE_FORMAT return?
t('date_format_collation', function () {
    global $out;
    $r = db()->query("SELECT COLLATION(DATE_FORMAT(NOW(), '%Y-%m')) AS c")->fetch();
    $out['date_format_collation_value'] = $r['c'];
});

// 3. The individual comparisons from the $open query.
t('cmp_status_plain', function () { db()->query("SELECT id FROM custom_requests WHERE status = 'open' LIMIT 1")->fetchAll(); });
t('cmp_status_forced', function () { db()->query("SELECT id FROM custom_requests WHERE status = 'open' COLLATE utf8mb4_unicode_ci LIMIT 1")->fetchAll(); });
t('cmp_date_format', function () use ($billId) {
    $s = db()->prepare("SELECT id FROM custom_requests WHERE user_id = ? AND DATE_FORMAT(requested_at, '%Y-%m') = ? LIMIT 1");
    $s->execute([$billId, gmdate('Y-m')]);
    $s->fetchAll();
});

// 4. The full $open query as shipped.
t('full_open_query', function () use ($billId) {
    $s = db()->prepare("SELECT id FROM custom_requests WHERE user_id = ? AND status = 'open' COLLATE utf8mb4_unicode_ci AND DATE_FORMAT(requested_at, '%Y-%m') = ? LIMIT 1");
    $s->execute([$billId, gmdate('Y-m')]);
    $s->fetchAll();
});

jsonResponse($out);
