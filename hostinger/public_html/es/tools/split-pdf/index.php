<?php require dirname(__DIR__, 3) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); $bbLang = 'es'; ?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Dividir PDF | Separa las páginas de un PDF en tu navegador | Leave It to Bum Bum</title>
<meta name="description" content="Divide un PDF gratis en tu navegador. Extrae páginas en grupos, cada grupo como su propio PDF, o separa cada página en su propio archivo. Sin registro, y tu PDF nunca sale de tu dispositivo.">
<meta property="og:title" content="Dividir PDF | Separa las páginas de un PDF en tu navegador | Leave It to Bum Bum">
<meta property="og:description" content="Divide un PDF gratis en tu navegador. Extrae páginas en grupos, cada grupo como su propio PDF, o separa cada página en su propio archivo. Sin registro, y tu PDF nunca sale de tu dispositivo.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/es/tools/split-pdf/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/split-pdf.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Dividir PDF | Separa las páginas de un PDF en tu navegador | Leave It to Bum Bum">
<meta name="twitter:description" content="Divide un PDF gratis en tu navegador. Extrae páginas en grupos, cada grupo como su propio PDF, o separa cada página en su propio archivo. Sin registro, y tu PDF nunca sale de tu dispositivo.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/split-pdf.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/es/tools/split-pdf/"><link rel="alternate" hreflang="en" href="https://leaveittobumbum.com/tools/split-pdf/"><link rel="alternate" hreflang="es" href="https://leaveittobumbum.com/es/tools/split-pdf/"><link rel="alternate" hreflang="x-default" href="https://leaveittobumbum.com/tools/split-pdf/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Dividir PDF",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" },
  "description": "Divide un PDF gratis en tu navegador. Extrae páginas en grupos, cada grupo como su propio PDF, o separa cada página en su propio archivo. Sin registro, y tu PDF nunca sale de tu dispositivo.",
  "url": "https://leaveittobumbum.com/es/tools/split-pdf/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    { "@type": "Question", "name": "¿Necesito una cuenta?", "acceptedAnswer": { "@type": "Answer", "text": "No. Este divisor es gratis y no pide registro. Suelta tu PDF y descarga las partes." } },
    { "@type": "Question", "name": "¿Mi PDF se sube a algún sitio?", "acceptedAnswer": { "@type": "Answer", "text": "No. La división pasa en tu navegador, en tu dispositivo. Tu PDF nunca se envía a Bum Bum ni a nadie más." } },
    { "@type": "Question", "name": "¿Cómo funcionan los rangos de páginas?", "acceptedAnswer": { "@type": "Answer", "text": "Cada grupo que escribes se convierte en su propio PDF. Escribir 1-2, 3 te da dos archivos: uno con las páginas 1 y 2, y otro con la página 3. Cuando hay más de un grupo, los archivos llegan juntos en un ZIP. Un solo grupo se descarga como un PDF normal." } },
    { "@type": "Question", "name": "¿Qué me da separar cada página?", "acceptedAnswer": { "@type": "Answer", "text": "Un PDF por página, reunidos en una sola descarga ZIP. Un extracto de 12 páginas se convierte en 12 PDF pequeños nombrados por número de página." } },
    { "@type": "Question", "name": "¿Puede abrir un PDF protegido con contraseña?", "acceptedAnswer": { "@type": "Answer", "text": "No. Un PDF bloqueado tiene que desbloquearlo primero quien lo creó. Si sabes la contraseña, ábrelo en un lector de PDF, imprímelo o guárdalo sin protección y divide esa copia." } },
    { "@type": "Question", "name": "¿Las páginas perderán calidad?", "acceptedAnswer": { "@type": "Answer", "text": "No. Las páginas se copian tal cual, con el mismo texto, imágenes y diseño. Nada se vuelve a dibujar ni a comprimir." } }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Split PDF | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Herramientas","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Dividir PDF","item":"https://leaveittobumbum.com/es/tools/split-pdf/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"Cómo usar Dividir PDF","description":"Extrae páginas de un PDF en tu navegador.","step":[{"@type":"HowToStep","position":1,"name":"Suelta tu PDF en el divisor."},{"@type":"HowToStep","position":2,"name":"Elige un rango de páginas o separa cada página en su propio archivo."},{"@type":"HowToStep","position":3,"name":"Bum Bum lo divide en tu navegador. Tu PDF nunca sale de tu dispositivo."},{"@type":"HowToStep","position":4,"name":"Descarga el PDF nuevo o el ZIP de páginas sueltas."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 3) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s;min-height:220px;display:flex;flex-direction:column;align-items:center;justify-content:center}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.hidden{display:none!important}
