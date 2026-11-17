<?php require dirname(__DIR__, 3) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); $bbLang = 'es'; ?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Convertir JPG a PDF | Junta tus imágenes en un PDF en tu navegador | Leave It to Bum Bum</title>
<meta name="description" content="Convierte JPG a PDF gratis en tu navegador. Suelta imágenes JPG o PNG, ordénalas, elige el tamaño de página y descarga un solo PDF. Sin registro, y tus imágenes nunca salen de tu dispositivo.">
<meta property="og:title" content="Convertir JPG a PDF | Junta tus imágenes en un PDF en tu navegador | Leave It to Bum Bum">
<meta property="og:description" content="Convierte JPG a PDF gratis en tu navegador. Suelta imágenes JPG o PNG, ordénalas, elige el tamaño de página y descarga un solo PDF. Sin registro, y tus imágenes nunca salen de tu dispositivo.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/es/tools/jpg-to-pdf/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/jpg-to-pdf.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Convertir JPG a PDF | Junta tus imágenes en un PDF en tu navegador | Leave It to Bum Bum">
<meta name="twitter:description" content="Convierte JPG a PDF gratis en tu navegador. Suelta imágenes JPG o PNG, ordénalas, elige el tamaño de página y descarga un solo PDF. Sin registro, y tus imágenes nunca salen de tu dispositivo.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/jpg-to-pdf.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/es/tools/jpg-to-pdf/"><link rel="alternate" hreflang="en" href="https://leaveittobumbum.com/tools/jpg-to-pdf/"><link rel="alternate" hreflang="es" href="https://leaveittobumbum.com/es/tools/jpg-to-pdf/"><link rel="alternate" hreflang="x-default" href="https://leaveittobumbum.com/tools/jpg-to-pdf/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Convertir JPG a PDF",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" },
  "description": "Convierte JPG a PDF gratis en tu navegador. Suelta imágenes JPG o PNG, ordénalas, elige el tamaño de página y descarga un solo PDF. Sin registro, y tus imágenes nunca salen de tu dispositivo.",
  "url": "https://leaveittobumbum.com/es/tools/jpg-to-pdf/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    { "@type": "Question", "name": "¿Necesito una cuenta?", "acceptedAnswer": { "@type": "Answer", "text": "No. Este convertidor es gratis y no pide registro. Suelta tus imágenes y descarga el PDF." } },
    { "@type": "Question", "name": "¿Mis imágenes se suben a algún sitio?", "acceptedAnswer": { "@type": "Answer", "text": "No. El PDF se crea en tu navegador, en tu dispositivo. Tus imágenes nunca se envían a Bum Bum ni a nadie más." } },
    { "@type": "Question", "name": "¿Qué tipos de imagen puedo usar?", "acceptedAnswer": { "@type": "Answer", "text": "JPG y PNG. Funcionan fotos de cámara o de teléfono, escaneos y capturas de pantalla. Las fotos HEIC de iPhone necesitan primero el convertidor de HEIC a JPG, porque los navegadores no pueden abrir HEIC por su cuenta." } },
    { "@type": "Question", "name": "¿Puedo cambiar el orden de las páginas?", "acceptedAnswer": { "@type": "Answer", "text": "Sí. Cada imagen de la lista tiene botones de subir y bajar. El PDF sigue el orden que ves, la primera imagen en la página uno." } },
    { "@type": "Question", "name": "¿Qué tamaño de página elijo?", "acceptedAnswer": { "@type": "Answer", "text": "Ajustar a la imagen hace cada página del tamaño de su imagen, ideal para fotos y escaneos. A4 y Carta crean páginas uniformes para imprimir y para formularios, y giran de lado las imágenes anchas para que nada se recorte." } },
    { "@type": "Question", "name": "¿Mis imágenes perderán calidad?", "acceptedAnswer": { "@type": "Answer", "text": "No. Los JPG entran en el PDF tal cual, sin una segunda ronda de compresión, y los PNG se mantienen sin pérdida. El archivo que descargas tiene los mismos píxeles que soltaste." } }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"JPG to PDF | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Herramientas","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Convertir JPG a PDF","item":"https://leaveittobumbum.com/es/tools/jpg-to-pdf/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"Cómo usar Convertir JPG a PDF","description":"Convierte imágenes JPG o PNG en un solo PDF en tu navegador.","step":[{"@type":"HowToStep","position":1,"name":"Suelta tus imágenes JPG o PNG en el convertidor."},{"@type":"HowToStep","position":2,"name":"Ordena las imágenes con los botones de subir y bajar."},{"@type":"HowToStep","position":3,"name":"Elige un tamaño de página: ajustar a la imagen, A4 o Carta."},{"@type":"HowToStep","position":4,"name":"Crea el PDF y descárgalo. Tus imágenes nunca salen de tu dispositivo."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 3) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s;min-height:220px;display:flex;flex-direction:column;align-items:center;justify-content:center}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.controls{display:flex;gap:14px;flex-wrap:wrap;margin-top:14px;align-items:end}
