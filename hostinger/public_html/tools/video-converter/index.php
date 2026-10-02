<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Video Converter | Leave It to Bum Bum</title>
<meta name="description" content="Wrong format? Fixed. Drop in a video and convert it to MP4, WebM, or animated GIF right in your browser. Your video never leaves your device. Free to try.">
<meta property="og:title" content="Video Converter | Leave It to Bum Bum">
<meta property="og:description" content="Wrong format? Fixed. Drop in a video and convert it to MP4, WebM, or animated GIF right in your browser. Your video never leaves your device. Free to try.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/video-converter/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og-video-converter.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Video Converter | Leave It to Bum Bum">
<meta name="twitter:description" content="Wrong format? Fixed. Drop in a video and convert it to MP4, WebM, or animated GIF right in your browser. Your video never leaves your device. Free to try.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og-video-converter.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/video-converter/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Video Converter",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Wrong format? Fixed. Drop in a video and convert it to MP4, WebM, or animated GIF right in your browser. Your video never leaves your device. Free to try.",
  "url": "https://leaveittobumbum.com/tools/video-converter/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Is Video Converter free?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You get 15 free actions with no signup. Create a free account and you get 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/. One finished conversion uses one action."
      }
    },
    {
      "@type": "Question",
      "name": "Do I need an account?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Convert videos as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "What formats can I convert?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Drop in MP4, WebM, MOV, MKV, or AVI, and get MP4, WebM, or an animated GIF back."
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
      "name": "Why is my GIF only 15 seconds?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "GIFs get chunky fast, so Bum Bum caps them at the first 15 seconds at a tidy size. Pick a short clip for the best GIFs."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Video Converter | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Video Converter","item":"https://leaveittobumbum.com/tools/video-converter/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use Video Converter","description":"Convert a video to MP4, WebM, or animated GIF.","step":[{"@type":"HowToStep","position":1,"name":"Drop in a video (MP4, WebM, MOV, MKV, or AVI, up to 100 MB)."},{"@type":"HowToStep","position":2,"name":"Pick an output format: MP4, WebM, or animated GIF."},{"@type":"HowToStep","position":3,"name":"Bum Bum converts it right in your browser."},{"@type":"HowToStep","position":4,"name":"Download your converted file. One action is used."}]}</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
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
.hidden{display:none!important}
.linklike{background:none;border:0;padding:0;margin-top:14px;color:var(--accent,#b3541e);font:inherit;font-weight:700;cursor:pointer;text-decoration:underline}
#vcConvert{font-size:18px;padding:14px 26px;margin-top:12px}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Video Converter"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-laptop.png" alt="Bum Bum converting video"><h1>Wrong format? Fixed.</h1><p class="lede">Drop in a video, pick MP4, WebM, or animated GIF, and Bum Bum converts it right in your browser. Your video never leaves your device. One finished conversion uses one action.</p>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to use Video Converter</h2><a class="button" href="/account/?next=<?= urlencode('/tools/video-converter/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/video-converter/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/video-converter/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/video-converter/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php $out = empty($usage['unlimited']) && ($usage['remaining'] ?? 0) <= 0; ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<?php if ($out): ?>
<div id="upgrade-slot"></div>
<?php else: ?>
<section class="panel" id="vcUpload">
<h2 style="margin-top:0">1. Your video</h2>
<div class="vc-drop" id="vcDrop" role="button" tabindex="0"><strong>Drop your video here, or tap to pick one</strong><span>MP4, WebM, MOV, MKV, or AVI. Up to 100 MB. Never uploaded anywhere.</span><input type="file" id="vcFile" accept="video/*,.mkv,.avi,.mov"></div>
<p class="vc-fileline" id="vcFileInfo"></p>
<p id="vcUploadErr" class="error"></p>
<div class="vc-step"><button id="vcToFmt" class="button" type="button" disabled>Continue</button></div>
</section>
<section class="panel hidden" id="vcFormat">
<h2 style="margin-top:0">2. Pick a format</h2>
<div class="fmt-grid" id="vcFormats">
<label class="fmt-card sel"><input type="radio" name="vcFmt" value="mp4" checked><span class="swatch">MP4</span><b>MP4</b><small>Plays everywhere. The safe default.</small></label>
<label class="fmt-card"><input type="radio" name="vcFmt" value="webm"><span class="swatch">WebM</span><b>WebM</b><small>Smaller files, great for the web.</small></label>
<label class="fmt-card"><input type="radio" name="vcFmt" value="gif"><span class="swatch">GIF</span><b>GIF</b><small>Animated, no sound. First 15 seconds.</small></label>
</div>
<p id="vcFmtErr" class="error"></p>
<div class="vc-step"><button id="vcConvert" class="button" type="button">Convert my video</button><button id="vcBack1" class="button secondary" type="button">Back</button></div>
<p id="vcUsage"></p>
</section>
<section class="panel hidden" id="vcResult">
<h2 style="margin-top:0">3. Your converted video</h2>
<div class="vc-progress hidden" id="vcProgWrap"><i id="vcProg"></i></div>
<p id="vcProgText" class="vc-status"></p>
<p id="vcConvertErr" class="error"></p>
<div class="vc-step"><a id="vcDl" class="button hidden" download="converted.mp4">Download</a><button id="vcAgain" class="linklike" type="button">convert another</button></div>
</section>
<div id="upgrade-slot"></div>
<?php endif; ?>
<script>
const TOOL_KEY='video-converter';
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
const uploadSec=$('vcUpload'),fmtSec=$('vcFormat'),resultSec=$('vcResult');
const fileInput=$('vcFile'),fileInfo=$('vcFileInfo'),uploadErr=$('vcUploadErr');
const toFmtBtn=$('vcToFmt'),convertBtn=$('vcConvert'),back1Btn=$('vcBack1'),fmtErr=$('vcFmtErr');
const progWrap=$('vcProgWrap'),progBar=$('vcProg'),progText=$('vcProgText'),convertErr=$('vcConvertErr');
const dlLink=$('vcDl'),againBtn=$('vcAgain'),usageEl=$('vcUsage');
const FFMPEG_VENDOR='/tools/video-trimmer/vendor/';
const OK_EXTS=['mp4','webm','mov','mkv','avi'];
const MAX_SIZE=100*1024*1024;
let videoFile=null,videoExt='',outFmt='mp4',ffmpeg=null,converting=false,convN=0;
function setProg(p,txt){progWrap.classList.remove('hidden');progBar.style.width=Math.round(p*100)+'%';progText.textContent=txt;}
function loadScript(src,timeoutMs){return new Promise((res,rej)=>{const s=document.createElement('script');s.src=src;const to=setTimeout(()=>{s.remove();rej(new Error('script-timeout:'+src))},timeoutMs||90000);s.onload=()=>{clearTimeout(to);res()};s.onerror=()=>{clearTimeout(to);rej(new Error('script-load:'+src))};document.head.appendChild(s)})}
async function getFFmpeg(onStage){if(ffmpeg)return ffmpeg;onStage&&onStage('engine');await loadScript(FFMPEG_VENDOR+'ffmpeg.js');if(!window.FFmpegWASM)throw new Error('no-ffmpeg-global');const{FFmpeg}=window.FFmpegWASM;const ff=new FFmpeg();onStage&&onStage('core');await ff.load({coreURL:FFMPEG_VENDOR+'ffmpeg-core.js'});ffmpeg=ff;return ffmpeg;}
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
document.querySelectorAll('#vcFormats .fmt-card').forEach(card=>{
  card.addEventListener('click',()=>{
    document.querySelectorAll('#vcFormats .fmt-card').forEach(c=>c.classList.remove('sel'));
    card.classList.add('sel');card.querySelector('input').checked=true;
    outFmt=card.querySelector('input').value;
  });
});
const OUTS={
  mp4:{file:'output.mp4',mime:'video/mp4',dl:'mp4',args:['-c:v','libx264','-preset','veryfast','-crf','23','-c:a','aac','-b:a','128k','-movflags','faststart']},
  webm:{file:'output.webm',mime:'video/webm',dl:'webm',args:['-c:v','libvpx-vp9','-crf','30','-b:v','0','-c:a','libopus']},
  gif:{file:'output.gif',mime:'image/gif',dl:'gif',args:null}
};
convertBtn.addEventListener('click',async()=>{
  if(converting||!videoFile)return;
  fmtErr.textContent='';convertErr.textContent='';dlLink.classList.add('hidden');
  resultSec.classList.remove('hidden');
  convertBtn.disabled=true;converting=true;
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
    ff.on('progress',({progress})=>{setProg(0.18+Math.min(1,progress||0)*0.64,'Converting to '+outFmt.toUpperCase()+'... '+Math.round((progress||0)*100)+'%')});
    step='converting';
    if(outFmt==='gif'){
      let code=await ff.exec(['-t','15','-i',inName,'-vf','fps=12,scale=480:-1:flags=lanczos,palettegen','palette.png']);
      if(code!==0)throw new Error('palette-exit-'+code);
      code=await ff.exec(['-t','15','-i',inName,'-i','palette.png','-lavfi','fps=12,scale=480:-1:flags=lanczos[x];[x][1:v]paletteuse',out.file]);
      if(code!==0)throw new Error('gif-exit-'+code);
      try{await ff.deleteFile('palette.png')}catch(e){}
    }else{
      const code=await ff.exec(['-i',inName].concat(out.args,[out.file]));
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
      usageEl.textContent=u.unlimited?'Unlimited actions.':u.remaining+' actions left.';
    }
    bbTrack('action_completed',{tool:TOOL_KEY,format:outFmt,mb:Math.round(videoFile.size/1048576)});
    dlLink.scrollIntoView({behavior:'smooth',block:'nearest'});
  }catch(e){
    if(e&&e.limit){
      bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});
      document.querySelector('#upgrade-slot').innerHTML=upgradeCard();
      bindUpgradeClicks(document.querySelector('#upgrade-slot'),'limit');
      convertErr.textContent='You are out of actions, so this conversion was not started.';
    }else if(step==='engine'||step==='core'||String((e&&e.message)||'').indexOf('script-load:')===0){
      convertErr.textContent='The converter engine failed to load. Check your connection and try again.';
    }else if(step==='reading'){
      convertErr.textContent='Bum Bum could not read that file. Try an MP4?';
    }else if(step==='converting'){
      convertErr.textContent='The conversion failed on that video. It might be an unusual format or too large for this device. Try an MP4 under 100 MB?';
    }else{
      convertErr.textContent='Something went wrong finishing the conversion. Try again?';
    }
    progWrap.classList.add('hidden');progText.textContent='';
  }finally{converting=false;convertBtn.disabled=false;}
});
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
<summary>Is Video Converter free?</summary>
<p>You get 15 free actions with no signup. Create a free account and you get 75 actions every month. <a href="/pricing/">See pricing</a>. One finished conversion uses one action.</p>
</details>
<details>
<summary>Do I need an account?</summary>
<p>No. Convert videos as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>What formats can I convert?</summary>
<p>Drop in MP4, WebM, MOV, MKV, or AVI, and get MP4, WebM, or an animated GIF back.</p>
</details>
<details>
<summary>Does my video get uploaded anywhere?</summary>
<p>No. The conversion happens entirely in your browser. Your video never leaves your device.</p>
</details>
<details>
<summary>Why is my GIF only 15 seconds?</summary>
<p>GIFs get chunky fast, so Bum Bum caps them at the first 15 seconds at a tidy size. Pick a short clip for the best GIFs.</p>
</details>
</section>
<section class="panel" aria-label="More tiny tools">
<h2 style="margin-top:0">More tiny tools</h2>
<p><a href="/tools/video-trimmer/">Video Trimmer</a> - Drop in a video, mark where the good part starts and ends, and Bum Bum snips it right in your browser.</p>
<p><a href="/tools/audiogram/">Audiogram</a> - Turn MP3s into captioned videos. Upload audio and a background image, pick a caption style, get a video ready for the feed.</p>
<p><a href="/tools/clips/">Bum Bum Clips</a> - Record your screen right in your browser.</p>
</section>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?><script src="/tools/capture-handoff.js"></script></body></html>
