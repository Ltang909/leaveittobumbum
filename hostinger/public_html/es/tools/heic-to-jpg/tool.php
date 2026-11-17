<?php require dirname(__DIR__, 3) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject);
$bbHeicMode = isset($bbHeicMode) && in_array($bbHeicMode, ['jpg','png','pdf'], true) ? $bbHeicMode : 'jpg';
$bbModes = [
  'jpg' => ['name' => 'Convertir HEIC a JPG', 'h1' => 'Convertidor HEIC a JPG gratuito', 'tag' => 'Tus fotos de iPhone, como JPG.', 'title' => 'Convertir HEIC a JPG | Convierte fotos de iPhone en tu navegador | Leave It to Bum Bum'],
  'png' => ['name' => 'Convertir HEIC a PNG', 'h1' => 'Convertidor HEIC a PNG gratuito', 'tag' => 'Tus fotos de iPhone, como PNG.', 'title' => 'Convertir HEIC a PNG | Convierte fotos de iPhone en tu navegador | Leave It to Bum Bum'],
  'pdf' => ['name' => 'Convertir HEIC a PDF', 'h1' => 'Convertidor HEIC a PDF gratuito', 'tag' => 'Tus fotos de iPhone, en un solo PDF.', 'title' => 'Convertir HEIC a PDF | Convierte fotos de iPhone en tu navegador | Leave It to Bum Bum'],
];
$bbM = $bbModes[$bbHeicMode];
$bbName = $bbM['name'];
$bbSlug = 'heic-to-' . $bbHeicMode;
$bbDesc = 'Convierte fotos HEIC de tu iPhone a JPG, PNG o PDF en tu navegador. Gratis, sin registro, y tus fotos nunca salen de tu dispositivo.';
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title><?= htmlspecialchars($bbM['title']) ?></title>
<meta name="description" content="<?= htmlspecialchars($bbDesc) ?>">
<meta property="og:title" content="<?= htmlspecialchars($bbM['title']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($bbDesc) ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/es/tools/<?= $bbSlug ?>/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/heic-to-<?= $bbHeicMode ?>.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="<?= htmlspecialchars($bbM['title']) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($bbDesc) ?>">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/heic-to-<?= $bbHeicMode ?>.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/es/tools/<?= $bbSlug ?>/"><link rel="alternate" hreflang="en" href="https://leaveittobumbum.com/tools/<?= $bbSlug ?>/"><link rel="alternate" hreflang="es" href="https://leaveittobumbum.com/es/tools/<?= $bbSlug ?>/"><link rel="alternate" hreflang="x-default" href="https://leaveittobumbum.com/tools/<?= $bbSlug ?>/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "<?= $bbName ?>",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" },
  "description": "<?= $bbDesc ?>",
  "url": "https://leaveittobumbum.com/es/tools/<?= $bbSlug ?>/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    { "@type": "Question", "name": "¿Necesito una cuenta?", "acceptedAnswer": { "@type": "Answer", "text": "No. Este convertidor es gratis, no usa acciones y no pide registro. Suelta tus fotos y descarga los archivos convertidos." } },
    { "@type": "Question", "name": "¿Mis fotos se suben a algún sitio?", "acceptedAnswer": { "@type": "Answer", "text": "No. La conversión pasa en tu navegador, en tu dispositivo. Tus fotos nunca se envían a Bum Bum ni a nadie más." } },
    { "@type": "Question", "name": "¿Por qué mis fotos de iPhone terminan en .heic?", "acceptedAnswer": { "@type": "Answer", "text": "HEIC es el formato de foto que los iPhone guardan desde iOS 11. Mantiene los archivos pequeños en el teléfono, pero muchos sitios web, formularios de subida y PC con Windows aún no pueden abrirlo. JPG y PNG se abren en todas partes." } },
    { "@type": "Question", "name": "¿Cambiará la calidad?", "acceptedAnswer": { "@type": "Answer", "text": "PNG conserva cada píxel y crea un archivo más grande. JPG te deja elegir la calidad, y el ajuste por defecto mantiene las fotos iguales mientras se abren en todas partes. PDF guarda la misma calidad JPG, una foto por página." } },
    { "@type": "Question", "name": "¿Puedo convertir un lote de fotos?", "acceptedAnswer": { "@type": "Answer", "text": "Sí. Suelta todas las fotos HEIC que quieras. Descarga cada foto convertida por separado, todas juntas en un ZIP, o elige PDF y consigue todo el conjunto en un solo archivo." } },
    { "@type": "Question", "name": "¿Qué pasa con las Live Photos?", "acceptedAnswer": { "@type": "Answer", "text": "Solo se convierte la foto fija. Una Live Photo también lleva un clip de video corto, y esa parte se queda como un archivo separado en tu teléfono." } }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"<?= $bbName ?> | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Herramientas","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"<?= $bbName ?>","item":"https://leaveittobumbum.com/es/tools/<?= $bbSlug ?>/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"Cómo usar <?= $bbName ?>","description":"Convierte fotos HEIC de un iPhone en tu navegador.","step":[{"@type":"HowToStep","position":1,"name":"Suelta tus fotos HEIC en el convertidor."},{"@type":"HowToStep","position":2,"name":"Elige tu salida: JPG, PNG o PDF."},{"@type":"HowToStep","position":3,"name":"Bum Bum las convierte en tu navegador. Tus fotos nunca salen de tu dispositivo."},{"@type":"HowToStep","position":4,"name":"Descarga cada foto, todas en un ZIP, o todo el conjunto en un solo PDF."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 3) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s;min-height:220px;display:flex;flex-direction:column;align-items:center;justify-content:center}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.controls{display:flex;gap:14px;flex-wrap:wrap;margin-top:14px;align-items:end}
