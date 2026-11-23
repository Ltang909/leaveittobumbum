<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>PNG to JPG Converter | Convert PNG Images in Your Browser | Leave It to Bum Bum</title>
<meta name="description" content="Convert PNG images to JPG in your browser. Smaller files that open anywhere, with a quality slider to set the size. Files never leave your device. Free, no account needed.">
<meta property="og:title" content="PNG to JPG Converter | Convert PNG Images in Your Browser | Leave It to Bum Bum">
<meta property="og:description" content="Convert PNG images to JPG in your browser. Smaller files that open anywhere, with a quality slider to set the size. Files never leave your device. Free, no account needed.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/png-to-jpg/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/png-to-jpg.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="PNG to JPG Converter | Convert PNG Images in Your Browser | Leave It to Bum Bum">
<meta name="twitter:description" content="Convert PNG images to JPG in your browser. Smaller files that open anywhere, with a quality slider to set the size. Files never leave your device. Free, no account needed.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/png-to-jpg.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/tools/png-to-jpg/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "PNG to JPG Converter",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Convert PNG images to JPG in your browser. Smaller files that open anywhere, with a quality slider to set the size. Files never leave your device. Free, no account needed.",
  "url": "https://leaveittobumbum.com/tools/png-to-jpg/"
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
        "text": "No. Convert as a guest. It is free, with no limits. See pricing: https://leaveittobumbum.com/pricing/."
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
      "name": "What happens to transparent parts of a PNG?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "JPG has no transparency, so see-through areas come out white. If you need the transparency kept, convert to WebP with the Image Converter instead."
      }
    },
    {
      "@type": "Question",
      "name": "How much smaller will the JPG be?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Photos and screenshots usually shrink a lot, often to a tenth of the PNG size at the default quality. The slider trades size for detail: lower for a smaller file, higher to keep every edge crisp."
      }
    },
    {
      "@type": "Question",
      "name": "Can I convert a batch?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, up to 20 images at a time."
      }
    },
    {
      "@type": "Question",
      "name": "Why does the text in my screenshot look smudged after converting?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "JPG compression blurs sharp, high-contrast edges, and small text is nothing but sharp edges. For screenshots where the words matter, keep the PNG, or convert at 100% quality and accept the bigger file."
      }
    },
    {
      "@type": "Question",
      "name": "Does converting back and forth lose quality?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. Every JPG save re-encodes the image and throws a little more away, and a JPG converted to PNG does not get the lost detail back. Keep the PNG as your master copy and convert fresh copies from it."
      }
    },
    {
      "@type": "Question",
      "name": "When should I not convert to JPG?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "When you need transparency, when the image is headed for more editing, or when it is mostly text or line art that has to stay crisp. PNG is the better format for all three; JPG wins when the job is sending, uploading, or posting a photo or screenshot."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"PNG to JPG Converter | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"PNG to JPG Converter","item":"https://leaveittobumbum.com/tools/png-to-jpg/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to convert PNG to JPG","description":"Turn a PNG image into a JPG in your browser.","step":[{"@type":"HowToStep","position":1,"name":"Drop a PNG image into the converter."},{"@type":"HowToStep","position":2,"name":"Set the quality slider. The default suits most photos and screenshots."},{"@type":"HowToStep","position":3,"name":"Bum Bum converts it right in your browser; your file never leaves your device."},{"@type":"HowToStep","position":4,"name":"Download the JPG."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s;min-height:500px;display:flex;flex-direction:column;align-items:center;justify-content:center}
@media(max-width:860px){.dropzone{min-height:360px}}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px;align-items:center}
.controls{display:grid;gap:12px;margin-top:14px}
@media(min-width:760px){.controls{grid-template-columns:1fr 1fr}}
.controls label{font-weight:700;font-size:14px;display:block}
.controls select,.controls input[type=text]{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px}
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
.ex-grid{display:grid;gap:14px;margin:14px 0}
@media(min-width:760px){.ex-grid{grid-template-columns:1fr 1fr}}
.ex-grid figure{margin:0}
.ex-grid img{width:100%;height:auto;border:1px solid #e2d7bf;border-radius:12px;background:#f7f2e7;display:block}
.ex-grid figcaption{font-size:13px;font-weight:700;opacity:.8;margin-top:6px}
table.ex-table{border-collapse:collapse;width:100%;margin:12px 0;font-size:15px}
table.ex-table th,table.ex-table td{border:1px solid #e2d7bf;padding:8px 10px;text-align:left;background:#fff}
table.ex-table th{background:#fffdf8}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"PNG to JPG Converter"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/image-converter.png" alt="PNG to JPG Converter"><div><h1>PNG to JPG Converter</h1><p class="lede bb-desc">Smaller files that open anywhere. Turn PNGs into JPGs right in your browser, at the quality you pick.</p></div></div>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to convert images</h2><a class="button" href="/account/?next=<?= urlencode('/tools/png-to-jpg/') ?>">Sign in or create an account</a></section>
<?php else: ?>

<section class="panel" id="uploadPanel">
<h2>Pick some PNGs</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Choose PNG image files">
<p style="font-size:40px;margin:0">🖼️</p>
<p><b>Drop PNG images here</b>, or pick them below. Several at once works.</p>
<p class="meta" style="font-size:13px;opacity:.75">PNGs come in, JPGs go out. Other image types work too and also come out as JPG.</p>
</div>
<input type="file" id="fileInput" accept="image/*" multiple class="hidden">
<div class="btnrow"><button type="button" class="button secondary" id="btnPick">Choose images</button></div>
<div class="controls">
<div><label>Convert to</label><p style="margin:6px 0 0;font-weight:800">JPG</p><p class="time-hint">Want PNG or WebP out instead? The <a href="/tools/image-converter/">Image Converter</a> runs every direction.</p></div>
<div><label>Quality: <span id="qualityVal">90</span>%<input type="range" id="qualityRange" min="10" max="100" step="5" value="90"></label><p class="time-hint">Higher keeps more detail. Lower makes a smaller file.</p></div>
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
<summary>Do I need an account?</summary>
<p>No. Convert as a guest. It is free, with no limits. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>Do my images upload anywhere?</summary>
<p>No. Your files never leave your device. Conversion happens right in your browser.</p>
</details>
<details>
<summary>What happens to transparent parts of a PNG?</summary>
<p>JPG has no transparency, so see-through areas come out white. If you need the transparency kept, convert to WebP with the <a href="/tools/image-converter/">Image Converter</a> instead.</p>
</details>
<details>
<summary>How much smaller will the JPG be?</summary>
<p>Photos and screenshots usually shrink a lot, often to a tenth of the PNG size at the default quality. The slider trades size for detail: lower for a smaller file, higher to keep every edge crisp.</p>
</details>
<details>
<summary>Can I convert a batch?</summary>
<p>Yes, up to 20 images at a time.</p>
</details>
<details>
<summary>Why does the text in my screenshot look smudged after converting?</summary>
<p>JPG compression blurs sharp, high-contrast edges, and small text is nothing but sharp edges. For screenshots where the words matter, keep the PNG, or convert at 100% quality and accept the bigger file.</p>
</details>
<details>
<summary>Does converting back and forth lose quality?</summary>
<p>Yes. Every JPG save re-encodes the image and throws a little more away, and a JPG converted to PNG does not get the lost detail back. Keep the PNG as your master copy and convert fresh copies from it.</p>
</details>
<details>
<summary>When should I not convert to JPG?</summary>
<p>When you need transparency, when the image is headed for more editing, or when it is mostly text or line art that has to stay crisp. PNG is the better format for all three; JPG wins when the job is sending, uploading, or posting a photo or screenshot.</p>
</details>
</section>
<section class="panel" aria-label="Guide">
<h2 style="margin-top:0">PNG to JPG, the short guide</h2>
<h3>Why make the switch</h3>
<p>PNG keeps every pixel, which makes it the right format for editing and the wrong size for sending. A screenshot that weighs 4 MB as a PNG often lands under 400 KB as a JPG, small enough for email, upload forms with size caps, and marketplace listings that reject big files. JPG also opens in every app and form on the internet, including the older ones that still refuse newer formats.</p>
<h3>A real conversion, with real numbers</h3>
<p>Here is an actual photo run through this exact converter: Bum Bum himself, a 1081 × 1455 photo saved as a PNG. Same picture three ways:</p>
<div class="ex-grid">
<figure><img src="/bum/examples/png-to-jpg-before.png" alt="The original photo of Bum Bum saved as a PNG" loading="lazy" width="1081" height="1455"><figcaption>Original PNG: 1.7 MB</figcaption></figure>
<figure><img src="/bum/examples/png-to-jpg-after.jpg" alt="The same photo of Bum Bum converted to JPG at 90% quality" loading="lazy" width="1081" height="1455"><figcaption>JPG at 90% (the default): 207 KB</figcaption></figure>
</div>
<table class="ex-table">
<tr><th>Version</th><th>File size</th><th>Smaller by</th></tr>
<tr><td>PNG original</td><td>1.7 MB</td><td>—</td></tr>
<tr><td>JPG at 90% quality</td><td>207 KB</td><td>88%</td></tr>
<tr><td>JPG at 70% quality</td><td>112 KB</td><td>93%</td></tr>
</table>
<p>Two things worth noticing. The see-through background of the PNG comes out white in the JPG, because JPG has no transparency; that is covered below. And the JPG at 90% is hard to tell from the original at normal viewing size, which is why 90% is the default here.</p>
<h3>PNG vs JPG, side by side</h3>
<table class="ex-table">
<tr><th></th><th>PNG</th><th>JPG</th></tr>
<tr><td>File size</td><td>Large; every pixel kept</td><td>Small; detail thrown away cleverly</td></tr>
<tr><td>Transparency</td><td>Yes</td><td>No; see-through areas turn white</td></tr>
<tr><td>Quality loss</td><td>None, ever</td><td>A little on every save</td></tr>
<tr><td>Best for</td><td>Editing, screenshots with small text, logos, line art</td><td>Photos, sending, uploading, posting</td></tr>
<tr><td>Opens in</td><td>Almost everything</td><td>Everything, including old forms and apps</td></tr>
</table>
<h3>Picking a quality</h3>
<p>The default 90% suits photos and screenshots: visibly the same picture, a fraction of the size. Drop toward 70% when the file only needs to be looked at once, like a quick proof for a client. Push toward 100% for images headed to print or further editing, and accept the bigger file.</p>
<h3>How to convert PNG to JPG on Windows, Mac, and iPhone</h3>
<p>The converter above is the fast route, but every computer and phone can also do this with the apps already on it:</p>
<p><strong>Windows (Paint):</strong> right-click the PNG and choose Open with, then Paint. Go to File, then Save as, then JPEG picture. Pick a folder and save; Paint writes a JPG copy and leaves your PNG alone.</p>
<p><strong>Mac (Preview):</strong> open the PNG in Preview. Go to File, then Export. Set the format to JPEG, move the quality slider toward Best for photos, and save.</p>
<p><strong>iPhone (Files):</strong> save the PNG to the Files app, touch and hold it, tap Quick Actions, then Convert Image. Choose JPEG and the image size, and the converted copy lands in the same folder.</p>
<p>One warning that saves real grief: renaming a file from photo.png to photo.jpg does not convert anything. The picture inside is still PNG data wearing a JPG name tag, and some apps will refuse to open it. A real conversion re-encodes the image, which is what the apps above and the converter on this page do.</p>
<h3>One thing to know before you convert</h3>
<p>Transparency does not survive the trip. A logo or sticker with see-through areas gets a white background in JPG, which is usually what an upload form wants anyway. Keep the PNG around if you still need the transparent version for design work.</p>
</section>
<?php $bbTool = 'png-to-jpg'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
</main>
<script>
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/image-converter.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}
bbTrack('tool_opened',{tool:'png-to-jpg'});

const dropzone=document.querySelector('#dropzone'),fileInput=document.querySelector('#fileInput'),convError=document.querySelector('#convError');
const qualityRange=document.querySelector('#qualityRange'),qualityVal=document.querySelector('#qualityVal');
const convertBtn=document.querySelector('#convertBtn'),clearBtn=document.querySelector('#clearBtn'),resultList=document.querySelector('#resultList');
const convProgress=document.querySelector('#convProgress'),barFill=document.querySelector('#barFill'),progText=document.querySelector('#progText');
let files=[],convKey=0;

function refreshButtons(){convertBtn.classList.toggle('hidden',!files.length);clearBtn.classList.toggle('hidden',!files.length && !resultList.children.length);convertBtn.textContent=files.length>1?('Convert '+files.length+' images'):('Convert this image')}
qualityRange.addEventListener('input',()=>{qualityVal.textContent=qualityRange.value});

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
function convertOne(img,quality){return new Promise((res,rej)=>{const c=document.createElement('canvas');c.width=img.naturalWidth;c.height=img.naturalHeight;const ctx=c.getContext('2d');ctx.fillStyle='#ffffff';ctx.fillRect(0,0,c.width,c.height);ctx.drawImage(img,0,0);c.toBlob(b=>{b?res(b):rej(new Error('encode'))},'image/jpeg',quality)})}

convertBtn.addEventListener('click',async()=>{
  if(!files.length)return;
  convError.textContent='';convertBtn.disabled=true;convProgress.classList.remove('hidden');
  const quality=qualityRange.value/100;
  const total=files.length;let done=0,blocked=false,okCount=0;
  for(const f of files){
    done++;barFill.style.width=(done/total*90)+'%';progText.textContent='Converting '+done+' of '+total+'...';
    const row=document.createElement('div');row.className='conv-item';
    try{
      const key='conv-'+Date.now().toString(36)+'-'+(++convKey)+'-'+done;
      const{response,data}=await apiCall({action:'convert',idempotencyKey:key});
      if(response.status===402)throw new Error('actions');
      if(!response.ok)throw new Error(data.error||'metering failed');
      const img=await loadImage(f);
      const blob=await convertOne(img,quality);
      const url=URL.createObjectURL(blob);
      const base=(f.name||'image').replace(/\.[^.]+$/,'');
      row.innerHTML='<a class="thumb" target="_blank" rel="noopener" title="Open full-size preview"><img alt="Converted image preview"></a><div class="grow"><b>'+esc(base+'.jpg')+'</b><br><span class="meta">'+esc(f.name)+' &rarr; '+(blob.size/1024).toFixed(0)+' KB</span></div>';
      const thumb=row.querySelector('.thumb');thumb.href=url;thumb.querySelector('img').src=url;
      const a=document.createElement('a');a.className='dl';a.href=url;a.download=base+'.jpg';a.textContent='Download';
      row.appendChild(a);resultList.appendChild(row);
      bbTrack('convert_done',{from:f.type,to:'image/jpeg'});
      okCount++;
    }catch(e){
      if(e.message==='actions'){blocked=true;row.innerHTML='<div class="grow"><b>'+esc(f.name)+'</b><br><span class="meta">Something stopped that job. Try again?</span></div>';resultList.appendChild(row);break}
      row.innerHTML='<div class="grow"><b>'+esc(f.name)+'</b><br><span class="meta">Bum Bum could not read that file. Try a PNG or JPG?</span></div>';resultList.appendChild(row);
    }
  }
  barFill.style.width='100%';progText.textContent=blocked?'Stopped.':'Done.';
  if(okCount>0&&window.BBDropStrip)window.BBDropStrip.show('image-converter');
  files=[];refreshButtons();convertBtn.disabled=false;
  setTimeout(()=>convProgress.classList.add('hidden'),900);
  resultList.scrollIntoView({behavior:'smooth',block:'center'});
});
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script></main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
