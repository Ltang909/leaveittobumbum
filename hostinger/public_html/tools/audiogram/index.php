<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Audiogram | Leave It to Bum Bum</title>
<meta name="description" content="Turn MP3s into captioned videos. Upload audio and a background image, pick a caption style, get a video ready for the feed. Free to try.">
<meta property="og:title" content="Audiogram | Leave It to Bum Bum">
<meta property="og:description" content="Turn MP3s into captioned videos. Upload audio and a background image, pick a caption style, get a video ready for the feed. Free to try.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/audiogram/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og-audiogram.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Audiogram | Leave It to Bum Bum">
<meta name="twitter:description" content="Turn MP3s into captioned videos. Upload audio and a background image, pick a caption style, get a video ready for the feed. Free to try.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og-audiogram.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/audiogram/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Audiogram",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Turn MP3s into captioned videos. Upload audio and a background image, pick a caption style, get a video ready for the feed. Free to try.",
  "url": "https://leaveittobumbum.com/tools/audiogram/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Is Audiogram free?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You get 15 free actions with no signup. Create a free account and you get 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/. One finished video uses one action."
      }
    },
    {
      "@type": "Question",
      "name": "Do I need an account?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Make videos as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "What happens to my audio?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "It is only transcribed to make your captions. Your audio is never kept."
      }
    },
    {
      "@type": "Question",
      "name": "What caption styles are there?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Four styles to pick from, so the captions match your feed."
      }
    },
    {
      "@type": "Question",
      "name": "What video sizes can I make?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Vertical, square, and widescreen, ready for any feed."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Audiogram | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Audiogram","item":"https://leaveittobumbum.com/tools/audiogram/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use Audiogram","description":"Turn an MP3 into a captioned video.","step":[{"@type":"HowToStep","position":1,"name":"Upload an MP3 and a background image."},{"@type":"HowToStep","position":2,"name":"Bum Bum transcribes your audio; the audio itself is never kept."},{"@type":"HowToStep","position":3,"name":"Pick a caption style and aspect ratio."},{"@type":"HowToStep","position":4,"name":"Download your captioned video, ready for the feed."}]}</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.ag-drop{border:2px dashed var(--line);border-radius:12px;padding:26px 18px;text-align:center;cursor:pointer;background:#fff;margin:0 0 12px}
.ag-drop.over{border-color:var(--accent,#b3541e);background:#fff8f2}
.ag-drop strong{display:block;font-size:17px;margin-bottom:4px}
.ag-drop span{color:var(--muted);font-size:14px}
.ag-drop input{display:none}
.ag-fileline{font-size:14px;font-weight:700;margin:0 0 12px;color:var(--muted)}
.ag-status{font-weight:700;margin:14px 0 0}
.ag-step{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-top:16px}
.style-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:10px;margin:12px 0}
.style-card{border:2px solid var(--line);border-radius:12px;padding:14px;cursor:pointer;background:#fff}
.style-card input{display:none}
.style-card.sel{border-color:var(--accent,#b3541e);background:#fff8f2}
.style-card b{display:block;font-size:16px;margin-bottom:4px}
.style-card small{color:var(--muted);font-size:13px;line-height:1.4;display:block}
.style-card .swatch{display:block;height:34px;border-radius:8px;margin-bottom:10px;background:#111;color:#fff;font-weight:900;display:flex;align-items:center;justify-content:center;font-size:13px}
select{width:100%;padding:14px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff}
#agCanvas{width:100%;max-width:420px;height:auto;display:block;margin:12px auto;border-radius:12px;background:#000}
.ag-progress{height:10px;border-radius:999px;background:#eee;overflow:hidden;margin:12px 0}
.ag-progress i{display:block;height:100%;width:0;background:var(--accent,#b3541e);transition:width .2s}
.hidden{display:none!important}
.linklike{background:none;border:0;padding:0;margin-top:14px;color:var(--accent,#b3541e);font:inherit;font-weight:700;cursor:pointer;text-decoration:underline}
.mini{padding:8px 14px;font-size:14px}
#agGen{font-size:18px;padding:14px 26px;margin-top:12px}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Audiogram"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-laptop.png" alt="Bum Bum editing video"><h1>MP3 in. Video out.</h1><p class="lede">Upload an MP3 and a background image. Bum Bum transcribes your audio, you pick a caption style, and you get a captioned video ready for the feed. Your audio is only transcribed, it is never kept. One finished video uses one action.</p>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to use Audiogram</h2><a class="button" href="/account/?next=<?= urlencode('/tools/audiogram/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/audiogram/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/audiogram/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/audiogram/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php $out = empty($usage['unlimited']) && ($usage['remaining'] ?? 0) <= 0; ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<?php if ($out): ?>
<div id="upgrade-slot"></div>
<?php else: ?>
<section class="panel" id="agUpload">
<h2 style="margin-top:0">1. Your ingredients</h2>
<div class="ag-drop" id="agMp3Drop" role="button" tabindex="0"><strong>Drop your MP3 here, or tap to pick one</strong><span>Up to 25 MB. Sent for transcription only, never stored.</span><input type="file" id="agMp3" accept="audio/mpeg,.mp3"></div>
<p class="ag-fileline" id="agMp3Info"></p>
<div class="ag-drop" id="agImgDrop" role="button" tabindex="0"><strong>Drop a background image here, or tap to pick one</strong><span>Stays in your browser. Never uploaded anywhere.</span><input type="file" id="agImg" accept="image/*"></div>
<p class="ag-fileline" id="agImgInfo"></p>
<p id="agUploadErr" class="error"></p>
<div class="ag-step"><button id="agToStyle" class="button" type="button" disabled>Continue</button></div>
</section>
<section class="panel hidden" id="agStyle">
<h2 style="margin-top:0">2. Caption style</h2>
<div class="style-grid" id="agStyles">
<label class="style-card sel"><input type="radio" name="agStyle" value="pop" checked><span class="swatch" style="background:#111">WORD <span style="color:#ffd93d">&nbsp;BY&nbsp;</span> WORD</span><b>Pop</b><small>Karaoke word-by-word highlight, bold and loud. The scroll-stopper.</small></label>
<label class="style-card"><input type="radio" name="agStyle" value="clean"><span class="swatch" style="background:rgba(0,0,0,.75)">clean captions here</span><b>Clean</b><small>Classic white text on a dark bar. Readable everywhere.</small></label>
<label class="style-card"><input type="radio" name="agStyle" value="neon"><span class="swatch" style="background:#1a0b2e;text-shadow:0 0 12px #ff4fd8">NEON GLOW</span><b>Neon</b><small>Bum Bum brand pink and purple glow. Made for the feed.</small></label>
<label class="style-card"><input type="radio" name="agStyle" value="minimal"><span class="swatch" style="background:#222;color:#ddd;font-weight:600">quiet captions</span><b>Minimal</b><small>Small and elegant, tucked in the corner. No shouting.</small></label>
</div>
<label>Video shape<select id="agAspect"><option value="9:16" selected>Vertical 9:16 (Reels, TikTok, Shorts)</option><option value="1:1">Square 1:1 (feed posts)</option><option value="16:9">Wide 16:9 (YouTube)</option></select></label>
<p id="agStyleErr" class="error"></p>
<div class="ag-step"><button id="agTranscribe" class="button" type="button">Transcribe my MP3</button><button id="agBack1" class="button secondary" type="button">Back</button></div>
<p id="agUsage"></p>
</section>
<section class="panel hidden" id="agStudio">
<h2 style="margin-top:0">3. Preview and render</h2>
<canvas id="agCanvas" width="720" height="1280"></canvas>
<div class="ag-step"><button id="agPlay" class="button" type="button">Play</button><span id="agTime" style="font-weight:800">0:00 / 0:00</span></div>
<p id="agStatus" class="ag-status">Have a look. When it feels right, render it.</p>
<div class="ag-progress hidden" id="agProgWrap"><i id="agProg"></i></div>
<p id="agProgText" style="font-weight:700"></p>
<div class="ag-step"><button id="agRender" class="button" type="button">Render video</button><a id="agDl" class="button secondary hidden" download="audiogram.mp4">Download MP4</a><button id="agAgain" class="linklike" type="button">start over</button></div>
<p id="agRenderErr" class="error"></p>
</section>
<div id="upgrade-slot"></div>
<?php endif; ?>
<script>
const TOOL_KEY='audiogram';
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
bbTrack('tool_opened',{tool:TOOL_KEY});
const $=id=>document.querySelector('#'+id);
const uploadSec=$('agUpload'),styleSec=$('agStyle'),studioSec=$('agStudio');
const mp3Input=$('agMp3'),imgInput=$('agImg'),mp3Info=$('agMp3Info'),imgInfo=$('agImgInfo'),uploadErr=$('agUploadErr');
const toStyleBtn=$('agToStyle'),transcribeBtn=$('agTranscribe'),back1Btn=$('agBack1'),styleErr=$('agStyleErr');
const canvas=$('agCanvas'),playBtn=$('agPlay'),timeEl=$('agTime'),statusEl=$('agStatus');
const renderBtn=$('agRender'),dlLink=$('agDl'),againBtn=$('agAgain'),renderErr=$('agRenderErr');
const progWrap=$('agProgWrap'),progBar=$('agProg'),progText=$('agProgText'),usageEl=$('agUsage');
const FFMPEG_VENDOR='/tools/video-trimmer/vendor/';
let audioFile=null,bgImg=null,audioUrl=null,duration=0,words=[],cues=[],peaks=[];
let style='pop',aspect='9:16',audioEl=null,rafId=0,playing=false;
let actx=null,mediaSrc=null,ffmpeg=null,renderKey=null,rendering=false;
function setStatus(m,isErr){statusEl.textContent=m;statusEl.classList.toggle('error',!!isErr);}
function fmtT(s){s=Math.max(0,s||0);const m=Math.floor(s/60),sec=Math.floor(s%60);return m+':'+String(sec).padStart(2,'0');}
function pickRecorderMime(){const c=['video/webm;codecs=vp9,opus','video/webm;codecs=vp8,opus','video/webm'];for(const t of c){if(window.MediaRecorder&&MediaRecorder.isTypeSupported(t))return t;}return '';}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/audiogram.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data};}
function loadScript(src,timeoutMs){return new Promise((res,rej)=>{const s=document.createElement('script');s.src=src;const to=setTimeout(()=>{s.remove();rej(new Error('script-timeout:'+src))},timeoutMs||90000);s.onload=()=>{clearTimeout(to);res()};s.onerror=()=>{clearTimeout(to);rej(new Error('script-load:'+src))};document.head.appendChild(s)})}
async function getFFmpeg(onStage){if(ffmpeg)return ffmpeg;onStage&&onStage('engine');await loadScript(FFMPEG_VENDOR+'ffmpeg.js');if(!window.FFmpegWASM)throw new Error('no-ffmpeg-global');const{FFmpeg}=window.FFmpegWASM;const ff=new FFmpeg();onStage&&onStage('core');await ff.load({coreURL:FFMPEG_VENDOR+'ffmpeg-core.js'});ffmpeg=ff;return ffmpeg;}
function bindDrop(zone,input,onFile){zone.addEventListener('click',()=>input.click());zone.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();input.click()}});['dragover','dragenter'].forEach(ev=>zone.addEventListener(ev,e=>{e.preventDefault();zone.classList.add('over')}));['dragleave','drop'].forEach(ev=>zone.addEventListener(ev,e=>{e.preventDefault();zone.classList.remove('over')}));zone.addEventListener('drop',e=>{const f=e.dataTransfer.files&&e.dataTransfer.files[0];if(f)onFile(f)});input.addEventListener('change',()=>{const f=input.files&&input.files[0];if(f)onFile(f);input.value=''})}
function checkReady(){toStyleBtn.disabled=!(audioFile&&bgImg);}
bindDrop($('agMp3Drop'),mp3Input,f=>{
  uploadErr.textContent='';
  if(!/^audio\//.test(f.type)&&!/\.mp3$/i.test(f.name)){uploadErr.textContent='That does not look like an MP3.';return;}
  if(f.size>25*1024*1024){uploadErr.textContent='That MP3 is too chunky. Keep it under 25 MB.';return;}
  audioFile=f;mp3Info.textContent='MP3: '+f.name+' ('+(f.size/1048576).toFixed(1)+' MB)';checkReady();
});
bindDrop($('agImgDrop'),imgInput,f=>{
  uploadErr.textContent='';
  if(!/^image\//.test(f.type)){uploadErr.textContent='That does not look like an image.';return;}
  const url=URL.createObjectURL(f);const img=new Image();
  img.onload=()=>{bgImg=img;imgInfo.textContent='Background: '+f.name+' ('+img.naturalWidth+'x'+img.naturalHeight+')';checkReady();};
  img.onerror=()=>{URL.revokeObjectURL(url);uploadErr.textContent='Bum Bum could not open that image.';};
  img.src=url;
});
toStyleBtn.addEventListener('click',()=>{uploadSec.classList.add('hidden');styleSec.classList.remove('hidden');styleSec.scrollIntoView({behavior:'smooth'})});
back1Btn.addEventListener('click',()=>{styleSec.classList.add('hidden');uploadSec.classList.remove('hidden')});
document.querySelectorAll('#agStyles .style-card').forEach(card=>{
  card.addEventListener('click',()=>{
    document.querySelectorAll('#agStyles .style-card').forEach(c=>c.classList.remove('sel'));
    card.classList.add('sel');card.querySelector('input').checked=true;
    style=card.querySelector('input').value;
  });
});
$('agAspect').addEventListener('change',e=>{aspect=e.target.value;});
function groupCues(ws){
  const out=[];let cur=null;
  const push=()=>{if(cur&&cur.words.length){cur.text=cur.words.map(w=>w.w).join(' ');out.push(cur);}cur=null;};
  for(const w of ws){
    const wlen=w.w.length;
    if(!cur){cur={words:[w],start:w.start,end:w.end,chars:wlen};}
    else if(cur.words.length>=6||cur.chars+wlen+1>32){push();cur={words:[w],start:w.start,end:w.end,chars:wlen};}
    else{cur.words.push(w);cur.end=w.end;cur.chars+=wlen+1;}
  }
  push();return out;
}
async function decodePeaks(file){
  actx=actx||new (window.AudioContext||window.webkitAudioContext)();
  const buf=await actx.decodeAudioData(await file.arrayBuffer());
  const ch0=buf.getChannelData(0),ch1=buf.numberOfChannels>1?buf.getChannelData(1):null;
  const N=240,peaks=new Array(N).fill(0),step=Math.floor(ch0.length/N);
  for(let i=0;i<N;i++){
    let m=0;const s=i*step;
    for(let j=s;j<Math.min(s+step,ch0.length);j+=7){
      const v=ch1?Math.abs((ch0[j]+ch1[j])/2):Math.abs(ch0[j]);
      if(v>m)m=v;
    }
    peaks[i]=m;
  }
  return{duration:buf.duration,peaks};
}
transcribeBtn.addEventListener('click',async()=>{
  styleErr.textContent='';transcribeBtn.disabled=true;
  setStatus('Listening to your MP3...');studioSec.classList.remove('hidden');
  try{
    const session=await fetch('/api/session.php').then(r=>r.json());
    const fd=new FormData();fd.append('action','transcribe');fd.append('audio',audioFile,audioFile.name);fd.append('csrf',session.csrf||'');
    const[resp,dec]=await Promise.all([
      fetch('/api/tools/audiogram.php',{method:'POST',body:fd}).then(async r=>({ok:r.ok,status:r.status,data:await r.json().catch(()=>({}))})),
      decodePeaks(audioFile)
    ]);
    if(!resp.ok)throw new Error(resp.data.error||('transcription failed ('+resp.status+')'));
    words=resp.data.words||[];duration=dec.duration||resp.data.duration||0;peaks=dec.peaks;
    if(!words.length||!duration)throw new Error('no words heard');
    cues=groupCues(words);
    setupStudio();
    styleSec.classList.add('hidden');studioSec.scrollIntoView({behavior:'smooth'});
    setStatus('Have a look. When it feels right, render it.');
    bbTrack('transcription_completed',{tool:TOOL_KEY,words:words.length,seconds:Math.round(duration)});
  }catch(e){
    studioSec.classList.add('hidden');
    styleErr.textContent=String(e.message||e).replace(/^Error:\s*/,'');
    setStatus('The audiogram ate the tape. Try again.',true);
  }finally{transcribeBtn.disabled=false;}
});
const ASPECTS={'9:16':[720,1280],'1:1':[1080,1080],'16:9':[1280,720]};
function setupStudio(){
  const[W,H]=ASPECTS[aspect]||ASPECTS['9:16'];
  canvas.width=W;canvas.height=H;
  canvas.style.maxWidth=aspect==='16:9'?'100%':'420px';
  if(audioUrl)URL.revokeObjectURL(audioUrl);
  audioUrl=URL.createObjectURL(audioFile);audioEl=new Audio(audioUrl);audioEl.preload='auto';
  audioEl.addEventListener('timeupdate',()=>{timeEl.textContent=fmtT(audioEl.currentTime)+' / '+fmtT(duration)});
  audioEl.addEventListener('ended',()=>{playing=false;playBtn.textContent='Play';audioEl.currentTime=0;});
  timeEl.textContent='0:00 / '+fmtT(duration);
  playBtn.textContent='Play';playing=false;
  dlLink.classList.add('hidden');renderErr.textContent='';progText.textContent='';progWrap.classList.add('hidden');
  cancelAnimationFrame(rafId);
  const loop=()=>{drawFrame(audioEl?audioEl.currentTime:0);rafId=requestAnimationFrame(loop);};
  loop();
}
playBtn.addEventListener('click',()=>{
  if(!audioEl)return;
  if(playing){audioEl.pause();playing=false;playBtn.textContent='Play';}
  else{audioEl.play().then(()=>{playing=true;playBtn.textContent='Pause';}).catch(()=>{setStatus('Audio would not play in this browser.',true)});}
});
function cueAt(t){
  for(const c of cues){if(t>=c.start-0.08&&t<=c.end+0.25)return c;}
  return null;
}
function fitFont(ctx,text,maxW,base){
  let s=base;ctx.font='900 '+s+'px "Nunito Sans",sans-serif';
  while(s>14&&ctx.measureText(text).width>maxW){s-=2;ctx.font='900 '+s+'px "Nunito Sans",sans-serif';}
  return s;
}
function drawFrame(t){
  const ctx=canvas.getContext('2d'),W=canvas.width,H=canvas.height;
  ctx.clearRect(0,0,W,H);
  if(bgImg){
    const zoom=1+0.07*(duration>0?Math.min(1,t/duration):0);
    const ir=bgImg.naturalWidth/bgImg.naturalHeight,cr=W/H;
    let dw,dh;
    if(ir>cr){dh=H*zoom;dw=dh*ir;}else{dw=W*zoom;dh=dw/ir;}
    ctx.drawImage(bgImg,(W-dw)/2,(H-dh)/2,dw,dh);
  }else{ctx.fillStyle='#111';ctx.fillRect(0,0,W,H);}
  const g=ctx.createLinearGradient(0,H*0.45,0,H);
  g.addColorStop(0,'rgba(0,0,0,0)');g.addColorStop(1,'rgba(0,0,0,0.55)');
  ctx.fillStyle=g;ctx.fillRect(0,H*0.45,W,H*0.55);
  drawWave(ctx,W,H,t);
  const cue=cueAt(t);
  if(cue)drawCaption(ctx,W,H,cue,t,style);
}
function drawWave(ctx,W,H,t){
  const n=peaks.length;if(!n)return;
  const bw=W/n,bh=H*0.055,y0=H-bh-14;
  const played=duration>0?t/duration:0;
  for(let i=0;i<n;i++){
    const h=Math.max(2,peaks[i]*bh);
    ctx.fillStyle=(i/n)<=played?'#ff7448':'rgba(255,255,255,0.35)';
    ctx.fillRect(i*bw+bw*0.2,y0+(bh-h)/2,Math.max(1,bw*0.6),h);
  }
  ctx.fillStyle='rgba(255,255,255,0.25)';ctx.fillRect(0,H-6,W,6);
  ctx.fillStyle='#ff7448';ctx.fillRect(0,H-6,W*played,6);
}
function drawCaption(ctx,W,H,cue,t,sty){
  const words=cue.words;
  if(sty==='pop'){
    const text=cue.text.toUpperCase();
    const size=fitFont(ctx,text,W*0.92,W/13);
    ctx.font='900 '+size+'px "Nunito Sans",sans-serif';
    ctx.textAlign='left';ctx.textBaseline='middle';
    const widths=words.map(w=>ctx.measureText(w.w.toUpperCase()).width);
    const gap=ctx.measureText(' ').width;
    const total=widths.reduce((a,b)=>a+b,0)+gap*(words.length-1);
    let x=(W-total)/2;const y=H*0.60;
    ctx.lineWidth=Math.max(2,size/9);ctx.strokeStyle='rgba(0,0,0,0.85)';
    words.forEach((w,i)=>{
      const ww=w.w.toUpperCase();
      ctx.fillStyle=(w.end<=t||(t>=w.start&&t<=w.end))?'#ffd93d':'#ffffff';
      ctx.strokeText(ww,x,y);ctx.fillText(ww,x,y);
      x+=widths[i]+gap;
    });
  }else if(sty==='clean'){
    const size=fitFont(ctx,cue.text,W*0.86,W/17);
    ctx.font='700 '+size+'px "Nunito Sans",sans-serif';
    ctx.textAlign='center';ctx.textBaseline='middle';
    const lines=wrapLines(ctx,cue.text,W*0.82);
    const lh=size*1.35,bh=lines.length*lh+size*0.7;
    const by=H-bh-H*0.10;
    roundRect(ctx,W*0.05,by,W*0.9,bh,14);ctx.fillStyle='rgba(0,0,0,0.72)';ctx.fill();
    ctx.fillStyle='#fff';
    lines.forEach((ln,i)=>ctx.fillText(ln,W/2,by+size*0.35+lh*(i+0.5)));
  }else if(sty==='neon'){
    const text=cue.text.toUpperCase();
    const size=fitFont(ctx,text,W*0.9,W/14);
    ctx.font='800 '+size+'px "Nunito Sans",sans-serif';
    ctx.textAlign='center';ctx.textBaseline='middle';
    ctx.shadowColor='#ff4fd8';ctx.shadowBlur=size*0.55;
    ctx.fillStyle='#ffffff';
    ctx.fillText(text,W/2,H*0.60);
    ctx.shadowColor='#7b5cff';ctx.shadowBlur=size*1.1;
    ctx.fillText(text,W/2,H*0.60);
    ctx.shadowBlur=0;
  }else{
    const size=Math.max(13,W/30);
    ctx.font='600 '+size+'px "Nunito Sans",sans-serif';
    ctx.textAlign='left';ctx.textBaseline='alphabetic';
    ctx.shadowColor='rgba(0,0,0,0.8)';ctx.shadowBlur=6;
    ctx.fillStyle='rgba(255,255,255,0.94)';
    const lines=wrapLines(ctx,cue.text,W*0.6);
    lines.forEach((ln,i)=>ctx.fillText(ln,W*0.07,H-H*0.09-(lines.length-1-i)*size*1.4));
    ctx.shadowBlur=0;
  }
  ctx.textAlign='left';ctx.textBaseline='alphabetic';
}
function wrapLines(ctx,text,maxW){
  const out=[];let line='';
  for(const wd of text.split(' ')){
    const test=line?line+' '+wd:wd;
    if(ctx.measureText(test).width>maxW&&line){out.push(line);line=wd;}
    else line=test;
  }
  if(line)out.push(line);
  return out.length?out:[text];
}
function roundRect(ctx,x,y,w,h,r){
  ctx.beginPath();
  ctx.moveTo(x+r,y);ctx.arcTo(x+w,y,x+w,y+h,r);ctx.arcTo(x+w,y+h,x,y+h,r);
  ctx.arcTo(x,y+h,x,y,r);ctx.arcTo(x,y,x+w,y,r);ctx.closePath();
}
renderBtn.addEventListener('click',async()=>{
  if(rendering||!audioEl)return;
  renderErr.textContent='';dlLink.classList.add('hidden');
  renderBtn.disabled=true;rendering=true;
  const setProg=(p,txt)=>{progWrap.classList.remove('hidden');progBar.style.width=Math.round(p*100)+'%';progText.textContent=txt;};
  try{
    setProg(0.02,'Checking your actions...');
    const st=await apiCall({action:'status'});
    if(st.response.ok&&st.data.usage&&!st.data.usage.unlimited&&Number(st.data.usage.remaining)<=0){
      throw{limit:true};
    }
    renderKey=crypto.randomUUID();
    setProg(0.06,'Loading the video engine (first render downloads it, about 30 MB)...');
    const ff=await getFFmpeg(s=>setProg(s==='engine'?0.08:0.14,'Loading the video engine...'));
    actx=actx||new (window.AudioContext||window.webkitAudioContext)();
    if(actx.state==='suspended')await actx.resume();
    if(!mediaSrc){mediaSrc=actx.createMediaElementSource(audioEl);mediaSrc.connect(actx.destination);}
    const dest=actx.createMediaStreamDestination();
    mediaSrc.connect(dest);
    const vstream=canvas.captureStream(30);
    const combined=new MediaStream([...vstream.getVideoTracks(),...dest.stream.getAudioTracks()]);
    const mime=pickRecorderMime();
    const rec=new MediaRecorder(combined,mime?{mimeType:mime,videoBitsPerSecond:8000000}:undefined);
    const chunks=[];
    rec.ondataavailable=e=>{if(e.data&&e.data.size)chunks.push(e.data);};
    const stopped=new Promise(res=>{rec.onstop=res;});
    audioEl.currentTime=0;
    setProg(0.18,'Recording your video...');
    rec.start(250);
    await audioEl.play();playing=true;playBtn.textContent='Pause';
    const tick=()=>{if(rendering&&audioEl)setProg(0.18+0.6*(audioEl.currentTime/Math.max(1,duration)),'Recording your video... '+fmtT(audioEl.currentTime)+' / '+fmtT(duration));};
    const tickInt=setInterval(tick,400);
    await new Promise(res=>{audioEl.onended=()=>res();});
    clearInterval(tickInt);
    rec.stop();await stopped;
    mediaSrc.disconnect(dest);
    playing=false;playBtn.textContent='Play';
    setProg(0.82,'Converting to MP4...');
    const webm=new Blob(chunks,{type:rec.mimeType||'video/webm'});
    await ff.writeFile('input.webm',new Uint8Array(await webm.arrayBuffer()));
    const code=await ff.exec(['-i','input.webm','-c:v','libx264','-preset','veryfast','-crf','23','-c:a','aac','-b:a','128k','-movflags','faststart','output.mp4']);
    if(code!==0)throw new Error('convert failed');
    const data=await ff.readFile('output.mp4');
    const mp4=new Blob([data],{type:'video/mp4'});
    if(dlLink.href)URL.revokeObjectURL(dlLink.href);
    dlLink.href=URL.createObjectURL(mp4);dlLink.classList.remove('hidden');
    setProg(1,'Done.');
    progText.textContent='Your video is ready. One action was used.';
    dlLink.scrollIntoView({behavior:'smooth',block:'nearest'});
    try{
      const done=await apiCall({action:'complete',idempotencyKey:renderKey});
      if(done.response.ok&&done.data.usage){
        const u=done.data.usage;
        usageEl.textContent=u.unlimited?'Unlimited actions.':u.remaining+' actions left.';
        bbTrack('action_completed',{tool:TOOL_KEY,used:u.used,limit:u.limit,remaining:u.remaining,seconds:Math.round(duration)});
      }else if(done.response.status===402){
        progText.textContent='Your video is ready. Heads up: this render could not be counted against your actions.';
      }
    }catch(_){}
  }catch(e){
    if(e&&e.limit){
      bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});
      document.querySelector('#upgrade-slot').innerHTML=upgradeCard();
      bindUpgradeClicks(document.querySelector('#upgrade-slot'),'limit');
      renderErr.textContent='You are out of actions, so this render was not started.';
    }else{
      renderErr.textContent='Render hiccup. Your files are safe, try again.';
      setStatus('Render hiccup. Your files are safe, try again.',true);
    }
    progWrap.classList.add('hidden');progText.textContent='';
  }finally{rendering=false;renderBtn.disabled=false;}
});
againBtn.addEventListener('click',()=>{
  cancelAnimationFrame(rafId);
  if(audioEl){audioEl.pause();audioEl=null;}
  if(audioUrl){URL.revokeObjectURL(audioUrl);audioUrl=null;}
  audioFile=null;bgImg=null;words=[];cues=[];peaks=[];duration=0;rendering=false;
  mp3Info.textContent='';imgInfo.textContent='';uploadErr.textContent='';renderErr.textContent='';
  usageEl.textContent='';dlLink.classList.add('hidden');
  studioSec.classList.add('hidden');styleSec.classList.add('hidden');uploadSec.classList.remove('hidden');
  toStyleBtn.disabled=true;setStatus('Have a look. When it feels right, render it.');
  uploadSec.scrollIntoView({behavior:'smooth'});
});
<?php endif; ?>
</script><?php endif; ?><section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Is Audiogram free?</summary>
<p>You get 15 free actions with no signup. Create a free account and you get 75 actions every month. <a href="/pricing/">See pricing</a>. One finished video uses one action.</p>
</details>
<details>
<summary>Do I need an account?</summary>
<p>No. Make videos as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>What happens to my audio?</summary>
<p>It is only transcribed to make your captions. Your audio is never kept.</p>
</details>
<details>
<summary>What caption styles are there?</summary>
<p>Four styles to pick from, so the captions match your feed.</p>
</details>
<details>
<summary>What video sizes can I make?</summary>
<p>Vertical, square, and widescreen, ready for any feed.</p>
</details>
</section>
<section class="panel" aria-label="More tiny tools">
<h2 style="margin-top:0">More tiny tools</h2>
<p><a href="/tools/clips/">Bum Bum Clips</a> - Record your screen right in your browser.</p>
<p><a href="/tools/video-trimmer/">Video Trimmer</a> - Drop in a video, mark where the good part starts and ends, and Bum Bum snips it right in your browser.</p>
<p><a href="/tools/image-converter/">Image Converter</a> - WebP to PNG, JPG to WebP, whatever to whatever.</p>
</section>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
