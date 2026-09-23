<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $user = currentUser(); $usage = $user ? usageFor(billingUser($user)) : null; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Purrsuit | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css?v=4"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700;9..144,900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
.chips{display:flex;gap:8px;flex-wrap:wrap;margin:12px 0}
h1,.lede{max-width:none}
.chip{border:2px solid var(--line);border-radius:999px;padding:6px 14px;font-weight:800;font-size:14px;background:#fff;cursor:pointer}
.chip.on{background:var(--ink);color:#fff;border-color:var(--ink)}
.contact{border:2px solid var(--line);border-radius:14px;background:#fff;padding:14px;margin-bottom:10px}
.contact .top{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.contact .top b{font-size:17px}
.stage{font-size:12px;font-weight:800;border:2px solid var(--ink);border-radius:999px;padding:2px 10px;text-transform:capitalize}
.stage.new{background:var(--mint)}.stage.talking{background:var(--yellow)}.stage.quoted{background:var(--blue);color:#fff}.stage.won{background:var(--ink);color:#fff}.stage.lost{opacity:.55}
.meta{font-size:13px;opacity:.8;margin:6px 0 0}
.detail{margin-top:12px;border-top:2px dashed var(--line);padding-top:12px}
.detail label{display:block;margin:8px 0}
.detail input,.detail select,.detail textarea{width:100%;padding:10px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff}
.detail textarea{min-height:70px}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:8px}
.timeline{margin:10px 0 0;padding:0;list-style:none}
.timeline li{border-left:3px solid var(--line);padding:4px 0 4px 12px;margin:0 0 8px;font-size:14px}
.timeline .when{font-size:12px;opacity:.65}
.followup{border:2px solid var(--ink);border-radius:14px;background:#fff;padding:14px;margin-bottom:10px;box-shadow:4px 4px 0 var(--yellow)}
.followup .draft{background:var(--cream);border:2px solid var(--line);border-radius:10px;padding:10px;margin:8px 0;font-size:14px;white-space:pre-wrap}
.followup .top{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
#contactList{max-height:520px;overflow-y:auto;padding-right:6px}
#addForm input,#addForm select{width:100%;padding:10px;border:2px solid var(--line);border-radius:10px;font:inherit;background-color:#fff}
#addForm textarea{width:100%;padding:10px;border:2px solid var(--line);border-radius:10px;font:inherit;background-color:#fff;min-height:70px}
.add-import-grid{display:grid;gap:28px;margin-top:28px}
.add-import-grid .panel{margin-top:0;margin-bottom:0}
@media(min-width:760px){.add-import-grid{grid-template-columns:1fr 1fr;gap:16px}}
.span-all{grid-column:1/-1}
.yp-head{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:4px}
.yp-head h2{margin:0!important}
.yp-head .button{margin-top:0}
.modal select,#addForm select{appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' fill='none' stroke='%23000' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 14px center;padding-right:38px!important;cursor:pointer}
.quiet{box-shadow:4px 4px 0 var(--line)}
input[type=date]{width:100%;padding:14px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:100;display:flex;align-items:flex-end;justify-content:center;padding:0}
@media(min-width:760px){.modal-overlay{align-items:center;padding:24px}}
.modal{background:#fff;border-radius:20px 20px 0 0;width:100%;max-width:560px;max-height:92vh;overflow-y:auto;padding:20px;position:relative}
@media(min-width:760px){.modal{border-radius:20px}}
.modal-close{position:absolute;top:16px;right:16px;border:2px solid var(--line);background:#fff;border-radius:999px;width:36px;height:36px;font-size:18px;font-weight:800;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0}
.modal h2{margin:0 0 4px;padding-right:44px}
.modal .lede{margin:0 0 8px}
.modal label{display:block;margin:10px 0}
.modal input,.modal select,.modal textarea{width:100%;padding:10px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff}
.modal textarea{min-height:70px}
.hidden{display:none!important}
.stat-row{display:flex;gap:12px;flex-wrap:wrap;margin:12px 0}
.stat-card{flex:1;min-width:140px;border:2px solid var(--line);border-radius:14px;background:#fff;padding:12px;text-align:center}
.stat-card b{font-size:22px;display:block}
.stat-card span{font-size:12px;font-weight:700;opacity:.75}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Purrsuit"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-wink-blep.png" alt="Bum Bum winking"><h1>Never let a lead go cold.</h1><p class="lede">Purrsuit is a tiny CRM for people who hate CRMs. Add the humans, move them down the pipeline, and every morning Purrsuit tells you exactly who to follow up with and what to say. Adding a contact uses one action. Everything else is free.</p>
<?php if (!$user): ?><section class="panel"><h2>Sign in to use Purrsuit</h2><a class="button" href="/account/?next=<?= urlencode('/tools/purrsuit/') ?>">Sign in or create an account</a></section><?php else: ?>
<?php $low = $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<section class="panel" id="followupPanel"><h2 style="margin-top:0">Follow up today</h2><div id="followupList"><p class="lede">Loading...</p></div></section>
<div class="stat-row"><div class="stat-card"><b id="statPipeline">$0</b><span>active pipeline</span></div><div class="stat-card"><b id="statWon">$0</b><span>won</span></div><div class="stat-card"><b id="statCount">0</b><span>contacts</span></div></div>
<div class="add-import-grid">
<section class="panel"><h2 style="margin-top:0">Add someone</h2><form id="addForm"><div class="nudge-grid"><label>Name<input name="name" type="text" maxlength="80" required placeholder="Jordan Lee"></label><label>Company<input name="company" type="text" maxlength="191" placeholder="Acme Inc"></label><label>Title<input name="title" type="text" maxlength="191" placeholder="Head of Growth"></label><label>Email<input name="email" type="email" maxlength="191" placeholder="jordan@acme.co"></label><label>Where you met<input name="source" type="text" maxlength="191" placeholder="Indie Hackers meetup"></label><label>Deal value<input name="deal_value" type="number" min="0" step="0.01" placeholder="2500"></label><label>Stage<select name="stage"><option value="new">New</option><option value="talking">Talking</option><option value="quoted">Quoted</option><option value="won">Won</option><option value="lost">Lost</option></select></label><label>Follow up on<input name="follow_up_date" type="date"></label><label class="span-all">Notes<textarea name="notes" maxlength="5000" placeholder="Anything worth remembering..."></textarea></label></div><button class="button" style="margin-top:10px">Add to Purrsuit</button><p id="addError" class="error"></p></form></section>
<section class="panel"><h2 style="margin-top:0">Or import a CSV</h2><p class="lede">Same fields as the form: <b>name</b> (required), company, title, email, where you met, deal value, stage (new, talking, quoted, won, lost), follow up (YYYY-MM-DD), notes. Names that already exist get <b>updated</b> (free, empty cells keep their current values); new names get added at one action each, with notes saved to their timeline. Up to 200 rows. <a href="#" id="tplLink">Download a template</a></p><form id="csvForm"><label>Choose file<input type="file" id="csvFile" accept=".csv,text/csv"></label><div class="btnrow"><button class="button secondary" style="margin-top:10px">Import CSV</button></div><p id="csvError" class="error"></p><p id="csvResult" class="lede"></p></form></section>
</div>
<section class="panel"><div class="yp-head"><h2>Your people</h2><button type="button" class="button secondary" id="csvDownload">Download CSV</button></div><div class="chips" id="stageChips"></div><div id="contactList"><p class="lede">Loading...</p></div><p id="listError" class="error"></p><p id="usage"></p></section>
<div id="upgrade-slot"></div>
<div class="modal-overlay hidden" id="modalOverlay"><div class="modal" role="dialog" aria-modal="true"><button class="modal-close" id="modalClose" aria-label="Close">×</button><div id="modalBody"></div></div></div>
<style>.nudge-grid{display:grid;gap:12px}@media(min-width:760px){.nudge-grid{grid-template-columns:1fr 1fr}}</style>
<script>
const TOOL_KEY='purrsuit';
const PERIOD=<?= json_encode($usage['period'] ?? '') ?>;
const STAGES=['new','talking','quoted','won','lost'];
let DATA={contacts:[],followUpDue:[],goneQuiet:[]};
let FILTER='all';
if(<?= $low ? 'true' : 'false' ?>){const seen='bb_m80_'+PERIOD;if(!localStorage.getItem(seen)){localStorage.setItem(seen,'1');bbTrack('usage_milestone_80',{tool:TOOL_KEY,used:<?= (int) ($usage['used'] ?? 0) ?>,limit:<?= (int) ($usage['limit'] ?? 0) ?>})}}
function upgradeCard(){return `<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) ($usage['limit'] ?? 75) ?> free actions this month. Helper gives you 1,500 actions for $12/month. Operator gives you 6,000 actions plus a custom tool built for you in 36 hours for $49/month.</p><p><a class="button" data-plan="helper" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" data-plan="operator" href="/checkout/?plan=operator">Get Operator, $49/mo</a></p><p><a href="/account/">See your usage</a></p></div>`}
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
function money(n){return new Intl.NumberFormat(undefined,{style:'currency',currency:'USD',maximumFractionDigits:0}).format(n||0)}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/purrsuit.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}
function stageBadge(s){return `<span class="stage ${esc(s)}">${esc(s)}</span>`}
function renderFollowups(){
  const el=document.querySelector('#followupList');
  const items=[...DATA.followUpDue.map(c=>({...c,quiet:false})),...DATA.goneQuiet.map(c=>({...c,quiet:true}))];
  if(!items.length){el.innerHTML='<p class="lede">All clear. Nobody needs a nudge today. Go touch grass.</p>';return}
  el.innerHTML=items.map(c=>`<div class="followup${c.quiet?' quiet':''}"><div class="top"><b>${esc(c.name)}</b>${stageBadge(c.stage)}${c.quiet?'<span class="stage">gone quiet</span>':''}</div>${c.deal_value?`<p class="meta">${money(c.deal_value)} potential</p>`:''}<div class="draft">${esc(c.draft)}</div><div class="btnrow"><button type="button" class="button secondary" data-copy-draft="${c.id}">Copy message</button><button type="button" class="button secondary" data-open="${c.id}">Log follow-up</button></div></div>`).join('');
  bindCardButtons(el);
}
function contactCard(c){
  const parts=[];
  if(c.company)parts.push(esc(c.company));
  if(c.title)parts.push(esc(c.title));
  if(c.deal_value)parts.push(money(c.deal_value));
  if(c.contact_info)parts.push(esc(c.contact_info));
  if(c.source)parts.push('met '+esc(c.source));
  if(c.follow_up_date)parts.push('follow up '+esc(c.follow_up_date));
  return `<div class="contact" data-id="${c.id}"><div class="top"><b>${esc(c.name)}</b>${stageBadge(c.stage)}<span style="flex:1"></span><button type="button" class="button secondary" data-open="${c.id}">Open</button><button type="button" class="button secondary" data-del-card="${c.id}">Delete</button></div>${parts.length?`<p class="meta">${parts.join(' · ')}</p>`:''}</div>`;
}
function renderContacts(){
  const el=document.querySelector('#contactList');
  const list=DATA.contacts.filter(c=>FILTER==='all'||c.stage===FILTER);
  document.querySelector('#stageChips').innerHTML=['all',...STAGES].map(s=>`<button type="button" class="chip${FILTER===s?' on':''}" data-filter="${s}">${s==='all'?'All':s} (${s==='all'?DATA.contacts.length:(DATA.counts[s]||0)})</button>`).join('');
  document.querySelectorAll('[data-filter]').forEach(b=>b.addEventListener('click',()=>{FILTER=b.getAttribute('data-filter');renderContacts();}));
  if(!list.length){el.innerHTML='<p class="lede">Nobody here yet. Add your first person above.</p>';return}
  el.innerHTML=list.map(contactCard).join('');
  bindCardButtons(el);
}
function bindCardButtons(root){
  root.querySelectorAll('[data-open]').forEach(b=>b.addEventListener('click',()=>openDetail(parseInt(b.getAttribute('data-open'),10))));
  root.querySelectorAll('[data-del-card]').forEach(b=>b.addEventListener('click',async()=>{
    if(b.dataset.armed!=='1'){
      b.dataset.armed='1';b.dataset.orig=b.textContent;b.textContent='Tap again to delete';
      setTimeout(()=>{if(b.isConnected&&b.dataset.armed==='1'){b.dataset.armed='';b.textContent=b.dataset.orig}},6000);
      return;
    }
    b.disabled=true;
    const id=parseInt(b.getAttribute('data-del-card'),10);
    const{response,data}=await apiCall({action:'delete',id});
    b.disabled=false;
    const errEl=document.querySelector('#listError');
    if(!response.ok){b.dataset.armed='';b.textContent=b.dataset.orig||'Delete';if(errEl)errEl.textContent=data.error||'Delete failed.';return}
    if(errEl)errEl.textContent='';
    bbTrack('purrsuit_deleted',{tool:TOOL_KEY});
    await refresh();
    document.querySelector('#csvResult').textContent='';
  }));
  root.querySelectorAll('[data-copy-draft]').forEach(b=>b.addEventListener('click',async()=>{
    const c=DATA.contacts.find(x=>x.id===parseInt(b.getAttribute('data-copy-draft'),10))||DATA.followUpDue.find(x=>x.id===parseInt(b.getAttribute('data-copy-draft'),10))||DATA.goneQuiet.find(x=>x.id===parseInt(b.getAttribute('data-copy-draft'),10));
    if(!c||!c.draft)return;
    try{await navigator.clipboard.writeText(c.draft);b.textContent='Copied!'}catch(_){b.textContent='Copy failed'}
    setTimeout(()=>{b.textContent='Copy message'},2000);
    bbTrack('purrsuit_draft_copied',{tool:TOOL_KEY});
  }));
}
async function openDetail(id){
  const overlay=document.querySelector('#modalOverlay');
  const body=document.querySelector('#modalBody');
  overlay.classList.remove('hidden');
  document.body.style.overflow='hidden';
  body.innerHTML='<p class="lede">Loading...</p>';
  const{response,data}=await apiCall({action:'get',id});
  if(!response.ok){body.innerHTML=`<p class="error">${esc(data.error||'Could not load.')}</p>`;return}
  const c=data.contact;
  body.innerHTML=`
    <h2>${esc(c.name)}</h2>
    <p class="lede">${c.company?esc(c.company)+' · ':''}${c.title?esc(c.title)+' · ':''}${c.contact_info?esc(c.contact_info)+' · ':''}${stageBadge(c.stage)}</p>
    <label>Stage<select data-f="stage">${STAGES.map(s=>`<option value="${s}"${c.stage===s?' selected':''}>${s}</option>`).join('')}</select></label>
    <label>Title<input data-f="title" type="text" maxlength="191" value="${esc(c.title||'')}" placeholder="Head of Growth"></label>
    <label>Deal value<input data-f="deal_value" type="number" min="0" step="0.01" value="${c.deal_value??''}" placeholder="0"></label>
    <label>Follow up on<input data-f="follow_up_date" type="date" value="${esc(c.follow_up_date||'')}"></label>
    <div class="btnrow"><button type="button" class="button secondary" data-save="${id}">Save changes</button><button type="button" class="button secondary" data-del="${id}">Delete</button></div>
    <h3 style="margin:14px 0 4px">Log an interaction</h3>
    <label>What happened<textarea data-f="log_body" placeholder="Had a great call, they want a proposal by Friday..."></textarea></label>
    <label>Next follow-up<input data-f="log_next" type="date" value="${esc(c.follow_up_date||'')}"><span class="meta">Leave empty to clear the reminder.</span></label>
    <div class="btnrow"><button type="button" class="button secondary" data-log="${id}">Log it</button></div>
    <h3 style="margin:14px 0 4px">Timeline</h3>
    <ul class="timeline">${data.notes.length?data.notes.map(n=>`<li>${esc(n.body)}<br><span class="when">${esc(n.created_at)}</span></li>`).join(''):'<li>No interactions logged yet.</li>'}</ul>
    <p class="error" data-err></p>`;
  body.querySelector('[data-save]').addEventListener('click',async()=>{
    const payload={action:'update',id,stage:body.querySelector('[data-f=stage]').value,title:body.querySelector('[data-f=title]').value,deal_value:body.querySelector('[data-f=deal_value]').value,follow_up_date:body.querySelector('[data-f=follow_up_date]').value};
    const r=await apiCall(payload);
    if(!r.response.ok){body.querySelector('[data-err]').textContent=r.data.error||'Save failed.';return}
    bbTrack('purrsuit_updated',{tool:TOOL_KEY});await refresh();openDetail(id);
  });
  body.querySelector('[data-del]').addEventListener('click',async()=>{
    if(!confirm('Delete '+c.name+' and their whole timeline?'))return;
    const r=await apiCall({action:'delete',id});
    if(!r.response.ok){body.querySelector('[data-err]').textContent=r.data.error||'Delete failed.';return}
    closeModal();await refresh();
    document.querySelector('#csvResult').textContent='';
  });
  body.querySelector('[data-log]').addEventListener('click',async()=>{
    const noteBody=body.querySelector('[data-f=log_body]').value.trim();
    if(!noteBody){body.querySelector('[data-err]').textContent='Write a note about the interaction first.';return}
    const payload={action:'log',id,body:noteBody};
    const nextVal=body.querySelector('[data-f=log_next]').value;
    if(nextVal!==(c.follow_up_date||''))payload.follow_up_date=nextVal;
    const r=await apiCall(payload);
    if(!r.response.ok){body.querySelector('[data-err]').textContent=r.data.error||'Log failed.';return}
    bbTrack('purrsuit_logged',{tool:TOOL_KEY});await refresh();openDetail(id);
  });
}
function closeModal(){
  document.querySelector('#modalOverlay').classList.add('hidden');
  document.body.style.overflow='';
}
document.querySelector('#modalClose').addEventListener('click',closeModal);
document.querySelector('#modalOverlay').addEventListener('click',e=>{
  if(e.target.id==='modalOverlay')closeModal();
});
document.addEventListener('keydown',e=>{
  if(e.key==='Escape'&&!document.querySelector('#modalOverlay').classList.contains('hidden'))closeModal();
});
async function refresh(){
  const{response,data}=await apiCall({action:'list'});
  if(!response.ok){document.querySelector('#contactList').innerHTML=`<p class="error">${esc(data.error||'Could not load.')}</p>`;return}
  DATA=data;
  const pipe=data.contacts.filter(c=>['new','talking','quoted'].includes(c.stage)).reduce((s,c)=>s+(c.deal_value||0),0);
  const won=data.contacts.filter(c=>c.stage==='won').reduce((s,c)=>s+(c.deal_value||0),0);
  document.querySelector('#statPipeline').textContent=money(pipe);
  document.querySelector('#statWon').textContent=money(won);
  document.querySelector('#statCount').textContent=data.contacts.length;
  renderFollowups();renderContacts();
}
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
  document.querySelector('#usage').textContent=data.usage.remaining+' actions remaining this month.';
  addForm.reset();await refresh();
});
document.querySelector('#csvDownload').addEventListener('click',async e=>{
  e.preventDefault();
  const btn=e.currentTarget;btn.disabled=true;
  const{response,data}=await apiCall({action:'export'});
  btn.disabled=false;
  if(!response.ok||!data.csv){document.querySelector('#csvError').textContent=data.error||'Download failed.';return}
  const blob=new Blob([data.csv],{type:'text/csv'});
  const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download=data.filename||'purrsuit-export.csv';a.click();
  setTimeout(()=>URL.revokeObjectURL(a.href),4000);
  bbTrack('purrsuit_exported',{tool:TOOL_KEY});
});
const csvForm=document.querySelector('#csvForm');
document.querySelector('#tplLink').addEventListener('click',e=>{
  e.preventDefault();
  const blob=new Blob(['name,company,title,email,where you met,deal value,stage,follow up,notes\nJordan Lee,Acme Inc,Head of Growth,jordan@acme.co,Indie Hackers meetup,2500,talking,2026-10-01,"Met at the mixer, wants a proposal by Friday"\nPriya Shah,Buildly,Product Growth Lead,priya@buildly.co,Twitter DM,800,new,,\n'],{type:'text/csv'});
  const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='purrsuit-template.csv';a.click();
  setTimeout(()=>URL.revokeObjectURL(a.href),4000);
});
csvForm.addEventListener('submit',async e=>{
  e.preventDefault();
  const file=document.querySelector('#csvFile').files[0];
  const errEl=document.querySelector('#csvError'),resEl=document.querySelector('#csvResult');
  errEl.textContent='';resEl.textContent='';
  if(!file){errEl.textContent='Pick a CSV file first.';return}
  const text=await file.text();
  const btn=csvForm.querySelector('button');btn.disabled=true;btn.textContent='Importing...';
  const{response,data}=await apiCall({action:'import',csv:text,idempotencyKey:crypto.randomUUID()});
  btn.disabled=false;btn.textContent='Import CSV';
  if(response.status===402){
    bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});
    errEl.textContent=data.error||'Out of actions.';
    document.querySelector('#usage').textContent=data.usage.remaining+' actions remaining this month.';
    document.querySelector('#upgrade-slot').innerHTML=upgradeCard();
    document.querySelectorAll('#upgrade-slot [data-plan]').forEach(a=>a.addEventListener('click',()=>bbTrack('upgrade_clicked',{plan:a.dataset.plan,context:'limit',tool:TOOL_KEY})));
    await refresh();return;
  }
  if(!response.ok){errEl.textContent=data.error||'Import failed.';return}
  bbTrack('action_completed',{tool:TOOL_KEY,used:data.usage.used,limit:data.usage.limit,remaining:data.usage.remaining});
  bbTrack('purrsuit_imported',{tool:TOOL_KEY,imported:data.imported});
  document.querySelector('#usage').textContent=data.usage.remaining+' actions remaining this month.';
  let msg=`Added ${data.imported} new, updated ${data.updated}.`;
  if(data.skipped)msg+=` Skipped ${data.skipped}.`;
  if(data.errors&&data.errors.length)msg+='\n'+data.errors.join('\n');
  resEl.textContent=msg;
  csvForm.reset();await refresh();
});
refresh();
</script><?php endif; ?></main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
