<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Hearsay | Leave It to Bum Bum</title>
<meta name="description" content="Find out what AI models say about your brand. Mention rate, sentiment, direct quotes, and which competitors get cited instead. Free to try, no account needed.">
<meta property="og:title" content="Hearsay | Leave It to Bum Bum">
<meta property="og:description" content="Find out what AI models say about your brand. Mention rate, sentiment, direct quotes, and which competitors get cited instead. Free to try, no account needed.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/hearsay/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og-hearsay.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Hearsay | Leave It to Bum Bum">
<meta name="twitter:description" content="Find out what AI models say about your brand. Mention rate, sentiment, direct quotes, and which competitors get cited instead. Free to try, no account needed.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og-hearsay.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/hearsay/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Hearsay",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Find out what AI models say about your brand. Mention rate, sentiment, direct quotes, and which competitors get cited instead. Free to try, no account needed.",
  "url": "https://leaveittobumbum.com/tools/hearsay/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Is Hearsay free?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You get 15 free actions with no signup. Create a free account and you get 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/. One finished scan uses one action."
      }
    },
    {
      "@type": "Question",
      "name": "Do I need an account?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Scan as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "Which AI models does Hearsay ask?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Three open models: GPT-OSS 120B, GPT-OSS 20B, and Qwen 3.8 27B. Every question goes to every model, so a 5-question scan is 15 separate AI answers."
      }
    },
    {
      "@type": "Question",
      "name": "How accurate are the results?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Directional, not gospel. AI answers shift with phrasing, timing, and model updates. Use Hearsay to spot trends month to month, not as a final grade."
      }
    },
    {
      "@type": "Question",
      "name": "What do I get in a scan?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "For each question: which models mentioned your brand, the vibe of what they said, direct quotes, and which competitors got cited instead. Plus an overall verdict, from radio silent to loud and clear."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Hearsay | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Hearsay","item":"https://leaveittobumbum.com/tools/hearsay/"}]}</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
