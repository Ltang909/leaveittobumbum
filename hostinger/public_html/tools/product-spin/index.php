<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>360 Spin | Turn Product Photos Into a Spinning View | Leave It to Bum Bum</title>
<meta name="description" content="Line up photos of your product from a few angles, or drop in one short video, and Bum Bum builds an interactive 360 spin plus a GIF and video for your listings. Everything happens in your browser: your media never leaves your device.">
<meta property="og:title" content="360 Spin | Turn Product Photos Into a Spinning View | Leave It to Bum Bum">
<meta property="og:description" content="Line up photos of your product from a few angles, or drop in one short video, and Bum Bum builds an interactive 360 spin plus a GIF and video for your listings. Everything happens in your browser: your media never leaves your device.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/product-spin/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og-receipt-reader.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="360 Spin | Turn Product Photos Into a Spinning View | Leave It to Bum Bum">
<meta name="twitter:description" content="Line up photos of your product from a few angles, or drop in one short video, and Bum Bum builds an interactive 360 spin plus a GIF and video for your listings. Everything happens in your browser: your media never leaves your device.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og-receipt-reader.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/product-spin/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "360 Spin",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Line up photos of your product from a few angles, or drop in one short video, and Bum Bum builds an interactive 360 spin plus a GIF and video for your listings. Everything happens in your browser: your media never leaves your device.",
  "url": "https://leaveittobumbum.com/tools/product-spin/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Where do my photos and videos go?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Nowhere. The spin is built entirely in your browser, on your device. Your photos and videos are never uploaded, never stored, and never shared. The only thing that talks to Bum Bum is the one action counted when you download a spin file."
      }
    },
    {
      "@type": "Question",
      "name": "How many photos do I need?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "At least 4 angles to make a spin. Twelve to twenty-four photos make it noticeably smoother. Short on photos? Drop in one video of you walking around the product (under 60 seconds) and Bum Bum pulls the frames out for you."
      }
    },
    {
      "@type": "Question",
      "name": "What is Rock mode?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Full Spin turns all the way around in a loop, which suits a full set of angles. Rock swings gently back and forth instead, which is kinder when you only photographed partway around the product and the jump back to the start would look odd."
      }
    },
    {
      "@type": "Question",
      "name": "Where can I use the spin files?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "The GIF drops into marketplace listings, emails, and social posts like any image. The video file suits Reels, TikTok, and Pinterest. The spin on this page is your preview: drag it to check every angle before you download."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"360 Spin | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"360 Spin","item":"https://leaveittobumbum.com/tools/product-spin/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use 360 Spin","description":"Turn product photos or a short video into an interactive spinning view, right in your browser.","step":[{"@type":"HowToStep","position":1,"name":"Add photos of your product from different angles, or add one short video walking around it."},{"@type":"HowToStep","position":2,"name":"Bum Bum lines the frames up in your browser. Your media never leaves your device."},{"@type":"HowToStep","position":3,"name":"Drag the preview to spin it. Pick a shape, a speed, and Full Spin or Rock."},{"@type":"HowToStep","position":4,"name":"Download a GIF or a video file for your listings and posts."}]}</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px;align-items:center}
