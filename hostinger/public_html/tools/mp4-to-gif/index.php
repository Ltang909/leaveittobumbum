<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject);
// NOTE (2026-11-01): cross-origin isolation headers (COOP/COEP) were
// tried here to unlock the multithreaded ffmpeg core. In the owner's
// Chrome/macOS every engine load (MT and ST alike) hung under isolation
// while the same files loaded fine un-isolated, so the headers were
// removed. The loader below still prefers the MT core if a future page
// context ever provides isolation some other way; in practice it takes
// the single-threaded core, now with a load timeout as a hang guard.
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>MP4 to GIF Converter | Turn MP4 Clips into Animated GIFs in Your Browser | Leave It to Bum Bum</title>
<meta name="description" content="Convert MP4 to an animated GIF right in your browser: the first 15 seconds at a tidy 480 pixels wide, looping forever. Your video never leaves your device. Free to try.">
<meta property="og:title" content="MP4 to GIF Converter | Turn MP4 Clips into Animated GIFs in Your Browser | Leave It to Bum Bum">
<meta property="og:description" content="Convert MP4 to an animated GIF right in your browser: the first 15 seconds at a tidy 480 pixels wide, looping forever. Your video never leaves your device. Free to try.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/mp4-to-gif/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/mp4-to-gif.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="MP4 to GIF Converter | Turn MP4 Clips into Animated GIFs in Your Browser | Leave It to Bum Bum">
<meta name="twitter:description" content="Convert MP4 to an animated GIF right in your browser: the first 15 seconds at a tidy 480 pixels wide, looping forever. Your video never leaves your device. Free to try.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/mp4-to-gif.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/tools/mp4-to-gif/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "MP4 to GIF Converter",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Convert MP4 to an animated GIF right in your browser: the first 15 seconds at a tidy 480 pixels wide, looping forever. Your video never leaves your device. Free to try.",
  "url": "https://leaveittobumbum.com/tools/mp4-to-gif/"
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
        "text": "No. Convert videos as a guest. It is free, with no limits. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "Why is my GIF only 15 seconds?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "GIFs get heavy fast, so the converter caps them at the first 15 seconds at 480 pixels wide and 12 frames per second. Trim the moment you want first with the Video Trimmer, then convert."
      }
    },
    {
      "@type": "Question",
      "name": "Does my video get uploaded anywhere?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. The conversion happens entirely in your browser. Your video never leaves your device."
      }
    },
    {
      "@type": "Question",
      "name": "How big will my GIF be?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Detail and motion cost more than duration. Measured with this converter: a calm, flat-colour clip runs about 1 MB per 5 seconds, while detailed footage with lots of movement runs about 5 MB per 5 seconds. Short and calm beats long and busy."
      }
    },
    {
      "@type": "Question",
      "name": "Will my GIF have sound?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. GIF has no sound at all; it is a picture format that happens to move. If the sound matters, keep the clip as an MP4 or WebM instead."
      }
    },
    {
      "@type": "Question",
      "name": "How do I GIF a moment that is not at the start of the video?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Trim it first with the Video Trimmer so the moment starts the clip, then convert the trimmed file here."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"MP4 to GIF Converter | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"MP4 to GIF Converter","item":"https://leaveittobumbum.com/tools/mp4-to-gif/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to convert MP4 to GIF","description":"Turn an MP4 clip into an animated GIF in your browser.","step":[{"@type":"HowToStep","position":1,"name":"Drop in an MP4 (or MOV, MKV, or AVI, up to 100 MB). Trim the moment first if it is not at the start."},{"@type":"HowToStep","position":2,"name":"The output is already set to GIF; there is nothing to pick."},{"@type":"HowToStep","position":3,"name":"Bum Bum converts the first 15 seconds right in your browser."},{"@type":"HowToStep","position":4,"name":"Download your GIF. One action is used."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.vc-drop{border:2px dashed var(--line);border-radius:12px;padding:26px 18px;text-align:center;cursor:pointer;background:#fff;margin:0 0 12px}
.vc-drop.over{border-color:var(--accent,#b3541e);background:#fff8f2}
.vc-drop strong{display:block;font-size:17px;margin-bottom:4px}
.vc-drop span{color:var(--muted);font-size:14px}
.vc-drop input{display:none}
.vc-fileline{font-size:14px;font-weight:700;margin:0 0 12px;color:var(--muted)}
.vc-status{font-weight:700;margin:14px 0 0}
.vc-step{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-top:16px}
.fmt-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:10px;margin:12px 0}
.fmt-card{border:2px solid var(--line);border-radius:12px;padding:14px;cursor:pointer;background:#fff}
.fmt-card input{display:none}
.fmt-card.sel{border-color:var(--accent,#b3541e);background:#fff8f2}
.fmt-card b{display:block;font-size:16px;margin-bottom:4px}
.fmt-card small{color:var(--muted);font-size:13px;line-height:1.4;display:block}
.fmt-card .swatch{display:block;height:34px;border-radius:8px;margin-bottom:10px;background:#111;color:#fff;font-weight:900;display:flex;align-items:center;justify-content:center;font-size:13px}
.vc-progress{height:10px;border-radius:999px;background:#eee;overflow:hidden;margin:12px 0}
.vc-progress i{display:block;height:100%;width:0;background:var(--accent,#b3541e);transition:width .2s}
table.ex-table{border-collapse:collapse;width:100%;margin:12px 0;font-size:15px}
table.ex-table th,table.ex-table td{border:1px solid var(--line);padding:8px 10px;text-align:left;background:#fff}
.hidden{display:none!important}
.linklike{background:none;border:0;padding:0;margin-top:14px;color:var(--accent,#b3541e);font:inherit;font-weight:700;cursor:pointer;text-decoration:underline}
#vcConvert{font-size:18px;padding:14px 26px;margin-top:12px}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"MP4 to GIF Converter"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/video-converter.png" alt="MP4 to GIF Converter"><div><h1>MP4 to GIF Converter</h1><p class="lede bb-desc">MP4 in, animated GIF out, right in your browser. The format is already picked; just drop the clip in.</p></div></div>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to convert MP4 to GIF</h2><a class="button" href="/account/?next=<?= urlencode('/tools/mp4-to-gif/') ?>">Sign in or create an account</a></section>
<?php else: ?>
<?php $out = empty($usage['unlimited']) && ($usage['remaining'] ?? 0) <= 0; ?>
<?php if ($out): ?>
<div id="upgrade-slot"></div>
<?php else: ?>
<section class="panel" id="vcUpload">
<h2 style="margin-top:0">1. Your video</h2>
<div class="vc-drop" id="vcDrop" role="button" tabindex="0"><strong>Drop your video here, or tap to pick one</strong><span>MP4, WebM, MOV, MKV, or AVI. Up to 100 MB, and your file is never uploaded anywhere.</span><input type="file" id="vcFile" accept="video/*,.mkv,.avi,.mov"></div>
<p class="vc-fileline" id="vcFileInfo"></p>
<p id="vcUploadErr" class="error"></p>
<div class="vc-step"><button id="vcToFmt" class="button" type="button" disabled>Continue</button></div>
</section>
<section class="panel hidden" id="vcFormat">
<h2 style="margin-top:0">2. Convert to GIF</h2>
<p>Output format: <strong>animated GIF</strong>, the first 15 seconds at 480 pixels wide. Nothing to pick; this page makes one trip. Want MP4 or WebM out instead? The <a href="/tools/video-converter/">Video Converter</a> runs every direction.</p>
<p id="vcFmtErr" class="error"></p>
<div class="vc-step"><button id="vcConvert" class="button" type="button">Convert my video</button><button id="vcBack1" class="button secondary" type="button">Back</button></div>
<p id="vcUsage"></p>
</section>
<section class="panel hidden" id="vcResult">
<h2 style="margin-top:0">3. Your converted video</h2>
<div class="vc-progress hidden" id="vcProgWrap"><i id="vcProg"></i></div>
<p id="vcProgText" class="vc-status"></p>
<p id="vcConvertErr" class="error"></p>
<div class="vc-step"><a id="vcDl" class="button hidden" download="converted.gif">Download</a><button id="vcCancel" class="linklike hidden" type="button">cancel conversion</button><button id="vcAgain" class="linklike" type="button">convert another</button></div>
</section>
<div id="upgrade-slot"></div>
<?php endif; ?>
<script>
const TOOL_KEY='mp4-to-gif';
const PERIOD=<?= json_encode($usage['period'] ?? '') ?>;
if(false){const seen='bb_m80_'+PERIOD;if(!localStorage.getItem(seen)){localStorage.setItem(seen,'1');bbTrack('usage_milestone_80',{tool:TOOL_KEY,used:<?= (int) ($usage['used'] ?? 0) ?>,limit:<?= (int) ($usage['limit'] ?? 0) ?>})}}
function upgradeCard(){return ''}
function bindUpgradeClicks(root,context){root.querySelectorAll('[data-plan]').forEach(a=>a.addEventListener('click',()=>bbTrack('upgrade_clicked',{plan:a.dataset.plan,context:context,tool:TOOL_KEY})))}
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
<?php if ($out): ?>
document.querySelector('#upgrade-slot').innerHTML=upgradeCard();
bindUpgradeClicks(document,'page_load');
bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'page_load'});
<?php else: ?>
bbTrack('tool_opened',{tool:TOOL_KEY});
const $=id=>document.querySelector('#'+id);
const uploadSec=$('vcUpload'),fmtSec=$('vcFormat'),resultSec=$('vcResult');
const fileInput=$('vcFile'),fileInfo=$('vcFileInfo'),uploadErr=$('vcUploadErr');
const toFmtBtn=$('vcToFmt'),convertBtn=$('vcConvert'),back1Btn=$('vcBack1'),fmtErr=$('vcFmtErr');
const progWrap=$('vcProgWrap'),progBar=$('vcProg'),progText=$('vcProgText'),convertErr=$('vcConvertErr');
const dlLink=$('vcDl'),againBtn=$('vcAgain'),usageEl=$('vcUsage'),cancelBtn=$('vcCancel');
const FFMPEG_VENDOR='/tools/video-trimmer/vendor/';
const OK_EXTS=['mp4','webm','mov','mkv','avi'];
const MAX_SIZE=100*1024*1024;
let videoFile=null,videoExt='',outFmt='gif',ffmpeg=null,converting=false,convN=0,cancelled=false,convStart=0;
function setProg(p,txt){progWrap.classList.remove('hidden');progBar.style.width=Math.round(p*100)+'%';progText.textContent=txt;}
function fmtElapsed(ms){const s=Math.max(0,Math.floor(ms/1000));return Math.floor(s/60)+':'+String(s%60).padStart(2,'0');}
function loadScript(src,timeoutMs){return new Promise((res,rej)=>{const s=document.createElement('script');s.src=src;const to=setTimeout(()=>{s.remove();rej(new Error('script-timeout:'+src))},timeoutMs||90000);s.onload=()=>{clearTimeout(to);res()};s.onerror=()=>{clearTimeout(to);rej(new Error('script-load:'+src))};document.head.appendChild(s)})}
let ffLogBuf=[],ffLogOn=false;
async function loadCore(ff,file,ms){let to;const timeout=new Promise((_,rej)=>{to=setTimeout(()=>rej(new Error('core-timeout:'+file)),ms)});try{await Promise.race([ff.load({coreURL:FFMPEG_VENDOR+file,wasmURL:FFMPEG_VENDOR+file.replace(/\.js$/,'.wasm'),workerURL:FFMPEG_VENDOR+file.replace(/\.js$/,'.worker.js')}),timeout]);}finally{clearTimeout(to);}}
function engineError(engine,e){if(window.bbTrack)bbTrack('converter_engine_error',{engine:engine,tool:TOOL_KEY,isolated:String(!!window.crossOriginIsolated),sab:typeof SharedArrayBuffer,message:String((e&&e.message)||e).slice(0,240)});}
async function getFFmpeg(onStage){if(ffmpeg)return ffmpeg;onStage&&onStage('engine');await loadScript(FFMPEG_VENDOR+'ffmpeg.js');if(!window.FFmpegWASM)throw new Error('no-ffmpeg-global');const{FFmpeg}=window.FFmpegWASM;const mk=()=>{const f=new FFmpeg();f.on('log',({message})=>{if(ffLogOn){ffLogBuf.push(String(message));if(ffLogBuf.length>400)ffLogBuf.shift();}});return f;};onStage&&onStage('core');const mtOk=window.crossOriginIsolated&&typeof SharedArrayBuffer!=='undefined';let ff=mk();if(mtOk){try{await loadCore(ff,'ffmpeg-core-mt.js',20000);}catch(e){engineError('mt',e);try{ff.terminate();}catch(_){}if(window.bbTrack)bbTrack('converter_engine_fallback',{from:'mt',tool:TOOL_KEY});ff=mk();try{await loadCore(ff,'ffmpeg-core.js',45000);}catch(e2){engineError('st-after-mt',e2);throw e2;}}}else{try{await loadCore(ff,'ffmpeg-core.js',45000);}catch(e){engineError('st',e);throw e;}}ffmpeg=ff;return ffmpeg;}
async function probeVideo(ff,inName){
  ffLogBuf=[];ffLogOn=true;
  try{await ff.exec(['-i',inName]);}catch(e){}
  ffLogOn=false;
  const text=ffLogBuf.join('\n');
  const vm=text.match(/Video:\s*([a-z0-9_]+)/i);
  const am=text.match(/Audio:\s*([a-z0-9_]+)/i);
  const dm=text.match(/Video:[^\n]*?(\d{3,5})x(\d{3,5})/i);
  const du=text.match(/Duration:\s*(\d+):(\d+):([\d.]+)/i);
  return{vcodec:vm?vm[1].toLowerCase():'',acodec:am?am[1].toLowerCase():'',w:dm?parseInt(dm[1],10):0,h:dm?parseInt(dm[2],10):0,dur:du?(parseInt(du[1],10)*3600+parseInt(du[2],10)*60+parseFloat(du[3])):0};
}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/video-converter.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data};}
function bindDrop(zone,input,onFile){zone.addEventListener('click',()=>input.click());zone.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();input.click()}});['dragover','dragenter'].forEach(ev=>zone.addEventListener(ev,e=>{e.preventDefault();zone.classList.add('over')}));['dragleave','drop'].forEach(ev=>zone.addEventListener(ev,e=>{e.preventDefault();zone.classList.remove('over')}));zone.addEventListener('drop',e=>{const f=e.dataTransfer.files&&e.dataTransfer.files[0];if(f)onFile(f)});input.addEventListener('change',()=>{const f=input.files&&input.files[0];if(f)onFile(f);input.value=''})}
function extOf(name){const m=String(name||'').toLowerCase().match(/\.([a-z0-9]+)$/);return m?m[1]:'';}
function vcOnFile(f){
  uploadErr.textContent='';
  const ext=extOf(f.name);
  const looksVideo=f.type.indexOf('video/')===0||OK_EXTS.indexOf(ext)>=0;
  if(!looksVideo){uploadErr.textContent='That does not look like a video. Try MP4, WebM, MOV, MKV, or AVI.';return;}
  if(f.size>MAX_SIZE){uploadErr.textContent='That video is too chunky. Keep it under 100 MB.';return;}
  videoFile=f;videoExt=OK_EXTS.indexOf(ext)>=0?ext:'mp4';
  fileInfo.textContent='Video: '+f.name+' ('+(f.size/1048576).toFixed(1)+' MB)';
  toFmtBtn.disabled=false;
}
bindDrop($('vcDrop'),fileInput,vcOnFile);
window.__bbCaptureHandoff=function(f){vcOnFile(f);};
toFmtBtn.addEventListener('click',()=>{uploadSec.classList.add('hidden');fmtSec.classList.remove('hidden');fmtSec.scrollIntoView({behavior:'smooth'})});
back1Btn.addEventListener('click',()=>{fmtSec.classList.add('hidden');uploadSec.classList.remove('hidden')});
const OUTS={
  mp4:{file:'output.mp4',mime:'video/mp4',dl:'mp4',args:['-c:v','libx264','-preset','ultrafast','-crf','23','-c:a','aac','-b:a','128k','-movflags','faststart']},
  webm:{file:'output.webm',mime:'video/webm',dl:'webm',args:['-c:v','libvpx-vp9','-deadline','realtime','-cpu-used','7','-crf','32','-b:v','0','-row-mt','1','-c:a','libopus']},
  gif:{file:'output.gif',mime:'image/gif',dl:'gif',args:null}
};
convertBtn.addEventListener('click',async()=>{
  if(converting||!videoFile)return;
  fmtErr.textContent='';convertErr.textContent='';dlLink.classList.add('hidden');
  resultSec.classList.remove('hidden');
  convertBtn.disabled=true;converting=true;cancelled=false;
  let step='starting';
  try{
    step='quota';setProg(0.02,'Checking your actions...');
    const st=await apiCall({action:'status'});
    if(st.response.ok&&st.data.usage&&!st.data.usage.unlimited&&Number(st.data.usage.remaining)<=0)throw{limit:true};
    step='engine';setProg(0.06,'Loading the converter (first run downloads it, about 30 MB)...');
    const ff=await getFFmpeg(s=>setProg(s==='engine'?0.08:0.14,'Loading the converter...'));
    step='reading';setProg(0.18,'Reading your video...');
    const inName='input.'+videoExt;
    await ff.writeFile(inName,new Uint8Array(await videoFile.arrayBuffer()));
    const out=OUTS[outFmt];
    // ffmpeg.wasm's progress ratio explodes on files with odd timestamp
    // metadata (seen live: -949433300%). Trust the ratio only when it is
    // sane; otherwise derive progress from processed time over the probed
    // duration, and never let the bar run backwards.
    let lastP=0,probeDur=0;
    ff.on('progress',({progress,time})=>{
      let p=progress;
      if(!(p>=0&&p<=1.02)){p=(probeDur>0&&time>0)?(time/1e6)/probeDur:lastP;}
      p=Math.max(lastP,Math.min(1,p||0));lastP=p;
      let eta='';
      if(p>0.03&&p<1){const left=(Date.now()-convStart)*(1-p)/p;if(isFinite(left)&&left>5000)eta=' · about '+fmtElapsed(left)+' left';}
      setProg(0.18+p*0.64,'Converting to '+outFmt.toUpperCase()+'... '+Math.round(p*100)+'% · '+fmtElapsed(Date.now()-convStart)+' in'+eta);
    });
    step='converting';convStart=Date.now();cancelBtn.classList.remove('hidden');
    if(outFmt==='gif'){
      let code=await ff.exec(['-t','15','-i',inName,'-vf','fps=12,scale=480:-1:flags=lanczos,palettegen','palette.png']);
      if(code!==0)throw new Error('palette-exit-'+code);
      code=await ff.exec(['-t','15','-i',inName,'-i','palette.png','-lavfi','fps=12,scale=480:-1:flags=lanczos[x];[x][1:v]paletteuse',out.file]);
      if(code!==0)throw new Error('gif-exit-'+code);
      try{await ff.deleteFile('palette.png')}catch(e){}
    }else{
      setProg(0.2,'Checking the video...');
      const probe=await probeVideo(ff,inName);
      probeDur=probe.dur||0;
      const canCopy=(outFmt==='mp4'&&probe.vcodec==='h264'&&(probe.acodec===''||probe.acodec==='aac'))||(outFmt==='webm'&&(probe.vcodec==='vp8'||probe.vcodec==='vp9'||probe.vcodec==='av1')&&(probe.acodec===''||probe.acodec==='opus'||probe.acodec==='vorbis'));
      // The height cap lives inside the encode filter itself, not in
      // the probe: files with thin metadata (every browser screen
      // recording) can leave probe.h at 0, and then an uncapped encode
      // of a 1440p recording runs at a crawl (measured: a 20 second
      // clip not done in 150 s). Cap is 720 (owner's call, 2026-11-01):
      // at 1440p in, 720 out measured ~64 s for 20 s of video vs
      // ~149 s at 1080 out, because the in-browser VP9 decode of the
      // big source dominates either way. min() makes the filter a
      // no-op at 720 and below.
      const scaleArgs=['-vf','scale=-2:min(720\\,ih):flags=lanczos'];
      let code;
      if(canCopy){
        setProg(0.3,'No re-encode needed. Repacking as '+outFmt.toUpperCase()+'...');
        code=await ff.exec(['-i',inName,'-c','copy'].concat(outFmt==='mp4'?['-movflags','faststart']:[],[out.file]));
        if(code!==0){code=await ff.exec(['-i',inName].concat(scaleArgs,out.args,[out.file]));}
      }else{
        code=await ff.exec(['-i',inName].concat(scaleArgs,out.args,[out.file]));
      }
      if(code!==0)throw new Error('encode-exit-'+code);
    }
    step='saving';
    const data=await ff.readFile(out.file);
    try{await ff.deleteFile(inName);await ff.deleteFile(out.file)}catch(e){}
    step='metering';setProg(0.94,'Almost done...');
    const key='convert-'+Date.now().toString(36)+'-'+(++convN)+'-'+Math.random().toString(36).slice(2,10);
    const done=await apiCall({action:'convert',idempotencyKey:key});
    if(done.response.status===402)throw{limit:true};
    if(!done.response.ok)throw new Error(done.data.error||'metering failed');
    const blob=new Blob([data],{type:out.mime});
    if(dlLink.href)URL.revokeObjectURL(dlLink.href);
    const base=(videoFile.name||'video').replace(/\.[^.]+$/,'');
    dlLink.href=URL.createObjectURL(blob);
    dlLink.download=base+'-converted.'+out.dl;
    dlLink.textContent='Download '+outFmt.toUpperCase();
    dlLink.classList.remove('hidden');
    setProg(1,'Done.');
    progText.textContent='Your '+outFmt.toUpperCase()+' is ready. One action was used.';
    if(done.data.usage){
      const u=done.data.usage;
      usageEl.textContent='Free, no limits.';
    }
    bbTrack('action_completed',{tool:TOOL_KEY,format:outFmt,mb:Math.round(videoFile.size/1048576)});
    dlLink.scrollIntoView({behavior:'smooth',block:'nearest'});
  }catch(e){
    if(e&&e.limit){
      bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});
      document.querySelector('#upgrade-slot').innerHTML=upgradeCard();
      bindUpgradeClicks(document.querySelector('#upgrade-slot'),'limit');
      convertErr.textContent='This conversion was not started. Try again?';
    }else if(cancelled){
      convertErr.textContent='';
    }else if(step==='engine'||step==='core'||String((e&&e.message)||'').indexOf('script-load:')===0){
      convertErr.textContent='The converter engine failed to load. Check your connection and try again.';
    }else if(step==='reading'){
      convertErr.textContent='Bum Bum could not read that file. Try an MP4?';
    }else if(step==='converting'){
      convertErr.textContent='The conversion failed on that video. It might be an unusual format or too large for this device. Try an MP4 under 100 MB?';
    }else{
      convertErr.textContent='Something went wrong finishing the conversion. Try again?';
    }
    progWrap.classList.add('hidden');progText.textContent=cancelled?'Conversion cancelled. No action was used.':'';
  }finally{converting=false;convertBtn.disabled=false;cancelBtn.classList.add('hidden');}
});
cancelBtn.addEventListener('click',()=>{if(!converting)return;cancelled=true;try{if(ffmpeg)ffmpeg.terminate();}catch(e){}ffmpeg=null;});
againBtn.addEventListener('click',()=>{
  if(dlLink.href){URL.revokeObjectURL(dlLink.href);dlLink.href='';}
  videoFile=null;videoExt='';dlLink.classList.add('hidden');
  fileInfo.textContent='';uploadErr.textContent='';convertErr.textContent='';usageEl.textContent='';
  progWrap.classList.add('hidden');progText.textContent='';
  resultSec.classList.add('hidden');fmtSec.classList.add('hidden');uploadSec.classList.remove('hidden');
  toFmtBtn.disabled=true;
  uploadSec.scrollIntoView({behavior:'smooth'});
});
<?php endif; ?>
</script><?php endif; ?><section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Do I need an account?</summary>
<p>No. Convert videos as a guest. It is free, with no limits. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>Why is my GIF only 15 seconds?</summary>
<p>GIFs get heavy fast, so the converter caps them at the first 15 seconds at 480 pixels wide and 12 frames per second. Trim the moment you want first with the <a href="/tools/video-trimmer/">Video Trimmer</a>, then convert.</p>
</details>
<details>
<summary>Does my video get uploaded anywhere?</summary>
<p>No. The conversion happens entirely in your browser. Your video never leaves your device.</p>
</details>
<details>
<summary>How big will my GIF be?</summary>
<p>Detail and motion cost more than duration. Measured with this converter: a calm, flat-colour clip runs about 1 MB per 5 seconds, while detailed footage with lots of movement runs about 5 MB per 5 seconds. Short and calm beats long and busy.</p>
</details>
<details>
<summary>Will my GIF have sound?</summary>
<p>No. GIF has no sound at all; it is a picture format that happens to move. If the sound matters, keep the clip as an MP4 or WebM instead.</p>
</details>
<details>
<summary>How do I GIF a moment that is not at the start of the video?</summary>
<p>Trim it first with the <a href="/tools/video-trimmer/">Video Trimmer</a> so the moment starts the clip, then convert the trimmed file here.</p>
</details>
</section>
<section class="panel" aria-label="Guide">
<h2 style="margin-top:0">MP4 to GIF, the short guide</h2>
<h3>How it works here</h3>
<p>Drop in an MP4 (MOV, MKV, and AVI work too) and get an animated GIF back: the first 15 seconds, at 480 pixels wide and 12 frames per second, converted entirely in your browser. Your video never leaves your device and Bum Bum keeps no copy. Changing your mind mid-conversion costs nothing. If the moment you want is not at the start of the clip, trim it first with the <a href="/tools/video-trimmer/">Video Trimmer</a>; a tight four-second GIF beats a wandering fifteen-second one.</p>
<h3>How big will my GIF be?</h3>
<p>Measured with this exact converter, at its real settings:</p>
<table class="ex-table">
<tr><th>Clip type</th><th>5 seconds</th><th>10 seconds</th></tr>
<tr><td>Calm, flat-colour clip</td><td>About 1 MB</td><td>About 2 MB</td></tr>
<tr><td>Detailed footage, lots of movement</td><td>About 3 to 5 MB</td><td>About 8 to 10 MB</td></tr>
</table>
<p>Detail and motion cost more than duration. A busy five seconds can outweigh a calm fifteen, because every frame of a GIF is a full picture. If a GIF comes out too heavy for the chat or page it is headed to, the fixes are a shorter moment, a calmer moment, or an MP4 instead.</p>
<h3>Why GIFs are heavy (and why they sometimes look grainy)</h3>
<p>A GIF frame holds at most 256 colours, chosen from your clip. The converter builds that colour palette from your actual footage first, in two passes, which keeps skin tones and gradients looking like themselves instead of banding into stripes. Fine detail and confetti-style motion still push against the 256-colour ceiling; that grain is the format, not your video.</p>
<h3>GIF or MP4?</h3>
<table class="ex-table">
<tr><th>Use a GIF when</th><th>Use an MP4 when</th></tr>
<tr><td>Posting in chat apps, GitHub READMEs, docs, and forums, where it plays inline and loops with no controls</td><td>Posting on a web page or social feed, where the file stays far smaller</td></tr>
<tr><td>The clip is short, silent, and the loop is the point</td><td>The sound matters, or the clip runs past a few seconds</td></tr>
<tr><td>Email, where video embeds are unreliable but images are not</td><td>Anywhere a play button is acceptable</td></tr>
</table>
<p>Our blog covers the same ground with more examples: <a href="/blog/mp4-to-gif/">how to convert MP4 to GIF</a>.</p>
</section>
<?php $bbTool = 'mp4-to-gif'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