.controls label{font-weight:700;font-size:14px;display:block}
.controls select{padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px;align-items:center}
.mini-btn{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:6px 12px;font-weight:700;font-size:13px;cursor:pointer;color:inherit}
.mini-btn:disabled{opacity:.45;cursor:default}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.file-row{display:flex;gap:10px;align-items:center;border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:12px;background:#fff;padding:10px 12px;margin-top:10px;flex-wrap:wrap}
.file-row img.thumb{width:52px;height:52px;object-fit:cover;border-radius:8px;border:1px solid #e2d7bf}
.file-row .grow{flex:1;min-width:160px}
.file-row .meta{font-size:13px;opacity:.75}
.file-row .st{font-size:13px;font-weight:700}
.hc-status{font-size:13px;font-weight:700;margin-top:10px;min-height:18px}
.hc-status.error{color:#b3261e}
.hc-note{font-size:13px;opacity:.75;margin-top:8px}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
</style></head>
<body><?php $showMeter = true; require dirname(__DIR__, 3) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Herramientas","url"=>"/tools/"],["label"=>"Convertir JPG a PDF"]]; require dirname(__DIR__,3)."/includes/breadcrumbs.php"; ?><p style="font-size:13px;opacity:.75;margin:4px 0 0"><a href="/tools/jpg-to-pdf/">View this tool in English</a></p><p class="eyebrow">La caja de herramientas de Bum Bum</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/jpg-to-pdf.png" alt="JPG to PDF"><div><h1>Convertidor de JPG a PDF gratis</h1><p class="lede bb-desc">Tus imágenes, juntas en un solo PDF. Bum Bum lo crea en tu navegador. Gratis, sin registro.</p></div></div>
<section class="panel" id="uploadPanel">
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Añadir imágenes">
<p style="font-size:2rem;margin:0">🖼️</p>
<p><b>Suelta aquí tus imágenes JPG o PNG</b> o haz clic para elegirlas</p>
<p class="hc-note">Todas las que quieras. La primera imagen de la lista será la página uno.</p>
<input type="file" id="fileInput" accept="image/jpeg,image/png,.jpg,.jpeg,.png" multiple hidden>
</div>
<div class="controls">
<div><label for="sizeSel">Tamaño de página</label><select id="sizeSel">
<option value="fit">Ajustar a la imagen</option>
<option value="a4">Páginas A4</option>
<option value="letter">Páginas Carta</option>
</select></div>
<div id="marginWrap" style="display:none"><label for="marginSel">Margen</label><select id="marginSel">
<option value="36">Pequeño</option>
<option value="72">Cómodo</option>
<option value="0">Ninguno, llenar la página</option>
</select></div>
</div>
<div id="fileList"></div>
<div class="btnrow">
<button type="button" class="button" id="buildBtn" disabled>Crear mi PDF</button>
<button type="button" class="mini-btn" id="clearBtn">Vaciar la lista</button>
</div>
<p class="hc-status" id="statusLine" role="status"></p>
<p class="hc-note">La creación se hace en tu dispositivo. Una lista larga de fotos pesadas puede tardar un poco; la línea de estado te va avisando.</p>
</section>
<section class="panel" id="faq" aria-label="Preguntas frecuentes">
<h2>Preguntas frecuentes</h2>
<details><summary>¿Necesito una cuenta?</summary><p>No. Este convertidor es gratis y no pide registro. Suelta tus imágenes y descarga el PDF.</p></details>
<details><summary>¿Mis imágenes se suben a algún sitio?</summary><p>No. El PDF se crea en tu navegador, en tu dispositivo. Tus imágenes nunca se envían a Bum Bum ni a nadie más.</p></details>
<details><summary>¿Qué tipos de imagen puedo usar?</summary><p>JPG y PNG. Funcionan fotos de cámara o de teléfono, escaneos y capturas de pantalla. Las fotos HEIC de iPhone necesitan primero el <a href="/es/tools/heic-to-jpg/">convertidor de HEIC a JPG</a>, porque los navegadores no pueden abrir HEIC por su cuenta.</p></details>
<details><summary>¿Puedo cambiar el orden de las páginas?</summary><p>Sí. Cada imagen de la lista tiene botones de subir y bajar. El PDF sigue el orden que ves, la primera imagen en la página uno.</p></details>
<details><summary>¿Qué tamaño de página elijo?</summary><p>Ajustar a la imagen hace cada página del tamaño de su imagen, ideal para fotos y escaneos. A4 y Carta crean páginas uniformes para imprimir y para formularios, y giran de lado las imágenes anchas para que nada se recorte.</p></details>
<details><summary>¿Mis imágenes perderán calidad?</summary><p>No. Los JPG entran en el PDF tal cual, sin una segunda ronda de compresión, y los PNG se mantienen sin pérdida. El archivo que descargas tiene los mismos píxeles que soltaste.</p></details>
</section>
<section class="panel" aria-label="Guía">
<h2>Convertir JPG a PDF, la guía corta</h2>
<p>Formularios, solicitudes y clientes piden un solo PDF mientras la vida te entrega un montón de imágenes sueltas: fotos de recibos, escaneos de páginas firmadas, capturas de un chat. Este convertidor reúne el montón en un solo archivo, en el orden que elijas, sin enviar nada a un servidor.</p>
<p>Cómo elegir el tamaño de página: elige Ajustar a la imagen cuando el PDF va a una pantalla, porque cada página coincide con su imagen y nada se rellena. Elige A4 o Carta cuando el archivo se va a imprimir o adjuntar a un formulario, porque las páginas uniformes se imprimen bien y las imágenes anchas giran de lado en vez de encogerse en una esquina.</p>
<p>La familia de este convertidor: <a href="/es/tools/pdf-merger/">Unir PDF</a> cose PDF ya terminados, <a href="/es/tools/split-pdf/">Dividir PDF</a> separa un PDF en páginas o en un rango, y <a href="/es/tools/pdf-compressor/">Comprimir PDF</a> reduce un PDF demasiado grande para enviarlo. Tus archivos se quedan en tu dispositivo en todos ellos.</p>
</section>
<?php $bbEsTool = 'jpg-to-pdf'; require dirname(__DIR__) . '/more-tools-es.php'; ?>
</main><?php require dirname(__DIR__, 3) . '/includes/site-footer.php'; ?>
<script src="/tools/pdf-merger/vendor/pdf-lib.min.js"></script>
<script>
(function(){
"use strict";
var $=function(id){return document.getElementById(id);};
var items=[];
function fmtSize(n){if(n<1024)return n+" B";if(n<1048576)return (n/1024).toFixed(1)+" KB";return (n/1048576).toFixed(2)+" MB";}
function setStatus(msg,isErr){var s=$("statusLine");s.textContent=msg||"";s.className="hc-status"+(isErr?" error":"");}
function refresh(){$("buildBtn").disabled=!items.length;$("marginWrap").style.display=$("sizeSel").value==="fit"?"none":"";}
function render(){
  var list=$("fileList");list.textContent="";
  items.forEach(function(item,idx){
    var row=document.createElement("div");row.className="file-row";
    row.innerHTML='<img class="thumb" alt=""><div class="grow"><div><b></b></div><div class="meta"></div></div><span class="st"></span>';
    row.querySelector("b").textContent=item.name;
    row.querySelector(".meta").textContent=fmtSize(item.file.size);
    row.querySelector(".st").textContent="Página "+(idx+1);
    if(item.url)row.querySelector(".thumb").src=item.url;
    var up=document.createElement("button");up.type="button";up.className="mini-btn";up.textContent="↑";up.title="Subir";up.disabled=idx===0;
    var dn=document.createElement("button");dn.type="button";dn.className="mini-btn";dn.textContent="↓";dn.title="Bajar";dn.disabled=idx===items.length-1;
    var rm=document.createElement("button");rm.type="button";rm.className="mini-btn";rm.textContent="Quitar";
    up.addEventListener("click",function(){var t=items[idx-1];items[idx-1]=items[idx];items[idx]=t;render();});
    dn.addEventListener("click",function(){var t=items[idx+1];items[idx+1]=items[idx];items[idx]=t;render();});
    rm.addEventListener("click",function(){if(items[idx].url)URL.revokeObjectURL(items[idx].url);items.splice(idx,1);render();refresh();});
    row.appendChild(up);row.appendChild(dn);row.appendChild(rm);
    list.appendChild(row);
  });
}
function addFiles(list){
  var skipped=0;
  Array.prototype.forEach.call(list,function(file){
    if(!/image\/(jpeg|png)/i.test(file.type||"")&&!/\.(jpe?g|png)$/i.test(file.name)){skipped++;return;}
    items.push({file:file,name:file.name,url:URL.createObjectURL(file)});
  });
  if(skipped)setStatus(skipped+" archivo"+(skipped>1?"s omitidos":" omitido")+": aquí solo funcionan imágenes JPG o PNG.",true);
  else if(list.length)setStatus("");
  render();refresh();
}
window.__bbCaptureHandoff=function(f){addFiles([f]);};
var dz=$("dropzone"),fi=$("fileInput");
dz.addEventListener("click",function(){fi.click();});
dz.addEventListener("keydown",function(e){if(e.key==="Enter"||e.key===" "){e.preventDefault();fi.click();}});
fi.addEventListener("change",function(){addFiles(fi.files);fi.value="";});
["dragover","dragenter"].forEach(function(ev){dz.addEventListener(ev,function(e){e.preventDefault();dz.classList.add("over");});});
["dragleave","drop"].forEach(function(ev){dz.addEventListener(ev,function(e){e.preventDefault();dz.classList.remove("over");});});
dz.addEventListener("drop",function(e){if(e.dataTransfer&&e.dataTransfer.files)addFiles(e.dataTransfer.files);});
$("sizeSel").addEventListener("change",refresh);
$("clearBtn").addEventListener("click",function(){
  items.forEach(function(i){if(i.url)URL.revokeObjectURL(i.url);});
  items=[];render();refresh();setStatus("");
});
$("buildBtn").addEventListener("click",async function(){
  if(!items.length)return;
  var btn=$("buildBtn");btn.disabled=true;var old=btn.textContent;btn.textContent="Creando...";
  try{
    var PDFLib=window.PDFLib;
    var doc=await PDFLib.PDFDocument.create();
    var mode=$("sizeSel").value;
    var margin=parseInt($("marginSel").value,10)||0;
    for(var k=0;k<items.length;k++){
      setStatus("Añadiendo la imagen "+(k+1)+" de "+items.length+"...");
      var bytes=await items[k].file.arrayBuffer();
      var img;
      try{img=await doc.embedJpg(bytes);}catch(e){img=await doc.embedPng(bytes);}
      var page;
      if(mode==="fit"){
        var scale=Math.min(1,1200/Math.max(img.width,img.height));
        var w=img.width*scale,h=img.height*scale;
        page=doc.addPage([w,h]);
        page.drawImage(img,{x:0,y:0,width:w,height:h});
      }else{
        var dims=mode==="a4"?[595.28,841.89]:[612,792];
        var pw=dims[0],ph=dims[1];
        if(img.width>img.height){pw=dims[1];ph=dims[0];}
        page=doc.addPage([pw,ph]);
        var availW=pw-margin*2,availH=ph-margin*2;
        var s=Math.min(availW/img.width,availH/img.height);
        var dw=img.width*s,dh=img.height*s;
        page.drawImage(img,{x:(pw-dw)/2,y:(ph-dh)/2,width:dw,height:dh});
      }
    }
    var pdfBytes=await doc.save();
    var outName=items.length===1?items[0].name.replace(/\.[^.]+$/,"")+".pdf":"imagenes.pdf";
    saveBlob(new Blob([pdfBytes],{type:"application/pdf"}),outName);
    setStatus("Tu PDF se está descargando: "+items.length+" imagen"+(items.length>1?"es":"")+", una por página.");
    if(window.BBDropStrip)window.BBDropStrip.show('jpg-to-pdf');
  }catch(e){setStatus("La creación del PDF falló. Una de las imágenes puede estar dañada; quítala e inténtalo de nuevo.",true);}
  btn.disabled=false;btn.textContent=old;
});
function saveBlob(blob,name){var a=document.createElement("a");a.href=URL.createObjectURL(blob);a.download=name;document.body.appendChild(a);a.click();a.remove();setTimeout(function(){URL.revokeObjectURL(a.href);},4000);}
refresh();
})();
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
