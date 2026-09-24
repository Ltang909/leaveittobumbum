<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $user = currentUser(); $usage = $user ? usageFor(billingUser($user)) : null; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Corporate Bum Bum | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css?v=5"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
.chips{display:flex;gap:8px;flex-wrap:wrap;margin:12px 0}
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.chip{border:2px solid var(--line);border-radius:999px;padding:6px 14px;font-weight:800;font-size:14px;background:#fff;cursor:pointer}
.chip.on{background:var(--ink);color:#fff;border-color:var(--ink)}
.contact{border:2px solid var(--line);border-radius:14px;background:#fff;padding:14px;margin-bottom:10px}
.contact .top{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.contact .top b{font-size:17px}
.card-status{margin-bottom:10px}
.card-title{display:block;font-size:17px;margin:0 0 8px}
.card-actions{display:flex;gap:8px;margin-top:12px}
.card-actions .button{flex:1;margin-top:0}
.stage{font-size:12px;font-weight:800;border:2px solid var(--ink);border-radius:999px;padding:2px 10px}
.stage.wishlist{background:#d8d3c8}.stage.applied{background:#8eb5ff}.stage.screening{background:#c3a6f2}.stage.interview{background:#ffd84d}.stage.final{background:#f7a9ca}.stage.offer{background:#83d6b2}.stage.accepted{background:#17150f;color:#fff}.stage.rejected{background:#e5484d;color:#fff}.stage.withdrawn{background:#8a8578;color:#fff}
.meta{font-size:13px;opacity:.8;margin:6px 0 0}
a[x-apple-data-detectors]{color:inherit!important;text-decoration:none!important}
.detail{margin-top:12px;border-top:2px dashed var(--line);padding-top:12px}
.detail label{display:block;margin:8px 0}
.detail input,.detail select,.detail textarea{width:100%;padding:10px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff}
.detail textarea{min-height:70px}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:8px}
.timeline{margin:10px 0 0;padding:0;list-style:none}
.timeline li{border-left:3px solid var(--line);padding:4px 0 4px 12px;margin:0 0 8px;font-size:14px}
.timeline .when{font-size:12px;opacity:.65}
.timeline .note-row{display:flex;gap:10px;align-items:flex-start;justify-content:space-between}
.timeline .note-row>span{flex:1;min-width:0}
.note-del{flex:none;border:2px solid var(--line);background:#fff;border-radius:8px;padding:4px 10px;font-size:12px;font-weight:800;cursor:pointer;font-family:inherit}
.note-del:hover{border-color:#e5484d;color:#e5484d}
.followup{border:2px solid var(--ink);border-radius:14px;background:#fff;padding:14px;margin-bottom:10px;box-shadow:4px 4px 0 var(--yellow)}
.followup .draft{background:var(--cream);border:2px solid var(--line);border-radius:10px;padding:10px;margin:8px 0;font-size:14px;white-space:pre-wrap}
.followup .top{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
#contactList{max-height:520px;overflow-y:auto;padding-right:6px}
#addForm input,#addForm select{width:100%;padding:10px;border:2px solid var(--line);border-radius:10px;font:inherit;background-color:#fff}
#addForm textarea{width:100%;padding:10px;border:2px solid var(--line);border-radius:10px;font:inherit;background-color:#fff;min-height:70px}
.add-import-grid{display:grid;gap:28px;margin-top:28px}
.add-import-grid .panel{margin-top:0;margin-bottom:0}
#addForm input::placeholder,#addForm textarea::placeholder,.modal input::placeholder,.modal textarea::placeholder{color:#c9c2b2;opacity:1}
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
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Corporate Bum Bum"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-glasses.png" alt="Bum Bum looking professional"><h1>Your job hunt, in a suit.</h1><p class="lede">Corporate Bum Bum is a job application tracker for people who hate spreadsheets. Add the roles, move them down the pipeline, and every morning it tells you exactly who to follow up with and what to say. Adding an application uses one action. Everything else is free.</p>
<?php if (!$user): ?><section class="panel"><h2>Sign in to use Corporate Bum Bum</h2><a class="button" href="/account/?next=<?= urlencode('/tools/corporate-bum-bum/') ?>">Sign in or create an account</a></section><?php else: ?>
<?php $low = $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<section class="panel" id="followupPanel"><h2 style="margin-top:0">Follow up today</h2><div id="followupList"><p class="lede">Loading...</p></div></section>
<div class="stat-row"><div class="stat-card"><b id="statActive">0</b><span>active applications</span></div><div class="stat-card"><b id="statInterviews">0</b><span>in interviews</span></div><div class="stat-card"><b id="statOffers">0</b><span>offers</span></div></div>
<section class="panel"><div class="yp-head"><h2>Your applications</h2><button type="button" class="button secondary" id="csvDownload">Download CSV</button></div><div class="chips" id="stageChips"></div><div id="contactList"><p class="lede">Loading...</p></div><p id="listError" class="error"></p><p id="usage"></p></section>
<div class="add-import-grid">
<section class="panel"><h2 style="margin-top:0">Add an application</h2><form id="addForm"><div class="nudge-grid"><label>Company<input name="company" type="text" maxlength="191" required placeholder="Acme Inc"></label><label>Role<input name="role" type="text" maxlength="191" required placeholder="Senior Growth Manager"></label><label>Contact name<input name="contact_name" type="text" maxlength="191" placeholder="Maya Chen"></label><label>Contact email<input name="contact_email" type="email" maxlength="191" placeholder="maya@acme.co"></label><label>Job posting URL<input name="job_url" type="url" maxlength="500" placeholder="https://acme.co/jobs/123"></label><label>Location<input name="location" type="text" maxlength="191" placeholder="Remote (US)"></label><label>Salary min<input name="salary_min" type="number" min="0" step="1" placeholder="120000"></label><label>Salary max<input name="salary_max" type="number" min="0" step="1" placeholder="140000"></label><label>Currency<select name="currency"><option value="CAD" selected>CAD</option><option value="USD">USD</option><option value="EUR">EUR</option><option value="GBP">GBP</option></select></label><label>Where you found it<input name="source" type="text" maxlength="191" placeholder="nanoglobals"></label><label>Date applied<input name="date_applied" type="date"></label><label>Stage<select name="stage"><option value="wishlist">Wishlist</option><option value="applied" selected>Applied</option><option value="screening">Screening</option><option value="interview">Interview</option><option value="final">Final round</option><option value="offer">Offer</option><option value="accepted">Accepted</option><option value="rejected">Rejected</option><option value="withdrawn">Withdrawn</option></select></label><label>Follow up on<input name="follow_up_date" type="date"></label><label class="span-all">Notes<textarea name="notes" maxlength="5000" placeholder="Anything worth remembering..."></textarea></label></div><button class="button" style="margin-top:10px">Add to Corporate Bum Bum</button><p id="addError" class="error"></p></form></section>
<section class="panel"><h2 style="margin-top:0">Or import a CSV</h2><p class="lede">Same fields as the form: <b>company</b> (required), role, contact name, contact email, job url, location, salary min, salary max, currency (CAD, USD, EUR, GBP — defaults to CAD), where found, date applied (YYYY-MM-DD), stage (wishlist, applied, screening, interview, final, offer, accepted, rejected, withdrawn), follow up (YYYY-MM-DD), notes. Rows matching an existing <b>company + role</b> get <b>updated</b> (free, empty cells keep their current values); everything else gets added at one action each. The notes column fills each record's notes field. Up to 200 rows. <a href="#" id="tplLink">Download a template</a></p><form id="csvForm"><label>Choose file<input type="file" id="csvFile" accept=".csv,text/csv"></label><div class="btnrow"><button class="button secondary" style="margin-top:10px">Import CSV</button></div><p id="csvError" class="error"></p><p id="csvResult" class="lede"></p></form></section>
</div>
<div id="upgrade-slot"></div>
<div class="modal-overlay hidden" id="modalOverlay"><div class="modal" role="dialog" aria-modal="true"><button class="modal-close" id="modalClose" aria-label="Close">×</button><div id="modalBody"></div></div></div>
<style>.nudge-grid{display:grid;gap:12px}@media(min-width:760px){.nudge-grid{grid-template-columns:1fr 1fr}}</style>
<script>
const TOOL_KEY='corporate-bum-bum';
const PERIOD=<?= json_encode($usage['period'] ?? '') ?>;
const STAGES=['wishlist','applied','screening','interview','final','offer','accepted','rejected','withdrawn'];
const STAGE_LABELS={wishlist:'Wishlist',applied:'Applied',screening:'Screening',interview:'Interview',final:'Final round',offer:'Offer',accepted:'Accepted',rejected:'Rejected',withdrawn:'Withdrawn'};
const ACTIVE_STAGES=['wishlist','applied','screening','interview','final','offer'];
let DATA={contacts:[],followUpDue:[],goneQuiet:[]};
let FILTER='all';
if(<?= $low ? 'true' : 'false' ?>){const seen='bb_m80_'+PERIOD;if(!localStorage.getItem(seen)){localStorage.setItem(seen,'1');bbTrack('usage_milestone_80',{tool:TOOL_KEY,used:<?= (int) ($usage['used'] ?? 0) ?>,limit:<?= (int) ($usage['limit'] ?? 0) ?>})}}
function upgradeCard(){return `<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) ($usage['limit'] ?? 75) ?> free actions this month. Helper gives you 1,500 actions for $12/month. Operator gives you 6,000 actions plus a custom tool built for you in 36 hours for $49/month.</p><p><a class="button" data-plan="helper" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" data-plan="operator" href="/checkout/?plan=operator">Get Operator, $49/mo</a></p><p><a href="/account/">See your usage</a></p></div>`}
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
const CUR_SYMBOLS={CAD:'CA$',USD:'US$',EUR:'\u20AC',GBP:'\u00A3'};
const CURRENCIES=['CAD','USD','EUR','GBP'];
function money(n,cur){const sym=CUR_SYMBOLS[cur]||CUR_SYMBOLS.CAD;return sym+Math.round(n||0).toLocaleString('en-US')}
function salaryRange(c){
  const lo=c.salary_min,hi=c.salary_max,cur=c.currency||'CAD';
  if(lo==null&&hi==null)return '';
  if(lo!=null&&hi!=null)return money(lo,cur)+' - '+money(hi,cur);
  return money(lo!=null?lo:hi,cur);
}
function stageLabel(s){return STAGE_LABELS[s]||s}
function stageBadge(s){return `<span class="stage ${esc(s)}">${esc(stageLabel(s))}</span>`}
function appTitle(c){return c.role?`${c.role} at ${c.company}`:c.company}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/corporate-bum-bum.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}
function renderFollowups(){
  const el=document.querySelector('#followupList');
  const items=[...DATA.followUpDue.map(c=>({...c,quiet:false})),...DATA.goneQuiet.map(c=>({...c,quiet:true}))];
  if(!items.length){el.innerHTML='<p class="lede">All clear. Nobody needs a nudge today. Go touch grass.</p>';return}
  el.innerHTML=items.map(c=>`<div class="followup${c.quiet?' quiet':''}"><div class="top"><b>${esc(appTitle(c))}</b>${stageBadge(c.stage)}${c.quiet?'<span class="stage">gone quiet</span>':''}</div>${salaryRange(c)?`<p class="meta">${salaryRange(c)}</p>`:''}<div class="draft">${esc(c.draft)}</div><div class="btnrow"><button type="button" class="button secondary" data-copy-draft="${c.id}">Copy message</button><button type="button" class="button secondary" data-open="${c.id}">Log follow-up</button><button type="button" class="button secondary" data-clear-followup="${c.id}">Clear</button></div></div>`).join('');
  bindCardButtons(el);
}
function contactCard(c){
  const parts=[];
  if(c.location)parts.push(esc(c.location));
  const sr=salaryRange(c);if(sr)parts.push(sr);
  if(c.contact_name)parts.push(esc(c.contact_name));
  if(c.source)parts.push('found on '+esc(c.source));
  if(c.date_applied)parts.push('applied '+esc(c.date_applied));
  if(c.follow_up_date)parts.push('follow up '+esc(c.follow_up_date));
  return `<div class="contact" data-id="${c.id}"><div class="card-status">${stageBadge(c.stage)}</div><b class="card-title">${esc(appTitle(c))}</b>${parts.length?`<p class="meta">${parts.join(' · ')}</p>`:''}<div class="card-actions"><button type="button" class="button secondary" data-open="${c.id}">Open</button><button type="button" class="button secondary" data-del-card="${c.id}">Delete</button></div></div>`;
}
function renderContacts(){
  const el=document.querySelector('#contactList');
  const list=DATA.contacts.filter(c=>FILTER==='all'||c.stage===FILTER);
  document.querySelector('#stageChips').innerHTML=['all',...STAGES].map(s=>`<button type="button" class="chip${FILTER===s?' on':''}" data-filter="${s}">${s==='all'?'All':stageLabel(s)} (${s==='all'?DATA.contacts.length:(DATA.counts[s]||0)})</button>`).join('');
  document.querySelectorAll('[data-filter]').forEach(b=>b.addEventListener('click',()=>{FILTER=b.getAttribute('data-filter');renderContacts();}));
  if(!list.length){el.innerHTML='<p class="lede">Nothing here yet. Add your first application above.</p>';return}
  el.innerHTML=list.map(contactCard).join('');
  bindCardButtons(el);
}
function bindCardButtons(root){
  root.querySelectorAll('[data-open]').forEach(b=>b.addEventListener('click',()=>openDetail(parseInt(b.getAttribute('data-open'),10))));
  root.querySelectorAll('[data-clear-followup]').forEach(b=>b.addEventListener('click',async()=>{
    b.disabled=true;
    const id=parseInt(b.getAttribute('data-clear-followup'),10);
    const{response,data}=await apiCall({action:'update',id,follow_up_date:''});
    b.disabled=false;
    if(!response.ok){document.querySelector('#listError').textContent=data.error||'Clear failed.';return}
    await refresh();
  }));
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
    bbTrack('jobtrack_deleted',{tool:TOOL_KEY});
    await refresh();
    document.querySelector('#csvResult').textContent='';
  }));
  root.querySelectorAll('[data-copy-draft]').forEach(b=>b.addEventListener('click',async()=>{
    const c=DATA.contacts.find(x=>x.id===parseInt(b.getAttribute('data-copy-draft'),10))||DATA.followUpDue.find(x=>x.id===parseInt(b.getAttribute('data-copy-draft'),10))||DATA.goneQuiet.find(x=>x.id===parseInt(b.getAttribute('data-copy-draft'),10));
    if(!c||!c.draft)return;
    try{await navigator.clipboard.writeText(c.draft);b.textContent='Copied!'}catch(_){b.textContent='Copy failed'}
    setTimeout(()=>{b.textContent='Copy message'},2000);
    bbTrack('jobtrack_draft_copied',{tool:TOOL_KEY});
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
  const sr=salaryRange(c);
  body.innerHTML=`
    <h2>${esc(appTitle(c))}</h2>
    <p class="lede">${c.location?esc(c.location)+' · ':''}${sr?sr+' · ':''}${c.contact_name?esc(c.contact_name)+' · ':''}${stageBadge(c.stage)}</p>
    ${c.job_url?`<p><a href="${esc(c.job_url)}" target="_blank" rel="noopener">View job posting</a></p>`:''}
    <label>Stage<select data-f="stage">${STAGES.map(s=>`<option value="${s}"${c.stage===s?' selected':''}>${stageLabel(s)}</option>`).join('')}</select></label>
    <label>Company<input data-f="company" type="text" maxlength="191" value="${esc(c.company||'')}" placeholder="Acme Inc"></label>
    <label>Role<input data-f="role" type="text" maxlength="191" value="${esc(c.role||'')}" placeholder="Senior Growth Manager"></label>
    <label>Contact name<input data-f="contact_name" type="text" maxlength="191" value="${esc(c.contact_name||'')}" placeholder="Maya Chen"></label>
    <label>Contact email<input data-f="contact_email" type="email" maxlength="191" value="${esc(c.contact_email||'')}" placeholder="maya@acme.co"></label>
    <label>Job posting URL<input data-f="job_url" type="url" maxlength="500" value="${esc(c.job_url||'')}"></label>
    <label>Location<input data-f="location" type="text" maxlength="191" value="${esc(c.location||'')}" placeholder="Remote (US)"></label>
    <label>Where found<input data-f="source" type="text" maxlength="191" value="${esc(c.source||'')}" placeholder="nanoglobals"></label>
    <label>Salary min<input data-f="salary_min" type="number" min="0" step="1" value="${c.salary_min??''}"></label>
    <label>Salary max<input data-f="salary_max" type="number" min="0" step="1" value="${c.salary_max??''}"></label>
    <label>Currency<select data-f="currency">${CURRENCIES.map(cur=>`<option value="${cur}"${(c.currency||'CAD')===cur?' selected':''}>${cur}</option>`).join('')}</select></label>
    <label>Date applied<input data-f="date_applied" type="date" value="${esc(c.date_applied||'')}"></label>
    <label>Follow up on<input data-f="follow_up_date" type="date" value="${esc(c.follow_up_date||'')}"></label>
    <label>Notes<textarea data-f="notes" maxlength="5000" placeholder="Anything worth remembering...">${esc(c.notes||'')}</textarea></label>
    <div class="btnrow"><button type="button" class="button secondary" data-save="${id}">Save changes</button><button type="button" class="button secondary" data-del="${id}">Delete</button></div>
    <h3 style="margin:14px 0 4px">Log an interaction</h3>
    <label>What happened<textarea data-f="log_body" placeholder="Had the screening call, they want to move me to the hiring manager round..."></textarea></label>
    <label>Next follow-up<input data-f="log_next" type="date" value="${esc(c.follow_up_date||'')}"><span class="meta">Leave empty to clear the reminder.</span></label>
    <div class="btnrow"><button type="button" class="button secondary" data-log="${id}">Log it</button></div>
    <h3 style="margin:14px 0 4px">Timeline</h3>
    <ul class="timeline">${data.notes.length?data.notes.map(n=>`<li><div class="note-row"><span>${esc(n.body)}</span><button type="button" class="note-del" data-del-note="${n.id}">Delete</button></div><span class="when">${esc(n.created_at)}</span></li>`).join(''):'<li>No interactions logged yet.</li>'}</ul>
    <p class="error" data-err></p>`;
  body.querySelector('[data-save]').addEventListener('click',async()=>{
    const payload={action:'update',id,stage:body.querySelector('[data-f=stage]').value,company:body.querySelector('[data-f=company]').value,role:body.querySelector('[data-f=role]').value,contact_name:body.querySelector('[data-f=contact_name]').value,contact_email:body.querySelector('[data-f=contact_email]').value,job_url:body.querySelector('[data-f=job_url]').value,location:body.querySelector('[data-f=location]').value,source:body.querySelector('[data-f=source]').value,salary_min:body.querySelector('[data-f=salary_min]').value,salary_max:body.querySelector('[data-f=salary_max]').value,currency:body.querySelector('[data-f=currency]').value,date_applied:body.querySelector('[data-f=date_applied]').value,follow_up_date:body.querySelector('[data-f=follow_up_date]').value,notes:body.querySelector('[data-f=notes]').value};
    const r=await apiCall(payload);
    if(!r.response.ok){body.querySelector('[data-err]').textContent=r.data.error||'Save failed.';return}
    bbTrack('jobtrack_updated',{tool:TOOL_KEY});await refresh();openDetail(id);
  });
  body.querySelector('[data-del]').addEventListener('click',async()=>{
    if(!confirm('Delete this application and its whole timeline?'))return;
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
    bbTrack('jobtrack_logged',{tool:TOOL_KEY});await refresh();openDetail(id);
  });
  body.querySelectorAll('[data-del-note]').forEach(b=>b.addEventListener('click',async()=>{
    if(!confirm('Delete this timeline entry?'))return;
    const r=await apiCall({action:'delete_note',note_id:parseInt(b.getAttribute('data-del-note'),10)});
    if(!r.response.ok){body.querySelector('[data-err]').textContent=r.data.error||'Delete failed.';return}
    await refresh();openDetail(id);
  }));
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
  document.querySelector('#statActive').textContent=data.contacts.filter(c=>ACTIVE_STAGES.includes(c.stage)).length;
  document.querySelector('#statInterviews').textContent=data.contacts.filter(c=>['screening','interview','final'].includes(c.stage)).length;
  document.querySelector('#statOffers').textContent=data.contacts.filter(c=>c.stage==='offer'||c.stage==='accepted').length;
  renderFollowups();renderContacts();
}const addForm=document.querySelector('#addForm');let attempt=crypto.randomUUID();
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
  const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download=data.filename||'corporate-bum-bum-export.csv';a.click();
  setTimeout(()=>URL.revokeObjectURL(a.href),4000);
  bbTrack('jobtrack_exported',{tool:TOOL_KEY});
});
const csvForm=document.querySelector('#csvForm');
document.querySelector('#tplLink').addEventListener('click',e=>{
  e.preventDefault();
  const blob=new Blob(['company,role,contact name,contact email,job url,location,salary min,salary max,currency,where found,date applied,stage,follow up,notes\nAcme Inc,Senior Growth Manager,Maya Chen,maya@acme.co,https://acme.co/jobs/123,Remote (US),120000,140000,CAD,nanoglobals,2026-09-23,applied,2026-09-30,"Applied via the careers page, recruiter reached out on LinkedIn"\nBuildly,Product Growth Lead,,,,Remote,100000,120000,CAD,LinkedIn,,wishlist,,\n'],{type:'text/csv'});
  const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='corporate-bum-bum-template.csv';a.click();
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
  bbTrack('jobtrack_imported',{tool:TOOL_KEY,imported:data.imported});
  document.querySelector('#usage').textContent=data.usage.remaining+' actions remaining this month.';
  let msg=`Added ${data.imported} new, updated ${data.updated}.`;
  if(data.skipped)msg+=` Skipped ${data.skipped}.`;
  if(data.errors&&data.errors.length)msg+='\n'+data.errors.join('\n');
  resEl.textContent=msg;
  csvForm.reset();await refresh();
});
refresh();
</script><?php endif; ?></main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
