<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>SOP-ify | Leave It to Bum Bum</title>
<meta name="description" content="Dump your process, messy and rambling. Get a clean step-by-step playbook a new helper could follow with zero context. Free to try.">
<meta property="og:title" content="SOP-ify | Leave It to Bum Bum">
<meta property="og:description" content="Dump your process, messy and rambling. Get a clean step-by-step playbook a new helper could follow with zero context. Free to try.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/sop-ify/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/favicon-cat.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="SOP-ify | Leave It to Bum Bum">
<meta name="twitter:description" content="Dump your process, messy and rambling. Get a clean step-by-step playbook a new helper could follow with zero context. Free to try.">
<link rel="canonical" href="https://leaveittobumbum.com/tools/sop-ify/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "SOP-ify",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Dump your process, messy and rambling. Get a clean step-by-step playbook a new helper could follow with zero context. Free to try.",
  "url": "https://leaveittobumbum.com/tools/sop-ify/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Is SOP-ify free?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You get 15 free actions with no signup. Create a free account and you get 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/. One finished playbook uses one action."
      }
    },
    {
      "@type": "Question",
      "name": "Do I need an account?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Start as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "How detailed is the playbook?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Step by step, written so a brand-new helper could follow it with zero prior context."
      }
    },
    {
      "@type": "Question",
      "name": "Can I talk instead of typing?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. Record a voice note and it gets transcribed automatically."
      }
    },
    {
      "@type": "Question",
      "name": "What do I do with the playbook?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Hand it to a helper, save it as your process doc, or use it to train the next person."
      }
    }
  ]
}
</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.sop-field{margin:0 0 16px}
.sop-field label{display:block;font-weight:800;margin-bottom:8px}
.sop-field input[type=text],.sop-field textarea{width:100%;padding:14px 16px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff;box-sizing:border-box}
.sop-field textarea{min-height:180px;resize:vertical}
.sop-hint{color:var(--muted);font-size:14px;margin:6px 0 0}
#sopResult{display:none}
.pb-title{font-family:Fraunces,Georgia,serif;font-weight:650;font-size:clamp(1.5rem,3.5vw,2.4rem);letter-spacing:-.02em;margin:0 0 8px}
.pb-purpose{color:var(--muted);margin:0 0 20px}
.pb-sec{margin:0 0 20px}
.pb-sec h3{margin:0 0 10px;font-size:1.05rem}
.pb-steps{list-style:none;margin:0;padding:0;counter-reset:step}
.pb-steps li{counter-increment:step;display:flex;gap:12px;margin:0 0 14px}
.pb-steps li::before{content:counter(step);flex:none;width:32px;height:32px;border-radius:50%;background:#1E2321;color:#fff;font-weight:900;display:flex;align-items:center;justify-content:center;font-size:15px}
.pb-step-t{font-weight:900;margin:0 0 4px}
.pb-step-d{margin:0;color:var(--ink)}
.pb-list{margin:0;padding-left:20px}
.pb-list li{margin:0 0 6px}
.pb-tips{background:#FFF9F2;border:2px solid var(--line);border-radius:12px;padding:14px 16px}
.pb-check{list-style:none;margin:0;padding:0}
.pb-check li{display:flex;gap:10px;align-items:flex-start;margin:0 0 10px;font-weight:700}
.pb-check input{width:20px;height:20px;margin-top:2px;accent-color:#1E2321;flex:none}
.pb-check li.done span{text-decoration:line-through;color:var(--muted)}
.pb-actions{display:flex;gap:10px;flex-wrap:wrap;margin:18px 0 0}
.sop-status{font-weight:700;min-height:1.5em;margin:10px 0 0}
.loading-dots::after{content:"";animation:dots 1.2s steps(4) infinite}
@keyframes dots{0%{content:""}25%{content:"."}50%{content:".."}75%{content:"..."}}
@media print{.site-header,.site-footer,.no-print,#sopForm{display:none!important}#sopResult{display:block!important}.shell{max-width:none}}
.hidden{display:none!important}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"SOP-ify"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-bowtie.png" alt="Bum Bum the executive"><h1>Chaos in. Playbook out.</h1><p class="lede">Dump how you do the thing, all messy and rambling. Get back a clean step-by-step playbook a brand-new helper could follow with zero prior context.</p>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to use SOP-ify</h2><a class="button" href="/account/?next=<?= urlencode('/tools/sop-ify/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/sop-ify/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/sop-ify/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/sop-ify/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php $out = empty($usage['unlimited']) && ($usage['remaining'] ?? 0) <= 0; ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<?php if ($out): ?>
<div id="upgrade-slot"></div>
<?php else: ?>
<section class="panel" id="sopForm">
<div class="sop-field"><label for="sopWho">Who is this playbook for? <span class="sop-hint">(optional)</span></label><input id="sopWho" type="text" maxlength="120" placeholder="my new part-time helper"></div>
<div class="sop-field"><label for="sopDump">Your brain-dump</label>
<div style="margin-bottom:10px"><button id="sopRec" class="button secondary" type="button">Record a voice note</button> <button id="sopStop" class="button secondary hidden" type="button">Stop recording</button></div>
<textarea id="sopDump" maxlength="6000" placeholder="ok so when someone books a lash fill I first check the calendar then I text them the day before to confirm, if they don't reply I..."></textarea><p class="sop-hint">Messy is fine. Ramble, type, or record. Bum Bum sorts it out.</p></div>
<button id="sopGo" class="button" type="button">Make it a playbook</button>
<p id="sopStatus" class="sop-status"></p>
<p id="sopUsage"></p>
</section>
<section class="panel" id="sopResult">
<h2 class="pb-title" id="pbTitle"></h2>
<p class="pb-purpose" id="pbPurpose"></p>
<div class="pb-sec" id="pbMaterialsWrap"><h3>Before you start</h3><ul class="pb-list" id="pbMaterials"></ul></div>
<div class="pb-sec"><h3>The steps</h3><ol class="pb-steps" id="pbSteps"></ol></div>
<div class="pb-sec" id="pbTipsWrap"><h3>Pro tips</h3><div class="pb-tips"><ul class="pb-list" id="pbTips"></ul></div></div>
<div class="pb-sec"><h3>Quick checklist</h3><ul class="pb-check" id="pbCheck"></ul></div>
<div class="pb-actions no-print">
<button class="button" id="pbCopy" type="button">Copy playbook</button>
<button class="button secondary" id="pbPrint" type="button">Print</button>
<button class="button secondary" id="pbAgain" type="button">Start over</button>
</div>
</section>
<div id="upgrade-slot"></div>
<?php endif; ?>
<script>
const TOOL_KEY='sop-ify';
const PERIOD=<?= json_encode($usage['period'] ?? '') ?>;
if(<?= $low ? 'true' : 'false' ?>){const seen='bb_m80_'+PERIOD;if(!localStorage.getItem(seen)){localStorage.setItem(seen,'1');bbTrack('usage_milestone_80',{tool:TOOL_KEY,used:<?= (int) ($usage['used'] ?? 0) ?>,limit:<?= (int) ($usage['limit'] ?? 0) ?>})}}
function upgradeCard(){return `<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) ($usage['limit'] ?? 75) ?> free actions this month. Helper gives you 1,500 actions for $12/month. Operator gives you 6,000 actions plus a custom tool built for you in 36 hours for $49/month.</p><p><a class="button" data-plan="helper" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" data-plan="operator" href="/checkout/?plan=operator">Get Operator, $49/mo</a></p><p><a href="/account/">See your usage</a></p></div>`}
function bindUpgradeClicks(root,context){root.querySelectorAll('[data-plan]').forEach(a=>a.addEventListener('click',()=>bbTrack('upgrade_clicked',{plan:a.dataset.plan,context:context,tool:TOOL_KEY})))}
<?php if ($out): ?>
document.querySelector('#upgrade-slot').innerHTML=upgradeCard();
bindUpgradeClicks(document,'page_load');
bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'page_load'});
<?php else: ?>
const dumpEl=document.querySelector('#sopDump');
const whoEl=document.querySelector('#sopWho');
const goBtn=document.querySelector('#sopGo');
const statusEl=document.querySelector('#sopStatus');
const usageEl=document.querySelector('#sopUsage');
const resultEl=document.querySelector('#sopResult');
const formEl=document.querySelector('#sopForm');
let genKey=crypto.randomUUID();
const recBtn=document.querySelector('#sopRec');
const stopBtn=document.querySelector('#sopStop');
const recState={recording:false,stream:null,recorder:null,chunks:[]};
function pickMime(){const c=['audio/webm;codecs=opus','audio/webm','audio/mp4','audio/ogg;codecs=opus'];for(const t of c){if(window.MediaRecorder&&MediaRecorder.isTypeSupported(t))return t;}return '';}
function cleanupRec(){if(recState.stream){recState.stream.getTracks().forEach(t=>t.stop());recState.stream=null;}recState.recording=false;recBtn.classList.remove('hidden');stopBtn.classList.add('hidden');dumpEl.readOnly=false;}
async function transcribeViaRelay(blob){
const session=await fetch('/api/session.php').then(r=>r.json());
const m=(blob.type||'').split(';')[0];
const ext=m.indexOf('mp4')>=0?'m4a':(m.indexOf('ogg')>=0?'ogg':(m.indexOf('wav')>=0?'wav':'webm'));
const fd=new FormData();
fd.append('audio',blob,'sopbrain.'+ext);
fd.append('language','en');
fd.append('csrf',session.csrf||'');
const r=await fetch('/api/voice-transcribe.php',{method:'POST',body:fd});
let data={};try{data=await r.json();}catch(e){}
if(!r.ok)throw new Error(data.error||'Transcription failed.');
return data.transcript||'';
}
recBtn.addEventListener('click',async()=>{
if(recState.recording)return;
if(!(navigator.mediaDevices&&navigator.mediaDevices.getUserMedia)){statusEl.textContent='This browser cannot access the microphone.';statusEl.classList.add('error');return;}
try{recState.stream=await navigator.mediaDevices.getUserMedia({audio:true});}catch(e){statusEl.textContent='Microphone permission denied.';statusEl.classList.add('error');return;}
recState.chunks=[];
const mime=pickMime();
try{recState.recorder=new MediaRecorder(recState.stream,mime?{mimeType:mime}:undefined);}catch(e){cleanupRec();statusEl.textContent='Recording is not supported in this browser.';statusEl.classList.add('error');return;}
recState.recorder.ondataavailable=e=>{if(e.data&&e.data.size)recState.chunks.push(e.data);};
recState.recorder.onstop=onRecStop;
try{recState.recorder.start(250);}catch(e){cleanupRec();statusEl.textContent='Recording could not start.';statusEl.classList.add('error');return;}
recState.recording=true;
recBtn.classList.add('hidden');stopBtn.classList.remove('hidden');
dumpEl.readOnly=true;
statusEl.textContent='Recording. Ramble away, then hit stop.';statusEl.classList.remove('error');
bbTrack('recording_started',{tool:TOOL_KEY});
});
stopBtn.addEventListener('click',()=>{try{recState.recorder.stop();}catch(e){cleanupRec();}});
async function onRecStop(){
const mime=(recState.recorder&&recState.recorder.mimeType)||'audio/webm';
const blob=new Blob(recState.chunks,{type:mime});
cleanupRec();
if(!blob||!blob.size){statusEl.textContent='Nothing recorded. Try again.';statusEl.classList.add('error');return;}
statusEl.textContent='Transcribing your ramble...';statusEl.classList.remove('error');
try{
const t=await transcribeViaRelay(blob);
if(t){dumpEl.value=(dumpEl.value.trim()?dumpEl.value.trim()+'\n':'')+t;statusEl.textContent='Nice. Tweak anything I misheard, then hit the big button.';}
else{statusEl.textContent='Hmm, I did not catch any words. Try again or type instead.';statusEl.classList.add('error');}
}catch(e){statusEl.textContent='Transcription failed. Try typing instead.';statusEl.classList.add('error');}
}
const msgs=['Reading your ramble','Finding the actual order','Writing the steps','Adding the gotchas','Polishing the checklist'];
let msgTimer=0;
function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');}
goBtn.addEventListener('click',async()=>{
const dump=dumpEl.value.trim();
if(dump.length<20){statusEl.textContent='Give me a little more to work with, at least a few sentences.';statusEl.classList.add('error');dumpEl.focus();return;}
const key=genKey;genKey=crypto.randomUUID();
goBtn.disabled=true;statusEl.classList.remove('error');
let mi=0;statusEl.innerHTML='<span class="loading-dots">'+msgs[0]+'</span>';
msgTimer=setInterval(()=>{mi=(mi+1)%msgs.length;statusEl.innerHTML='<span class="loading-dots">'+msgs[mi]+'</span>';},2200);
try{
const session=await fetch('/api/session.php').then(r=>r.json());
const response=await fetch('/api/tools/sop-ify.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'generate',brainDump:dump,helperFor:whoEl.value.trim(),idempotencyKey:key,csrf:session.csrf})});
let data={};try{data=await response.json()}catch(e){}
clearInterval(msgTimer);
if(!response.ok){
if(response.status===402){bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});document.querySelector('#upgrade-slot').innerHTML=upgradeCard();bindUpgradeClicks(document.querySelector('#upgrade-slot'),'limit');statusEl.textContent='This playbook was not counted.';}
else{statusEl.textContent=data.error||'SOP-ify hiccup. Try again.';statusEl.classList.add('error');}
return;}
const b=data.playbook;
document.querySelector('#pbTitle').textContent=b.title;
document.querySelector('#pbPurpose').textContent=b.purpose;
const mw=document.querySelector('#pbMaterialsWrap');
if(b.materials&&b.materials.length){mw.style.display='';document.querySelector('#pbMaterials').innerHTML=b.materials.map(m=>'<li>'+esc(m)+'</li>').join('');}
else{mw.style.display='none';}
document.querySelector('#pbSteps').innerHTML=b.steps.map(s=>'<li><div><p class="pb-step-t">'+esc(s.step)+'</p><p class="pb-step-d">'+esc(s.detail)+'</p></div></li>').join('');
const tw=document.querySelector('#pbTipsWrap');
if(b.tips&&b.tips.length){tw.style.display='';document.querySelector('#pbTips').innerHTML=b.tips.map(t=>'<li>'+esc(t)+'</li>').join('');}
else{tw.style.display='none';}
document.querySelector('#pbCheck').innerHTML=b.checklist.map(c=>'<li><input type="checkbox"><span>'+esc(c)+'</span></li>').join('');
document.querySelectorAll('#pbCheck input').forEach(cb=>cb.addEventListener('change',()=>cb.closest('li').classList.toggle('done',cb.checked)));
resultEl.style.display='block';
resultEl.scrollIntoView({behavior:'smooth'});
statusEl.textContent='';
usageEl.textContent=data.usage.unlimited?'Unlimited actions.':data.usage.remaining+' actions left.';
bbTrack('action_completed',{tool:TOOL_KEY,used:data.usage.used,limit:data.usage.limit,remaining:data.usage.remaining});
window._sopBook=b;
}catch(e){clearInterval(msgTimer);statusEl.textContent='SOP-ify hiccup. Try again.';statusEl.classList.add('error');}
finally{goBtn.disabled=false;}
});
document.querySelector('#pbCopy').addEventListener('click',()=>{
const b=window._sopBook;if(!b)return;
let t=b.title+'\n\n'+b.purpose+'\n';
if(b.materials&&b.materials.length)t+='\nBefore you start:\n'+b.materials.map(m=>'- '+m).join('\n')+'\n';
t+='\nSteps:\n'+b.steps.map((s,i)=>(i+1)+'. '+s.step+(s.detail?'\n   '+s.detail:'')).join('\n');
if(b.tips&&b.tips.length)t+='\n\nPro tips:\n'+b.tips.map(x=>'- '+x).join('\n');
if(b.checklist&&b.checklist.length)t+='\n\nChecklist:\n'+b.checklist.map(x=>'[ ] '+x).join('\n');
navigator.clipboard.writeText(t).then(()=>{document.querySelector('#pbCopy').textContent='Copied!';setTimeout(()=>document.querySelector('#pbCopy').textContent='Copy playbook',1500);});
});
document.querySelector('#pbPrint').addEventListener('click',()=>window.print());
document.querySelector('#pbAgain').addEventListener('click',()=>{resultEl.style.display='none';formEl.scrollIntoView({behavior:'smooth'});dumpEl.focus();});
<?php endif; ?>
</script><?php endif; ?><section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Is SOP-ify free?</summary>
<p>You get 15 free actions with no signup. Create a free account and you get 75 actions every month. <a href="/pricing/">See pricing</a>. One finished playbook uses one action.</p>
</details>
<details>
<summary>Do I need an account?</summary>
<p>No. Start as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>How detailed is the playbook?</summary>
<p>Step by step, written so a brand-new helper could follow it with zero prior context.</p>
</details>
<details>
<summary>Can I talk instead of typing?</summary>
<p>Yes. Record a voice note and it gets transcribed automatically.</p>
</details>
<details>
<summary>What do I do with the playbook?</summary>
<p>Hand it to a helper, save it as your process doc, or use it to train the next person.</p>
</details>
</section>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
