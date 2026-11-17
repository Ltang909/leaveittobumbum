<?php require dirname(__DIR__, 3) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); $bbLang = 'es'; ?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Comprimir PDF | Reduce un PDF en tu navegador | Leave It to Bum Bum</title>
<meta name="description" content="Reduce un PDF en tu navegador hasta que quepa en el límite del correo o del formulario. Rápido y privado: tu archivo nunca sale de tu dispositivo. Gratis para probar, sin cuenta.">
<meta property="og:title" content="Comprimir PDF | Reduce un PDF en tu navegador | Leave It to Bum Bum">
<meta property="og:description" content="Reduce un PDF en tu navegador hasta que quepa en el límite del correo o del formulario. Rápido y privado: tu archivo nunca sale de tu dispositivo. Gratis para probar, sin cuenta.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/es/tools/pdf-compressor/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/pdf-compressor.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Comprimir PDF | Reduce un PDF en tu navegador | Leave It to Bum Bum">
<meta name="twitter:description" content="Reduce un PDF en tu navegador hasta que quepa en el límite del correo o del formulario. Rápido y privado: tu archivo nunca sale de tu dispositivo. Gratis para probar, sin cuenta.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/pdf-compressor.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/es/tools/pdf-compressor/"><link rel="alternate" hreflang="en" href="https://leaveittobumbum.com/tools/pdf-compressor/"><link rel="alternate" hreflang="es" href="https://leaveittobumbum.com/es/tools/pdf-compressor/"><link rel="alternate" hreflang="x-default" href="https://leaveittobumbum.com/tools/pdf-compressor/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Comprimir PDF",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Reduce un PDF en tu navegador hasta que quepa en el límite del correo o del formulario. Rápido y privado: tu archivo nunca sale de tu dispositivo. Gratis para probar, sin cuenta.",
  "url": "https://leaveittobumbum.com/es/tools/pdf-compressor/"
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
        "text": "No. Comprime como invitado. Es gratis, sin límites. Ver precios: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "¿Mis PDF se suben a algún sitio?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Tus archivos nunca salen de tu dispositivo. La compresión pasa en tu navegador."
      }
    },
    {
      "@type": "Question",
      "name": "¿Cómo funciona la reducción?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Cada página se vuelve a dibujar como una imagen en el nivel que elijas, y las páginas se reconstruyen en un PDF nuevo con los mismos tamaños de página. Las imágenes se reducen mucho mejor que la mezcla de texto, fuentes y extras que lleva un PDF normal."
      }
    },
    {
      "@type": "Question",
      "name": "¿El texto de mi PDF seguirá siendo seleccionable?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Las páginas salen como imágenes, así que el texto no se puede seleccionar, copiar ni buscar, y los enlaces y campos de formulario no se conservan. Guarda tu original para todo lo que aún necesites editar, rellenar o firmar."
      }
    },
    {
      "@type": "Question",
      "name": "¿Qué nivel elijo?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Equilibrado es el valor por defecto adecuado para correo y subidas. Más pequeño crea el archivo más reducido para límites estrictos, a cambio de páginas más suaves. Alta calidad mantiene las páginas más nítidas para lo que se lee de cerca o se imprime."
      }
    },
    {
      "@type": "Question",
      "name": "¿Qué pasa si mi PDF no se hace más pequeño?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Algunos PDF ya son ligeros. Si el archivo reconstruido no es más pequeño que tu original, Bum Bum lo dice, no hay nada que descargar."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"PDF Compressor | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Herramientas","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Comprimir PDF","item":"https://leaveittobumbum.com/es/tools/pdf-compressor/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"Cómo usar Comprimir PDF","description":"Reduce un PDF en tu navegador hasta que quepa en el límite del correo o del formulario.","step":[{"@type":"HowToStep","position":1,"name":"Suelta un PDF en Comprimir PDF."},{"@type":"HowToStep","position":2,"name":"Elige un nivel: Más pequeño, Equilibrado o Alta calidad."},{"@type":"HowToStep","position":3,"name":"Bum Bum lo reconstruye más ligero en tu navegador; tu archivo nunca sale de tu dispositivo."},{"@type":"HowToStep","position":4,"name":"Descarga el PDF comprimido. Si no se redujo, Bum Bum te lo dice."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 3) . '/includes/analytics.php'; ?><style>
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
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guía"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 3) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Herramientas","url"=>"/tools/"],["label"=>"Comprimir PDF"]]; require dirname(__DIR__,3)."/includes/breadcrumbs.php"; ?><p style="font-size:13px;opacity:.75;margin:4px 0 0"><a href="/tools/pdf-compressor/">View this tool in English</a></p><p class="eyebrow">La caja de herramientas de Bum Bum</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/pdf-compressor.png" alt="PDF Compressor"><div><h1>Comprimir PDF</h1><p class="lede bb-desc">El mismo PDF. Un archivo más pequeño. Bum Bum reconstruye tu PDF más ligero en tu navegador.</p></div></div>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Inicia sesión para comprimir PDF</h2><a class="button" href="/account/?next=<?= urlencode('/es/tools/pdf-compressor/') ?>">Iniciar sesión o crear una cuenta</a></section>
<?php else: ?>

