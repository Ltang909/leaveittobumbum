<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Free Background Remover | Remove a Photo Background in Your Browser | Leave It to Bum Bum</title>
<meta name="description" content="Free background remover that runs in your browser. Drop in a JPG, PNG, or WebP photo and download it with the background removed, as a transparent PNG. Your photo is processed on your device and never uploaded. No sign-up, no watermark.">
<meta property="og:title" content="Free Background Remover | Remove a Photo Background in Your Browser | Leave It to Bum Bum">
<meta property="og:description" content="Free background remover that runs in your browser. Drop in a photo and download a transparent PNG with the background gone. Your photo never uploads. No sign-up, no watermark.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/background-remover/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/background-remover.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Free Background Remover | Leave It to Bum Bum">
<meta name="twitter:description" content="Free background remover that runs in your browser. Drop in a photo and download a transparent PNG. Your photo never uploads. No sign-up, no watermark.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/background-remover.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/tools/background-remover/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Background Remover",
  "applicationCategory": "MultimediaApplication",
  "operatingSystem": "Web",
  "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" },
  "description": "Free background remover that runs in your browser. Drop in a JPG, PNG, or WebP photo and the background is removed on your device, ready to download as a transparent PNG. Your photo is never uploaded. No sign-up, no watermark.",
  "url": "https://leaveittobumbum.com/tools/background-remover/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    { "@type": "Question", "name": "Do I need an account?", "acceptedAnswer": { "@type": "Answer", "text": "No. The remover is free and needs no sign-up. Drop in a photo and download the cutout." } },
    { "@type": "Question", "name": "Does my photo upload anywhere?", "acceptedAnswer": { "@type": "Answer", "text": "No. The background is removed right in your browser, on your device. Your photo is never sent to Bum Bum or anyone else. The only thing that downloads is the removal model itself, the first time you use the tool." } },
    { "@type": "Question", "name": "Why did my first photo take longer?", "acceptedAnswer": { "@type": "Answer", "text": "The removal model is about 44 MB and downloads once, on your first visit. After that, photos are processed right away, and most browsers keep the model cached for your next visit." } },
    { "@type": "Question", "name": "What format comes out?", "acceptedAnswer": { "@type": "Answer", "text": "A transparent PNG at the same size as the photo you put in, so it drops cleanly onto listings, designs, and documents." } },
    { "@type": "Question", "name": "What photos work best?", "acceptedAnswer": { "@type": "Answer", "text": "Photos with a clear subject and some contrast between the subject and the background: product shots, portraits, pets, and logos. Fur and hair come out well; fine flyaway strands against a busy background are the hardest case for any remover." } },
    { "@type": "Question", "name": "What does it cost?", "acceptedAnswer": { "@type": "Answer", "text": "Nothing. Removing backgrounds is free, as many times as you like, and downloads carry no watermark." } }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Free Background Remover | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Background Remover","item":"https://leaveittobumbum.com/tools/background-remover/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use the Background Remover","description":"Remove the background from a photo in your browser and download a transparent PNG, without uploading the photo anywhere.","step":[{"@type":"HowToStep","position":1,"name":"Drop in a JPG, PNG, or WebP photo, or tap to pick one."},{"@type":"HowToStep","position":2,"name":"On your first visit, the removal model downloads once. It runs on your device."},{"@type":"HowToStep","position":3,"name":"Check the before and after. The after view sits on a checkerboard so you can see the transparent parts."},{"@type":"HowToStep","position":4,"name":"Download the transparent PNG, or start over with another photo."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.br-drop{border:2.5px dashed var(--line);border-radius:14px;background:#fffdf8;padding:34px 20px;text-align:center;cursor:pointer;margin-top:20px;transition:border-color .15s,background .15s}
