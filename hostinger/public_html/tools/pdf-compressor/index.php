<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>PDF Compressor | Shrink a PDF in Your Browser | Leave It to Bum Bum</title>
<meta name="description" content="Shrink a PDF right in your browser until it fits the email or upload limit. Fast, private, files never leave your device. Free to try, no account needed.">
<meta property="og:title" content="PDF Compressor | Shrink a PDF in Your Browser | Leave It to Bum Bum">
<meta property="og:description" content="Shrink a PDF right in your browser until it fits the email or upload limit. Fast, private, files never leave your device. Free to try, no account needed.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/pdf-compressor/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/icons/pdf-compressor.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="PDF Compressor | Shrink a PDF in Your Browser | Leave It to Bum Bum">
<meta name="twitter:description" content="Shrink a PDF right in your browser until it fits the email or upload limit. Fast, private, files never leave your device. Free to try, no account needed.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/icons/pdf-compressor.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/pdf-compressor/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "PDF Compressor",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Shrink a PDF right in your browser until it fits the email or upload limit. Fast, private, files never leave your device. Free to try, no account needed.",
  "url": "https://leaveittobumbum.com/tools/pdf-compressor/"
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
        "text": "No. Compress as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "Do my PDFs upload anywhere?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Your files never leave your device. Compression happens right in your browser."
      }
    },
    {
      "@type": "Question",
      "name": "How does the shrinking work?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Each page is redrawn as a picture at the level you pick, and the pages are rebuilt into a fresh PDF at the same page sizes. Pictures squeeze down far better than the mix of text, fonts, and extras a regular PDF carries around."
      }
    },
    {
      "@type": "Question",
      "name": "Will the text in my PDF stay selectable?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Pages come out as pictures, so the text cannot be selected, copied, or searched, and links and form fields do not carry over. Keep your original for anything you still need to edit, fill in, or sign."
      }
    },
    {
      "@type": "Question",
      "name": "Which level should I pick?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Balanced is the right default for email and uploads. Smaller makes the tiniest file for strict size caps, at the cost of softer pages. High quality keeps pages crispest for anything read closely or printed."
      }
    },
    {
      "@type": "Question",
      "name": "What if my PDF does not get smaller?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Some PDFs are already lean. If the rebuilt file is not smaller than your original, Bum Bum says so, there is nothing to download, and no action is charged."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"PDF Compressor | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"PDF Compressor","item":"https://leaveittobumbum.com/tools/pdf-compressor/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use PDF Compressor","description":"Shrink a PDF in your browser until it fits the email or upload limit.","step":[{"@type":"HowToStep","position":1,"name":"Drop a PDF into PDF Compressor."},{"@type":"HowToStep","position":2,"name":"Pick a level: Smaller, Balanced, or High quality."},{"@type":"HowToStep","position":3,"name":"Bum Bum rebuilds it leaner right in your browser; your file never leaves your device."},{"@type":"HowToStep","position":4,"name":"Download the compressed PDF. If it did not shrink, no action is charged."}]}</script><link rel="stylesheet" href="/app.css?v=10"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s;min-height:300px;display:flex;flex-direction:column;align-items:center;justify-content:center}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px;align-items:center}
.controls{display:grid;gap:12px;margin-top:14px}
.controls label{font-weight:700;font-size:14px;display:block}
.controls select{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px}
.controls select:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.time-hint{font-size:13px;opacity:.7;margin-top:4px}
.progress-wrap{margin-top:14px}
.progress-bar{height:10px;border-radius:999px;background:#f0e8d4;overflow:hidden}
#barFill{height:100%;width:0%;border-radius:999px;background:#2f2a22;transition:width .2s}
#progText{font-size:13px;font-weight:700;margin:8px 0 0;opacity:.75}
.error{color:#b3261e;font-weight:700}
.hidden{display:none!important}
.file-row{display:flex;gap:10px;align-items:center;border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:12px;background:#fff;padding:10px 12px;margin-top:10px;flex-wrap:wrap}
.file-row .meta{font-size:13px;opacity:.75}
.file-row .grow{flex:1;min-width:160px}
.file-row .warn{font-size:13px;color:#8a5a12;font-weight:700}
.mini-btn{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:6px 12px;font-weight:700;font-size:13px;cursor:pointer;color:inherit}
.result-block{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:12px;background:#fff;padding:12px;margin-top:12px}
.result-block .meta{font-size:13px;opacity:.75}
.result-block .fineprint{font-size:13px;opacity:.75;margin-top:8px}
.result-head{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.result-head .grow{flex:1;min-width:160px}
.dl{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:8px 16px;font-weight:700;font-size:13px;cursor:pointer;text-decoration:none;color:inherit;display:inline-block}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"PDF Compressor"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/pdf-compressor.png" alt="PDF Compressor"><div><h1>PDF Compressor</h1><p class="lede bb-desc">Same PDF. Smaller file. Bum Bum rebuilds your PDF leaner right in your browser.</p></div></div>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to compress PDFs</h2><a class="button" href="/account/?next=<?= urlencode('/tools/pdf-compressor/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/pdf-compressor/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/pdf-compressor/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/pdf-compressor/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>

<section class="panel" id="uploadPanel">
<h2>Pick a PDF</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Choose a PDF file">
<p style="font-size:40px;margin:0">🗜️</p>
<p><b>Drop a PDF here</b>, or pick one below. One at a time.</p>
<p class="meta" style="font-size:13px;opacity:.75">Password-protected PDFs need to be unlocked first. Everything happens on your device.</p>
</div>
<input type="file" id="fileInput" accept="application/pdf,.pdf" class="hidden">
<div class="btnrow"><button type="button" class="button secondary" id="btnPick">Choose a PDF</button></div>
<div id="fileSlot"></div>
<div class="controls">
<div><label>Level<select id="levelSel"><option value="smaller">Smaller: tiniest file, softer pages</option><option value="balanced" selected>Balanced: the everyday pick</option><option value="high">High quality: crispest pages, gentler shrink</option></select></label><p class="time-hint">Smaller is for strict size caps, like email attachments and upload forms. Balanced suits most sending. High quality keeps pages their crispest for reading and printing.</p></div>
</div>
<div class="btnrow"><button type="button" class="button hidden" id="compressBtn">Compress this PDF (1 action)</button><button type="button" class="button secondary hidden" id="clearBtn">Clear</button></div>
<div class="progress-wrap hidden" id="compProgress"><div class="progress-bar"><div id="barFill"></div></div><p id="progText">Working...</p></div>
<p id="compError" class="error"></p>
<div id="resultList"></div>
</section>
<?php endif; ?>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Do I need an account?</summary>
<p>No. Compress as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>Do my PDFs upload anywhere?</summary>
<p>No. Your files never leave your device. Compression happens right in your browser.</p>
</details>
<details>
<summary>How does the shrinking work?</summary>
<p>Each page is redrawn as a picture at the level you pick, and the pages are rebuilt into a fresh PDF at the same page sizes. Pictures squeeze down far better than the mix of text, fonts, and extras a regular PDF carries around.</p>
</details>
<details>
<summary>Will the text in my PDF stay selectable?</summary>
<p>No. Pages come out as pictures, so the text cannot be selected, copied, or searched, and links and form fields do not carry over. Keep your original for anything you still need to edit, fill in, or sign.</p>
</details>
<details>
<summary>Which level should I pick?</summary>
<p>Balanced is the right default for email and uploads. Smaller makes the tiniest file for strict size caps, at the cost of softer pages. High quality keeps pages crispest for anything read closely or printed.</p>
</details>
<details>
<summary>What if my PDF does not get smaller?</summary>
<p>Some PDFs are already lean. If the rebuilt file is not smaller than your original, Bum Bum says so, there is nothing to download, and no action is charged.</p>
</details>
<details>
<summary>Why did my PDF fail?</summary>
<p>Usually because it is password-protected or damaged. Unlock it with the password in your usual PDF app, save an unprotected copy, and drop that in.</p>
</details>
</section>
<section class="panel" aria-label="Guide">
<h2 style="margin-top:0">Shrinking a PDF, the short guide</h2>
<h3>How it works here</h3>
<p>Most online compressors upload your PDF to a server and squeeze it there. This one works in your browser: each page is redrawn as a picture at the level you pick, then the pages are rebuilt into a fresh PDF at the same page sizes. Nothing is uploaded, kept, or seen by anyone else. It is one action per PDF, and the action is only charged when the new file is actually smaller than the one you brought.</p>
<h3>Picking a level</h3>
<p>Smaller is the one for strict caps: the email attachment that bounces, the application portal with a hard upload limit, the client inbox with no room. Balanced is the everyday pick, small enough for sending and still comfortable to read. High quality shrinks gently and keeps pages crisp for anything read closely or printed. If a level does not get you under your cap, run the compressed copy through again at the next level down.</p>
<h3>Send the copy, keep the original</h3>
<p>A compressed page is a picture of the page. The text in it cannot be selected, copied, or searched, and links and form fields do not carry over. That is the right trade for a document whose only job now is to be read at the other end. It is the wrong trade for a form someone still has to fill in, a contract still being signed, or the master copy you will edit next month. Compress the copy you send; keep the original as the real one.</p>
</section>
<?php $bbTool = 'pdf-compressor'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
</main><?php require dirname(__DIR__, 2) . '/includes/site-footer.php'; ?>
<script src="/tools/pdf-merger/vendor/pdf-lib.min.js"></script>
<script src="/tools/pdf-merger/vendor/pdf.min.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc='/tools/pdf-merger/vendor/pdf.worker.min.js';
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/pdf-compressor.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}
bbTrack('tool_opened',{tool:'pdf-compressor'});

const dropzone=document.querySelector('#dropzone'),fileInput=document.querySelector('#fileInput'),compError=document.querySelector('#compError');
const levelSel=document.querySelector('#levelSel');
const compressBtn=document.querySelector('#compressBtn'),clearBtn=document.querySelector('#clearBtn'),resultList=document.querySelector('#resultList'),fileSlot=document.querySelector('#fileSlot');
const compProgress=document.querySelector('#compProgress'),barFill=document.querySelector('#barFill'),progText=document.querySelector('#progText');
const LEVELS={smaller:{scale:1,quality:0.6,label:'Smaller'},balanced:{scale:1.5,quality:0.75,label:'Balanced'},high:{scale:2,quality:0.85,label:'High quality'}};
let current=null;

function fmtSize(b){if(b>=1048576)return (b/1048576).toFixed(1)+' MB';return Math.max(1,Math.round(b/1024))+' KB'}
function refresh(){
  fileSlot.innerHTML='';
  if(current){
    const row=document.createElement('div');row.className='file-row';
    let html='<div class="grow"><b>'+esc(current.file.name)+'</b><br><span class="meta">'+current.pages+' page'+(current.pages===1?'':'s')+' &middot; '+fmtSize(current.file.size)+'</span>';
    if(current.file.size>20*1048576||current.pages>50)html+='<br><span class="warn">That is a big one. Compressing runs on your device, so it can take a while and use a lot of memory. A desktop browser handles files like this best.</span>';
    html+='</div>';
    row.innerHTML=html;
    const rm=document.createElement('button');rm.type='button';rm.className='mini-btn';rm.textContent='Remove';
    rm.addEventListener('click',()=>{current=null;refresh()});
    row.appendChild(rm);
    fileSlot.appendChild(row);
  }
  compressBtn.classList.toggle('hidden',!current);
  clearBtn.classList.toggle('hidden',!current && !resultList.children.length);
}
async function addFiles(list){
  compError.textContent='';
  const f=list&&list[0];
  if(!f)return;
  const isPdf=f.type==='application/pdf'||/\.pdf$/i.test(f.name||'');
  if(!isPdf){compError.textContent='That was not a PDF. Pick a PDF file.';return}
  try{
    const buf=await f.arrayBuffer();
    const doc=await pdfjsLib.getDocument({data:buf}).promise;
    const n=doc.numPages;
    await doc.destroy();
    current={file:f,pages:n};
    refresh();
  }catch(e){
    const block=document.createElement('div');block.className='result-block';
    const why=e&&e.name==='PasswordException'?'It is password-protected. Unlock it first, then drop the unlocked copy in.':'Bum Bum could not read that file. It may be damaged.';
    block.innerHTML='<div class="grow"><b>'+esc(f.name)+'</b><br><span class="meta">'+why+'</span></div>';
    resultList.prepend(block);
    clearBtn.classList.remove('hidden');
  }
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
clearBtn.addEventListener('click',()=>{current=null;resultList.innerHTML='';compError.textContent='';refresh()});

function canvasBlob(canvas,mime,quality){return new Promise((res,rej)=>{canvas.toBlob(b=>{b?res(b):rej(new Error('encode'))},mime,quality)})}

compressBtn.addEventListener('click',async()=>{
  if(!current)return;
  const f=current.file,levelKey=levelSel.value,level=LEVELS[levelKey];
  compError.textContent='';compressBtn.disabled=true;compProgress.classList.remove('hidden');
  barFill.style.width='2%';progText.textContent='Getting ready...';
  try{
    const buf=await f.arrayBuffer();
    const doc=await pdfjsLib.getDocument({data:buf}).promise;
    const total=doc.numPages;
    const out=await PDFLib.PDFDocument.create();
    for(let p=1;p<=total;p++){
      const page=await doc.getPage(p);
      const base=page.getViewport({scale:1});
      const viewport=page.getViewport({scale:level.scale});
      const canvas=document.createElement('canvas');
      canvas.width=Math.ceil(viewport.width);canvas.height=Math.ceil(viewport.height);
      const ctx=canvas.getContext('2d');
      ctx.fillStyle='#ffffff';ctx.fillRect(0,0,canvas.width,canvas.height);
      await page.render({canvasContext:ctx,viewport}).promise;
      const blob=await canvasBlob(canvas,'image/jpeg',level.quality);
      const img=await out.embedJpg(await blob.arrayBuffer());
      const newPage=out.addPage([base.width,base.height]);
      newPage.drawImage(img,{x:0,y:0,width:base.width,height:base.height});
      canvas.width=0;canvas.height=0;
      barFill.style.width=(p/total*100)+'%';
      progText.textContent='Compressing: page '+p+' of '+total+'...';
    }
    await doc.destroy();
    const bytes=await out.save();
    const base=(f.name||'pdf').replace(/\.pdf$/i,'');
    if(bytes.length>=f.size){
      const block=document.createElement('div');block.className='result-block';
      let msg='<div class="grow"><b>'+esc(f.name)+'</b><br><span class="meta">Good news and bad news: this PDF is already lean. The rebuilt file came out at '+fmtSize(bytes.length)+', no smaller than your original '+fmtSize(f.size)+', so there is nothing to download and no action was charged.</span>';
      if(levelKey!=='smaller')msg+='<br><span class="meta">If you need it smaller still, try the Smaller level. Pages will come out softer.</span>';
      msg+='</div>';
      block.innerHTML=msg;
      resultList.prepend(block);
      progText.textContent='Done. Already as small as this level gets.';
      bbTrack('compress_done',{tool:'pdf-compressor',pages:total,level:levelKey,saved_pct:0});
    }else{
      const key='comp-'+Date.now().toString(36)+'-'+Math.random().toString(36).slice(2,10);
      const{response,data}=await apiCall({action:'compress',idempotencyKey:key});
      if(response.status===402){compError.textContent='Out of actions for this month.';progText.textContent='Stopped.';compressBtn.disabled=false;setTimeout(()=>compProgress.classList.add('hidden'),900);return}
      if(!response.ok)throw new Error(data.error||'metering failed');
      const blob=new Blob([bytes],{type:'application/pdf'});
      const url=URL.createObjectURL(blob);
      const pct=Math.round((1-bytes.length/f.size)*100);
      const block=document.createElement('div');block.className='result-block';
      block.innerHTML='<div class="result-head"><div class="grow"><b>'+esc(base+'-compressed.pdf')+'</b><br><span class="meta">'+total+' page'+(total===1?'':'s')+' &middot; was '+fmtSize(f.size)+' &middot; now '+fmtSize(bytes.length)+' &middot; saved '+pct+'% &middot; '+level.label+' level</span></div></div><p class="fineprint">Pages were rebuilt as pictures, so text in this copy is not selectable and links and form fields did not carry over. Keep your original for editing, filling in, or signing.</p>';
      const a=document.createElement('a');a.className='dl';a.href=url;a.download=base+'-compressed.pdf';a.textContent='Download';
      block.querySelector('.result-head').appendChild(a);
      resultList.prepend(block);
      progText.textContent='Done.';
      bbTrack('compress_done',{tool:'pdf-compressor',pages:total,level:levelKey,saved_pct:pct});
      if(window.BBDropStrip)window.BBDropStrip.show('pdf-compressor');
      resultList.scrollIntoView({behavior:'smooth',block:'center'});
    }
  }catch(e){
    compError.textContent='Something went wrong compressing that one. Try a smaller PDF or the Smaller level.';
    progText.textContent='Stopped.';
  }
  compressBtn.disabled=false;
  setTimeout(()=>compProgress.classList.add('hidden'),900);
});
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261030r1"></script></body></html>