<section class="panel" id="uploadPanel">
<h2>Elige un PDF</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Elegir un archivo PDF">
<p style="font-size:40px;margin:0">🗜️</p>
<p><b>Suelta un PDF aquí</b>, o elige uno abajo. Uno a la vez.</p>
<p class="meta" style="font-size:13px;opacity:.75">Los PDF con contraseña hay que desbloquearlos primero. Todo pasa en tu dispositivo.</p>
</div>
<input type="file" id="fileInput" accept="application/pdf,.pdf" class="hidden">
<div class="btnrow"><button type="button" class="button secondary" id="btnPick">Elegir un PDF</button></div>
<div id="fileSlot"></div>
<div class="controls">
<div><label>Nivel<select id="levelSel"><option value="smaller">Más pequeño: el archivo más reducido, páginas más suaves</option><option value="balanced" selected>Equilibrado: la opción de cada día</option><option value="high">Alta calidad: páginas más nítidas, reducción más suave</option></select></label><p class="time-hint">Más pequeño sirve para límites estrictos, como adjuntos de correo y formularios de subida. Equilibrado vale para casi todo lo que se envía. Alta calidad mantiene las páginas más nítidas para leer e imprimir.</p></div>
</div>
<div class="btnrow"><button type="button" class="button hidden" id="compressBtn">Comprimir este PDF</button><button type="button" class="button secondary hidden" id="clearBtn">Borrar</button></div>
<div class="progress-wrap hidden" id="compProgress"><div class="progress-bar"><div id="barFill"></div></div><p id="progText">Trabajando...</p></div>
<p id="compError" class="error"></p>
<div id="resultList"></div>
</section>
<?php endif; ?>
<section class="panel" id="faq" aria-label="Preguntas frecuentes">
<h2 style="margin-top:0">Preguntas frecuentes</h2>
<details>
<summary>¿Necesito una cuenta?</summary>
<p>No. Comprime como invitado. Es gratis, sin límites. <a href="/pricing/">Ver precios</a>.</p>
</details>
<details>
<summary>¿Mis PDF se suben a algún sitio?</summary>
<p>No. Tus archivos nunca salen de tu dispositivo. La compresión pasa en tu navegador.</p>
</details>
<details>
<summary>¿Cómo funciona la reducción?</summary>
<p>Cada página se vuelve a dibujar como una imagen en el nivel que elijas, y las páginas se reconstruyen en un PDF nuevo con los mismos tamaños de página. Las imágenes se reducen mucho mejor que la mezcla de texto, fuentes y extras que lleva un PDF normal.</p>
</details>
<details>
<summary>¿El texto de mi PDF seguirá siendo seleccionable?</summary>
<p>No. Las páginas salen como imágenes, así que el texto no se puede seleccionar, copiar ni buscar, y los enlaces y campos de formulario no se conservan. Guarda tu original para todo lo que aún necesites editar, rellenar o firmar.</p>
</details>
<details>
<summary>¿Qué nivel elijo?</summary>
<p>Equilibrado es el valor por defecto adecuado para correo y subidas. Más pequeño crea el archivo más reducido para límites estrictos, a cambio de páginas más suaves. Alta calidad mantiene las páginas más nítidas para lo que se lee de cerca o se imprime.</p>
</details>
<details>
<summary>¿Qué pasa si mi PDF no se hace más pequeño?</summary>
<p>Algunos PDF ya son ligeros. Si el archivo reconstruido no es más pequeño que tu original, Bum Bum lo dice, no hay nada que descargar.</p>
</details>
<details>
<summary>¿Por qué falló mi PDF?</summary>
<p>Normalmente porque está protegido con contraseña o dañado. Desbloquéalo con la contraseña en tu aplicación de PDF habitual, guarda una copia sin protección y suelta esa.</p>
</details>
</section>
<section class="panel" aria-label="Guía">
<h2 style="margin-top:0">Reducir un PDF, la guía corta</h2>
<h3>Cómo funciona aquí</h3>
<p>La mayoría de los compresores en línea suben tu PDF a un servidor y lo aprietan allí. Este trabaja en tu navegador: cada página se vuelve a dibujar como una imagen en el nivel que elijas, y luego las páginas se reconstruyen en un PDF nuevo con los mismos tamaños de página. Nada se sube, se guarda ni se ve por nadie más. Si el archivo nuevo no es de verdad más pequeño que el que trajiste, no hay nada que descargar.</p>
<h3>Cómo elegir el nivel</h3>
<p>Más pequeño es el nivel para límites estrictos: el adjunto de correo que rebota, el portal de solicitudes con un límite de subida duro, la bandeja del cliente sin espacio. Equilibrado es la opción de cada día, lo bastante pequeño para enviar y aún cómodo de leer. Alta calidad reduce con suavidad y mantiene las páginas nítidas para lo que se lee de cerca o se imprime. Si un nivel no te baja del límite, vuelve a pasar la copia comprimida por el siguiente nivel hacia abajo.</p>
<h3>Envía la copia y guarda el original</h3>
<p>Una página comprimida es una foto de la página. El texto que contiene no se puede seleccionar, copiar ni buscar, y los enlaces y campos de formulario no se conservan. Es el trato adecuado para un documento cuyo único trabajo ahora es leerse al otro lado. Es el trato equivocado para un formulario que alguien aún tiene que rellenar, un contrato que aún se está firmando o la copia maestra que editarás el mes que viene. Comprime la copia que envías; guarda el original como el verdadero.</p>
</section>
<?php $bbEsTool = 'pdf-compressor'; require dirname(__DIR__) . '/more-tools-es.php'; ?>
</main><?php require dirname(__DIR__, 3) . '/includes/site-footer.php'; ?>
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
const LEVELS={smaller:{scale:1,quality:0.6,label:'Más pequeño'},balanced:{scale:1.5,quality:0.75,label:'Equilibrado'},high:{scale:2,quality:0.85,label:'Alta calidad'}};
let current=null;

