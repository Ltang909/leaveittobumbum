<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>PDF Merger | Merge PDFs in Your Browser | Leave It to Bum Bum</title>
<meta name="description" content="Merge several PDFs into one, right in your browser, in the order you choose. Fast, private, files never leave your device. Free to try, no account needed.">
<meta property="og:title" content="PDF Merger | Merge PDFs in Your Browser | Leave It to Bum Bum">
<meta property="og:description" content="Merge several PDFs into one, right in your browser, in the order you choose. Fast, private, files never leave your device. Free to try, no account needed.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/pdf-merger/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/pdf-merger.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="PDF Merger | Merge PDFs in Your Browser | Leave It to Bum Bum">
<meta name="twitter:description" content="Merge several PDFs into one, right in your browser, in the order you choose. Fast, private, files never leave your device. Free to try, no account needed.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/pdf-merger.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/tools/pdf-merger/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "PDF Merger",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Merge several PDFs into one, right in your browser, in the order you choose. Fast, private, files never leave your device. Free to try, no account needed.",
  "url": "https://leaveittobumbum.com/tools/pdf-merger/"
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
        "text": "No. Merge as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "Do my PDFs upload anywhere?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Your files never leave your device. Merging happens right in your browser."
      }
    },
    {
      "@type": "Question",
      "name": "Does merging reduce the quality of my PDFs?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Pages are copied into the merged file exactly as they are, with no re-compression and no quality loss."
      }
    },
    {
      "@type": "Question",
      "name": "Can I change the order before merging?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. Use the up and down buttons on each file to arrange the order, then merge."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"PDF Merger | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"PDF Merger","item":"https://leaveittobumbum.com/tools/pdf-merger/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use PDF Merger","description":"Stitch several PDFs into one, in the order you choose.","step":[{"@type":"HowToStep","position":1,"name":"Drop your PDFs into PDF Merger."},{"@type":"HowToStep","position":2,"name":"Arrange the files in the order you want with the up and down buttons."},{"@type":"HowToStep","position":3,"name":"Bum Bum merges them right in your browser; your files never leave your device."},{"@type":"HowToStep","position":4,"name":"Download the merged PDF."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s;min-height:300px;display:flex;flex-direction:column;align-items:center;justify-content:center}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px;align-items:center}
.progress-wrap{margin-top:14px}
.progress-bar{height:10px;border-radius:999px;background:#f0e8d4;overflow:hidden}
#barFill{height:100%;width:0%;border-radius:999px;background:#2f2a22;transition:width .2s}
#progText{font-size:13px;font-weight:700;margin:8px 0 0;opacity:.75}
.error{color:#b3261e;font-weight:700}
.hidden{display:none!important}
.file-row{display:flex;gap:10px;align-items:center;border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:12px;background:#fff;padding:10px 12px;margin-top:10px;flex-wrap:wrap}
.file-row .meta{font-size:13px;opacity:.75}
.file-row .grow{flex:1;min-width:160px}
.file-row.bad{opacity:.65}
.file-row img.thumb{width:58px;height:76px;object-fit:contain;border:1px solid #e7dcc3;border-radius:6px;background:#fff;flex:none}
.file-row .thumbph{width:58px;height:76px;border:1px dashed #ddd1b8;border-radius:6px;background:#fffdf8;display:flex;align-items:center;justify-content:center;font-size:24px;flex:none}
.file-row .thumbcap{font-size:11px;opacity:.65;text-align:center;margin-top:2px}
.thumbwrap{flex:none;width:58px}
.mini-btn{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:6px 12px;font-weight:700;font-size:13px;cursor:pointer;color:inherit}
.mini-btn:disabled{opacity:.35;cursor:default}
.dl{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:8px 16px;font-weight:700;font-size:13px;cursor:pointer;text-decoration:none;color:inherit;display:inline-block}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"PDF Merger"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/pdf-merger.png" alt="PDF Merger"><div><h1>Merge PDF</h1><p class="lede bb-desc">All your PDFs. One PDF. Stitch contracts, signed pages, and appendices into a single file.</p></div></div>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to merge PDFs</h2><a class="button" href="/account/?next=<?= urlencode('/tools/pdf-merger/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/pdf-merger/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/pdf-merger/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/pdf-merger/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>

<section class="panel" id="uploadPanel">
<h2>Pick some PDFs</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Choose PDF files">
<p style="font-size:40px;margin:0">📑</p>
<p><b>Drop PDFs here</b>, or pick them below. Two or more makes a merge.</p>
<p class="meta" style="font-size:13px;opacity:.75">Password-protected PDFs need to be unlocked first. Everything happens on your device.</p>
</div>
<input type="file" id="fileInput" accept="application/pdf,.pdf" multiple class="hidden">
<div class="btnrow"><button type="button" class="button secondary" id="btnPick">Choose PDFs</button></div>
<div id="fileList"></div>
<p id="totalLine" class="meta hidden" style="font-size:13px;opacity:.75;margin-top:10px"></p>
<div class="btnrow"><button type="button" class="button hidden" id="mergeBtn">Merge PDFs (1 action)</button><button type="button" class="button secondary hidden" id="clearBtn">Clear</button></div>
<div class="progress-wrap hidden" id="mergeProgress"><div class="progress-bar"><div id="barFill"></div></div><p id="progText">Working...</p></div>
<p id="mergeError" class="error"></p>
<div id="resultList"></div>
</section>
<?php endif; ?>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Do I need an account?</summary>
<p>No. Merge as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>Do my PDFs upload anywhere?</summary>
<p>No. Your files never leave your device. Merging happens right in your browser.</p>
</details>
<details>
<summary>Does merging reduce the quality of my PDFs?</summary>
<p>No. Pages are copied into the merged file exactly as they are, with no re-compression and no quality loss.</p>
</details>
<details>
<summary>Can I change the order before merging?</summary>
<p>Yes. Use the up and down buttons on each file to arrange the order, then merge.</p>
</details>
<details>
<summary>Why did one of my PDFs fail?</summary>
<p>Usually because it is password-protected or damaged. Unlock it with the password in your usual PDF app, save an unprotected copy, and drop that in.</p>
</details>
</section>
<section class="panel" aria-label="Guide">
<h2 style="margin-top:0">Merging PDFs, the short guide</h2>
<h3>How it works here</h3>
<p>Most online mergers upload your PDFs to a server and combine them there. This one does the combining in your browser: you drop the files, arrange them, and the merged PDF is built on your own device from the pages of the originals. Pages are copied, never rebuilt as images, so text stays sharp and searchable and the quality is exactly what went in. Nothing is uploaded, kept, or seen by anyone else.</p>
<h3>The order a reader expects</h3>
<p>Main document first. Supporting files follow in the order the main document mentions them, signed pages sit where the signature belongs, and appendices go last. The first-page preview on each row shows you what you are arranging, so the merged file reads like one document instead of a pile of attachments.</p>
<h3>Merge, or keep the files separate?</h3>
<p>Merge when the destination wants one document: a job application, a client packet, a signed contract with its schedules, an archive you will search later. Keep files separate when different people need different pieces, or when some of the pieces are still being edited and would force a re-merge every round.</p>
</section>
<?php $bbTool = 'pdf-merger'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
</main>
<script src="/tools/pdf-merger/vendor/pdf-lib.min.js"></script>
<script src="/tools/pdf-merger/vendor/pdf.min.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc='/tools/pdf-merger/vendor/pdf.worker.min.js';
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/pdf-merger.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}
bbTrack('tool_opened',{tool:'pdf-merger'});

const dropzone=document.querySelector('#dropzone'),fileInput=document.querySelector('#fileInput'),mergeError=document.querySelector('#mergeError');
const mergeBtn=document.querySelector('#mergeBtn'),clearBtn=document.querySelector('#clearBtn'),resultList=document.querySelector('#resultList');
const fileList=document.querySelector('#fileList'),totalLine=document.querySelector('#totalLine');
const mergeProgress=document.querySelector('#mergeProgress'),barFill=document.querySelector('#barFill'),progText=document.querySelector('#progText');
let entries=[];

async function renderThumb(source){
  try{
    const doc=await pdfjsLib.getDocument({data:source}).promise;
    const page=await doc.getPage(1);
    const base=page.getViewport({scale:1});
    const viewport=page.getViewport({scale:116/base.width});
    const canvas=document.createElement('canvas');
    canvas.width=Math.ceil(viewport.width);canvas.height=Math.ceil(viewport.height);
    const ctx=canvas.getContext('2d');
    ctx.fillStyle='#ffffff';ctx.fillRect(0,0,canvas.width,canvas.height);
    await page.render({canvasContext:ctx,viewport}).promise;
    const url=canvas.toDataURL('image/jpeg',0.72);
    await doc.destroy();
    return url;
  }catch(e){return null}
}
function goodEntries(){return entries.filter(e=>e.ok)}
function refresh(){
  fileList.innerHTML='';
  entries.forEach((e,i)=>{
    const row=document.createElement('div');row.className='file-row'+(e.ok?'':' bad');
    const info=e.ok
      ? '<b>'+esc(e.file.name)+'</b><br><span class="meta">'+e.pages+' page'+(e.pages===1?'':'s')+' &middot; '+(e.file.size/1024).toFixed(0)+' KB</span>'
      : '<b>'+esc(e.file.name)+'</b><br><span class="meta">Bum Bum could not read this one. It may be password-protected or damaged, so it is left out of the merge.</span>';
    row.innerHTML='';
    const tw=document.createElement('div');tw.className='thumbwrap';
    if(e.ok && e.thumb){tw.innerHTML='<img class="thumb" alt="First page of '+esc(e.file.name)+'"><div class="thumbcap">Page 1 of '+e.pages+'</div>';tw.querySelector('img').src=e.thumb}
    else{tw.innerHTML='<div class="thumbph">📄</div>'}
    row.appendChild(tw);
    const grow=document.createElement('div');grow.className='grow';grow.innerHTML=info;row.appendChild(grow);
    const up=document.createElement('button');up.type='button';up.className='mini-btn';up.textContent='Up';up.disabled=i===0;
    up.addEventListener('click',()=>{const t=entries[i-1];entries[i-1]=entries[i];entries[i]=t;refresh()});
    const down=document.createElement('button');down.type='button';down.className='mini-btn';down.textContent='Down';down.disabled=i===entries.length-1;
    down.addEventListener('click',()=>{const t=entries[i+1];entries[i+1]=entries[i];entries[i]=t;refresh()});
    const rm=document.createElement('button');rm.type='button';rm.className='mini-btn';rm.textContent='Remove';
    rm.addEventListener('click',()=>{entries.splice(i,1);refresh()});
    row.appendChild(up);row.appendChild(down);row.appendChild(rm);
    fileList.appendChild(row);
  });
  const good=goodEntries();
  const totalPages=good.reduce((a,e)=>a+e.pages,0);
  totalLine.classList.toggle('hidden',!entries.length);
  if(entries.length)totalLine.textContent=good.length+' of '+entries.length+' PDFs ready, '+totalPages+' pages in total.';
  mergeBtn.classList.toggle('hidden',good.length<2);
  mergeBtn.textContent='Merge '+good.length+' PDFs (1 action)';
  clearBtn.classList.toggle('hidden',!entries.length && !resultList.children.length);
}
async function addFiles(list){
  mergeError.textContent='';
  let added=0;
  for(const f of list){
    if(entries.length>=30)break;
    const isPdf=f.type==='application/pdf'||/\.pdf$/i.test(f.name||'');
    if(!isPdf)continue;
    const entry={file:f,ok:false,pages:0,thumb:null};
    try{
      const buf=await f.arrayBuffer();
      const doc=await PDFLib.PDFDocument.load(buf);
      entry.ok=true;entry.pages=doc.getPageCount();
      entry.thumb=await renderThumb(await f.arrayBuffer());
    }catch(e){entry.ok=false}
    entries.push(entry);added++;
  }
  if(added)refresh();
  else if(!entries.length)mergeError.textContent='No PDF files in that drop.';
  else mergeError.textContent='Up to 30 PDFs at a time.';
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
clearBtn.addEventListener('click',()=>{entries=[];resultList.innerHTML='';mergeError.textContent='';refresh()});

mergeBtn.addEventListener('click',async()=>{
  const good=goodEntries();
  if(good.length<2)return;
  mergeError.textContent='';mergeBtn.disabled=true;mergeProgress.classList.remove('hidden');
  try{
    barFill.style.width='8%';progText.textContent='Getting ready...';
    const key='merge-'+Date.now().toString(36)+'-'+Math.random().toString(36).slice(2,10);
    const{response,data}=await apiCall({action:'merge',idempotencyKey:key});
    if(response.status===402)throw new Error('actions');
    if(!response.ok)throw new Error(data.error||'metering failed');
    const out=await PDFLib.PDFDocument.create();
    let donePages=0;const totalPages=good.reduce((a,e)=>a+e.pages,0);
    for(const e of good){
      const src=await PDFLib.PDFDocument.load(await e.file.arrayBuffer());
      const copied=await out.copyPages(src,src.getPageIndices());
      copied.forEach(p=>out.addPage(p));
      donePages+=e.pages;
      barFill.style.width=(8+donePages/totalPages*84)+'%';
      progText.textContent='Merging: '+donePages+' of '+totalPages+' pages...';
    }
    const bytes=await out.save();
    const blob=new Blob([bytes],{type:'application/pdf'});
    const url=URL.createObjectURL(blob);
    const base=(good[0].file.name||'merged').replace(/\.pdf$/i,'');
    const row=document.createElement('div');row.className='file-row';
    row.innerHTML='<div class="grow"><b>'+esc(base+'-merged.pdf')+'</b><br><span class="meta">'+totalPages+' pages &middot; '+(blob.size/1024).toFixed(0)+' KB</span></div>';
    const a=document.createElement('a');a.className='dl';a.href=url;a.download=base+'-merged.pdf';a.textContent='Download';
    row.appendChild(a);
    const mergedThumb=await renderThumb(bytes.slice(0));
    if(mergedThumb){const tw=document.createElement('div');tw.className='thumbwrap';tw.innerHTML='<img class="thumb" alt="First page of the merged PDF"><div class="thumbcap">Merged</div>';tw.querySelector('img').src=mergedThumb;row.prepend(tw)}
    resultList.prepend(row);
    bbTrack('merge_done',{files:good.length,pages:totalPages});
    if(window.BBDropStrip)window.BBDropStrip.show('pdf-merger');
    barFill.style.width='100%';progText.textContent='Done.';
    resultList.scrollIntoView({behavior:'smooth',block:'center'});
  }catch(e){
    if(e.message==='actions'){mergeError.textContent='Out of actions for this month.'}
    else{mergeError.textContent='Something went wrong merging those. Try fewer or smaller PDFs.'}
    progText.textContent='Stopped.';
  }
  mergeBtn.disabled=false;
  setTimeout(()=>mergeProgress.classList.add('hidden'),900);
});
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script></main><script src="/tools/drop-strip.js?v=20261030r1"></script></body></html>
