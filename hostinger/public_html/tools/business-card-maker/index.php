<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Free Business Card Maker | Design a Card and Download a Print-Ready PDF | Leave It to Bum Bum</title>
<meta name="description" content="Free business card maker that runs in your browser. Type your details, add a logo, pick a template and a color, and design the back too: a logo and tagline, a QR code, or your name in big type. Download a print-ready PDF or PNG at 300 DPI. No sign-up, no watermark, and your details never leave your device.">
<meta property="og:title" content="Free Business Card Maker | Design a Card and Download a Print-Ready PDF | Leave It to Bum Bum">
<meta property="og:description" content="Free business card maker that runs in your browser. Type your details, pick a template and a color, design the back, and download a print-ready PDF or PNG at 300 DPI. No sign-up, and your details never leave your device.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/business-card-maker/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/business-card-maker.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Free Business Card Maker | Leave It to Bum Bum">
<meta name="twitter:description" content="Free business card maker that runs in your browser. Type your details, pick a template and a color, and download a print-ready PDF or PNG at 300 DPI. No sign-up, and your details never leave your device.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/business-card-maker.jpg">
<link rel="canonical" href="https://leaveittobumbum.com/tools/business-card-maker/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Business Card Maker",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" },
  "description": "Free business card maker that runs in your browser. Type your details, add a logo, pick a template and a color, and download a print-ready PDF or PNG at 300 DPI. No sign-up, no watermark, and your details never leave your device.",
  "url": "https://leaveittobumbum.com/tools/business-card-maker/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    { "@type": "Question", "name": "Do I need an account?", "acceptedAnswer": { "@type": "Answer", "text": "No. The maker is free and needs no sign-up. Type your details and download the card." } },
    { "@type": "Question", "name": "Do my details upload anywhere?", "acceptedAnswer": { "@type": "Answer", "text": "No. The card is drawn right in your browser, on your device. Your details and your logo are never sent to Bum Bum or anyone else." } },
    { "@type": "Question", "name": "What size are the cards?", "acceptedAnswer": { "@type": "Answer", "text": "Standard business card size, 3.5 by 2 inches. The PDF page is exactly that size and the artwork renders at 300 DPI, 1050 by 600 pixels, the sharpness print shops ask for. The PNG is the same 1050 by 600 artwork." } },
    { "@type": "Question", "name": "Can I design the back of the card?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Switch to the Back side to add your logo with a tagline, a QR code that points at your site, or your business name in big type. When a back is designed, the PDF downloads as two pages, front then back, each exactly 3.5 by 2 inches. You can leave the back blank instead, and then the PDF stays one page." } },
    { "@type": "Question", "name": "Can I add my logo?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Pick any image file and it is placed on the card. The file is read on your device and never uploaded. Square or wide logos both work; the maker fits them into the template's logo spot." } },
    { "@type": "Question", "name": "What if a printer asks for bleed?", "acceptedAnswer": { "@type": "Answer", "text": "These cards are drawn at exact trim size with no bleed area. Most online printers accept that. If yours insists on bleed, a white-background template hides the trim line best." } },
    { "@type": "Question", "name": "What does it cost?", "acceptedAnswer": { "@type": "Answer", "text": "Nothing. Designing and downloading are free, as many times as you like, and downloads carry no watermark." } }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Free Business Card Maker | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Business Card Maker","item":"https://leaveittobumbum.com/tools/business-card-maker/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use the Business Card Maker","description":"Design a standard business card in your browser and download a print-ready PDF or PNG.","step":[{"@type":"HowToStep","position":1,"name":"Type your name, title, business, and contact details."},{"@type":"HowToStep","position":2,"name":"Add your logo if you have one. It is read on your device only."},{"@type":"HowToStep","position":3,"name":"Pick a template and an accent color. The preview updates as you type."},{"@type":"HowToStep","position":4,"name":"Switch to the Back side to add a logo and tagline, a QR code, or your business name in big type, or leave the back blank."},{"@type":"HowToStep","position":5,"name":"Download the print-ready PDF for a print shop, or the PNGs for sharing online."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.bc-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;align-items:start;margin-top:20px}
