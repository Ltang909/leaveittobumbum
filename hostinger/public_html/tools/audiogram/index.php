<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Audiogram Maker | Turn an MP3 Into a Captioned Video | Leave It to Bum Bum</title>
<meta name="description" content="Turn MP3s into videos, with captions or without. Upload audio and a background image, pick a caption style or none at all, get a video ready for the feed. Free to try.">
<meta property="og:title" content="Audiogram Maker | Turn an MP3 Into a Captioned Video | Leave It to Bum Bum">
<meta property="og:description" content="Turn MP3s into videos, with captions or without. Upload audio and a background image, pick a caption style or none at all, get a video ready for the feed. Free to try.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/audiogram/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/audiogram.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Audiogram Maker | Turn an MP3 Into a Captioned Video | Leave It to Bum Bum">
<meta name="twitter:description" content="Turn MP3s into captioned videos. Upload audio and a background image, pick a caption style, get a video ready for the feed. Free to try.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/audiogram.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/tools/audiogram/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Audiogram Maker",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Turn MP3s into videos, with captions or without. Upload audio and a background image, pick a caption style or none at all, get a video ready for the feed. Free to try.",
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
        "text": "With captions, it is only transcribed to make them, and never kept. Pick No captions and your audio never leaves your browser at all."
      }
    },
    {
      "@type": "Question",
      "name": "What caption styles are there?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Four caption styles, or No captions: just your image and your sound, with no transcription at all."
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
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Audiogram Maker | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Audiogram Maker","item":"https://leaveittobumbum.com/tools/audiogram/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use Audiogram Maker","description":"Turn an MP3 into a video, with captions or without.","step":[{"@type":"HowToStep","position":1,"name":"Upload an MP3 and a background image."},{"@type":"HowToStep","position":2,"name":"Bum Bum transcribes your audio, or skips it entirely with No captions; the audio itself is never kept."},{"@type":"HowToStep","position":3,"name":"Pick a caption style (or none) and aspect ratio."},{"@type":"HowToStep","position":4,"name":"Download your video, ready for the feed."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
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
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
.ag-lyric-sec{font-weight:800;margin:14px 0 4px}
.ag-lyric-row{display:flex;gap:8px;align-items:center;margin:6px 0}
.ag-lyric-time{font-size:12px;color:var(--muted);min-width:36px;font-weight:700}
.ag-lyric-row input{flex:1}
#agSeek{margin:10px 0 2px}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Audiogram Maker"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/audiogram.png" alt="Audiogram Maker"><div><h1>Audiogram Maker</h1><p class="lede bb-desc">MP3 in. Video out. Upload an MP3 and a background image, and Bum Bum builds the captioned video.</p></div></div>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to use Audiogram Maker</h2><a class="button" href="/account/?next=<?= urlencode('/tools/audiogram/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/audiogram/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/audiogram/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage && (int) $usage['used'] >= 2): ?><div class="nudge bb-guest-nudge" data-remaining="<?= (int) $usage['remaining'] ?>"><button type="button" class="bb-guest-nudge-dismiss" aria-label="Dismiss" style="float:right;border:none;background:none;cursor:pointer;font:inherit;font-weight:900;opacity:.55;padding:0 0 0 12px">&times;</button>You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions left. <a href="/account/?next=<?= urlencode('/tools/audiogram/') ?>">Create a free account</a> to save your work and keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php $out = empty($usage['unlimited']) && ($usage['remaining'] ?? 0) <= 0; ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<?php if ($out): ?>
<div id="upgrade-slot"></div>
<?php else: ?>
<section class="panel" id="agUpload">
<h2 style="margin-top:0">1. Your ingredients</h2>
<div class="ag-drop" id="agMp3Drop" role="button" tabindex="0"><strong>Drop your MP3 here, or tap to pick one</strong><span>Up to 25 MB. Sent for transcription only with captions; with No captions it never leaves your browser.</span><input type="file" id="agMp3" accept="audio/mpeg,.mp3"></div>
<p class="ag-fileline" id="agMp3Info"></p>
<div class="ag-drop" id="agImgDrop" role="button" tabindex="0"><strong>Drop a background image here, or tap to pick one</strong><span>Your image stays in your browser and is never uploaded anywhere.</span><input type="file" id="agImg" accept="image/*"></div>
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
<label class="style-card"><input type="radio" name="agStyle" value="none"><span class="swatch" style="background:#0f3d2e">&#9835; no words</span><b>No captions</b><small>Just your image and your sound. No transcription, nothing uploaded.</small></label>
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
<input type="range" id="agSeek" min="0" max="0" step="0.1" value="0" aria-label="Seek through the track">
<p id="agStatus" class="ag-status">Have a look. When it feels right, render it.</p>
<div class="ag-progress hidden" id="agProgWrap"><i id="agProg"></i></div>
<p id="agProgText" style="font-weight:700"></p>
<p id="agLive" class="ag-status hidden" aria-live="polite"></p>
<div class="ag-step"><button id="agEditLyrics" class="button secondary hidden" type="button">Edit lyrics</button></div>
<div id="agLyricEditor" class="hidden">
<p class="ag-fileline">Fix any words the transcription heard wrong. Clear a line to remove it. The highlight timing adjusts to your words.</p>
<div id="agLyricFields"></div>
<div class="ag-step"><button id="agLyricSave" class="button" type="button">Save lyrics</button><button id="agLyricCancel" class="linklike" type="button">Cancel</button></div>
</div>
<div class="ag-step"><button id="agRender" class="button" type="button">Render video</button><a id="agDl" class="button secondary hidden" download="audiogram.mp4">Download MP4</a><button id="agAgain" class="linklike" type="button">start over</button></div>
<p id="agRenderErr" class="error"></p>
</section>
<div id="upgrade-slot"></div>
<?php endif; ?>
<script src="/tools/live-transcribe.js"></script>
<script>
const TOOL_KEY='audiogram';
const PERIOD=<?= json_encode($usage['period'] ?? '') ?>;
if(<?= $low ? 'true' : 'false' ?>){const seen='bb_m80_'+PERIOD;if(!localStorage.getItem(seen)){localStorage.setItem(seen,'1');bbTrack('usage_milestone_80',{tool:TOOL_KEY,used:<?= (int) ($usage['used'] ?? 0) ?>,limit:<?= (int) ($usage['limit'] ?? 0) ?>})}}
function upgradeCard(){return `<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) ($usage['limit'] ?? 75) ?> free actions this month. Operator gives you unlimited actions plus a custom tool built for you in 36 hours for $49/month.</p><p><a class="button" data-plan="operator" href="/checkout/?plan=operator">Get Operator, $49/mo</a></p><p><a href="/account/">See your usage</a></p></div>`}
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
const canvas=$('agCanvas'),playBtn=$('agPlay'),timeEl=$('agTime'),statusEl=$('agStatus'),seekEl=$('agSeek');
const renderBtn=$('agRender'),dlLink=$('agDl'),againBtn=$('agAgain'),renderErr=$('agRenderErr');
const progWrap=$('agProgWrap'),progBar=$('agProg'),progText=$('agProgText'),usageEl=$('agUsage'),liveEl=$('agLive');
const editLyricsBtn=$('agEditLyrics'),lyricEditor=$('agLyricEditor'),lyricFields=$('agLyricFields'),lyricSaveBtn=$('agLyricSave'),lyricCancelBtn=$('agLyricCancel');
const FFMPEG_VENDOR='/tools/video-trimmer/vendor/';
let audioFile=null,bgImg=null,audioUrl=null,duration=0,words=[],cues=[],peaks=[],capSizeMemo={};
let style='pop',aspect='9:16',audioEl=null,rafId=0,playing=false,seeking=false;
let actx=null,mediaSrc=null,ffmpeg=null,renderKey=null,rendering=false,drawPaused=false;
function setStatus(m,isErr){statusEl.textContent=m;statusEl.classList.toggle('error',!!isErr);}
function fmtT(s){s=Math.max(0,s||0);const m=Math.floor(s/60),sec=Math.floor(s%60);return m+':'+String(sec).padStart(2,'0');}
// MP4 first: most current browsers can record MP4 directly, and an MP4
// recording only needs a quick remux at the end instead of a full
// WebM-to-MP4 transcode in the wasm converter (the slow path that used
// to time out on song-length tracks). WebM stays as the fallback.
function pickRecorderMime(){const c=['video/mp4','video/webm;codecs=vp8,opus','video/webm;codecs=vp9,opus','video/webm'];for(const t of c){if(window.MediaRecorder&&MediaRecorder.isTypeSupported(t))return t;}return '';}
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
    transcribeBtn.textContent=style==='none'?'Continue without captions':'Transcribe my MP3';
  });
});
$('agAspect').addEventListener('change',e=>{aspect=e.target.value;});
// Caption lines break where the singer pauses: a gap of LINE_GAP or
// more before the next word always starts a new line, so a line never
// mixes the end of one sung line with the start of the next. Inside a
// line the size caps still apply. Lines are then grouped into sections
// at gaps of SECTION_GAP or more, and the caption styles show one
// section at a time, so a verse stays on screen as a block and clears
// before the next section starts.
const LINE_GAP=0.7,SECTION_GAP=2.0;
function groupCues(ws){
  const out=[];let cur=null;
  const push=()=>{if(cur&&cur.words.length){cur.text=cur.words.map(w=>w.w).join(' ');out.push(cur);}cur=null;};
  for(const w of ws){
    const wlen=w.w.length;
    if(!cur){cur={words:[w],start:w.start,end:w.end,chars:wlen};}
    else if(w.start-cur.end>=LINE_GAP||cur.words.length>=6||cur.chars+wlen+1>32){push();cur={words:[w],start:w.start,end:w.end,chars:wlen};}
    else{cur.words.push(w);cur.end=w.end;cur.chars+=wlen+1;}
  }
  push();
  assignSections(out);
  return out;
}
function assignSections(out){
  let sec=-1,prev=null;
  for(const c of out){
    if(!prev||c.start-prev.end>=SECTION_GAP)sec++;
    c.sec=sec;prev=c;
  }
  for(const c of out)c.secLines=out.filter(o=>o.sec===c.sec);
}
function openLyrics(){
  lyricFields.innerHTML='';
  let lastSec=-1;
  cues.forEach((c,i)=>{
    if(c.sec!==lastSec){lastSec=c.sec;const h=document.createElement('p');h.className='ag-lyric-sec';h.textContent='Section '+(c.sec+1);lyricFields.appendChild(h);}
    const row=document.createElement('div');row.className='ag-lyric-row';
    const lab=document.createElement('span');lab.className='ag-lyric-time';lab.textContent=fmtT(c.start);
    const inp=document.createElement('input');inp.type='text';inp.value=c.text;inp.dataset.cue=i;inp.setAttribute('aria-label','Lyric line at '+fmtT(c.start));
    row.appendChild(lab);row.appendChild(inp);lyricFields.appendChild(row);
  });
  lyricEditor.classList.remove('hidden');
  lyricEditor.scrollIntoView({behavior:'smooth',block:'nearest'});
}
function retimeWords(text,start,end,orig,prevEnd){
  // Words that are still there, in order, keep their exact times.
  // Only new words get fitted in, between their kept neighbours or
  // just before the first kept word. (Spreading every word across
  // the line's slot, the old way, shifted words the user never
  // touched, so one small edit threw the whole line off the singing.)
  const parts=text.split(/\s+/).filter(Boolean);
  const used=new Array(orig.length).fill(false);
  const kept=parts.map(p=>{
    const lp=p.toLowerCase();
    for(let j=0;j<orig.length;j++){if(!used[j]&&orig[j].w.toLowerCase()===lp){used[j]=true;return {w:p,start:orig[j].start,end:orig[j].end};}}
    return {w:p,start:null,end:null};
  });
  if(kept.every(k=>k.start===null)){
    // The whole line was rewritten: spread across its slot by length.
    const weights=parts.map(p=>p.length+1),tot=weights.reduce((a,b)=>a+b,0),span=Math.max(0.01,end-start);
    let t=start;
    return parts.map((p,i)=>{const d=span*weights[i]/tot;const w={w:p,start:t,end:t+d};t+=d;return w;});
  }
  let prevT=prevEnd;
  for(let i=0;i<kept.length;i++){
    if(kept[i].start!==null){prevT=kept[i].end;continue;}
    let j=i;while(j<kept.length&&kept[j].start===null)j++;
    const nextT=j<kept.length?kept[j].start:end;
    const room=Math.max(0.05*(j-i),Math.min(nextT-prevT,0.3*(j-i)));
    const ws2=kept.slice(i,j).map(k=>k.w.length+1),tot=ws2.reduce((x,y)=>x+y,0);
    let t=nextT-room;
    for(let k=i;k<j;k++){const d=room*ws2[k-i]/tot;kept[k].start=t;kept[k].end=t+d;t+=d;}
    prevT=nextT;i=j-1;
  }
  return kept;
}
function saveLyrics(){
  const out=[];
  lyricFields.querySelectorAll('input[data-cue]').forEach(inp=>{
    const c=cues[Number(inp.dataset.cue)],text=inp.value.trim();
    if(!c||!text)return;
    const prevEnd=out.length?out[out.length-1].end:0;
    const words=retimeWords(text,c.start,c.end,c.words,prevEnd);
    out.push({words,start:words[0].start,end:words[words.length-1].end,text});
  });
  assignSections(out);
  cues=out;capSizeMemo={};
  lyricEditor.classList.add('hidden');
  setStatus('Lyrics updated. The preview and your render will use your words.');
}
editLyricsBtn.addEventListener('click',openLyrics);
lyricSaveBtn.addEventListener('click',saveLyrics);
lyricCancelBtn.addEventListener('click',()=>lyricEditor.classList.add('hidden'));
function repairWordTimes(ws){
  // Whisper sometimes stretches words across a whole intro or
  // interlude. Borrowed Hours arrived with an 11-second "There's"
  // covering the music before the vocal starts, and Go Make Something
  // arrived with "She bought" tiled across the intro as 1.9s + 8.7s,
  // which left "She" stranded at 0:00 in its own section while the
  // phrase is really sung at 10 seconds. Detect long words, grow the
  // run to any neighbours that tile onto it and are themselves long,
  // then re-anchor the whole run to its end when the singing
  // continues right after it, at a typical word length taken from
  // the tight words nearby. A run followed by a real pause keeps the
  // older per-word treatment.
  const dur=w=>w.end-w.start;
  const nominalAt=i=>{
    const near=[];
    for(let j=Math.max(0,i-6);j<Math.min(ws.length,i+7);j++){const d=dur(ws[j]);if(d<=1.2)near.push(d);}
    near.sort((a,b)=>a-b);
    return near.length?Math.min(0.8,Math.max(0.25,near[Math.floor(near.length/2)])):0.35;
  };
  const done=new Array(ws.length).fill(false);
  for(let i=0;i<ws.length;i++){
    if(done[i]||dur(ws[i])<=2)continue;
    let a=i,b=i;
    while(a>0&&ws[a].start-ws[a-1].end<=0.05&&dur(ws[a-1])>1)a--;
    while(b<ws.length-1&&ws[b+1].start-ws[b].end<=0.05&&dur(ws[b+1])>1)b++;
    const after=b<ws.length-1?ws[b+1].start-ws[b].end:0;
    if(after<=0.3){
      let t=ws[b].end;
      for(let k=b;k>=a;k--){const n=nominalAt(k);ws[k].end=t;ws[k].start=t-n;t=ws[k].start;done[k]=true;}
    }else{
      for(let k=a;k<=b;k++){
        if(dur(ws[k])>2){
          const n=nominalAt(k);
          const gapBefore=ws[k].start-(k>0?ws[k-1].end:0);
          const gapAfter=(k<ws.length-1?ws[k+1].start:Infinity)-ws[k].end;
          if(gapAfter<=gapBefore)ws[k].start=ws[k].end-n;else ws[k].end=ws[k].start+n;
        }
        done[k]=true;
      }
    }
  }
  return ws;
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
  const noCaptions=style==='none';
  setStatus(noCaptions?'Reading your MP3...':'Listening to your MP3...');studioSec.classList.remove('hidden');
  try{
    if(noCaptions){
      const dec=await decodePeaks(audioFile);
      duration=dec.duration||0;peaks=dec.peaks;words=[];cues=[];
      if(!duration)throw new Error('could not read that MP3');
      bbTrack('captions_skipped',{tool:TOOL_KEY,seconds:Math.round(duration)});
    }else{
      const session=await fetch('/api/session.php').then(r=>r.json());
      const fd=new FormData();fd.append('action','transcribe');fd.append('audio',audioFile,audioFile.name);fd.append('csrf',session.csrf||'');
      const[resp,dec]=await Promise.all([
        fetch('/api/tools/audiogram.php',{method:'POST',body:fd}).then(async r=>({ok:r.ok,status:r.status,data:await r.json().catch(()=>({}))})),
        decodePeaks(audioFile)
      ]);
      if(!resp.ok)throw new Error(resp.data.error||('transcription failed ('+resp.status+')'));
      words=repairWordTimes(resp.data.words||[]);duration=dec.duration||resp.data.duration||0;peaks=dec.peaks;
      if(!words.length||!duration)throw new Error('no words heard');
      cues=groupCues(words);capSizeMemo={};
      bbTrack('transcription_completed',{tool:TOOL_KEY,words:words.length,seconds:Math.round(duration)});
    }
    setupStudio();
    styleSec.classList.add('hidden');studioSec.scrollIntoView({behavior:'smooth'});
    setStatus(noCaptions?'No captions. Your image, your sound, nothing else.':'Have a look. When it feels right, render it.');
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
  audioEl.addEventListener('timeupdate',()=>{timeEl.textContent=fmtT(audioEl.currentTime)+' / '+fmtT(duration);if(!seeking)seekEl.value=audioEl.currentTime;});
  audioEl.addEventListener('ended',()=>{playing=false;playBtn.textContent='Play';audioEl.currentTime=0;});
  // Drop the old WebAudio tap: it belongs to the previous audio element,
  // and render() rebuilds it for the current one. Keeping it would record silence.
  if(mediaSrc){try{mediaSrc.disconnect();}catch(e){}mediaSrc=null;}
  timeEl.textContent='0:00 / '+fmtT(duration);
  seekEl.max=duration;seekEl.value=0;
  playBtn.textContent='Play';playing=false;
  dlLink.classList.add('hidden');renderErr.textContent='';progText.textContent='';progWrap.classList.add('hidden');
  editLyricsBtn.classList.toggle('hidden',!cues.length);lyricEditor.classList.add('hidden');
  cancelAnimationFrame(rafId);
  const loop=()=>{if(!drawPaused)drawFrame(audioEl?audioEl.currentTime:0);rafId=requestAnimationFrame(loop);};
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
  const cue=cueAt(t);
  if(cue)drawCaption(ctx,W,H,cue,t,style);
  else drawCountdown(ctx,W,H,t);
}
function drawCountdown(ctx,W,H,t){
  // Karaoke lead-in: when a line follows a break of 4+ seconds with no
  // singing, count 3, 2, 1 into it so the first highlighted word never
  // lands cold. Applies at the start of the song and after interludes.
  if(!cues.length||style==='none')return;
  let prevEnd=0,next=null;
  for(const c of cues){if(c.start>t){next=c;break;}prevEnd=c.end;}
  if(!next)return;
  const remain=next.start-t;
  if(remain>3||remain<=0||next.start-prevEnd<4)return;
  const n=Math.ceil(remain),frac=remain-Math.floor(remain);
  const size=W/4*(0.82+0.18*frac);
  ctx.font='900 '+size+'px "Nunito Sans",sans-serif';
  ctx.textAlign='center';ctx.textBaseline='middle';
  ctx.lineWidth=Math.max(3,size/11);ctx.strokeStyle='rgba(0,0,0,0.85)';
  ctx.fillStyle='rgba(255,255,255,0.95)';
  ctx.strokeText(n,W/2,H*0.60);ctx.fillText(n,W/2,H*0.60);
  ctx.textAlign='left';ctx.textBaseline='alphabetic';
}
function secWindow(lines,active){
  if(lines.length<=5)return{lines,active};
  const start=Math.min(Math.max(0,active-2),lines.length-5);
  return{lines:lines.slice(start,start+5),active:active-start};
}
function longestCueText(){return cues.reduce((a,c)=>c.text.length>a.text.length?c:a).text;}
function songSize(sty,W){
  // One font size per style for the whole song, fitted to the longest
  // caption line anywhere in it, so the text never grows or shrinks
  // from section to section. Memoized once the webfont has loaded;
  // before that the measurement would use a fallback font.
  const key=sty+':'+W;
  if(capSizeMemo[key])return capSizeMemo[key];
  const conf={pop:[0.92,W/13],clean:[0.86,W/17],neon:[0.9,W/14],minimal:[0.86,Math.max(13,W/30)]}[sty]||[0.9,W/14];
  const raw=longestCueText();
  const txt=(sty==='pop'||sty==='neon')?raw.toUpperCase():raw;
  const s=fitFont(canvas.getContext('2d'),txt,W*conf[0],conf[1]);
  if(document.fonts&&document.fonts.status==='loaded')capSizeMemo[key]=s;
  return s;
}
function drawCaption(ctx,W,H,cue,t,sty){
  // Show the whole section the current line belongs to. The line being
  // sung gets the style's full treatment; the section's other lines sit
  // dimmed behind it, and the block clears when the section ends.
  const allLines=cue.secLines||[cue];
  const win=secWindow(allLines,allLines.indexOf(cue));
  const lines=win.lines,active=win.active;
  if(sty==='pop'){
    const size=songSize('pop',W);
    ctx.font='900 '+size+'px "Nunito Sans",sans-serif';
    ctx.textBaseline='middle';
    const gap=ctx.measureText(' ').width;
    const lh=size*1.32,y0=H*0.60-(lines.length-1)*lh/2;
    ctx.lineWidth=Math.max(2,size/9);ctx.strokeStyle='rgba(0,0,0,0.85)';
    lines.forEach((c,li)=>{
      const y=y0+li*lh;
      if(li===active){
        const widths=c.words.map(w=>ctx.measureText(w.w.toUpperCase()).width);
        const total=widths.reduce((a,b)=>a+b,0)+gap*(c.words.length-1);
        let x=(W-total)/2;ctx.textAlign='left';
        c.words.forEach((w,i)=>{
          const ww=w.w.toUpperCase();
          ctx.fillStyle=(w.end<=t||(t>=w.start&&t<=w.end))?'#ffd93d':'#ffffff';
          ctx.strokeText(ww,x,y);ctx.fillText(ww,x,y);
          x+=widths[i]+gap;
        });
      }else{
        ctx.textAlign='center';ctx.fillStyle='rgba(255,255,255,0.45)';
        ctx.strokeText(c.text.toUpperCase(),W/2,y);ctx.fillText(c.text.toUpperCase(),W/2,y);
      }
    });
  }else if(sty==='clean'){
    const size=songSize('clean',W);
    ctx.font='700 '+size+'px "Nunito Sans",sans-serif';
    ctx.textAlign='center';ctx.textBaseline='middle';
    const lh=size*1.35,bh=lines.length*lh+size*0.7;
    const by=H-bh-H*0.10;
    roundRect(ctx,W*0.05,by,W*0.9,bh,14);ctx.fillStyle='rgba(0,0,0,0.72)';ctx.fill();
    lines.forEach((c,li)=>{
      ctx.fillStyle=li===active?'#ffffff':'rgba(255,255,255,0.5)';
      ctx.fillText(c.text,W/2,by+size*0.35+lh*(li+0.5));
    });
  }else if(sty==='neon'){
    const size=songSize('neon',W);
    ctx.font='800 '+size+'px "Nunito Sans",sans-serif';
    ctx.textAlign='center';ctx.textBaseline='middle';
    const lh=size*1.32,y0=H*0.60-(lines.length-1)*lh/2;
    lines.forEach((c,li)=>{
      const y=y0+li*lh;
      if(li===active){
        ctx.shadowColor='#ff4fd8';ctx.shadowBlur=size*0.55;
        ctx.fillStyle='#ffffff';
        ctx.fillText(c.text.toUpperCase(),W/2,y);
        ctx.shadowColor='#7b5cff';ctx.shadowBlur=size*1.1;
        ctx.fillText(c.text.toUpperCase(),W/2,y);
      }else{
        ctx.shadowColor='#ff4fd8';ctx.shadowBlur=size*0.25;
        ctx.fillStyle='rgba(255,255,255,0.4)';
        ctx.fillText(c.text.toUpperCase(),W/2,y);
      }
    });
    ctx.shadowBlur=0;
  }else{
    const size=songSize('minimal',W);
    ctx.font='600 '+size+'px "Nunito Sans",sans-serif';
    ctx.textAlign='left';ctx.textBaseline='alphabetic';
    ctx.shadowColor='rgba(0,0,0,0.8)';ctx.shadowBlur=6;
    lines.forEach((c,li)=>{
      ctx.fillStyle=li===active?'rgba(255,255,255,0.94)':'rgba(255,255,255,0.45)';
      ctx.fillText(c.text,W*0.07,H-H*0.09-(lines.length-1-li)*size*1.4);
    });
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
  let webmMB='';
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
    // Always rebuild the tap for the CURRENT audio element. The old code kept
    // the first element's tap across "start over", so later renders recorded
    // a silent audio track from a dead element.
    if(mediaSrc){try{mediaSrc.disconnect();}catch(e){}mediaSrc=null;}
    mediaSrc=actx.createMediaElementSource(audioEl);mediaSrc.connect(actx.destination);
    const dest=actx.createMediaStreamDestination();
    mediaSrc.connect(dest);
    // 15 fps is plenty for a still image, waveform and captions, and it
    // halves the frames the converter has to chew through afterwards.
    const vstream=canvas.captureStream(15);
    const combined=new MediaStream([...vstream.getVideoTracks(),...dest.stream.getAudioTracks()]);
    const mime=pickRecorderMime();
    const rec=new MediaRecorder(combined,mime?{mimeType:mime,videoBitsPerSecond:2000000}:undefined);
    const chunks=[];
    rec.ondataavailable=e=>{if(e.data&&e.data.size)chunks.push(e.data);};
    const stopped=new Promise(res=>{rec.onstop=res;});
    audioEl.currentTime=0;
    setProg(0.18,'Recording your video...');
    rec.start(250);
    const wallStart=performance.now();
    if(style!=='none'&&window.BBLiveTranscribe){
      liveEl.classList.remove('hidden');
      liveEl.innerHTML='<span class="bb-dot"></span>Listening... words will appear here in about 5 seconds, then keep updating while your video records.';
      BBLiveTranscribe.start({stream:new MediaStream(dest.stream.getAudioTracks()),lang:'en',mode:'relay',onText:(f,i)=>{const t=(f+(i?(f?' ':'')+i:'')).trim();if(t)liveEl.textContent='Live words: '+t;}});
    }
    await audioEl.play();playing=true;playBtn.textContent='Pause';
    // The recording must never outlive the track. 'ended' is the primary stop,
    // but if the browser fires it late (or never), these backstops end the
    // recording instead of producing a runaway video.
    let stopReason='',resolveEnded=null;
    const finishRecording=reason=>{if(stopReason)return;stopReason=reason;clearInterval(tickInt);audioEl.onended=null;if(resolveEnded)resolveEnded();};
    const tick=()=>{
      if(!rendering||!audioEl||stopReason)return;
      const ct=audioEl.currentTime,wall=(performance.now()-wallStart)/1000;
      setProg(0.18+0.6*(ct/Math.max(1,duration)),'Recording your video... '+fmtT(ct)+' / '+fmtT(duration));
      if(duration>0&&ct>=Math.max(0.5,duration-0.15))finishRecording('end');
      else if(duration>0&&wall>duration+25)finishRecording('wall');
      else if(wall>20&&duration>0&&ct<wall*0.4&&ct<duration-2)finishRecording('slow');
    };
    const tickInt=setInterval(tick,400);
    await new Promise(res=>{resolveEnded=res;audioEl.onended=()=>finishRecording('end');});
    rec.stop();await stopped;
    // Recording is done: freeze the preview painter so the converter gets
    // the CPU instead of competing with full-canvas redraws.
    drawPaused=true;
    if(stopReason==='slow')throw{slow:true};
    mediaSrc.disconnect(dest);
    playing=false;playBtn.textContent='Play';
    setProg(0.82,'Converting to MP4... 0%');
    const webm=new Blob(chunks,{type:rec.mimeType||'video/webm'});
    webmMB=(webm.size/1048576).toFixed(0);
    setProg(0.80,'Checking the recording has sound...');
    try{
      const detBuf=await actx.decodeAudioData(await webm.arrayBuffer());
      let peak=0;
      for(let ch=0;ch<detBuf.numberOfChannels&&peak<0.003;ch++){
        const d=detBuf.getChannelData(ch);
        for(let i=0;i<d.length&&peak<0.003;i+=97){const v=Math.abs(d[i]);if(v>peak)peak=v;}
      }
      if(peak<0.003)throw{silent:true};
    }catch(se){if(se&&se.silent)throw se;}
    setProg(0.81,'Preparing '+webmMB+' MB of video for conversion...');
    const isMp4=(webm.type||'').indexOf('mp4')>=0;
    const inName=isMp4?'input.mp4':'input.webm';
    await ff.writeFile(inName,new Uint8Array(await webm.arrayBuffer()));
    const totalUs=Math.max(1,Math.round((duration||0)*1e6));
    const convProg=({time})=>{
      const cp=Math.min(0.999,Math.max(0,(Number(time)||0)/totalUs));
      setProg(0.82+0.18*cp,'Converting to MP4... '+Math.round(cp*100)+'%');
    };
    ff.on('progress',convProg);
    // Watchdog: a stuck conversion used to hang the page forever. Bound it and fail loudly.
    // An MP4 recording skips the transcode entirely: the video stream is
    // copied as-is and only the audio is encoded to AAC, which takes
    // seconds. If a recorder's MP4 is not directly usable, fall back to
    // the full transcode on the same file.
    const convTimeoutMs=Math.min(1200000,Math.max(300000,(duration||60)*3000));
    let convTimer=null;
    const convTimeoutP=new Promise((_,rej)=>{convTimer=setTimeout(()=>rej({timeout:true}),convTimeoutMs);});
    const transcodeArgs=['-i',inName,'-c:v','libx264','-preset','ultrafast','-crf','23','-c:a','aac','-b:a','128k','-movflags','faststart','output.mp4'];
    const remuxArgs=['-i',inName,'-c:v','copy','-c:a','aac','-b:a','128k','-movflags','+faststart','output.mp4'];
    let code=1;
    try{
      code=await Promise.race([ff.exec(isMp4?remuxArgs:transcodeArgs),convTimeoutP]);
      if(code!==0&&isMp4)code=await Promise.race([ff.exec(transcodeArgs),convTimeoutP]);
    }finally{clearTimeout(convTimer);try{ff.off('progress',convProg);}catch(_){}}
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
    if(e&&e.silent){
      renderErr.textContent='Bum Bum recorded silence instead of your audio, so this export was stopped before it could make a dead video. Hard-refresh this page (Ctrl/Cmd+Shift+R) and render again. If it keeps happening, tell me which browser and device you are on.';
      setStatus('The recording came back silent. Nothing was rendered.',true);
    }else if(e&&e.slow){
      renderErr.textContent='Your audio played back much slower than real time, so the render was stopped instead of making a broken video. This usually means the browser or your audio output is struggling - try closing other tabs, disconnecting any Bluetooth audio devices, or switching browsers, then render again. If it keeps happening, tell me which browser and device you are on.';
      setStatus('The audio played too slowly to render. Nothing was rendered.',true);
    }else if(e&&e.timeout){
      try{if(ffmpeg&&typeof ffmpeg.terminate==='function')ffmpeg.terminate();}catch(_){}
      ffmpeg=null;
      renderErr.textContent='The MP4 conversion took too long and was stopped before it could finish. Your files are safe - try closing other tabs and rendering again. If it keeps happening, tell me which browser and device you are on.';
      setStatus('The conversion timed out. Nothing was rendered.',true);
    }else if(e&&e.limit){
      bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});
      document.querySelector('#upgrade-slot').innerHTML=upgradeCard();
      bindUpgradeClicks(document.querySelector('#upgrade-slot'),'limit');
      renderErr.textContent='You are out of actions, so this render was not started.';
    }else{
      renderErr.textContent='Render hiccup. Your files are safe, try again.';
      setStatus('Render hiccup. Your files are safe, try again.',true);
    }
    progWrap.classList.add('hidden');progText.textContent='';
  }finally{if(window.BBLiveTranscribe)BBLiveTranscribe.stop();rendering=false;renderBtn.disabled=false;drawPaused=false;}
});
seekEl.addEventListener('input',()=>{if(!audioEl)return;seeking=true;audioEl.currentTime=Number(seekEl.value);timeEl.textContent=fmtT(audioEl.currentTime)+' / '+fmtT(duration);});
seekEl.addEventListener('change',()=>{seeking=false;});
againBtn.addEventListener('click',()=>{
  cancelAnimationFrame(rafId);
  if(audioEl){audioEl.pause();audioEl=null;}
  if(audioUrl){URL.revokeObjectURL(audioUrl);audioUrl=null;}
  audioFile=null;bgImg=null;words=[];cues=[];peaks=[];duration=0;rendering=false;capSizeMemo={};
  mp3Info.textContent='';imgInfo.textContent='';uploadErr.textContent='';renderErr.textContent='';
  usageEl.textContent='';dlLink.classList.add('hidden');liveEl.classList.add('hidden');liveEl.textContent='';
  editLyricsBtn.classList.add('hidden');lyricEditor.classList.add('hidden');
  studioSec.classList.add('hidden');styleSec.classList.add('hidden');uploadSec.classList.remove('hidden');
  toStyleBtn.disabled=true;setStatus('Have a look. When it feels right, render it.');
  uploadSec.scrollIntoView({behavior:'smooth'});
});
<?php endif; ?>
</script><?php endif; ?><section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Do I need an account?</summary>
<p>No. Make videos as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>What happens to my audio?</summary>
<p>With captions, it is only transcribed to make them, and never kept. Pick No captions and your audio never leaves your browser at all.</p>
</details>
<details>
<summary>What caption styles are there?</summary>
<p>Four caption styles, or No captions: just your image and your sound, with no transcription at all.</p>
</details>
<details>
<summary>What video sizes can I make?</summary>
<p>Vertical, square, and widescreen, ready for any feed.</p>
</details>
</section>
<section class="panel" aria-label="Guide">
<h2 style="margin-top:0">Turning audio into video, the short guide</h2>
<h3>How it works here</h3>
<p>Upload an MP3 and a background image, pick a caption style, and download a captioned MP4 ready for the feed. The transcription that makes the captions is automatic, and your audio is never stored after. Setting up and previewing are free; one finished video uses one action.</p>
<h3>Pick the shape first</h3>
<p>Vertical for Reels and TikTok, square for feeds. Caption styles and sizes cover the usual formats, but the shape decision comes before all of them. Choosing the wrong shape means rendering the video again, so pick the shape before anything else.</p>
<h3>Readable captions</h3>
<p>Choose a background with a calm middle. Animated captions are hard to follow over a busy photo, however good the audio is.</p>
</section>
<?php $bbTool = 'audiogram'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