.error{color:#b3261e;font-weight:700}
.hidden{display:none!important}
.controls{display:flex;gap:10px;flex-wrap:wrap;align-items:stretch;margin-top:16px}
.tool-group{display:flex;gap:10px;align-items:center;flex-wrap:wrap;background:#fff;border:2px solid var(--line);border-radius:12px;padding:8px 12px}
.tool-group .lbl{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;opacity:.55;flex:none}
.seg{display:flex;height:44px;border:2px solid var(--ink);border-radius:12px;overflow:hidden;background:#fff;flex:none}
.seg button{border:0;background:transparent;margin:0;padding:0 14px;font:inherit;font-weight:800;cursor:pointer;height:100%;display:flex;align-items:center;color:var(--ink)}
.seg button.on{background:var(--ink);color:#fff}
.canvas-wrap{background:#fff;border:2px solid var(--ink);border-radius:14px;padding:10px;box-shadow:4px 4px 0 var(--ink);margin-top:16px}
#spinCanvas{display:block;width:100%;height:auto;border-radius:8px;background:#fff;cursor:grab;touch-action:none}
#spinCanvas:active{cursor:grabbing}
.hint{font-size:13px;opacity:.7;margin:8px 0 0}
.strip{display:flex;gap:8px;overflow-x:auto;padding:8px 2px 4px}
.thumb{position:relative;flex:none;width:64px;height:64px;border:2px solid var(--line);border-radius:10px;overflow:hidden;background:#fff}
.thumb img{width:100%;height:100%;object-fit:cover;display:block}
.thumb button{position:absolute;top:2px;right:2px;width:20px;height:20px;border-radius:50%;border:2px solid var(--ink);background:#fff;color:var(--ink);font:inherit;font-size:12px;font-weight:900;line-height:1;cursor:pointer;padding:0;display:flex;align-items:center;justify-content:center}
.prog{height:10px;background:#eee3c8;border-radius:6px;overflow:hidden;margin-top:10px}
.prog>i{display:block;height:100%;background:var(--ink);width:0;transition:width .2s}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
.shell .button:disabled{opacity:.45;cursor:default;box-shadow:none}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
.panel h2{margin-top:0}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"360 Spin"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-sunglasses.png" alt="Bum Bum wearing sunglasses, ready to show off a product"><h1>Your product, spinning like the big stores.</h1><p class="lede bb-desc">Take photos of your product as you walk around it, or record one short video, and Bum Bum turns them into an interactive 360 spin you can drag with a finger, plus a GIF and a video file for your listings and posts. Everything happens <b>right here in your browser</b>: your photos and videos <b>never leave your device</b>. Building and playing with your spin is free. Downloading a spin file uses one action.</p>
<?php if ($isGuest && $usage && (int) $usage['used'] >= 2): ?><div class="nudge bb-guest-nudge" data-remaining="<?= (int) $usage['remaining'] ?>"><button type="button" class="bb-guest-nudge-dismiss" aria-label="Dismiss" style="float:right;border:none;background:none;cursor:pointer;font:inherit;font-weight:900;opacity:.55;padding:0 0 0 12px">&times;</button>You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions left. <a href="/account/?next=<?= urlencode('/tools/product-spin/') ?>">Create a free account</a> to save your work and keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>

<section class="panel" id="spinPanel">
<h2>Start with photos or a video</h2>
<div class="seg" role="group" aria-label="Spin source">
<button type="button" id="tabPhotos" class="on">Photos</button>
<button type="button" id="tabVideo">Video</button>
</div>
<div id="photosPane" style="margin-top:14px">
<div class="dropzone" id="photoDrop" role="button" tabindex="0" aria-label="Choose product photos">
<p style="font-size:40px;margin:0">🔄</p>
<p><b>Drop your angle photos here</b>, or pick them below.</p>
<p class="meta" style="font-size:13px;opacity:.75">Four or more photos, taken stepping around the product. They are used in the order you add them, and they stay on your device.</p>
</div>
<input type="file" id="photoInput" accept="image/*" multiple class="hidden">
<div class="btnrow">
<button type="button" class="button secondary" id="btnPhotos">Choose photos</button>
</div>
</div>
<div id="videoPane" class="hidden" style="margin-top:14px">
<div class="dropzone" id="videoDrop" role="button" tabindex="0" aria-label="Choose a product video">
<p style="font-size:40px;margin:0">🎥</p>
<p><b>Drop one short video here</b>, or pick one below.</p>
<p class="meta" style="font-size:13px;opacity:.75">Walk slowly around your product while filming. Under 60 seconds and 100 MB. Bum Bum pulls 24 frames out of it, right in your browser.</p>
</div>
<input type="file" id="videoInput" accept="video/mp4,video/webm" class="hidden">
<div class="btnrow">
<button type="button" class="button secondary" id="btnVideo">Choose a video</button>
</div>
</div>
<div id="sceneSlot" style="margin-top:14px"></div>
<p id="toolError" class="error"></p>
<p id="workStatus" class="hint hidden"></p>
<div id="frameArea" class="hidden">
<p style="margin:16px 0 0"><b>Your frames</b> <span class="hint" style="margin:0" id="frameCount"></span></p>
<div class="strip" id="frameStrip"></div>
<div class="btnrow">
<button type="button" class="button secondary" id="btnReverse">Reverse order</button>
<button type="button" class="button secondary hidden" id="btnMorePhotos">Add more photos</button>
</div>
<p class="hint">Frames play in this order. If the spin turns the wrong way, Reverse order or Flip direction below will set it right.</p>
</div>
<div id="stage" class="hidden">
<div class="canvas-wrap"><canvas id="spinCanvas" width="720" height="720" aria-label="Interactive spinning view of your product. Drag to rotate."></canvas></div>
<p class="hint">Drag the picture to spin it yourself. It keeps turning on its own when you let go.</p>
<p id="needMsg" class="hint hidden" style="opacity:1;font-weight:700">A spin needs at least 4 angles. Add a few more photos and it will come alive.</p>
<div class="controls">
<div class="tool-group"><span class="lbl">Shape</span><div class="seg" role="group" aria-label="Spin shape"><button type="button" data-aspect="1:1" class="on">Square 1:1</button><button type="button" data-aspect="4:5">Portrait 4:5</button></div></div>
<div class="tool-group"><span class="lbl">Speed</span><div class="seg" role="group" aria-label="Spin speed"><button type="button" data-speed="2">2s</button><button type="button" data-speed="4" class="on">4s</button><button type="button" data-speed="8">8s</button></div></div>
<div class="tool-group"><span class="lbl">Mode</span><div class="seg" role="group" aria-label="Spin mode"><button type="button" data-mode="spin" class="on">Full Spin</button><button type="button" data-mode="rock">Rock</button></div></div>
<div class="tool-group"><button type="button" class="button secondary" id="btnFlip" style="height:44px">Flip direction</button></div>
</div>
<div class="btnrow">
<button type="button" class="button" id="btnGif">Download GIF</button>
<button type="button" class="button secondary" id="btnVideoDl">Download video</button>
<span class="hint" style="margin:0">1 action per file. The GIF is 480px wide; the video is 720px.</span>
</div>
<p id="exportStatus" class="hint hidden"></p>
<div class="prog hidden" id="exportProgWrap"><i id="exportProg"></i></div>
</div>
<div id="upgrade-slot"></div>
</section>

<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Do I need an account?</summary>
<p>No. Build spins as a guest with 15 free actions, or create a free account for 75 actions every month. Making and previewing a spin is free; one action is counted when you download a spin file. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>Where do my photos and videos go?</summary>
<p>Nowhere. The spin is built entirely in your browser, on your device. Your photos and videos are never uploaded, never stored, and never shared. The only thing that talks to Bum Bum is the one action counted when you download a spin file.</p>
</details>
<details>
<summary>How many photos do I need?</summary>
<p>At least 4 angles to make a spin. Twelve to twenty-four photos make it noticeably smoother. Short on photos? Drop in one video of you walking around the product (under 60 seconds) and Bum Bum pulls the frames out for you.</p>
</details>
<details>
<summary>What is Rock mode?</summary>
<p>Full Spin turns all the way around in a loop, which suits a full set of angles. Rock swings gently back and forth instead, which is kinder when you only photographed partway around the product and the jump back to the start would look odd.</p>
</details>
<details>
<summary>Where can I use the spin files?</summary>
<p>The GIF drops into marketplace listings, emails, and social posts like any image. The video file suits Reels, TikTok, and Pinterest. The spin on this page is your preview: drag it to check every angle before you download.</p>
</details>
</section>
<section class="panel" aria-label="More tiny tools">
<h2 style="margin-top:0">More tiny tools</h2>
<p><a href="/tools/video-converter/">Video Converter</a> - Drop in a video and convert it to MP4, WebM, or animated GIF right in your browser.</p>
<p><a href="/tools/emoji-mosaic/">Emoji Mosaic</a> - Drop in a photo and Bum Bum rebuilds it as a mosaic of emoji, right in your browser.</p>
<p><a href="/tools/image-converter/">Image Converter</a> - WebP to PNG, JPG to WebP, whatever to whatever, right in your browser.</p>
</section>
<script src="/tools/error-scenes.js"></script>
<script>
bbTrack('tool_opened',{tool:'product-spin'});

const tabPhotos=document.querySelector('#tabPhotos');
const tabVideo=document.querySelector('#tabVideo');
const photosPane=document.querySelector('#photosPane');
const videoPane=document.querySelector('#videoPane');
const photoDrop=document.querySelector('#photoDrop');
const photoInput=document.querySelector('#photoInput');
const btnPhotos=document.querySelector('#btnPhotos');
const videoDrop=document.querySelector('#videoDrop');
const videoInput=document.querySelector('#videoInput');
const btnVideo=document.querySelector('#btnVideo');
const sceneSlot=document.querySelector('#sceneSlot');
const toolError=document.querySelector('#toolError');
const workStatus=document.querySelector('#workStatus');
const frameArea=document.querySelector('#frameArea');
const frameStrip=document.querySelector('#frameStrip');
const frameCount=document.querySelector('#frameCount');
const btnReverse=document.querySelector('#btnReverse');
const btnMorePhotos=document.querySelector('#btnMorePhotos');
const stage=document.querySelector('#stage');
const spinCanvas=document.querySelector('#spinCanvas');
const sctx=spinCanvas.getContext('2d');
const needMsg=document.querySelector('#needMsg');
const btnGif=document.querySelector('#btnGif');
const btnVideoDl=document.querySelector('#btnVideoDl');
const exportStatus=document.querySelector('#exportStatus');
const exportProgWrap=document.querySelector('#exportProgWrap');
const exportProg=document.querySelector('#exportProg');
const btnFlip=document.querySelector('#btnFlip');

const FFMPEG_VENDOR='/tools/video-trimmer/vendor/';
const MAX_PHOTO_BYTES=25*1024*1024;
const MAX_VIDEO_BYTES=100*1024*1024;
const MAX_VIDEO_SECONDS=60;
const MAX_PHOTO_FRAMES=48;
const VIDEO_FRAMES=24;

let frames=[]; /* each: {bmp, url} where bmp is an Image, ImageBitmap, or canvas */
let frameSource='photos';
let normFrames=[];
let aspect='1:1';
let speedSec=4;
let mode='spin';
let flip=1, rockDir=1, pos=0;
let dragging=false, dragX=0, dragPos=0;
let rafStarted=false, lastT=0;
let createdTracked=false;
let exporting=false;
let ffmpeg=null;

function showScene(state,opts){
  sceneSlot.innerHTML='';
  if(window.BBErrorScenes)BBErrorScenes.mount(sceneSlot,state,opts||{});
  bbTrack('spin_error',{scene:state});
}
function clearScene(){sceneSlot.innerHTML=''}
function clearFeedback(){toolError.textContent='';clearScene()}
function setWork(msg){
  if(msg){workStatus.textContent=msg;workStatus.classList.remove('hidden')}
  else{workStatus.textContent='';workStatus.classList.add('hidden')}
}
function setExport(pct,msg){
  if(msg==null){exportStatus.classList.add('hidden');exportProgWrap.classList.add('hidden');return}
  exportStatus.classList.remove('hidden');exportStatus.textContent=msg;
  if(pct==null){exportProgWrap.classList.add('hidden')}
  else{exportProgWrap.classList.remove('hidden');exportProg.style.width=Math.round(pct*100)+'%'}
}

/* Frame normalization: center-crop every frame to the chosen shape at
   720px wide, so the preview, the GIF, and the video all share one stack. */
function targetSize(){return aspect==='1:1'?[720,720]:[720,900]}
function buildNorm(){
  const ts=targetSize(),W=ts[0],H=ts[1];
  spinCanvas.width=W;spinCanvas.height=H;
  normFrames=frames.map(f=>{
    const c=document.createElement('canvas');c.width=W;c.height=H;
    const g=c.getContext('2d');
    const bw=f.bmp.width||f.bmp.naturalWidth,bh=f.bmp.height||f.bmp.naturalHeight;
    const s=Math.max(W/bw,H/bh),dw=bw*s,dh=bh*s;
    g.drawImage(f.bmp,(W-dw)/2,(H-dh)/2,dw,dh);
    return c;
  });
  const N=normFrames.length;
  if(N&&pos>=N)pos=mode==='spin'?pos%N:N-1;
  draw();
}

/* Draw the current fractional position, crossfading between the two nearest
   frames so sparse photo sets glide instead of jumping. */
function draw(){
  const N=normFrames.length;
  if(!N)return;
  let i,f,j;
  if(mode==='spin'){
    const p=((pos%N)+N)%N;
    i=Math.floor(p);f=p-i;j=(i+1)%N;
    sctx.globalAlpha=1;sctx.drawImage(normFrames[i],0,0);
    if(f>0.001&&j!==i){sctx.globalAlpha=f;sctx.drawImage(normFrames[j],0,0);sctx.globalAlpha=1}
  }else{
    const p=Math.min(N-1,Math.max(0,pos));
    i=Math.floor(p);f=p-i;
    sctx.globalAlpha=1;sctx.drawImage(normFrames[i],0,0);
    if(f>0.001&&i<N-1){sctx.globalAlpha=f;sctx.drawImage(normFrames[i+1],0,0);sctx.globalAlpha=1}
  }
}
function startLoop(){if(rafStarted)return;rafStarted=true;lastT=performance.now();requestAnimationFrame(tick)}
function tick(t){
  const dt=Math.min(0.05,(t-lastT)/1000);lastT=t;
  const N=normFrames.length;
  if(N>=2&&!dragging){
    if(mode==='spin'){
      pos=((pos+flip*dt*N/speedSec)%N+N)%N;
    }else{
      pos+=rockDir*flip*dt*(2*(N-1)/speedSec);
      if(pos>N-1){pos=(N-1)-(pos-(N-1));rockDir=-1}
      if(pos<0){pos=-pos;rockDir=1}
    }
  }
  draw();
  requestAnimationFrame(tick);
}

/* Drag to rotate: grabbing the canvas pauses the autoplay until release,
   like the spin viewers on the big marketplace sites. */
spinCanvas.addEventListener('pointerdown',e=>{
  if(!normFrames.length)return;
  dragging=true;dragX=e.clientX;dragPos=pos;
  try{spinCanvas.setPointerCapture(e.pointerId)}catch(x){}
  e.preventDefault();
});
spinCanvas.addEventListener('pointermove',e=>{
  if(!dragging)return;
  const N=normFrames.length;
  const w=spinCanvas.clientWidth||720;
  const np=dragPos+flip*(e.clientX-dragX)/w*N;
  if(mode==='spin')pos=((np%N)+N)%N;
  else pos=Math.min(N-1,Math.max(0,np));
  draw();
});
['pointerup','pointercancel'].forEach(ev=>spinCanvas.addEventListener(ev,()=>{dragging=false}));

function renderStrip(){
  frameStrip.innerHTML='';
  frames.forEach((f,i)=>{
    const d=document.createElement('div');d.className='thumb';
    const img=document.createElement('img');img.alt='Frame '+(i+1);
    img.src=f.url||f.bmp.toDataURL('image/jpeg',0.6);
    const b=document.createElement('button');b.type='button';b.textContent='×';b.setAttribute('aria-label','Remove frame '+(i+1));
    b.addEventListener('click',()=>{
      const fr=frames[i];
      if(fr&&fr.url)URL.revokeObjectURL(fr.url);
      frames.splice(i,1);pos=0;rebuild();
    });
    d.appendChild(img);d.appendChild(b);frameStrip.appendChild(d);
  });
}
function rebuild(){
  buildNorm();
  renderStrip();
  const n=frames.length;
  frameArea.classList.toggle('hidden',n===0);
  stage.classList.toggle('hidden',n===0);
  needMsg.classList.toggle('hidden',n>=4);
  btnGif.disabled=btnVideoDl.disabled=!(n>=4)||exporting;
  frameCount.textContent=n?'('+n+' frame'+(n===1?'':'s')+')':'';
  btnMorePhotos.classList.toggle('hidden',frameSource!=='photos');
  if(n>=4&&!createdTracked){createdTracked=true;bbTrack('spin_created',{frames:n,source:frameSource})}
  if(n<4)createdTracked=false;
  if(n>0)startLoop();
}
function clearFrames(){
  frames.forEach(f=>{if(f.url)URL.revokeObjectURL(f.url)});
  frames=[];normFrames=[];pos=0;createdTracked=false;
}

/* Photos in: used in the order they are added. */
function loadBitmap(file){
  if(window.createImageBitmap)return createImageBitmap(file).then(bmp=>({bmp:bmp,ownUrl:null}));
  return new Promise((res,rej)=>{
    const url=URL.createObjectURL(file);
    const img=new Image();
    img.onload=()=>res({bmp:img,ownUrl:url});
    img.onerror=()=>{URL.revokeObjectURL(url);rej(new Error('img-load'))};
    img.src=url;
  });
}
async function addPhotoFiles(fileList){
  clearFeedback();
  const files=Array.from(fileList||[]);
  if(!files.length)return;
  if(frameSource==='video')clearFrames();
  frameSource='photos';
  let added=0,skippedType=0,skippedBig=0;
  for(const f of files){
    if(frames.length>=MAX_PHOTO_FRAMES)break;
    if(!f.type||!f.type.startsWith('image/')){skippedType++;continue}
    if(f.size>MAX_PHOTO_BYTES){skippedBig++;continue}
    try{
      const got=await loadBitmap(f);
      frames.push({bmp:got.bmp,url:got.ownUrl||URL.createObjectURL(f)});
      added++;
    }catch(e){skippedType++}
  }
  if(!added&&skippedType)showScene('invalid_type',{bumbum:'That format is not one I can open. Try one of these instead: JPG, PNG, or WebP.'});
  else if(!added&&skippedBig)showScene('file_too_large',{});
  if(added)rebuild();
  if(frames.length>=MAX_PHOTO_FRAMES)setWork('The first '+MAX_PHOTO_FRAMES+' photos are in. That is plenty for a smooth spin.');
}

/* Video in: seek through the clip in the browser and pull frames evenly
   across it. The video element never leaves the page and nothing uploads. */
function waitEvent(el,name,ms){
  return new Promise((res,rej)=>{
    const to=setTimeout(()=>rej(new Error('timeout-'+name)),ms);
    el.addEventListener(name,()=>{clearTimeout(to);res()},{once:true});
  });
}
function seekTo(video,t){
  return new Promise((res,rej)=>{
    const to=setTimeout(()=>rej(new Error('seek-timeout')),10000);
    video.addEventListener('seeked',()=>{clearTimeout(to);res()},{once:true});
    video.currentTime=t;
  });
}
async function handleVideoFile(file){
  clearFeedback();
  if(!file)return;
  const looksVideo=(file.type&&file.type.indexOf('video/')===0)||/\.(mp4|webm)$/i.test(file.name||'');
  if(!looksVideo){showScene('invalid_type',{bumbum:'That format is not one I can open. Try one of these instead: MP4 or WebM.'});return}
  if(file.size>MAX_VIDEO_BYTES){showScene('file_too_large',{});return}
  setWork('Reading your video...');
  const url=URL.createObjectURL(file);
  const video=document.createElement('video');
  video.muted=true;video.playsInline=true;video.preload='auto';video.src=url;
  try{
    await waitEvent(video,'loadedmetadata',15000);
    try{await video.play();video.pause()}catch(e){}
    let dur=video.duration;
    if(!isFinite(dur)){
      try{video.currentTime=Number.MAX_SAFE_INTEGER;await waitEvent(video,'durationchange',4000);dur=video.duration}catch(e){}
    }
    if(!isFinite(dur)||dur<=0)throw new Error('no-duration');
    if(dur>MAX_VIDEO_SECONDS+0.5){
      URL.revokeObjectURL(url);setWork('');
      toolError.textContent='That clip runs '+Math.round(dur)+' seconds. Keep it under 60 seconds so the spin stays snappy.';
      return;
    }
    const vw=video.videoWidth,vh=video.videoHeight;
    if(!vw||!vh)throw new Error('no-dimensions');
    const scale=Math.min(1,1280/Math.max(vw,vh));
    const cw=Math.max(2,Math.round(vw*scale)),ch=Math.max(2,Math.round(vh*scale));
    const scratch=document.createElement('canvas');scratch.width=cw;scratch.height=ch;
    const sg=scratch.getContext('2d');
    const got=[];
    for(let k=0;k<VIDEO_FRAMES;k++){
      await seekTo(video,(k+0.5)*dur/VIDEO_FRAMES);
      sg.drawImage(video,0,0,cw,ch);
      const fc=document.createElement('canvas');fc.width=cw;fc.height=ch;
      fc.getContext('2d').drawImage(scratch,0,0);
      got.push({bmp:fc,url:null});
      setWork('Pulling frames from your video... '+(k+1)+' of '+VIDEO_FRAMES);
    }
    URL.revokeObjectURL(url);
    clearFrames();
    frames=got;frameSource='video';
    setWork('');
    rebuild();
  }catch(e){
    URL.revokeObjectURL(url);
    setWork('');
    showScene('upload_failed',{onCta:()=>{clearScene();videoInput.click()}});
  }
}

/* Tabs and inputs */
function setTab(which){
  const ph=which==='photos';
  tabPhotos.classList.toggle('on',ph);
  tabVideo.classList.toggle('on',!ph);
  photosPane.classList.toggle('hidden',!ph);
  videoPane.classList.toggle('hidden',ph);
}
tabPhotos.addEventListener('click',()=>setTab('photos'));
tabVideo.addEventListener('click',()=>setTab('video'));
function bindDrop(zone,input,onFile){
  zone.addEventListener('click',()=>input.click());
  zone.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();input.click()}});
  ['dragover','dragenter'].forEach(ev=>zone.addEventListener(ev,e=>{e.preventDefault();zone.classList.add('over')}));
  ['dragleave','drop'].forEach(ev=>zone.addEventListener(ev,e=>{e.preventDefault();zone.classList.remove('over')}));
  zone.addEventListener('drop',e=>{onFile(e.dataTransfer.files)});
}
bindDrop(photoDrop,photoInput,files=>addPhotoFiles(files));
bindDrop(videoDrop,videoInput,files=>{if(files&&files.length)handleVideoFile(files[0])});
btnPhotos.addEventListener('click',()=>photoInput.click());
btnMorePhotos.addEventListener('click',()=>photoInput.click());
btnVideo.addEventListener('click',()=>videoInput.click());
photoInput.addEventListener('change',()=>{addPhotoFiles(photoInput.files);photoInput.value=''});
videoInput.addEventListener('change',()=>{if(videoInput.files.length)handleVideoFile(videoInput.files[0]);videoInput.value=''});
btnReverse.addEventListener('click',()=>{frames.reverse();pos=0;rebuild()});

/* Controls */
document.querySelectorAll('[data-aspect]').forEach(b=>b.addEventListener('click',()=>{
  document.querySelectorAll('[data-aspect]').forEach(x=>x.classList.remove('on'));
  b.classList.add('on');aspect=b.dataset.aspect;buildNorm();
}));
document.querySelectorAll('[data-speed]').forEach(b=>b.addEventListener('click',()=>{
  document.querySelectorAll('[data-speed]').forEach(x=>x.classList.remove('on'));
  b.classList.add('on');speedSec=+b.dataset.speed;
}));
document.querySelectorAll('[data-mode]').forEach(b=>b.addEventListener('click',()=>{
  document.querySelectorAll('[data-mode]').forEach(x=>x.classList.remove('on'));
  b.classList.add('on');mode=b.dataset.mode;
  const N=normFrames.length;
  if(mode==='rock'&&N)pos=Math.min(N-1,Math.max(0,pos));
  draw();
}));
btnFlip.addEventListener('click',()=>{flip*=-1});

/* Export plumbing. The pattern matches Video Converter: check actions
   first, do the work in the browser, count one action only when a finished
   file is ready, then hand over the download. */
function showUpgrade(){
  document.querySelector('#upgrade-slot').innerHTML='<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">Downloading a spin file uses one action. Helper gives you 1,500 actions for $12/month.</p><p><a class="button" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" href="/account/">See your usage</a></p></div>';
}
async function actionsAvailable(){
  try{
    const s=await fetch('/api/session.php').then(r=>r.json());
    const u=s&&s.usage;
    if(u&&!u.unlimited&&Number(u.remaining)<=0){showUpgrade();return false}
    return true;
  }catch(e){return true}
}
async function spendSpinAction(){
  try{
    const s=await fetch('/api/session.php').then(r=>r.json());
    const key='product-spin-'+Date.now()+'-'+Math.random().toString(36).slice(2,10);
    const res=await fetch('/api/tools/product-spin.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({csrf:s.csrf,idempotencyKey:key})});
    const data=await res.json().catch(()=>({}));
    if(res.status===402){
      if(data&&data.signup_required)return false;
      showUpgrade();
      return false;
    }
    if(!res.ok){alert(data.error||'Could not save. Try again.');return false}
    if(data&&data.usage)document.dispatchEvent(new CustomEvent('bb:usage',{detail:data.usage}));
    return true;
  }catch(e){alert('Could not reach Bum Bum. Check your connection and try again.');return false}
}
function downloadBlob(blob,name){
  const url=URL.createObjectURL(blob);
  const a=document.createElement('a');
  a.href=url;a.download=name;
  document.body.appendChild(a);a.click();a.remove();
  setTimeout(()=>URL.revokeObjectURL(url),4000);
}

/* The GIF engine is the same vendored ffmpeg.wasm the Video Converter uses:
   a two-pass palette encode, entirely in the browser. */
function loadScript(src,timeoutMs){
  return new Promise((res,rej)=>{
    const s=document.createElement('script');s.src=src;
    const to=setTimeout(()=>{s.remove();rej(new Error('script-timeout:'+src))},timeoutMs||90000);
    s.onload=()=>{clearTimeout(to);res()};
    s.onerror=()=>{clearTimeout(to);rej(new Error('script-load:'+src))};
    document.head.appendChild(s);
  });
}
async function getFFmpeg(onStage){
  if(ffmpeg)return ffmpeg;
  onStage&&onStage('engine');
  await loadScript(FFMPEG_VENDOR+'ffmpeg.js');
  if(!window.FFmpegWASM)throw new Error('no-ffmpeg-global');
  const FFmpeg=window.FFmpegWASM.FFmpeg;
  const ff=new FFmpeg();
  onStage&&onStage('core');
  await ff.load({coreURL:FFMPEG_VENDOR+'ffmpeg-core.js'});
  ffmpeg=ff;
  return ffmpeg;
}

/* Sample one full cycle of the current mode at u in [0,1) and paint it,
   blended, onto an export canvas. */
function cyclePos(u){
  const N=normFrames.length;
  if(mode==='spin')return u*N;
  const tri=u<0.5?u*2:2-u*2;
  return tri*(N-1);
}
function drawAt(g,W,H,p){
  const N=normFrames.length;
  let i,f,j;
  if(mode==='spin'){
    const pp=((p%N)+N)%N;
    i=Math.floor(pp);f=pp-i;j=(i+1)%N;
    g.globalAlpha=1;g.drawImage(normFrames[i],0,0,W,H);
    if(f>0.001&&j!==i){g.globalAlpha=f;g.drawImage(normFrames[j],0,0,W,H);g.globalAlpha=1}
  }else{
    const pp=Math.min(N-1,Math.max(0,p));
    i=Math.floor(pp);f=pp-i;
    g.globalAlpha=1;g.drawImage(normFrames[i],0,0,W,H);
    if(f>0.001&&i<N-1){g.globalAlpha=f;g.drawImage(normFrames[i+1],0,0,W,H);g.globalAlpha=1}
  }
}

btnGif.addEventListener('click',async()=>{
  if(exporting||frames.length<4)return;
  exporting=true;btnGif.disabled=true;btnVideoDl.disabled=true;clearFeedback();
  try{
    if(!await actionsAvailable()){setExport(null);return}
    setExport(0.04,'Loading the GIF engine (the first run downloads it)...');
    const ff=await getFFmpeg(s=>setExport(s==='engine'?0.06:0.1,'Loading the GIF engine...'));
    const M=Math.min(60,Math.max(24,Math.round(speedSec*12)));
    const fps=M/speedSec;
    const ts=aspect==='1:1'?[480,480]:[480,600],tw=ts[0],th=ts[1];
    const c=document.createElement('canvas');c.width=tw;c.height=th;
    const g=c.getContext('2d');
    const names=[];
    for(let k=0;k<M;k++){
      drawAt(g,tw,th,cyclePos(k/M));
      const blob=await new Promise(r=>c.toBlob(r,'image/png'));
      const name='f'+String(k).padStart(3,'0')+'.png';
      await ff.writeFile(name,new Uint8Array(await blob.arrayBuffer()));
      names.push(name);
      setExport(0.1+0.35*(k+1)/M,'Building your GIF frames...');
    }
    ff.on('progress',ev=>{setExport(0.45+Math.min(1,(ev&&ev.progress)||0)*0.45,'Encoding your GIF...')});
    let code=await ff.exec(['-framerate',String(fps),'-i','f%03d.png','-vf','scale=480:-1:flags=lanczos,palettegen','palette.png']);
    if(code!==0)throw new Error('palette-exit-'+code);
    code=await ff.exec(['-framerate',String(fps),'-i','f%03d.png','-i','palette.png','-lavfi','[0:v][1:v]paletteuse','product-spin.gif']);
    if(code!==0)throw new Error('gif-exit-'+code);
    const data=await ff.readFile('product-spin.gif');
    for(const n of names){try{await ff.deleteFile(n)}catch(e){}}
    try{await ff.deleteFile('palette.png');await ff.deleteFile('product-spin.gif')}catch(e){}
    if(!await spendSpinAction()){setExport(null);return}
    downloadBlob(new Blob([data],{type:'image/gif'}),'product-spin.gif');
    bbTrack('spin_export',{how:'gif',frames:frames.length});
    setExport(1,'Your GIF is downloaded. One action was used.');
  }catch(e){
    setExport(null);
    showScene('processing_failed',{onCta:()=>{clearScene()}});
    toolError.textContent='The GIF did not come together. Try again, or use Download video instead.';
  }finally{
    exporting=false;rebuild();
  }
});

/* The video export records the live preview canvas for exactly one turn at
   the chosen speed with MediaRecorder, so what you preview is what you get. */
btnVideoDl.addEventListener('click',async()=>{
  if(exporting||frames.length<4)return;
  clearFeedback();
  if(!spinCanvas.captureStream||!window.MediaRecorder){
    toolError.textContent='This browser cannot record the spin. Try Download GIF instead.';
    return;
  }
  const mimes=['video/mp4;codecs=avc1.42E01E','video/mp4','video/webm;codecs=vp9','video/webm'];
  const mime=mimes.find(m=>{try{return MediaRecorder.isTypeSupported(m)}catch(e){return false}})||'';
  exporting=true;btnGif.disabled=true;btnVideoDl.disabled=true;
  try{
    if(!await actionsAvailable()){setExport(null);return}
    const stream=spinCanvas.captureStream(30);
    const rec=new MediaRecorder(stream,mime?{mimeType:mime,videoBitsPerSecond:6000000}:{videoBitsPerSecond:6000000});
    const chunks=[];
    rec.ondataavailable=e=>{if(e.data&&e.data.size)chunks.push(e.data)};
    const stopped=new Promise(r=>{rec.onstop=r});
    if(!await spendSpinAction()){setExport(null);return}
    setExport(0,'Recording your spin...');
    const t0=performance.now();
    const iv=setInterval(()=>{setExport(Math.min(1,(performance.now()-t0)/(speedSec*1000)),'Recording your spin...')},100);
    rec.start(200);
    await new Promise(r=>setTimeout(r,speedSec*1000));
    rec.stop();
    await stopped;
    clearInterval(iv);
    const type=rec.mimeType||mime||'video/webm';
    const ext=type.indexOf('mp4')>=0?'mp4':'webm';
    downloadBlob(new Blob(chunks,{type:type}),'product-spin.'+ext);
    bbTrack('spin_export',{how:'video',frames:frames.length});
    setExport(1,'Your video is downloaded. One action was used.');
  }catch(e){
    setExport(null);
    toolError.textContent='The video recording did not work in this browser. Try Download GIF instead.';
  }finally{
    exporting=false;rebuild();
  }
});
</script>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