.bc-card{background:#fff;border:2px solid var(--line);border-radius:14px;padding:16px}
.bc-card h2{margin:0 0 12px;font-size:18px}
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
.bc-preview-wrap{position:sticky;top:16px}
.bc-preview{background:#fffdf8;border:2px solid var(--ink);border-radius:14px;padding:16px;box-shadow:4px 4px 0 var(--ink)}
.bc-preview canvas{width:100%;height:auto;display:block;border:1px solid #e2d7bf;border-radius:6px;background:#fff}
.export-row{display:flex;gap:10px;flex-wrap:wrap;margin-top:14px}
.export-row .button{min-height:48px}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.hint{font-size:13px;opacity:.7;margin:10px 0 0}
.bb-hide{display:none!important}
.hc-status{font-size:13px;font-weight:700;margin-top:10px;min-height:18px}
.hc-status.error{color:#b3261e}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media(max-width:900px){.bc-grid{grid-template-columns:1fr}.bc-preview-wrap{position:static}}
</style></head>
<body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Business Card Maker"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/business-card-maker.png" alt="Business Card Maker"><div><h1>Free Business Card Maker</h1><p class="lede bb-desc">Design a sharp business card, front and back, in your browser and download a print-ready PDF. Free.</p></div></div>
<div class="bc-grid">
<form class="bc-card" id="bcForm" autocomplete="off">
<h2>Your details</h2>
<p class="grp">Card side</p>
<div class="seg" role="group" aria-label="Card side"><button type="button" data-side="front" class="on">Front</button><button type="button" data-side="back">Back</button></div>
<div id="sideFront">
<p class="grp">On the card</p>
<div class="fld"><label for="fName">Full name</label><input id="fName" type="text" placeholder="Jane Appleseed" maxlength="60"></div>
<div class="fld"><label for="fTitle">Job title</label><input id="fTitle" type="text" placeholder="Founder and Dog Walker" maxlength="60"></div>
<div class="fld"><label for="fBusiness">Business name</label><input id="fBusiness" type="text" placeholder="Appleseed Walks" maxlength="60"></div>
<p class="grp">Contact</p>
<div class="fld"><label for="fPhone">Phone</label><input id="fPhone" type="tel" placeholder="416-555-0134" maxlength="40"></div>
<div class="fld"><label for="fEmail">Email</label><input id="fEmail" type="email" placeholder="jane@appleseed.co" maxlength="60"></div>
<div class="fld"><label for="fWebsite">Website</label><input id="fWebsite" type="text" placeholder="appleseed.co" maxlength="60"></div>
<div class="fld"><label for="fLogo">Logo (optional)</label><input id="fLogo" type="file" accept="image/*"><p class="note">Read on your device only. Nothing uploads. Square or wide logos both fit.</p></div>
</div>
<div id="sideBack" class="bb-hide">
<p class="grp">Back side</p>
<div class="seg" role="group" aria-label="Back side design"><button type="button" data-backblank="design" class="on">Design a back</button><button type="button" data-backblank="blank">Leave it blank</button></div>
<div id="backDesign">
<p class="grp">Back layout</p>
<div class="seg" role="group" aria-label="Back layout"><button type="button" data-backlayout="logo" class="on">Logo &amp; tagline</button><button type="button" data-backlayout="qr">QR code</button><button type="button" data-backlayout="bigname">Big name</button></div>
<div class="fld" id="fldTagline" style="margin-top:14px"><label for="fTagline">Tagline</label><input id="fTagline" type="text" placeholder="Good work, done properly." maxlength="80"></div>
<div class="fld bb-hide" id="fldQr"><label for="fQrLink">QR code link</label><input id="fQrLink" type="text" placeholder="yourbusiness.com" maxlength="300"><p class="note">The code points here. It starts filled in with your website; change it if the code should land somewhere else.</p></div>
<p class="note">The back wears the same template and accent color as the front, so the two sides match.</p>
</div>
</div>
<p class="grp">Template</p>
<div class="seg" role="group" aria-label="Card template"><button type="button" data-tpl="classic" class="on">Classic</button><button type="button" data-tpl="bold">Bold</button><button type="button" data-tpl="sidebar">Sidebar</button><button type="button" data-tpl="centered">Centered</button><button type="button" data-tpl="minimal">Minimal</button><button type="button" data-tpl="bumbum">Bum Bum</button></div>
<p class="grp">Accent color</p>
<div class="color-row"><button type="button" class="swatch on" data-color="#b3541e" style="background:#b3541e" aria-label="Rust"></button><button type="button" class="swatch" data-color="#2f6fed" style="background:#2f6fed" aria-label="Blue"></button><button type="button" class="swatch" data-color="#2f9e5f" style="background:#2f9e5f" aria-label="Green"></button><button type="button" class="swatch" data-color="#8e4ec6" style="background:#8e4ec6" aria-label="Purple"></button><button type="button" class="swatch" data-color="#1a1a1a" style="background:#1a1a1a" aria-label="Black"></button><input type="color" class="pick" id="customColor" value="#b3541e" aria-label="Custom color"></div>
</form>
<div class="bc-preview-wrap">
<h2 style="margin:0 0 12px;font-size:18px">Preview <span id="previewSide" style="opacity:.55;font-size:14px">front</span></h2>
<div class="bc-preview"><canvas id="cardCanvas" width="1050" height="600" aria-label="Your business card preview"></canvas></div>
<div class="export-row"><button type="button" class="button" id="pdfBtn">Download print-ready PDF</button><button type="button" class="button secondary" id="pngBtn">Download front PNG</button><button type="button" class="button secondary" id="backPngBtn">Download back PNG</button></div>
<p class="hc-status" id="statusLine" role="status"></p>
<p class="hint">Each PDF page is exactly 3.5 by 2 inches and the artwork renders at 300 DPI. Design a back and the PDF downloads as two pages, front then back; leave the back blank and it stays one page. Blank fields show as soft gray sample text in the preview, so fill in a field to replace its sample. Your details and logo are drawn on your device. Nothing is uploaded, stored, or sent to Bum Bum.</p>
</div>
</div>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2>Questions, answered</h2>
<details><summary>Do I need an account?</summary><p>No. The maker is free and needs no sign-up. Type your details and download the card.</p></details>
<details><summary>Do my details upload anywhere?</summary><p>No. The card is drawn right in your browser, on your device. Your details and your logo are never sent to Bum Bum or anyone else. The form remembers your text entries on this device for your next visit; your logo file is never kept.</p></details>
<details><summary>What size are the cards?</summary><p>Standard business card size, 3.5 by 2 inches. The PDF page is exactly that size and the artwork renders at 300 DPI, 1050 by 600 pixels, the sharpness print shops ask for. The PNGs are the same 1050 by 600 artwork. Design a back and the PDF comes out as two pages, one per side.</p></details>
<details><summary>Can I design the back of the card?</summary><p>Yes. Use the Front and Back switch in the editor to design the back: your logo with a tagline under it, a QR code that points at your site, or your business name in big type. The back picks up the front's template and accent color on its own. When a back is designed, the PDF downloads as two pages, front then back, and you can grab a PNG of each side. Leave the back blank instead and the PDF stays one page.</p></details>
<details><summary>Can I add my logo?</summary><p>Yes. Pick any image file and it is placed on the card. The file is read on your device and never uploaded. Square or wide logos both work; the maker fits them into the template's logo spot.</p></details>
<details><summary>What if a printer asks for bleed?</summary><p>These cards are drawn at exact trim size with no bleed area. Most online printers accept that. If yours insists on bleed, a white-background template hides the trim line best.</p></details>
<details><summary>What does it cost?</summary><p>Nothing. Designing and downloading are free, as many times as you like, and downloads carry no watermark.</p></details>
</section>
<section class="panel" aria-label="Guide">
<h2>Business cards, the short guide</h2>
<p>A good card does one job: it hands a new contact your name, what you do, and one easy way to reach you, in a form that survives a wallet, a pocket, and a desk drawer. This maker keeps that job small. Six templates, one accent color, your details, done.</p>
<p>What to put on it: your name, your title or trade in plain words, your business name, one phone number, one email, one website. That is the whole list. A card people can read at a glance gets kept; a crowded one gets recycled. Leave a field blank and it simply stays off the card.</p>
<p>Printing: send the PDF to the print shop. The page is exactly 3.5 by 2 inches at 300 DPI, the size and sharpness printers ask for. Keep the PNG on your phone too; texting your card to a new contact beats spelling your email out loud. Want a digital card for your phone? The <a href="/tools/digital-business-card-maker/">Digital Business Card Maker</a> builds a tall card made for a phone screen, with a QR code that saves your contact to any phone that scans it. Match the accent color to your <a href="/tools/email-signature-generator/">email signature</a> and your <a href="/tools/invoice-generator/">invoices</a>, and everything you hand out looks like one business. For the back, give it one job: your logo with a tagline under it, your business name in big type, or a QR code that sends people to your site. One clear back beats a busy one.</p>
</section>
<?php $bbTool = 'business-card-maker'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
</main><?php require dirname(__DIR__, 2) . '/includes/site-footer.php'; ?>
<script src="/tools/pdf-merger/vendor/pdf-lib.min.js"></script>
<script src="/tools/purr-code/qrcode.min.js"></script>
<script>
(function(){
"use strict";
var $=function(id){return document.getElementById(id);};
var W=1050,H=600,PAD=70;
var SANS="'Nunito Sans',Helvetica,Arial,sans-serif";
var SERIF="Fraunces,Georgia,serif";
var canvas=$("cardCanvas"),ctx=canvas.getContext("2d");
var state={tpl:"classic",color:"#b3541e",logo:null,side:"front",backLayout:"logo",backBlank:false,qrDirty:false};
var FIELDS=["fName","fTitle","fBusiness","fPhone","fEmail","fWebsite"];
var BACKFIELDS=["fTagline","fQrLink"];
var SAMPLE={fName:"Your Name",fTitle:"Your Title",fBusiness:"Your Business",fPhone:"416-555-0134",fEmail:"you@yourbusiness.com",fWebsite:"yourbusiness.com"};

function val(id){return ($(id).value||"").trim();}
function data(){
  var d={},ph={};
  FIELDS.forEach(function(id){
    var v=val(id);
    d[id]=v||SAMPLE[id];
    ph[id]=!v;
  });
  d.websiteDisp=d.fWebsite.replace(/^https?:\/\//i,"").replace(/\/+$/,"");
  return {d:d,ph:ph};
}
function setStatus(msg,isErr){var s=$("statusLine");s.textContent=msg||"";s.className="hc-status"+(isErr?" error":"");}
function saveBlob(blob,name){var a=document.createElement("a");a.href=URL.createObjectURL(blob);a.download=name;document.body.appendChild(a);a.click();a.remove();setTimeout(function(){URL.revokeObjectURL(a.href);},4000);}
function hexRgb(hex){var n=parseInt(hex.slice(1),16);return [n>>16&255,n>>8&255,n&255];}
function lum(hex){var c=hexRgb(hex);return (0.2126*c[0]+0.7152*c[1]+0.0722*c[2])/255;}
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
function paw(x,y,s,color){
  ctx.fillStyle=color;
  ctx.beginPath();ctx.ellipse(x,y+s*0.28,s*0.52,s*0.4,-0.35,0,Math.PI*2);ctx.fill();
  var toes=[[-0.62,-0.28],[ -0.22,-0.52],[0.22,-0.52],[0.62,-0.28]];
  toes.forEach(function(t){ctx.beginPath();ctx.arc(x+t[0]*s,y+t[1]*s,s*0.17,0,Math.PI*2);ctx.fill();});
}
function contacts(d,ph){
  var out=[];
  if(d.fPhone)out.push({t:d.fPhone,ph:ph.fPhone});
  if(d.fEmail)out.push({t:d.fEmail,ph:ph.fEmail});
  if(d.fWebsite)out.push({t:d.websiteDisp,ph:ph.fWebsite});
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

var draw={
classic:function(d,ph,ac){
  ctx.fillStyle="#ffffff";ctx.fillRect(0,0,W,H);
  ink(ac,ph.fBusiness);spacing("3px");
  fit(d.fBusiness,W-PAD*2-220,30,18,"800",SANS);
  ctx.fillText(d.fBusiness.toUpperCase(),PAD,100);
  spacing("0px");ctx.globalAlpha=1;
  if(state.logo){drawLogo(W-PAD-190,52,190,104);}
  ink("#20242a",ph.fName);
  fit(d.fName,W-PAD*2,98,40,"700",SERIF);
  ctx.fillText(d.fName,PAD,272);
  ctx.globalAlpha=1;
  ink("#5a6068",ph.fTitle);
  fit(d.fTitle,W-PAD*2,42,22,"600",SANS);
  ctx.fillText(d.fTitle,PAD,336);
  ctx.globalAlpha=1;
  ctx.fillStyle=ac;ctx.fillRect(PAD,376,120,8);
  drawLines(contacts(d,ph),PAD,458,50,34,"#33383e");
},
bold:function(d,ph,ac){
  ctx.fillStyle=ac;ctx.fillRect(0,0,W,H);
  var main="#ffffff";
  ink(main,ph.fBusiness);spacing("3px");
  ctx.globalAlpha=ph.fBusiness?0.5:0.9;
  fit(d.fBusiness,W-PAD*2-220,30,18,"800",SANS);
  ctx.fillText(d.fBusiness.toUpperCase(),PAD,104);
  spacing("0px");ctx.globalAlpha=1;
  if(state.logo){
    ctx.fillStyle="#ffffff";roundRect(W-PAD-160,52,160,160,18);ctx.fill();
    drawLogo(W-PAD-160+12,64,136,136);
  }
  ink(main,ph.fName);
  fit(d.fName,state.logo?W-PAD*2-210:W-PAD*2,112,44,"900",SANS);
  ctx.fillText(d.fName,PAD,268);
  ctx.globalAlpha=1;
  ink(main,ph.fTitle);ctx.globalAlpha=ph.fTitle?0.5:0.85;
  fit(d.fTitle,W-PAD*2,44,22,"700",SANS);
  ctx.fillText(d.fTitle,PAD,334);
  ctx.globalAlpha=1;
  drawLines(contacts(d,ph),PAD,458,50,34,main);
},
sidebar:function(d,ph,ac){
  ctx.fillStyle="#ffffff";ctx.fillRect(0,0,W,H);
  ctx.fillStyle=ac;ctx.fillRect(0,0,320,H);
  if(state.logo){
    ctx.fillStyle="#ffffff";roundRect(50,86,220,220,20);ctx.fill();
    drawLogo(62,98,196,196);
  }else{
    monogram(160,196,84,d.fBusiness||d.fName,ac==="#ffffff"?"#dddddd":"#ffffff",ac);
  }
  ctx.fillStyle="#ffffff";ctx.textAlign="center";
  spacing("2px");
  var biz=d.fBusiness.toUpperCase();
  ctx.font="800 27px "+SANS;
  var words=biz.split(" "),lines=[],cur="";
  words.forEach(function(w){var t=(cur+" "+w).trim();if(ctx.measureText(t).width>248&&cur){lines.push(cur);cur=w;}else{cur=t;}});
  if(cur)lines.push(cur);
  lines.slice(0,3).forEach(function(l,i){ctx.globalAlpha=ph.fBusiness?0.55:1;ctx.fillText(l,160,392+i*36);});
  ctx.globalAlpha=1;spacing("0px");ctx.textAlign="left";
  var X=392,RW=W-PAD-X;
  ink("#20242a",ph.fName);
  fit(d.fName,RW,86,38,"700",SERIF);
  ctx.fillText(d.fName,X,206);
  ctx.globalAlpha=1;
  ink("#5a6068",ph.fTitle);
  fit(d.fTitle,RW,40,22,"600",SANS);
  ctx.fillText(d.fTitle,X,268);
  ctx.globalAlpha=1;
  ctx.fillStyle=ac;ctx.fillRect(X,302,90,7);
  drawLines(contacts(d,ph),X,402,54,34,"#33383e");
},
centered:function(d,ph,ac){
  ctx.fillStyle="#ffffff";ctx.fillRect(0,0,W,H);
  var nameY=316;
  if(state.logo){drawLogo(W/2-130,48,260,118);nameY=330;}
  else{monogram(W/2,108,56,d.fBusiness||d.fName,ac,"#ffffff");nameY=316;}
  ctx.textAlign="center";
  ink("#20242a",ph.fName);
  fit(d.fName,W-PAD*2,94,40,"700",SERIF);
  ctx.fillText(d.fName,W/2,nameY);
  ctx.globalAlpha=1;
  ink(ac,ph.fTitle);
  fit(d.fTitle,W-PAD*2,40,22,"700",SANS);
  ctx.fillText(d.fTitle,W/2,nameY+62);
  ctx.globalAlpha=1;
  ink("#8a8f96",ph.fBusiness);spacing("3px");
  fit(d.fBusiness,W-PAD*2,26,16,"800",SANS);
  ctx.fillText(d.fBusiness.toUpperCase(),W/2,nameY+108);
  spacing("0px");ctx.globalAlpha=1;
  ctx.fillStyle=ac;
  [-46,0,46].forEach(function(off){ctx.beginPath();ctx.arc(W/2+off,nameY+142,5,0,Math.PI*2);ctx.fill();});
  var list=contacts(d,ph);
  ctx.font="600 33px "+SANS;
  var joined=list.map(function(l){return l.t;}).join("   ·   ");
  var anyPh=list.some(function(l){return l.ph;});
  ink("#33383e",anyPh);
  fit(joined,W-PAD*2,33,20,"600",SANS);
  ctx.fillText(joined,W/2,nameY+196);
  ctx.globalAlpha=1;ctx.textAlign="left";
},
minimal:function(d,ph,ac){
  ctx.fillStyle="#fdfcf9";ctx.fillRect(0,0,W,H);
  ctx.fillStyle=ac;ctx.fillRect(PAD,64,16,16);
  ink("#6b7280",ph.fBusiness);spacing("4px");
  fit(d.fBusiness,W-PAD*2-260,26,16,"800",SANS);
  ctx.fillText(d.fBusiness.toUpperCase(),PAD+30,80);
  spacing("0px");ctx.globalAlpha=1;
  if(state.logo){drawLogo(W-PAD-170,120,170,92);}
  var list=contacts(d,ph);
  var cy0=state.logo?258:150;
  ctx.textAlign="right";
  list.forEach(function(line,i){
    ctx.font="600 30px "+SANS;
    ink("#4b5563",line.ph);
    ctx.fillText(line.t,W-PAD,cy0+i*44);
  });
  ctx.globalAlpha=1;ctx.textAlign="left";
  ink("#14171c",ph.fName);
  fit(d.fName,W-PAD*2,88,38,"800",SANS);
  ctx.fillText(d.fName,PAD,472);
  ctx.globalAlpha=1;
  ink("#6b7280",ph.fTitle);
  fit(d.fTitle,W-PAD*2,36,20,"600",SANS);
  ctx.fillText(d.fTitle,PAD,528);
  ctx.globalAlpha=1;
},
bumbum:function(d,ph,ac){
  ctx.fillStyle="#fff6e9";ctx.fillRect(0,0,W,H);
  ctx.fillStyle=ac;ctx.beginPath();ctx.arc(1006,30,190,0,Math.PI*2);ctx.fill();
  paw(972,66,74,"#fff6e9");
  ink("#8a6d4f",ph.fBusiness);spacing("3px");
  fit(d.fBusiness,W-PAD*2-300,28,16,"800",SANS);
  ctx.fillText(d.fBusiness.toUpperCase(),PAD,104);
  spacing("0px");ctx.globalAlpha=1;
  ink("#3a2a1c",ph.fName);
  fit(d.fName,W-PAD*2-160,100,42,"900",SANS);
  ctx.fillText(d.fName,PAD,256);
  ctx.globalAlpha=1;
  ink(ac,ph.fTitle);
  fit(d.fTitle,W-PAD*2-160,42,22,"800",SANS);
  ctx.fillText(d.fTitle,PAD,322);
  ctx.globalAlpha=1;
  if(state.logo){
    ctx.fillStyle="#ffffff";roundRect(W-PAD-196,H-PAD-150,196,150,16);ctx.fill();
    ctx.strokeStyle="#e8dcc4";ctx.lineWidth=2;ctx.stroke();
    drawLogo(W-PAD-196+10,H-PAD-150+10,176,130);
  }
  var list=contacts(d,ph);
  list.forEach(function(line,i){
    var y=446+i*52;
    paw(PAD+14,y-12,26,ac);
    ctx.font="700 34px "+SANS;
    ink("#4a3a28",line.ph);
    ctx.fillText(line.t,PAD+52,y);
    ctx.globalAlpha=1;
  });
}
};
function backTheme(ac){
  if(state.tpl==="bold")return{bg:ac,fg:"#ffffff",sub:"#ffffff",subPh:0.55};
  if(state.tpl==="bumbum")return{bg:"#fff6e9",fg:"#3a2a1c",sub:"#8a6d4f",subPh:0.35};
  if(state.tpl==="minimal")return{bg:"#fdfcf9",fg:"#14171c",sub:"#6b7280",subPh:0.35};
  return{bg:"#ffffff",fg:"#20242a",sub:"#5a6068",subPh:0.35};
}
function inkA(color,alpha){ctx.fillStyle=color;ctx.globalAlpha=alpha;}
function qrLinkRaw(){
  var q=val("fQrLink");
  return q||val("fWebsite");
}
function qrPayload(){
  var p=qrLinkRaw()||SAMPLE.fWebsite;
  if(!/^[a-zA-Z][a-zA-Z0-9+.-]*:/.test(p))p="https://"+p.replace(/^\/+/,"");
  return p;
}
function drawQR(x,y,size,fg){
  var qr;
  try{qr=qrcode(0,"M");qr.addData(qrPayload());qr.make();}
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
var drawBack={
logo:function(d,ph,ac){
  var th=backTheme(ac);
  ctx.fillStyle=th.bg;ctx.fillRect(0,0,W,H);
  ctx.textAlign="center";
  var tagY;
  if(state.logo){
    drawLogo((W-480)/2,84,480,236);
    tagY=424;
  }else{
    ink(th.fg,ph.fBusiness);
    fit(d.fBusiness,W-PAD*2,96,44,"700",SERIF);
    ctx.fillText(d.fBusiness,W/2,272);
    ctx.globalAlpha=1;
    tagY=352;
  }
  var tag=val("fTagline"),tagText=tag||"Good work, done properly.";
  inkA(th.sub,tag?1:th.subPh);
  fit(tagText,W-PAD*2,42,22,"600",SANS);
  ctx.fillText(tagText,W/2,tagY);
  ctx.globalAlpha=1;
  if(state.tpl==="bumbum")paw(W/2,472,30,ac);
  ink(state.tpl==="bold"?"#ffffff":ac,ph.fWebsite);spacing("3px");
  fit(d.websiteDisp.toUpperCase(),W-PAD*2,28,16,"800",SANS);
  ctx.fillText(d.websiteDisp.toUpperCase(),W/2,530);
  spacing("0px");ctx.globalAlpha=1;ctx.textAlign="left";
},
qr:function(d,ph,ac){
  var th=backTheme(ac);
  ctx.fillStyle=th.bg;ctx.fillRect(0,0,W,H);
  var size=324,x=(W-size)/2,y=54;
  ctx.fillStyle="#ffffff";roundRect(x-20,y-20,size+40,size+40,22);ctx.fill();
  if(!drawQR(x,y,size,"#14171c")){
    ctx.fillStyle="#b3261e";ctx.font="700 28px "+SANS;ctx.textAlign="center";
    ctx.fillText("That link is too long for a QR code.",W/2,y+size/2);
  }
  ctx.textAlign="center";
  ink(th.fg,ph.fBusiness);
  fit(d.fBusiness,W-PAD*2,46,24,"800",SANS);
  ctx.fillText(d.fBusiness,W/2,478);
  ctx.globalAlpha=1;
  inkA(th.sub,state.tpl==="bold"?0.85:0.8);
  ctx.font="600 29px "+SANS;
  ctx.fillText("Scan to take a look",W/2,528);
  ctx.globalAlpha=1;ctx.textAlign="left";
},
bigname:function(d,ph,ac){
  var th=backTheme(ac);
  ctx.fillStyle=th.bg;ctx.fillRect(0,0,W,H);
  var tag=val("fTagline");
  ctx.textAlign="center";
  ink(th.fg,ph.fBusiness);
  fit(d.fBusiness,W-PAD*2,168,58,"900",SANS);
  ctx.fillText(d.fBusiness,W/2,tag?322:338);
  ctx.globalAlpha=1;
  if(tag){
    inkA(th.sub,1);
    fit(tag,W-PAD*2,38,20,"600",SANS);
    ctx.fillText(tag,W/2,398);
    ctx.globalAlpha=1;
  }
  if(state.tpl==="bumbum")paw(W/2,492,34,ac);
  ctx.textAlign="left";
}
};
function drawBlankBack(ac){
  var th=backTheme(ac);
  ctx.fillStyle=th.bg;ctx.fillRect(0,0,W,H);
}
function render(){
  var o=data();
  ctx.save();
  ctx.globalAlpha=1;ctx.textAlign="left";ctx.textBaseline="alphabetic";spacing("0px");
  if(state.side==="back"){
    if(state.backBlank)drawBlankBack(state.color);
    else drawBack[state.backLayout](o.d,o.ph,state.color);
  }else{
    draw[state.tpl](o.d,o.ph,state.color);
  }
  ctx.restore();
}
function sideCanvas(side){
  var c=document.createElement("canvas");c.width=W;c.height=H;
  var main=ctx;ctx=c.getContext("2d");
  var o=data();
  ctx.save();
  ctx.globalAlpha=1;ctx.textAlign="left";ctx.textBaseline="alphabetic";spacing("0px");
  if(side==="back")drawBack[state.backLayout](o.d,o.ph,state.color);
  else draw[state.tpl](o.d,o.ph,state.color);
  ctx.restore();
  ctx=main;
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
FIELDS.forEach(function(id){
  $(id).addEventListener("input",function(){render();persist();});
});
BACKFIELDS.forEach(function(id){
  $(id).addEventListener("input",function(){render();persist();});
});
$("fQrLink").addEventListener("input",function(){
  state.qrDirty=!!val("fQrLink");
  persist();
});
$("fWebsite").addEventListener("input",function(){
  if(!state.qrDirty)$("fQrLink").value=val("fWebsite");
});
function persist(){
  var o={};
  FIELDS.concat(BACKFIELDS).forEach(function(id){o[id]=val(id);});
  o._layout=state.backLayout;o._blank=state.backBlank;o._qrDirty=state.qrDirty;
  try{localStorage.setItem("bb-bcm-fields",JSON.stringify(o));}catch(e){}
}
function segOn(sel,attr,value){
  document.querySelectorAll(sel).forEach(function(b){b.classList.toggle("on",b.getAttribute(attr)===value);});
}
function syncBackUI(){
  $("sideFront").classList.toggle("bb-hide",state.side!=="front");
  $("sideBack").classList.toggle("bb-hide",state.side!=="back");
  $("previewSide").textContent=state.side;
  $("backDesign").classList.toggle("bb-hide",state.backBlank);
  $("fldTagline").classList.toggle("bb-hide",state.backLayout==="qr");
  $("fldQr").classList.toggle("bb-hide",state.backLayout!=="qr");
  $("backPngBtn").classList.toggle("bb-hide",state.backBlank);
  segOn("[data-side]","data-side",state.side);
  segOn("[data-backblank]","data-backblank",state.backBlank?"blank":"design");
  segOn("[data-backlayout]","data-backlayout",state.backLayout);
}
(function restore(){
  try{
    var o=JSON.parse(localStorage.getItem("bb-bcm-fields")||"{}");
    FIELDS.concat(BACKFIELDS).forEach(function(id){if(o[id])$(id).value=o[id];});
    if(o._layout&&drawBack[o._layout])state.backLayout=o._layout;
    state.backBlank=!!o._blank;
    state.qrDirty=!!o._qrDirty;
  }catch(e){}
  if(!state.qrDirty&&!val("fQrLink"))$("fQrLink").value=val("fWebsite");
  syncBackUI();
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
    btn.classList.add("on");state.tpl=btn.getAttribute("data-tpl");render();
  });
});
document.querySelectorAll(".swatch").forEach(function(sw){
  sw.addEventListener("click",function(){
    document.querySelectorAll(".swatch").forEach(function(s){s.classList.remove("on");});
    sw.classList.add("on");state.color=sw.getAttribute("data-color");
    $("customColor").value=state.color;render();
  });
});
$("customColor").addEventListener("input",function(){
  document.querySelectorAll(".swatch").forEach(function(s){s.classList.remove("on");});
  state.color=this.value;render();
});
document.querySelectorAll("[data-side]").forEach(function(btn){
  btn.addEventListener("click",function(){
    state.side=btn.getAttribute("data-side");syncBackUI();render();
  });
});
document.querySelectorAll("[data-backblank]").forEach(function(btn){
  btn.addEventListener("click",function(){
    state.backBlank=btn.getAttribute("data-backblank")==="blank";syncBackUI();render();persist();
  });
});
document.querySelectorAll("[data-backlayout]").forEach(function(btn){
  btn.addEventListener("click",function(){
    state.backLayout=btn.getAttribute("data-backlayout");syncBackUI();render();persist();
  });
});
$("pngBtn").addEventListener("click",function(){
  sideCanvas("front").toBlob(function(blob){
    if(!blob){setStatus("The PNG could not be built in this browser.",true);return;}
    saveBlob(blob,"business-card-front.png");
    setStatus("Your front PNG is downloading: 1050 by 600 pixels, ready to share.");
    if(window.BBDropStrip)window.BBDropStrip.show('business-card-maker');
  },"image/png");
});
$("backPngBtn").addEventListener("click",function(){
  sideCanvas("back").toBlob(function(blob){
    if(!blob){setStatus("The PNG could not be built in this browser.",true);return;}
    saveBlob(blob,"business-card-back.png");
    setStatus("Your back PNG is downloading: 1050 by 600 pixels, ready to share.");
    if(window.BBDropStrip)window.BBDropStrip.show('business-card-maker');
  },"image/png");
});
$("pdfBtn").addEventListener("click",async function(){
  var btn=this;btn.disabled=true;var old=btn.textContent;btn.textContent="Building...";
  try{
    var doc=await window.PDFLib.PDFDocument.create();
    var frontImg=await doc.embedPng(await canvasBytes(sideCanvas("front")));
    var page1=doc.addPage([252,144]);
    page1.drawImage(frontImg,{x:0,y:0,width:252,height:144});
    var pages=1;
    if(!state.backBlank){
      var backImg=await doc.embedPng(await canvasBytes(sideCanvas("back")));
      var page2=doc.addPage([252,144]);
      page2.drawImage(backImg,{x:0,y:0,width:252,height:144});
      pages=2;
    }
    var bytes=await doc.save();
    saveBlob(new Blob([bytes],{type:"application/pdf"}),"business-card.pdf");
    setStatus(pages===2?"Your PDF is downloading: two pages, front and back, each exactly 3.5 by 2 inches.":"Your PDF is downloading: one page, exactly 3.5 by 2 inches.");
    if(window.BBDropStrip)window.BBDropStrip.show('business-card-maker');
  }catch(e){setStatus("The PDF could not be built in this browser. The PNG downloads still work.",true);}
  btn.disabled=false;btn.textContent=old;
});
if(document.fonts&&document.fonts.ready)document.fonts.ready.then(render);
render();
})();
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
