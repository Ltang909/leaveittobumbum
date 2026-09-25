<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $user = currentUser(); $usage = $user ? usageFor(billingUser($user)) : null; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Image Cropper | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px;align-items:center}
.controls{display:grid;gap:12px;margin-top:14px}
@media(min-width:760px){.controls{grid-template-columns:1fr 1fr}}
.controls label{font-weight:700;font-size:14px;display:block}
.controls select,.controls input[type=text]{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px}
.controls select:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.controls input[type=range]{width:100%;margin-top:8px;accent-color:#2f2a22}
.time-hint{font-size:13px;opacity:.7;margin-top:4px}
.error{color:#b3261e;font-weight:700}
.hidden{display:none!important}
.crop-stage{position:relative;display:inline-block;line-height:0;max-width:100%;margin-top:14px;touch-action:none;user-select:none;-webkit-user-select:none}
.crop-stage img{max-width:100%;max-height:62vh;display:block;border-radius:10px;pointer-events:none}
.crop-box{position:absolute;border:2px solid #fff;box-shadow:0 0 0 2px #2f2a22,0 0 0 9999px rgba(23,20,14,.45);cursor:move}
.handle{position:absolute;width:16px;height:16px;background:#fff;border:2px solid #2f2a22;border-radius:50%;z-index:2}
.handle.nw{left:-9px;top:-9px;cursor:nwse-resize}.handle.ne{right:-9px;top:-9px;cursor:nesw-resize}
.handle.sw{left:-9px;bottom:-9px;cursor:nesw-resize}.handle.se{right:-9px;bottom:-9px;cursor:nwse-resize}
.aspect-row{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
.aspect-row button{border:1.5px solid #2f2a22;border-radius:100px;background:transparent;padding:8px 14px;font-size:.82rem;font-weight:800;cursor:pointer;font-family:inherit}
.aspect-row button.active{background:#2f2a22;color:#fff}
.crop-meta{font-size:13px;font-weight:700;opacity:.7;margin:8px 0 0}
.result-preview{margin-top:14px;text-align:center}
.result-preview img{max-width:100%;max-height:50vh;border-radius:10px;border:1px solid #e7dcc3}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Image Cropper"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-box-v2.png" alt="Bum Bum with a box"><h1>Keep the good part.</h1><p class="lede">Drag a box around the bit you actually want. Bum Bum crops your image <b>right in your browser</b> — your files never leave your device. One action per crop.</p>
<?php if (!$user): ?><section class="panel"><h2>Sign in to crop images</h2><a class="button" href="/account/?next=<?= urlencode('/tools/image-cropper/') ?>">Sign in or create an account</a></section><?php else: ?>
<?php $low = $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>

<section class="panel" id="uploadPanel">
<h2>Pick an image</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Choose an image file">
<p style="font-size:40px;margin:0">✂️</p>
<p><b>Drop an image here</b>, or pick one below.</p>
<p class="meta" style="font-size:13px;opacity:.75">PNG, JPG, WebP, GIF and friends. (Phone HEIC photos are not supported by browsers yet.)</p>
</div>
<input type="file" id="fileInput" accept="image/*" class="hidden">
<div class="btnrow"><button type="button" class="button secondary" id="btnPick">Choose image</button><button type="button" class="button secondary hidden" id="btnNew">New image</button></div>
<div id="cropArea" class="hidden">
<div class="aspect-row" role="group" aria-label="Aspect ratio">
<button type="button" data-ratio="0" class="active">Free</button>
<button type="button" data-ratio="1">1:1</button>
<button type="button" data-ratio="0.8">4:5</button>
<button type="button" data-ratio="1.5">3:2</button>
<button type="button" data-ratio="1.7777778">16:9</button>
</div>
<p class="crop-meta" id="cropHint">Drag on the image to draw your crop box. Drag inside it to move.</p>
<div style="text-align:center"><div class="crop-stage" id="cropStage"><img id="cropImg" alt="Image to crop"><div class="crop-box" id="cropBox" style="display:none"></div></div></div>
<p class="crop-meta" id="cropSize"></p>
<div class="controls">
<div><label>Save as<select id="formatSel"><option value="image/png">PNG</option><option value="image/jpeg">JPG</option><option value="image/webp">WebP</option></select></label></div>
<div id="qualityWrap"><label>Quality: <span id="qualityVal">90</span>%<input type="range" id="qualityRange" min="10" max="100" step="5" value="90"></label><p class="time-hint">PNG is always lossless, so quality only applies to JPG and WebP.</p></div>
</div>
<div class="btnrow"><button type="button" class="button" id="cropBtn" disabled>Crop it (1 action)</button></div>
<p id="cropError" class="error"></p>
<div class="result-preview hidden" id="resultWrap"><h3 style="margin-bottom:8px">Cropped ✨</h3><img id="resultImg" alt="Cropped image preview"><div class="btnrow" style="justify-content:center"><a class="button" id="dlBtn" href="#" download>Download</a></div></div>
</div>
</section>
<?php endif; ?>
</main>
<script>
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/image-cropper.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}
bbTrack('tool_opened',{tool:'image-cropper'});
const EXT={'image/png':'png','image/jpeg':'jpg','image/webp':'webp'};

const dropzone=document.querySelector('#dropzone'),fileInput=document.querySelector('#fileInput'),btnPick=document.querySelector('#btnPick'),btnNew=document.querySelector('#btnNew');
const cropArea=document.querySelector('#cropArea'),stage=document.querySelector('#cropStage'),cimg=document.querySelector('#cropImg'),cbox=document.querySelector('#cropBox');
const cropBtn=document.querySelector('#cropBtn'),cropError=document.querySelector('#cropError'),cropSize=document.querySelector('#cropSize');
const formatSel=document.querySelector('#formatSel'),qualityRange=document.querySelector('#qualityRange'),qualityVal=document.querySelector('#qualityVal'),qualityWrap=document.querySelector('#qualityWrap');
const resultWrap=document.querySelector('#resultWrap'),resultImg=document.querySelector('#resultImg'),dlBtn=document.querySelector('#dlBtn');

let NW=0,NH=0,scale=1,crop=null,aspect=0,imgFile=null,imgUrl=null,cropKey=0,gesture=null;
const clamp=(v,a,b)=>Math.min(b,Math.max(a,v));
['nw','ne','sw','se'].forEach(p=>{const h=document.createElement('div');h.className='handle '+p;h.dataset.pos=p;cbox.appendChild(h)});

qualityRange.addEventListener('input',()=>{qualityVal.textContent=qualityRange.value});
formatSel.addEventListener('change',()=>{qualityWrap.style.opacity=formatSel.value==='image/png'?'0.45':'1'});
document.querySelectorAll('.aspect-row button').forEach(b=>b.addEventListener('click',()=>{
  document.querySelectorAll('.aspect-row button').forEach(x=>x.classList.remove('active'));
  b.classList.add('active');aspect=parseFloat(b.dataset.ratio)||0;
  if(crop&&aspect>0)fitAspectToCrop();
  renderBox();
}));

function fitAspectToCrop(){
  const cx=crop.x+crop.w/2,cy=crop.y+crop.h/2;
  let w=Math.min(crop.w,NW),h=w/aspect;
  if(h>Math.min(crop.h,NH)){h=Math.min(crop.h,NH);w=h*aspect}
  if(w>NW){w=NW;h=w/aspect}if(h>NH){h=NH;w=h*aspect}
  crop={x:clamp(cx-w/2,0,NW-w),y:clamp(cy-h/2,0,NH-h),w,h};
}
function stagePos(e){const r=stage.getBoundingClientRect();return{x:e.clientX-r.left,y:e.clientY-r.top}}
function measure(){if(NW)scale=cimg.clientWidth/NW}
function renderBox(){
  if(!crop){cbox.style.display='none';cropSize.textContent='';cropBtn.disabled=true;return}
  cbox.style.display='block';
  cbox.style.left=(crop.x*scale)+'px';cbox.style.top=(crop.y*scale)+'px';
  cbox.style.width=(crop.w*scale)+'px';cbox.style.height=(crop.h*scale)+'px';
  cropSize.textContent='Crop size: '+Math.round(crop.w)+' × '+Math.round(crop.h)+' px';
  cropBtn.disabled=false;
}
function normalizeCrop(){
  crop.w=Math.max(8,crop.w);crop.h=Math.max(8,crop.h);
  crop.x=clamp(crop.x,0,NW-8);crop.y=clamp(crop.y,0,NH-8);
  crop.w=Math.min(crop.w,NW-crop.x);crop.h=Math.min(crop.h,NH-crop.y);
}
function resizeCrop(g,n){
  const o=g.orig,left=g.corner.includes('w'),top=g.corner.includes('n');
  const fx=left?o.x+o.w:o.x,fy=top?o.y+o.h:o.y;
  let w=left?fx-clamp(n.x,0,NW):clamp(n.x,0,NW)-fx;
  let h=aspect>0?w/aspect:(top?fy-clamp(n.y,0,NH):clamp(n.y,0,NH)-fy);
  w=Math.max(8,w);h=Math.max(8,h);
  const maxW=left?fx:NW-fx,maxH=top?fy:NH-fy;
  if(aspect>0){if(w>maxW){w=maxW;h=w/aspect}if(h>maxH){h=maxH;w=h*aspect}}
  else{w=Math.min(w,maxW);h=Math.min(h,maxH)}
  return{x:left?fx-w:fx,y:top?fy-h:fy,w,h};
}

stage.addEventListener('pointerdown',e=>{
  if(!NW)return;
  try{stage.setPointerCapture(e.pointerId)}catch(_){}
  const r=stage.getBoundingClientRect(),n={x:(e.clientX-r.left)/scale,y:(e.clientY-r.top)/scale};
  const t=e.target;
  if(t.classList&&t.classList.contains('handle')){
    if(!crop)return;
    gesture={mode:'resize',corner:t.dataset.pos,orig:{...crop}};
  }else if(crop&&n.x>=crop.x&&n.x<=crop.x+crop.w&&n.y>=crop.y&&n.y<=crop.y+crop.h){
    gesture={mode:'move',sx:n.x,sy:n.y,orig:{...crop}};
  }else{
    gesture={mode:'draw',ax:clamp(n.x,0,NW),ay:clamp(n.y,0,NH)};
    crop={x:gesture.ax,y:gesture.ay,w:0,h:0};
  }
  e.preventDefault();
});
stage.addEventListener('pointermove',e=>{
  if(!gesture)return;
  const r=stage.getBoundingClientRect(),n={x:(e.clientX-r.left)/scale,y:(e.clientY-r.top)/scale};
  if(gesture.mode==='draw'){
    let w=n.x-gesture.ax,h=n.y-gesture.ay;
    if(aspect>0){
      const aw=Math.abs(w),ah=Math.abs(h);
      let bw=aw,bh=bw/aspect;
      if(bh>ah){bh=ah;bw=bh*aspect}
      w=(w<0?-1:1)*bw;h=(h<0?-1:1)*bh;
    }
    crop={x:w<0?gesture.ax+w:gesture.ax,y:h<0?gesture.ay+h:gesture.ay,w:Math.abs(w),h:Math.abs(h)};
    normalizeCrop();
  }else if(gesture.mode==='move'){
    const o=gesture.orig,dx=n.x-gesture.sx,dy=n.y-gesture.sy;
    crop={x:clamp(o.x+dx,0,NW-o.w),y:clamp(o.y+dy,0,NH-o.h),w:o.w,h:o.h};
  }else{
    crop=resizeCrop(gesture,n);
  }
  renderBox();
});
window.addEventListener('pointerup',()=>{gesture=null});
window.addEventListener('resize',()=>{measure();renderBox()});

function setImage(f){
  cropError.textContent='';
  if(!f||!f.type.startsWith('image/')){cropError.textContent='That does not look like an image.';return}
  if(imgUrl)URL.revokeObjectURL(imgUrl);
  imgUrl=URL.createObjectURL(f);
  const probe=new Image();
  probe.onload=()=>{
    NW=probe.naturalWidth;NH=probe.naturalHeight;imgFile=f;
    crop=null;gesture=null;resultWrap.classList.add('hidden');cropBtn.disabled=true;cropSize.textContent='';
    cimg.src=imgUrl;cropArea.classList.remove('hidden');btnNew.classList.remove('hidden');
  };
  probe.onerror=()=>{cropError.textContent='Bum Bum could not read that file. Try a PNG or JPG?'};
  probe.src=imgUrl;
}
cimg.addEventListener('load',()=>{measure();renderBox()});

function addFile(list){for(const f of list){if(f.type.startsWith('image/')){setImage(f);return}}cropError.textContent='No image files in that drop.'}
dropzone.addEventListener('click',()=>fileInput.click());
dropzone.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();fileInput.click()}});
['dragover','dragenter'].forEach(ev=>dropzone.addEventListener(ev,e=>{e.preventDefault();dropzone.classList.add('over')}));
['dragleave','drop'].forEach(ev=>dropzone.addEventListener(ev,e=>{e.preventDefault();dropzone.classList.remove('over')}));
dropzone.addEventListener('drop',e=>addFile(e.dataTransfer.files));
btnPick.addEventListener('click',()=>fileInput.click());
fileInput.addEventListener('change',()=>{addFile(fileInput.files);fileInput.value=''});
btnNew.addEventListener('click',()=>{
  if(imgUrl)URL.revokeObjectURL(imgUrl);
  imgUrl=null;imgFile=null;NW=0;NH=0;crop=null;gesture=null;
  cimg.removeAttribute('src');cropArea.classList.add('hidden');btnNew.classList.add('hidden');
  resultWrap.classList.add('hidden');cropError.textContent='';fileInput.click();
});

function cropToBlob(){
  return new Promise((res,rej)=>{
    const sx=Math.round(crop.x),sy=Math.round(crop.y),sw=Math.round(crop.w),sh=Math.round(crop.h);
    const c=document.createElement('canvas');c.width=sw;c.height=sh;
    const ctx=c.getContext('2d'),mime=formatSel.value;
    if(mime==='image/jpeg'){ctx.fillStyle='#ffffff';ctx.fillRect(0,0,sw,sh)}
    const draw=new Image();
    draw.onload=()=>{try{ctx.drawImage(draw,sx,sy,sw,sh,0,0,sw,sh);c.toBlob(b=>{b?res(b):rej(new Error('encode'))},mime,qualityRange.value/100)}catch(_){rej(new Error('encode'))}};
    draw.onerror=()=>rej(new Error('decode'));
    draw.src=imgUrl;
  });
}

cropBtn.addEventListener('click',async()=>{
  if(!crop||!imgFile)return;
  cropError.textContent='';cropBtn.disabled=true;
  const key='crop-'+Date.now().toString(36)+'-'+(++cropKey)+'-'+Math.round(crop.w)+'x'+Math.round(crop.h);
  try{
    const{response,data}=await apiCall({action:'status'});
    if(!response.ok)throw new Error('status');
    const u=data.usage||{};
    if(!u.unlimited&&!(u.remaining>0))throw new Error('actions');
    const blob=await cropToBlob();
    const r2=await apiCall({action:'crop',idempotencyKey:key});
    if(r2.response.status===402)throw new Error('actions');
    if(!r2.response.ok)throw new Error(r2.data.error||'metering failed');
    const url=URL.createObjectURL(blob),ext=EXT[formatSel.value];
    const base=(imgFile.name||'image').replace(/\.[^.]+$/,'');
    resultImg.src=url;dlBtn.href=url;dlBtn.download='cropped-'+base+'.'+ext;
    resultWrap.classList.remove('hidden');
    resultWrap.scrollIntoView({behavior:'smooth',block:'center'});
    bbTrack('crop_done',{to:formatSel.value});
  }catch(e){
    cropError.textContent=e.message==='actions'?'Out of actions for this month.':'Something went wrong with that crop. Try again?';
  }
  cropBtn.disabled=false;
});
</script></main></body></html>
