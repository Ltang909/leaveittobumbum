<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Free Digital Business Card Maker | A Card for Your Phone with a Scannable QR Code | Leave It to Bum Bum</title>
<meta name="description" content="Free digital business card maker that runs in your browser. Type your details, add a logo, pick a template and a color, and download a tall card made for phone screens as a PNG, JPG, or PDF. The QR code on the card saves your contact to any phone that scans it. No sign-up, no watermark, and your details never leave your device.">
<meta property="og:title" content="Free Digital Business Card Maker | A Card for Your Phone with a Scannable QR Code | Leave It to Bum Bum">
<meta property="og:description" content="Free digital business card maker that runs in your browser. Type your details, pick a template and a color, and download a tall phone card as a PNG, JPG, or PDF. Its QR code saves your contact to any phone that scans it. No sign-up, and your details never leave your device.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/digital-business-card-maker/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/digital-business-card-maker.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Free Digital Business Card Maker | Leave It to Bum Bum">
<meta name="twitter:description" content="Free digital business card maker that runs in your browser. Type your details, pick a template and a color, and download a tall phone card as a PNG, JPG, or PDF. No sign-up, and your details never leave your device.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/digital-business-card-maker.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/tools/digital-business-card-maker/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Digital Business Card Maker",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" },
  "description": "Free digital business card maker that runs in your browser. Type your details, add a logo, pick a template and a color, and download a tall phone-shaped card as a PNG, JPG, or PDF. The QR code on the card carries your contact details, so anyone who scans it can save you to their phone. No sign-up, no watermark, and your details never leave your device.",
  "url": "https://leaveittobumbum.com/tools/digital-business-card-maker/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    { "@type": "Question", "name": "Do I need an account?", "acceptedAnswer": { "@type": "Answer", "text": "No. The maker is free and needs no sign-up. Type your details and download the card." } },
    { "@type": "Question", "name": "Do my details upload anywhere?", "acceptedAnswer": { "@type": "Answer", "text": "No. The card is drawn right in your browser, on your device. Your details and your logo are never sent to Bum Bum or anyone else." } },
    { "@type": "Question", "name": "What does the QR code do?", "acceptedAnswer": { "@type": "Answer", "text": "The QR code carries a contact card (a vCard) built from your details. When someone points their phone camera at it, the phone offers to save you as a contact with your name, title, business, phone, email, website, LinkedIn, and tagline. It works in person off your screen, and it works from the PNG, JPG, or PDF you send or post." } },
    { "@type": "Question", "name": "What size is the card?", "acceptedAnswer": { "@type": "Answer", "text": "1080 by 1920 pixels, the tall shape of a phone screen, so it fills the screen when you show it and looks right when you send it. The PDF is a single page in the same tall shape." } },
    { "@type": "Question", "name": "What is the contact (.vcf) download?", "acceptedAnswer": { "@type": "Answer", "text": "It is your contact card as a small file. Send it by text, email, or message and the person who opens it can add you to their phone contacts in one tap, no scanning needed." } },
    { "@type": "Question", "name": "Can I add my logo?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Pick any image file and it is placed on the card. The file is read on your device and never uploaded. Square or wide logos both work; the maker fits them into the template's logo spot." } },
    { "@type": "Question", "name": "Can I print this card?", "acceptedAnswer": { "@type": "Answer", "text": "You can, but it is drawn tall for screens, not trimmed for paper. For a standard paper card, use the Business Card Maker instead: it designs a 3.5 by 2 inch card, front and back, as a print-ready PDF." } },
    { "@type": "Question", "name": "What does it cost?", "acceptedAnswer": { "@type": "Answer", "text": "Nothing. Designing and downloading are free, as many times as you like, and downloads carry no watermark." } }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Free Digital Business Card Maker | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Digital Business Card Maker","item":"https://leaveittobumbum.com/tools/digital-business-card-maker/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use the Digital Business Card Maker","description":"Design a tall digital business card with a scannable QR contact code in your browser and download it as a PNG, JPG, PDF, or contact file.","step":[{"@type":"HowToStep","position":1,"name":"Type your name, title, business, tagline, and contact details."},{"@type":"HowToStep","position":2,"name":"Add your logo if you have one. It is read on your device only."},{"@type":"HowToStep","position":3,"name":"Pick a template, a font, and an accent color. The preview updates as you type, QR code included."},{"@type":"HowToStep","position":4,"name":"Download the card as a PNG, JPG, or PDF, download the contact file (.vcf) on its own, or tap Share to send the card to your phone or by email."},{"@type":"HowToStep","position":5,"name":"Show the card on your phone or send the image. Anyone who scans the QR code saves your contact."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dc-grid{display:grid;grid-template-columns:1fr 430px;gap:16px;align-items:start;margin-top:20px}
