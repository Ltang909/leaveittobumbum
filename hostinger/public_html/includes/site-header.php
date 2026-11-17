<?php
// Shared site header: same nav + logo as the static pages.
// Expects $user (array|null). $showMeter is accepted for compatibility but
// renders nothing since the 2026-11-17 pricing reset removed the usage pill.
// Set $bbLang = 'es' on localized pages to render the chrome in Spanish.
$shUser = isset($user) ? $user : (function_exists('currentUser') ? currentUser() : null);
$shMeter = !empty($showMeter);
$shEs = isset($bbLang) && $bbLang === 'es';
$shL = $shEs
  ? ['tools' => 'Herramientas', 'about' => 'Acerca de', 'account' => 'Cuenta', 'signin' => 'Iniciar sesión', 'signout' => 'Cerrar sesión', 'nav' => 'Navegación principal', 'open' => 'Abrir menú', 'close' => 'Cerrar menú', 'guide_show' => 'Leer la guía', 'guide_hide' => 'Ocultar la guía']
  : ['tools' => 'Tools', 'about' => 'About', 'account' => 'Account', 'signin' => 'Sign in', 'signout' => 'Sign out', 'nav' => 'Main navigation', 'open' => 'Open menu', 'close' => 'Close menu', 'guide_show' => 'Read the guide', 'guide_hide' => 'Hide the guide'];
?>
<header class="nav <?php echo !empty($navShellWide) ? 'shell shell-wide' : 'shell'; ?>">
  <a class="brand" href="/" aria-label="Leave It to Bum Bum home" title="psst… boop the cat on the homepage"><img class="brand-cat" src="/bum/favicon-cat.png" alt="Bum Bum the cat"><span>Leave It to<br><b>Bum Bum</b></span></a>
  <nav aria-label="<?= $shL['nav'] ?>" id="bbPhpNav"><a href="/tools/"><?= $shL['tools'] ?></a><a href="/about/"><?= $shL['about'] ?></a><a href="/36-hours/">36 hours</a><a href="/account/"><?= $shL['account'] ?></a></nav>
  <span class="nav-right"><?php if ($shMeter) require __DIR__ . '/meter.php'; ?><?php if ($shUser): ?><button type="button" class="button button-small secondary" id="bbSignOut"><?= $shL['signout'] ?></button><?php else: ?><a class="button button-small" href="/account/"><?= $shL['signin'] ?></a><?php endif; ?></span>
  <button type="button" class="menu-toggle" id="bbMenuToggle" aria-expanded="false" aria-controls="bbPhpNav" aria-label="<?= $shL['open'] ?>"><span aria-hidden="true"></span><span aria-hidden="true"></span><span aria-hidden="true"></span></button>
</header>
<script>
(function(){var t=document.getElementById('bbMenuToggle'),n=document.getElementById('bbPhpNav');if(!t||!n)return;t.addEventListener('click',function(){var open=n.classList.toggle('open');t.setAttribute('aria-expanded',open?'true':'false');t.setAttribute('aria-label',open?'<?= $shL['close'] ?>':'<?= $shL['open'] ?>');});n.addEventListener('click',function(e){if(e.target.closest('a')){n.classList.remove('open');t.setAttribute('aria-expanded','false');t.setAttribute('aria-label','<?= $shL['open'] ?>');}});})();
</script>
<?php if ($shUser): ?><script>
(function(){var b=document.getElementById('bbSignOut');if(!b)return;b.addEventListener('click',async function(){b.disabled=true;try{var s=await fetch('/api/session.php').then(function(r){return r.json()});await fetch('/api/auth.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'logout',csrf:s.csrf})});}catch(e){}location.reload();});})();
</script><?php endif; ?>
<?php // Guest analytics identity (the old conversion modal was removed in the pricing reset). ?>
<?php require __DIR__ . '/guest-convert.php'; ?>
<script>
// Short guides start minimized: heading stays, body sits behind a toggle.
document.addEventListener('DOMContentLoaded',function(){
  var panel=document.querySelector('section[aria-label="Guide"],section[aria-label="Guía"]');
  if(!panel)return;
  var h2=panel.querySelector('h2');
  if(!h2)return;
  var body=document.createElement('div');
  body.className='guide-body';
  var node=h2.nextSibling;
  while(node){var next=node.nextSibling;body.appendChild(node);node=next;}
  body.hidden=true;
  panel.appendChild(body);
  var btn=document.createElement('button');
  btn.type='button';
  btn.className='guide-toggle';
  btn.textContent='<?= $shL['guide_show'] ?>';
  btn.setAttribute('aria-expanded','false');
  btn.addEventListener('click',function(){
    var open=body.hidden;
    body.hidden=!open;
    btn.textContent=open?'<?= $shL['guide_hide'] ?>':'<?= $shL['guide_show'] ?>';
    btn.setAttribute('aria-expanded',open?'true':'false');
  });
  h2.insertAdjacentElement('afterend',btn);
});
</script>