#hsScan input[type=text]{width:100%;padding:14px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff;margin:6px 0 12px}
#hsScan textarea{width:100%;padding:12px 14px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff;margin:6px 0 8px;min-height:64px;resize:vertical}
#hsScan label{font-weight:800;display:block;margin-top:8px}
#hsScan .qrow{display:flex;gap:8px;align-items:flex-start}
#hsScan .qrow textarea{flex:1}
#hsScan .qrow button{flex:none;margin-top:6px}
#hsGo{font-size:18px;padding:14px 26px;margin-top:14px}
#hsStatus{font-weight:700;margin:14px 0 0}
.mini{padding:8px 14px;font-size:14px}
.hidden{display:none!important}
.badge{display:inline-block;font-size:11px;font-weight:800;letter-spacing:.05em;text-transform:uppercase;border-radius:999px;padding:3px 10px;margin-right:6px;vertical-align:2px}
.badge.yes{background:#1d7a3a;color:#fff}.badge.no{background:#e8e2d4;color:#6b655a}
.pill{display:inline-block;font-size:12px;font-weight:800;border-radius:999px;padding:3px 12px;margin:2px 4px 2px 0}
.pill.positive{background:#e2f2e6;color:#1d7a3a}.pill.negative{background:#fbe3e3;color:#b3261e}.pill.mixed{background:#fdf0d5;color:#8a5a00}.pill.neutral{background:#eee;color:#6b655a}
#hsResults .qcard{border:2px solid var(--line);border-radius:10px;padding:16px;background:#fff;margin:0 0 12px}
#hsResults .qcard h3{margin:0 0 8px;font-size:18px}
#hsResults blockquote{border-left:4px solid var(--accent,#b3541e);margin:8px 0;padding:6px 12px;background:#faf8f2;border-radius:0 8px 8px 0;font-style:italic}
#hsResults .modelhead{font-weight:900;margin:12px 0 4px}
#hsSummary .big{font-size:clamp(1.4rem,3vw,2.2rem);font-weight:900;margin:0 0 6px}
.meterbar{height:12px;border-radius:999px;background:#eee;overflow:hidden;margin:10px 0}
.meterbar>div{height:100%;background:var(--accent,#b3541e)}
.compchip{display:inline-block;background:#fff;border:2px solid var(--line);border-radius:999px;padding:4px 14px;font-weight:800;margin:2px 6px 2px 0}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Hearsay"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-curious.png" alt="Bum Bum listening in"><h1>Find out what the AI says about your brand.</h1><p class="lede">Type your brand name and the questions your customers ask. Hearsay asks three AI models every question and reports back: how often you get mentioned, the vibe, direct quotes, and which competitors keep stealing your spotlight. One finished scan uses one action.</p>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to use Hearsay</h2><a class="button" href="/account/?next=<?= urlencode('/tools/hearsay/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/hearsay/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/hearsay/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/hearsay/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php $out = empty($usage['unlimited']) && ($usage['remaining'] ?? 0) <= 0; ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<?php if ($out): ?>
<div id="upgrade-slot"></div>
<?php else: ?>
<section class="panel" id="hsScan">
<h2 style="margin-top:0">Run a scan</h2>
<label>Brand name<input type="text" id="hsBrand" maxlength="60" placeholder="Acme Lash Studio" autocomplete="off"></label>
<label>Domain (optional)<input type="text" id="hsDomain" maxlength="120" placeholder="acmelash.com" autocomplete="off"></label>
<label>Questions your customers ask <span style="font-weight:400;color:var(--muted)">(1 to 5)</span></label>
<div id="hsQs"></div>
<button id="hsAddQ" class="button secondary mini" type="button">Add a question</button><br>
<button id="hsGo" class="button" type="button">Ask the models</button>
<p id="hsStatus"></p>
<p id="hsUsage"></p>
</section>
<section class="panel hidden" id="hsSummary"><h2 style="margin-top:0">The verdict</h2><div id="hsSummaryBody"></div></section>
<section class="panel hidden" id="hsResults"><h2 style="margin-top:0">Question by question</h2><div id="hsResultsBody"></div></section>
<section class="panel" id="hsLibrary"><h2 style="margin-top:0">Your scans</h2><div id="hsList"><p class="lede">Loading...</p></div></section>
<div id="upgrade-slot"></div>
<?php endif; ?>
<script>
const TOOL_KEY='hearsay';
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
const brandEl=document.querySelector('#hsBrand');
const domainEl=document.querySelector('#hsDomain');
const qsEl=document.querySelector('#hsQs');
const addQBtn=document.querySelector('#hsAddQ');
const goBtn=document.querySelector('#hsGo');
const statusEl=document.querySelector('#hsStatus');
const usageEl=document.querySelector('#hsUsage');
const sumSec=document.querySelector('#hsSummary');
const sumBody=document.querySelector('#hsSummaryBody');
const resSec=document.querySelector('#hsResults');
const resBody=document.querySelector('#hsResultsBody');
let scanKey=crypto.randomUUID();
function setStatus(msg,isError){statusEl.textContent=msg;statusEl.classList.toggle('error',!!isError);}
function addQuestion(value){const rows=qsEl.querySelectorAll('textarea');if(rows.length>=5)return;const wrap=document.createElement('div');wrap.className='qrow';wrap.innerHTML='<textarea maxlength="300" placeholder="What is the best lash studio in Toronto?"></textarea><button type="button" class="button secondary mini">Remove</button>';if(value)wrap.querySelector('textarea').value=value;wrap.querySelector('button').addEventListener('click',()=>{if(qsEl.querySelectorAll('textarea').length>1)wrap.remove();});qsEl.appendChild(wrap);addQBtn.disabled=qsEl.querySelectorAll('textarea').length>=5;}
addQBtn.addEventListener('click',()=>addQuestion(''));
addQuestion('');addQuestion('');
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/hearsay.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data};}
function sentPill(s){return `<span class="pill ${esc(s)}">${esc(s)}</span>`;}
function renderScan(scan){
const s=scan.summary;
sumBody.innerHTML=`<p class="big">${esc(s.headline)}</p>
<div class="meterbar"><div style="width:${s.mention_pct}%"></div></div>
<p>${sentPill('positive')} ${s.sentiment.positive} &nbsp;${sentPill('negative')} ${s.sentiment.negative} &nbsp;${sentPill('mixed')} ${s.sentiment.mixed} &nbsp;${sentPill('neutral')} ${s.sentiment.neutral}</p>
${s.top_competitors.length?`<p><strong>Cited instead of you:</strong></p><p>${s.top_competitors.map(c=>`<span class="compchip">${esc(c)}</span>`).join('')}</p>`:'<p>No competitors cited. The spotlight is all yours.</p>'}
<p style="color:var(--muted);font-size:14px">${esc(s.directional_note)}</p>`;
sumSec.classList.remove('hidden');
resBody.innerHTML=scan.questions.map(q=>{const rate=q.answers_count?Math.round(q.mentions/q.answers_count*100):0;
const answers=q.answers.map(a=>`<div class="modelhead">${esc(a.label)} <span class="badge ${a.mentioned?'yes':'no'}">${a.mentioned?'mentioned':'not mentioned'}</span>${a.mentioned?sentPill(a.sentiment):''}</div>${a.quotes.map(qt=>`<blockquote>${esc(qt)}</blockquote>`).join('')}`).join('');
return `<div class="qcard"><h3>${esc(q.question)}</h3><p><strong>${q.mentions} of ${q.answers_count}</strong> models mentioned ${esc(scan.brand)} (${rate}%)</p>${answers}${q.competitors.length?`<p style="margin-top:8px"><strong>Also cited:</strong> ${q.competitors.map(c=>`<span class="compchip">${esc(c)}</span>`).join('')}</p>`:''}</div>`;}).join('');
resSec.classList.remove('hidden');
resSec.scrollIntoView({behavior:'smooth',block:'nearest'});
}
goBtn.addEventListener('click',async()=>{
const brand=brandEl.value.trim();
const domain=domainEl.value.trim();
const questions=[...qsEl.querySelectorAll('textarea')].map(t=>t.value.trim()).filter(Boolean);
if(brand.length<2){setStatus('Tell me your brand name first.',true);brandEl.focus();return;}
if(!questions.length){setStatus('Ask at least one question your customers would ask.',true);return;}
if(questions.some(q=>q.length<10)){setStatus('Spell each question out a little more, like a customer would.',true);return;}
const key=scanKey;scanKey=crypto.randomUUID();
goBtn.disabled=true;setStatus('Asking three AI models. This takes about 30 seconds...');
sumSec.classList.add('hidden');resSec.classList.add('hidden');
try{
const{response,data}=await apiCall({action:'scan',brand:brand,domain:domain,questions:questions,idempotencyKey:key});
if(!response.ok){
if(response.status===402){bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});document.querySelector('#upgrade-slot').innerHTML=upgradeCard();bindUpgradeClicks(document.querySelector('#upgrade-slot'),'limit');usageEl.textContent='This scan was not counted.';}
else{setStatus('Hearsay hiccup ('+(data.error||'hmm')+'). Nothing was counted, try again.',true);}
return;}
bbTrack('action_completed',{tool:TOOL_KEY,used:data.usage.used,limit:data.usage.limit,remaining:data.usage.remaining,questions:questions.length});
renderScan(data.scan);
setStatus('Done. Go get mentioned more.');
usageEl.textContent=data.usage.unlimited?'Unlimited actions.':data.usage.remaining+' actions left.';
loadScans();
}catch(e){setStatus('Hearsay hiccup. Nothing was counted, try again.',true);}
finally{goBtn.disabled=false;}
});
async function loadScans(){const listEl=document.querySelector('#hsList');if(!listEl)return;const{response,data}=await apiCall({action:'list'});if(!response.ok){listEl.innerHTML='<p class="error">Could not load your scans.</p>';return}const entries=data.entries||[];if(!entries.length){listEl.innerHTML='<p class="lede" style="margin:0">No scans yet. Your finished scans will land here.</p>';return}listEl.innerHTML=entries.map(e=>'<div class="req" data-id="'+e.id+'"><div style="display:flex;justify-content:space-between;gap:12px;align-items:start;flex-wrap:wrap"><div><div style="font-weight:900">'+esc(e.title)+'</div><div style="color:var(--muted);font-size:14px">'+esc(e.created_at)+'</div><div style="margin-top:6px">'+esc(e.preview||'')+'</div></div><div style="white-space:nowrap"><button type="button" class="secondary" data-open style="margin-top:0">Open</button> <button type="button" class="secondary" data-del style="margin-top:0">Delete</button></div></div></div>').join('')}
document.querySelector('#hsList').addEventListener('click',async event=>{const btn=event.target.closest('[data-open],[data-del]');if(!btn)return;const row=event.target.closest('[data-id]');const id=row.dataset.id;if(btn.hasAttribute('data-open')){const{response,data}=await apiCall({action:'get',id});if(!response.ok){alert(data.error||'Could not open that scan.');return}renderScan(data.entry.scan);setStatus('Opened from your library.');}else{if(!confirm('Delete this scan? This cannot be undone.'))return;const{response,data}=await apiCall({action:'delete',id});if(!response.ok){alert(data.error||'Could not delete.');return}loadScans();}});
loadScans();
<?php endif; ?>
</script><?php endif; ?><section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Is Hearsay free?</summary>
<p>You get 15 free actions with no signup. Create a free account and you get 75 actions every month. <a href="/pricing/">See pricing</a>. One finished scan uses one action.</p>
</details>
<details>
<summary>Do I need an account?</summary>
<p>No. Scan as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>Which AI models does Hearsay ask?</summary>
<p>Three open models: GPT-OSS 120B, GPT-OSS 20B, and Qwen 3.8 27B. Every question goes to every model, so a 5-question scan is 15 separate AI answers.</p>
</details>
<details>
<summary>How accurate are the results?</summary>
<p>Directional, not gospel. AI answers shift with phrasing, timing, and model updates. Use Hearsay to spot trends month to month, not as a final grade.</p>
</details>
<details>
<summary>What do I get in a scan?</summary>
<p>For each question: which models mentioned your brand, the vibe of what they said, direct quotes, and which competitors got cited instead. Plus an overall verdict, from radio silent to loud and clear.</p>
</details>
</section>
<section class="panel" aria-label="More tiny tools">
<h2 style="margin-top:0">More tiny tools</h2>
<p><a href="/tools/ghostwriter/">Ghostwriter</a> - Ramble for a minute, get three hooks, a 60-second script, and a caption ready to post.</p>
<p><a href="/tools/notes/">Bum Bum Notes</a> - Talk it out and get a live transcript you can copy or download.</p>
<p><a href="/tools/purr-code/">Purr Code</a> - Your link, but cute. Branded QR codes with soft dots, pretty colors, and a cat in the middle. Ready to print.</p>
</section>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
