<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Image Converter | Leave It to Bum Bum</title>
<meta name="description" content="Convert images between WebP, PNG, and JPG in your browser. Fast, private, files never leave your device. Free to try, no account needed.">
<meta property="og:title" content="Image Converter | Leave It to Bum Bum">
<meta property="og:description" content="Convert images between WebP, PNG, and JPG in your browser. Fast, private, files never leave your device. Free to try, no account needed.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/image-converter/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og-image-converter.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Image Converter | Leave It to Bum Bum">
<meta name="twitter:description" content="Convert images between WebP, PNG, and JPG in your browser. Fast, private, files never leave your device. Free to try, no account needed.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og-image-converter.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/image-converter/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Image Converter",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Convert images between WebP, PNG, and JPG in your browser. Fast, private, files never leave your device. Free to try, no account needed.",
  "url": "https://leaveittobumbum.com/tools/image-converter/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Is Image Converter free?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You get 15 free actions with no signup. Create a free account and you get 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/. One action per image converted."
      }
    },
    {
      "@type": "Question",
      "name": "Do I need an account?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "Do my images upload anywhere?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Your files never leave your device. Conversion happens right in your browser."
      }
    },
    {
      "@type": "Question",
      "name": "Which formats are supported?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "WebP, PNG, and JPG, in any direction."
      }
    },
    {
      "@type": "Question",
      "name": "Is there a size limit?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "If your browser can open it, Image Converter can convert it."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Image Converter | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Image Converter","item":"https://leaveittobumbum.com/tools/image-converter/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use Image Converter","description":"WebP to PNG, JPG to WebP, whatever to whatever.","step":[{"@type":"HowToStep","position":1,"name":"Drop an image into Image Converter."},{"@type":"HowToStep","position":2,"name":"Pick the format you want: WebP, PNG, or JPG."},{"@type":"HowToStep","position":3,"name":"Bum Bum converts it right in your browser; your file never leaves your device."},{"@type":"HowToStep","position":4,"name":"Download the converted image."}]}</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
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
.progress-wrap{margin-top:14px}
.progress-bar{height:10px;border-radius:999px;background:#f0e8d4;overflow:hidden}
#barFill{height:100%;width:0%;border-radius:999px;background:#2f2a22;transition:width .2s}
#progText{font-size:13px;font-weight:700;margin:8px 0 0;opacity:.75}
.error{color:#b3261e;font-weight:700}
.hidden{display:none!important}
.conv-item{display:flex;gap:12px;align-items:center;border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:12px;background:#fff;padding:10px 12px;margin-top:10px;flex-wrap:wrap}
.conv-item img{width:64px;height:64px;object-fit:cover;border-radius:8px;border:1px solid #e7dcc3;background:#f7f2e7}
.conv-item .thumb{display:inline-block;line-height:0;border-radius:8px;cursor:zoom-in}
.conv-item .meta{font-size:13px;opacity:.75}
.conv-item .grow{flex:1;min-width:160px}
.conv-item .dl{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:8px 16px;font-weight:700;font-size:13px;cursor:pointer;text-decoration:none;color:inherit;display:inline-block}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Image Converter"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-curious.png" alt="Bum Bum looking curious"><h1>Whatever to whatever.</h1><p class="lede">WebP to PNG, JPG to WebP, PNG to JPG. Bum Bum converts your images <b>right in your browser</b> and hands them back in the format you actually wanted. Your files never leave your device. One action per image converted.</p>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to convert images</h2><a class="button" href="/account/?next=<?= urlencode('/tools/image-converter/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/image-converter/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/image-converter/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/image-converter/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>

<section class="panel" id="uploadPanel">
<h2>Pick some images</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Choose image files">
<p style="font-size:40px;margin:0">🖼️</p>
<p><b>Drop images here</b>, or pick them below. Several at once works.</p>
<p class="meta" style="font-size:13px;opacity:.75">PNG, JPG, WebP, GIF and friends. (Phone HEIC photos are not supported by browsers yet.)</p>
</div>
<input type="file" id="fileInput" accept="image/*" multiple class="hidden">
<div class="btnrow"><button type="button" class="button secondary" id="btnPick">Choose images</button></div>
<div class="controls">
<div><label>Convert to<select id="formatSel"><option value="image/png">PNG</option><option value="image/jpeg">JPG</option><option value="image/webp">WebP</option></select></label></div>
<div id="qualityWrap"><label>Quality: <span id="qualityVal">90</span>%<input type="range" id="qualityRange" min="10" max="100" step="5" value="90"></label><p class="time-hint">PNG is always lossless, so quality only applies to JPG and WebP.</p></div>
</div>
<div class="btnrow"><button type="button" class="button hidden" id="convertBtn">Convert</button><button type="button" class="button secondary hidden" id="clearBtn">Clear</button></div>
<div class="progress-wrap hidden" id="convProgress"><div class="progress-bar"><div id="barFill"></div></div><p id="progText">Working...</p></div>
<p id="convError" class="error"></p>
<div id="resultList"></div>
</section>
<?php endif; ?>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Is Image Converter free?</summary>
<p>You get 15 free actions with no signup. Create a free account and you get 75 actions every month. <a href="/pricing/">See pricing</a>. One action per image converted.</p>
</details>
<details>
<summary>Do I need an account?</summary>
<p>No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>Do my images upload anywhere?</summary>
<p>No. Your files never leave your device. Conversion happens right in your browser.</p>
</details>
<details>
<summary>Which formats are supported?</summary>
<p>WebP, PNG, and JPG, in any direction.</p>
</details>
<details>
<summary>Is there a size limit?</summary>
<p>If your browser can open it, Image Converter can convert it.</p>
</details>
</section>
<section class="panel" aria-label="More tiny tools">
<h2 style="margin-top:0">More tiny tools</h2>
<p><a href="/tools/image-cropper/">Image Cropper</a> - Drag a box around the good part.</p>
<p><a href="/tools/video-trimmer/">Video Trimmer</a> - Drop in a video, mark where the good part starts and ends, and Bum Bum snips it right in your browser.</p>
<p><a href="/tools/clips/">Bum Bum Clips</a> - Record your screen right in your browser.</p>
</section>
</main>
<script>
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/image-converter.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}
bbTrack('tool_opened',{tool:'image-converter'});

const dropzone=document.querySelector('#dropzone'),fileInput=document.querySelector('#fileInput'),convError=document.querySelector('#convError');
const formatSel=document.querySelector('#formatSel'),qualityRange=document.querySelector('#qualityRange'),qualityVal=document.querySelector('#qualityVal'),qualityWrap=document.querySelector('#qualityWrap');
const convertBtn=document.querySelector('#convertBtn'),clearBtn=document.querySelector('#clearBtn'),resultList=document.querySelector('#resultList');
const convProgress=document.querySelector('#convProgress'),barFill=document.querySelector('#barFill'),progText=document.querySelector('#progText');
let files=[],convKey=0;
const EXT={'image/png':'png','image/jpeg':'jpg','image/webp':'webp'};

function refreshButtons(){convertBtn.classList.toggle('hidden',!files.length);clearBtn.classList.toggle('hidden',!files.length && !resultList.children.length);convertBtn.textContent=files.length>1?('Convert '+files.length+' images ('+files.length+' actions)'):('Convert this image (1 action)')}
qualityRange.addEventListener('input',()=>{qualityVal.textContent=qualityRange.value});
formatSel.addEventListener('change',()=>{qualityWrap.style.opacity=formatSel.value==='image/png'?'0.45':'1'});

if(dropzone){
  dropzone.addEventListener('click',()=>fileInput.click());
  dropzone.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();fileInput.click()}});
  ['dragover','dragenter'].forEach(ev=>dropzone.addEventListener(ev,e=>{e.preventDefault();dropzone.classList.add('over')}));
  ['dragleave','drop'].forEach(ev=>dropzone.addEventListener(ev,e=>{e.preventDefault();dropzone.classList.remove('over')}));
  dropzone.addEventListener('drop',e=>{addFiles(e.dataTransfer.files)});
  document.querySelector('#btnPick').addEventListener('click',()=>fileInput.click());
  fileInput.addEventListener('change',()=>{addFiles(fileInput.files);fileInput.value=''});
}
function addFiles(list){convError.textContent='';let added=0;for(const f of list){if(!f.type.startsWith('image/'))continue;if(files.length>=20)break;files.push(f);added++}if(added)refreshButtons();else if(!files.length)convError.textContent='No image files in that drop.';else convError.textContent='Up to 20 images at a time.'}
window.__bbCaptureHandoff=function(f){addFiles([f]);};
clearBtn.addEventListener('click',()=>{files=[];resultList.innerHTML='';convError.textContent='';refreshButtons()});

