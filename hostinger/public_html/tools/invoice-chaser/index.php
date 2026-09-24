<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $user = currentUser(); $usage = $user ? usageFor(billingUser($user)) : null; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Invoice Chaser | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.chips{display:flex;gap:8px;flex-wrap:wrap;margin:12px 0}
.chip{border:2px solid var(--line);border-radius:999px;padding:6px 14px;font-weight:800;font-size:14px;background:#fff;cursor:pointer}
.chip.on{background:var(--ink);color:#fff;border-color:var(--ink)}
.stat-row{display:flex;gap:12px;flex-wrap:wrap;margin:12px 0}
.stat-card{flex:1;min-width:140px;border:2px solid var(--line);border-radius:14px;background:#fff;padding:12px;text-align:center}
.stat-card b{font-size:22px;display:block}
.stat-card span{font-size:12px;font-weight:700;opacity:.75}
.stat-card small{display:block;font-size:12px;opacity:.75;margin-top:4px;line-height:1.5}
.invoice{border:2px solid var(--line);border-radius:14px;background:#fff;padding:14px;margin-bottom:10px}
.invoice .top{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.invoice .top b{font-size:17px}
.invoice .amount{font-family:Fraunces,Georgia,serif;font-size:1.35rem;font-weight:700}
.badge{font-size:12px;font-weight:800;border:2px solid var(--ink);border-radius:999px;padding:2px 10px}
.badge.ok{background:#b7d6ff}.badge.warn{background:var(--yellow)}.badge.hot{background:#ff6b35;color:#fff}.badge.fire{background:#e5484d;color:#fff}.badge.paid{background:#2f9e5f;color:#fff}
.meta{font-size:13px;opacity:.8;margin:6px 0 0}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}
#invoiceList{max-height:560px;overflow-y:auto;padding-right:6px}
#addForm input,#addForm select,#addForm textarea{width:100%;padding:10px;border:2px solid var(--line);border-radius:10px;font:inherit;background-color:#fff}
#addForm textarea{min-height:70px}
.form-grid{display:grid;gap:12px}@media(min-width:760px){.form-grid{grid-template-columns:1fr 1fr}}
.span-all{grid-column:1/-1}
.draft-box{background:var(--cream);border:2px solid var(--line);border-radius:10px;padding:12px;margin:8px 0;font-size:14px;white-space:pre-wrap}
.draft-subject{font-weight:800;margin-bottom:6px}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:100;display:flex;align-items:flex-end;justify-content:center;padding:0}
@media(min-width:760px){.modal-overlay{align-items:center;padding:24px}}
.modal{background:#fff;border-radius:20px 20px 0 0;width:100%;max-width:560px;max-height:92vh;overflow-y:auto;padding:20px;position:relative}
@media(min-width:760px){.modal{border-radius:20px}}
.modal-close{position:absolute;top:16px;right:16px;border:2px solid var(--line);background:#fff;border-radius:999px;width:36px;height:36px;font-size:18px;font-weight:800;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0}
.modal h2{margin:0 0 4px;padding-right:44px}
.hidden{display:none!important}
/* ---- Soft UI pass: easier on the eyes ---- */
.invoice,.stat-card,.modal,.modal-close{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);color:#38332a}
.chip{border:1px solid #ddd1b8;font-weight:700}
.chip.on{border-color:#2f2a22}
.badge{border:1px solid #d9cdae;font-weight:700}
.invoice .top b{font-weight:700}
#addForm label{font-weight:600}
#addForm input:focus,#addForm select:focus,#addForm textarea:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
#addForm input,#addForm select,#addForm textarea{border:1px solid #ddd1b8;font-weight:500}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
.draft-box{border:1px solid #d9cdae}
.modal-close{font-weight:700}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Invoice Chaser"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-bowtie.png" alt="Bum Bum in a bowtie, ready to collect"><h1>Get paid without the awkward.</h1><p class="lede">Invoice Chaser tracks who owes you what, writes the chase email in the right tone for how overdue it is, and emails you a reminder digest so nothing slips. Adding an invoice uses one action. Everything else is free.</p>
<?php if (!$user): ?><section class="panel"><h2>Sign in to chase invoices</h2><a class="button" href="/account/?next=<?= urlencode('/tools/invoice-chaser/') ?>">Sign in or create an account</a></section><?php else: ?>
<?php $low = $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<div class="stat-row">
  <div class="stat-card"><b id="statOutstanding">–</b><span>outstanding</span><small id="statOutstandingBy"></small></div>
  <div class="stat-card"><b id="statOverdue">–</b><span>overdue</span><small id="statOverdueBy"></small></div>
  <div class="stat-card"><b id="statOpen">0</b><span>open invoices</span><small id="statPaid"></small></div>
</div>
<section class="panel"><div class="yp-head" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap"><h2 style="margin:0">Your chase list</h2><button type="button" class="button" id="remindBtn">Email me the chase list</button></div><p class="lede" style="margin-top:8px">One email with everything overdue and upcoming, sent to <?= htmlspecialchars($user['email'] ?? '') ?>. Uses one action; repeat sends on the same day are free.</p><p id="remindMsg" class="lede"></p><div class="chips" id="filterChips"></div><div id="invoiceList"><p class="lede">Loading...</p></div><p id="listError" class="error"></p><p id="usage"></p></section>
<section class="panel"><h2 style="margin-top:0">Add an invoice</h2><form id="addForm"><div class="form-grid"><label>Client name<input name="client_name" type="text" maxlength="191" required placeholder="Acme Inc"></label><label>Amount<input name="amount" type="number" min="0.01" step="0.01" required placeholder="1200.00"></label><label>Currency<select name="currency"><option value="USD">USD ($)</option><option value="CAD">CAD (CA$)</option><option value="EUR">EUR (€)</option><option value="GBP">GBP (£)</option></select></label><label>Invoice # (optional)<input name="invoice_no" type="text" maxlength="64" placeholder="INV-2026-014"></label><label>Due date<input name="due_date" type="date" required></label><label>Notes (optional)<textarea name="notes" maxlength="2000" placeholder="Net 30, sent Sep 1..."></textarea></label></div><button class="button" style="margin-top:12px">Add invoice</button><p id="addError" class="error"></p></form></section>
<div id="upgrade-slot"></div>
<div class="modal-overlay hidden" id="modalOverlay"><div class="modal" role="dialog" aria-modal="true"><button class="modal-close" id="modalClose" aria-label="Close">×</button><div id="modalBody"></div></div></div>
<script>
const TOOL_KEY='invoice-chaser';
const PERIOD=<?= json_encode($usage['period'] ?? '') ?>;
let DATA={invoices:[],stats:{}};
let FILTER='all';
if(<?= $low ? 'true' : 'false' ?>){const seen='bb_m80_'+PERIOD;if(!localStorage.getItem(seen)){localStorage.setItem(seen,'1');bbTrack('usage_milestone_80',{tool:TOOL_KEY,used:<?= (int) ($usage['used'] ?? 0) ?>,limit:<?= (int) ($usage['limit'] ?? 0) ?>})}}
function upgradeCard(){return `<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) ($usage['limit'] ?? 75) ?> free actions this month. Helper gives you 1,500 actions for $12/month. Operator gives you 6,000 actions plus a custom tool built for you in 36 hours for $49/month.</p><p><a class="button" data-plan="helper" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" data-plan="operator" href="/checkout/?plan=operator">Get Operator, $49/mo</a></p><p><a href="/account/">See your usage</a></p></div>`}
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/invoice-chaser.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}
function badge(inv){
  if(inv.status==='paid')return '<span class="badge paid">paid</span>';
  const d=inv.days_overdue;
  if(d<0)return `<span class="badge ok">due ${esc(inv.due_date)}</span>`;
  if(d===0)return '<span class="badge warn">due today</span>';
  if(d<=14)return `<span class="badge warn">${d}d overdue</span>`;
  if(d<=30)return `<span class="badge hot">${d}d overdue</span>`;
  return `<span class="badge fire">${d}d overdue</span>`;
}
function bucket(inv){
  if(inv.status==='paid')return 'paid';
  return inv.days_overdue>0?'overdue':'upcoming';
}
function invoiceCard(inv){
  const parts=[];
  if(inv.invoice_no)parts.push('invoice '+esc(inv.invoice_no));
  if(inv.days_overdue>0)parts.push(`was due ${esc(inv.due_date)}`);
  if(inv.notes)parts.push(esc(inv.notes));
  return `<div class="invoice"><div class="top"><b>${esc(inv.client_name)}</b><span class="amount">${esc(inv.amount_display)}</span>${badge(inv)}<span style="flex:1"></span>`
    +(inv.status==='open'
      ? `<button type="button" class="button secondary" data-draft="${inv.id}">Chase draft</button><button type="button" class="button secondary" data-paid="${inv.id}">Mark paid</button>`
      : `<button type="button" class="button secondary" data-reopen="${inv.id}">Reopen</button>`)
    +`<button type="button" class="button secondary" data-del="${inv.id}">Delete</button></div>`
    +(parts.length?`<p class="meta">${parts.join(' · ')}</p>`:'')+`</div>`;
}
function renderList(){
  const el=document.querySelector('#invoiceList');
  const counts={all:DATA.invoices.length,overdue:0,upcoming:0,paid:0};
  DATA.invoices.forEach(i=>counts[bucket(i)]++);
  document.querySelector('#filterChips').innerHTML=['all','overdue','upcoming','paid'].map(f=>`<button type="button" class="chip${FILTER===f?' on':''}" data-filter="${f}">${f[0].toUpperCase()+f.slice(1)} (${counts[f]})</button>`).join('');
  document.querySelectorAll('[data-filter]').forEach(b=>b.addEventListener('click',()=>{FILTER=b.getAttribute('data-filter');renderList();}));
  const list=DATA.invoices.filter(i=>FILTER==='all'||bucket(i)===FILTER);
  if(!list.length){el.innerHTML='<p class="lede">'+(FILTER==='paid'?'No paid invoices yet. Go get that money.':'Nothing here. '+(FILTER==='all'?'Add your first invoice below.':'All clear in this view.'))+'</p>';return}
  el.innerHTML=list.map(invoiceCard).join('');
  bindButtons(el);
}
function renderStats(){
  const s=DATA.stats||{};
  const open=DATA.invoices.filter(i=>i.status==='open');
  const byCur={},byCurOver={};
  open.forEach(i=>{byCur[i.currency]=(byCur[i.currency]||0)+i.amount;if(i.days_overdue>0)byCurOver[i.currency]=(byCurOver[i.currency]||0)+i.amount});
  const fmtCur=o=>Object.keys(o).sort().map(c=>`${c} ${o[c].toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}`).join(' · ');
  const tot=Object.keys(byCur).length,totO=Object.keys(byCurOver).length;
  document.querySelector('#statOutstanding').textContent=open.length? (tot===1?fmtCur(byCur):open.length+' invoices') : '$0.00';
  document.querySelector('#statOutstandingBy').textContent=tot>1?fmtCur(byCur):'';
  const od=open.filter(i=>i.days_overdue>0).length;
  document.querySelector('#statOverdue').textContent=od? (totO===1?fmtCur(byCurOver):od+' invoices') : 'None';
  document.querySelector('#statOverdueBy').textContent=totO>1?fmtCur(byCurOver):'';
  document.querySelector('#statOpen').textContent=open.length;
  const paid=DATA.invoices.filter(i=>i.status==='paid').length;
  document.querySelector('#statPaid').textContent=paid?paid+' paid, nice':'';
}
function bindButtons(root){
  root.querySelectorAll('[data-draft]').forEach(b=>b.addEventListener('click',()=>openDraft(parseInt(b.getAttribute('data-draft'),10))));
  root.querySelectorAll('[data-paid]').forEach(b=>b.addEventListener('click',async()=>{
    b.disabled=true;
    const{response,data}=await apiCall({action:'update',id:parseInt(b.getAttribute('data-paid'),10),status:'paid'});
    b.disabled=false;
    if(!response.ok){document.querySelector('#listError').textContent=data.error||'Update failed.';return}
    bbTrack('chaser_paid',{tool:TOOL_KEY});await refresh();
  }));
  root.querySelectorAll('[data-reopen]').forEach(b=>b.addEventListener('click',async()=>{
    b.disabled=true;
    const{response,data}=await apiCall({action:'update',id:parseInt(b.getAttribute('data-reopen'),10),status:'open'});
    b.disabled=false;
    if(!response.ok){document.querySelector('#listError').textContent=data.error||'Update failed.';return}
    await refresh();
  }));
  root.querySelectorAll('[data-del]').forEach(b=>b.addEventListener('click',async()=>{
    if(b.dataset.armed!=='1'){b.dataset.armed='1';b.dataset.orig=b.textContent;b.textContent='Tap again to delete';setTimeout(()=>{if(b.isConnected&&b.dataset.armed==='1'){b.dataset.armed='';b.textContent=b.dataset.orig}},6000);return}
    b.disabled=true;
    const{response,data}=await apiCall({action:'delete',id:parseInt(b.getAttribute('data-del'),10)});
    b.disabled=false;
    if(!response.ok){b.dataset.armed='';b.textContent=b.dataset.orig||'Delete';document.querySelector('#listError').textContent=data.error||'Delete failed.';return}
    document.querySelector('#listError').textContent='';
    bbTrack('chaser_deleted',{tool:TOOL_KEY});await refresh();
  }));
}
async function openDraft(id){
  const inv=DATA.invoices.find(x=>x.id===id);
  const overlay=document.querySelector('#modalOverlay'),body=document.querySelector('#modalBody');
  overlay.classList.remove('hidden');document.body.style.overflow='hidden';
  body.innerHTML='<p class="lede">Writing your chase email...</p>';
  const{response,data}=await apiCall({action:'draft',id});
  if(!response.ok){body.innerHTML=`<p class="error">${esc(data.error||'Could not load.')}</p>`;return}
  const tone=inv.days_overdue<0?'Heads-up':inv.days_overdue<=14?'Gentle nudge':inv.days_overdue<=30?'Firm follow-up':'Final notice';
  body.innerHTML=`<h2>Chase draft</h2><p class="lede">${esc(inv.client_name)} · ${esc(inv.amount_display)} · <b>${tone}</b> (${inv.days_overdue<0?'not due yet':inv.days_overdue===0?'due today':inv.days_overdue+' days overdue'})</p>`
    +`<div class="draft-subject">Subject: ${esc(data.subject)}</div><div class="draft-box">${esc(data.body)}</div>`
    +`<div class="btnrow"><button type="button" class="button secondary" id="copySubject">Copy subject</button><button type="button" class="button secondary" id="copyBody">Copy email</button></div><p class="error" id="draftErr"></p>`;
  const copy=(text,btn,okLabel)=>{
    btn.addEventListener('click',async()=>{
      try{await navigator.clipboard.writeText(text);btn.textContent=okLabel;}catch(_){btn.textContent='Copy failed'}
      setTimeout(()=>{btn.textContent=btn.dataset.orig},2000);
    });
    btn.dataset.orig=btn.textContent;
  };
  copy(data.subject,body.querySelector('#copySubject'),'Subject copied!');
  copy(data.body,body.querySelector('#copyBody'),'Email copied!');
  bbTrack('chaser_draft_viewed',{tool:TOOL_KEY});
}
function closeModal(){document.querySelector('#modalOverlay').classList.add('hidden');document.body.style.overflow='';}
document.querySelector('#modalClose').addEventListener('click',closeModal);
document.querySelector('#modalOverlay').addEventListener('click',e=>{if(e.target.id==='modalOverlay')closeModal()});
document.addEventListener('keydown',e=>{if(e.key==='Escape'&&!document.querySelector('#modalOverlay').classList.contains('hidden'))closeModal()});
async function refresh(){
  const{response,data}=await apiCall({action:'list'});
  if(!response.ok){document.querySelector('#invoiceList').innerHTML=`<p class="error">${esc(data.error||'Could not load.')}</p>`;return}
  DATA=data;renderStats();renderList();
}
document.querySelector('#remindBtn').addEventListener('click',async e=>{
  const btn=e.currentTarget;btn.disabled=true;
  const msg=document.querySelector('#remindMsg');msg.textContent='Sending...';document.querySelector('#upgrade-slot').innerHTML='';
  const{response,data}=await apiCall({action:'remind'});
  btn.disabled=false;
  if(!response.ok){
    if(response.status===402){bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});document.querySelector('#upgrade-slot').innerHTML=upgradeCard();document.querySelectorAll('#upgrade-slot [data-plan]').forEach(a=>a.addEventListener('click',()=>bbTrack('upgrade_clicked',{plan:a.dataset.plan,context:'limit',tool:TOOL_KEY})))}
    msg.textContent=data.error||'Something went wrong.';
    return;
  }
  bbTrack('action_completed',{tool:TOOL_KEY,used:data.usage.used,limit:data.usage.limit,remaining:data.usage.remaining});
  document.querySelector('#usage').textContent=data.usage.unlimited?'Unlimited actions.':data.usage.remaining+' actions remaining this month.';
  msg.textContent=`Sent to ${data.sent_to}${data.duplicate?' (already sent today, no extra charge)':''}. Go get that money.`;
  bbTrack('chaser_reminded',{tool:TOOL_KEY,overdue:data.overdue});
});
const addForm=document.querySelector('#addForm');let attempt=crypto.randomUUID();
addForm.addEventListener('submit',async e=>{
  e.preventDefault();
  const btn=addForm.querySelector('button');btn.disabled=true;
  document.querySelector('#addError').textContent='';document.querySelector('#upgrade-slot').innerHTML='';
  const fields=Object.fromEntries(new FormData(addForm));
  const{response,data}=await apiCall({action:'add',...fields,idempotencyKey:attempt});
  btn.disabled=false;
  if(!response.ok){
    if(response.status===402){bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});document.querySelector('#upgrade-slot').innerHTML=upgradeCard();document.querySelectorAll('#upgrade-slot [data-plan]').forEach(a=>a.addEventListener('click',()=>bbTrack('upgrade_clicked',{plan:a.dataset.plan,context:'limit',tool:TOOL_KEY})))}
    else{document.querySelector('#addError').textContent=data.error||'Something went wrong.'}
    return;
  }
  attempt=crypto.randomUUID();
  bbTrack('action_completed',{tool:TOOL_KEY,used:data.usage.used,limit:data.usage.limit,remaining:data.usage.remaining});
  document.querySelector('#usage').textContent=data.usage.unlimited?'Unlimited actions.':data.usage.remaining+' actions remaining this month.';
  addForm.reset();await refresh();
});
refresh();
</script><?php endif; ?></main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
