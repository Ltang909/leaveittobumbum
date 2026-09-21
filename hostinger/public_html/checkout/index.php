<?php
require dirname(__DIR__) . '/api/_bootstrap.php';
$user = currentUser();
$plan = ($_GET['plan'] ?? '') === 'operator' ? 'operator' : 'helper';
$publishableKey = (string) config()['stripe']['publishable_key'];
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Checkout | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css"><?php require dirname(__DIR__) . '/includes/analytics.php'; ?><script src="https://js.stripe.com/clover/stripe.js"></script></head><body>
<?php $showMeter = false; require dirname(__DIR__) . '/includes/site-header.php'; ?>
<main class="shell"><p class="eyebrow">Bum Bum’s checkout</p><img class="tool-mascot-page" src="/bum/cat-spa.png" alt="Bum Bum relaxing at the spa"><h1>Put the busywork on a shorter leash.</h1><p class="lede">Bum Bum approves this treat-yourself moment.</p>
<div class="plan-switch"><a class="<?= $plan === 'helper' ? 'active' : '' ?>" href="?plan=helper">Helper · $12</a><a class="<?= $plan === 'operator' ? 'active' : '' ?>" href="?plan=operator">Operator · $49</a></div>
<?php if (!$user): ?><section class="panel"><h2>Sign in before checkout</h2><p>This connects the subscription to your workspace and makes the usage meter work.</p><a class="button" href="/account/?next=<?= urlencode('/checkout/?plan=' . $plan) ?>">Sign in or create an account</a></section>
<?php else: ?><p class="lede">Signed in as <?= htmlspecialchars($user['email']) ?>. Payment details go directly to Stripe and never touch our server.</p><div id="checkout" class="checkout-wrap"></div><p id="error" class="error"></p>
<script>
bbTrack('checkout_started',{plan:<?= json_encode($plan) ?>});
const stripe=Stripe(<?= json_encode($publishableKey) ?>);
const plan=<?= json_encode($plan) ?>;
async function clientSecret(){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/checkout.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({plan,csrf:session.csrf})});const data=await response.json();if(!response.ok)throw new Error(data.error||'Checkout could not open.');return data.clientSecret}
(async()=>{try{const checkout=await stripe.initEmbeddedCheckout({fetchClientSecret:clientSecret});checkout.mount('#checkout')}catch(error){document.querySelector('#error').textContent=error.message}})();
</script><?php endif; ?></main></body></html>
