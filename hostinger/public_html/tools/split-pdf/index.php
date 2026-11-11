<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Split PDF | Pull Pages out of a PDF in Your Browser | Leave It to Bum Bum</title>
<meta name="description" content="Free PDF splitter that runs in your browser. Pull pages out in groups, each group as its own PDF, or split every page into its own file. No sign-up, and your PDF never leaves your device.">
<meta property="og:title" content="Split PDF | Pull Pages out of a PDF in Your Browser | Leave It to Bum Bum">
<meta property="og:description" content="Free PDF splitter that runs in your browser. Pull pages out in groups, each group as its own PDF, or split every page into its own file. No sign-up, and your PDF never leaves your device.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/split-pdf/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/split-pdf.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Split PDF | Pull Pages out of a PDF in Your Browser | Leave It to Bum Bum">
<meta name="twitter:description" content="Free PDF splitter that runs in your browser. Pull pages out in groups, each group as its own PDF, or split every page into its own file. No sign-up, and your PDF never leaves your device.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/split-pdf.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/tools/split-pdf/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Split PDF",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" },
  "description": "Free PDF splitter that runs in your browser. Pull pages out in groups, each group as its own PDF, or split every page into its own file. No sign-up, and your PDF never leaves your device.",
  "url": "https://leaveittobumbum.com/tools/split-pdf/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    { "@type": "Question", "name": "Do I need an account?", "acceptedAnswer": { "@type": "Answer", "text": "No. This splitter is free, uses no actions, and needs no sign-up. Drop your PDF in and download the pieces." } },
    { "@type": "Question", "name": "Does my PDF upload anywhere?", "acceptedAnswer": { "@type": "Answer", "text": "No. Splitting happens right in your browser, on your device. Your PDF is never sent to Bum Bum or anyone else." } },
    { "@type": "Question", "name": "How do page ranges work?", "acceptedAnswer": { "@type": "Answer", "text": "Each group you type becomes its own PDF. Typing 1-2, 3 gives you two files: one with pages 1 and 2, one with page 3. When there is more than one group, the files arrive together in a ZIP. A single group downloads as a plain PDF." } },
    { "@type": "Question", "name": "What does splitting every page give me?", "acceptedAnswer": { "@type": "Answer", "text": "One PDF per page, gathered into a single ZIP download. A 12-page statement becomes 12 small PDFs named by page number." } },
    { "@type": "Question", "name": "Can it open a password-protected PDF?", "acceptedAnswer": { "@type": "Answer", "text": "No. A locked PDF has to be unlocked by its owner first. If you know the password, open the file in a PDF reader, print or save it without protection, and split that copy." } },
    { "@type": "Question", "name": "Will the pages lose quality?", "acceptedAnswer": { "@type": "Answer", "text": "No. Pages are copied exactly as they are, with the same text, images, and layout. Nothing is re-rendered or compressed." } }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Split PDF | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Split PDF","item":"https://leaveittobumbum.com/tools/split-pdf/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use Split PDF","description":"Pull pages out of a PDF in your browser.","step":[{"@type":"HowToStep","position":1,"name":"Drop your PDF into the splitter."},{"@type":"HowToStep","position":2,"name":"Choose a page range, or split every page into its own file."},{"@type":"HowToStep","position":3,"name":"Bum Bum splits it right in your browser. Your PDF never leaves your device."},{"@type":"HowToStep","position":4,"name":"Download the new PDF, or the ZIP of single pages."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
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
<body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Split PDF"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/split-pdf.png" alt="Split PDF"><div><h1>Free PDF Splitter</h1><p class="lede bb-desc">Keep the pages you need. Pull a range out of a PDF, or split it page by page, right in your browser.</p></div></div>
<section class="panel" id="uploadPanel">
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Add a PDF">
<p style="font-size:2rem;margin:0">📄</p>
<p><b>Drop your PDF here</b> or click to pick it</p>
<p class="hc-note">One PDF at a time. Bum Bum reads its page count, then you choose the split.</p>
<input type="file" id="fileInput" accept="application/pdf,.pdf" hidden>
</div>
<div id="fileList"></div>
<div id="splitControls" class="hidden">
<div class="mode-opts" role="radiogroup" aria-label="What do you want?">
<label class="mode-opt"><input type="radio" name="splitMode" value="range" checked><span><b>Pull out some pages</b><br><span class="mode-sub">A PDF for each group you type: 1-2 makes one file, 3 makes the next.</span></span></label>
<label class="mode-opt"><input type="radio" name="splitMode" value="each"><span><b>Split into single pages</b><br><span class="mode-sub">A ZIP download with one PDF for every page.</span></span></label>
</div>
<div class="range-row" id="rangeWrap"><label for="rangeInput">Which pages?</label><input type="text" id="rangeInput" placeholder="1-2, 3" size="22"> <span class="hc-note" id="rangeHint"></span></div>
<div class="btnrow">
<button type="button" class="button" id="splitBtn" disabled>Split my PDF</button>
<button type="button" class="mini-btn" id="clearBtn">Clear</button>
</div>
</div>
<p class="hc-status" id="statusLine" role="status"></p>
<p class="hc-note">Splitting runs on your device. Very large PDFs can take a moment to read.</p>
</section>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2>Questions, answered</h2>
<details><summary>Do I need an account?</summary><p>No. This splitter is free, uses no actions, and needs no sign-up. Drop your PDF in and download the pieces.</p></details>
<details><summary>Does my PDF upload anywhere?</summary><p>No. Splitting happens right in your browser, on your device. Your PDF is never sent to Bum Bum or anyone else.</p></details>
<details><summary>How do page ranges work?</summary><p>Each group you type becomes its own PDF. Typing 1-2, 3 gives you two files: one with pages 1 and 2, one with page 3. When there is more than one group, the files arrive together in a ZIP. A single group downloads as a plain PDF.</p></details>
<details><summary>What does splitting every page give me?</summary><p>One PDF per page, gathered into a single ZIP download. A 12-page statement becomes 12 small PDFs named by page number.</p></details>
<details><summary>Can it open a password-protected PDF?</summary><p>No. A locked PDF has to be unlocked by its owner first. If you know the password, open the file in a PDF reader, print or save it without protection, and split that copy.</p></details>
<details><summary>Will the pages lose quality?</summary><p>No. Pages are copied exactly as they are, with the same text, images, and layout. Nothing is re-rendered or compressed.</p></details>
</section>
<section class="panel" aria-label="Guide">
<h2>Split PDF, the short guide</h2>
<p>Most PDFs arrive as bundles: a contract with the signing page buried at the back, a bank statement covering a whole quarter, a report where only one section matters to the person you are sending it to. This splitter pulls out the pages that matter and leaves the rest behind, on your own device.</p>
<p>Choosing a split: groups suit pulling a document apart into its pieces, like the signed page as one file and the quote pages as another. Splitting every page suits sorting, where each page becomes its own file you can name, file, and forward one at a time.</p>
<p>Working through this splitter's family: <a href="/tools/pdf-merger/">PDF Merger</a> stitches PDFs together, <a href="/tools/jpg-to-pdf/">JPG to PDF</a> turns images into a fresh PDF, and <a href="/tools/pdf-compressor/">PDF Compressor</a> shrinks a PDF that is too big to email. Your files stay on your device in all of them.</p>
</section>
<?php $bbTool = 'split-pdf'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
</main><?php require dirname(__DIR__, 2) . '/includes/site-footer.php'; ?>
<script src="/tools/pdf-merger/vendor/pdf-lib.min.js"></script>
<script src="/tools/pdf-merger/vendor/jszip.min.js"></script>
<script>
(function(){
"use strict";
var $=function(id){return document.getElementById(id);};
var current=null; // {file, name, doc, count}
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
  if(group.length===1)return base+"-page-"+group[0]+".pdf";
  return base+"-pages-"+group[0]+"-"+group[group.length-1]+".pdf";
}
function groupLabel(group){
  if(group.length===1)return "page "+group[0];
  return "pages "+group[0]+"-"+group[group.length-1];
}
async function addFile(file){
  if(!/\.pdf$/i.test(file.name)&&!/pdf/i.test(file.type||"")){setStatus("That is not a PDF. Drop a file ending in .pdf.",true);return;}
  setStatus("Reading your PDF...");
  try{
    var bytes=await file.arrayBuffer();
    var doc=await window.PDFLib.PDFDocument.load(bytes,{ignoreEncryption:true});
    current={file:file,name:file.name,doc:doc,count:doc.getPageCount()};
    var list=$("fileList");list.textContent="";
    var row=document.createElement("div");row.className="file-row";
    row.innerHTML='<div class="grow"><div><b></b></div><div class="meta"></div></div><span class="st"></span>';
    row.querySelector("b").textContent=file.name;
    row.querySelector(".meta").textContent=fmtSize(file.size);
    row.querySelector(".st").textContent=current.count+" page"+(current.count===1?"":"s");
    list.appendChild(row);
    $("rangeHint").textContent="This PDF has "+current.count+" page"+(current.count===1?"":"s")+", numbered 1 to "+current.count+".";
    setStatus("Loaded: "+current.count+" page"+(current.count===1?"":"s")+".");
  }catch(e){
    current=null;$("fileList").textContent="";
    setStatus("Bum Bum could not read that PDF. It may be password-protected or damaged.",true);
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
$("clearBtn").addEventListener("click",function(){current=null;$("fileList").textContent="";$("rangeInput").value="";setStatus("");refresh();});
$("splitBtn").addEventListener("click",async function(){
  if(!current)return;
  var btn=$("splitBtn");btn.disabled=true;var old=btn.textContent;btn.textContent="Splitting...";
  var base=current.name.replace(/\.pdf$/i,"");
  try{
    var PDFLib=window.PDFLib;
    if(getMode()==="each"){
      var zip=new window.JSZip();
      var width=String(current.count).length;
      for(var i=0;i<current.count;i++){
        setStatus("Splitting page "+(i+1)+" of "+current.count+"...");
        var nd=await PDFLib.PDFDocument.create();
        var copied=await nd.copyPages(current.doc,[i]);
        nd.addPage(copied[0]);
        var b=await nd.save();
        var num=String(i+1);while(num.length<width)num="0"+num;
        zip.file(base+"-page-"+num+".pdf",b);
      }
      var zb=await zip.generateAsync({type:"blob"});
      saveBlob(zb,base+"-pages.zip");
      setStatus("Your ZIP is downloading: "+current.count+" PDF"+(current.count===1?"":"s")+", one page each.");
    }else{
      var groups=parseGroups($("rangeInput").value,current.count);
      if(!groups){setStatus("That does not work for a "+current.count+"-page PDF. Use pages between 1 and "+current.count+", like 1-2, 3.",true);btn.disabled=false;btn.textContent=old;refresh();return;}
      async function buildGroup(group){
        var nd=await PDFLib.PDFDocument.create();
        var idx=group.map(function(p){return p-1;});
        var cp=await nd.copyPages(current.doc,idx);
        cp.forEach(function(pg){nd.addPage(pg);});
        return nd.save();
      }
      if(groups.length===1){
        var out1=await buildGroup(groups[0]);
        saveBlob(new Blob([out1],{type:"application/pdf"}),groupName(base,groups[0]));
        setStatus("Your PDF is downloading: "+groupLabel(groups[0])+".");
      }else{
        var zip2=new window.JSZip();
        for(var gi=0;gi<groups.length;gi++){
          setStatus("Building file "+(gi+1)+" of "+groups.length+"...");
          zip2.file(groupName(base,groups[gi]),await buildGroup(groups[gi]));
        }
        var zb2=await zip2.generateAsync({type:"blob"});
        saveBlob(zb2,base+"-split.zip");
        setStatus("Your ZIP is downloading: "+groups.length+" PDFs ("+groups.map(groupLabel).join(", ")+").");
      }
    }
    if(window.BBDropStrip)window.BBDropStrip.show('split-pdf');
  }catch(e){setStatus("Splitting failed. The PDF may use features this browser tool cannot copy.",true);}
  btn.disabled=false;btn.textContent=old;refresh();
});
function saveBlob(blob,name){var a=document.createElement("a");a.href=URL.createObjectURL(blob);a.download=name;document.body.appendChild(a);a.click();a.remove();setTimeout(function(){URL.revokeObjectURL(a.href);},4000);}
refresh();
})();
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
