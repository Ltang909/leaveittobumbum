<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject);
$bbHeicMode = isset($bbHeicMode) && in_array($bbHeicMode, ['jpg','png','pdf'], true) ? $bbHeicMode : 'jpg';
$bbModes = [
  'jpg' => ['name' => 'HEIC to JPG', 'h1' => 'Free HEIC to JPG Converter', 'tag' => 'Your iPhone photos, as JPGs.', 'title' => 'HEIC to JPG Converter | Convert iPhone Photos in Your Browser | Leave It to Bum Bum'],
  'png' => ['name' => 'HEIC to PNG', 'h1' => 'Free HEIC to PNG Converter', 'tag' => 'Your iPhone photos, as PNGs.', 'title' => 'HEIC to PNG Converter | Convert iPhone Photos in Your Browser | Leave It to Bum Bum'],
  'pdf' => ['name' => 'HEIC to PDF', 'h1' => 'Free HEIC to PDF Converter', 'tag' => 'Your iPhone photos, in one PDF.', 'title' => 'HEIC to PDF Converter | Convert iPhone Photos in Your Browser | Leave It to Bum Bum'],
];
$bbM = $bbModes[$bbHeicMode];
$bbName = $bbM['name'];
$bbSlug = 'heic-to-' . $bbHeicMode;
$bbDesc = 'Convert HEIC photos from your iPhone to JPG, PNG, or PDF right in your browser. Free, no sign-up, and your photos never leave your device.';
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title><?= htmlspecialchars($bbM['title']) ?></title>
<meta name="description" content="<?= htmlspecialchars($bbDesc) ?>">
<meta property="og:title" content="<?= htmlspecialchars($bbM['title']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($bbDesc) ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/<?= $bbSlug ?>/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/icons/heic-to-jpg.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="<?= htmlspecialchars($bbM['title']) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($bbDesc) ?>">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/icons/heic-to-jpg.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/<?= $bbSlug ?>/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "<?= $bbName ?>",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" },
  "description": "<?= $bbDesc ?>",
  "url": "https://leaveittobumbum.com/tools/<?= $bbSlug ?>/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    { "@type": "Question", "name": "Do I need an account?", "acceptedAnswer": { "@type": "Answer", "text": "No. This converter is free, uses no actions, and needs no sign-up. Drop your photos in and download the converted files." } },
    { "@type": "Question", "name": "Do my photos upload anywhere?", "acceptedAnswer": { "@type": "Answer", "text": "No. Conversion happens right in your browser, on your device. Your photos are never sent to Bum Bum or anyone else." } },
    { "@type": "Question", "name": "Why do my iPhone photos end in .heic?", "acceptedAnswer": { "@type": "Answer", "text": "HEIC is the photo format iPhones have saved in since iOS 11. It keeps files small on the phone, but many websites, upload forms, and Windows PCs still cannot open it. JPG and PNG open everywhere." } },
    { "@type": "Question", "name": "Will the quality change?", "acceptedAnswer": { "@type": "Answer", "text": "PNG keeps every pixel and makes a bigger file. JPG lets you pick the quality, and the default setting keeps photos looking the same while opening anywhere. PDF holds the same JPG quality, one photo per page." } },
    { "@type": "Question", "name": "Can I convert a batch of photos?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Drop in as many HEIC photos as you like. Download each converted photo on its own, grab them all in one ZIP, or pick PDF and get the whole set as a single file." } },
    { "@type": "Question", "name": "What about Live Photos?", "acceptedAnswer": { "@type": "Answer", "text": "Only the still photo converts. A Live Photo also carries a short video clip, and that part stays a separate file on your phone." } }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"<?= $bbName ?> | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"<?= $bbName ?>","item":"https://leaveittobumbum.com/tools/<?= $bbSlug ?>/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use <?= $bbName ?>","description":"Convert HEIC photos from an iPhone in your browser.","step":[{"@type":"HowToStep","position":1,"name":"Drop your HEIC photos into the converter."},{"@type":"HowToStep","position":2,"name":"Pick your output: JPG, PNG, or PDF."},{"@type":"HowToStep","position":3,"name":"Bum Bum converts them right in your browser. Your photos never leave your device."},{"@type":"HowToStep","position":4,"name":"Download each photo, all of them in a ZIP, or the whole set as one PDF."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
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
<body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>$bbName]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/heic-to-jpg.png" alt="<?= htmlspecialchars($bbName) ?>"><div><h1><?= htmlspecialchars($bbM['h1']) ?></h1><p class="lede bb-desc"><?= htmlspecialchars($bbM['tag']) ?> Bum Bum converts them right in your browser. Free, no sign-up needed.</p></div></div>
<section class="panel" id="uploadPanel">
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Add HEIC photos">
<p style="font-size:2rem;margin:0">📸</p>
<p><b>Drop your HEIC photos here</b> or click to pick them</p>
<p class="hc-note">.heic and .heif files from iPhones and iPads. As many as you like.</p>
<input type="file" id="fileInput" accept=".heic,.heif" multiple hidden>
</div>
<div class="controls">
<div><label for="fmtSel">Turn them into</label><select id="fmtSel">
<option value="jpg"<?= $bbHeicMode === 'jpg' ? ' selected' : '' ?>>JPG, opens everywhere</option>
<option value="png"<?= $bbHeicMode === 'png' ? ' selected' : '' ?>>PNG, keeps every pixel</option>
<option value="pdf"<?= $bbHeicMode === 'pdf' ? ' selected' : '' ?>>PDF, one file for the set</option>
</select></div>
<div id="qualityWrap"><label for="qualityRange">JPG quality: <span id="qualityVal">92%</span></label><input type="range" id="qualityRange" min="70" max="100" step="1" value="92"></div>
</div>
<div id="fileList"></div>
<div class="btnrow">
<button type="button" class="button" id="downloadAllBtn" disabled>Download all as ZIP</button>
<button type="button" class="mini-btn" id="clearBtn">Clear the list</button>
</div>
<p class="hc-status" id="statusLine" role="status"></p>
<p class="hc-note">Conversion runs on your device. Big batches of large photos can take a moment each; the list shows every photo as it finishes.</p>
</section>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2>Questions, answered</h2>
<details><summary>Do I need an account?</summary><p>No. This converter is free, uses no actions, and needs no sign-up. Drop your photos in and download the converted files.</p></details>
<details><summary>Do my photos upload anywhere?</summary><p>No. Conversion happens right in your browser, on your device. Your photos are never sent to Bum Bum or anyone else.</p></details>
<details><summary>Why do my iPhone photos end in .heic?</summary><p>HEIC is the photo format iPhones have saved in since iOS 11. It keeps files small on the phone, but many websites, upload forms, and Windows PCs still cannot open it. JPG and PNG open everywhere.</p></details>
<details><summary>Will the quality change?</summary><p>PNG keeps every pixel and makes a bigger file. JPG lets you pick the quality, and the default setting keeps photos looking the same while opening anywhere. PDF holds the same JPG quality, one photo per page.</p></details>
<details><summary>Can I convert a batch of photos?</summary><p>Yes. Drop in as many HEIC photos as you like. Download each converted photo on its own, grab them all in one ZIP, or pick PDF and get the whole set as a single file.</p></details>
<details><summary>What about Live Photos?</summary><p>Only the still photo converts. A Live Photo also carries a short video clip, and that part stays a separate file on your phone.</p></details>
</section>
<section class="panel" aria-label="Guide">
<h2><?= htmlspecialchars($bbName) ?>, the short guide</h2>
<p>HEIC is a fine format on the phone and a headache everywhere else. Product uploads, application forms, shared drives, and most Windows PCs want JPG or PNG. This converter bridges that gap on your own device, so a photo of a product, a receipt, or a whiteboard full of plans becomes a file the rest of the world accepts.</p>
<p>Picking the output: JPG is the safe default for sharing and uploading, with a quality slider if file size matters. PNG suits screenshots and graphics you plan to edit again, since nothing is thrown away. PDF gathers a whole set, a site visit, a stack of receipts, a photo walkthrough, into one tidy file you can email.</p>
<p>Working through this converter's family: <a href="/tools/heic-to-jpg/">HEIC to JPG</a>, <a href="/tools/heic-to-png/">HEIC to PNG</a>, and <a href="/tools/heic-to-pdf/">HEIC to PDF</a> are the same tool wearing the outfit you searched for. Your photos stay on your device in all three.</p>
</section>
<?php $bbTool = 'heic-to-jpg'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
</main><?php require dirname(__DIR__, 2) . '/includes/site-footer.php'; ?>
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
  b.textContent=fmt()==="pdf"?"Download as one PDF":"Download all as ZIP";
  b.disabled=!done.length;
  $("qualityWrap").style.display=fmt()==="png"?"none":"";
}
function convertItem(item){
  item.status="working";item.stEl.textContent="Converting...";item.dlEl.style.display="none";
  var f=fmt();var q=parseInt($("qualityRange").value,10)/100;
  var job=(window.heic2any?window.heic2any({blob:item.file,toType:f==="png"?"image/png":"image/jpeg",quality:f==="png"?undefined:q}):Promise.reject(new Error("decoder missing")));
  return Promise.resolve(job).then(function(out){
    var blob=Array.isArray(out)?out[0]:out;
    item.blob=blob;item.status="done";
    item.url=URL.createObjectURL(blob);
    item.thumbEl.src=item.url;
    item.stEl.textContent="Done · "+fmtSize(blob.size);
    if(f!=="pdf"){item.dlEl.href=item.url;item.dlEl.download=outName(item.name);item.dlEl.style.display="";}
    refreshAllBtn();
  }).catch(function(){
    item.status="error";item.stEl.textContent="Could not read this photo. It may not be a real HEIC file.";item.stEl.className="st err";
    refreshAllBtn();
  });
}
function enqueue(item){chain=chain.then(function(){return convertItem(item);});}
function addFiles(list){
  var skipped=0;
  Array.prototype.forEach.call(list,function(file){
    if(!/\.hei[cf]$/i.test(file.name)&&!/hei[cf]/i.test(file.type||"")){skipped++;return;}
    var row=document.createElement("div");row.className="file-row";
    row.innerHTML='<img class="thumb" alt=""><div class="grow"><div><b></b></div><div class="meta"></div></div><span class="st">Waiting</span><a class="dl" style="display:none">Download</a>';
    row.querySelector("b").textContent=file.name;
    row.querySelector(".meta").textContent=fmtSize(file.size);
    $("fileList").appendChild(row);
    var item={file:file,name:file.name,status:"waiting",blob:null,url:null,
      thumbEl:row.querySelector(".thumb"),stEl:row.querySelector(".st"),dlEl:row.querySelector(".dl")};
    items.push(item);enqueue(item);
  });
  if(skipped)setStatus(skipped+" file"+(skipped>1?"s were":" was")+" skipped: only .heic or .heif photos work here.",true);
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
  items.forEach(function(i){if(i.url)URL.revokeObjectURL(i.url);i.blob=null;i.url=null;i.status="waiting";i.stEl.textContent="Waiting";i.stEl.className="st";i.dlEl.style.display="none";});
  items.forEach(enqueue);refreshAllBtn();
}
$("clearBtn").addEventListener("click",function(){
  items.forEach(function(i){if(i.url)URL.revokeObjectURL(i.url);});
  items=[];$("fileList").textContent="";setStatus("");refreshAllBtn();
});
$("downloadAllBtn").addEventListener("click",async function(){
  var done=items.filter(function(i){return i.status==="done";});
  if(!done.length)return;
  var btn=$("downloadAllBtn");btn.disabled=true;var old=btn.textContent;btn.textContent="Packing...";
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
      saveBlob(new Blob([pdfBytes],{type:"application/pdf"}),"heic-photos.pdf");
      setStatus("Your PDF is downloading: "+done.length+" photo"+(done.length>1?"s":"")+", one per page.");
    }else{
      var zip=new window.JSZip();var seen={};
      done.forEach(function(i){var n=outName(i.name);if(seen[n]){seen[n]++;n=n.replace(/(\.[^.]+)$/," ("+seen[n]+")$1");}else seen[n]=1;zip.file(n,i.blob);});
      var zb=await zip.generateAsync({type:"blob"});
      saveBlob(zb,"heic-photos.zip");
      setStatus("Your ZIP is downloading with "+done.length+" converted photo"+(done.length>1?"s":"")+".");
    }
  }catch(e){setStatus("Packing the download failed. You can still download each photo from its row.",true);}
  btn.disabled=false;btn.textContent=old;
});
function saveBlob(blob,name){var a=document.createElement("a");a.href=URL.createObjectURL(blob);a.download=name;document.body.appendChild(a);a.click();a.remove();setTimeout(function(){URL.revokeObjectURL(a.href);},4000);}
refreshAllBtn();
})();
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261030r1"></script></body></html>
