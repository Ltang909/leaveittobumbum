<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Vibe Check | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.vc-row{display:flex;gap:10px;flex-wrap:wrap;margin-top:14px}
.vc-row input{flex:1;min-width:220px;padding:14px 16px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff}
#vcGo{font-size:18px;padding:14px 26px;margin-top:0}
.vc-status{font-weight:700;margin:14px 0 0;min-height:1.5em}
#vcReport .verdict{display:inline-block;font-size:clamp(1.4rem,3.4vw,2.2rem);font-weight:900;background:#1E2321;color:#E8EBE9;border-radius:999px;padding:10px 26px;margin:0 0 6px}
#vcReport .hostline{color:var(--muted);font-weight:700;margin:0 0 14px}
#vcReport .roast{font-size:19px;line-height:1.6;margin:0 0 18px}
#vcReport .fix{border:2px solid var(--line);border-radius:10px;padding:14px 16px;margin:0 0 12px;background:#fff}
#vcReport .fix .num{font-size:12px;font-weight:900;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}
#vcReport .fix h3{margin:6px 0 8px;font-size:18px}
#vcReport .fix p{margin:6px 0;line-height:1.6}
#vcReport .fix .why{color:var(--muted)}
.mini{padding:8px 14px;font-size:14px}
.hidden{display:none!important}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Vibe Check"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-sunglasses.png" alt="Bum Bum looking judgmental but kind"><h1>Get roasted. Kindly.</h1><p class="lede">Drop in your URL. Bum Bum takes one good look at your site and hands you a kind roast plus three fixes that actually move the needle. One check uses one action.</p>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to use Vibe Check</h2><a class="button" href="/account/?next=<?= urlencode('/tools/vibe-check/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/vibe-check/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/vibe-check/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/vibe-check/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php $out = empty($usage['unlimited']) && ($usage['remaining'] ?? 0) <= 0; ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<?php if ($out): ?>
<div id="upgrade-slot"></div>
<?php else: ?>
<section class="panel" id="vcForm">
<div class="vc-row"><input id="vcUrl" type="text" inputmode="url" autocomplete="url" placeholder="yoursite.com" aria-label="Website address"><button id="vcGo" class="button" type="button">Check my vibe</button></div>
<p id="vcStatus" class="vc-status"></p>
<p id="vcUsage"></p>
</section>
<section class="panel hidden" id="vcReport"><div id="vcReportBody"></div></section>
<div id="upgrade-slot"></div>
<?php endif; ?>
<script>
const TOOL_KEY='vibe-check';
const PERIOD=<?= json_encode($usage['period'] ?? '') ?>;
if(<?= $low ? 'true' : 'false' ?>){const seen='bb_m80_'+PERIOD;if(!localStorage.getItem(seen)){localStorage.setItem(seen,'1');bbTrack('usage_milestone_80',{tool:TOOL_KEY,used:<?= (int) ($usage['used'] ?? 0) ?>,limit:<?= (int) ($usage['limit'] ?? 0) ?>})}}
function upgradeCard(){return `<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) ($usage['limit'] ?? 75) ?> free actions this month. Helper gives you 1,500 actions for $12/month. Operator gives you 6,000 actions plus a custom tool built for you in 36 hours for $49/month.</p><p><a class="button" data-plan="helper" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" data-plan="operator" href="/checkout/?plan=operator">Get Operator, $49/mo</a></p><p><a href="/account/">See your usage</a></p></div>`}
function bindUpgradeClicks(root,context){root.querySelectorAll('[data-plan]').forEach(a=>a.addEventListener('click',()=>bbTrack('upgrade_clicked',{plan:a.dataset.plan,context:context,tool:TOOL_KEY})))}
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
<?php if ($out): ?>
document.querySelector('#upgrade-slot').innerHTML=upgradeCard();
bindUpgradeClicks(document,'page_load');
bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'page_load'});
<?php else: ?>
const urlEl=document.querySelector('#vcUrl');
const goBtn=document.querySelector('#vcGo');
const statusEl=document.querySelector('#vcStatus');
const usageEl=document.querySelector('#vcUsage');
const reportSec=document.querySelector('#vcReport');
const reportBody=document.querySelector('#vcReportBody');
let checkKey=crypto.randomUUID();
const LOADING_MSGS=['Knocking on the door...','Peeking through the windows...','Judging the curtains...','Reading the fine print...','Writing the roast...'];
let loadInt=0;
function setStatus(msg,isError){statusEl.textContent=msg;statusEl.classList.toggle('error',!!isError);}
function startLoading(){let i=0;setStatus(LOADING_MSGS[0]);loadInt=setInterval(()=>{i=(i+1)%LOADING_MSGS.length;setStatus(LOADING_MSGS[i]);},1600);}
function stopLoading(){clearInterval(loadInt);}
function copyText(t,btn){const done=()=>{const old=btn.textContent;btn.textContent='Copied!';setTimeout(()=>{btn.textContent=old;},1500);};if(navigator.clipboard&&navigator.clipboard.writeText){navigator.clipboard.writeText(t).then(done).catch(()=>{});}else{done();}}
function renderReport(r){
const fixes=(r.fixes||[]).map((f,i)=>`<div class="fix"><span class="num">Fix ${i+1}${i===0?' · biggest win':''}</span><h3>${esc(f.title)}</h3><p class="why"><strong>Why it matters:</strong> ${esc(f.why)}</p><p><strong>How to fix it:</strong> ${esc(f.how)}</p><button type="button" class="button secondary mini" data-copy="${esc(f.title+'\nWhy it matters: '+f.why+'\nHow to fix it: '+f.how)}">Copy fix</button></div>`).join('');
reportBody.innerHTML=`<span class="verdict">${esc(r.verdict)}</span><p class="hostline">Vibe report for ${esc(r.host||'your site')}</p><p class="roast">${esc(r.roast)}</p>${fixes}`;
reportBody.querySelectorAll('[data-copy]').forEach(b=>b.addEventListener('click',()=>copyText(b.getAttribute('data-copy'),b)));
reportSec.classList.remove('hidden');reportSec.scrollIntoView({behavior:'smooth',block:'nearest'});
}
async function runCheck(){
const url=urlEl.value.trim();
if(!url){setStatus('Give me a URL first, like yoursite.com.',true);urlEl.focus();return;}
const key=checkKey;checkKey=crypto.randomUUID();
goBtn.disabled=true;reportSec.classList.add('hidden');usageEl.textContent='';startLoading();
try{
const session=await fetch('/api/session.php').then(r=>r.json());
const response=await fetch('/api/tools/vibe-check.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'check',url:url,idempotencyKey:key,csrf:session.csrf})});
let data={};try{data=await response.json()}catch(e){}
if(!response.ok){
if(response.status===402){bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});document.querySelector('#upgrade-slot').innerHTML=upgradeCard();bindUpgradeClicks(document.querySelector('#upgrade-slot'),'limit');setStatus('This check was not counted.',true);}
else{setStatus(data.error||'Vibe Check hiccup. Try again.',true);}
return;}
bbTrack('action_completed',{tool:TOOL_KEY,used:data.usage.used,limit:data.usage.limit,remaining:data.usage.remaining});
renderReport(data.report);
setStatus('Done. Take the roast, keep the fixes.');
usageEl.textContent=data.usage.unlimited?'Unlimited actions.':data.usage.remaining+' actions left.';
}catch(e){setStatus('Vibe Check hiccup. Try again.',true);}
finally{stopLoading();goBtn.disabled=false;}
}
goBtn.addEventListener('click',runCheck);
urlEl.addEventListener('keydown',e=>{if(e.key==='Enter')runCheck();});
<?php endif; ?>
</script><?php endif; ?></main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