.mode-opts{display:grid;gap:10px;margin-top:16px}
.mode-opt{display:flex;gap:10px;align-items:flex-start;margin:0;border:1px solid #e2d7bf;border-radius:12px;background:#fff;padding:12px 14px;cursor:pointer;text-align:left}
.mode-opt:has(input:checked){border-color:#b3a37e;background:#fffdf8}
.mode-opt input{width:auto;padding:0;border:none;flex:none;margin:3px 0 0;accent-color:#2f2a22}
.mode-opt>span{flex:1;min-width:0}
.mode-opt .mode-sub{font-size:13px;opacity:.75;font-weight:400}
.range-row{margin-top:12px}
.range-row label{font-weight:700;font-size:14px;display:block}
.range-row input[type=text]{padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px;align-items:center}
.mini-btn{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:6px 12px;font-weight:700;font-size:13px;cursor:pointer;color:inherit}
.mini-btn:disabled{opacity:.45;cursor:default}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.file-row{display:flex;gap:10px;align-items:center;border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:12px;background:#fff;padding:10px 12px;margin-top:10px;flex-wrap:wrap}
.file-row .grow{flex:1;min-width:160px}
.file-row .meta{font-size:13px;opacity:.75}
.file-row .st{font-size:13px;font-weight:700}
.hc-status{font-size:13px;font-weight:700;margin-top:10px;min-height:18px}
.hc-status.error{color:#b3261e}
.hc-note{font-size:13px;opacity:.75;margin-top:8px}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
</style></head>
<body><?php $showMeter = true; require dirname(__DIR__, 3) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Herramientas","url"=>"/tools/"],["label"=>"Dividir PDF"]]; require dirname(__DIR__,3)."/includes/breadcrumbs.php"; ?><p style="font-size:13px;opacity:.75;margin:4px 0 0"><a href="/tools/split-pdf/">View this tool in English</a></p><p class="eyebrow">La caja de herramientas de Bum Bum</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/split-pdf.png" alt="Dividir PDF"><div><h1>Dividir PDF gratis</h1><p class="lede bb-desc">Quédate con las páginas que necesitas. Extrae un rango de un PDF o sepáralo página por página, en tu navegador.</p></div></div>
<section class="panel" id="uploadPanel">
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Añadir un PDF">
<p style="font-size:2rem;margin:0">📄</p>
<p><b>Suelta tu PDF aquí</b> o haz clic para elegirlo</p>
<p class="hc-note">Un PDF a la vez. Bum Bum lee su número de páginas y luego eliges la división.</p>
<input type="file" id="fileInput" accept="application/pdf,.pdf" hidden>
</div>
<div id="fileList"></div>
<div id="splitControls" class="hidden">
<div class="mode-opts" role="radiogroup" aria-label="¿Qué quieres hacer?">
<label class="mode-opt"><input type="radio" name="splitMode" value="range" checked><span><b>Extraer algunas páginas</b><br><span class="mode-sub">Separa los rangos de páginas con comas para crear un PDF por grupo. Por ejemplo, 1-2, 3 crea dos PDF: uno con las páginas 1–2 y otro con la página 3.</span></span></label>
<label class="mode-opt"><input type="radio" name="splitMode" value="each"><span><b>Separar en páginas individuales</b><br><span class="mode-sub">Una descarga ZIP con un PDF por cada página.</span></span></label>
</div>
<div class="range-row" id="rangeWrap"><label for="rangeInput">¿Qué páginas?</label><input type="text" id="rangeInput" placeholder="1-2, 3" size="22"> <span class="hc-note" id="rangeHint"></span></div>
<div class="btnrow">
<button type="button" class="button" id="splitBtn" disabled>Dividir mi PDF</button>
<button type="button" class="mini-btn" id="clearBtn">Borrar</button>
</div>
</div>
<p class="hc-status" id="statusLine" role="status"></p>
<p class="hc-note">La división se hace en tu dispositivo. Los PDF muy grandes pueden tardar un poco en leerse.</p>
</section>
<section class="panel" id="faq" aria-label="Preguntas frecuentes">
<h2>Preguntas frecuentes</h2>
<details><summary>¿Necesito una cuenta?</summary><p>No. Este divisor es gratis y no pide registro. Suelta tu PDF y descarga las partes.</p></details>
<details><summary>¿Mi PDF se sube a algún sitio?</summary><p>No. La división pasa en tu navegador, en tu dispositivo. Tu PDF nunca se envía a Bum Bum ni a nadie más.</p></details>
<details><summary>¿Cómo funcionan los rangos de páginas?</summary><p>Cada grupo que escribes se convierte en su propio PDF. Escribir 1-2, 3 te da dos archivos: uno con las páginas 1 y 2, y otro con la página 3. Cuando hay más de un grupo, los archivos llegan juntos en un ZIP. Un solo grupo se descarga como un PDF normal.</p></details>
<details><summary>¿Qué me da separar cada página?</summary><p>Un PDF por página, reunidos en una sola descarga ZIP. Un extracto de 12 páginas se convierte en 12 PDF pequeños nombrados por número de página.</p></details>
<details><summary>¿Puede abrir un PDF protegido con contraseña?</summary><p>No. Un PDF bloqueado tiene que desbloquearlo primero quien lo creó. Si sabes la contraseña, ábrelo en un lector de PDF, imprímelo o guárdalo sin protección y divide esa copia.</p></details>
<details><summary>¿Las páginas perderán calidad?</summary><p>No. Las páginas se copian tal cual, con el mismo texto, imágenes y diseño. Nada se vuelve a dibujar ni a comprimir.</p></details>
</section>
<section class="panel" aria-label="Guía">
<h2>Dividir PDF, la guía corta</h2>
<p>La mayoría de los PDF llegan como paquetes: un contrato con la página de la firma escondida al final, un extracto bancario de todo un trimestre, un informe del que solo importa una parte a quien se lo envías. Este divisor saca las páginas que importan y deja el resto, en tu propio dispositivo.</p>
<p>Cómo elegir la división: los grupos sirven para desarmar un documento en sus piezas, como la página firmada en un archivo y las páginas del presupuesto en otro. Separar cada página sirve para clasificar, porque cada página se convierte en su propio archivo que puedes nombrar, guardar y reenviar una por una.</p>
<p>La familia de este divisor: <a href="/es/tools/pdf-merger/">Unir PDF</a> cose PDF en uno solo, <a href="/es/tools/jpg-to-pdf/">Convertir JPG a PDF</a> convierte imágenes en un PDF nuevo, y <a href="/es/tools/pdf-compressor/">Comprimir PDF</a> reduce un PDF demasiado grande para enviar por correo. Tus archivos se quedan en tu dispositivo en todos ellos.</p>
</section>
<?php $bbEsTool = 'split-pdf'; require dirname(__DIR__) . '/more-tools-es.php'; ?>
</main><?php require dirname(__DIR__, 3) . '/includes/site-footer.php'; ?>
<script src="/tools/pdf-merger/vendor/pdf-lib.min.js"></script>
<script src="/tools/pdf-merger/vendor/jszip.min.js"></script>
<script>
(function(){
"use strict";
var $=function(id){return document.getElementById(id);};
var current=null; // {file, name, doc, count}
var lastSplit=null; // {sig, blob, name} of the last finished split, so a repeat click is recognized
function fmtSize(n){if(n<1024)return n+" B";if(n<1048576)return (n/1024).toFixed(1)+" KB";return (n/1048576).toFixed(2)+" MB";}
function setStatus(msg,isErr){var s=$("statusLine");s.textContent=msg||"";s.className="hc-status"+(isErr?" error":"");}
function getMode(){var r=document.querySelector('input[name="splitMode"]:checked');return r?r.value:"range";}
function refresh(){
  $("splitBtn").disabled=!current;
  $("splitControls").classList.toggle("hidden",!current);
  $("rangeWrap").style.display=getMode()==="range"?"":"none";
}
function parseGroups(text,count){
  var groups=[];
  var parts=String(text||"").split(",");
  for(var i=0;i<parts.length;i++){
    var p=parts[i].trim();if(!p)continue;
    var group=[],seen={};
    var m=p.match(/^(\d+)\s*-\s*(\d+)$/);
    if(m){
      var a=parseInt(m[1],10),b=parseInt(m[2],10);
      if(a>b){var t=a;a=b;b=t;}
      for(var n=a;n<=b;n++){
        if(n<1||n>count)return null;
        if(!seen[n]){seen[n]=1;group.push(n);}
      }
    }else if(/^\d+$/.test(p)){
      var s=parseInt(p,10);
      if(s<1||s>count)return null;
      group.push(s);
    }else return null;
    if(group.length)groups.push(group);
  }
  return groups.length?groups:null;
}
function groupName(base,group){
  if(group.length===1)return base+"-pagina-"+group[0]+".pdf";
  return base+"-paginas-"+group[0]+"-"+group[group.length-1]+".pdf";
}
function groupLabel(group){
  if(group.length===1)return "página "+group[0];
  return "páginas "+group[0]+"-"+group[group.length-1];
}
async function addFile(file){
  if(!/\.pdf$/i.test(file.name)&&!/pdf/i.test(file.type||"")){setStatus("Eso no es un PDF. Suelta un archivo que termine en .pdf.",true);return;}
  setStatus("Leyendo tu PDF...");
  try{
    var bytes=await file.arrayBuffer();
    var doc=await window.PDFLib.PDFDocument.load(bytes,{ignoreEncryption:true});
    current={file:file,name:file.name,doc:doc,count:doc.getPageCount()};
    lastSplit=null;
    var list=$("fileList");list.textContent="";
    var row=document.createElement("div");row.className="file-row";
    row.innerHTML='<div class="grow"><div><b></b></div><div class="meta"></div></div><span class="st"></span>';
    row.querySelector("b").textContent=file.name;
    row.querySelector(".meta").textContent=fmtSize(file.size);
    row.querySelector(".st").textContent=current.count+" página"+(current.count===1?"":"s");
    list.appendChild(row);
    $("rangeHint").textContent="Este PDF tiene "+current.count+" página"+(current.count===1?"":"s")+", numeradas del 1 al "+current.count+".";
    setStatus("Cargado: "+current.count+" página"+(current.count===1?"":"s")+".");
  }catch(e){
    current=null;$("fileList").textContent="";
    setStatus("Bum Bum no pudo leer ese PDF. Puede estar protegido con contraseña o dañado.",true);
  }
  refresh();
}
window.__bbCaptureHandoff=function(f){addFile(f);};
var dz=$("dropzone"),fi=$("fileInput");
dz.addEventListener("click",function(){fi.click();});
dz.addEventListener("keydown",function(e){if(e.key==="Enter"||e.key===" "){e.preventDefault();fi.click();}});
fi.addEventListener("change",function(){if(fi.files.length)addFile(fi.files[0]);fi.value="";});
["dragover","dragenter"].forEach(function(ev){dz.addEventListener(ev,function(e){e.preventDefault();dz.classList.add("over");});});
["dragleave","drop"].forEach(function(ev){dz.addEventListener(ev,function(e){e.preventDefault();dz.classList.remove("over");});});
dz.addEventListener("drop",function(e){if(e.dataTransfer&&e.dataTransfer.files.length)addFile(e.dataTransfer.files[0]);});
Array.prototype.forEach.call(document.querySelectorAll('input[name="splitMode"]'),function(r){r.addEventListener("change",refresh);});
$("clearBtn").addEventListener("click",function(){current=null;lastSplit=null;$("fileList").textContent="";$("rangeInput").value="";setStatus("");refresh();});
$("splitBtn").addEventListener("click",async function(){
  if(!current)return;
  var btn=$("splitBtn");btn.disabled=true;var old=btn.textContent;btn.textContent="Dividiendo...";
  var base=current.name.replace(/\.pdf$/i,"");
  var mode=getMode();
  var sig=current.file.name+"|"+current.file.size+"|"+(current.file.lastModified||0)+"|"+mode+"|"+(mode==="range"?$("rangeInput").value.trim():"");
  if(lastSplit&&lastSplit.sig===sig){
    saveBlob(lastSplit.blob,lastSplit.name);
    setStatus("Ya se dividió con estos ajustes. Descargando el mismo archivo otra vez.");
    btn.disabled=false;btn.textContent=old;refresh();return;
  }
  try{
    var PDFLib=window.PDFLib;
    if(mode==="each"){
      var zip=new window.JSZip();
      var width=String(current.count).length;
      for(var i=0;i<current.count;i++){
        setStatus("Separando la página "+(i+1)+" de "+current.count+"...");
        var nd=await PDFLib.PDFDocument.create();
        var copied=await nd.copyPages(current.doc,[i]);
        nd.addPage(copied[0]);
        var b=await nd.save();
        var num=String(i+1);while(num.length<width)num="0"+num;
        zip.file(base+"-pagina-"+num+".pdf",b);
      }
      var zb=await zip.generateAsync({type:"blob"});
      saveBlob(zb,base+"-pages.zip");
      lastSplit={sig:sig,blob:zb,name:base+"-pages.zip"};
      setStatus("Listo. Tu ZIP se descargó: "+current.count+" PDF"+(current.count===1?"":"s")+", uno por página.");
    }else{
      var groups=parseGroups($("rangeInput").value,current.count);
      if(!groups){setStatus("Eso no funciona para un PDF de "+current.count+" páginas. Usa páginas entre 1 y "+current.count+", como 1-2, 3.",true);btn.disabled=false;btn.textContent=old;refresh();return;}
      async function buildGroup(group){
        var nd=await PDFLib.PDFDocument.create();
        var idx=group.map(function(p){return p-1;});
        var cp=await nd.copyPages(current.doc,idx);
        cp.forEach(function(pg){nd.addPage(pg);});
        return nd.save();
      }
      if(groups.length===1){
        var out1=await buildGroup(groups[0]);
        var blob1=new Blob([out1],{type:"application/pdf"});
        saveBlob(blob1,groupName(base,groups[0]));
        lastSplit={sig:sig,blob:blob1,name:groupName(base,groups[0])};
        setStatus("Listo. Tu PDF se descargó: "+groupLabel(groups[0])+".");
      }else{
        var zip2=new window.JSZip();
        for(var gi=0;gi<groups.length;gi++){
          setStatus("Creando el archivo "+(gi+1)+" de "+groups.length+"...");
          zip2.file(groupName(base,groups[gi]),await buildGroup(groups[gi]));
        }
        var zb2=await zip2.generateAsync({type:"blob"});
        saveBlob(zb2,base+"-split.zip");
        lastSplit={sig:sig,blob:zb2,name:base+"-split.zip"};
        setStatus("Listo. Tu ZIP se descargó: "+groups.length+" PDF ("+groups.map(groupLabel).join(", ")+").");
      }
    }
    if(window.BBDropStrip)window.BBDropStrip.show('split-pdf');
  }catch(e){setStatus("La división falló. El PDF puede usar funciones que esta herramienta del navegador no puede copiar.",true);}
  btn.disabled=false;btn.textContent=old;refresh();
});
function saveBlob(blob,name){var a=document.createElement("a");a.href=URL.createObjectURL(blob);a.download=name;document.body.appendChild(a);a.click();a.remove();setTimeout(function(){URL.revokeObjectURL(a.href);},4000);}
refresh();
})();
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
