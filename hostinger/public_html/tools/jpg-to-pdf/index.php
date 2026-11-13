<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>JPG to PDF Converter | Turn Images into One PDF in Your Browser | Leave It to Bum Bum</title>
<meta name="description" content="Free JPG to PDF converter that runs in your browser. Drop in JPG or PNG images, set the order, pick a page size, and download one PDF. No sign-up, and your images never leave your device.">
<meta property="og:title" content="JPG to PDF Converter | Turn Images into One PDF in Your Browser | Leave It to Bum Bum">
<meta property="og:description" content="Free JPG to PDF converter that runs in your browser. Drop in JPG or PNG images, set the order, pick a page size, and download one PDF. No sign-up, and your images never leave your device.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/jpg-to-pdf/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/jpg-to-pdf.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="JPG to PDF Converter | Turn Images into One PDF in Your Browser | Leave It to Bum Bum">
<meta name="twitter:description" content="Free JPG to PDF converter that runs in your browser. Drop in JPG or PNG images, set the order, pick a page size, and download one PDF. No sign-up, and your images never leave your device.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/jpg-to-pdf.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/tools/jpg-to-pdf/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "JPG to PDF",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" },
  "description": "Free JPG to PDF converter that runs in your browser. Drop in JPG or PNG images, set the order, pick a page size, and download one PDF. No sign-up, and your images never leave your device.",
  "url": "https://leaveittobumbum.com/tools/jpg-to-pdf/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    { "@type": "Question", "name": "Do I need an account?", "acceptedAnswer": { "@type": "Answer", "text": "No. This converter is free, uses no actions, and needs no sign-up. Drop your images in and download the PDF." } },
    { "@type": "Question", "name": "Do my images upload anywhere?", "acceptedAnswer": { "@type": "Answer", "text": "No. The PDF is built right in your browser, on your device. Your images are never sent to Bum Bum or anyone else." } },
    { "@type": "Question", "name": "Which image types can I use?", "acceptedAnswer": { "@type": "Answer", "text": "JPG and PNG. Photos from a camera or phone, scans, and screenshots all work. iPhone HEIC photos need the HEIC to JPG converter first, since browsers cannot open HEIC on their own." } },
    { "@type": "Question", "name": "Can I change the page order?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Every image in the list has up and down buttons. The PDF follows the order you see, first image on page one." } },
    { "@type": "Question", "name": "What page size should I pick?", "acceptedAnswer": { "@type": "Answer", "text": "Fit to image makes each page the size of its picture, which suits photos and scans. A4 and Letter make uniform pages for printing and forms, turning sideways for wide images so nothing gets cropped." } },
    { "@type": "Question", "name": "Will my images lose quality?", "acceptedAnswer": { "@type": "Answer", "text": "No. JPGs go into the PDF exactly as they are, with no second round of compression, and PNGs stay lossless. The file you download holds the same pixels you dropped in." } }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"JPG to PDF | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"JPG to PDF","item":"https://leaveittobumbum.com/tools/jpg-to-pdf/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use JPG to PDF","description":"Turn JPG or PNG images into one PDF in your browser.","step":[{"@type":"HowToStep","position":1,"name":"Drop your JPG or PNG images into the converter."},{"@type":"HowToStep","position":2,"name":"Put the images in order with the up and down buttons."},{"@type":"HowToStep","position":3,"name":"Pick a page size: fit to image, A4, or Letter."},{"@type":"HowToStep","position":4,"name":"Build the PDF and download it. Your images never leave your device."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
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
<body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"JPG to PDF"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/jpg-to-pdf.png" alt="JPG to PDF"><div><h1>Free JPG to PDF Converter</h1><p class="lede bb-desc">Your pictures, gathered into one PDF. Bum Bum builds it right in your browser. Free, no sign-up needed.</p></div></div>
<section class="panel" id="uploadPanel">
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Add images">
<p style="font-size:2rem;margin:0">🖼️</p>
<p><b>Drop your JPG or PNG images here</b> or click to pick them</p>
<p class="hc-note">As many as you like. Page one is the first image in the list.</p>
<input type="file" id="fileInput" accept="image/jpeg,image/png,.jpg,.jpeg,.png" multiple hidden>
</div>
<div class="controls">
<div><label for="sizeSel">Page size</label><select id="sizeSel">
<option value="fit">Fit to image</option>
<option value="a4">A4 pages</option>
<option value="letter">Letter pages</option>
</select></div>
<div id="marginWrap" style="display:none"><label for="marginSel">Margin</label><select id="marginSel">
<option value="36">Small</option>
<option value="72">Comfortable</option>
<option value="0">None, fill the page</option>
</select></div>
</div>
<div id="fileList"></div>
<div class="btnrow">
<button type="button" class="button" id="buildBtn" disabled>Build my PDF</button>
<button type="button" class="mini-btn" id="clearBtn">Clear the list</button>
</div>
<p class="hc-status" id="statusLine" role="status"></p>
<p class="hc-note">Building runs on your device. A long list of big photos can take a moment; the status line keeps you posted.</p>
</section>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2>Questions, answered</h2>
<details><summary>Do I need an account?</summary><p>No. This converter is free, uses no actions, and needs no sign-up. Drop your images in and download the PDF.</p></details>
<details><summary>Do my images upload anywhere?</summary><p>No. The PDF is built right in your browser, on your device. Your images are never sent to Bum Bum or anyone else.</p></details>
<details><summary>Which image types can I use?</summary><p>JPG and PNG. Photos from a camera or phone, scans, and screenshots all work. iPhone HEIC photos need the <a href="/tools/heic-to-jpg/">HEIC to JPG converter</a> first, since browsers cannot open HEIC on their own.</p></details>
<details><summary>Can I change the page order?</summary><p>Yes. Every image in the list has up and down buttons. The PDF follows the order you see, first image on page one.</p></details>
<details><summary>What page size should I pick?</summary><p>Fit to image makes each page the size of its picture, which suits photos and scans. A4 and Letter make uniform pages for printing and forms, turning sideways for wide images so nothing gets cropped.</p></details>
<details><summary>Will my images lose quality?</summary><p>No. JPGs go into the PDF exactly as they are, with no second round of compression, and PNGs stay lossless. The file you download holds the same pixels you dropped in.</p></details>
</section>
<section class="panel" aria-label="Guide">
<h2>JPG to PDF, the short guide</h2>
<p>Forms, applications, and clients all ask for one PDF while life hands you a pile of separate images: photos of receipts, scans of signed pages, screenshots of a chat. This converter gathers the pile into a single file, in the order you set, without sending any of it to a server.</p>
<p>Picking a page size: choose Fit to image when the PDF is headed for a screen, since every page matches its picture and nothing gets padded. Choose A4 or Letter when the file will be printed or attached to a form, since uniform pages print cleanly and wide images turn sideways instead of shrinking into a corner.</p>
<p>Working through this converter's family: <a href="/tools/pdf-merger/">PDF Merger</a> stitches finished PDFs together, <a href="/tools/split-pdf/">Split PDF</a> pulls a PDF apart into pages or a range, and <a href="/tools/pdf-to-image/">PDF to Image</a> runs this trip in reverse. Your files stay on your device in all of them.</p>
</section>
<?php $bbTool = 'jpg-to-pdf'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
</main><?php require dirname(__DIR__, 2) . '/includes/site-footer.php'; ?>
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
    row.querySelector(".st").textContent="Page "+(idx+1);
    if(item.url)row.querySelector(".thumb").src=item.url;
    var up=document.createElement("button");up.type="button";up.className="mini-btn";up.textContent="↑";up.title="Move up";up.disabled=idx===0;
    var dn=document.createElement("button");dn.type="button";dn.className="mini-btn";dn.textContent="↓";dn.title="Move down";dn.disabled=idx===items.length-1;
    var rm=document.createElement("button");rm.type="button";rm.className="mini-btn";rm.textContent="Remove";
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
  if(skipped)setStatus(skipped+" file"+(skipped>1?"s were":" was")+" skipped: only JPG or PNG images work here.",true);
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
  var btn=$("buildBtn");btn.disabled=true;var old=btn.textContent;btn.textContent="Building...";
  try{
    var PDFLib=window.PDFLib;
    var doc=await PDFLib.PDFDocument.create();
    var mode=$("sizeSel").value;
    var margin=parseInt($("marginSel").value,10)||0;
    for(var k=0;k<items.length;k++){
      setStatus("Adding image "+(k+1)+" of "+items.length+"...");
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
    var outName=items.length===1?items[0].name.replace(/\.[^.]+$/,"")+".pdf":"images.pdf";
    saveBlob(new Blob([pdfBytes],{type:"application/pdf"}),outName);
    setStatus("Your PDF is downloading: "+items.length+" image"+(items.length>1?"s":"")+", one per page.");
    if(window.BBDropStrip)window.BBDropStrip.show('jpg-to-pdf');
  }catch(e){setStatus("Building the PDF failed. One of the images may be damaged; try removing it and building again.",true);}
  btn.disabled=false;btn.textContent=old;
});
function saveBlob(blob,name){var a=document.createElement("a");a.href=URL.createObjectURL(blob);a.download=name;document.body.appendChild(a);a.click();a.remove();setTimeout(function(){URL.revokeObjectURL(a.href);},4000);}
refresh();
})();
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