.br-drop:hover{border-color:var(--ink)}
.br-drop.over{border-color:var(--ink);background:#fff4e0}
.br-drop .big{font-weight:900;font-size:20px;margin:0 0 6px}
.br-drop .small{font-size:14px;opacity:.7;margin:0}
.br-bar{height:12px;border:2px solid var(--ink);border-radius:8px;overflow:hidden;background:#fff;margin-top:14px}
.br-bar i{display:block;height:100%;width:0;background:var(--accent,#b3541e);transition:width .2s}
.br-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:20px;align-items:start}
.br-pane{background:#fff;border:2px solid var(--line);border-radius:14px;padding:14px}
.br-pane h2{margin:0 0 10px;font-size:16px}
.br-pane img{width:100%;height:auto;display:block;border-radius:6px}
.br-check{background:repeating-conic-gradient(#d8d8d8 0% 25%, #ffffff 0% 50%) 0 0/28px 28px;border:1px solid #e2d7bf;border-radius:6px}
.br-check img{border-radius:6px}
.br-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:16px}
.br-actions .button{min-height:48px}
.br-actions .button[disabled]{opacity:.45;cursor:not-allowed}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.hint{font-size:13px;opacity:.7;margin:10px 0 0}
.bb-hide{display:none!important}
.hc-status{font-size:14px;font-weight:700;margin-top:12px;min-height:20px}
.hc-status.error{color:#b3261e}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media(max-width:900px){.br-grid{grid-template-columns:1fr}}
</style></head>
<body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Background Remover"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/background-remover.png" alt="Background Remover"><div><h1>Free Background Remover</h1><p class="lede bb-desc">Drop in a photo, the background comes off in your browser, and your photo never uploads.</p></div></div>
<div class="br-drop" id="dropzone" role="button" tabindex="0" aria-label="Drop a photo here or tap to pick one">
<p class="big">Drop a photo here, or tap to pick one</p>
<p class="small">JPG, PNG, or WebP, up to 25 MB. Processed on your device. Nothing uploads.</p>
<input type="file" id="fileInput" accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp" class="bb-hide">
<div class="br-bar bb-hide" id="modelBarWrap"><i id="modelBar"></i></div>
<p class="hc-status" id="statusLine" role="status"></p>
</div>
<div class="br-grid bb-hide" id="resultGrid">
<div class="br-pane"><h2>Before</h2><img id="beforeImg" alt="Your photo before background removal"></div>
<div class="br-pane"><h2>After</h2><div class="br-check"><img id="afterImg" alt="Your photo with the background removed"></div></div>
</div>
<div class="br-actions bb-hide" id="actionRow">
<button type="button" class="button" id="downloadBtn">Download PNG</button>
<button type="button" class="button secondary" id="againBtn">Remove another photo</button>
</div>
<p class="hint">The checkerboard behind the After photo is not part of the picture. It marks the transparent parts, so you can see exactly what was removed. Your download is a transparent PNG at the full size of the photo you put in. On your first visit the removal model downloads once (about 44 MB); after that, photos are processed right away. The model runs on your device, so your photos stay yours: nothing is uploaded, stored, or sent to Bum Bum.</p>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2>Questions, answered</h2>
<details><summary>Do I need an account?</summary><p>No. The remover is free and needs no sign-up. Drop in a photo and download the cutout.</p></details>
<details><summary>Does my photo upload anywhere?</summary><p>No. The background is removed right in your browser, on your device. Your photo is never sent to Bum Bum or anyone else. The only thing that downloads is the removal model itself, the first time you use the tool.</p></details>
<details><summary>Why did my first photo take longer?</summary><p>The removal model is about 44 MB and downloads once, on your first visit. After that, photos are processed right away, and most browsers keep the model cached for your next visit.</p></details>
<details><summary>What format comes out?</summary><p>A transparent PNG at the same size as the photo you put in, so it drops cleanly onto listings, designs, and documents.</p></details>
<details><summary>What photos work best?</summary><p>Photos with a clear subject and some contrast between the subject and the background: product shots, portraits, pets, and logos. Fur and hair come out well; fine flyaway strands against a busy background are the hardest case for any remover.</p></details>
<details><summary>What does it cost?</summary><p>Nothing. Removing backgrounds is free, as many times as you like, and downloads carry no watermark.</p></details>
</section>
<section class="panel" aria-label="Guide">
<h2>Background removal, the short guide</h2>
<p>A cutout is the photo with everything else gone: the product, the pet, the person, floating on transparency. That one file does a lot of jobs. It drops onto a marketplace listing with a clean white backdrop, onto a profile page, a sticker sheet, a presentation, or a social post, and it looks deliberate everywhere it lands.</p>
<p>For the cleanest edge, start with a photo where the subject stands out from what is behind it. A product on a plain table, a pet in good light, a portrait against a wall: the remover reads those easily. Busy backgrounds still work; they just ask more of the edge, and fine hair against a busy backdrop is where every remover, this one included, earns its keep.</p>
<p>What to do with the PNG: put it straight into your design as is, run it through the <a href="/tools/image-converter/">Image Converter</a> if a listing wants a JPG on white instead, or trim the empty space around the subject with the <a href="/tools/image-cropper/">Image Cropper</a> so the subject fills the frame. And because the work happens on your device, the family photos and product shots you process here never sit on anyone else's server.</p>
</section>
<?php $bbTool = 'background-remover'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
</main><?php require dirname(__DIR__, 2) . '/includes/site-footer.php'; ?>
<script src="/tools/background-remover/vendor/ort.min.js"></script>
<script>
(function(){
"use strict";
var $=function(id){return document.getElementById(id);};
var VENDOR="/tools/background-remover/vendor/";
var MODEL_BYTES=44173029;
var session=null,sessionPromise=null,resultBlob=null,resultName="background-removed.png";
function setStatus(msg,isErr){var s=$("statusLine");s.textContent=msg||"";s.className="hc-status"+(isErr?" error":"");}
if(window.ort){
  ort.env.wasm.wasmPaths={
    "ort-wasm-simd-threaded.wasm":VENDOR+"ort-wasm-simd-threaded.wasm",
    "ort-wasm-simd-threaded.mjs":VENDOR+"ort-wasm-simd-threaded.js"
  };
  ort.env.wasm.numThreads=1;
}
function loadModel(){
  if(sessionPromise)return sessionPromise;
  sessionPromise=(async function(){
    setStatus("Downloading the removal model, first visit only. Your photo stays on your device.");
    $("modelBarWrap").classList.remove("bb-hide");
    var resp=await fetch(VENDOR+"model.onnx");
    if(!resp.ok)throw new Error("model download failed");
    var total=parseInt(resp.headers.get("content-length")||MODEL_BYTES,10)||MODEL_BYTES;
    var reader=resp.body.getReader();
    var chunks=[],got=0;
    for(;;){
      var step=await reader.read();
      if(step.done)break;
      chunks.push(step.value);got+=step.value.length;
      $("modelBar").style.width=Math.min(99,Math.round(got/total*100))+"%";
      setStatus("Downloading the removal model, first visit only: "+(got/1048576).toFixed(1)+" MB of "+(total/1048576).toFixed(1)+" MB.");
    }
    var buf=new Uint8Array(got),off=0;
    chunks.forEach(function(c){buf.set(c,off);off+=c.length;});
    $("modelBar").style.width="100%";
    setStatus("Preparing the removal model.");
    session=await ort.InferenceSession.create(buf.buffer,{executionProviders:["wasm"]});
    $("modelBarWrap").classList.add("bb-hide");
    return session;
  })();
  sessionPromise.catch(function(){sessionPromise=null;});
  return sessionPromise;
}
function preprocess(bitmap){
  var S=320;
  var c=document.createElement("canvas");c.width=S;c.height=S;
  var x=c.getContext("2d",{willReadFrequently:true});
  x.drawImage(bitmap,0,0,S,S);
  var d=x.getImageData(0,0,S,S).data;
  var max=0,i;
  for(i=0;i<d.length;i+=4){
    if(d[i]>max)max=d[i];
    if(d[i+1]>max)max=d[i+1];
    if(d[i+2]>max)max=d[i+2];
  }
  if(!max)max=1;
  var mean=[0.485,0.456,0.406],std=[0.229,0.224,0.225];
  var px=S*S,out=new Float32Array(3*px),p,ch;
  for(p=0;p<px;p++){
    for(ch=0;ch<3;ch++){
      out[ch*px+p]=(d[p*4+ch]/max-mean[ch])/std[ch];
    }
  }
  return new ort.Tensor("float32",out,[1,3,S,S]);
}
function buildCutout(bitmap,maskData){
  var S=320,i,mn=Infinity,mx=-Infinity;
  for(i=0;i<maskData.length;i++){if(maskData[i]<mn)mn=maskData[i];if(maskData[i]>mx)mx=maskData[i];}
  var range=(mx-mn)||1;
  var small=document.createElement("canvas");small.width=S;small.height=S;
  var sx=small.getContext("2d");
  var img=sx.createImageData(S,S);
  for(i=0;i<maskData.length;i++){
    var v=(maskData[i]-mn)/range;
    v=v<0?0:(v>1?1:v);
    img.data[i*4]=255;img.data[i*4+1]=255;img.data[i*4+2]=255;
    img.data[i*4+3]=Math.round(v*255);
  }
  sx.putImageData(img,0,0);
  var W=bitmap.width,H=bitmap.height;
  var maskFull=document.createElement("canvas");maskFull.width=W;maskFull.height=H;
  var mf=maskFull.getContext("2d");
  mf.imageSmoothingEnabled=true;mf.imageSmoothingQuality="high";
  mf.drawImage(small,0,0,W,H);
  var out=document.createElement("canvas");out.width=W;out.height=H;
  var ox=out.getContext("2d");
  ox.drawImage(bitmap,0,0);
  ox.globalCompositeOperation="destination-in";
  ox.drawImage(maskFull,0,0);
  ox.globalCompositeOperation="source-over";
  return out;
}
function saveBlob(blob,name){
  var a=document.createElement("a");
  a.href=URL.createObjectURL(blob);a.download=name;
  document.body.appendChild(a);a.click();a.remove();
  setTimeout(function(){URL.revokeObjectURL(a.href);},4000);
}
function dropStrip(){if(window.BBDropStrip)window.BBDropStrip.show('background-remover');}
async function bitmapFrom(file){
  if(window.createImageBitmap){
    try{return await createImageBitmap(file,{imageOrientation:"from-image"});}
    catch(e){try{return await createImageBitmap(file);}catch(e2){}}
  }
  return new Promise(function(res,rej){
    var url=URL.createObjectURL(file);
    var img=new Image();
    img.onload=function(){res(img);};
    img.onerror=function(){URL.revokeObjectURL(url);rej(new Error("image read failed"));};
    img.src=url;
  });
}
var busy=false;
async function handleFile(file){
  if(busy||!file)return;
  if(!/^image\/(jpeg|png|webp)$/.test(file.type)&&!/\.(jpe?g|png|webp)$/i.test(file.name)){
    setStatus("That file is not a JPG, PNG, or WebP photo. Pick one of those and the remover can work on it.",true);
    return;
  }
  if(file.size>25*1024*1024){
    setStatus("That photo is over 25 MB. A smaller copy will process happily; most phones can export one.",true);
    return;
  }
  busy=true;
  $("downloadBtn").disabled=true;
  try{
    var bitmap=await bitmapFrom(file);
    $("beforeImg").src=URL.createObjectURL(file);
    $("resultGrid").classList.remove("bb-hide");
    var t0=performance.now();
    var sess=await loadModel();
    setStatus("Removing the background. This happens on your device.");
    var tensor=preprocess(bitmap);
    var feeds={};feeds[sess.inputNames[0]]=tensor;
    var out=await sess.run(feeds);
    var mask=out[sess.outputNames[0]].data;
    var canvas=buildCutout(bitmap,mask);
    resultBlob=await new Promise(function(res){canvas.toBlob(res,"image/png");});
    if(!resultBlob)throw new Error("png failed");
    var secs=((performance.now()-t0)/1000).toFixed(1);
    $("afterImg").src=URL.createObjectURL(resultBlob);
    var base=(file.name||"photo").replace(/\.[^.]+$/,"").replace(/[^a-z0-9-_]+/gi,"-").replace(/^-+|-+$/g,"")||"photo";
    resultName=base+"-background-removed.png";
    $("actionRow").classList.remove("bb-hide");
    $("downloadBtn").disabled=false;
    setStatus("Done in "+secs+" seconds. The background is off; download the transparent PNG when you are ready.");
  }catch(e){
    setStatus("The removal could not finish in this browser. A different photo, or a desktop browser, is worth a try.",true);
  }
  busy=false;
}
var dz=$("dropzone"),fi=$("fileInput");
dz.addEventListener("click",function(){fi.click();});
dz.addEventListener("keydown",function(e){if(e.key==="Enter"||e.key===" "){e.preventDefault();fi.click();}});
fi.addEventListener("change",function(){if(fi.files&&fi.files[0])handleFile(fi.files[0]);fi.value="";});
["dragenter","dragover"].forEach(function(ev){dz.addEventListener(ev,function(e){e.preventDefault();dz.classList.add("over");});});
["dragleave","drop"].forEach(function(ev){dz.addEventListener(ev,function(e){e.preventDefault();dz.classList.remove("over");});});
dz.addEventListener("drop",function(e){
  var f=e.dataTransfer&&e.dataTransfer.files&&e.dataTransfer.files[0];
  if(f)handleFile(f);
});
$("downloadBtn").addEventListener("click",function(){
  if(!resultBlob)return;
  saveBlob(resultBlob,resultName);
  setStatus("Your transparent PNG is downloading: "+resultName+".");
  dropStrip();
});
$("againBtn").addEventListener("click",function(){
  resultBlob=null;
  $("resultGrid").classList.add("bb-hide");
  $("actionRow").classList.add("bb-hide");
  setStatus("");
  fi.click();
});
})();
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
