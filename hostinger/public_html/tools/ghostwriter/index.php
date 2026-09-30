<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Ghostwriter | Leave It to Bum Bum</title>
<meta name="description" content="Turn voice rambles into content packs: 3 hooks, a 60-second script, and a caption. Free to try, no account needed.">
<meta property="og:title" content="Ghostwriter | Leave It to Bum Bum">
<meta property="og:description" content="Turn voice rambles into content packs: 3 hooks, a 60-second script, and a caption. Free to try, no account needed.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/ghostwriter/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/favicon-cat.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Ghostwriter | Leave It to Bum Bum">
<meta name="twitter:description" content="Turn voice rambles into content packs: 3 hooks, a 60-second script, and a caption. Free to try, no account needed.">
<link rel="canonical" href="https://leaveittobumbum.com/tools/ghostwriter/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Ghostwriter",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Turn voice rambles into content packs: 3 hooks, a 60-second script, and a caption. Free to try, no account needed.",
  "url": "https://leaveittobumbum.com/tools/ghostwriter/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Is Ghostwriter free?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You get 15 free actions with no signup. One finished content pack uses one action."
      }
    },
    {
      "@type": "Question",
      "name": "Do I need an account?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Record as a guest with 15 free actions."
      }
    },
    {
      "@type": "Question",
      "name": "What happens to my audio?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "It is only raw material. Your audio is transcribed and never kept."
      }
    },
    {
      "@type": "Question",
      "name": "What do I get in a content pack?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Three hooks testing different angles, a 60-second script, and a caption with hashtags, ready to post."
      }
    },
    {
      "@type": "Question",
      "name": "Which languages can I ramble in?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "English plus Spanish, French, German, Italian, Portuguese, Chinese, Cantonese, Japanese, and Korean."
      }
    }
  ]
}
</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.notes-row{display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin-top:8px}
.notes-row .button{margin-top:0}
#gwRec{font-size:20px;padding:16px 30px}
.notes-timer{font-size:34px;font-weight:900}
.notes-status{font-weight:700;margin:14px 0 0}
#gwMeter{width:100%;height:56px;display:block;margin-top:16px;border:2px solid var(--line);border-radius:10px;background:#fff}
#gwText{min-height:150px}
#gwGen{font-size:18px;padding:14px 26px;margin-top:12px}
.linklike{background:none;border:0;padding:0;margin-top:14px;color:var(--accent,#b3541e);font:inherit;font-weight:700;cursor:pointer;text-decoration:underline}
select{width:100%;padding:14px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff}
#gwPack .hook{border:2px solid var(--line);border-radius:10px;padding:14px 16px;margin:0 0 12px;background:#fff}
#gwPack .hook .rank{font-size:12px;font-weight:900;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}
#gwPack .hook .theme{display:inline-block;font-size:11px;font-weight:800;letter-spacing:.05em;text-transform:uppercase;background:#333;color:#fff;border-radius:999px;padding:3px 10px;margin-left:8px;vertical-align:2px}
#gwPack .hook p{margin:6px 0 10px;font-size:18px;font-weight:800;line-height:1.35}
#gwPack .script-card{border:2px solid var(--line);border-radius:10px;padding:16px;background:#fff;margin:0 0 12px}
#gwPack .script-card p{margin:6px 0 10px;line-height:1.65;white-space:pre-wrap}
#gwPack .cap{font-size:15px}
#gwPack .tags{color:var(--muted);font-size:14px}
.mini{padding:8px 14px;font-size:14px}
.hidden{display:none!important}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Ghostwriter"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-excited-v2.png" alt="Bum Bum feeling inspired"><h1>You ramble. It writes.</h1><p class="lede">Hit record and talk for a minute about your day, your work, whatever is on your mind. Bum Bum turns it into three hooks (each testing a different angle), a 60-second script, and a caption ready to post. Your audio is only raw material, it is never kept. One finished pack uses one action.</p>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to use Ghostwriter</h2><a class="button" href="/account/?next=<?= urlencode('/tools/ghostwriter/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/ghostwriter/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/ghostwriter/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/ghostwriter/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php $out = empty($usage['unlimited']) && ($usage['remaining'] ?? 0) <= 0; ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<?php if ($out): ?>
<div id="upgrade-slot"></div>
<?php else: ?>
<section class="panel" id="recorder">
<div id="gwRecordUI">
<div class="notes-row"><button id="gwRec" class="button" type="button">Record</button><button id="gwStop" class="button secondary" type="button" disabled>Stop</button><span id="gwTimer" class="notes-timer">00:00</span></div>
<canvas id="gwMeter" width="640" height="56" aria-hidden="true"></canvas>
<p id="gwStatus" class="notes-status">Ready when you are. Sixty seconds of rambling is plenty.</p>
<label>Transcription language<select id="gwLang"><option value="en-US" selected>English (US)</option><option value="en-GB">English (UK)</option><option value="es-ES">Español</option><option value="fr-FR">Français</option><option value="de-DE">Deutsch</option><option value="it-IT">Italiano</option><option value="pt-BR">Português (BR)</option><option value="zh-CN">中文 (简体)</option><option value="zh-TW">中文 (繁體)</option><option value="yue-Hant-HK">粵語 (香港)</option><option value="ja-JP">日本語</option><option value="ko-KR">한국어</option></select></label>
<label>Tone of voice<select id="gwTone"><option value="professional" selected>Professional</option><option value="friendly">Friendly</option><option value="playful">Playful</option><option value="bold">Bold</option></select></label>
<button id="gwPasteToggle" class="linklike" type="button">or paste text instead</button>
</div>
<div id="gwPasteUI" class="hidden">
<label>Your ramble<textarea id="gwPasteText" rows="6" placeholder="Paste what you would have said out loud..."></textarea></label>
<button id="gwBackToRec" class="linklike" type="button">back to recording</button>
</div>
<p class="eyebrow" style="margin-top:20px">What you said <span id="gwSrHint"></span></p>
<textarea id="gwText" rows="6" readonly placeholder="Your words will appear here while you record..."></textarea>
<div class="notes-row"><button id="gwGen" class="button" type="button" disabled>Write my content</button><button id="gwAgain" class="button secondary" type="button">Start over</button></div>
<p id="gwUsage"></p>
</section>
<section class="panel hidden" id="gwPack"><h2 style="margin-top:0">Your content pack</h2><div id="gwPackBody"></div></section>
<section class="panel" id="gwLibrary"><h2 style="margin-top:0">Your packs</h2><div id="gwList"><p class="lede">Loading...</p></div></section>
<div id="upgrade-slot"></div>
<?php endif; ?>
<script>
const TOOL_KEY='ghostwriter';
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
const recBtn=document.querySelector('#gwRec');
const stopBtn=document.querySelector('#gwStop');
const timerEl=document.querySelector('#gwTimer');
const statusEl=document.querySelector('#gwStatus');
const srHint=document.querySelector('#gwSrHint');
const langSel=document.querySelector('#gwLang');
const toneSel=document.querySelector('#gwTone');
const meterCanvas=document.querySelector('#gwMeter');
const textEl=document.querySelector('#gwText');
const usageEl=document.querySelector('#gwUsage');
const genBtn=document.querySelector('#gwGen');
const againBtn=document.querySelector('#gwAgain');
const packSec=document.querySelector('#gwPack');
const packBody=document.querySelector('#gwPackBody');
const pasteToggle=document.querySelector('#gwPasteToggle');
const backToRec=document.querySelector('#gwBackToRec');
const recordUI=document.querySelector('#gwRecordUI');
const pasteUI=document.querySelector('#gwPasteUI');
const pasteText=document.querySelector('#gwPasteText');
const SR=window.SpeechRecognition||window.webkitSpeechRecognition;
let recording=false,micStream=null,recorder=null,chunks=[],audioCtx=null,analyser=null,meterRAF=0,timerInt=0,startTs=0,recog=null,finalTranscript='',srBlocked=false,srFailed=false,genKey=crypto.randomUUID(),currentEntryId=null,pasteMode=false;
if(!SR){srHint.textContent='On iPhone your recording is transcribed on our server after you stop.';}
function fmtTime(sec){sec=Math.max(0,Math.floor(sec));return String(Math.floor(sec/60)).padStart(2,'0')+':'+String(sec%60).padStart(2,'0');}
function pickAudioMime(){const c=['audio/webm;codecs=opus','audio/webm','audio/mp4','audio/ogg;codecs=opus'];for(const t of c){if(window.MediaRecorder&&MediaRecorder.isTypeSupported(t))return t;}return '';}
function setStatus(msg,isError){statusEl.textContent=msg;statusEl.classList.toggle('error',!!isError);}
function setGen(on){genBtn.disabled=!on;}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/ghostwriter.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data};}
function drawMeter(){const g=meterCanvas.getContext('2d');const W=meterCanvas.width,H=meterCanvas.height;const data=new Uint8Array(analyser.frequencyBinCount);(function frame(){if(!recording)return;meterRAF=requestAnimationFrame(frame);analyser.getByteFrequencyData(data);g.clearRect(0,0,W,H);const bars=48,bw=W/bars;for(let i=0;i<bars;i++){const v=data[Math.floor(i*data.length/bars)]/255;const h=Math.max(3,v*H);g.fillStyle='#ff7448';g.globalAlpha=0.3+v*0.7;g.fillRect(i*bw+bw*0.2,(H-h)/2,bw*0.6,h);}g.globalAlpha=1;})();}
function clearMeter(){const g=meterCanvas.getContext('2d');g.clearRect(0,0,meterCanvas.width,meterCanvas.height);}
function startRecognition(){if(!SR)return;srHint.textContent='Live: '+langSel.options[langSel.selectedIndex].text;const r=new SR();r.lang=langSel.value;r.continuous=true;r.interimResults=true;r.onresult=e=>{let interim='';for(let i=e.resultIndex;i<e.results.length;i++){const res=e.results[i];const txt=res[0].transcript;if(res.isFinal){finalTranscript+=(finalTranscript&&!/\s$/.test(finalTranscript)?' ':'')+txt.trim()+' ';}else{interim+=txt;}}textEl.value=finalTranscript+interim;textEl.scrollTop=textEl.scrollHeight;};r.onerror=e=>{const err=e.error||'unknown';if(err==='not-allowed'||err==='service-not-allowed'){srBlocked=true;srHint.textContent='Transcription was blocked. Check the microphone permission.';}else if(err!=='no-speech'&&err!=='aborted'){srFailed=true;setStatus('Live transcription dropped out ('+err+'). Keep talking, I will transcribe from the recording when you stop.');}};r.onend=()=>{if(recording){try{r.start();}catch(_){}}};recog=r;try{r.start();}catch(_){}}
function cleanupAudio(){if(micStream){micStream.getTracks().forEach(t=>t.stop());micStream=null;}if(audioCtx){audioCtx.close().catch(()=>{});audioCtx=null;analyser=null;}}
async function startRecording(){if(recording||pasteMode)return;if(!(navigator.mediaDevices&&navigator.mediaDevices.getUserMedia)){setStatus('This browser cannot access the microphone.',true);return;}try{micStream=await navigator.mediaDevices.getUserMedia({audio:true});}catch(e){setStatus('Microphone permission denied.',true);return;}try{audioCtx=new (window.AudioContext||window.webkitAudioContext)();analyser=audioCtx.createAnalyser();analyser.fftSize=256;audioCtx.createMediaStreamSource(micStream).connect(analyser);}catch(e){analyser=null;}chunks=[];const mime=pickAudioMime();try{recorder=new MediaRecorder(micStream,mime?{mimeType:mime}:undefined);}catch(e){cleanupAudio();setStatus('Recording is not supported in this browser.',true);return;}recorder.ondataavailable=e=>{if(e.data&&e.data.size)chunks.push(e.data);};recorder.onstop=onVoiceStop;finalTranscript='';srBlocked=false;srFailed=false;textEl.value='';textEl.readOnly=true;setGen(false);usageEl.textContent='';packSec.classList.add('hidden');startRecognition();try{recorder.start(250);}catch(e){cleanupAudio();if(recog){try{recog.onend=null;recog.stop();}catch(_){}recog=null;}recBtn.disabled=false;setStatus('Recording could not start in this browser.',true);return;}recording=true;startTs=Date.now();if(analyser)drawMeter();timerInt=setInterval(()=>{timerEl.textContent=fmtTime((Date.now()-startTs)/1000);},500);timerEl.textContent='00:00';recBtn.disabled=true;stopBtn.disabled=false;langSel.disabled=true;setStatus(SR?'Recording. Ramble away.':'Recording. Your words will be transcribed when you stop.');bbTrack('recording_started',{tool:TOOL_KEY});}
function stopRecording(){if(!recording)return;recording=false;clearInterval(timerInt);cancelAnimationFrame(meterRAF);if(recog){try{recog.onend=null;recog.stop();}catch(e){}recog=null;}try{recorder.stop();}catch(e){onVoiceStop();}recBtn.disabled=false;stopBtn.disabled=true;langSel.disabled=false;setStatus('Wrapping up...');}
async function transcribeViaRelay(blob){const session=await fetch('/api/session.php').then(r=>r.json());const m=(blob.type||'').split(';')[0];const ext=m.indexOf('mp4')>=0?'m4a':(m.indexOf('ogg')>=0?'ogg':(m.indexOf('wav')>=0?'wav':'webm'));const fd=new FormData();fd.append('audio',blob,'ramble.'+ext);fd.append('language',langSel.value);fd.append('csrf',session.csrf||'');const r=await fetch('/api/voice-transcribe.php',{method:'POST',body:fd});let data={};try{data=await r.json();}catch(e){}if(!r.ok)throw new Error(data.error||'Transcription failed.');return data.transcript||'';}
async function onVoiceStop(){const mime=(recorder&&recorder.mimeType)||'audio/webm';const audioBlob=new Blob(chunks,{type:mime});cleanupAudio();const liveText=textEl.value.trim();if((!SR||srBlocked||srFailed||!liveText)&&audioBlob&&audioBlob.size>0){setStatus('Transcribing your ramble...');try{textEl.value=await transcribeViaRelay(audioBlob);}catch(e){if(!liveText){textEl.readOnly=false;setStatus('Transcription failed ('+e.message+'). Try typing or pasting your ramble instead.',true);return;}}}const text=textEl.value.trim();textEl.readOnly=false;setGen(text.length>=20);setStatus(text?'Nice. Tweak anything I misheard, then hit the big button.':'Hmm, I did not catch any words. Try again or paste text instead.');}
pasteToggle.addEventListener('click',()=>{if(recording)return;pasteMode=true;recordUI.classList.add('hidden');pasteUI.classList.remove('hidden');textEl.value='';textEl.readOnly=false;setGen(false);packSec.classList.add('hidden');setStatus('Paste your ramble below, then hit the big button.');pasteText.focus();});
backToRec.addEventListener('click',()=>{pasteMode=false;pasteUI.classList.add('hidden');recordUI.classList.remove('hidden');pasteText.value='';textEl.value='';textEl.readOnly=true;setGen(false);setStatus('Ready when you are. Sixty seconds of rambling is plenty.');});
pasteText.addEventListener('input',()=>{textEl.value=pasteText.value;setGen(pasteText.value.trim().length>=20);});
textEl.addEventListener('input',()=>{if(pasteMode){pasteText.value=textEl.value;}setGen(textEl.value.trim().length>=20);});
againBtn.addEventListener('click',()=>{if(recording)stopRecording();pasteMode=false;pasteUI.classList.add('hidden');recordUI.classList.remove('hidden');pasteText.value='';textEl.value='';textEl.readOnly=true;finalTranscript='';currentEntryId=null;genKey=crypto.randomUUID();setGen(false);packSec.classList.add('hidden');usageEl.textContent='';timerEl.textContent='00:00';clearMeter();setStatus('Ready when you are. Sixty seconds of rambling is plenty.');recBtn.focus();});
function copyText(t,btn){const done=()=>{const old=btn.textContent;btn.textContent='Copied!';setTimeout(()=>{btn.textContent=old;},1500);};if(navigator.clipboard&&navigator.clipboard.writeText){navigator.clipboard.writeText(t).then(()=>{done();bbTrack('pack_copied',{tool:TOOL_KEY});}).catch(()=>{fallbackCopy(t)?done():null;});}else{if(fallbackCopy(t))done();}}
function fallbackCopy(t){textEl.value=t;textEl.select();try{return document.execCommand('copy');}catch(_){return false;}}
function hookParts(h){if(typeof h==='string')return{hook:h,theme:''};return{hook:String(h.hook||''),theme:String(h.theme||'')}}
function renderPack(pack){const hooks=(pack.hooks||[]).map((h,i)=>{const{hook,theme}=hookParts(h);return `<div class="hook"><span class="rank">Hook ${i+1}${i===0?' · strongest':''}</span>${theme?`<span class="theme">${esc(theme)}</span>`:''}<p>${esc(hook)}</p><button type="button" class="button secondary mini" data-copy="${esc(hook)}">Copy hook</button></div>`}).join('');
const tags=((pack.hashtags||[]).join(' '));
packBody.innerHTML=`${hooks}
<div class="script-card"><span class="rank" style="font-size:12px;font-weight:900;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)">Your 60-second script</span><p>${esc(pack.script||'')}</p><button type="button" class="button secondary mini" data-copy="${esc(pack.script||'')}">Copy script</button></div>
<div class="script-card"><span class="rank" style="font-size:12px;font-weight:900;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)">Caption</span><p class="cap">${esc(pack.caption||'')}</p><button type="button" class="button secondary mini" data-copy="${esc((pack.caption||'')+'\n'+tags)}">Copy caption + hashtags</button><p class="tags">${esc(tags)}</p></div>`;
packBody.querySelectorAll('[data-copy]').forEach(b=>b.addEventListener('click',()=>copyText(b.getAttribute('data-copy'),b)));
packSec.classList.remove('hidden');packSec.scrollIntoView({behavior:'smooth',block:'nearest'});}
genBtn.addEventListener('click',async()=>{
const text=textEl.value.trim();
if(text.length<20){setStatus('Give me a little more to work with, at least a sentence or two.',true);return;}
const key=genKey;genKey=crypto.randomUUID();
genBtn.disabled=true;setStatus('Ghostwriting your content. This takes a few seconds...');
try{
const{response,data}=await apiCall({action:'generate',transcript:text,idempotencyKey:key,tone:toneSel.value});
if(!response.ok){
if(response.status===402){bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});document.querySelector('#upgrade-slot').innerHTML=upgradeCard();bindUpgradeClicks(document.querySelector('#upgrade-slot'),'limit');usageEl.textContent='This pack was not counted. Your words are safe below.';}
else{setStatus('Ghostwriter hiccup ('+(data.error||'hmm')+'). Your words are safe below, try again.',true);}
return;}
bbTrack('action_completed',{tool:TOOL_KEY,used:data.usage.used,limit:data.usage.limit,remaining:data.usage.remaining,chars:text.length});
currentEntryId=data.entry&&data.entry.id;
renderPack(data.pack);
setStatus('Done. Steal the hooks, tweak the script, post it.');
usageEl.textContent=data.usage.unlimited?'Unlimited actions.':data.usage.remaining+' actions left.';
loadPacks();
}catch(e){setStatus('Ghostwriter hiccup. Your words are safe below, try again.',true);}
finally{setGen(true);}
});
async function loadPacks(){const listEl=document.querySelector('#gwList');if(!listEl)return;const{response,data}=await apiCall({action:'list'});if(!response.ok){listEl.innerHTML='<p class="error">Could not load your packs.</p>';return}const entries=data.entries||[];if(!entries.length){listEl.innerHTML='<p class="lede" style="margin:0">No packs yet. Your finished content packs will land here.</p>';return}listEl.innerHTML=entries.map(e=>'<div class="req" data-id="'+e.id+'"><div style="display:flex;justify-content:space-between;gap:12px;align-items:start;flex-wrap:wrap"><div><div style="font-weight:900">'+esc(e.title)+'</div><div style="color:var(--muted);font-size:14px">'+esc(e.created_at)+'</div><div style="margin-top:6px">'+esc(e.preview)+(e.preview.length>=140?'...':'')+'</div></div><div style="white-space:nowrap"><button type="button" class="secondary" data-open style="margin-top:0">Open</button> <button type="button" class="secondary" data-del style="margin-top:0">Delete</button></div></div></div>').join('')}
document.querySelector('#gwList').addEventListener('click',async event=>{const btn=event.target.closest('[data-open],[data-del]');if(!btn)return;const row=event.target.closest('[data-id]');const id=row.dataset.id;if(btn.hasAttribute('data-open')){const{response,data}=await apiCall({action:'get',id});if(!response.ok){alert(data.error||'Could not open that pack.');return}textEl.value=data.entry.transcript;textEl.readOnly=false;setGen(true);currentEntryId=data.entry.id;renderPack(data.entry.pack);setStatus('Opened from your library.');}else{if(!confirm('Delete this pack? This cannot be undone.'))return;const{response,data}=await apiCall({action:'delete',id});if(!response.ok){alert(data.error||'Could not delete.');return}if(String(currentEntryId)===String(id)){currentEntryId=null;}loadPacks();}});
recBtn.addEventListener('click',startRecording);
stopBtn.addEventListener('click',stopRecording);
loadPacks();
<?php endif; ?>
</script><?php endif; ?><section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<h3>Is Ghostwriter free?</h3>
<p>You get 15 free actions with no signup. One finished content pack uses one action.</p>
<h3>Do I need an account?</h3>
<p>No. Record as a guest with 15 free actions.</p>
<h3>What happens to my audio?</h3>
<p>It is only raw material. Your audio is transcribed and never kept.</p>
<h3>What do I get in a content pack?</h3>
<p>Three hooks testing different angles, a 60-second script, and a caption with hashtags, ready to post.</p>
<h3>Which languages can I ramble in?</h3>
<p>English plus Spanish, French, German, Italian, Portuguese, Chinese, Cantonese, Japanese, and Korean.</p>
</section>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
