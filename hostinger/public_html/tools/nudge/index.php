<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $user = currentUser(); $usage = $user ? usageFor(billingUser($user)) : null; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Nudge | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css?v=3"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
.nudge-grid{display:grid;gap:14px}
@media(min-width:760px){.nudge-grid{grid-template-columns:1fr 1fr}}
.email-card{border:2px solid var(--line);border-radius:14px;background:#fff;padding:16px}
.email-card.suggested{border-color:var(--ink);box-shadow:4px 4px 0 var(--ink)}
.email-card h3{margin:0 0 2px}
.email-card .hint{font-size:13px;opacity:.75;margin:0 0 10px}
.email-card .subject{font-weight:800;margin:0 0 8px}
.email-card pre{white-space:pre-wrap;font:inherit;background:var(--cream);border:2px solid var(--line);border-radius:10px;padding:12px;margin:0 0 10px;max-height:260px;overflow:auto}
.email-card .row{display:flex;gap:8px;flex-wrap:wrap}
.badge{display:inline-block;font-size:12px;font-weight:800;background:var(--yellow);border:2px solid var(--ink);border-radius:999px;padding:2px 10px;margin-left:8px;vertical-align:middle}
#nudgeResult .lede{margin-top:0}
input[type=date]{width:100%;padding:14px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-judging.png" alt="Bum Bum judging late payers"><h1>Get paid without the awkward.</h1><p class="lede">Chasing an overdue invoice is the worst part of freelancing. Tell Nudge the details and it writes three ready to send emails, from a friendly nudge to a final notice, so you never have to agonize over the wording again. One generation uses one action.</p>
<?php if (!$user): ?><section class="panel"><h2>Sign in to use Nudge</h2><a class="button" href="/account/?next=<?= urlencode('/tools/nudge/') ?>">Sign in or create an account</a></section><?php else: ?>
<?php $low = $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<div class="nudge-grid"><form id="tool" class="panel"><label>Your name<input name="yourName" type="text" maxlength="80" required placeholder="Alex Rivera"></label><label>Client name<input name="clientName" type="text" maxlength="80" required placeholder="Jordan at Acme Co"></label><label>Invoice number<input name="invoiceNumber" type="text" maxlength="40" required placeholder="INV-1042"></label><label>Amount<input name="amount" type="number" min="0.01" step="0.01" required placeholder="850.00"></label><label>Due date<input name="dueDate" type="date" required></label><button>Write my nudge emails</button><p id="error" class="error"></p></form><section id="result" class="hidden"><p class="lede" id="resultIntro"></p><div class="nudge-grid" id="emails"></div><p id="usage"></p></section></div>
<div id="upgrade-slot"></div>
<script>
const TOOL_KEY='nudge';
const PERIOD=<?= json_encode($usage['period'] ?? '') ?>;
if(<?= $low ? 'true' : 'false' ?>){const seen='bb_m80_'+PERIOD;if(!localStorage.getItem(seen)){localStorage.setItem(seen,'1');bbTrack('usage_milestone_80',{tool:TOOL_KEY,used:<?= (int) ($usage['used'] ?? 0) ?>,limit:<?= (int) ($usage['limit'] ?? 0) ?>})}}
function upgradeCard(){return `<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) ($usage['limit'] ?? 75) ?> free actions this month. Helper gives you 1,500 actions for $12/month. Operator gives you 6,000 actions plus a custom tool built for you in 36 hours for $49/month.</p><p><a class="button" data-plan="helper" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" data-plan="operator" href="/checkout/?plan=operator">Get Operator, $49/mo</a></p><p><a href="/account/">See your usage</a></p></div>`}
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
const form=document.querySelector('#tool');let attempt=crypto.randomUUID();
form.addEventListener('submit',async event=>{event.preventDefault();const button=form.querySelector('button');button.disabled=true;document.querySelector('#error').textContent='';document.querySelector('#upgrade-slot').innerHTML='';document.querySelector('#result').classList.add('hidden');
const session=await fetch('/api/session.php').then(r=>r.json());const fields=Object.fromEntries(new FormData(form));
const response=await fetch('/api/tools/invoice-nudger.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...fields,csrf:session.csrf,idempotencyKey:attempt})});
const data=await response.json();button.disabled=false;
if(!response.ok){if(response.status===402){bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});document.querySelector('#upgrade-slot').innerHTML=upgradeCard();document.querySelectorAll('#upgrade-slot [data-plan]').forEach(a=>a.addEventListener('click',()=>bbTrack('upgrade_clicked',{plan:a.dataset.plan,context:'limit',tool:TOOL_KEY})))}else{document.querySelector('#error').textContent=data.error||'Something went wrong.'}return}
attempt=crypto.randomUUID();bbTrack('action_completed',{tool:TOOL_KEY,used:data.usage.used,limit:data.usage.limit,remaining:data.usage.remaining});
const r=data.result,order=['friendly','firm','final'];
const sug=r.suggested;
document.querySelector('#resultIntro').innerHTML='Invoice is <b>'+r.daysOverdue+(r.daysOverdue===1?' day':' days')+' overdue</b>. We marked the tone we would send today.';
document.querySelector('#emails').innerHTML=order.map(k=>{const e=r.emails[k];const isSug=k===sug;return `<div class="email-card${isSug?' suggested':''}"><h3>${esc(e.label)}${isSug?'<span class="badge">send this one</span>':''}</h3><p class="hint">${esc(e.hint)}</p><p class="subject">Subject: ${esc(e.subject)}</p><pre id="body-${k}">${esc(e.body)}</pre><div class="row"><button type="button" class="button secondary" data-copy="${k}">Copy email</button></div></div>`}).join('');
document.querySelectorAll('[data-copy]').forEach(b=>b.addEventListener('click',async()=>{const k=b.getAttribute('data-copy');const e=r.emails[k];const text='Subject: '+e.subject+'\n\n'+e.body;try{await navigator.clipboard.writeText(text);b.textContent='Copied!';}catch(_){const ta=document.createElement('textarea');ta.value=text;document.body.appendChild(ta);ta.select();try{document.execCommand('copy');b.textContent='Copied!';}catch(__){b.textContent='Copy failed';}ta.remove();}setTimeout(()=>{b.textContent='Copy email';},2000);bbTrack('nudge_copied',{tool:TOOL_KEY,tone:k})}));
document.querySelector('#usage').textContent=data.usage.remaining+' actions remaining this month.';
document.querySelector('#result').classList.remove('hidden');document.querySelector('#result').scrollIntoView({behavior:'smooth',block:'start'})});
</script><?php endif; ?></main></body></html>