function fmtSize(b){if(b>=1048576)return (b/1048576).toFixed(1)+' MB';return Math.max(1,Math.round(b/1024))+' KB'}
function refresh(){
  fileSlot.innerHTML='';
  if(current){
    const row=document.createElement('div');row.className='file-row';
    let html='<div class="grow"><b>'+esc(current.file.name)+'</b><br><span class="meta">'+current.pages+' página'+(current.pages===1?'':'s')+' &middot; '+fmtSize(current.file.size)+'</span>';
    if(current.file.size>20*1048576||current.pages>50)html+='<br><span class="warn">Este es grande. La compresión se hace en tu dispositivo, así que puede tardar y usar mucha memoria. Un navegador de escritorio maneja mejor archivos así.</span>';
    html+='</div>';
    row.innerHTML=html;
    const rm=document.createElement('button');rm.type='button';rm.className='mini-btn';rm.textContent='Quitar';
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
  if(!isPdf){compError.textContent='Eso no era un PDF. Elige un archivo PDF.';return}
  try{
    const buf=await f.arrayBuffer();
    const doc=await pdfjsLib.getDocument({data:buf}).promise;
    const n=doc.numPages;
    await doc.destroy();
    current={file:f,pages:n};
    refresh();
  }catch(e){
    const block=document.createElement('div');block.className='result-block';
    const why=e&&e.name==='PasswordException'?'Está protegido con contraseña. Desbloquéalo primero y luego suelta la copia desbloqueada.':'Bum Bum no pudo leer ese archivo. Puede estar dañado.';
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
  barFill.style.width='2%';progText.textContent='Preparando...';
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
      progText.textContent='Comprimiendo: página '+p+' de '+total+'...';
    }
    await doc.destroy();
    const bytes=await out.save();
    const base=(f.name||'pdf').replace(/\.pdf$/i,'');
    if(bytes.length>=f.size){
      const block=document.createElement('div');block.className='result-block';
      let msg='<div class="grow"><b>'+esc(f.name)+'</b><br><span class="meta">Buenas y malas noticias: este PDF ya es ligero. El archivo reconstruido salió de '+fmtSize(bytes.length)+', nada más pequeño que tu original de '+fmtSize(f.size)+', así que no hay nada que descargar.</span>';
      if(levelKey!=='smaller')msg+='<br><span class="meta">Si lo necesitas aún más pequeño, prueba el nivel Más pequeño. Las páginas saldrán más suaves.</span>';
      msg+='</div>';
      block.innerHTML=msg;
      resultList.prepend(block);
      progText.textContent='Listo. Ya es lo más pequeño que da este nivel.';
      bbTrack('compress_done',{tool:'pdf-compressor',pages:total,level:levelKey,saved_pct:0});
    }else{
      const key='comp-'+Date.now().toString(36)+'-'+Math.random().toString(36).slice(2,10);
      const{response,data}=await apiCall({action:'compress',idempotencyKey:key});
      if(response.status===402){compError.textContent='Algo detuvo ese trabajo. ¿Intentas de nuevo?';progText.textContent='Detenido.';compressBtn.disabled=false;setTimeout(()=>compProgress.classList.add('hidden'),900);return}
      if(!response.ok)throw new Error(data.error||'metering failed');
      const blob=new Blob([bytes],{type:'application/pdf'});
      const url=URL.createObjectURL(blob);
      const pct=Math.round((1-bytes.length/f.size)*100);
      const block=document.createElement('div');block.className='result-block';
      block.innerHTML='<div class="result-head"><div class="grow"><b>'+esc(base+'-comprimido.pdf')+'</b><br><span class="meta">'+total+' página'+(total===1?'':'s')+' &middot; antes '+fmtSize(f.size)+' &middot; ahora '+fmtSize(bytes.length)+' &middot; ahorro del '+pct+'% &middot; nivel '+level.label</span></div></div><p class="fineprint">Las páginas se reconstruyeron como imágenes, así que el texto de esta copia no se puede seleccionar y los enlaces y campos de formulario no se conservaron. Guarda tu original para editar, rellenar o firmar.</p>';
      const a=document.createElement('a');a.className='dl';a.href=url;a.download=base+'-comprimido.pdf';a.textContent='Descargar';
      block.querySelector('.result-head').appendChild(a);
      resultList.prepend(block);
      progText.textContent='Listo.';
      bbTrack('compress_done',{tool:'pdf-compressor',pages:total,level:levelKey,saved_pct:pct});
      if(window.BBDropStrip)window.BBDropStrip.show('pdf-compressor');
      resultList.scrollIntoView({behavior:'smooth',block:'center'});
    }
  }catch(e){
    compError.textContent='Algo salió mal al comprimir ese archivo. Prueba con un PDF más pequeño o con el nivel Más pequeño.';
    progText.textContent='Detenido.';
  }
  compressBtn.disabled=false;
  setTimeout(()=>compProgress.classList.add('hidden'),900);
});
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
