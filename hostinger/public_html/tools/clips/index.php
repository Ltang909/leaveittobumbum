<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $user = currentUser(); $usage = $user ? usageFor($user) : null; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Bum Bum Clips | Leave It to Bum Bum</title>
<link rel="stylesheet" href="/app.css">
<?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?>
</head>
<body>
<header class="shell"><a class="brand" href="/">BB · Leave It to Bum Bum</a><span><?php require dirname(__DIR__, 2) . '/includes/meter.php'; ?> <a href="/account/">Account</a></span></header>
<main class="shell">
<p class="eyebrow">Bum Bum's toolbox</p>
<h1>Show them instead of telling them</h1>
<p class="lede">Bum Bum Clips records your screen right in your browser. Pick a tab, a window, or your whole screen, add your mic if you want to narrate, and download the clip when you are done. Nothing uploads anywhere, your video never leaves your computer. One finished recording uses one action.</p>
<?php if (!$user): ?>
<section class="panel"><h2>Sign in to use Bum Bum Clips</h2><a class="button" href="/account/?next=<?= urlencode('/tools/clips/') ?>">Sign in or create an account</a></section>
<?php else: ?>
<?php $low = $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<?php if ($usage && $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) ($usage['limit'] ?? 75) ?> free actions this month. Helper gives you 1,500 actions for $12/month. Operator gives you 6,000 actions plus a custom tool built for you in 36 hours for $49/month.</p><p><a class="button" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" href="/checkout/?plan=operator">Get Operator, $49/mo</a></p><p><a href="/account/">See your usage</a></p></div>
<?php else: ?>
<section class="panel hidden" id="no-screen">
<h2>Clips needs a desktop browser</h2>
<p class="lede">Phones cannot share their screen with a browser, so recording only works on a laptop or desktop. Hop on your computer (Chrome or Edge work great) and let's make a clip.</p>
</section>
<section class="panel" id="recorder">
<p><label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer"><input type="checkbox" id="micToggle" checked style="width:auto;margin:0"> Include my microphone</label></p>
<p id="micNote" class="hidden">Mic unavailable, recording screen audio only.</p>
<button id="startBtn">Start recording</button>
<div id="recordingView" class="hidden">
<p class="lede">Recording <strong id="timer">00:00</strong></p>
<button id="stopBtn">Stop</button>
</div>
<p id="clipError" class="error"></p>
</section>
<section class="panel hidden" id="doneView">
<h2>Your clip is ready</h2>
<video id="preview" controls playsinline style="width:100%;border-radius:12px"></video>
<p><a id="downloadBtn" class="button" href="#" download>Download clip</a> <button id="againBtn" class="secondary">Record another</button></p>
<p id="usageLine"></p>
</section>
<div id="upgrade-slot"></div>
<script>
const TOOL_KEY='clips';
const PERIOD=<?= json_encode($usage['period'] ?? '') ?>;
const LIMIT=<?= (int) ($usage['limit'] ?? 75) ?>;
if(<?= $low ? 'true' : 'false' ?>){const seen='bb_m80_'+PERIOD;if(!localStorage.getItem(seen)){localStorage.setItem(seen,'1');bbTrack('usage_milestone_80',{tool:TOOL_KEY,used:<?= (int) ($usage['used'] ?? 0) ?>,limit:LIMIT})}}
function upgradeCard(){return `<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all ${LIMIT} free actions this month. Helper gives you 1,500 actions for $12/month. Operator gives you 6,000 actions plus a custom tool built for you in 36 hours for $49/month.</p><p><a class="button" data-plan="helper" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" data-plan="operator" href="/checkout/?plan=operator">Get Operator, $49/mo</a></p><p><a href="/account/">See your usage</a></p></div>`}
function showUpgrade(){bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});document.querySelector('#upgrade-slot').innerHTML=upgradeCard();document.querySelectorAll('#upgrade-slot [data-plan]').forEach(a=>a.addEventListener('click',()=>bbTrack('upgrade_clicked',{plan:a.dataset.plan,context:'limit',tool:TOOL_KEY})))}
function fmt(ms){const s=Math.floor(ms/1000);const h=Math.floor(s/3600);const m=Math.floor(s%3600/60);const sec=s%60;const p=n=>String(n).padStart(2,'0');return (h?p(h)+':':'')+p(m)+':'+p(sec)}
const screenOK=!!(navigator.mediaDevices&&navigator.mediaDevices.getDisplayMedia);
if(!screenOK){
  document.querySelector('#recorder').classList.add('hidden');
  document.querySelector('#no-screen').classList.remove('hidden');
}else{
  const startBtn=document.querySelector('#startBtn');
  const stopBtn=document.querySelector('#stopBtn');
  const timerEl=document.querySelector('#timer');
  const errorEl=document.querySelector('#clipError');
  const micToggle=document.querySelector('#micToggle');
  const micNote=document.querySelector('#micNote');
  const recordingView=document.querySelector('#recordingView');
  const recorderSection=document.querySelector('#recorder');
  const doneView=document.querySelector('#doneView');
  const preview=document.querySelector('#preview');
  const downloadBtn=document.querySelector('#downloadBtn');
  const againBtn=document.querySelector('#againBtn');
  const usageLine=document.querySelector('#usageLine');
  let screenStream=null,micStream=null,recorder=null,chunks=[],startTime=0,timerInt=null,mime='',blobUrl='',attempt=crypto.randomUUID();
  function pickMime(){const c=['video/webm;codecs=vp9,opus','video/webm;codecs=vp8,opus','video/webm','video/mp4;codecs=avc1.42E01E,mp4a.40.2','video/mp4'];for(const t of c){if(window.MediaRecorder&&MediaRecorder.isTypeSupported(t))return t}return ''}
  function setError(m){errorEl.textContent=m}
  function stopTracks(){[screenStream,micStream].forEach(s=>{if(s)s.getTracks().forEach(t=>t.stop())});screenStream=null;micStream=null}
  function doStop(){if(recorder&&recorder.state!=='inactive')recorder.stop()}
  startBtn.addEventListener('click',async()=>{
    setError('');micNote.classList.add('hidden');
    let ss;
    try{ss=await navigator.mediaDevices.getDisplayMedia({video:true,audio:true})}
    catch(err){const n=err&&err.name?err.name:'UnknownError';setError(n==='NotAllowedError'?'Screen sharing was blocked or cancelled. No worries, try again when you are ready.':'Could not start screen capture ('+n+'). Try Chrome or Edge on a desktop.');return}
    screenStream=ss;
    let ms=null;
    if(micToggle.checked){
      try{ms=await navigator.mediaDevices.getUserMedia({audio:true})}
      catch(err){ms=null;micNote.classList.remove('hidden')}
    }
    micStream=ms;
    const tracks=[...screenStream.getVideoTracks(),...screenStream.getAudioTracks()];
    if(micStream)tracks.push(...micStream.getAudioTracks());
    mime=pickMime();
    if(!mime){setError('This browser cannot record video. Try Chrome or Edge.');stopTracks();return}
    chunks=[];
    recorder=new MediaRecorder(new MediaStream(tracks),{mimeType:mime});
    recorder.ondataavailable=e=>{if(e.data&&e.data.size)chunks.push(e.data)};
    recorder.onstop=onStop;
    try{recorder.start(250)}catch(err){setError('Recording could not start. Please try again.');stopTracks();return}
    startTime=Date.now();
    timerEl.textContent='00:00';
    timerInt=setInterval(()=>{timerEl.textContent=fmt(Date.now()-startTime)},250);
    const vt=screenStream.getVideoTracks()[0];
    if(vt)vt.addEventListener('ended',()=>{if(recorder&&recorder.state!=='inactive')doStop()});
    startBtn.disabled=true;micToggle.disabled=true;
    recordingView.classList.remove('hidden');
    bbTrack('recording_started',{tool:TOOL_KEY});
  });
  stopBtn.addEventListener('click',doStop);
  function onStop(){
    clearInterval(timerInt);timerInt=null;
    const dur=Math.max(1,Math.round((Date.now()-startTime)/1000));
    stopTracks();
    const type=(mime.split(';')[0]||'video/webm');
    const blob=new Blob(chunks,{type});
    if(blobUrl)URL.revokeObjectURL(blobUrl);
    blobUrl=URL.createObjectURL(blob);
    preview.src=blobUrl;
    const ext=type.indexOf('mp4')>=0?'mp4':'webm';
    const d=new Date(),p=n=>String(n).padStart(2,'0');
    downloadBtn.href=blobUrl;
    downloadBtn.setAttribute('download','bum-bum-clip-'+d.getFullYear()+p(d.getMonth()+1)+p(d.getDate())+'-'+p(d.getHours())+p(d.getMinutes())+'.'+ext);
    recorderSection.classList.add('hidden');
    recordingView.classList.add('hidden');
    doneView.classList.remove('hidden');
    startBtn.disabled=false;micToggle.disabled=false;
    bbTrack('recording_finished',{tool:TOOL_KEY,duration_seconds:dur});
    meter(dur);
  }
  async function meter(dur){
    try{
      const session=await fetch('/api/session.php').then(r=>r.json());
      const response=await fetch('/api/tools/clips.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({csrf:session.csrf,idempotencyKey:attempt,durationSeconds:dur})});
      const data=await response.json();
      if(!response.ok){
        if(response.status===402){showUpgrade()}
        else{usageLine.textContent='We could not count this one, but your clip is still yours. Download away.'}
        return;
      }
      attempt=crypto.randomUUID();
      bbTrack('action_completed',{tool:TOOL_KEY,used:data.usage.used,limit:data.usage.limit,remaining:data.usage.remaining});
      usageLine.textContent=data.usage.remaining+' actions remaining this month.';
    }catch(err){usageLine.textContent='We could not count this one, but your clip is still yours. Download away.'}
  }
  againBtn.addEventListener('click',()=>{
    if(blobUrl){URL.revokeObjectURL(blobUrl);blobUrl=''}
    preview.removeAttribute('src');preview.load();
    attempt=crypto.randomUUID();
    doneView.classList.add('hidden');
    document.querySelector('#upgrade-slot').innerHTML='';
    usageLine.textContent='';setError('');timerEl.textContent='00:00';
    recorderSection.classList.remove('hidden');
  });
}
</script>
<?php endif; ?>
<?php endif; ?>
</main>
</body>
</html>