function loadImage(f){return new Promise((res,rej)=>{const url=URL.createObjectURL(f);const img=new Image();img.onload=()=>{URL.revokeObjectURL(url);res(img)};img.onerror=()=>{URL.revokeObjectURL(url);rej(new Error('decode'))};img.src=url})}
function convertOne(img,mime,quality){return new Promise((res,rej)=>{const c=document.createElement('canvas');c.width=img.naturalWidth;c.height=img.naturalHeight;const ctx=c.getContext('2d');if(mime==='image/jpeg'){ctx.fillStyle='#ffffff';ctx.fillRect(0,0,c.width,c.height)}ctx.drawImage(img,0,0);c.toBlob(b=>{b?res(b):rej(new Error('encode'))},mime,quality)})}

convertBtn.addEventListener('click',async()=>{
  if(!files.length)return;
  convError.textContent='';convertBtn.disabled=true;convProgress.classList.remove('hidden');
  const mime=formatSel.value,ext=EXT[mime],quality=qualityRange.value/100;
  const total=files.length;let done=0,blocked=false;
  for(const f of files){
    done++;barFill.style.width=(done/total*90)+'%';progText.textContent='Converting '+done+' of '+total+'...';
    const row=document.createElement('div');row.className='conv-item';
    try{
      const key='conv-'+Date.now().toString(36)+'-'+(++convKey)+'-'+done;
      const{response,data}=await apiCall({action:'convert',idempotencyKey:key});
      if(response.status===402)throw new Error('actions');
      if(!response.ok)throw new Error(data.error||'metering failed');
      const img=await loadImage(f);
      const blob=await convertOne(img,mime,quality);
      const url=URL.createObjectURL(blob);
      const base=(f.name||'image').replace(/\.[^.]+$/,'');
      row.innerHTML='<a class="thumb" target="_blank" rel="noopener" title="Open full-size preview"><img alt="Converted image preview"></a><div class="grow"><b>'+esc(base+'.'+ext)+'</b><br><span class="meta">'+esc(f.name)+' &rarr; '+(blob.size/1024).toFixed(0)+' KB</span></div>';
      const thumb=row.querySelector('.thumb');thumb.href=url;thumb.querySelector('img').src=url;
      const a=document.createElement('a');a.className='dl';a.href=url;a.download=base+'.'+ext;a.textContent='Download';
      row.appendChild(a);resultList.appendChild(row);
      bbTrack('convert_done',{from:f.type,to:mime});
    }catch(e){
      if(e.message==='actions'){blocked=true;row.innerHTML='<div class="grow"><b>'+esc(f.name)+'</b><br><span class="meta">Out of actions for this month.</span></div>';resultList.appendChild(row);break}
      row.innerHTML='<div class="grow"><b>'+esc(f.name)+'</b><br><span class="meta">Bum Bum could not read that file. Try a PNG or JPG?</span></div>';resultList.appendChild(row);
    }
  }
  barFill.style.width='100%';progText.textContent=blocked?'Stopped: out of actions.':'Done.';
  files=[];refreshButtons();convertBtn.disabled=false;
  setTimeout(()=>convProgress.classList.add('hidden'),900);
  resultList.scrollIntoView({behavior:'smooth',block:'center'});
});
</script><script src="/tools/capture-handoff.js"></script></main></body></html>
