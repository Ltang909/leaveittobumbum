<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $user = currentUser(); $usage = $user ? usageFor(billingUser($user)) : null; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no">
<title>Bum Bum Clips | Leave It to Bum Bum</title>
<link rel="stylesheet" href="/app.css?v=5"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><style>h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}</style>
<?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?>
</head>
<body>
<?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?>
<main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Bum Bum Clips"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?>
<p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-sunglasses.png" alt="Bum Bum looking cool">
<h1>Show them instead of telling them</h1>
<p class="lede">Bum Bum Clips records your screen right in your browser. Pick a tab, a window, or your whole screen, add your mic and a little camera bubble if you want to be in it, then trim and tweak the speed before you download. Nothing uploads anywhere, your video never leaves your computer. One finished recording uses one action.</p>
<?php if (!$user): ?>
<section class="panel"><h2>Sign in to use Bum Bum Clips</h2><a class="button" href="/account/?next=<?= urlencode('/tools/clips/') ?>">Sign in or create an account</a></section>
<?php else: ?>
<?php $low = $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<?php if ($usage && empty($usage['unlimited']) && $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) ($usage['limit'] ?? 75) ?> free actions this month. Helper gives you 1,500 actions for $12/month. Operator gives you 6,000 actions plus a custom tool built for you in 36 hours for $49/month.</p><p><a class="button" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" href="/checkout/?plan=operator">Get Operator, $49/mo</a></p><p><a href="/account/">See your usage</a></p></div>
<?php else: ?>
<section class="panel hidden" id="no-screen">
<h2>Clips needs a desktop browser</h2>
<p class="lede">Phones cannot share their screen with a browser, so recording only works on a laptop or desktop. Hop on your computer (Chrome or Edge work great) and let's make a clip.</p>
</section>
<section class="panel" id="recorder">
<p><label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer"><input type="checkbox" id="micToggle" checked style="width:auto;margin:0"> Include my microphone</label></p>
<p id="micNote" class="hidden">Mic unavailable, recording screen audio only.</p>
<p style="font-size:14px;opacity:.75">Tip: your computer's own sound only comes through when you share a browser <strong>tab</strong> and tick "Share tab audio" in the picker. Window and full-screen shares cannot carry system sound on most computers.</p>
<p><label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer"><input type="checkbox" id="camToggle" style="width:auto;margin:0"> Include my camera (floating bubble)</label></p>
<p id="camNote" class="hidden">Camera unavailable, recording screen only.</p>
<p id="camHint" class="hidden" style="font-size:14px;opacity:.75">While you record, your bubble floats on screen so you can see exactly where it lands.</p>
<button id="startBtn">Start recording</button>
<div id="recordingView" class="hidden">
<p class="lede">Recording <strong id="timer">00:00</strong></p>
<p id="audioWarn" class="hidden" style="font-weight:700">No audio is being captured. To get sound, stop and share a browser tab with "Share tab audio" ticked, and keep "Include my microphone" checked.</p>
<button id="stopBtn">Stop</button>
</div>
<p id="clipError" class="error"></p>
</section>
<section class="panel hidden" id="doneView">
<h2>Your clip is ready</h2>
<video id="preview" controls playsinline style="width:100%;border-radius:12px"></video>
<p><a id="downloadBtn" class="button" href="#" download>Download clip</a> <button id="againBtn" class="secondary">Record another</button></p>
<section id="editSection" style="margin-top:24px;border-top:2px solid var(--line);padding-top:20px">
<h3 style="margin-top:0">Edit this clip</h3>
<p class="lede" id="editNote">Trim the boring bits and play with the speed. Editing is free and never uses an action.</p>
<div id="editBody">
<div><label for="trimStart" style="margin-bottom:2px">Start: <span id="trimStartTime">00:00</span></label><input type="range" id="trimStart" min="0" max="0" step="0.1" value="0" style="width:100%;padding:0;border:none;background:transparent"></div>
<div><label for="trimEnd" style="margin-bottom:2px">End: <span id="trimEndTime">00:00</span></label><input type="range" id="trimEnd" min="0" max="0" step="0.1" value="0" style="width:100%;padding:0;border:none;background:transparent"></div>
<p class="eyebrow" style="margin-top:20px">Speed</p>
<p id="speedPicker"><button type="button" class="secondary" data-speed="0.5">0.5x</button> <button type="button" data-speed="1">1x</button> <button type="button" class="secondary" data-speed="1.5">1.5x</button> <button type="button" class="secondary" data-speed="2">2x</button></p>
<button id="editBtn">Make edited clip</button>
<p id="editStatus" class="error"></p>
<div id="editProgress" class="hidden"><div class="meter"><span id="editFill" style="width:0%"></span></div><p id="editText" style="font-weight:700">0%</p></div>
<div id="editResult" class="hidden" style="margin-top:16px">
<video id="editPreview" controls playsinline style="width:100%;border-radius:12px"></video>
<p><a id="editDownload" class="button" href="#" download>Download edited clip</a></p>
</div>
</div>
</section>
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
  const camToggle=document.querySelector('#camToggle');
  const camNote=document.querySelector('#camNote');
  const camHint=document.querySelector('#camHint');
  const audioWarn=document.querySelector('#audioWarn');
  camToggle.addEventListener('change',()=>{camHint.classList.toggle('hidden',!camToggle.checked)});
  const recordingView=document.querySelector('#recordingView');
  const recorderSection=document.querySelector('#recorder');
  const doneView=document.querySelector('#doneView');
  const preview=document.querySelector('#preview');
  const downloadBtn=document.querySelector('#downloadBtn');
  const againBtn=document.querySelector('#againBtn');
  const usageLine=document.querySelector('#usageLine');
  const trimStart=document.querySelector('#trimStart');
  const trimEnd=document.querySelector('#trimEnd');
  const trimStartTime=document.querySelector('#trimStartTime');
  const trimEndTime=document.querySelector('#trimEndTime');
  const speedPicker=document.querySelector('#speedPicker');
  const editBtn=document.querySelector('#editBtn');
  const editStatus=document.querySelector('#editStatus');
  const editProgress=document.querySelector('#editProgress');
  const editFill=document.querySelector('#editFill');
  const editText=document.querySelector('#editText');
  const editResult=document.querySelector('#editResult');
  const editPreview=document.querySelector('#editPreview');
  const editDownload=document.querySelector('#editDownload');
  const editBody=document.querySelector('#editBody');
  const editNote=document.querySelector('#editNote');
  let screenStream=null,micStream=null,camStream=null,recorder=null,chunks=[],startTime=0,timerInt=null,mime='',blobUrl='',mixCtx=null,attempt=crypto.randomUUID();
  let screenVideoEl=null,camVideoEl=null,compCanvas=null,compCtx=null,drawRAF=0;
  let recordedBlob=null,editBlobUrl='',editSpeed=1;
  let pipWin=null,bubbleTimerEl=null,fallbackBubble=null;
  function pickMime(){const c=['video/webm;codecs=vp9,opus','video/webm;codecs=vp8,opus','video/webm','video/mp4;codecs=avc1.42E01E,mp4a.40.2','video/mp4'];for(const t of c){if(window.MediaRecorder&&MediaRecorder.isTypeSupported(t))return t}return ''}
  function setError(m){errorEl.textContent=m}
  function stopTracks(){[screenStream,micStream,camStream].forEach(s=>{if(s)s.getTracks().forEach(t=>t.stop())});screenStream=null;micStream=null;camStream=null;if(drawRAF)cancelAnimationFrame(drawRAF);drawRAF=0;screenVideoEl=null;camVideoEl=null;compCanvas=null;compCtx=null;if(mixCtx){mixCtx.close().catch(()=>{});mixCtx=null}closeBubbleWindow()}
  function doStop(){if(recorder&&recorder.state!=='inactive')recorder.stop()}
  // --- floating self-view bubble -------------------------------------------
  // An always-on-top bubble window with a stop button, positioned as a live
  // reference for where the bubble lands in the recording. Uses the Document
  // Picture-in-Picture API (Chrome/Edge); other browsers get a fixed
  // floating bubble in the page instead.
  async function openBubbleWindow(){
    if(!camStream||!camStream.getVideoTracks().length)return;
    if(pipWin&&!pipWin.closed){pipWin.focus();return}
    if(window.documentPictureInPicture){
      try{
        pipWin=await window.documentPictureInPicture.requestWindow({width:280,height:340});
        const link=pipWin.document.createElement('link');
        link.rel='stylesheet';link.href='/app.css?v=3';
        pipWin.document.head.appendChild(link);
        const style=pipWin.document.createElement('style');
        style.textContent='html,body{margin:0;padding:16px;min-height:100%;box-sizing:border-box;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;text-align:center}'+
          '.bubble{width:180px;height:180px;border-radius:50%;overflow:hidden;border:4px solid #fff;box-shadow:0 4px 16px rgba(0,0,0,.25);background:#000}'+
          '.bubble video{width:100%;height:100%;object-fit:cover;transform:scaleX(-1)}'+
          '.bubble-timer{font-weight:700;font-size:15px;opacity:.7}'+
          '.bubble-note{font-size:13px;opacity:.7;margin:0}';
        pipWin.document.head.appendChild(style);
        const bubble=pipWin.document.createElement('div');
        bubble.className='bubble';
        const vid=pipWin.document.createElement('video');
        vid.autoplay=true;vid.muted=true;vid.playsInline=true;
        vid.srcObject=camStream;
        bubble.appendChild(vid);
        vid.play().catch(()=>{});
        bubbleTimerEl=pipWin.document.createElement('div');
        bubbleTimerEl.className='bubble-timer';
        bubbleTimerEl.textContent='00:00';
        const stopButton=pipWin.document.createElement('button');
        stopButton.className='button';
        stopButton.type='button';
        stopButton.textContent='Stop';
        stopButton.addEventListener('click',()=>{doStop()});
        const note=pipWin.document.createElement('p');
        note.className='bubble-note';
        note.textContent='Keep this corner clear, your bubble sits here in the final video.';
        pipWin.document.body.appendChild(bubble);
        pipWin.document.body.appendChild(bubbleTimerEl);
        pipWin.document.body.appendChild(stopButton);
        pipWin.document.body.appendChild(note);
        pipWin.addEventListener('pagehide',()=>{pipWin=null;bubbleTimerEl=null});
        bbTrack('bubble_window_opened',{tool:TOOL_KEY});
        return;
      }catch(err){pipWin=null}
    }
    // Fallback: fixed floating bubble in the page (bottom-right).
    fallbackBubble=document.createElement('div');
    fallbackBubble.style.cssText='position:fixed;right:24px;bottom:24px;z-index:9999;display:flex;flex-direction:column;align-items:center;gap:10px;background:#fff;border:2px solid var(--line,#1a1a1a);border-radius:18px;padding:16px;box-shadow:0 8px 24px rgba(0,0,0,.25);text-align:center';
    const fbVid=document.createElement('video');
    fbVid.autoplay=true;fbVid.muted=true;fbVid.playsInline=true;
    fbVid.srcObject=camStream;
    fbVid.style.cssText='width:150px;height:150px;border-radius:50%;object-fit:cover;transform:scaleX(-1);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.2)';
    fbVid.play().catch(()=>{});
    bubbleTimerEl=document.createElement('div');
    bubbleTimerEl.style.cssText='font-weight:700;font-size:14px';
    bubbleTimerEl.textContent='00:00';
    const fbStop=document.createElement('button');
    fbStop.className='button';
    fbStop.type='button';
    fbStop.textContent='Stop';
    fbStop.addEventListener('click',()=>{doStop()});
    const fbNote=document.createElement('div');
    fbNote.style.cssText='font-size:12px;opacity:.7;max-width:170px';
    fbNote.textContent='Keep this corner clear, your bubble sits here in the final video.';
    fallbackBubble.appendChild(fbVid);
    fallbackBubble.appendChild(bubbleTimerEl);
    fallbackBubble.appendChild(fbStop);
    fallbackBubble.appendChild(fbNote);
    document.body.appendChild(fallbackBubble);
    bbTrack('bubble_window_opened',{tool:TOOL_KEY});
  }
  function closeBubbleWindow(){
    if(pipWin){try{pipWin.close()}catch(e){}pipWin=null}
    bubbleTimerEl=null;
    if(fallbackBubble){fallbackBubble.remove();fallbackBubble=null}
  }
  function scaledSize(w,h,cap){if(!cap||Math.max(w,h)<=cap)return{w,h};const scale=cap/Math.max(w,h);return{w:Math.round(w*scale/2)*2,h:Math.round(h*scale/2)*2}}
  function drawFrame(){
    if(screenVideoEl&&screenVideoEl.videoWidth&&compCtx){
      compCtx.drawImage(screenVideoEl,0,0,compCanvas.width,compCanvas.height);
    }
    if(camVideoEl&&camVideoEl.videoWidth&&compCtx){
      const minSide=Math.min(compCanvas.width,compCanvas.height);
      const bubbleSize=Math.max(140,Math.round(minSide*0.22));
      const margin=Math.round(minSide*0.03);
      const x=compCanvas.width-bubbleSize-margin;
      const y=compCanvas.height-bubbleSize-margin;
      const vw=camVideoEl.videoWidth,vh=camVideoEl.videoHeight;
      const side=Math.min(vw,vh);
      const sx=(vw-side)/2,sy=(vh-side)/2;
      compCtx.save();
      compCtx.beginPath();
      compCtx.arc(x+bubbleSize/2,y+bubbleSize/2,bubbleSize/2,0,Math.PI*2);
      compCtx.closePath();
      compCtx.clip();
      compCtx.translate(x+bubbleSize,y);
      compCtx.scale(-1,1);
      compCtx.drawImage(camVideoEl,sx,sy,side,side,0,0,bubbleSize,bubbleSize);
      compCtx.restore();
      compCtx.save();
      compCtx.lineWidth=Math.max(2,bubbleSize*0.02);
      compCtx.strokeStyle='rgba(255,255,255,0.85)';
      compCtx.beginPath();
      compCtx.arc(x+bubbleSize/2,y+bubbleSize/2,bubbleSize/2-compCtx.lineWidth/2,0,Math.PI*2);
      compCtx.stroke();
      compCtx.restore();
    }
  }
  function getBlobDuration(videoEl){
    return new Promise(resolve=>{
      function onMeta(){
        if(videoEl.duration===Infinity||isNaN(videoEl.duration)){
          videoEl.currentTime=1e101;
          videoEl.addEventListener('timeupdate',function onTU(){
            videoEl.removeEventListener('timeupdate',onTU);
            const d=videoEl.duration===Infinity||isNaN(videoEl.duration)?0:videoEl.duration;
            videoEl.currentTime=0;
            resolve(d);
          });
        }else{resolve(videoEl.duration)}
      }
      if(videoEl.readyState>=1)onMeta();
      else videoEl.addEventListener('loadedmetadata',onMeta,{once:true});
    });
  }
  startBtn.addEventListener('click',async()=>{
    setError('');micNote.classList.add('hidden');camNote.classList.add('hidden');audioWarn.classList.add('hidden');
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
    let cs=null;
    if(camToggle.checked){
      try{cs=await navigator.mediaDevices.getUserMedia({video:true})}
      catch(err){cs=null;camNote.classList.remove('hidden')}
    }
    camStream=cs;
    const useCamera=!!camStream;
    const screenTrack=screenStream.getVideoTracks()[0];
    const screenSettings=(screenTrack&&screenTrack.getSettings)?screenTrack.getSettings():{};
    const displaySurface=screenSettings.displaySurface||'';
    // A full-screen capture already includes the floating bubble window, so
    // only burn the bubble into the video for tab/window captures, where the
    // floating window sits outside the recorded surface.
    const compositeCamera=useCamera&&displaySurface!=='monitor';
    let videoTracks;
    if(compositeCamera){
      try{
        screenVideoEl=document.createElement('video');
        screenVideoEl.muted=true;screenVideoEl.playsInline=true;
        screenVideoEl.srcObject=screenStream;
        await screenVideoEl.play().catch(()=>{});
        camVideoEl=document.createElement('video');
        camVideoEl.muted=true;camVideoEl.playsInline=true;
        camVideoEl.srcObject=camStream;
        await camVideoEl.play().catch(()=>{});
      }catch(err){screenVideoEl=null;camVideoEl=null}
      compCanvas=document.createElement('canvas');
      compCtx=compCanvas.getContext('2d');
      const target=scaledSize(screenSettings.width||1280,screenSettings.height||720,1920);
      compCanvas.width=target.w;compCanvas.height=target.h;
      videoTracks=compCanvas.captureStream(30).getVideoTracks();
    }else{
      videoTracks=screenStream.getVideoTracks();
    }
    const tracks=[...videoTracks];
    // Mix audio through an AudioContext (same pattern as loom-ish): raw
    // audio tracks from different sources can record silent, but a mixed
    // MediaStreamDestination track captures properly.
    const audioSources=[];
    if(screenStream.getAudioTracks().length)audioSources.push(new MediaStream([screenStream.getAudioTracks()[0]]));
    if(micStream&&micStream.getAudioTracks().length)audioSources.push(new MediaStream([micStream.getAudioTracks()[0]]));
    if(audioSources.length){
      try{
        mixCtx=new (window.AudioContext||window.webkitAudioContext)();
        if(mixCtx.state==='suspended')mixCtx.resume().catch(()=>{});
        const dest=mixCtx.createMediaStreamDestination();
        audioSources.forEach(ms=>{mixCtx.createMediaStreamSource(ms).connect(dest);});
        tracks.push(...dest.stream.getAudioTracks());
      }catch(err){mixCtx=null;}
    }
    // If the browser gave us no audio at all (e.g. window/screen share with no
    // system sound and mic denied), say so now instead of a silent clip.
    if(!tracks.some(t=>t.kind==='audio'))audioWarn.classList.remove('hidden');
    mime=pickMime();
    if(!mime){setError('This browser cannot record video. Try Chrome or Edge.');stopTracks();return}
    chunks=[];
    recorder=new MediaRecorder(new MediaStream(tracks),{mimeType:mime});
    recorder.ondataavailable=e=>{if(e.data&&e.data.size)chunks.push(e.data)};
    recorder.onstop=onStop;
    try{recorder.start(250)}catch(err){setError('Recording could not start. Please try again.');stopTracks();return}
    if(compositeCamera){
      const draw=()=>{if(recorder&&recorder.state!=='inactive'){drawFrame();drawRAF=requestAnimationFrame(draw)}};
      draw();
    }
    if(useCamera){openBubbleWindow();}
    startTime=Date.now();
    timerEl.textContent='00:00';
    timerInt=setInterval(()=>{const t=fmt(Date.now()-startTime);timerEl.textContent=t;if(bubbleTimerEl)bubbleTimerEl.textContent=t},250);
    const vt=screenStream.getVideoTracks()[0];
    if(vt)vt.addEventListener('ended',()=>{if(recorder&&recorder.state!=='inactive')doStop()});
    startBtn.disabled=true;micToggle.disabled=true;camToggle.disabled=true;
    recordingView.classList.remove('hidden');
    bbTrack('recording_started',{tool:TOOL_KEY});
    if(useCamera)bbTrack('camera_enabled',{tool:TOOL_KEY});
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
    recordedBlob=blob;
    initEditor();
    recorderSection.classList.add('hidden');
    recordingView.classList.add('hidden');
    doneView.classList.remove('hidden');
    startBtn.disabled=false;micToggle.disabled=false;camToggle.disabled=false;
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
      usageLine.textContent=data.usage.unlimited?'Unlimited actions.':data.usage.remaining+' actions remaining this month.';
    }catch(err){usageLine.textContent='We could not count this one, but your clip is still yours. Download away.'}
  }
  const probeVideo=document.createElement('video');
  const canEdit=!!(probeVideo.captureStream||probeVideo.mozCaptureStream);
  if(!canEdit){
    editBody.classList.add('hidden');
    editNote.textContent='Your browser cannot re-encode video, so editing is unavailable here. The original clip above is all yours though.';
  }
  async function initEditor(){
    editResult.classList.add('hidden');
    editProgress.classList.add('hidden');
    editStatus.textContent='';
    editBtn.disabled=false;
    if(!canEdit||!recordedBlob)return;
    const probe=document.createElement('video');
    probe.preload='metadata';
    probe.src=blobUrl;
    const dur=await getBlobDuration(probe);
    probe.removeAttribute('src');probe.load();
    if(dur<=0)return;
    trimStart.min=0;trimStart.max=dur;trimStart.step=0.1;trimStart.value=0;
    trimEnd.min=0;trimEnd.max=dur;trimEnd.step=0.1;trimEnd.value=dur;
    trimStartTime.textContent=fmt(0);
    trimEndTime.textContent=fmt(dur*1000);
  }
  trimStart.addEventListener('input',()=>{
    if(parseFloat(trimStart.value)>=parseFloat(trimEnd.value)){
      trimStart.value=Math.max(0,parseFloat(trimEnd.value)-0.1);
    }
    trimStartTime.textContent=fmt(parseFloat(trimStart.value)*1000);
  });
  trimEnd.addEventListener('input',()=>{
    if(parseFloat(trimEnd.value)<=parseFloat(trimStart.value)){
      trimEnd.value=Math.min(parseFloat(trimEnd.max),parseFloat(trimStart.value)+0.1);
    }
    trimEndTime.textContent=fmt(parseFloat(trimEnd.value)*1000);
  });
  speedPicker.addEventListener('click',e=>{
    const b=e.target.closest('button');if(!b)return;
    editSpeed=parseFloat(b.dataset.speed);
    [...speedPicker.children].forEach(x=>x.classList.toggle('secondary',x!==b));
  });
  editBtn.addEventListener('click',async()=>{
    if(!recordedBlob)return;
    const start=parseFloat(trimStart.value);
    const end=parseFloat(trimEnd.value);
    if(end-start<0.2){editStatus.textContent='That selection is too short. Give it at least a moment.';return}
    editBtn.disabled=true;
    editStatus.textContent='';
    editResult.classList.add('hidden');
    editProgress.classList.remove('hidden');
    editFill.style.width='0%';
    editText.textContent='0% ... estimating';
    bbTrack('clip_edit_started',{tool:TOOL_KEY,speed:editSpeed});
    const srcVideo=document.createElement('video');
    srcVideo.src=URL.createObjectURL(recordedBlob);
    srcVideo.playsInline=true;
    await new Promise(res=>srcVideo.addEventListener('loadedmetadata',res,{once:true}));
    await new Promise(res=>{srcVideo.currentTime=start;srcVideo.addEventListener('seeked',res,{once:true})});
    srcVideo.playbackRate=editSpeed;
    const captureFn=srcVideo.captureStream?srcVideo.captureStream.bind(srcVideo):srcVideo.mozCaptureStream.bind(srcVideo);
    const clipStream=captureFn();
    const clipMime=pickMime();
    const clipRecorder=new MediaRecorder(clipStream,{mimeType:clipMime});
    const clipChunks=[];
    clipRecorder.ondataavailable=e=>{if(e.data&&e.data.size)clipChunks.push(e.data)};
    clipRecorder.onstop=()=>{
      const clipBlob=new Blob(clipChunks,{type:clipMime.split(';')[0]});
      if(editBlobUrl)URL.revokeObjectURL(editBlobUrl);
      editBlobUrl=URL.createObjectURL(clipBlob);
      const ext2=clipMime.indexOf('mp4')>=0?'mp4':'webm';
      const d=new Date(),p=n=>String(n).padStart(2,'0');
      const stamp=d.getFullYear()+p(d.getMonth()+1)+p(d.getDate())+'-'+p(d.getHours())+p(d.getMinutes());
      editPreview.src=editBlobUrl;
      editDownload.href=editBlobUrl;
      editDownload.setAttribute('download','bum-bum-clip-edited-'+stamp+'.'+ext2);
      editResult.classList.remove('hidden');
      editProgress.classList.add('hidden');
      editBtn.disabled=false;
      srcVideo.pause();srcVideo.removeAttribute('src');srcVideo.load();
      bbTrack('clip_edit_completed',{tool:TOOL_KEY,speed:editSpeed,trimmed_seconds:Math.round((end-start)*10)/10});
    };
    clipRecorder.start(200);
    srcVideo.play();
    const renderStartedAt=Date.now();
    srcVideo.addEventListener('timeupdate',function onTU(){
      const span=end-start;
      const done=Math.min(1,Math.max(0,(srcVideo.currentTime-start)/span));
      editFill.style.width=(done*100).toFixed(0)+'%';
      const elapsedMs=Date.now()-renderStartedAt;
      const estTotalMs=done>0.02?elapsedMs/done:(span/editSpeed)*1000;
      const remainingMs=Math.max(0,estTotalMs-elapsedMs);
      editText.textContent=(done*100).toFixed(0)+'% ... '+fmt(Math.ceil(remainingMs/1000)*1000)+' left';
      if(srcVideo.currentTime>=end){
        srcVideo.removeEventListener('timeupdate',onTU);
        editFill.style.width='100%';
        editText.textContent='100% ... done';
        if(clipRecorder.state!=='inactive')clipRecorder.stop();
      }
    });
  });
  againBtn.addEventListener('click',()=>{
    closeBubbleWindow();
    if(blobUrl){URL.revokeObjectURL(blobUrl);blobUrl=''}
    if(editBlobUrl){URL.revokeObjectURL(editBlobUrl);editBlobUrl=''}
    preview.removeAttribute('src');preview.load();
    editPreview.removeAttribute('src');
    attempt=crypto.randomUUID();
    recordedBlob=null;
    doneView.classList.add('hidden');
    document.querySelector('#upgrade-slot').innerHTML='';
    usageLine.textContent='';setError('');timerEl.textContent='00:00';
    recorderSection.classList.remove('hidden');
  });
}
</script>
<?php endif; ?>
<?php endif; ?>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?>
</body>
</html>
