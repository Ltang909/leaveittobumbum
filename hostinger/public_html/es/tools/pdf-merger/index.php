<?php require dirname(__DIR__, 3) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); $bbLang = 'es'; ?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Unir PDF | Junta varios PDF en uno en tu navegador | Leave It to Bum Bum</title>
<meta name="description" content="Une varios PDF en uno, en tu navegador y en el orden que elijas. Rápido y privado: tus archivos nunca salen de tu dispositivo. Gratis para probar, sin cuenta.">
<meta property="og:title" content="Unir PDF | Junta varios PDF en uno en tu navegador | Leave It to Bum Bum">
<meta property="og:description" content="Une varios PDF en uno, en tu navegador y en el orden que elijas. Rápido y privado: tus archivos nunca salen de tu dispositivo. Gratis para probar, sin cuenta.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/es/tools/pdf-merger/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/pdf-merger.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Unir PDF | Junta varios PDF en uno en tu navegador | Leave It to Bum Bum">
<meta name="twitter:description" content="Une varios PDF en uno, en tu navegador y en el orden que elijas. Rápido y privado: tus archivos nunca salen de tu dispositivo. Gratis para probar, sin cuenta.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/pdf-merger.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/es/tools/pdf-merger/"><link rel="alternate" hreflang="en" href="https://leaveittobumbum.com/tools/pdf-merger/"><link rel="alternate" hreflang="es" href="https://leaveittobumbum.com/es/tools/pdf-merger/"><link rel="alternate" hreflang="x-default" href="https://leaveittobumbum.com/tools/pdf-merger/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Unir PDF",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Une varios PDF en uno, en tu navegador y en el orden que elijas. Rápido y privado: tus archivos nunca salen de tu dispositivo. Gratis para probar, sin cuenta.",
  "url": "https://leaveittobumbum.com/es/tools/pdf-merger/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "¿Necesito una cuenta?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Une como invitado. Es gratis, sin límites. Ver precios: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "¿Mis PDF se suben a algún sitio?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Tus archivos nunca salen de tu dispositivo. La unión pasa en tu navegador."
      }
    },
    {
      "@type": "Question",
      "name": "¿Unir reduce la calidad de mis PDF?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Las páginas se copian en el archivo unido tal cual, sin recomprimirlas y sin perder calidad."
      }
    },
    {
      "@type": "Question",
      "name": "¿Puedo cambiar el orden antes de unir?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Sí. Usa los botones de subir y bajar de cada archivo para ordenar, y luego une."
      }
    },
    {
      "@type": "Question",
      "name": "¿Por qué falló uno de mis PDF?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Normalmente porque está protegido con contraseña o dañado. Desbloquéalo con la contraseña en tu aplicación de PDF habitual, guarda una copia sin protección y suelta esa."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"PDF Merger | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Herramientas","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Unir PDF","item":"https://leaveittobumbum.com/es/tools/pdf-merger/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"Cómo usar Unir PDF","description":"Junta varios PDF en uno, en el orden que elijas.","step":[{"@type":"HowToStep","position":1,"name":"Suelta tus PDF en Unir PDF."},{"@type":"HowToStep","position":2,"name":"Ordena los archivos con los botones de subir y bajar."},{"@type":"HowToStep","position":3,"name":"Bum Bum los une en tu navegador; tus archivos nunca salen de tu dispositivo."},{"@type":"HowToStep","position":4,"name":"Descarga el PDF unido."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 3) . '/includes/analytics.php'; ?><style>
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
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guía"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 3) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Herramientas","url"=>"/tools/"],["label"=>"Unir PDF"]]; require dirname(__DIR__,3)."/includes/breadcrumbs.php"; ?><p style="font-size:13px;opacity:.75;margin:4px 0 0"><a href="/tools/pdf-merger/">View this tool in English</a></p><p class="eyebrow">La caja de herramientas de Bum Bum</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/pdf-merger.png" alt="PDF Merger"><div><h1>Unir PDF</h1><p class="lede bb-desc">Todos tus PDF. Un solo PDF. Junta contratos, páginas firmadas y anexos en un único archivo.</p></div></div>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Inicia sesión para unir PDF</h2><a class="button" href="/account/?next=<?= urlencode('/es/tools/pdf-merger/') ?>">Iniciar sesión o crear una cuenta</a></section>
<?php else: ?>