.controls label{font-weight:700;font-size:14px;display:block}
.controls select{padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px}
.controls input[type=range]{margin-top:10px}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px;align-items:center}
.mini-btn{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:6px 12px;font-weight:700;font-size:13px;cursor:pointer;color:inherit}
.mini-btn:disabled{opacity:.45;cursor:default}
.dl{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:8px 16px;font-weight:700;font-size:13px;cursor:pointer;text-decoration:none;color:inherit;display:inline-block}
.dl:disabled{opacity:.45;cursor:default}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.file-row{display:flex;gap:10px;align-items:center;border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:12px;background:#fff;padding:10px 12px;margin-top:10px;flex-wrap:wrap}
.file-row img.thumb{width:52px;height:52px;object-fit:cover;border-radius:8px;border:1px solid #e2d7bf}
.file-row .grow{flex:1;min-width:160px}
.file-row .meta{font-size:13px;opacity:.75}
.file-row .st{font-size:13px;font-weight:700}
.file-row .st.err{color:#b3261e}
.hc-status{font-size:13px;font-weight:700;margin-top:10px;min-height:18px}
.hc-status.error{color:#b3261e}
.hc-note{font-size:13px;opacity:.75;margin-top:8px}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
</style></head>
<body><?php $showMeter = true; require dirname(__DIR__, 3) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Herramientas","url"=>"/tools/"],["label"=>$bbName]]; require dirname(__DIR__,3)."/includes/breadcrumbs.php"; ?><p style="font-size:13px;opacity:.75;margin:4px 0 0"><a href="/tools/heic-to-jpg/">View this tool in English</a></p><p class="eyebrow">La caja de herramientas de Bum Bum</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/heic-to-jpg.png" alt="<?= htmlspecialchars($bbName) ?>"><div><h1><?= htmlspecialchars($bbM['h1']) ?></h1><p class="lede bb-desc"><?= htmlspecialchars($bbM['tag']) ?> Bum Bum las convierte en tu navegador. Gratis, sin registro.</p></div></div>
<section class="panel" id="uploadPanel">
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Añadir fotos HEIC">
<p style="font-size:2rem;margin:0">📸</p>
<p><b>Suelta aquí tus fotos HEIC</b> o haz clic para elegirlas</p>
<p class="hc-note">Archivos .heic y .heif de iPhone y iPad. Todos los que quieras.</p>
<input type="file" id="fileInput" accept=".heic,.heif" multiple hidden>
</div>
<div class="controls">
<div><label for="fmtSel">Convertir a</label><select id="fmtSel">
<option value="jpg"<?= $bbHeicMode === 'jpg' ? ' selected' : '' ?>>JPG, se abre en todas partes</option>
<option value="png"<?= $bbHeicMode === 'png' ? ' selected' : '' ?>>PNG, conserva cada píxel</option>
<option value="pdf"<?= $bbHeicMode === 'pdf' ? ' selected' : '' ?>>PDF, un solo archivo para todo el conjunto</option>
</select></div>
<div id="qualityWrap"><label for="qualityRange">Calidad JPG: <span id="qualityVal">92%</span></label><input type="range" id="qualityRange" min="70" max="100" step="1" value="92"></div>
</div>
<div id="fileList"></div>
<div class="btnrow">
<button type="button" class="button" id="downloadAllBtn" disabled>Descargar todo en ZIP</button>
<button type="button" class="mini-btn" id="clearBtn">Vaciar la lista</button>
</div>
<p class="hc-status" id="statusLine" role="status"></p>
<p class="hc-note">La conversión se hace en tu dispositivo. Los lotes grandes de fotos pesadas pueden tardar un poco; la lista muestra cada foto cuando termina.</p>
</section>
<section class="panel" id="faq" aria-label="Preguntas frecuentes">
<h2>Preguntas frecuentes</h2>
<details><summary>¿Necesito una cuenta?</summary><p>No. Este convertidor es gratis, no usa acciones y no pide registro. Suelta tus fotos y descarga los archivos convertidos.</p></details>
<details><summary>¿Mis fotos se suben a algún sitio?</summary><p>No. La conversión pasa en tu navegador, en tu dispositivo. Tus fotos nunca se envían a Bum Bum ni a nadie más.</p></details>
<details><summary>¿Por qué mis fotos de iPhone terminan en .heic?</summary><p>HEIC es el formato de foto que los iPhone guardan desde iOS 11. Mantiene los archivos pequeños en el teléfono, pero muchos sitios web, formularios de subida y PC con Windows aún no pueden abrirlo. JPG y PNG se abren en todas partes.</p></details>
<details><summary>¿Cambiará la calidad?</summary><p>PNG conserva cada píxel y crea un archivo más grande. JPG te deja elegir la calidad, y el ajuste por defecto mantiene las fotos iguales mientras se abren en todas partes. PDF guarda la misma calidad JPG, una foto por página.</p></details>
<details><summary>¿Puedo convertir un lote de fotos?</summary><p>Sí. Suelta todas las fotos HEIC que quieras. Descarga cada foto convertida por separado, todas juntas en un ZIP, o elige PDF y consigue todo el conjunto en un solo archivo.</p></details>
<details><summary>¿Qué pasa con las Live Photos?</summary><p>Solo se convierte la foto fija. Una Live Photo también lleva un clip de video corto, y esa parte se queda como un archivo separado en tu teléfono.</p></details>
</section>
<section class="panel" aria-label="Guía">
<h2><?= htmlspecialchars($bbName) ?>, la guía corta</h2>
<p>HEIC es un buen formato en el teléfono y un dolor de cabeza en todas partes. Subidas de producto, formularios de solicitud, unidades compartidas y la mayoría de los PC con Windows quieren JPG o PNG. Este convertidor cierra esa brecha en tu propio dispositivo, así una foto de un producto, un recibo o una pizarra llena de planes se convierte en un archivo que el resto del mundo acepta.</p>
<p>Cómo elegir la salida: JPG es el valor seguro para compartir y subir, con un control de calidad si el tamaño importa. PNG va bien para capturas y gráficos que piensas editar de nuevo, porque no se desecha nada. PDF reúne todo un conjunto, una visita a un sitio, un montón de recibos, un recorrido fotográfico, en un único archivo ordenado que puedes enviar por correo.</p>
<p>En esta misma página puedes cambiar el formato de salida: JPG para compartir, PNG cuando importa cada píxel, o PDF para reunir todo el conjunto en un solo archivo. Y si tus imágenes ya son JPG, <a href="/es/tools/jpg-to-pdf/">Convertir JPG a PDF</a> las junta en un PDF. Tus fotos se quedan en tu dispositivo en todos los casos.</p>
</section>
<?php $bbEsTool = 'heic-to-jpg'; require dirname(__DIR__) . '/more-tools-es.php'; ?>
</main><?php require dirname(__DIR__, 3) . '/includes/site-footer.php'; ?>
<script src="/tools/heic-to-jpg/vendor/heic2any.min.js"></script>
<script src="/tools/pdf-merger/vendor/pdf-lib.min.js"></script>
<script src="/tools/pdf-merger/vendor/jszip.min.js"></script>
<script>
(function(){
"use strict";
var $=function(id){return document.getElementById(id);};
var items=[];var chain=Promise.resolve();
function fmtSize(n){if(n<1024)return n+" B";if(n<1048576)return (n/1024).toFixed(1)+" KB";return (n/1048576).toFixed(2)+" MB";}
function fmt(){return $("fmtSel").value;}
function setStatus(msg,isErr){var s=$("statusLine");s.textContent=msg||"";s.className="hc-status"+(isErr?" error":"");}
function outName(name){var base=name.replace(/\.[^.]+$/,"");return base+"."+(fmt()==="png"?"png":"jpg");}
function refreshAllBtn(){
  var done=items.filter(function(i){return i.status==="done";});
  var b=$("downloadAllBtn");
  b.textContent=fmt()==="pdf"?"Descargar como un solo PDF":"Descargar todo en ZIP";
  b.disabled=!done.length;
  $("qualityWrap").style.display=fmt()==="png"?"none":"";
}
function convertItem(item){
  item.status="working";item.stEl.textContent="Convirtiendo...";item.dlEl.style.display="none";
  var f=fmt();var q=parseInt($("qualityRange").value,10)/100;
  var job=(window.heic2any?window.heic2any({blob:item.file,toType:f==="png"?"image/png":"image/jpeg",quality:f==="png"?undefined:q}):Promise.reject(new Error("decoder missing")));
  return Promise.resolve(job).then(function(out){
    var blob=Array.isArray(out)?out[0]:out;
    item.blob=blob;item.status="done";
    item.url=URL.createObjectURL(blob);
    item.thumbEl.src=item.url;
    item.stEl.textContent="Listo · "+fmtSize(blob.size);
    if(f!=="pdf"){item.dlEl.href=item.url;item.dlEl.download=outName(item.name);item.dlEl.style.display="";}
    refreshAllBtn();
  }).catch(function(){
    item.status="error";item.stEl.textContent="No se pudo leer esta foto. Puede que no sea un archivo HEIC de verdad.";item.stEl.className="st err";
    refreshAllBtn();
  });
}
function enqueue(item){chain=chain.then(function(){return convertItem(item);});}
function addFiles(list){
  var skipped=0;
  Array.prototype.forEach.call(list,function(file){
    if(!/\.hei[cf]$/i.test(file.name)&&!/hei[cf]/i.test(file.type||"")){skipped++;return;}
    var row=document.createElement("div");row.className="file-row";
    row.innerHTML='<img class="thumb" alt=""><div class="grow"><div><b></b></div><div class="meta"></div></div><span class="st">En espera</span><a class="dl" style="display:none">Descargar</a>';
    row.querySelector("b").textContent=file.name;
    row.querySelector(".meta").textContent=fmtSize(file.size);
    $("fileList").appendChild(row);
    var item={file:file,name:file.name,status:"waiting",blob:null,url:null,
      thumbEl:row.querySelector(".thumb"),stEl:row.querySelector(".st"),dlEl:row.querySelector(".dl")};
    items.push(item);enqueue(item);
  });
  if(skipped)setStatus(skipped+" archivo"+(skipped>1?"s omitidos":" omitido")+": aquí solo funcionan fotos .heic o .heif.",true);
  else if(list.length)setStatus("");
  refreshAllBtn();
}
var dz=$("dropzone"),fi=$("fileInput");
dz.addEventListener("click",function(){fi.click();});
dz.addEventListener("keydown",function(e){if(e.key==="Enter"||e.key===" "){e.preventDefault();fi.click();}});
fi.addEventListener("change",function(){addFiles(fi.files);fi.value="";});
["dragover","dragenter"].forEach(function(ev){dz.addEventListener(ev,function(e){e.preventDefault();dz.classList.add("over");});});
["dragleave","drop"].forEach(function(ev){dz.addEventListener(ev,function(e){e.preventDefault();dz.classList.remove("over");});});
dz.addEventListener("drop",function(e){if(e.dataTransfer&&e.dataTransfer.files)addFiles(e.dataTransfer.files);});
$("qualityRange").addEventListener("input",function(){$("qualityVal").textContent=$("qualityRange").value+"%";});
$("qualityRange").addEventListener("change",function(){reconvert();});
$("fmtSel").addEventListener("change",function(){reconvert();});
function reconvert(){
  items.forEach(function(i){if(i.url)URL.revokeObjectURL(i.url);i.blob=null;i.url=null;i.status="waiting";i.stEl.textContent="En espera";i.stEl.className="st";i.dlEl.style.display="none";});
  items.forEach(enqueue);refreshAllBtn();
}
$("clearBtn").addEventListener("click",function(){
  items.forEach(function(i){if(i.url)URL.revokeObjectURL(i.url);});
  items=[];$("fileList").textContent="";setStatus("");refreshAllBtn();
});
$("downloadAllBtn").addEventListener("click",async function(){
  var done=items.filter(function(i){return i.status==="done";});
  if(!done.length)return;
  var btn=$("downloadAllBtn");btn.disabled=true;var old=btn.textContent;btn.textContent="Preparando...";
  try{
    if(fmt()==="pdf"){
      var doc=await window.PDFLib.PDFDocument.create();
      for(var k=0;k<done.length;k++){
        var bytes=await done[k].blob.arrayBuffer();
        var img=await doc.embedJpg(bytes);
        var scale=Math.min(1,792/img.height,612/img.width);
        var w=img.width*scale,h=img.height*scale;
        var page=doc.addPage([w,h]);
        page.drawImage(img,{x:0,y:0,width:w,height:h});
      }
      var pdfBytes=await doc.save();
      saveBlob(new Blob([pdfBytes],{type:"application/pdf"}),"fotos-heic.pdf");
      setStatus("Tu PDF se está descargando: "+done.length+" foto"+(done.length>1?"s":"")+", una por página.");
    }else{
      var zip=new window.JSZip();var seen={};
      done.forEach(function(i){var n=outName(i.name);if(seen[n]){seen[n]++;n=n.replace(/(\.[^.]+)$/," ("+seen[n]+")$1");}else seen[n]=1;zip.file(n,i.blob);});
      var zb=await zip.generateAsync({type:"blob"});
      saveBlob(zb,"fotos-heic.zip");
      setStatus("Tu ZIP se está descargando con "+done.length+" foto"+(done.length>1?"s":"")+" convertida"+(done.length>1?"s":"")+".");
    }
  }catch(e){setStatus("La preparación de la descarga falló. Aun así puedes descargar cada foto desde su fila.",true);}
  btn.disabled=false;btn.textContent=old;
});
function saveBlob(blob,name){var a=document.createElement("a");a.href=URL.createObjectURL(blob);a.download=name;document.body.appendChild(a);a.click();a.remove();setTimeout(function(){URL.revokeObjectURL(a.href);},4000);}
refreshAllBtn();
})();
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
