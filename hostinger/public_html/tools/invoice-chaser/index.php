<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Invoice Reminder Tool | Chase Late Payments Without the Awkward Emails | Leave It to Bum Bum</title>
<meta name="description" content="Get paid without the awkward. Bum Bum writes the chase email, sends it as your billing assistant, and learns when each client needs a nudge. Free to try.">
<meta property="og:title" content="Invoice Reminder Tool | Chase Late Payments Without the Awkward Emails | Leave It to Bum Bum">
<meta property="og:description" content="Get paid without the awkward. Bum Bum writes the chase email, sends it as your billing assistant, and learns when each client needs a nudge. Free to try.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/invoice-chaser/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/invoice-chaser.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Invoice Reminder Tool | Chase Late Payments Without the Awkward Emails | Leave It to Bum Bum">
<meta name="twitter:description" content="Get paid without the awkward. Bum Bum writes the chase email, sends it as your billing assistant, and learns when each client needs a nudge. Free to try.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/invoice-chaser.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/tools/invoice-chaser/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Invoice Reminder",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Get paid without the awkward. Bum Bum writes the chase email, sends it as your billing assistant, and learns when each client needs a nudge. Free to try.",
  "url": "https://leaveittobumbum.com/tools/invoice-chaser/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Do I need an account?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Start as a guest. It is free, with no limits. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "Does it actually send the emails?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. Bum Bum writes the chase email in the right tone and sends it as your billing assistant in one tap."
      }
    },
    {
      "@type": "Question",
      "name": "Will it annoy my clients?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "It learns how each client actually pays, so it knows when to nudge and when to sit tight. The tone stays professional, never desperate."
      }
    },
    {
      "@type": "Question",
      "name": "What if a client always pays late?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "That is exactly who this is for. The chaser adapts its timing to each client's real payment pattern."
      }
    },
    {
      "@type": "Question",
      "name": "Where can I get invoice reminder email templates?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Right on this page: the templates section at https://leaveittobumbum.com/tools/invoice-chaser/#templates has the first send, the polite reminder, the firm reminder, and the final notice, ready to copy and adjust. The blog's invoice reminder templates post walks through the same stages with more explanation."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Invoice Reminder | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Invoice Reminder","item":"https://leaveittobumbum.com/tools/invoice-chaser/"}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
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
.invoice.flash{outline:3px solid #d8ff62;outline-offset:2px}
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

.chaching{text-align:center;padding:32px 20px}
.chaching img{width:110px;height:auto}
.chaching-amount{font-family:Fraunces,Georgia,serif;font-weight:700;font-size:clamp(2.5rem,9vw,4rem);margin:6px 0;animation:pop .45s cubic-bezier(.2,1.6,.4,1)}
@keyframes pop{from{transform:scale(.6);opacity:0}to{transform:scale(1);opacity:1}}
table.ex-table{border-collapse:collapse;width:100%;margin:12px 0;font-size:15px}
table.ex-table th,table.ex-table td{border:1px solid #e2d7bf;padding:8px 10px;text-align:left;background:#fff}
table.ex-table th{background:#fffdf8}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Invoice Reminder"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/invoice-chaser.png" alt="Invoice Reminder"><div><h1>Invoice Reminder</h1><p class="lede bb-desc">Get paid without the awkward. Log who owes you what and Bum Bum writes the chase email.</p></div></div>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to chase invoices</h2><a class="button" href="/account/?next=<?= urlencode('/tools/invoice-chaser/') ?>">Sign in or create an account</a></section>
<?php else: ?>
<?php require dirname(__DIR__, 2) . '/includes/weekly-note.php'; ?>
<div class="stat-row">
  <div class="stat-card"><b id="statOutstanding">–</b><span>outstanding</span><small id="statOutstandingBy"></small></div>
  <div class="stat-card"><b id="statOverdue">–</b><span>overdue</span><small id="statOverdueBy"></small></div>
  <div class="stat-card"><b id="statRecovered">–</b><span>recovered this month</span><small id="statRecoveredBy"></small></div>
</div>
<section class="panel"><div class="yp-head" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap"><h2 style="margin:0">Your chase list</h2><button type="button" class="button" id="remindBtn">Email me the chase list</button></div><p class="lede" style="margin-top:8px">One email with everything overdue and upcoming, sent to <?= htmlspecialchars($user['email'] ?? '') ?>. Uses one action; repeat sends on the same day are free.</p><p id="remindMsg" class="lede"></p><div class="chips" id="filterChips"></div><div id="invoiceList"><p class="lede">Loading...</p></div><p id="listError" class="error"></p><p id="usage"></p></section>
<section class="panel"><h2 style="margin-top:0">Add an invoice</h2><form id="addForm"><div class="form-grid"><label>Client name<input name="client_name" type="text" maxlength="191" required placeholder="Acme Inc"></label><label>Client email (for one-tap sending)<input name="client_email" type="email" maxlength="191" placeholder="billing@acme.co"></label><label>Amount<input name="amount" type="number" min="0.01" step="0.01" required placeholder="1200.00"></label><label>Currency<select name="currency"><option value="USD">USD ($)</option><option value="CAD">CAD (CA$)</option><option value="EUR">EUR (€)</option><option value="GBP">GBP (£)</option></select></label><label>Invoice # (optional)<input name="invoice_no" type="text" maxlength="64" placeholder="INV-2026-014"></label><label>Due date<input name="due_date" type="date" required></label><label class="span-all">Notes (optional)<textarea name="notes" maxlength="2000" placeholder="Net 30, sent Sep 1..."></textarea></label></div><button class="button" style="margin-top:12px">Add invoice</button><p id="addError" class="error"></p></form></section>
<div id="upgrade-slot"></div>
<div class="modal-overlay hidden" id="modalOverlay"><div class="modal" role="dialog" aria-modal="true"><button class="modal-close" id="modalClose" aria-label="Close">×</button><div id="modalBody"></div></div></div>
<div class="modal-overlay hidden" id="chachingOverlay"><div class="modal chaching" role="dialog" aria-modal="true"><img src="/bum/cat-bowtie.png" alt="Bum Bum celebrating"><div class="chaching-amount" id="chachingAmount"></div><p class="lede" id="chachingSub"></p><button type="button" class="button" id="chachingClose">Nice.</button></div></div>
<script>
const TOOL_KEY='invoice-chaser';
const PERIOD=<?= json_encode($usage['period'] ?? '') ?>;
let DATA={invoices:[],stats:{}};
let FILTER='all';
if(false){const seen='bb_m80_'+PERIOD;if(!localStorage.getItem(seen)){localStorage.setItem(seen,'1');bbTrack('usage_milestone_80',{tool:TOOL_KEY,used:<?= (int) ($usage['used'] ?? 0) ?>,limit:<?= (int) ($usage['limit'] ?? 0) ?>})}}
function upgradeCard(){return ''}
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
  if(inv.status==='open'&&inv.days_overdue>0)parts.push(`was due ${esc(inv.due_date)}`);
  if(inv.last_chase_sent_at)parts.push(`chased ${esc(inv.last_chase_sent_at.slice(0,10))}`);
  if(inv.intel&&inv.intel.count>=1){
    const avg=inv.intel.avg_late;
    parts.push(avg<=0?'usually pays on time':`usually pays ~${avg} days late`);
  }
  if(inv.notes)parts.push(esc(inv.notes));
  return `<div class="invoice" data-inv="${inv.id}"><div class="top"><b>${esc(inv.client_name)}</b><span class="amount">${esc(inv.amount_display)}</span>${badge(inv)}<span style="flex:1"></span>`
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
  const rec=DATA.stats.recovered||{};
  const recKeys=Object.keys(rec).sort();
  const recAmounts={};recKeys.forEach(c=>recAmounts[c]=rec[c].total);
  const recCount=recKeys.reduce((s,c)=>s+rec[c].count,0);
  document.querySelector('#statRecovered').textContent=recCount?(recKeys.length===1?fmtCur(recAmounts):recCount+' invoices'):'$0.00';
  document.querySelector('#statRecoveredBy').textContent=recKeys.length>1?fmtCur(recAmounts):(recCount?open.length+' open · '+DATA.invoices.filter(i=>i.status==='paid').length+' paid':'');
}
function bindButtons(root){
  root.querySelectorAll('[data-draft]').forEach(b=>b.addEventListener('click',()=>openDraft(parseInt(b.getAttribute('data-draft'),10))));
  root.querySelectorAll('[data-paid]').forEach(b=>b.addEventListener('click',async()=>{
    const id=parseInt(b.getAttribute('data-paid'),10);
    const inv=DATA.invoices.find(x=>x.id===id);
    b.disabled=true;
    const{response,data}=await apiCall({action:'update',id,status:'paid'});
    b.disabled=false;
    if(!response.ok){document.querySelector('#listError').textContent=data.error||'Update failed.';return}
    bbTrack('chaser_paid',{tool:TOOL_KEY});
    document.querySelector('#chachingAmount').textContent='+'+(inv?inv.amount_display:'');
    document.querySelector('#chachingSub').textContent=inv?`${inv.client_name} paid up. That's real money back in your pocket.`:'Paid! Get that money.';
    document.querySelector('#chachingOverlay').classList.remove('hidden');
    document.body.style.overflow='hidden';
    await refresh();
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
let DRAFT_VOICE='me';
async function openDraft(id){
  const overlay=document.querySelector('#modalOverlay'),body=document.querySelector('#modalBody');
  overlay.classList.remove('hidden');document.body.style.overflow='hidden';
  DRAFT_VOICE='me';
  body.innerHTML='<p class="lede">Writing your chase email...</p>';
  await loadDraft(id);
}
async function loadDraft(id){
  const inv=DATA.invoices.find(x=>x.id===id);
  const body=document.querySelector('#modalBody');
  const{response,data}=await apiCall({action:'draft',id,voice:DRAFT_VOICE});
  if(!response.ok){body.innerHTML=`<p class="error">${esc(data.error||'Could not load.')}</p>`;return}
  const tone=inv.days_overdue<0?'Heads-up':inv.days_overdue<=14?'Gentle nudge':inv.days_overdue<=30?'Firm follow-up':'Final notice';
  body.innerHTML=`<h2>Chase draft</h2><p class="lede">${esc(inv.client_name)} · ${esc(inv.amount_display)} · <b>${tone}</b> (${inv.days_overdue<0?'not due yet':inv.days_overdue===0?'due today':inv.days_overdue+' days overdue'})</p>`
    +`<div class="chips"><button type="button" class="chip${DRAFT_VOICE==='me'?' on':''}" data-voice="me">Send as me</button><button type="button" class="chip${DRAFT_VOICE==='assistant'?' on':''}" data-voice="assistant">Send as Bum Bum, my billing assistant</button></div>`
    +`<p class="meta" style="margin:2px 0 10px">Bum Bum's take: ${esc(data.take)}</p>`
    +`<div class="draft-subject">Subject: ${esc(data.subject)}</div><div class="draft-box">${esc(data.body)}</div>`
    +`<div class="btnrow"><button type="button" class="button secondary" id="copySubject">Copy subject</button><button type="button" class="button secondary" id="copyBody">Copy email</button></div>`
    +`<div id="sendZone" style="margin-top:14px"></div><p class="error" id="draftErr"></p>`;
  const sendZone=body.querySelector('#sendZone');
  const renderSendZone=()=>{
    const fresh=DATA.invoices.find(x=>x.id===id);
    if(fresh.client_email){
      sendZone.innerHTML=`<button type="button" class="button" id="sendChase">Send it via Bum Bum</button><p class="meta" style="margin:6px 0 0">Sends to ${esc(fresh.client_email)} from Bum Bum. Replies go to your email. One action.</p><p class="lede" id="sendMsg" style="margin:6px 0 0"></p>`;
      const btn=sendZone.querySelector('#sendChase');
      btn.addEventListener('click',async()=>{
        if(!btn.dataset.armed){btn.dataset.armed='1';btn.textContent=`Tap again to send to ${fresh.client_email}`;return}
        btn.disabled=true;btn.textContent='Sending...';
        const r=await apiCall({action:'send',id,voice:DRAFT_VOICE});
        btn.disabled=false;
        const msg=sendZone.querySelector('#sendMsg');
        if(!r.response.ok){body.querySelector('#draftErr').textContent=r.data.error||'Send failed.';btn.textContent='Send it via Bum Bum';delete btn.dataset.armed;return}
        msg.textContent=r.data.duplicate?`Already chased today. Bum Bum is on it.`:`Sent to ${r.data.sent_to}. Bum Bum is on it.`;
        msg.style.color='#2f7d3a';btn.style.display='none';
        bbTrack('chaser_sent',{tool:TOOL_KEY,voice:DRAFT_VOICE});
        refresh();
      });
    }else{
      sendZone.innerHTML=`<label style="font-weight:600">Client email, so Bum Bum can send it<input id="clientEmailInline" type="email" maxlength="191" placeholder="billing@client.co"></label><div class="btnrow"><button type="button" class="button secondary" id="saveClientEmail">Save email</button></div>`;
      sendZone.querySelector('#saveClientEmail').addEventListener('click',async()=>{
        const em=sendZone.querySelector('#clientEmailInline').value.trim();
        if(!em){body.querySelector('#draftErr').textContent='Type the client email first.';return}
        const r=await apiCall({action:'update',id,client_email:em});
        if(!r.response.ok){body.querySelector('#draftErr').textContent=r.data.error||'Could not save.';return}
        await refresh();loadDraft(id);
      });
    }
  };
  renderSendZone();
  body.querySelectorAll('[data-voice]').forEach(ch=>ch.addEventListener('click',()=>{DRAFT_VOICE=ch.getAttribute('data-voice');loadDraft(id);}));
  const copy=(text,btn,okLabel)=>{
    btn.addEventListener('click',async()=>{
      try{await navigator.clipboard.writeText(text);btn.textContent=okLabel;}catch(_){btn.textContent='Copy failed'}
      setTimeout(()=>{btn.textContent=btn.dataset.orig},2000);
    });
    btn.dataset.orig=btn.textContent;
  };
  copy(data.subject,body.querySelector('#copySubject'),'Subject copied!');
  copy(data.body,body.querySelector('#copyBody'),'Email copied!');
  bbTrack('chaser_draft_viewed',{tool:TOOL_KEY,voice:DRAFT_VOICE});
}
function closeModal(){document.querySelector('#modalOverlay').classList.add('hidden');document.body.style.overflow='';}
document.querySelector('#modalClose').addEventListener('click',closeModal);
document.querySelector('#modalOverlay').addEventListener('click',e=>{if(e.target.id==='modalOverlay')closeModal()});
function closeChaching(){document.querySelector('#chachingOverlay').classList.add('hidden');document.body.style.overflow='';}
document.querySelector('#chachingClose').addEventListener('click',closeChaching);
document.querySelector('#chachingOverlay').addEventListener('click',e=>{if(e.target.id==='chachingOverlay')closeChaching()});
document.addEventListener('keydown',e=>{if(e.key==='Escape'){if(!document.querySelector('#chachingOverlay').classList.contains('hidden'))closeChaching();else if(!document.querySelector('#modalOverlay').classList.contains('hidden'))closeModal()}});
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
  document.querySelector('#usage').textContent='Free, no limits.';
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
  document.querySelector('#usage').textContent='Free, no limits.';
  addForm.reset();await refresh();
});
refresh().then(function(){
  // Deep link from the dashboard briefing: scroll to the invoice and flash it.
  var id = parseInt(new URLSearchParams(location.search).get('invoice') || '', 10);
  if (!id) return;
  var card = document.querySelector('[data-inv="' + id + '"]');
  if (!card) return;
  card.scrollIntoView({behavior:'smooth', block:'center'});
  card.classList.add('flash');
  setTimeout(function(){ card.classList.remove('flash'); }, 2800);
}).catch(function(){});
</script><?php endif; ?><section class="panel" id="templates" aria-label="Invoice email templates">
<h2 style="margin-top:0">Sending the invoice email, and chasing it</h2>
<p>Most invoice email trouble is two jobs, not one: sending the invoice well the first time, and chasing it when it goes quiet. The tool above handles the chasing for you, tracks who owes what, writes the email in the right tone, and sends it as your billing assistant in one tap. This section is the by-hand version of the same work, for the invoices you send yourself.</p>
<h3>How to send an invoice by email</h3>
<p>1. <strong>Use a subject line that says what it is:</strong> Invoice [number] — [your business] — due [date]. Your client should be able to spot it in a crowded inbox and search for it later.</p>
<p>2. <strong>Attach the invoice as a PDF.</strong> Never send the invoice only as email text; accounts departments file PDFs, and a fresh copy attached to every email means nobody has to dig for the original.</p>
<p>3. <strong>Put the essentials in the email itself:</strong> the amount, the due date, what the work was, and how to pay (a payment link or your bank details).</p>
<p>4. <strong>Send it the day the work is done</strong>, from an address the client recognizes. An invoice that arrives with the delivery starts the payment clock at the right time.</p>
<p>5. <strong>Keep the tone boring.</strong> The first invoice email is admin, not a sales pitch.</p>
<h3>A real example: the same invoice, two tones</h3>
<p>Invoice INV-2026-014, Acme Inc, $1,200.00, due November 1. Three days overdue, the polite version:</p>
<div class="draft-box"><div class="draft-subject">Subject: Quick check on invoice INV-2026-014</div>Hi Sarah, just checking in on invoice INV-2026-014 for $1,200.00, which was due on November 1. It may already be on its way, in which case ignore this. If it is stuck somewhere, tell me what you need and I will send it over. Thanks, Leon</div>
<p>Sixteen days overdue, the same invoice gets the firm version:</p>
<div class="draft-box"><div class="draft-subject">Subject: Invoice INV-2026-014 is now 16 days overdue</div>Hi Sarah, invoice INV-2026-014 for $1,200.00 was due on November 1 and is now 16 days overdue. Please arrange payment this week, or let me know today if there is a problem I am not aware of. The invoice is attached again for convenience. Regards, Leon</div>
<p>The difference is a specific timeframe and a named next step, not raised voices. The tool writes this draft for you at whichever stage the invoice has reached; the templates below are the copy-paste version.</p>
<h3>Copy-paste templates</h3>
<p><strong>The first send</strong> (the day the work is done):</p>
<div class="draft-box"><div class="draft-subject">Subject: Invoice [number] — [your business] — due [date]</div>Hi [name], attached is invoice [number] for [amount] for [work done]. It is due on [date]. You can pay here: [payment link or details]. Any questions, just reply to this email. Thanks, [your name]</div>
<p><strong>The polite reminder</strong> (1 to 7 days overdue):</p>
<div class="draft-box"><div class="draft-subject">Subject: Quick check on invoice [number]</div>Hi [name], just checking in on invoice [number] for [amount], which was due on [date]. It may already be on its way, in which case ignore this. If it is stuck somewhere, tell me what you need and I will send it over. Thanks, [your name]</div>
<p><strong>The firm reminder</strong> (around 14 days overdue):</p>
<div class="draft-box"><div class="draft-subject">Subject: Invoice [number] is now 14 days overdue</div>Hi [name], invoice [number] for [amount] was due on [date] and is now two weeks overdue. Please arrange payment this week, or let me know today if there is a problem I am not aware of. I have attached the invoice again for convenience. Regards, [your name]</div>
<p><strong>The final notice</strong> (30 days and beyond):</p>
<div class="draft-box"><div class="draft-subject">Subject: Final notice: invoice [number]</div>Hi [name], this is a final notice for invoice [number] for [amount], due on [date]. If payment is not received by [specific date], I will need to pause work on [project or account] and pass this to collections. If something has gone wrong, call me at [number] and we will sort it out directly. Regards, [your name]</div>
<p>Only send consequences you will actually carry out. A pause on work is usually consequence enough, and it is honest. Mention late fees only if your invoice terms stated them up front; introducing a fee mid-chase damages trust and may not be enforceable.</p>
<h3>When to send each one</h3>
<table class="ex-table">
<tr><th>When</th><th>Send</th></tr>
<tr><td>The day the work is done</td><td>The invoice itself, with the first-send email above</td></tr>
<tr><td>A few days before the due date</td><td>A friendly heads-up with the invoice attached; it reads as good admin and prevents a large share of late payments</td></tr>
<tr><td>1 to 7 days overdue</td><td>The polite reminder</td></tr>
<tr><td>Around 14 days overdue</td><td>The firm reminder</td></tr>
<tr><td>30 days and beyond</td><td>The final notice, with a named consequence</td></tr>
</table>
<p>The full walkthrough of the four chase stages, with the heads-up template included, is on the blog: <a href="/blog/invoice-reminder-templates/">invoice reminder templates for every stage of late</a>.</p>
</section>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Do I need an account?</summary>
<p>No. Start as a guest. It is free, with no limits. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>Does it actually send the emails?</summary>
<p>Yes. Bum Bum writes the chase email in the right tone and sends it as your billing assistant in one tap.</p>
</details>
<details>
<summary>Will it annoy my clients?</summary>
<p>It learns how each client actually pays, so it knows when to nudge and when to sit tight. The tone stays professional, never desperate.</p>
</details>
<details>
<summary>What if a client always pays late?</summary>
<p>That is exactly who this is for. The chaser adapts its timing to each client's real payment pattern.</p>
</details>
<details>
<summary>Where can I get invoice reminder email templates?</summary>
<p>Right on this page: the <a href="#templates">templates section</a> above has the first send, the polite reminder, the firm reminder, and the final notice, ready to copy and adjust. The blog's <a href="/blog/invoice-reminder-templates/">invoice reminder templates</a> post walks through the same stages with more explanation.</p>
</details>
</section>
<?php $bbTool = 'invoice-chaser'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
<section class="panel" aria-label="Guide"><h2 style="margin-top:0">Getting paid without the awkward, the short guide</h2>
<h3>How it works here</h3>
<p>Track who owes you what, and when a chase is due, Bum Bum writes the email in the right tone. You always see and approve it before anything sends; then it goes out as your billing assistant in one tap. Adding an invoice or sending a chase is free, and so is everything else.</p>
<h3>Timing is most of the tone</h3>
<p>Chase the day after the due date, politely. Treat the first nudge as a simple reminder, since most late invoices are sitting unread in a busy inbox rather than being deliberately ignored. The chaser also learns how each client actually pays, so a client who always pays on day 20 does not get nudged on day 2.</p>
<h3>Add invoices when you send them</h3>
<p>Invoices added only once they are overdue skew the learning, which works from the real dates. A tracker that only holds the problem invoices teaches itself the wrong lesson about everyone.</p>
<h3>Read the guide</h3><p>The full walkthrough: <a href="/how-to-write-an-invoice-reminder-email/">How to write an invoice reminder email (with templates)</a>.</p><p>Chasing invoices is one piece of the money puzzle: see how <a href="/blog/best-business-account-software/">the best business account software for small businesses</a> compares side by side.</p></section></main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
