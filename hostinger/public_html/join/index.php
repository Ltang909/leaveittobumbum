<?php require dirname(__DIR__) . '/api/_bootstrap.php';
$token = (string) ($_GET['token'] ?? '');
$invite = null;
if (preg_match('/^[a-f0-9]{64}$/', $token)) {
    try {
        $stmt = db()->prepare("SELECT tm.id, u.email AS owner_email, u.plan AS owner_plan FROM team_members tm JOIN users u ON u.id = tm.owner_user_id WHERE tm.invite_token = ? AND tm.status = 'invited' LIMIT 1");
        $stmt->execute([$token]);
        $invite = $stmt->fetch() ?: null;
    } catch (Throwable $e) { $invite = null; }
}
$user = currentUser();
$next = '/join/?token=' . urlencode($token);
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Join a team | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css?v=3"><?php require __DIR__ . '/../includes/analytics.php'; ?></head><body>
<?php $showMeter = false; require __DIR__ . '/../includes/site-header.php'; ?><main class="shell">
<?php if (!$invite): ?>
<p class="eyebrow">Team invite</p><h1>This invite link is spent.</h1>
<p class="lede"><img src="/bum/cat-sleepy.png" alt="Bum Bum napping" class="sticker" style="width:88px;vertical-align:middle;margin-right:10px">It was already used, revoked, or never existed. Ask the team owner for a fresh one.</p>
<a class="button" href="/account/">Go to your account</a>
<?php elseif (!$user): ?>
<p class="eyebrow">Team invite</p><h1><?= htmlspecialchars($invite['owner_email']) ?> invited you to their Bum Bum team.</h1>
<p class="lede">Sign in or create a free account to accept. Your work will draw from their shared action bucket.</p>
<div class="grid"><section class="panel"><h2>Sign in</h2><form data-action="login"><label>Work email</label><input name="email" type="email" required><label>Password</label><input name="password" type="password" minlength="10" required><button>Sign in</button><p class="error"></p></form></section><section class="panel"><h2>Create an account</h2><p>Joining a team is free. Your actions count against the team owner's plan.</p><form data-action="register"><label>Work email</label><input name="email" type="email" required><label>Password</label><input name="password" type="password" minlength="10" required><button>Create free account</button><p class="error"></p></form></section></div>
<script>
document.querySelectorAll('form').forEach(form=>form.addEventListener('submit',async event=>{event.preventDefault();const button=form.querySelector('button');button.disabled=true;const session=await fetch('/api/session.php').then(r=>r.json());const fields=Object.fromEntries(new FormData(form));const response=await fetch('/api/auth.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...fields,action:form.dataset.action,csrf:session.csrf})});const data=await response.json();if(response.ok){location.href=<?= json_encode($next) ?>}else{form.querySelector('.error').textContent=data.error;button.disabled=false}}));
</script>
<?php else: $isOwn = strtolower((string)$user['email']) === strtolower((string)$invite['owner_email']); $already = teamMembership((int)$user['id']); $hasPaid = in_array($user['subscription_status'], ['active','trialing','past_due'], true) && $user['plan'] !== 'free'; ?>
<p class="eyebrow">Team invite</p><h1>Join <?= htmlspecialchars($invite['owner_email']) ?>'s team?</h1>
<div class="grid"><section class="panel">
<p><img src="/bum/cat-peek.png" alt="Bum Bum peeking in" class="sticker" style="width:96px;vertical-align:middle;margin-right:10px">Here's the deal:</p>
<ul>
<li>Your completed actions draw from <b><?= htmlspecialchars($invite['owner_email']) ?></b>'s shared bucket (<?= htmlspecialchars(ucfirst($invite['owner_plan'])) ?> plan).</li>
<li>You keep your own login. Only the owner can manage billing and seats.</li>
<?php if ($hasPaid): ?><li><b>Heads up:</b> your own <?= htmlspecialchars(ucfirst($user['plan'])) ?> subscription will sit dormant while you're on the team. It keeps its billing schedule, and your own plan takes over again if you leave.</li><?php endif; ?>
</ul>
<?php if ($isOwn): ?><p class="lede">This invite is for your own team. You already have the best seat.</p>
<?php elseif ($already): ?><p class="lede">You're already on a team. Leave it from your account page to join another.</p><a class="button secondary" href="/account/">Go to your account</a>
<?php else: ?><button id="joinBtn">Join the team</button><p id="joinMsg" class="error"></p><?php endif; ?>
</section></div>
<script>
const joinBtn=document.querySelector('#joinBtn');
if(joinBtn)joinBtn.addEventListener('click',async()=>{joinBtn.disabled=true;const s=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/team.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'accept',token:<?= json_encode($token) ?>,csrf:s.csrf})});const data=await response.json();if(response.ok){location.href='/account/'}else{document.querySelector('#joinMsg').textContent=data.error;joinBtn.disabled=false}});
</script>
<?php endif; ?>
</main></body></html>
