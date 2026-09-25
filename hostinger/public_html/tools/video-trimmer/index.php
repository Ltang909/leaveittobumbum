<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $user = currentUser(); $usage = $user ? usageFor(billingUser($user)) : null; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Video Trimmer | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px;align-items:center}
#preview{width:100%;max-height:380px;border-radius:12px;border:1px solid #e2d7bf;margin-top:12px;background:#000;display:block}
.trim-grid{display:grid;gap:12px;margin-top:14px}
@media(min-width:760px){.trim-grid{grid-template-columns:1fr 1fr}}
.trim-grid label{font-weight:700;font-size:14px;display:block}
.trim-grid input[type=text]{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px;font-variant-numeric:tabular-nums}
.trim-grid input[type=text]:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.trim-grid input[type=range]{width:100%;margin-top:8px;accent-color:#2f2a22}
.time-hint{font-size:13px;opacity:.7;margin-top:4px}
.progress-wrap{margin-top:14px}
.progress-bar{height:10px;border-radius:999px;background:#f0e8d4;overflow:hidden}
#barFill{height:100%;width:0%;border-radius:999px;background:#2f2a22;transition:width .2s}
#progText{font-size:13px;font-weight:700;margin:8px 0 0;opacity:.75}
.error{color:#b3261e;font-weight:700}
.hidden{display:none!important}
.result-card{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:14px;background:#fff;padding:16px;margin-top:14px}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Video Trimmer"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-laptop.png" alt="Bum Bum with a laptop, ready to edit"><h1>Keep the good part.</h1><p class="lede">Drop in a video, mark where the good part starts and ends, and Bum Bum snips it <b>right in your browser</b>. Your video never leaves your device. One action per trim.</p>
<?php if (!$user): ?><section class="panel"><h2>Sign in to trim videos</h2><a class="button" href="/account/?next=<?= urlencode('/tools/video-trimmer/') ?>">Sign in or create an account</a></section><?php else: ?>
<?php $low = $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>

<section class="panel" id="uploadPanel">
<h2>Pick a video</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Choose a video file">
<p style="font-size:40px;margin:0">🎬</p>
<p><b>Drop a video here</b>, or pick one below.</p>
<p class="meta" style="font-size:13px;opacity:.75">MP4, MOV, WebM and friends. Big files take a minute on phones.</p>
</div>
<input type="file" id="fileInput" accept="video/*" class="hidden">
<div class="btnrow"><button type="button" class="button secondary" id="btnPick">Choose video</button></div>
<p id="fileError" class="error"></p>
</section>

<section class="panel hidden" id="trimPanel">
<h2>Mark the good part</h2>
<video id="preview" controls playsinline></video>
<div class="trim-grid">
<div><label>Starts at<input type="text" id="startText" value="0:00.0" inputmode="decimal"></label><input type="range" id="startRange" min="0" max="100" step="0.1" value="0"><p class="time-hint">Tip: type seconds or m:ss, like 75 or 1:15</p></div>
<div><label>Ends at<input type="text" id="endText" value="0:00.0" inputmode="decimal"></label><input type="range" id="endRange" min="0" max="100" step="0.1" value="0"></div>
</div>
<div class="btnrow">
<button type="button" class="button secondary" id="previewBtn">Play selection</button>
<button type="button" class="button" id="trimBtn">Trim it (1 action)</button>
<button type="button" class="button secondary" id="newBtn">New video</button>
</div>
<div class="progress-wrap hidden" id="trimProgress"><div class="progress-bar"><div id="barFill"></div></div><p id="progText">Warming up...</p></div>
<p id="trimError" class="error"></p>
<div class="result-card hidden" id="resultCard">
<h2 style="margin-top:0">Done, download it</h2>
<video id="resultVideo" controls playsinline style="width:100%;max-height:300px;border-radius:10px;background:#000"></video>
<div class="btnrow"><a class="button" id="dlLink" download>Download trimmed video</a></div>
</div>
</section>
<?php endif; ?>
</main>
<script>
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/video-trimmer.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}
bbTrack('tool_opened',{tool:'video-trimmer'});

const dropzone=document.querySelector('#dropzone'),fileInput=document.querySelector('#fileInput'),fileError=document.querySelector('#fileError');
const trimPanel=document.querySelector('#trimPanel'),preview=document.querySelector('#preview');
const startText=document.querySelector('#startText'),endText=document.querySelector('#endText'),startRange=document.querySelector('#startRange'),endRange=document.querySelector('#endRange');
const previewBtn=document.querySelector('#previewBtn'),trimBtn=document.querySelector('#trimBtn'),newBtn=document.querySelector('#newBtn');
const trimProgress=document.querySelector('#trimProgress'),barFill=document.querySelector('#barFill'),progText=document.querySelector('#progText'),trimError=document.querySelector('#trimError');
const resultCard=document.querySelector('#resultCard'),resultVideo=document.querySelector('#resultVideo'),dlLink=document.querySelector('#dlLink');
let duration=0,objectUrl=null,resultUrl=null,ffmpeg=null,trimKey=0,ffLogs=[];
const FFMPEG_VENDOR='/tools/video-trimmer/vendor/';
function loadScript(src,timeoutMs){return new Promise((res,rej)=>{const s=document.createElement('script');s.src=src;const to=setTimeout(()=>{s.remove();rej(new Error('script-timeout:'+src))},timeoutMs||60000);s.onload=()=>{clearTimeout(to);res()};s.onerror=()=>{clearTimeout(to);rej(new Error('script-load:'+src))};document.head.appendChild(s)})}
async function fetchFile(file){return new Uint8Array(await file.arrayBuffer())}
async function getFFmpeg(onStage){
  if(ffmpeg)return ffmpeg;
  onStage&&onStage('engine');
  await loadScript(FFMPEG_VENDOR+'ffmpeg.js');
  if(!window.FFmpegWASM)throw new Error('no-ffmpeg-global');
  const{FFmpeg}=window.FFmpegWASM;const ff=new FFmpeg();
  ff.on('log',({message})=>{ffLogs.push(String(message));if(ffLogs.length>40)ffLogs.shift()});
  onStage&&onStage('core');
  await ff.load({coreURL:FFMPEG_VENDOR+'ffmpeg-core.js'});
  ffmpeg=ff;return ffmpeg;
}

function fmt(s){s=Math.max(0,s);const m=Math.floor(s/60),sec=(s%60);return m+':'+(sec<10?'0':'')+sec.toFixed(1)}
function parse(v){v=String(v).trim();if(!v)return NaN;if(v.includes(':')){const parts=v.split(':');if(parts.length!==2)return NaN;const m=parseFloat(parts[0]),s=parseFloat(parts[1]);if(isNaN(m)||isNaN(s)||s<0||s>=60)return NaN;return m*60+s}const n=parseFloat(v);return isNaN(n)?NaN:n}
function clampTimes(){let a=parse(startText.value),b=parse(endText.value);if(isNaN(a))a=0;if(isNaN(b))b=duration;a=Math.min(Math.max(0,a),duration);b=Math.min(Math.max(0,b),duration);if(a>=b){if(document.activeElement===startText||document.activeElement===startRange)a=Math.max(0,b-0.5);else b=Math.min(duration,a+0.5)}startText.value=fmt(a);endText.value=fmt(b);startRange.value=a;endRange.value=b;return[a,b]}

if(dropzone){
  dropzone.addEventListener('click',()=>fileInput.click());
  dropzone.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();fileInput.click()}});
  ['dragover','dragenter'].forEach(ev=>dropzone.addEventListener(ev,e=>{e.preventDefault();dropzone.classList.add('over')}));
  ['dragleave','drop'].forEach(ev=>dropzone.addEventListener(ev,e=>{e.preventDefault();dropzone.classList.remove('over')}));
  dropzone.addEventListener('drop',e=>{const f=e.dataTransfer.files&&e.dataTransfer.files[0];if(f)loadFile(f)});
  document.querySelector('#btnPick').addEventListener('click',()=>fileInput.click());
  fileInput.addEventListener('change',()=>{const f=fileInput.files&&fileInput.files[0];if(f)loadFile(f);fileInput.value=''});
}
function loadFile(f){
  fileError.textContent='';trimError.textContent='';resultCard.classList.add('hidden');
  if(!f.type.startsWith('video/')){fileError.textContent='That does not look like a video file.';return}
  if(objectUrl)URL.revokeObjectURL(objectUrl);
  objectUrl=URL.createObjectURL(f);preview.src=objectUrl;preview.load();
  preview.onloadedmetadata=()=>{duration=preview.duration||0;startRange.max=duration;endRange.max=duration;startText.value=fmt(0);endText.value=fmt(duration);startRange.value=0;endRange.value=duration;document.querySelector('#uploadPanel').classList.add('hidden');trimPanel.classList.remove('hidden');window.scrollTo({top:trimPanel.offsetTop-20,behavior:'smooth'})};
  preview.onerror=()=>{fileError.textContent='Bum Bum could not open that video. Try an MP4?';
  };
  preview.dataset.name=f.name;preview.dataset.file=f.name;
  preview._file=f;
}
[startText,endText].forEach(el=>el.addEventListener('change',clampTimes));
startRange.addEventListener('input',()=>{startText.value=fmt(parseFloat(startRange.value));clampTimes()});
endRange.addEventListener('input',()=>{endText.value=fmt(parseFloat(endRange.value));clampTimes()});
previewBtn.addEventListener('click',()=>{const[a,b]=clampTimes();preview.currentTime=a;preview.play();const stop=()=>{if(preview.currentTime>=b){preview.pause();preview.removeEventListener('timeupdate',stop)}};preview.addEventListener('timeupdate',stop)});
newBtn.addEventListener('click',()=>{trimPanel.classList.add('hidden');document.querySelector('#uploadPanel').classList.remove('hidden');resultCard.classList.add('hidden');if(resultUrl){URL.revokeObjectURL(resultUrl);resultUrl=null}});

