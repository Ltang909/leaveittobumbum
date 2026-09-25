<?php
// TEMPORARY repair page: restores the signed-in user's LIVE Stripe IDs after a
// test-mode staging checkout overwrote them in the shared DB. Only touches the
// current user's own row. Tombstone with HTTP 410 after use.
require __DIR__ . '/_bootstrap.php';
$user = requireUser();

function maskId($v) {
    $v = (string) ($v ?? '');
    if ($v === '') return '(empty)';
    if (strlen($v) <= 16) return substr($v, 0, 4) . '...';
    return substr($v, 0, 12) . '...' . substr($v, -4);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requirePost();
    $input = body();
    requireCsrf($input);
    $cus = trim((string) ($input['customer_id'] ?? ''));
    $sub = trim((string) ($input['subscription_id'] ?? ''));
    if (!preg_match('/^cus_[A-Za-z0-9]{6,80}$/', $cus)) jsonResponse(['error' => 'That customer ID does not look right (should look like cus_...).'], 422);
    if (!preg_match('/^sub_[A-Za-z0-9]{6,80}$/', $sub)) jsonResponse(['error' => 'That subscription ID does not look right (should look like sub_...).'], 422);
    db()->prepare('UPDATE users SET stripe_customer_id = ?, stripe_subscription_id = ? WHERE id = ?')->execute([$cus, $sub, (int) $user['id']]);
    jsonResponse(['ok' => true, 'message' => 'Saved. Your live billing IDs are restored.']);
}

$stmt = db()->prepare('SELECT plan, stripe_customer_id, stripe_subscription_id FROM users WHERE id = ?');
$stmt->execute([(int) $user['id']]);
$me = $stmt->fetch() ?: [];
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Restore live billing IDs | Leave It to Bum Bum</title><link rel="icon" href="/bum/favicon-cat.png"><style>body{font-family:system-ui,sans-serif;max-width:560px;margin:40px auto;padding:0 20px;line-height:1.5;color:#2f2a22}input{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;margin:4px 0 12px;box-sizing:border-box}button{background:#2f2a22;color:#fff;border:0;border-radius:999px;padding:12px 28px;font:inherit;font-weight:700;cursor:pointer}label{font-weight:700;font-size:14px}.cur{font-size:13px;opacity:.7;margin:0 0 16px}#msg{font-weight:700;margin-top:14px}.ok{color:#1e7a34}.err{color:#b3261e}</style></head><body>
<h1>Restore your live billing IDs</h1>
<p>Your account is currently pointing at a <b>test-mode</b> subscription, which is why plan switching and Manage billing fail in production. Paste your <b>live</b> IDs from the Stripe dashboard (Live mode → Customers → your email) below.</p>
<p class="cur">Currently saved: <?= htmlspecialchars(maskId($me['stripe_customer_id'] ?? '')) ?> / <?= htmlspecialchars(maskId($me['stripe_subscription_id'] ?? '')) ?> (plan: <?= htmlspecialchars((string)($me['plan'] ?? '')) ?>)</p>
<label>Live customer ID<input id="cus" placeholder="cus_..." autocomplete="off"></label>
<label>Live subscription ID<input id="sub" placeholder="sub_..." autocomplete="off"></label>
<div><button id="save">Save live IDs</button></div>
<p id="msg"></p>
<script>
document.querySelector('#save').addEventListener('click', async () => {
  const msg = document.querySelector('#msg');
  msg.className = ''; msg.textContent = 'Saving...';
  try {
    const session = await fetch('/api/session.php').then(r => r.json());
    const res = await fetch(location.pathname, {method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({customer_id: document.querySelector('#cus').value.trim(), subscription_id: document.querySelector('#sub').value.trim(), csrf: session.csrf})});
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || 'Save failed.');
    msg.className = 'ok'; msg.textContent = data.message + ' You can close this page and retry the plan switch on the live site.';
  } catch (e) { msg.className = 'err'; msg.textContent = e.message; }
});
</script>
</body></html>
