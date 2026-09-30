<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Stat Cat | Leave It to Bum Bum</title>
<meta name="description" content="Connect Google Analytics and Search Console. Stat Cat turns your traffic into a plain-English digest: sessions, top pages, top queries, and what to do next.">
<meta property="og:title" content="Stat Cat | Leave It to Bum Bum">
<meta property="og:description" content="Connect Google Analytics and Search Console. Stat Cat turns your traffic into a plain-English digest: sessions, top pages, top queries, and what to do next.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/stat-cat/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og-stat-cat.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Stat Cat | Leave It to Bum Bum">
<meta name="twitter:description" content="Connect Google Analytics and Search Console. Stat Cat turns your traffic into a plain-English digest: sessions, top pages, top queries, and what to do next.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og-stat-cat.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/stat-cat/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Stat Cat",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Connect Google Analytics and Search Console. Stat Cat turns your traffic into a plain-English digest: sessions, top pages, top queries, and what to do next.",
  "url": "https://leaveittobumbum.com/tools/stat-cat/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Is Stat Cat free?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Connecting your Google account and picking your sites is free. Each traffic digest uses one action. You need a Bum Bum account to connect Google, so your tokens stay private to you. Create a free account and you get 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "What Google data does Stat Cat read?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Sessions, page views, and top pages from Google Analytics 4, plus clicks, impressions, and top search queries from Search Console. Read-only access, nothing else."
      }
    },
    {
      "@type": "Question",
      "name": "Is my Google data safe?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Stat Cat asks Google for read-only access only. Your tokens are encrypted on our server, never shown to anyone, and you can disconnect anytime, which revokes access on the spot."
      }
    },
    {
      "@type": "Question",
      "name": "What is in a traffic digest?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Your last 28 days of sessions, top pages, and traffic channels from Analytics, plus clicks, impressions, and top queries from Search Console, wrapped in a plain-English summary written by Stat Cat."
      }
    },
    {
      "@type": "Question",
      "name": "Why does Google warn the app is unverified?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Google shows that warning for new apps until its verification review finishes. It is safe to continue, and the warning goes away once Google approves us."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Stat Cat | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Stat Cat","item":"https://leaveittobumbum.com/tools/stat-cat/"}]}</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.hidden{display:none!important}
.mini{padding:8px 14px;font-size:14px}
select{width:100%;padding:14px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff;margin:6px 0 12px}
#scSummary{white-space:pre-wrap;line-height:1.7;font-size:17px}
.sc-table{width:100%;border-collapse:collapse;margin:12px 0;font-size:15px}
.sc-table th{text-align:left;font-size:12px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);padding:8px 10px;border-bottom:2px solid var(--line)}
.sc-table td{padding:8px 10px;border-bottom:1px solid var(--line)}
.sc-table td.n,.sc-table th.n{text-align:right;font-variant-numeric:tabular-nums}
.sc-kpis{display:flex;gap:12px;flex-wrap:wrap;margin:14px 0}
.sc-kpi{flex:1 1 140px;border:2px solid var(--line);border-radius:10px;padding:12px 14px;background:#fff}
.sc-kpi .v{font-size:26px;font-weight:900}
.sc-kpi .l{font-size:12px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:var(--muted)}
.sc-kpi .d{font-size:13px;font-weight:800}
.sc-kpi .d.up{color:#1e7a34}.sc-kpi .d.down{color:#b3541e}
.linklike{background:none;border:0;padding:0;margin-top:14px;color:var(--accent,#b3541e);font:inherit;font-weight:700;cursor:pointer;text-decoration:underline}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Stat Cat"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-laptop.png" alt="Bum Bum studying the numbers"><h1>Your traffic, translated from robot.</h1><p class="lede">Connect your Google account and Stat Cat reads your Analytics and Search Console, then tells you what actually happened in plain English. Sessions, top pages, top search queries, and one clear suggestion for next week. Connecting is free. Each digest uses one action.</p>
<?php if (!$user): ?><section class="panel"><h2 style="margin-top:0">Sign in to use Stat Cat</h2><p class="lede">Stat Cat links your Google account, so it needs a Bum Bum account to keep your tokens private to you.</p><a class="button" href="/account/?next=<?= urlencode('/tools/stat-cat/') ?>">Sign in or create an account</a></section><?php else: ?>
<div id="scNotice"></div>
<section class="panel" id="scConnect"><h2 style="margin-top:0">Connect Google</h2><p class="lede" style="margin-bottom:14px">Stat Cat asks for read-only access to Google Analytics and Search Console. Nothing else, and you can disconnect anytime.</p><p><a class="button" href="/api/stat-cat-oauth-start.php">Connect Google</a></p><p class="lede" style="font-size:14px">Heads up: Google may show an "unverified app" warning while our verification finishes. It is safe to continue.</p></section>
<section class="panel hidden" id="scSetup"><h2 style="margin-top:0">Pick your sites</h2><div id="scPickers"><p class="lede">Loading your Google properties...</p></div><button id="scSave" class="button" type="button">Save my picks</button> <button id="scDisconnect1" class="linklike" type="button">Disconnect Google</button></section>
<section class="panel hidden" id="scReady"><h2 style="margin-top:0">Fresh digest</h2><p class="lede" id="scReadyLine"></p><div class="notes-row" style="display:flex;gap:12px;flex-wrap:wrap"><button id="scDigestBtn" class="button" type="button">Generate my digest</button><button id="scChange" class="button secondary" type="button">Change sites</button></div><p id="scUsage"></p><div id="scOut"></div><p style="margin-top:14px"><button id="scDisconnect2" class="linklike" type="button">Disconnect Google</button></p></section>
<section class="panel" id="scLibrary"><h2 style="margin-top:0">Your past digests</h2><div id="scList"><p class="lede">Loading...</p></div></section>
<?php endif; ?>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Is Stat Cat free?</summary>
<p>Connecting your Google account and picking your sites is free. Each traffic digest uses one action. You need a Bum Bum account to connect Google, so your tokens stay private to you. Create a free account and you get 75 actions every month. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>What Google data does Stat Cat read?</summary>
<p>Sessions, page views, and top pages from Google Analytics 4, plus clicks, impressions, and top search queries from Search Console. Read-only access, nothing else.</p>
</details>
<details>
<summary>Is my Google data safe?</summary>
<p>Stat Cat asks Google for read-only access only. Your tokens are encrypted on our server, never shown to anyone, and you can disconnect anytime, which revokes access on the spot.</p>
</details>
<details>
<summary>What is in a traffic digest?</summary>
<p>Your last 28 days of sessions, top pages, and traffic channels from Analytics, plus clicks, impressions, and top queries from Search Console, wrapped in a plain-English summary written by Stat Cat.</p>
</details>
<details>
<summary>Why does Google warn the app is unverified?</summary>
<p>Google shows that warning for new apps until its verification review finishes. It is safe to continue, and the warning goes away once Google approves us.</p>
</details>
</section>
<section class="panel" aria-label="More tiny tools">
<h2 style="margin-top:0">More tiny tools</h2>
<p><a href="/tools/gap-scout/">Gap Scout</a> - Upload your resume and paste in job descriptions. Bum Bum spots the skills they keep asking for that your resume never mentions.</p>
<p><a href="/tools/ghostwriter/">Ghostwriter</a> - Ramble for a minute, get three hooks, a 60-second script, and a caption ready to post.</p>
<p><a href="/tools/sop-ify/">SOP-ify</a> - Paste your chaotic process brain-dump. Get a clean step-by-step playbook a brand-new helper can follow.</p>
</section>
<?php if ($user): ?>
<script>
const TOOL_KEY='stat-cat';
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
function fmt(n){return Number(n||0).toLocaleString('en-US')}
function pctChange(cur,prev){if(!prev)return'';const d=Math.round((cur-prev)/prev*100);const cls=d>=0?'up':'down';const arrow=d>=0?'▲':'▼';return `<span class="d ${cls}">${arrow} ${Math.abs(d)}% vs prior 28d</span>`}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/stat-cat.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data};}
const noticeEl=document.querySelector('#scNotice');
const connectSec=document.querySelector('#scConnect');
const setupSec=document.querySelector('#scSetup');
const readySec=document.querySelector('#scReady');
const pickersEl=document.querySelector('#scPickers');
const outEl=document.querySelector('#scOut');
const usageEl=document.querySelector('#scUsage');
const readyLine=document.querySelector('#scReadyLine');
(function flash(){
  const q=new URLSearchParams(location.search);
  const err=q.get('error'), ok=q.get('connected');
  if(ok){noticeEl.innerHTML='<div class="nudge">Google connected. Now pick which property and site Stat Cat should read.</div>';}
  else if(err){
    const msgs={not_configured:'Stat Cat is not finished setting up on the server yet. Check back soon.',google_denied:'Google connection was cancelled. No worries, try again whenever.',signed_out:'You were signed out. Sign in and try again.',bad_state:'That connection attempt expired. Please try again.',expired:'That connection attempt expired. Please try again.',no_code:'Google did not send us a code. Please try again.',exchange_failed:'Google said no during the handshake. Try again in a moment.',no_refresh_token:'Google did not grant ongoing access. Try connecting again.'};
    noticeEl.innerHTML='<div class="upgrade-card"><p class="lede" style="margin:0">'+esc(msgs[err]||'Something went sideways. Try again.')+'</p></div>';
  }
  if(err||ok){history.replaceState(null,'',location.pathname);}
})();
function show(el){el.classList.remove('hidden')}
function hide(el){el.classList.add('hidden')}
let ga4Props=[], scSites=[];
async function loadStatus(){
  const{response,data}=await apiCall({action:'status'});
  if(!response.ok){noticeEl.innerHTML='<div class="upgrade-card"><p class="lede" style="margin:0">'+esc(data.error||'Could not reach Stat Cat.')+'</p></div>';return}
  if(!data.oauth_configured){
    connectSec.innerHTML='<h2 style="margin-top:0">Almost ready</h2><p class="lede">Stat Cat is still being wired up on the server. Check back soon.</p>';
    return;
  }
  if(!data.connected){show(connectSec);hide(setupSec);hide(readySec);return}
  hide(connectSec);
  if(data.setup_complete){
    hide(setupSec);show(readySec);
    const bits=[];
    if(data.ga4_property_name)bits.push('Analytics: '+data.ga4_property_name);
    if(data.sc_site_url)bits.push('Search Console: '+data.sc_site_url);
    readyLine.textContent='Reading '+bits.join(' and ')+'. One digest uses one action.';
    if(data.usage)usageEl.textContent=data.usage.unlimited?'Unlimited actions.':data.usage.remaining+' actions left.';
  }else{
    show(setupSec);hide(readySec);
    await loadPickers(data);
  }
}
async function loadPickers(status){
  pickersEl.innerHTML='<p class="lede">Loading your Google properties...</p>';
  const props=await apiCall({action:'properties'});
  const sites=await apiCall({action:'sites'});
  ga4Props=(props.data.properties||[]);
  scSites=(sites.data.sites||[]);
  let html='';
  if(props.data.error)html+='<p class="error">'+esc(props.data.error)+'</p>';
  else{
    html+='<label>Google Analytics 4 property<select id="scGa4"><option value="">None (skip Analytics)</option>'+ga4Props.map(p=>`<option value="${esc(p.id)}">${esc(p.account)} / ${esc(p.name)}</option>`).join('')+'</select></label>';
  }
  if(sites.data.error)html+='<p class="error">'+esc(sites.data.error)+'</p>';
  else{
    html+='<label>Search Console site<select id="scSite"><option value="">None (skip Search Console)</option>'+scSites.map(s=>`<option value="${esc(s.siteUrl)}">${esc(s.siteUrl)}</option>`).join('')+'</select></label>';
  }
  if(!ga4Props.length&&!scSites.length&&!props.data.error&&!sites.data.error){
    html='<p class="lede">This Google account has no Analytics properties or Search Console sites we can see. Double check you picked the right Google account, then try again.</p>';
  }
  pickersEl.innerHTML=html;
  const cur=status||{};
  if(cur.ga4_property_id){const s=document.querySelector('#scGa4');if(s)s.value=cur.ga4_property_id;}
  if(cur.sc_site_url){const s=document.querySelector('#scSite');if(s)s.value=cur.sc_site_url;}
}
document.querySelector('#scSave').addEventListener('click',async()=>{
  const ga4=document.querySelector('#scGa4');const site=document.querySelector('#scSite');
  const btn=document.querySelector('#scSave');btn.disabled=true;btn.textContent='Saving...';
  const{response,data}=await apiCall({action:'save',ga4_property_id:ga4?ga4.value:'',sc_site_url:site?site.value:''});
  btn.disabled=false;btn.textContent='Save my picks';
  if(!response.ok){alert(data.error||'Could not save.');return}
  bbTrack('action_completed',{tool:TOOL_KEY,event:'sites_saved'});
  loadStatus();
});
document.querySelector('#scChange').addEventListener('click',async()=>{
  hide(readySec);show(setupSec);outEl.innerHTML='';
  const{data}=await apiCall({action:'status'});
  await loadPickers(data);
});
async function disconnect(){
  if(!confirm('Disconnect Google? Stat Cat will forget your tokens. You can reconnect anytime.'))return;
  const{response,data}=await apiCall({action:'disconnect'});
  if(!response.ok){alert(data.error||'Could not disconnect.');return}
  outEl.innerHTML='';loadStatus();
}
document.querySelector('#scDisconnect1').addEventListener('click',disconnect);
document.querySelector('#scDisconnect2').addEventListener('click',disconnect);
function kpi(label,value,delta){
  return `<div class="sc-kpi"><div class="v">${value}</div><div class="l">${label}</div>${delta||''}</div>`;
}
function renderDigest(digest,summary){
  const g=digest.ga4, s=digest.sc;
  let html='<h3 style="margin-top:24px">Stat Cat says</h3><p id="scSummary">'+esc(summary)+'</p>';
  if(g){
    html+='<h3>Analytics, last 28 days</h3><div class="sc-kpis">'
      +kpi('Sessions',fmt(g.sessions),pctChange(g.sessions,g.sessions_prev))
      +kpi('Visitors',fmt(g.users),'')
      +kpi('Page views',fmt(g.pageviews),'')
      +'</div>';
    if(g.top_pages&&g.top_pages.length){
      html+='<h4>Top pages</h4><table class="sc-table"><tr><th>Page</th><th class="n">Sessions</th></tr>'
        +g.top_pages.map(p=>`<tr><td>${esc(p.title||p.path)}</td><td class="n">${fmt(p.sessions)}</td></tr>`).join('')+'</table>';
    }
    if(g.channels&&g.channels.length){
      html+='<h4>Where visitors came from</h4><table class="sc-table"><tr><th>Channel</th><th class="n">Sessions</th></tr>'
        +g.channels.map(c=>`<tr><td>${esc(c.channel)}</td><td class="n">${fmt(c.sessions)}</td></tr>`).join('')+'</table>';
    }
  }
  if(s){
    html+='<h3>Search Console, last 30 days</h3><div class="sc-kpis">'
      +kpi('Clicks',fmt(s.clicks),'')
      +kpi('Impressions',fmt(s.impressions),'')
      +kpi('Avg. position','#'+s.avg_position,'')
      +kpi('Click rate',s.ctr+'%','')
      +'</div>';
    if(s.top_queries&&s.top_queries.length){
      html+='<h4>Top search queries</h4><table class="sc-table"><tr><th>Query</th><th class="n">Clicks</th><th class="n">Impressions</th><th class="n">Position</th></tr>'
        +s.top_queries.map(q=>`<tr><td>${esc(q.query)}</td><td class="n">${fmt(q.clicks)}</td><td class="n">${fmt(q.impressions)}</td><td class="n">${q.position}</td></tr>`).join('')+'</table>';
    }
  }
  if(digest.warnings&&digest.warnings.length){
    html+='<p class="lede" style="font-size:14px">Note: '+esc(digest.warnings.join(' '))+'</p>';
  }
  return html;
}
document.querySelector('#scDigestBtn').addEventListener('click',async()=>{
  const btn=document.querySelector('#scDigestBtn');
  const key=crypto.randomUUID();
  btn.disabled=true;btn.textContent='Reading your traffic...';
  outEl.innerHTML='<p class="lede">Asking Google nicely, then Stat Cat writes it up. Takes about 20 seconds.</p>';
  try{
    const{response,data}=await apiCall({action:'digest',idempotencyKey:key});
    if(!response.ok){
      if(response.status===402){outEl.innerHTML='<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">'+esc(data.error||'You are out of actions.')+'</p><p><a class="button" href="/account/#upgrade">Get more actions</a></p></div>';}
      else{outEl.innerHTML='<p class="error">'+esc(data.error||'The digest failed. Nothing was counted, try again.')+'</p>';}
      return;
    }
    bbTrack('action_completed',{tool:TOOL_KEY,used:data.usage.used,limit:data.usage.limit,remaining:data.usage.remaining});
    outEl.innerHTML=renderDigest(data.digest,data.summary);
    usageEl.textContent=data.usage.unlimited?'Unlimited actions.':data.usage.remaining+' actions left.';
    loadDigests();
  }catch(e){outEl.innerHTML='<p class="error">The digest failed. Nothing was counted, try again.</p>';}
  finally{btn.disabled=false;btn.textContent='Generate my digest';}
});
async function loadDigests(){
  const listEl=document.querySelector('#scList');if(!listEl)return;
  const{response,data}=await apiCall({action:'digests'});
  if(!response.ok){listEl.innerHTML='<p class="error">Could not load your digests.</p>';return}
  const rows=data.digests||[];
  if(!rows.length){listEl.innerHTML='<p class="lede" style="margin:0">No digests yet. Your finished digests will land here.</p>';return}
  listEl.innerHTML=rows.map(r=>'<div class="req" data-id="'+r.id+'"><div style="display:flex;justify-content:space-between;gap:12px;align-items:start;flex-wrap:wrap"><div><div style="font-weight:900">'+esc(r.created_at)+'</div><div style="margin-top:6px">'+esc(r.preview)+(r.preview.length>=140?'...':'')+'</div></div><div style="white-space:nowrap"><button type="button" class="secondary" data-open style="margin-top:0">Open</button> <button type="button" class="secondary" data-del style="margin-top:0">Delete</button></div></div></div>').join('');
}
document.querySelector('#scList').addEventListener('click',async event=>{
  const btn=event.target.closest('[data-open],[data-del]');if(!btn)return;
  const row=event.target.closest('[data-id]');const id=row.dataset.id;
  if(btn.hasAttribute('data-open')){
    const{response,data}=await apiCall({action:'get',id});
    if(!response.ok){alert(data.error||'Could not open that digest.');return}
    const d=data.digest;
    outEl.innerHTML='<h3 style="margin-top:0">Digest from '+esc(d.created_at)+'</h3>'+renderDigest(d.data,d.summary);
    outEl.scrollIntoView({behavior:'smooth',block:'nearest'});
  }else{
    if(!confirm('Delete this digest? This cannot be undone.'))return;
    const{response,data}=await apiCall({action:'delete',id});
    if(!response.ok){alert(data.error||'Could not delete.');return}
    loadDigests();
  }
});
loadStatus();
loadDigests();
</script>
<?php endif; ?>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