trimBtn.addEventListener('click',async()=>{
  trimError.textContent='';const[a,b]=clampTimes();
  if(b-a<0.2){trimError.textContent='Make the selection at least a blink long.';return}
  const f=preview._file;if(!f){trimError.textContent='Pick a video first.';return}
  trimBtn.disabled=true;trimProgress.classList.remove('hidden');barFill.style.width='2%';
  let step='starting';
  try{
    step='quota';progText.textContent='Checking your actions...';
    const st=await apiCall({action:'status'});
    if(st.response.ok&&st.data.usage&&Number(st.data.usage.remaining)<=0)throw new Error('actions');
    step='engine';progText.textContent='Loading the trimmer (first trim downloads it, about 30 MB)...';barFill.style.width='8%';
    const ff=await getFFmpeg(s=>{step=s});
    step='reading';barFill.style.width='18%';progText.textContent='Reading your video...';ffLogs.length=0;
    ff.on('progress',({progress})=>{barFill.style.width=(18+Math.min(1,progress||0)*72)+'%';progText.textContent='Snipping... '+Math.round((progress||0)*100)+'%'});
    await ff.writeFile('input',await fetchFile(f));
    step='trimming';
    const dur=(b-a).toFixed(2);
    const code=await ff.exec(['-i','input','-ss',a.toFixed(2),'-t',dur,'-c:v','libx264','-preset','veryfast','-crf','23','-c:a','aac','-movflags','faststart','output.mp4']);
    if(code!==0)throw new Error('encode-exit-'+code);
    step='saving';
    const out=await ff.readFile('output.mp4');
    try{await ff.deleteFile('input');await ff.deleteFile('output.mp4')}catch(e){}
    step='metering';progText.textContent='Almost done...';barFill.style.width='94%';
    const key='trim-'+Date.now().toString(36)+'-'+(++trimKey);
    const{response,data}=await apiCall({action:'trim',idempotencyKey:key});
    if(response.status===402)throw new Error('actions');
    if(!response.ok)throw new Error(data.error||'metering failed');
    const blob=new Blob([out],{type:'video/mp4'});
    if(resultUrl)URL.revokeObjectURL(resultUrl);
    resultUrl=URL.createObjectURL(blob);resultVideo.src=resultUrl;
    const base=(f.name||'video').replace(/\.[^.]+$/,'');
    dlLink.href=resultUrl;dlLink.download=base+'-trimmed.mp4';
    resultCard.classList.remove('hidden');barFill.style.width='100%';progText.textContent='Done.';
    bbTrack('trim_done',{seconds:Math.round(b-a)});
    resultCard.scrollIntoView({behavior:'smooth',block:'center'});
  }catch(e){
    console.warn('trim failed at step '+step+': '+(e&&e.message),ffLogs.slice(-6));
    if(e.message==='actions')trimError.textContent='Out of actions for this month.';
    else if(step==='engine'||step==='core'||String(e.message).indexOf('script-load:')===0)trimError.textContent='The trimmer engine failed to load. Check your connection and try again.';
    else if(step==='reading')trimError.textContent='Bum Bum could not read that file. Try an MP4?';
    else if(step==='trimming')trimError.textContent='The snip failed on that video. It might be an unusual format or too large for this device. Try an MP4, or a shorter clip?';
    else trimError.textContent='Something went wrong finishing the trim. Try again?';
  }finally{trimBtn.disabled=false;setTimeout(()=>trimProgress.classList.add('hidden'),900)}
});
</script></main></body></html>
