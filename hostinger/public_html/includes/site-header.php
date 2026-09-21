<?php
// Shared site header: same nav + logo as the static pages.
// Expects $user (array|null). Set $showMeter = true to render the usage meter pill.
$shUser = isset($user) ? $user : (function_exists('currentUser') ? currentUser() : null);
$shMeter = !empty($showMeter);
?>
<header class="nav shell">
  <a class="brand" href="/" aria-label="Leave It to Bum Bum home" title="psst… boop the cat on the homepage"><img class="brand-cat" src="/bum/favicon-cat.png" alt="Bum Bum the cat"><span>Leave It to<br><b>Bum Bum</b></span></a>
  <nav aria-label="Main navigation" id="bbPhpNav"><a href="/tools/">Tools</a><a href="/#pricing">Pricing</a><a href="/#guarantee">36 hours</a><a href="/account/">Account</a></nav>
  <span class="nav-right"><?php if ($shMeter) require __DIR__ . '/meter.php'; ?><?php if ($shUser): ?><button type="button" class="button button-small secondary" id="bbSignOut">Sign out</button><?php else: ?><a class="button button-small" href="/account/">Sign in</a><?php endif; ?></span>
  <button type="button" class="menu-toggle" id="bbMenuToggle" aria-expanded="false" aria-controls="bbPhpNav" aria-label="Open menu"><span aria-hidden="true"></span><span aria-hidden="true"></span><span aria-hidden="true"></span></button>
</header>
<script>
(function(){var t=document.getElementById('bbMenuToggle'),n=document.getElementById('bbPhpNav');if(!t||!n)return;t.addEventListener('click',function(){var open=n.classList.toggle('open');t.setAttribute('aria-expanded',open?'true':'false');t.setAttribute('aria-label',open?'Close menu':'Open menu');});n.addEventListener('click',function(e){if(e.target.closest('a')){n.classList.remove('open');t.setAttribute('aria-expanded','false');t.setAttribute('aria-label','Open menu');}});})();
</script>
<script>
(function(){var t=document.getElementById('bbMenuToggle'),n=document.getElementById('bbPhpNav');if(!t||!n)return;t.addEventListener('click',function(){var open=n.classList.toggle('open');t.setAttribute('aria-expanded',open?'true':'false');t.setAttribute('aria-label',open?'Close menu':'Open menu');});n.addEventListener('click',function(e){if(e.target.closest('a')){n.classList.remove('open');t.setAttribute('aria-expanded','false');t.setAttribute('aria-label','Open menu');}});})();
</script>
<?php if ($shUser): ?><script>
(function(){var b=document.getElementById('bbSignOut');if(!b)return;b.addEventListener('click',async function(){b.disabled=true;try{var s=await fetch('/api/session.php').then(function(r){return r.json()});await fetch('/api/auth.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'logout',csrf:s.csrf})});}catch(e){}location.reload();});})();
</script><?php endif; ?>