<section class="panel" id="uploadPanel">
<h2>Elige algunos PDF</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Elegir archivos PDF">
<p style="font-size:40px;margin:0">📑</p>
<p><b>Suelta los PDF aquí</b>, o elígelos abajo. Con dos o más ya puedes unirlos.</p>
<p class="meta" style="font-size:13px;opacity:.75">Los PDF con contraseña hay que desbloquearlos primero. Todo pasa en tu dispositivo.</p>
</div>
<input type="file" id="fileInput" accept="application/pdf,.pdf" multiple class="hidden">
<div class="btnrow"><button type="button" class="button secondary" id="btnPick">Elegir PDF</button></div>
<div id="fileList"></div>
<p id="totalLine" class="meta hidden" style="font-size:13px;opacity:.75;margin-top:10px"></p>
<div class="btnrow"><button type="button" class="button hidden" id="mergeBtn">Unir PDF (1 acción)</button><button type="button" class="button secondary hidden" id="clearBtn">Borrar</button></div>
<div class="progress-wrap hidden" id="mergeProgress"><div class="progress-bar"><div id="barFill"></div></div><p id="progText">Trabajando...</p></div>
<p id="mergeError" class="error"></p>
<div id="resultList"></div>
</section>
<?php endif; ?>
<section class="panel" id="faq" aria-label="Preguntas frecuentes">
<h2 style="margin-top:0">Preguntas frecuentes</h2>
<details>
<summary>¿Necesito una cuenta?</summary>
<p>No. Une como invitado. Es gratis, sin límites. <a href="/pricing/">Ver precios</a>.</p>
</details>
<details>
<summary>¿Mis PDF se suben a algún sitio?</summary>
<p>No. Tus archivos nunca salen de tu dispositivo. La unión pasa en tu navegador.</p>
</details>
<details>
<summary>¿Unir reduce la calidad de mis PDF?</summary>
<p>No. Las páginas se copian en el archivo unido tal cual, sin recomprimirlas y sin perder calidad.</p>
</details>
<details>
<summary>¿Puedo cambiar el orden antes de unir?</summary>
<p>Sí. Usa los botones de subir y bajar de cada archivo para ordenar, y luego une.</p>
</details>
<details>
<summary>¿Por qué falló uno de mis PDF?</summary>
<p>Normalmente porque está protegido con contraseña o dañado. Desbloquéalo con la contraseña en tu aplicación de PDF habitual, guarda una copia sin protección y suelta esa.</p>
</details>
</section>
<section class="panel" aria-label="Guía">
<h2 style="margin-top:0">Unir PDF, la guía corta</h2>
<h3>Cómo funciona aquí</h3>
<p>La mayoría de los servicios en línea suben tus PDF a un servidor y los combinan allí. Este hace la unión en tu navegador: sueltas los archivos, los ordenas y el PDF unido se crea en tu propio dispositivo a partir de las páginas de los originales. Las páginas se copian, nunca se reconstruyen como imágenes, así que el texto se mantiene nítido y con búsqueda, y la calidad es exactamente la que entró. Nada se sube, se guarda ni se ve por nadie más.</p>
<h3>El orden que espera quien lee</h3>
<p>Primero el documento principal. Los archivos de apoyo siguen en el orden en que el documento principal los menciona, las páginas firmadas van donde corresponde la firma y los anexos van al final. La vista previa de la primera página en cada fila te muestra qué estás ordenando, así el archivo unido se lee como un documento y no como un montón de adjuntos.</p>
<h3>¿Unir o mantener los archivos separados?</h3>
<p>Une cuando el destino quiere un solo documento: una solicitud de empleo, un paquete para un cliente, un contrato firmado con sus anexos, un archivo que buscarás después. Mantén los archivos separados cuando distintas personas necesitan distintas partes, o cuando algunas partes aún se están editando y obligarían a volver a unir en cada ronda.</p>
</section>
<?php $bbEsTool = 'pdf-merger'; require dirname(__DIR__) . '/more-tools-es.php'; ?>
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
      ? '<b>'+esc(e.file.name)+'</b><br><span class="meta">'+e.pages+' página'+(e.pages===1?'':'s')+' &middot; '+(e.file.size/1024).toFixed(0)+' KB</span>'
      : '<b>'+esc(e.file.name)+'</b><br><span class="meta">Bum Bum no pudo leer este. Puede estar protegido con contraseña o dañado, así que queda fuera de la unión.</span>';
    row.innerHTML='';
    const tw=document.createElement('div');tw.className='thumbwrap';
    if(e.ok && e.thumb){tw.innerHTML='<img class="thumb" alt="Primera página de '+esc(e.file.name)+'"><div class="thumbcap">Página 1 de '+e.pages+'</div>';tw.querySelector('img').src=e.thumb}
    else{tw.innerHTML='<div class="thumbph">📄</div>'}
    row.appendChild(tw);
    const grow=document.createElement('div');grow.className='grow';grow.innerHTML=info;row.appendChild(grow);
    const up=document.createElement('button');up.type='button';up.className='mini-btn';up.textContent='Subir';up.disabled=i===0;
    up.addEventListener('click',()=>{const t=entries[i-1];entries[i-1]=entries[i];entries[i]=t;refresh()});
    const down=document.createElement('button');down.type='button';down.className='mini-btn';down.textContent='Bajar';down.disabled=i===entries.length-1;
    down.addEventListener('click',()=>{const t=entries[i+1];entries[i+1]=entries[i];entries[i]=t;refresh()});
    const rm=document.createElement('button');rm.type='button';rm.className='mini-btn';rm.textContent='Quitar';
    rm.addEventListener('click',()=>{entries.splice(i,1);refresh()});
    row.appendChild(up);row.appendChild(down);row.appendChild(rm);
    fileList.appendChild(row);
  });
  const good=goodEntries();
  const totalPages=good.reduce((a,e)=>a+e.pages,0);
  totalLine.classList.toggle('hidden',!entries.length);
  if(entries.length)totalLine.textContent=good.length+' de '+entries.length+' PDF listos, '+totalPages+' páginas en total.';
  mergeBtn.classList.toggle('hidden',good.length<2);
  mergeBtn.textContent='Unir '+good.length+' PDF (1 acción)';
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
  else if(!entries.length)mergeError.textContent='No había archivos PDF en eso.';
  else mergeError.textContent='Hasta 30 PDF a la vez.';
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
    barFill.style.width='8%';progText.textContent='Preparando...';
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
      progText.textContent='Uniendo: '+donePages+' de '+totalPages+' páginas...';
    }
    const bytes=await out.save();
    const blob=new Blob([bytes],{type:'application/pdf'});
    const url=URL.createObjectURL(blob);
    const base=(good[0].file.name||'merged').replace(/\.pdf$/i,'');
    const row=document.createElement('div');row.className='file-row';
    row.innerHTML='<div class="grow"><b>'+esc(base+'-unido.pdf')+'</b><br><span class="meta">'+totalPages+' páginas &middot; '+(blob.size/1024).toFixed(0)+' KB</span></div>';
    const a=document.createElement('a');a.className='dl';a.href=url;a.download=base+'-unido.pdf';a.textContent='Descargar';
    row.appendChild(a);
    const mergedThumb=await renderThumb(bytes.slice(0));
    if(mergedThumb){const tw=document.createElement('div');tw.className='thumbwrap';tw.innerHTML='<img class="thumb" alt="Primera página del PDF unido"><div class="thumbcap">Unido</div>';tw.querySelector('img').src=mergedThumb;row.prepend(tw)}
    resultList.prepend(row);
    bbTrack('merge_done',{files:good.length,pages:totalPages});
    if(window.BBDropStrip)window.BBDropStrip.show('pdf-merger');
    barFill.style.width='100%';progText.textContent='Listo.';
    resultList.scrollIntoView({behavior:'smooth',block:'center'});
  }catch(e){
    if(e.message==='actions'){mergeError.textContent='Algo detuvo ese trabajo. ¿Intentas de nuevo?'}
    else{mergeError.textContent='Algo salió mal al unirlos. Prueba con menos PDF o con archivos más pequeños.'}
    progText.textContent='Detenido.';
  }
  mergeBtn.disabled=false;
  setTimeout(()=>mergeProgress.classList.add('hidden'),900);
});
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script></main><?php require dirname(__DIR__, 3)."/includes/site-footer.php"; ?><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
