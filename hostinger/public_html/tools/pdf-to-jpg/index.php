<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>PDF to JPG | Turn PDF Pages Into Images in Your Browser | Leave It to Bum Bum</title>
<meta name="description" content="Turn every page of a PDF into a clean JPG image, right in your browser. Fast, private, files never leave your device. Free to try, no account needed.">
<meta property="og:title" content="PDF to JPG | Turn PDF Pages Into Images in Your Browser | Leave It to Bum Bum">
<meta property="og:description" content="Turn every page of a PDF into a clean JPG image, right in your browser. Fast, private, files never leave your device. Free to try, no account needed.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/pdf-to-jpg/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/icons/pdf-to-jpg.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="PDF to JPG | Turn PDF Pages Into Images in Your Browser | Leave It to Bum Bum">
<meta name="twitter:description" content="Turn every page of a PDF into a clean JPG image, right in your browser. Fast, private, files never leave your device. Free to try, no account needed.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/icons/pdf-to-jpg.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/pdf-to-jpg/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "PDF to JPG",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Turn every page of a PDF into a clean JPG image, right in your browser. Fast, private, files never leave your device. Free to try, no account needed.",
  "url": "https://leaveittobumbum.com/tools/pdf-to-jpg/"
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
        "text": "No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "Do my PDFs upload anywhere?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Your files never leave your device. Conversion happens right in your browser."
      }
    },
    {
      "@type": "Question",
      "name": "What do I get from a multi-page PDF?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "One JPG per page, in order. Download each page on its own, or grab them all together in a zip file."
      }
    },
    {
      "@type": "Question",
      "name": "Which resolution should I pick?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Standard is plenty for forms and uploads. High is the pick for anything printed or read closely, and Extra high is for pages with small print. Bigger means bigger files."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"PDF to JPG | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"PDF to JPG","item":"https://leaveittobumbum.com/tools/pdf-to-jpg/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use PDF to JPG","description":"Turn every page of a PDF into a JPG image.","step":[{"@type":"HowToStep","position":1,"name":"Drop a PDF into PDF to JPG."},{"@type":"HowToStep","position":2,"name":"Pick a resolution and quality."},{"@type":"HowToStep","position":3,"name":"Bum Bum converts every page right in your browser; your file never leaves your device."},{"@type":"HowToStep","position":4,"name":"Download each page, or grab them all in a zip."}]}</script><link rel="stylesheet" href="/app.css?v=8"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s;min-height:300px;display:flex;flex-direction:column;align-items:center;justify-content:center}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px;align-items:center}
.controls{display:grid;gap:12px;margin-top:14px}
@media(min-width:760px){.controls{grid-template-columns:1fr 1fr}}
.controls label{font-weight:700;font-size:14px;display:block}
.controls select{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px}
.controls select:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.controls input[type=range]{width:100%;margin-top:8px;accent-color:#2f2a22}
.time-hint{font-size:13px;opacity:.7;margin-top:4px}
.progress-wrap{margin-top:14px}
.progress-bar{height:10px;border-radius:999px;background:#f0e8d4;overflow:hidden}
#barFill{height:100%;width:0%;border-radius:999px;background:#2f2a22;transition:width .2s}
#progText{font-size:13px;font-weight:700;margin:8px 0 0;opacity:.75}
.error{color:#b3261e;font-weight:700}
.hidden{display:none!important}
.result-block{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:12px;background:#fff;padding:12px;margin-top:12px}
.result-block .meta{font-size:13px;opacity:.75}
.result-head{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.result-head .grow{flex:1;min-width:160px}
.thumbs{display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:10px;margin-top:12px}
.thumbs figure{margin:0}
.thumbs img{width:100%;border-radius:8px;border:1px solid #e7dcc3;background:#f7f2e7;display:block}
.thumbs figcaption{font-size:12px;margin-top:4px;text-align:center}
.dl{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:8px 16px;font-weight:700;font-size:13px;cursor:pointer;text-decoration:none;color:inherit;display:inline-block}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"PDF to JPG"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/icons/pdf-to-jpg.png" alt="PDF to JPG"><h1>PDF pages, as pictures.</h1><p class="lede bb-desc">Some form, platform, or client insists your PDF arrive as images. Bum Bum turns every page into a clean JPG <b>right in your browser</b>. Your files never leave your device. One action per PDF.</p>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to convert PDFs</h2><a class="button" href="/account/?next=<?= urlencode('/tools/pdf-to-jpg/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/pdf-to-jpg/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/pdf-to-jpg/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/pdf-to-jpg/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>

<section class="panel" id="uploadPanel">
<h2>Pick a PDF</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Choose PDF files">
<p style="font-size:40px;margin:0">🖼️</p>
<p><b>Drop PDFs here</b>, or pick them below. Every page becomes a JPG.</p>
<p class="meta" style="font-size:13px;opacity:.75">Password-protected PDFs need to be unlocked first. Everything happens on your device.</p>
</div>
<input type="file" id="fileInput" accept="application/pdf,.pdf" multiple class="hidden">
<div class="btnrow"><button type="button" class="button secondary" id="btnPick">Choose PDFs</button></div>
<div class="controls">
<div><label>Resolution<select id="scaleSel"><option value="1">Standard: fine for forms and uploads</option><option value="2" selected>High: crisp for reading and printing</option><option value="3">Extra high: for small print</option></select></label></div>
<div><label>Quality: <span id="qualityVal">90</span>%<input type="range" id="qualityRange" min="50" max="100" step="5" value="90"></label><p class="time-hint">Higher quality means bigger JPGs. 90% is the sweet spot for most pages.</p></div>
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
<p>No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>Do my PDFs upload anywhere?</summary>
<p>No. Your files never leave your device. Conversion happens right in your browser.</p>
</details>
<details>
<summary>What do I get from a multi-page PDF?</summary>
<p>One JPG per page, in order. Download each page on its own, or grab them all together in a zip file.</p>
</details>
<details>
<summary>Which resolution should I pick?</summary>
<p>Standard is plenty for forms and uploads. High is the pick for anything printed or read closely, and Extra high is for pages with small print. Bigger means bigger files.</p>
</details>
<details>
<summary>Why did my PDF fail?</summary>
<p>Usually because it is password-protected or damaged. Unlock it with the password in your usual PDF app, save an unprotected copy, and drop that in.</p>
</details>
</section>
<section class="panel" aria-label="More tiny tools">
<h2 style="margin-top:0">More tiny tools</h2>
<p><a href="/tools/pdf-merger/">PDF Merger</a> - Stitch several PDFs into one, in the order you choose.</p>
<p><a href="/tools/image-converter/">Image Converter</a> - WebP to PNG, JPG to WebP, whatever to whatever, right in your browser.</p>
<p><a href="/tools/image-cropper/">Image Cropper</a> - Drag a box around the good part.</p>
</section>
</main>
<script src="/tools/pdf-merger/vendor/pdf.min.js"></script>
<script src="/tools/pdf-merger/vendor/jszip.min.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc='/tools/pdf-merger/vendor/pdf.worker.min.js';
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/pdf-to-jpg.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}
bbTrack('tool_opened',{tool:'pdf-to-jpg'});

const dropzone=document.querySelector('#dropzone'),fileInput=document.querySelector('#fileInput'),convError=document.querySelector('#convError');
const scaleSel=document.querySelector('#scaleSel'),qualityRange=document.querySelector('#qualityRange'),qualityVal=document.querySelector('#qualityVal');
const convertBtn=document.querySelector('#convertBtn'),clearBtn=document.querySelector('#clearBtn'),resultList=document.querySelector('#resultList');
const convProgress=document.querySelector('#convProgress'),barFill=document.querySelector('#barFill'),progText=document.querySelector('#progText');
let files=[],convKey=0;

function refreshButtons(){convertBtn.classList.toggle('hidden',!files.length);clearBtn.classList.toggle('hidden',!files.length && !resultList.children.length);convertBtn.textContent=files.length>1?('Convert '+files.length+' PDFs ('+files.length+' actions)'):('Convert this PDF (1 action)')}
qualityRange.addEventListener('input',()=>{qualityVal.textContent=qualityRange.value});

async function probePdf(f){
  const buf=await f.arrayBuffer();
  const doc=await pdfjsLib.getDocument({data:buf}).promise;
  const n=doc.numPages;
  await doc.destroy();
  return n;
}
async function addFiles(list){
  convError.textContent='';
  let added=0;
  for(const f of list){
    if(files.length>=10)break;
    const isPdf=f.type==='application/pdf'||/\.pdf$/i.test(f.name||'');
    if(!isPdf)continue;
    try{await probePdf(f);files.push(f);added++}
    catch(e){
      const block=document.createElement('div');block.className='result-block';
      const why=e&&e.name==='PasswordException'?'It is password-protected. Unlock it first, then drop the unlocked copy in.':'Bum Bum could not read that file. It may be damaged.';
      block.innerHTML='<div class="grow"><b>'+esc(f.name)+'</b><br><span class="meta">'+why+'</span></div>';
      resultList.prepend(block);
    }
  }
  if(added)refreshButtons();
  else if(!files.length && !resultList.children.length)convError.textContent='No readable PDF files in that drop.';
  else if(!added)refreshButtons();
}
if(dropzone){
  dropzone.addEventListener('click',()=>fileInput.click());
  dropzone.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();fileInput.click()}});
  ['dragover','dragenter'].forEach(ev=>dropzone.addEventListener(ev,e=>{e.preventDefault();dropzone.classList.add('over')}));
  ['dragleave','drop'].forEach(ev=>dropzone.addEventListener(ev,e=>{e.preventDefault();dropzone.classList.remove('over')}));
  dropzone.addEventListener('drop',e=>{addFiles(e.dataTransfer.files)});
  document.querySelector('#btnPick').addEventListener('click',()=>fileInput.click());
  fileInput.addEventListener('change',()=>{addFiles(fileInput.files);fileInput.value=''});
}
window.__bbCaptureHandoff=function(f){addFiles([f]);};
clearBtn.addEventListener('click',()=>{files=[];resultList.innerHTML='';convError.textContent='';refreshButtons()});

function canvasBlob(canvas,quality){return new Promise((res,rej)=>{canvas.toBlob(b=>{b?res(b):rej(new Error('encode'))},'image/jpeg',quality)})}

convertBtn.addEventListener('click',async()=>{
  if(!files.length)return;
  convError.textContent='';convertBtn.disabled=true;convProgress.classList.remove('hidden');
  const scale=parseFloat(scaleSel.value),quality=qualityRange.value/100;
  const queue=files.slice();files=[];refreshButtons();
  let blocked=false;
  for(const f of queue){
    const base=(f.name||'pdf').replace(/\.pdf$/i,'');
    const block=document.createElement('div');block.className='result-block';
    block.innerHTML='<div class="result-head"><div class="grow"><b>'+esc(f.name)+'</b><br><span class="meta">Converting...</span></div></div>';
    resultList.prepend(block);
    const meta=block.querySelector('.meta');
    try{
      const key='conv-'+Date.now().toString(36)+'-'+(++convKey)+'-'+Math.random().toString(36).slice(2,8);
      const{response,data}=await apiCall({action:'convert',idempotencyKey:key});
      if(response.status===402)throw new Error('actions');
      if(!response.ok)throw new Error(data.error||'metering failed');
      const buf=await f.arrayBuffer();
      const doc=await pdfjsLib.getDocument({data:buf}).promise;
      const total=doc.numPages;
      const pages=[];
      const grid=document.createElement('div');grid.className='thumbs';
      for(let p=1;p<=total;p++){
        const page=await doc.getPage(p);
        const viewport=page.getViewport({scale});
        const canvas=document.createElement('canvas');
        canvas.width=Math.ceil(viewport.width);canvas.height=Math.ceil(viewport.height);
        const ctx=canvas.getContext('2d');
        ctx.fillStyle='#ffffff';ctx.fillRect(0,0,canvas.width,canvas.height);
        await page.render({canvasContext:ctx,viewport}).promise;
        const blob=await canvasBlob(canvas,quality);
        const url=URL.createObjectURL(blob);
        const pageName=base+'-page-'+p+'.jpg';
        pages.push({name:pageName,blob});
        const fig=document.createElement('figure');
        fig.innerHTML='<img alt="Page '+p+' of '+esc(f.name)+'"><figcaption><a class="dl" download="'+esc(pageName)+'">Page '+p+'</a></figcaption>';
        fig.querySelector('img').src=url;
        fig.querySelector('a').href=url;
        grid.appendChild(fig);
        barFill.style.width=(p/total*100)+'%';
        progText.textContent='Converting '+esc(f.name)+': page '+p+' of '+total+'...';
      }
      await doc.destroy();
      block.appendChild(grid);
      meta.textContent=total+' page'+(total===1?'':'s')+' converted.';
      if(total>1){
        const zipBtn=document.createElement('button');zipBtn.type='button';zipBtn.className='dl';zipBtn.textContent='Download all (zip)';
        zipBtn.addEventListener('click',async()=>{
          zipBtn.disabled=true;zipBtn.textContent='Zipping...';
          const zip=new JSZip();
          pages.forEach(pg=>zip.file(pg.name,pg.blob));
          const zb=await zip.generateAsync({type:'blob'});
          const a=document.createElement('a');a.href=URL.createObjectURL(zb);a.download=base+'-pages.zip';a.click();
          zipBtn.disabled=false;zipBtn.textContent='Download all (zip)';
        });
        block.querySelector('.result-head').appendChild(zipBtn);
      }
      bbTrack('convert_done',{tool:'pdf-to-jpg',pages:total});
      if(window.BBDropStrip)window.BBDropStrip.show('pdf-to-jpg');
    }catch(e){
      if(e.message==='actions'){blocked=true;meta.textContent='Out of actions for this month.';break}
      meta.textContent='Something went wrong converting that one. Try a smaller PDF or the Standard resolution.';
    }
  }
  barFill.style.width='100%';progText.textContent=blocked?'Stopped: out of actions.':'Done.';
  convertBtn.disabled=false;
  setTimeout(()=>convProgress.classList.add('hidden'),900);
  resultList.scrollIntoView({behavior:'smooth',block:'center'});
});
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script></main><script src="/tools/drop-strip.js?v=20261030r1"></script></body></html>