.dc-card{background:#fff;border:2px solid var(--line);border-radius:14px;padding:16px}
.dc-card h2{margin:0 0 12px;font-size:18px}
.grp{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;opacity:.55;margin:18px 0 8px}
.grp:first-of-type{margin-top:4px}
.fld{margin-bottom:10px}
.fld label{display:block;font-weight:800;font-size:13px;margin:0 0 5px}
.fld input{width:100%;padding:10px 12px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff;color:var(--ink);margin:0}
.fld input:focus{border-color:var(--ink);outline:none}
.fld .note{font-size:12px;opacity:.65;margin:4px 0 0}
.seg{display:inline-flex;min-height:44px;border:2px solid var(--ink);border-radius:12px;overflow:hidden;background:#fff;flex-wrap:wrap}
.seg button{border:0;background:transparent;margin:0;padding:0 14px;font:inherit;font-weight:800;cursor:pointer;min-height:44px;display:flex;align-items:center;color:var(--ink)}
.seg button.on{background:var(--ink);color:#fff}
.swatch{width:36px;height:36px;border-radius:50%;border:2px solid var(--ink);cursor:pointer;padding:0;margin:0;flex:none}
.swatch.on{outline:3px solid var(--ink);outline-offset:2px}
input[type=color].pick{width:36px;height:36px;border:2px solid var(--ink);border-radius:50%;padding:2px;background:#fff;cursor:pointer;margin:0;flex:none}
.color-row{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.dc-preview-wrap{position:sticky;top:16px}
.dc-preview{background:#fffdf8;border:2px solid var(--ink);border-radius:14px;padding:16px;box-shadow:4px 4px 0 var(--ink)}
.dc-preview canvas{width:100%;height:auto;display:block;border:1px solid #e2d7bf;border-radius:6px;background:#fff}
.export-row{display:flex;gap:10px;flex-wrap:wrap;margin-top:14px}
.export-row .button{min-height:48px}
.export-row .button[disabled]{opacity:.45;cursor:not-allowed}
.share-wrap{position:relative}
.share-menu{position:absolute;left:0;bottom:calc(100% + 8px);background:#fff;border:2px solid var(--ink);border-radius:12px;box-shadow:4px 4px 0 var(--ink);padding:6px;min-width:220px;z-index:30}
.share-menu button{display:block;width:100%;text-align:left;background:transparent;border:0;padding:10px 12px;font:inherit;font-weight:700;cursor:pointer;border-radius:8px;color:var(--ink);margin:0;min-height:44px}
.share-menu button:hover{background:#fff4e0}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.hint{font-size:13px;opacity:.7;margin:10px 0 0}
.bb-hide{display:none!important}
.hc-status{font-size:13px;font-weight:700;margin-top:10px;min-height:18px}
.hc-status.error{color:#b3261e}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media(max-width:900px){.dc-grid{grid-template-columns:1fr}.dc-preview-wrap{position:static}}
</style></head>
<body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Digital Business Card Maker"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/digital-business-card-maker.png" alt="Digital Business Card Maker"><div><h1>Free Digital Business Card Maker</h1><p class="lede bb-desc">Make a tall card for your phone, with a QR code that saves your contact when scanned. Free.</p></div></div>
<div class="dc-grid">
<form class="dc-card" id="dcForm" autocomplete="off">
<h2>Your details</h2>
<p class="grp">On the card</p>
<div class="fld"><label for="fName">Full name</label><input id="fName" type="text" placeholder="Jane Appleseed" maxlength="60"></div>
<div class="fld"><label for="fTitle">Job title</label><input id="fTitle" type="text" placeholder="Founder and Dog Walker" maxlength="60"></div>
<div class="fld"><label for="fBusiness">Business name</label><input id="fBusiness" type="text" placeholder="Appleseed Walks" maxlength="60"></div>
<div class="fld"><label for="fTagline">Tagline</label><input id="fTagline" type="text" placeholder="Good work, done properly." maxlength="80"></div>
<p class="grp">Contact</p>
<div class="fld"><label for="fPhone">Phone</label><input id="fPhone" type="tel" placeholder="416-555-0134" maxlength="40"></div>
<div class="fld"><label for="fEmail">Email</label><input id="fEmail" type="email" placeholder="jane@appleseed.co" maxlength="60"></div>
<div class="fld"><label for="fWebsite">Website</label><input id="fWebsite" type="text" placeholder="appleseed.co" maxlength="60"></div>
<div class="fld"><label for="fLinkedin">LinkedIn profile</label><input id="fLinkedin" type="text" placeholder="linkedin.com/in/yourname" maxlength="80"></div>
<div class="fld"><label for="fLogo">Logo (optional)</label><input id="fLogo" type="file" accept="image/*"><p class="note">Read on your device only. Nothing uploads. Square or wide logos both fit.</p></div>
<p class="grp">Template</p>
<div class="seg" role="group" aria-label="Card template"><button type="button" data-tpl="classic" class="on">Classic</button><button type="button" data-tpl="bold">Bold</button><button type="button" data-tpl="topband">Top Band</button><button type="button" data-tpl="centered">Centered</button><button type="button" data-tpl="minimal">Minimal</button></div>
<p class="grp">Font</p>
<div class="seg" role="group" aria-label="Card font"><button type="button" data-font="serif" class="on" style="font-family:Fraunces,Georgia,serif">Serif</button><button type="button" data-font="sans" style="font-family:'Nunito Sans',Helvetica,Arial,sans-serif">Sans</button><button type="button" data-font="allserif" style="font-family:Fraunces,Georgia,serif">All serif</button></div>
<p class="grp">Accent color</p>
<div class="color-row"><button type="button" class="swatch on" data-color="#b3541e" style="background:#b3541e" aria-label="Rust"></button><button type="button" class="swatch" data-color="#2f6fed" style="background:#2f6fed" aria-label="Blue"></button><button type="button" class="swatch" data-color="#2f9e5f" style="background:#2f9e5f" aria-label="Green"></button><button type="button" class="swatch" data-color="#8e4ec6" style="background:#8e4ec6" aria-label="Purple"></button><button type="button" class="swatch" data-color="#1a1a1a" style="background:#1a1a1a" aria-label="Black"></button><input type="color" class="pick" id="customColor" value="#b3541e" aria-label="Custom color"></div>
</form>
<div class="dc-preview-wrap">
<h2 style="margin:0 0 12px;font-size:18px">Preview</h2>
<div class="dc-preview"><canvas id="cardCanvas" width="1080" height="1920" aria-label="Your digital business card preview"></canvas></div>
<div class="export-row"><button type="button" class="button" id="pngBtn">Download PNG</button><button type="button" class="button secondary" id="jpgBtn">Download JPG</button><button type="button" class="button secondary" id="pdfBtn">Download PDF</button><button type="button" class="button secondary" id="vcfBtn">Download contact (.vcf)</button><span class="share-wrap" id="shareWrap"><button type="button" class="button secondary" id="shareBtn" aria-haspopup="true" aria-expanded="false">Share</button><span class="share-menu bb-hide" id="shareMenu" role="menu"><button type="button" id="shareEmail">Email it</button><button type="button" id="shareCopy">Copy image</button></span></span></div>
<p class="hc-status" id="statusLine" role="status"></p>
<p class="hint bb-hide" id="nameHint">Add your name and the download buttons unlock. The card, the QR code, and the contact file all carry it.</p>
<p class="hint">The card is 1080 by 1920 pixels, the tall shape of a phone screen. Hold your phone up and the preview itself can be scanned; the QR code on it saves your contact to the other person's phone. Send the PNG or JPG when you are not in the room, and the code still works. Making the card on a computer? Tap Share to send it to your phone or by email in one go. Blank fields show as soft gray sample text in the preview, so fill in a field to replace its sample. Your details and logo are drawn on your device. Nothing is uploaded, stored, or sent to Bum Bum.</p>
</div>
</div>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2>Questions, answered</h2>
<details><summary>Do I need an account?</summary><p>No. The maker is free and needs no sign-up. Type your details and download the card.</p></details>
<details><summary>Do my details upload anywhere?</summary><p>No. The card is drawn right in your browser, on your device. Your details and your logo are never sent to Bum Bum or anyone else. The form remembers your text entries on this device for your next visit; your logo file is never kept.</p></details>
<details><summary>What does the QR code do?</summary><p>The QR code carries a contact card (a vCard) built from your details. When someone points their phone camera at it, the phone offers to save you as a contact with your name, title, business, phone, email, website, LinkedIn, and tagline. It works in person off your screen, and it works from the PNG, JPG, or PDF you send or post.</p></details>
<details><summary>What size is the card?</summary><p>1080 by 1920 pixels, the tall shape of a phone screen, so it fills the screen when you show it and looks right when you send it. The PDF is a single page in the same tall shape.</p></details>
<details><summary>What is the contact (.vcf) download?</summary><p>It is your contact card as a small file. Send it by text, email, or message and the person who opens it can add you to their phone contacts in one tap, no scanning needed.</p></details>
<details><summary>Can I add my logo?</summary><p>Yes. Pick any image file and it is placed on the card. The file is read on your device and never uploaded. Square or wide logos both work; the maker fits them into the template's logo spot.</p></details>
<details><summary>Can I print this card?</summary><p>You can, but it is drawn tall for screens, not trimmed for paper. For a standard paper card, use the <a href="/tools/business-card-maker/">Business Card Maker</a> instead: it designs a 3.5 by 2 inch card, front and back, as a print-ready PDF.</p></details>
<details><summary>What does it cost?</summary><p>Nothing. Designing and downloading are free, as many times as you like, and downloads carry no watermark.</p></details>
</section>
<section class="panel" aria-label="Guide">
<h2>Digital business cards, the short guide</h2>
<p>A paper card gets photographed, pocketed, and lost. A digital card skips all three: it lives on your phone as an image, fills the screen when you hold it up, and the person you just met scans it and has you in their contacts before you both walk away. No app for them to install, no account for you to keep.</p>
<p>What to put on it: your name, your title or trade in plain words, your business name, one phone number, one email, one website, your LinkedIn profile, and a short tagline if you have one. That is the whole list, and it is exactly what the QR code carries, so what they see is what gets saved. Leave a field blank and it simply stays off the card and out of the code.</p>
<p>Where it earns its keep: markets, trade shows, networking nights, and any counter where spelling your email out loud has already failed you once. Save the PNG to your photos, or set it as your lock screen during an event, and your card is one glance away. Send the JPG in a follow-up message and the new contact can scan it right off the thread. Want it on paper instead? The <a href="/tools/business-card-maker/">Business Card Maker</a> designs the classic card, front and back, as a print-ready PDF, and the two makers share templates and colors so your paper and phone cards match.</p>
</section>
<?php $bbTool = 'digital-business-card-maker'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
</main><?php require dirname(__DIR__, 2) . '/includes/site-footer.php'; ?>
<script src="/tools/pdf-merger/vendor/pdf-lib.min.js"></script>
<script src="/tools/purr-code/qrcode.min.js"></script>
<script>
(function(){
"use strict";
var $=function(id){return document.getElementById(id);};
var W=1080,H=1920,PAD=90,CW=W-PAD*2;
var SANS="'Nunito Sans',Helvetica,Arial,sans-serif";
var SERIF="Fraunces,Georgia,serif";
/* Font looks, built only from the two families this page already loads.
   serif: Fraunces name, Nunito text (the original look). sans: Nunito
   throughout. allserif: Fraunces for the name, title, and tagline too. */
var FONTS={serif:1,sans:1,allserif:1};
function dispF(){return state.font==="sans"?SANS:SERIF;}
function headF(){return state.font==="allserif"?SERIF:SANS;}
function ensureFonts(){
  if(!document.fonts||!document.fonts.load)return Promise.resolve();
  var specs=["700 60px Fraunces","900 60px 'Nunito Sans'","800 60px 'Nunito Sans'","700 60px 'Nunito Sans'","600 60px 'Nunito Sans'","400 60px 'Nunito Sans'"];
  return Promise.all(specs.map(function(s){return document.fonts.load(s);})).then(function(){return document.fonts.ready;}).catch(function(){});
}
var canvas=$("cardCanvas"),ctx=canvas.getContext("2d");
var state={tpl:"classic",color:"#b3541e",logo:null,font:"serif"};
var RE=false;
var FIELDS=["fName","fTitle","fBusiness","fTagline","fPhone","fEmail","fWebsite","fLinkedin"];
var SAMPLE={fName:"Your Name",fTitle:"Your Title",fBusiness:"Your Business",fTagline:"Good work, done properly.",fPhone:"416-555-0134",fEmail:"you@yourbusiness.com",fWebsite:"yourbusiness.com",fLinkedin:"linkedin.com/in/yourname"};
try{if(window.qrcode&&qrcode.stringToBytesFuncs&&qrcode.stringToBytesFuncs["UTF-8"])qrcode.stringToBytes=qrcode.stringToBytesFuncs["UTF-8"];}catch(e){}

function val(id){return ($(id).value||"").trim();}
function liNorm(raw){
  var s=(raw||"").trim();
  if(!s)return null;
  var handle="";
  /* Full or partial profile URL: keep only the /in/<handle> segment, so a
     pasted URL with trailing path segments, a query string, or a fragment
     still normalizes to the clean profile address. */
  var m=s.match(/linkedin\.com\/in\/([^\/?#\s]+)/i);
  if(m)handle=m[1];
  else{
    var t=s.replace(/^https?:\/\//i,"").replace(/^www\./i,"").replace(/^in\//i,"").replace(/[/?#].*$/,"").trim();
    if(t&&t.indexOf(".")<0&&t.indexOf(" ")<0)handle=t;
  }
  if(!handle)return null;
  try{handle=decodeURIComponent(handle);}catch(e){}
  handle=handle.replace(/[\s.,;:)\]]+$/,"");
  if(!handle)return null;
  return {full:"https://www.linkedin.com/in/"+handle,disp:"linkedin.com/in/"+handle};
}
function data(){
  var d={},ph={};
  FIELDS.forEach(function(id){
    var v=val(id);
    d[id]=v||SAMPLE[id];
    ph[id]=!v;
  });
  d.websiteDisp=d.fWebsite.replace(/^https?:\/\//i,"").replace(/\/+$/,"");
  var lin=liNorm(d.fLinkedin);
  d.liFull=lin?lin.full:"";
  d.liDisp=lin?lin.disp:"";
  return {d:d,ph:ph};
}
function vis(phFlag){return !RE||!phFlag;}
function setStatus(msg,isErr){var s=$("statusLine");s.textContent=msg||"";s.className="hc-status"+(isErr?" error":"");}
function saveBlob(blob,name){var a=document.createElement("a");a.href=URL.createObjectURL(blob);a.download=name;document.body.appendChild(a);a.click();a.remove();setTimeout(function(){URL.revokeObjectURL(a.href);},4000);}
function fit(text,maxW,base,min,weight,family){
  var s=base;
  ctx.font=weight+" "+s+"px "+family;
  while(s>min&&ctx.measureText(text).width>maxW){s-=2;ctx.font=weight+" "+s+"px "+family;}
  return s;
}
function spacing(v){try{ctx.letterSpacing=v;}catch(e){}}
function ink(color,ph){ctx.fillStyle=color;ctx.globalAlpha=ph?0.35:1;}
function drawLogo(x,y,w,h){
  if(!state.logo)return false;
  var img=state.logo,iw=img.naturalWidth,ih=img.naturalHeight;
  if(!iw||!ih)return false;
  var s=Math.min(w/iw,h/ih),dw=iw*s,dh=ih*s;
  ctx.drawImage(img,x+(w-dw)/2,y+(h-dh)/2,dw,dh);
  return true;
}
function roundRect(x,y,w,h,r){ctx.beginPath();ctx.moveTo(x+r,y);ctx.arcTo(x+w,y,x+w,y+h,r);ctx.arcTo(x+w,y+h,x,y+h,r);ctx.arcTo(x,y+h,x,y,r);ctx.arcTo(x,y,x+w,y,r);ctx.closePath();}
function monogram(cx,cy,r,text,bg,fg){
  ctx.fillStyle=bg;ctx.beginPath();ctx.arc(cx,cy,r,0,Math.PI*2);ctx.fill();
  ctx.fillStyle=fg;ctx.font="900 "+Math.round(r*1.05)+"px "+SANS;ctx.textAlign="center";ctx.textBaseline="middle";
  ctx.fillText((text||"B").charAt(0).toUpperCase(),cx,cy+r*0.05);
  ctx.textAlign="left";ctx.textBaseline="alphabetic";
}
function contacts(d,ph){
  var out=[];
  if(d.fPhone)out.push({t:d.fPhone,ph:ph.fPhone});
  if(d.fEmail)out.push({t:d.fEmail,ph:ph.fEmail});
  if(d.fWebsite)out.push({t:d.websiteDisp,ph:ph.fWebsite});
  if(d.liDisp)out.push({t:"LinkedIn: "+d.liDisp,ph:ph.fLinkedin});
  if(RE)out=out.filter(function(l){return !l.ph;});
  return out;
}
function drawLines(list,x,y0,step,size,color,align){
  ctx.textAlign=align||"left";
  list.forEach(function(line,i){
    ctx.font="600 "+size+"px "+SANS;
    ink(color,line.ph);
    ctx.fillText(line.t,x,y0+i*step);
  });
  ctx.globalAlpha=1;ctx.textAlign="left";
}
function wrapText(text,x,y,maxW,size,step,weight,family,align){
  ctx.font=weight+" "+size+"px "+family;
  var words=text.split(/\s+/),lines=[],cur="";
  words.forEach(function(w){var t=(cur+" "+w).trim();if(ctx.measureText(t).width>maxW&&cur){lines.push(cur);cur=w;}else{cur=t;}});
  if(cur)lines.push(cur);
  lines=lines.slice(0,2);
  ctx.textAlign=align||"left";
  lines.forEach(function(l,i){ctx.fillText(l,x,y+i*step);});
  ctx.textAlign="left";
  return lines.length;
}
function vcard(){
  var name=val("fName"),biz=val("fBusiness"),title=val("fTitle"),
      phone=val("fPhone"),email=val("fEmail"),web=val("fWebsite"),tag=val("fTagline"),
      li=val("fLinkedin");
  if(!name&&!biz&&!phone&&!email&&!web){
    name=SAMPLE.fName;biz=SAMPLE.fBusiness;title=SAMPLE.fTitle;
    phone=SAMPLE.fPhone;email=SAMPLE.fEmail;web=SAMPLE.fWebsite;tag=SAMPLE.fTagline;
    li=SAMPLE.fLinkedin;
  }
  function esc(s){return s.replace(/\\/g,"\\\\").replace(/\r?\n/g,"\\n").replace(/;/g,"\\;").replace(/,/g,"\\,");}
  var parts=name.split(/\s+/).filter(Boolean);
  var last=parts.length>1?parts[parts.length-1]:"";
  var first=parts.length?(parts.length>1?parts.slice(0,-1).join(" "):parts[0]):"";
  var L=["BEGIN:VCARD","VERSION:3.0","N:"+esc(last)+";"+esc(first)+";;;","FN:"+esc(name)];
  if(biz)L.push("ORG:"+esc(biz));
  if(title)L.push("TITLE:"+esc(title));
  if(phone)L.push("TEL;TYPE=CELL:"+esc(phone));
  if(email)L.push("EMAIL:"+esc(email));
  if(web){var u=web;if(!/^[a-zA-Z][a-zA-Z0-9+.-]*:/.test(u))u="https://"+u.replace(/^\/+/,"");L.push("URL:"+esc(u));}
  var lin=liNorm(li);
  /* X-SOCIALPROFILE is the field phone contact importers map to the
     contact's LinkedIn profile (iOS does). A second bare URL: line was
     dropped inconsistently by importers, so LinkedIn never rides as one;
     the website stays the single URL: entry. */
  if(lin)L.push("X-SOCIALPROFILE;TYPE=linkedin:"+esc(lin.full));
  if(tag)L.push("NOTE:"+esc(tag));
  L.push("END:VCARD");
  return L.join("\r\n");
}
function drawQR(x,y,size,fg){
  var qr;
  try{qr=qrcode(0,"M");qr.addData(vcard());qr.make();}
  catch(e){return false;}
  var n=qr.getModuleCount(),quiet=4,total=n+quiet*2;
  var m=Math.floor(size/total);
  if(m<1)return false;
  var drawn=m*total,ox=x+Math.round((size-drawn)/2),oy=y+Math.round((size-drawn)/2);
  ctx.fillStyle=fg;ctx.globalAlpha=1;
  for(var r=0;r<n;r++)for(var c=0;c<n;c++){
    if(qr.isDark(r,c))ctx.fillRect(ox+(c+quiet)*m,oy+(r+quiet)*m,m,m);
  }
  return true;
}
function qrBox(x,top,size,fg){
  var box=size+68;
  ctx.fillStyle="#ffffff";roundRect(x,top,box,box,28);ctx.fill();
  ctx.strokeStyle="#e2d7bf";ctx.lineWidth=2;ctx.stroke();
  if(!drawQR(x+34,top+34,size,fg)){
    ctx.fillStyle="#b3261e";ctx.font="700 30px "+SANS;ctx.textAlign="center";
    ctx.fillText("That contact is too long for a QR code.",x+box/2,top+box/2);
    ctx.textAlign="left";
  }
  return top+box;
}
function qrBlock(cx,top,size,fg){return qrBox(cx-(size+68)/2,top,size,fg);}
function placeQR(y,fg,capColor){
  var size=Math.min(430,Math.max(240,1740-y-68));
  var bottom=qrBlock(W/2,y,size,fg);
  if(bottom+56<=1905){
    ctx.fillStyle=capColor;ctx.globalAlpha=1;
    ctx.font="700 32px "+SANS;ctx.textAlign="center";
    ctx.fillText("Scan to save my contact",W/2,bottom+54);
    ctx.textAlign="left";
  }
  return bottom;
}

var draw={
classic:function(d,ph,ac){
  ctx.fillStyle="#ffffff";ctx.fillRect(0,0,W,H);
  if(vis(ph.fBusiness)){
    ink(ac,ph.fBusiness);spacing("3px");
    fit(d.fBusiness,state.logo?540:620,30,18,"800",SANS);
    ctx.fillText(d.fBusiness.toUpperCase(),PAD,142);
    spacing("0px");ctx.globalAlpha=1;
  }
  if(state.logo)drawLogo(W-PAD-315,84,315,177);
  var y=660;
  if(vis(ph.fName)){
    ink("#20242a",ph.fName);
    fit(d.fName,CW,118,46,"700",dispF());
    ctx.fillText(d.fName,PAD,y);
    ctx.globalAlpha=1;
  }
  y+=98;
  if(vis(ph.fTitle)){
    ink("#5a6068",ph.fTitle);
    fit(d.fTitle,CW,46,24,"600",headF());
    ctx.fillText(d.fTitle,PAD,y);
    ctx.globalAlpha=1;
  }
  y+=56;ctx.fillStyle=ac;ctx.fillRect(PAD,y,120,9);
  if(vis(ph.fTagline)){
    y+=96;
    ink("#5a6068",ph.fTagline);
    var n=wrapText(d.fTagline,PAD,y,CW,42,56,"600",headF(),"left");
    ctx.globalAlpha=1;
    y+=(n-1)*56;
  }
  var list=contacts(d,ph);
  if(list.length){
    y+=110;
    drawLines(list,PAD,y,64,40,"#33383e");
    y+=(list.length-1)*64;
  }
  placeQR(Math.max(y+130,1150),"#14171c","#5a6068");
},
bold:function(d,ph,ac){
  ctx.fillStyle=ac;ctx.fillRect(0,0,W,H);
  if(vis(ph.fBusiness)){
    ctx.fillStyle="#ffffff";ctx.globalAlpha=ph.fBusiness?0.5:0.9;spacing("3px");
    fit(d.fBusiness,620,30,18,"800",SANS);
    ctx.fillText(d.fBusiness.toUpperCase(),PAD,142);
    spacing("0px");ctx.globalAlpha=1;
  }
  if(state.logo){
    ctx.fillStyle="#ffffff";roundRect(W-PAD-276,84,276,276,20);ctx.fill();
    drawLogo(W-PAD-276+16,100,244,244);
  }
  var y=660;
  if(vis(ph.fName)){
    ink("#ffffff",ph.fName);
    fit(d.fName,CW,126,48,"900",dispF());
    ctx.fillText(d.fName,PAD,y);
    ctx.globalAlpha=1;
  }
  y+=100;
  if(vis(ph.fTitle)){
    ctx.fillStyle="#ffffff";ctx.globalAlpha=ph.fTitle?0.5:0.85;
    fit(d.fTitle,CW,46,24,"700",headF());
    ctx.fillText(d.fTitle,PAD,y);
    ctx.globalAlpha=1;
  }
  if(vis(ph.fTagline)){
    y+=100;
    ctx.fillStyle="#ffffff";ctx.globalAlpha=ph.fTagline?0.5:0.92;
    var n=wrapText(d.fTagline,PAD,y,CW,42,56,"600",headF(),"left");
    ctx.globalAlpha=1;
    y+=(n-1)*56;
  }
  var list=contacts(d,ph);
  if(list.length){
    y+=116;
    drawLines(list,PAD,y,68,40,"#ffffff");
    y+=(list.length-1)*68;
  }
  placeQR(Math.max(y+140,1150),"#14171c","#ffffff");
},
topband:function(d,ph,ac){
  ctx.fillStyle="#ffffff";ctx.fillRect(0,0,W,H);
  ctx.fillStyle=ac;ctx.fillRect(0,0,W,620);
  if(state.logo){
    ctx.fillStyle="#ffffff";roundRect(W/2-180,64,360,360,24);ctx.fill();
    drawLogo(W/2-164,80,328,328);
  }else{
    monogram(W/2,250,104,d.fBusiness||d.fName,"#ffffff",ac);
  }
  if(vis(ph.fBusiness)){
    ctx.fillStyle="#ffffff";ctx.globalAlpha=ph.fBusiness?0.55:1;spacing("2px");
    wrapText(d.fBusiness.toUpperCase(),W/2,520,780,30,42,"800",SANS,"center");
    spacing("0px");ctx.globalAlpha=1;
  }
  var y=836;
  if(vis(ph.fName)){
    ink("#20242a",ph.fName);ctx.textAlign="center";
    fit(d.fName,CW,106,44,"700",dispF());
    ctx.fillText(d.fName,W/2,y);
    ctx.globalAlpha=1;ctx.textAlign="left";
  }
  y+=100;
  if(vis(ph.fTitle)){
    ink(ac,ph.fTitle);ctx.textAlign="center";
    fit(d.fTitle,CW,44,24,"700",headF());
    ctx.fillText(d.fTitle,W/2,y);
    ctx.globalAlpha=1;ctx.textAlign="left";
  }
  y+=50;ctx.fillStyle=ac;ctx.fillRect(W/2-45,y,90,8);
  if(vis(ph.fTagline)){
    y+=98;
    ink("#5a6068",ph.fTagline);
    var n=wrapText(d.fTagline,W/2,y,CW,42,56,"600",headF(),"center");
    ctx.globalAlpha=1;
    y+=(n-1)*56;
  }
  var list=contacts(d,ph);
  if(list.length){
    y+=108;
    drawLines(list,W/2,y,62,38,"#33383e","center");
    y+=(list.length-1)*62;
  }
  placeQR(Math.max(y+120,1200),"#14171c","#5a6068");
},
centered:function(d,ph,ac){
  ctx.fillStyle="#ffffff";ctx.fillRect(0,0,W,H);
  if(state.logo){drawLogo(W/2-240,100,480,240);}
  else{monogram(W/2,196,86,d.fBusiness||d.fName,ac,"#ffffff");}
  var y=620;
  if(vis(ph.fName)){
    ink("#20242a",ph.fName);ctx.textAlign="center";
    fit(d.fName,CW,108,44,"700",dispF());
    ctx.fillText(d.fName,W/2,y);
    ctx.globalAlpha=1;ctx.textAlign="left";
  }
  y+=102;
  if(vis(ph.fTitle)){
    ink(ac,ph.fTitle);ctx.textAlign="center";
    fit(d.fTitle,CW,42,24,"700",headF());
    ctx.fillText(d.fTitle,W/2,y);
    ctx.globalAlpha=1;ctx.textAlign="left";
  }
  if(vis(ph.fBusiness)){
    y+=72;
    ink("#8a8f96",ph.fBusiness);spacing("3px");ctx.textAlign="center";
    fit(d.fBusiness,CW,26,16,"800",SANS);
    ctx.fillText(d.fBusiness.toUpperCase(),W/2,y);
    spacing("0px");ctx.globalAlpha=1;ctx.textAlign="left";
  }
  y+=58;ctx.fillStyle=ac;
  [-46,0,46].forEach(function(off){ctx.beginPath();ctx.arc(W/2+off,y,5,0,Math.PI*2);ctx.fill();});
  if(vis(ph.fTagline)){
    y+=96;
    ink("#5a6068",ph.fTagline);
    var n=wrapText(d.fTagline,W/2,y,CW,42,56,"600",headF(),"center");
    ctx.globalAlpha=1;
    y+=(n-1)*56;
  }
  var list=contacts(d,ph);
  if(list.length){
    y+=112;
    drawLines(list,W/2,y,62,38,"#33383e","center");
    y+=(list.length-1)*62;
  }
  placeQR(Math.max(y+130,1220),"#14171c","#5a6068");
},
minimal:function(d,ph,ac){
  ctx.fillStyle="#fdfcf9";ctx.fillRect(0,0,W,H);
  ctx.fillStyle=ac;ctx.fillRect(PAD,80,17,17);
  if(vis(ph.fBusiness)){
    ink("#6b7280",ph.fBusiness);spacing("4px");
    fit(d.fBusiness,600,26,16,"800",SANS);
    ctx.fillText(d.fBusiness.toUpperCase(),PAD+32,100);
    spacing("0px");ctx.globalAlpha=1;
  }
  if(state.logo)drawLogo(W-PAD-300,120,300,160);
  var list=contacts(d,ph);
  if(list.length){
    ctx.textAlign="right";
    list.forEach(function(line,i){
      ctx.font="600 32px "+SANS;
      ink("#4b5563",line.ph);
      ctx.fillText(line.t,W-PAD,330+i*54);
    });
    ctx.globalAlpha=1;ctx.textAlign="left";
  }
  if(vis(ph.fTagline)){
    ink("#4b5563",ph.fTagline);
    wrapText(d.fTagline,PAD,990,700,40,54,"600",headF(),"left");
    ctx.globalAlpha=1;
  }
  var bottom=qrBox(PAD,1120,350,"#14171c");
  ctx.fillStyle="#6b7280";ctx.globalAlpha=1;
  ctx.font="700 30px "+SANS;
  ctx.fillText("Scan to save my contact",PAD,bottom+54);
  if(vis(ph.fName)){
    ink("#14171c",ph.fName);
    fit(d.fName,CW,94,40,"800",dispF());
    ctx.fillText(d.fName,PAD,1752);
    ctx.globalAlpha=1;
  }
  if(vis(ph.fTitle)){
    ink("#6b7280",ph.fTitle);
    fit(d.fTitle,CW,38,20,"600",headF());
    ctx.fillText(d.fTitle,PAD,1838);
    ctx.globalAlpha=1;
  }
}
};
function render(){
  var o=data();
  ctx.save();
  ctx.globalAlpha=1;ctx.textAlign="left";ctx.textBaseline="alphabetic";spacing("0px");
  draw[state.tpl](o.d,o.ph,state.color);
  ctx.restore();
}
function exportCanvas(){
  var c=document.createElement("canvas");c.width=W;c.height=H;
  var main=ctx;ctx=c.getContext("2d");RE=true;
  var o=data();
  ctx.save();
  ctx.globalAlpha=1;ctx.textAlign="left";ctx.textBaseline="alphabetic";spacing("0px");
  draw[state.tpl](o.d,o.ph,state.color);
  ctx.restore();
  ctx=main;RE=false;
  return c;
}
function canvasBytes(c){
  return new Promise(function(res,rej){
    c.toBlob(function(b){
      if(!b){rej(new Error("png failed"));return;}
      b.arrayBuffer().then(res,rej);
    },"image/png");
  });
}
function persist(){
  var o={};
  FIELDS.forEach(function(id){o[id]=val(id);});
  o._tpl=state.tpl;o._color=state.color;o._font=state.font;
  try{localStorage.setItem("bb-dbcm-fields",JSON.stringify(o));}catch(e){}
}
function syncExports(){
  var ready=!!val("fName");
  ["pngBtn","jpgBtn","pdfBtn","vcfBtn","shareBtn"].forEach(function(id){$(id).disabled=!ready;});
  $("nameHint").classList.toggle("bb-hide",ready);
}
FIELDS.forEach(function(id){
  $(id).addEventListener("input",function(){render();persist();syncExports();});
});
(function restore(){
  try{
    var o=JSON.parse(localStorage.getItem("bb-dbcm-fields")||"{}");
    FIELDS.forEach(function(id){if(o[id])$(id).value=o[id];});
    if(o._tpl&&draw[o._tpl])state.tpl=o._tpl;
    if(o._color)state.color=o._color;
    if(o._font&&FONTS[o._font])state.font=o._font;
  }catch(e){}
  document.querySelectorAll("[data-tpl]").forEach(function(b){b.classList.toggle("on",b.getAttribute("data-tpl")===state.tpl);});
  document.querySelectorAll("[data-font]").forEach(function(b){b.classList.toggle("on",b.getAttribute("data-font")===state.font);});
  document.querySelectorAll(".swatch").forEach(function(s){s.classList.toggle("on",s.getAttribute("data-color")===state.color);});
  $("customColor").value=state.color;
})();
$("fLogo").addEventListener("change",function(){
  var file=this.files&&this.files[0];
  if(!file){state.logo=null;render();return;}
  var url=URL.createObjectURL(file);
  var img=new Image();
  img.onload=function(){state.logo=img;render();setStatus("Logo added. It lives on your device only.");};
  img.onerror=function(){URL.revokeObjectURL(url);setStatus("That image could not be read by this browser. Try a JPG or PNG.",true);};
  img.src=url;
});
document.querySelectorAll("[data-tpl]").forEach(function(btn){
  btn.addEventListener("click",function(){
    document.querySelectorAll("[data-tpl]").forEach(function(b){b.classList.remove("on");});
    btn.classList.add("on");state.tpl=btn.getAttribute("data-tpl");render();persist();
  });
});
document.querySelectorAll("[data-font]").forEach(function(btn){
  btn.addEventListener("click",function(){
    document.querySelectorAll("[data-font]").forEach(function(b){b.classList.remove("on");});
    btn.classList.add("on");state.font=btn.getAttribute("data-font");persist();
    render();ensureFonts().then(render);
  });
});
document.querySelectorAll(".swatch").forEach(function(sw){
  sw.addEventListener("click",function(){
    document.querySelectorAll(".swatch").forEach(function(s){s.classList.remove("on");});
    sw.classList.add("on");state.color=sw.getAttribute("data-color");
    $("customColor").value=state.color;render();persist();
  });
});
$("customColor").addEventListener("input",function(){
  document.querySelectorAll(".swatch").forEach(function(s){s.classList.remove("on");});
  state.color=this.value;render();persist();
});
function dropStrip(){if(window.BBDropStrip)window.BBDropStrip.show('digital-business-card-maker');}
function needName(){
  if(val("fName"))return false;
  setStatus("Add your name first. The downloads unlock as soon as the card has a name on it.",true);
  return true;
}
$("pngBtn").addEventListener("click",async function(){
  if(needName())return;
  await ensureFonts();
  exportCanvas().toBlob(function(blob){
    if(!blob){setStatus("The PNG could not be built in this browser.",true);return;}
    saveBlob(blob,"digital-business-card.png");
    setStatus("Your PNG is downloading: 1080 by 1920 pixels, ready to send or post.");
    dropStrip();
  },"image/png");
});
$("jpgBtn").addEventListener("click",async function(){
  if(needName())return;
  await ensureFonts();
  exportCanvas().toBlob(function(blob){
    if(!blob){setStatus("The JPG could not be built in this browser.",true);return;}
    saveBlob(blob,"digital-business-card.jpg");
    setStatus("Your JPG is downloading: 1080 by 1920 pixels, an easy size to text or email.");
    dropStrip();
  },"image/jpeg",0.92);
});
$("pdfBtn").addEventListener("click",async function(){
  if(needName())return;
  var btn=this;btn.disabled=true;var old=btn.textContent;btn.textContent="Building...";
  await ensureFonts();
  try{
    var doc=await window.PDFLib.PDFDocument.create();
    var img=await doc.embedPng(await canvasBytes(exportCanvas()));
    var page=doc.addPage([288,512]);
    page.drawImage(img,{x:0,y:0,width:288,height:512});
    var bytes=await doc.save();
    saveBlob(new Blob([bytes],{type:"application/pdf"}),"digital-business-card.pdf");
    setStatus("Your PDF is downloading: one page in the tall phone shape, QR code included.");
    dropStrip();
  }catch(e){setStatus("The PDF could not be built in this browser. The PNG and JPG downloads still work.",true);}
  btn.disabled=false;btn.textContent=old;syncExports();
});
$("vcfBtn").addEventListener("click",function(){
  if(needName())return;
  var slug=val("fName").toLowerCase().replace(/[^a-z0-9]+/g,"-").replace(/^-+|-+$/g,"")||"contact";
  saveBlob(new Blob([vcard()],{type:"text/vcard;charset=utf-8"}),slug+"-contact.vcf");
  setStatus("Your contact file is downloading. Send it by text or email and one tap adds you to their contacts.");
  dropStrip();
});
var shareBlob=null;
var SHARE_URL="https://leaveittobumbum.com/tools/digital-business-card-maker/";
var SHARE_CREDIT="Made with the Free Digital Business Card Maker from Leave It To Bum Bum";
function shareHead(){
  var name=val("fName"),biz=val("fBusiness");
  return name+(biz?" \u2014 "+biz:"");
}
function closeShareMenu(){
  $("shareMenu").classList.add("bb-hide");
  $("shareBtn").setAttribute("aria-expanded","false");
}
function openShareMenu(){
  var canCopy=!!(navigator.clipboard&&navigator.clipboard.write&&typeof ClipboardItem!=="undefined");
  $("shareCopy").classList.toggle("bb-hide",!canCopy);
  $("shareMenu").classList.remove("bb-hide");
  $("shareBtn").setAttribute("aria-expanded","true");
}
document.addEventListener("click",function(e){
  if(!$("shareWrap").contains(e.target))closeShareMenu();
});
document.addEventListener("keydown",function(e){
  if(e.key==="Escape")closeShareMenu();
});
$("shareBtn").addEventListener("click",async function(){
  if(needName())return;
  if(!$("shareMenu").classList.contains("bb-hide")){closeShareMenu();return;}
  await ensureFonts();
  var blob=await new Promise(function(res){exportCanvas().toBlob(res,"image/png");});
  if(!blob){setStatus("The card image could not be built in this browser. The PNG download still works.",true);return;}
  shareBlob=blob;
  var head=shareHead();
  var file=null;
  try{file=new File([blob],"digital-business-card.png",{type:"image/png"});}catch(e){}
  if(file&&navigator.canShare&&navigator.canShare({files:[file]})){
    try{
      await navigator.share({files:[file],title:head+" \u2014 digital business card",text:head+"\n\n"+SHARE_CREDIT+"\n"+SHARE_URL});
      setStatus("Shared. Your card is on its way.");
      dropStrip();
      return;
    }catch(e){
      if(e&&e.name==="AbortError")return;
    }
  }
  openShareMenu();
});
$("shareEmail").addEventListener("click",function(){
  closeShareMenu();
  if(!shareBlob)return;
  saveBlob(shareBlob,"digital-business-card.png");
  var subject=encodeURIComponent(shareHead()+" \u2014 digital business card");
  var body=encodeURIComponent("Here is my digital business card.\n\nYour card image just downloaded as digital-business-card.png. Attach it to this email before sending.\n\n"+SHARE_CREDIT+"\n"+SHARE_URL);
  setStatus("Your card image is downloading. Your email app opens next; attach the image before sending.");
  setTimeout(function(){window.location.href="mailto:?subject="+subject+"&body="+body;},400);
});
$("shareCopy").addEventListener("click",async function(){
  closeShareMenu();
  if(!shareBlob)return;
  try{
    await navigator.clipboard.write([new ClipboardItem({"image/png":shareBlob})]);
    setStatus("Card image copied. Paste it into a message, an email, or a doc.");
    var b=$("shareBtn"),old=b.textContent;
    b.textContent="Copied";
    setTimeout(function(){b.textContent=old;},1600);
    dropStrip();
  }catch(e){
    setStatus("Copying the image is not available in this browser. The PNG download works instead.",true);
  }
});
if(document.fonts&&document.fonts.ready)document.fonts.ready.then(render);
ensureFonts().then(render);
syncExports();
render();
})();
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
