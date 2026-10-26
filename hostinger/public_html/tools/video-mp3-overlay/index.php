<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Video MP3 Overlay | Put an MP3 Over Your Video, In Your Browser | Leave It to Bum Bum</title>
<meta name="description" content="Drop in a video and an MP3, snip the good bits of the song, pin them where they belong, and Bum Bum mixes them over your video right in your browser. Nothing gets uploaded. Free to try.">
<meta property="og:title" content="Video MP3 Overlay | Put an MP3 Over Your Video, In Your Browser | Leave It to Bum Bum">
<meta property="og:description" content="Drop in a video and an MP3, snip the good bits of the song, pin them where they belong, and Bum Bum mixes them over your video right in your browser. Nothing gets uploaded. Free to try.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/video-mp3-overlay/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/icons/video-mp3-overlay.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Video MP3 Overlay | Put an MP3 Over Your Video, In Your Browser | Leave It to Bum Bum">
<meta name="twitter:description" content="Drop in a video and an MP3, snip the good bits of the song, pin them where they belong, and Bum Bum mixes them over your video right in your browser. Nothing gets uploaded. Free to try.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/icons/video-mp3-overlay.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/video-mp3-overlay/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Video MP3 Overlay",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Drop in a video and an MP3, snip the good bits of the song, pin them where they belong, and Bum Bum mixes them over your video right in your browser. Nothing gets uploaded. Free to try.",
  "url": "https://leaveittobumbum.com/tools/video-mp3-overlay/"
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
        "text": "No. Mix videos as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "Do my video and MP3 get uploaded anywhere?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. The mixing happens entirely in your browser. Your video and your MP3 never leave your device."
      }
    },
    {
      "@type": "Question",
      "name": "How does the mix work?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Your video's own sound keeps playing from start to finish at the volume you set. Each snippet you place plays on top of it, at the time you picked, at the MP3 volume you set. Two sliders, one for each, so the balance is yours."
      }
    },
    {
      "@type": "Question",
      "name": "Can I use the same part of the MP3 more than once?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. Snip a part once, or snip a few different parts, and place each one wherever it belongs in the video. The same chorus can absolutely come back for an encore."
      }
    },
    {
      "@type": "Question",
      "name": "What files can I use?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Videos in MP4, WebM, MOV, MKV, or AVI, up to 100 MB, and an MP3 for the sound you are laying over it."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Video MP3 Overlay | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Video MP3 Overlay","item":"https://leaveittobumbum.com/tools/video-mp3-overlay/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use Video MP3 Overlay","description":"Lay snippets of an MP3 over a video, mixed in your browser.","step":[{"@type":"HowToStep","position":1,"name":"Drop in a video and an MP3."},{"@type":"HowToStep","position":2,"name":"Snip a favourite part of the MP3 and pin it to the moment in the video where it should play. Add as many snippets as you like."},{"@type":"HowToStep","position":3,"name":"Set the two volumes: the video's own sound, and the MP3 on top."},{"@type":"HowToStep","position":4,"name":"Bum Bum mixes and renders it right in your browser. Download the finished video. One action is used."}]}</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.vm-drop{border:2px dashed var(--line);border-radius:12px;padding:26px 18px;text-align:center;cursor:pointer;background:#fff;margin:0 0 12px}
.vm-drop.over{border-color:var(--accent,#b3541e);background:#fff8f2}
.vm-drop strong{display:block;font-size:17px;margin-bottom:4px}
.vm-drop span{color:var(--muted);font-size:14px}
.vm-drop input{display:none}
.vm-fileline{font-size:14px;font-weight:700;margin:0 0 12px;color:var(--muted)}
.vm-status{font-weight:700;margin:14px 0 0}
.vm-step{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-top:16px}
.vm-progress{height:10px;border-radius:999px;background:#eee;overflow:hidden;margin:12px 0}
.vm-progress i{display:block;height:100%;width:0;background:var(--accent,#b3541e);transition:width .2s}
.hidden{display:none!important}
.linklike{background:none;border:0;padding:0;margin-top:14px;color:var(--accent,#b3541e);font:inherit;font-weight:700;cursor:pointer;text-decoration:underline}
#vmRender{font-size:18px;padding:14px 26px;margin-top:12px}
#vmVideo{width:100%;max-height:380px;border-radius:12px;border:1px solid #e2d7bf;background:#000;display:block}
.vm-grid{display:grid;gap:12px;margin-top:14px}
@media(min-width:760px){.vm-grid{grid-template-columns:1fr 1fr 1fr}}
.vm-grid label{font-weight:700;font-size:14px;display:block}
.vm-grid input[type=text]{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px;font-variant-numeric:tabular-nums}
.vm-grid input[type=text]:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.vm-wavewrap{position:relative;background:#17140f;border-radius:10px;height:76px;margin-top:10px;overflow:hidden;touch-action:none}
.vm-wavewrap canvas{width:100%;height:76px;display:block}
.vm-sel{position:absolute;top:0;bottom:0;background:rgba(255,176,84,.32);border-left:2px solid #ffb054;border-right:2px solid #ffb054;cursor:grab}
.vm-handle{position:absolute;top:0;bottom:0;width:16px;margin-left:-8px;cursor:ew-resize;background:transparent}
.vm-handle::after{content:"";position:absolute;top:50%;left:50%;width:6px;height:30px;margin:-15px 0 0 -3px;border-radius:3px;background:#ffb054}
.vm-row{display:flex;gap:10px;align-items:center;flex-wrap:wrap;border:1px solid #e2d7bf;border-radius:12px;padding:10px 12px;margin-top:10px;background:#fff}
.vm-row .vm-where{font-weight:800}
.vm-row input[type=text]{width:110px;padding:8px 10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;font-variant-numeric:tabular-nums}
.vm-row .vm-del{margin-left:auto}
.vm-vols{display:grid;gap:14px;margin-top:8px}
@media(min-width:760px){.vm-vols{grid-template-columns:1fr 1fr}}
.vm-vol label{font-weight:800;font-size:14px;display:flex;justify-content:space-between;align-items:center;gap:10px}
.vm-vol input[type=range]{width:100%;margin-top:8px;accent-color:#2f2a22}
.vm-vol input[type=number]{width:84px;padding:8px 10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;font-variant-numeric:tabular-nums}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Video MP3 Overlay"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/icons/video-mp3-overlay.png" alt="Video MP3 Overlay"><h1>Your video, your soundtrack.</h1><p class="lede bb-desc">Drop in a video and an MP3. Snip the bits of the song you want, pin each one where it should play in the video, set how loud everything sits, and Bum Bum mixes it <b>right in your browser</b>. Nothing gets uploaded. One finished mix uses one action.</p>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to use Video MP3 Overlay</h2><a class="button" href="/account/?next=<?= urlencode('/tools/video-mp3-overlay/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/video-mp3-overlay/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/video-mp3-overlay/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/video-mp3-overlay/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php $out = empty($usage['unlimited']) && ($usage['remaining'] ?? 0) <= 0; ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<?php if ($out): ?>
<div id="upgrade-slot"></div>
<?php else: ?>
<section class="panel" id="vmUpload">
<h2 style="margin-top:0">1. Your video and your MP3</h2>
<div class="vm-drop" id="vmVideoDrop" role="button" tabindex="0"><strong>Drop your video here, or tap to pick one</strong><span>MP4, WebM, MOV, MKV, or AVI. Up to 100 MB. Never uploaded anywhere.</span><input type="file" id="vmVideoFile" accept="video/*,.mkv,.avi,.mov"></div>
<p class="vm-fileline" id="vmVideoInfo"></p>
<div class="vm-drop" id="vmMp3Drop" role="button" tabindex="0"><strong>Drop your MP3 here, or tap to pick one</strong><span>The song, the jingle, the voiceover. Up to 30 MB, and it stays on your device too.</span><input type="file" id="vmMp3File" accept="audio/*,.mp3"></div>
<p class="vm-fileline" id="vmMp3Info"></p>
<p id="vmUploadErr" class="error"></p>
<div class="vm-step"><button id="vmToArrange" class="button" type="button" disabled>Continue</button></div>
</section>
<section class="panel hidden" id="vmArrange">
<h2 style="margin-top:0">2. Place your snippets</h2>
<video id="vmVideo" controls playsinline></video>
<h3 style="margin:18px 0 0">Snip a part of the MP3</h3>
<p style="margin:6px 0 0">Drag the orange edges to pick the part, or type the times. Then tell Bum Bum where in the video it should play.</p>
<div class="vm-wavewrap" id="vmWaveWrap"><canvas id="vmWave" width="720" height="76"></canvas><div class="vm-sel" id="vmSel"><div class="vm-handle" id="vmHandleL" style="left:0"></div><div class="vm-handle" id="vmHandleR" style="left:100%"></div></div></div>
<audio id="vmAudio" controls style="width:100%;margin-top:10px"></audio>
<div class="vm-grid">
<div><label>Snippet starts<input type="text" id="vmInText" value="0:00.0" inputmode="decimal"></label></div>
<div><label>Snippet ends<input type="text" id="vmOutText" value="0:00.0" inputmode="decimal"></label></div>
<div><label>Plays at (in the video)<input type="text" id="vmAtText" value="0:00.0" inputmode="decimal"></label></div>
</div>
<div class="vm-step">
<button id="vmPlaySnip" class="button secondary" type="button">Play snippet</button>
<button id="vmUseTime" class="button secondary" type="button">Use current video time</button>
<button id="vmAdd" class="button" type="button">Add snippet</button>
</div>
<p id="vmArrangeErr" class="error"></p>
<div id="vmList"></div>
<h3 style="margin:22px 0 0">Set the mix</h3>
<div class="vm-vols">
<div class="vm-vol"><label>Original audio <span><input type="number" id="vmOrigVolNum" min="0" max="200" step="5" value="100"> %</span></label><input type="range" id="vmOrigVol" min="0" max="200" step="5" value="100"></div>
<div class="vm-vol"><label>MP3 snippets <span><input type="number" id="vmMp3VolNum" min="0" max="200" step="5" value="100"> %</span></label><input type="range" id="vmMp3Vol" min="0" max="200" step="5" value="100"></div>
</div>
<div class="vm-step">
<button id="vmPreviewBtn" class="button secondary" type="button">Preview the mix: off</button>
</div>
<p id="vmPreviewNote" style="margin:8px 0 0;color:var(--muted);font-size:14px">Preview plays the video with a live approximation of your mix, at the volumes above. The render itself is exact.</p>
<div class="vm-step"><button id="vmRender" class="button" type="button">Mix my video (1 action)</button><button id="vmBack1" class="button secondary" type="button">Back</button></div>
<p id="vmUsage"></p>
</section>
<section class="panel hidden" id="vmResult">
<h2 style="margin-top:0">3. Your mixed video</h2>
<div class="vm-progress hidden" id="vmProgWrap"><i id="vmProg"></i></div>
<p id="vmProgText" class="vm-status"></p>
<p id="vmRenderErr" class="error"></p>
<video id="vmResultVideo" controls playsinline class="hidden" style="width:100%;max-height:340px;border-radius:12px;background:#000"></video>
<div class="vm-step"><a id="vmDl" class="button hidden" download="mixed.mp4">Download</a><button id="vmAgain" class="linklike" type="button">mix another</button></div>
</section>
<div id="upgrade-slot"></div>
<?php endif; ?>
<script>
const TOOL_KEY='video-mp3-overlay';
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
const uploadSec=$('vmUpload'),arrangeSec=$('vmArrange'),resultSec=$('vmResult');
const uploadErr=$('vmUploadErr'),arrangeErr=$('vmArrangeErr'),renderErr=$('vmRenderErr');
const toArrangeBtn=$('vmToArrange'),back1Btn=$('vmBack1'),renderBtn=$('vmRender');
const videoEl=$('vmVideo'),audioEl=$('vmAudio');
const inText=$('vmInText'),outText=$('vmOutText'),atText=$('vmAtText');
const waveWrap=$('vmWaveWrap'),waveCanvas=$('vmWave'),selEl=$('vmSel');
const listEl=$('vmList'),usageEl=$('vmUsage');
const progWrap=$('vmProgWrap'),progBar=$('vmProg'),progText=$('vmProgText');
const dlLink=$('vmDl'),againBtn=$('vmAgain'),resultVideo=$('vmResultVideo');
const FFMPEG_VENDOR='/tools/video-trimmer/vendor/';
const OK_EXTS=['mp4','webm','mov','mkv','avi'];
const MAX_VIDEO=100*1024*1024,MAX_MP3=30*1024*1024;
let videoFile=null,videoExt='',mp3File=null,mp3Ext='mp3',ffmpeg=null,rendering=false,renderN=0;
let videoUrl=null,mp3Url=null,resultUrl=null,videoDur=0,mp3Dur=0;
let selIn=0,selOut=0,placements=[],placeN=0,origPct=100,mp3Pct=100;
let mp3Buffer=null,actx=null,origSrcNode=null,origGainNode=null,mp3GainNode=null,activeSrcs=[],previewOn=false;
function setProg(p,txt){progWrap.classList.remove('hidden');progBar.style.width=Math.round(p*100)+'%';progText.textContent=txt;}
function loadScript(src,timeoutMs){return new Promise((res,rej)=>{const s=document.createElement('script');s.src=src;const to=setTimeout(()=>{s.remove();rej(new Error('script-timeout:'+src))},timeoutMs||90000);s.onload=()=>{clearTimeout(to);res()};s.onerror=()=>{clearTimeout(to);rej(new Error('script-load:'+src))};document.head.appendChild(s)})}
async function getFFmpeg(onStage){if(ffmpeg)return ffmpeg;onStage&&onStage('engine');await loadScript(FFMPEG_VENDOR+'ffmpeg.js');if(!window.FFmpegWASM)throw new Error('no-ffmpeg-global');const{FFmpeg}=window.FFmpegWASM;const ff=new FFmpeg();onStage&&onStage('core');await ff.load({coreURL:FFMPEG_VENDOR+'ffmpeg-core.js'});ffmpeg=ff;return ffmpeg;}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/video-mp3-overlay.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data};}
function bindDrop(zone,input,onFile){zone.addEventListener('click',()=>input.click());zone.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();input.click()}});['dragover','dragenter'].forEach(ev=>zone.addEventListener(ev,e=>{e.preventDefault();zone.classList.add('over')}));['dragleave','drop'].forEach(ev=>zone.addEventListener(ev,e=>{e.preventDefault();zone.classList.remove('over')}));zone.addEventListener('drop',e=>{const f=e.dataTransfer.files&&e.dataTransfer.files[0];if(f)onFile(f)});input.addEventListener('change',()=>{const f=input.files&&input.files[0];if(f)onFile(f);input.value=''})}
function extOf(name){const m=String(name||'').toLowerCase().match(/\.([a-z0-9]+)$/);return m?m[1]:'';}
function fmt(s){s=Math.max(0,s||0);const m=Math.floor(s/60),sec=(s%60);return m+':'+(sec<10?'0':'')+sec.toFixed(1)}
function parseT(v){v=String(v).trim();if(!v)return NaN;if(v.includes(':')){const parts=v.split(':');if(parts.length!==2)return NaN;const m=parseFloat(parts[0]),s=parseFloat(parts[1]);if(isNaN(m)||isNaN(s)||s<0||s>=60)return NaN;return m*60+s}const n=parseFloat(v);return isNaN(n)?NaN:n}
function maybeReady(){toArrangeBtn.disabled=!(videoFile&&mp3File);}
function vmOnVideo(f){
  uploadErr.textContent='';
  const ext=extOf(f.name);
  const looksVideo=f.type.indexOf('video/')===0||OK_EXTS.indexOf(ext)>=0;
  if(!looksVideo){uploadErr.textContent='That does not look like a video. Try MP4, WebM, MOV, MKV, or AVI.';return;}
  if(f.size>MAX_VIDEO){uploadErr.textContent='That video is too chunky. Keep it under 100 MB.';return;}
  videoFile=f;videoExt=OK_EXTS.indexOf(ext)>=0?ext:'mp4';
  if(videoUrl)URL.revokeObjectURL(videoUrl);
  videoUrl=URL.createObjectURL(f);videoEl.src=videoUrl;videoEl.load();
  $('vmVideoInfo').textContent='Video: '+f.name+' ('+(f.size/1048576).toFixed(1)+' MB)';
  maybeReady();
}
function vmOnMp3(f){
  uploadErr.textContent='';
  const ext=extOf(f.name);
  const looksAudio=f.type.indexOf('audio/')===0||ext==='mp3';
  if(!looksAudio){uploadErr.textContent='That does not look like an MP3. Pick the song file instead.';return;}
  if(f.size>MAX_MP3){uploadErr.textContent='That MP3 is too chunky. Keep it under 30 MB.';return;}
  mp3File=f;mp3Ext=ext||'mp3';mp3Buffer=null;
  if(mp3Url)URL.revokeObjectURL(mp3Url);
  mp3Url=URL.createObjectURL(f);audioEl.src=mp3Url;audioEl.load();
  $('vmMp3Info').textContent='MP3: '+f.name+' ('+(f.size/1048576).toFixed(1)+' MB)';
  maybeReady();
}
bindDrop($('vmVideoDrop'),$('vmVideoFile'),vmOnVideo);
bindDrop($('vmMp3Drop'),$('vmMp3File'),vmOnMp3);
videoEl.addEventListener('loadedmetadata',()=>{videoDur=videoEl.duration||0;placements.forEach(p=>{p.at=Math.min(p.at,Math.max(0,videoDur-0.1))});renderList();});
audioEl.addEventListener('loadedmetadata',()=>{mp3Dur=audioEl.duration||0;selIn=0;selOut=mp3Dur;syncSelUI();decodeMp3();});
toArrangeBtn.addEventListener('click',()=>{uploadSec.classList.add('hidden');arrangeSec.classList.remove('hidden');arrangeSec.scrollIntoView({behavior:'smooth'})});
back1Btn.addEventListener('click',()=>{stopPreview();arrangeSec.classList.add('hidden');uploadSec.classList.remove('hidden')});
/* ---- Snippet selection ---- */
function clampSel(){if(mp3Dur<=0)return;selIn=Math.min(Math.max(0,selIn),mp3Dur);selOut=Math.min(Math.max(0,selOut),mp3Dur);if(selOut-selIn<0.2){if(document.activeElement===inText)selOut=Math.min(mp3Dur,selIn+0.2);else selIn=Math.max(0,selOut-0.2)}}
function syncSelUI(){clampSel();if(mp3Dur>0){selEl.style.left=(selIn/mp3Dur*100)+'%';selEl.style.width=Math.max(0.5,(selOut-selIn)/mp3Dur*100)+'%';}if(document.activeElement!==inText)inText.value=fmt(selIn);if(document.activeElement!==outText)outText.value=fmt(selOut);}
[inText,outText].forEach(el=>el.addEventListener('change',()=>{const a=parseT(inText.value),b=parseT(outText.value);if(!isNaN(a))selIn=a;if(!isNaN(b))selOut=b;syncSelUI();}));
let dragMode=null,dragStartX=0,dragIn=0,dragOut=0;
function waveTime(e){const r=waveWrap.getBoundingClientRect();const x=(e.clientX-r.left)/r.width;return Math.min(1,Math.max(0,x))*mp3Dur;}
waveWrap.addEventListener('pointerdown',e=>{if(mp3Dur<=0)return;const t=waveTime(e);const mid=(selIn+selOut)/2;if(e.target.id==='vmHandleL')dragMode='l';else if(e.target.id==='vmHandleR')dragMode='r';else if(t>selIn&&t<selOut)dragMode='move';else dragMode=t<mid?'l':'r';dragStartX=t;dragIn=selIn;dragOut=selOut;waveWrap.setPointerCapture(e.pointerId);e.preventDefault();});
waveWrap.addEventListener('pointermove',e=>{if(!dragMode)return;const t=waveTime(e);const d=t-dragStartX;if(dragMode==='l')selIn=Math.min(dragIn+d,dragOut-0.2);else if(dragMode==='r')selOut=Math.max(dragOut+d,dragIn+0.2);else{let ni=dragIn+d,no=dragOut+d;if(ni<0){no-=ni;ni=0}if(no>mp3Dur){ni-=no-mp3Dur;no=mp3Dur}selIn=ni;selOut=no}syncSelUI();});
['pointerup','pointercancel'].forEach(ev=>waveWrap.addEventListener(ev,()=>{dragMode=null;}));
let snipStopper=null;
$('vmPlaySnip').addEventListener('click',()=>{if(mp3Dur<=0)return;clampSel();audioEl.currentTime=selIn;audioEl.play();if(snipStopper)audioEl.removeEventListener('timeupdate',snipStopper);snipStopper=()=>{if(audioEl.currentTime>=selOut){audioEl.pause();audioEl.removeEventListener('timeupdate',snipStopper);snipStopper=null;}};audioEl.addEventListener('timeupdate',snipStopper);});
$('vmUseTime').addEventListener('click',()=>{atText.value=fmt(videoEl.currentTime||0);});
/* ---- Placements ---- */
function renderList(){
  if(!placements.length){listEl.innerHTML='<p style="margin:12px 0 0;color:var(--muted)">No snippets yet. Snip a part above and hit Add snippet.</p>';return;}
  listEl.innerHTML=placements.map(p=>'<div class="vm-row" data-id="'+p.id+'"><span class="vm-where">MP3 '+fmt(p.sin)+' to '+fmt(p.sout)+'</span><span>plays at</span><input type="text" class="vm-at" value="'+fmt(p.at)+'" inputmode="decimal" aria-label="Plays at, in the video"><span>in the video</span><button type="button" class="button secondary vm-del">Remove</button></div>').join('');
  listEl.querySelectorAll('.vm-row').forEach(row=>{
    const id=Number(row.dataset.id);const p=placements.find(x=>x.id===id);if(!p)return;
    row.querySelector('.vm-at').addEventListener('change',e=>{const v=parseT(e.target.value);if(!isNaN(v))p.at=Math.min(Math.max(0,v),Math.max(0,videoDur-0.1));e.target.value=fmt(p.at);if(previewOn&&!videoEl.paused)scheduleFrom(videoEl.currentTime);});
    row.querySelector('.vm-del').addEventListener('click',()=>{placements=placements.filter(x=>x.id!==id);renderList();if(previewOn&&!videoEl.paused)scheduleFrom(videoEl.currentTime);});
  });
}
renderList();
$('vmAdd').addEventListener('click',()=>{
  arrangeErr.textContent='';
  if(!mp3File||mp3Dur<=0){arrangeErr.textContent='Pick an MP3 first.';return;}
  clampSel();
  if(selOut-selIn<0.2){arrangeErr.textContent='Make the snippet at least a blink long.';return;}
  let at=parseT(atText.value);if(isNaN(at))at=0;
  at=Math.min(Math.max(0,at),Math.max(0,videoDur-0.1));
  if(videoDur>0&&at>=videoDur){arrangeErr.textContent='That start time is past the end of the video.';return;}
  placements.push({id:++placeN,sin:selIn,sout:selOut,at:at});
  placements.sort((a,b)=>a.at-b.at);
  renderList();
  if(previewOn&&!videoEl.paused)scheduleFrom(videoEl.currentTime);
});
/* ---- Volumes ---- */
function bindVol(rangeId,numId,setter){const r=$(rangeId),n=$(numId);r.addEventListener('input',()=>{setter(Number(r.value));n.value=r.value;applyGains();});n.addEventListener('change',()=>{let v=Math.min(200,Math.max(0,Number(n.value)||0));setter(v);r.value=v;n.value=v;applyGains();});}
bindVol('vmOrigVol','vmOrigVolNum',v=>{origPct=v});
bindVol('vmMp3Vol','vmMp3VolNum',v=>{mp3Pct=v});
/* ---- Live mix preview (approximation, scheduled against the video clock) ---- */
function ensureCtx(){
  if(actx)return true;
  try{
    const AC=window.AudioContext||window.webkitAudioContext;if(!AC)return false;
    actx=new AC();
    origSrcNode=actx.createMediaElementSource(videoEl);
    origGainNode=actx.createGain();mp3GainNode=actx.createGain();
    origSrcNode.connect(origGainNode);origGainNode.connect(actx.destination);
    mp3GainNode.connect(actx.destination);
    applyGains();
    return true;
  }catch(e){return false;}
}
function applyGains(){if(!actx||!origGainNode)return;origGainNode.gain.value=previewOn?origPct/100:1;mp3GainNode.gain.value=mp3Pct/100;}
async function decodeMp3(){
  if(!mp3File)return;
  if(!ensureCtx()){drawWave();return;}
  try{const buf=await mp3File.arrayBuffer();mp3Buffer=await actx.decodeAudioData(buf.slice(0));}catch(e){mp3Buffer=null;}
  drawWave();
}
function drawWave(){
  const ctx2=waveCanvas.getContext('2d');const W=waveCanvas.width,H=waveCanvas.height;
  ctx2.clearRect(0,0,W,H);ctx2.fillStyle='#8f8577';
  if(!mp3Buffer){ctx2.fillRect(0,H/2-1,W,2);return;}
  const ch=mp3Buffer.getChannelData(0);const buckets=180;const step=Math.max(1,Math.floor(ch.length/buckets));const bw=W/buckets;
  for(let i=0;i<buckets;i++){let peak=0;const off=i*step;for(let j=0;j<step;j+=97){const v=Math.abs(ch[off+j]||0);if(v>peak)peak=v;}const h=Math.max(2,peak*(H-8));ctx2.fillRect(i*bw+0.5,(H-h)/2,Math.max(1,bw-1),h);}
}
function stopSnippets(){activeSrcs.forEach(s=>{try{s.stop()}catch(e){}try{s.disconnect()}catch(e){}});activeSrcs=[];}
function scheduleFrom(t){
  stopSnippets();
  if(!previewOn||!actx||!mp3Buffer||videoEl.paused)return;
  placements.forEach(p=>{
    const dur=p.sout-p.sin;const end=p.at+dur;
    let delay=0,offset=0,len=0;
    if(t<p.at){delay=p.at-t;offset=p.sin;len=dur;}
    else if(t<end){delay=0;offset=p.sin+(t-p.at);len=dur-(t-p.at);}
    else return;
    if(len<=0.05)return;
    const src=actx.createBufferSource();src.buffer=mp3Buffer;src.connect(mp3GainNode);
    try{src.start(actx.currentTime+delay,offset,len);}catch(e){return;}
    activeSrcs.push(src);
  });
}
videoEl.addEventListener('play',()=>{if(actx&&actx.state==='suspended')actx.resume();if(previewOn)scheduleFrom(videoEl.currentTime);});
videoEl.addEventListener('pause',stopSnippets);
videoEl.addEventListener('seeked',()=>{if(previewOn&&!videoEl.paused)scheduleFrom(videoEl.currentTime);});
videoEl.addEventListener('ended',stopSnippets);
function stopPreview(){previewOn=false;stopSnippets();applyGains();$('vmPreviewBtn').textContent='Preview the mix: off';}
$('vmPreviewBtn').addEventListener('click',()=>{
  if(previewOn){stopPreview();$('vmPreviewNote').textContent='Preview off. The video plays with its own sound, and the render will mix exactly what you set.';return;}
  if(!ensureCtx()){$('vmPreviewNote').textContent='Live mix preview is not available in this browser. You can still audition the snippet above, and the render will mix exactly what you set.';return;}
  if(!mp3Buffer){decodeMp3();}
  actx.resume();
  previewOn=true;applyGains();
  $('vmPreviewBtn').textContent='Preview the mix: on';
  $('vmPreviewNote').textContent='Preview on: play the video and you will hear its own sound plus your snippets, at the volumes above. It is a live approximation; the render is exact.';
  if(!videoEl.paused)scheduleFrom(videoEl.currentTime);
});
/* ---- Render ---- */
function volStr(pct){return String(Math.round(pct)/100);}
function buildFilter(withOrig){
  const parts=[];
  if(withOrig)parts.push('[0:a]aresample=44100,aformat=channel_layouts=stereo,volume='+volStr(origPct)+'[aorig]');
  else parts.push('anullsrc=channel_layout=stereo:sample_rate=44100,atrim=0:'+(videoDur||0).toFixed(3)+',asetpts=PTS-STARTPTS,volume='+volStr(origPct)+'[aorig]');
  placements.forEach((p,i)=>{
    const d=Math.round(p.at*1000);
    parts.push('[1:a]atrim=start='+p.sin.toFixed(3)+':end='+p.sout.toFixed(3)+',asetpts=PTS-STARTPTS,aresample=44100,aformat=channel_layouts=stereo,volume='+volStr(mp3Pct)+',adelay='+d+'|'+d+'[s'+i+']');
  });
  const labels=['[aorig]'].concat(placements.map((p,i)=>'[s'+i+']')).join('');
  parts.push(labels+'amix=inputs='+(placements.length+1)+':duration=first:normalize=0[aout]');
  return parts.join(';');
}
renderBtn.addEventListener('click',async()=>{
  if(rendering)return;
  renderErr.textContent='';
  if(!videoFile||!mp3File){renderErr.textContent='Pick a video and an MP3 first.';return;}
  if(!placements.length){renderErr.textContent='Add at least one snippet first. A mix with no MP3 is just the video.';return;}
  stopPreview();
  dlLink.classList.add('hidden');resultVideo.classList.add('hidden');
  resultSec.classList.remove('hidden');
  renderBtn.disabled=true;rendering=true;
  let step='starting';
  try{
    step='quota';setProg(0.02,'Checking your actions...');
    const st=await apiCall({action:'status'});
    if(st.response.ok&&st.data.usage&&!st.data.usage.unlimited&&Number(st.data.usage.remaining)<=0)throw{limit:true};
    step='engine';setProg(0.06,'Loading the mixing desk (first run downloads it, about 30 MB)...');
    const ff=await getFFmpeg(s=>setProg(s==='engine'?0.08:0.14,'Loading the mixing desk...'));
    step='reading';setProg(0.18,'Reading your video and MP3...');
    const vName='video.'+videoExt,mName='song.'+mp3Ext;
    await ff.writeFile(vName,new Uint8Array(await videoFile.arrayBuffer()));
    await ff.writeFile(mName,new Uint8Array(await mp3File.arrayBuffer()));
    ff.on('progress',({progress})=>{setProg(0.18+Math.min(1,progress||0)*0.64,'Mixing... '+Math.round((progress||0)*100)+'%')});
    step='rendering';
    const X264=['-c:v','libx264','-preset','veryfast','-crf','23'];
    const COPY=['-c:v','copy'];
    const attempts=[
      {filter:buildFilter(true),vargs:COPY},
      {filter:buildFilter(true),vargs:X264},
      {filter:buildFilter(false),vargs:COPY},
      {filter:buildFilter(false),vargs:X264}
    ];
    let code=-1;
    for(const att of attempts){
      try{await ff.deleteFile('output.mp4')}catch(e){}
      code=await ff.exec(['-i',vName,'-i',mName,'-filter_complex',att.filter,'-map','0:v:0','-map','[aout]'].concat(att.vargs,['-c:a','aac','-b:a','128k','-movflags','faststart','output.mp4']));
      if(code===0)break;
    }
    if(code!==0)throw new Error('encode-exit-'+code);
    step='saving';
    const data=await ff.readFile('output.mp4');
    try{await ff.deleteFile(vName);await ff.deleteFile(mName);await ff.deleteFile('output.mp4')}catch(e){}
    step='metering';setProg(0.94,'Almost done...');
    const key='render-'+Date.now().toString(36)+'-'+(++renderN)+'-'+Math.random().toString(36).slice(2,10);
    const done=await apiCall({action:'render',idempotencyKey:key});
    if(done.response.status===402)throw{limit:true};
    if(!done.response.ok)throw new Error(done.data.error||'metering failed');
    const blob=new Blob([data],{type:'video/mp4'});
    if(resultUrl)URL.revokeObjectURL(resultUrl);
    resultUrl=URL.createObjectURL(blob);
    const base=(videoFile.name||'video').replace(/\.[^.]+$/,'');
    dlLink.href=resultUrl;dlLink.download=base+'-mixed.mp4';dlLink.textContent='Download MP4';
    dlLink.classList.remove('hidden');
    resultVideo.src=resultUrl;resultVideo.classList.remove('hidden');
    setProg(1,'Done.');
    progText.textContent='Your mixed video is ready. One action was used.';
    if(done.data.usage){
      const u=done.data.usage;
      usageEl.textContent=u.unlimited?'Unlimited actions.':u.remaining+' actions left.';
    }
    bbTrack('action_completed',{tool:TOOL_KEY,snippets:placements.length,mb:Math.round(videoFile.size/1048576)});
    resultSec.scrollIntoView({behavior:'smooth',block:'nearest'});
  }catch(e){
    if(e&&e.limit){
      bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});
      document.querySelector('#upgrade-slot').innerHTML=upgradeCard();
      bindUpgradeClicks(document.querySelector('#upgrade-slot'),'limit');
      renderErr.textContent='You are out of actions, so this mix was not started.';
    }else if(step==='engine'||step==='core'||String((e&&e.message)||'').indexOf('script-load:')===0){
      renderErr.textContent='The mixing engine failed to load. Check your connection and try again.';
    }else if(step==='reading'){
      renderErr.textContent='Bum Bum could not read those files. Try an MP4 video and an MP3?';
    }else if(step==='rendering'){
      renderErr.textContent='The mix failed on that video. It might be an unusual format or too large for this device. Try an MP4 under 100 MB?';
    }else{
      renderErr.textContent='Something went wrong finishing the mix. Try again?';
    }
    progWrap.classList.add('hidden');progText.textContent='';
  }finally{rendering=false;renderBtn.disabled=false;}
});
againBtn.addEventListener('click',()=>{
  if(resultUrl){URL.revokeObjectURL(resultUrl);resultUrl='';}
  dlLink.classList.add('hidden');resultVideo.classList.add('hidden');resultVideo.removeAttribute('src');
  renderErr.textContent='';progWrap.classList.add('hidden');progText.textContent='';
  resultSec.classList.add('hidden');arrangeSec.classList.remove('hidden');
  arrangeSec.scrollIntoView({behavior:'smooth'});
});
<?php endif; ?>
</script><?php endif; ?><section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Do I need an account?</summary>
<p>No. Mix videos as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>Do my video and MP3 get uploaded anywhere?</summary>
<p>No. The mixing happens entirely in your browser. Your video and your MP3 never leave your device.</p>
</details>
<details>
<summary>How does the mix work?</summary>
<p>Your video's own sound keeps playing from start to finish at the volume you set. Each snippet you place plays on top of it, at the time you picked, at the MP3 volume you set. Two sliders, one for each, so the balance is yours.</p>
</details>
<details>
<summary>Can I use the same part of the MP3 more than once?</summary>
<p>Yes. Snip a part once, or snip a few different parts, and place each one wherever it belongs in the video. The same chorus can absolutely come back for an encore.</p>
</details>
<details>
<summary>What files can I use?</summary>
<p>Videos in MP4, WebM, MOV, MKV, or AVI, up to 100 MB, and an MP3 for the sound you are laying over it.</p>
</details>
</section>
<section class="panel" aria-label="More tiny tools">
<h2 style="margin-top:0">More tiny tools</h2>
<p><a href="/tools/video-trimmer/">Video Trimmer</a> - Drop in a video, mark where the good part starts and ends, and Bum Bum snips it right in your browser.</p>
<p><a href="/tools/video-converter/">Video Converter</a> - Drop in a video and convert it to MP4, WebM, or animated GIF right in your browser.</p>
<p><a href="/tools/audiogram/">Audiogram Maker</a> - Turn an MP3 into a captioned video. Pick a background, pick a caption style, download the MP4.</p>
</section>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261026r14"></script></body></html>
