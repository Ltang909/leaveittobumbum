<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Invoice Generator | Make and Download an Invoice PDF | Leave It to Bum Bum</title>
<meta name="description" content="Free invoice generator that runs in your browser. Fill in your details, add items, set tax, and download a clean invoice PDF. No sign-up, and your numbers never leave your device.">
<meta property="og:title" content="Invoice Generator | Make and Download an Invoice PDF | Leave It to Bum Bum">
<meta property="og:description" content="Free invoice generator that runs in your browser. Fill in your details, add items, set tax, and download a clean invoice PDF. No sign-up, and your numbers never leave your device.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/invoice-generator/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/icons/invoice-generator.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Invoice Generator | Make and Download an Invoice PDF | Leave It to Bum Bum">
<meta name="twitter:description" content="Free invoice generator that runs in your browser. Fill in your details, add items, set tax, and download a clean invoice PDF. No sign-up, and your numbers never leave your device.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/icons/invoice-generator.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/invoice-generator/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Invoice Generator",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Free invoice generator that runs in your browser. Fill in your details, add items, set tax, and download a clean invoice PDF. No sign-up, and your numbers never leave your device.",
  "url": "https://leaveittobumbum.com/tools/invoice-generator/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Do I need an account?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. The Invoice Generator is free, uses no actions, and needs no sign-up. Fill it in and download the PDF."
      }
    },
    {
      "@type": "Question",
      "name": "Where does my invoice data go?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Nowhere. Everything runs in your browser and the PDF is built on your device. Your business details save in this browser only, so the form is half filled in next time. Nothing is sent to Bum Bum."
      }
    },
    {
      "@type": "Question",
      "name": "Can it handle tax?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. Name the tax whatever you call it, HST, GST, VAT, or sales tax, and set the rate. You can also add a percentage discount. The discount comes off the subtotal first, then tax applies to what is left, and the total updates as you type."
      }
    },
    {
      "@type": "Question",
      "name": "Which currencies does it support?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "US, Canadian, and Australian dollars, euros, and British pounds. Pick yours at the top of the form."
      }
    },
    {
      "@type": "Question",
      "name": "Can I add my logo?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Not yet. The layout is clean and fixed for now, with your business name set large at the top. Logo support is first on the list if people ask for it."
      }
    },
    {
      "@type": "Question",
      "name": "What do I do after I send the invoice?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Send the PDF to your client by email. If it goes unpaid, the Invoice Reminder in this toolbox tracks who owes you what and chases late payers politely for you."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Invoice Generator | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Invoice Generator","item":"https://leaveittobumbum.com/tools/invoice-generator/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use the Invoice Generator","description":"Fill in your details, add items, and download a clean invoice PDF.","step":[{"@type":"HowToStep","position":1,"name":"Fill in your business details and your client's details."},{"@type":"HowToStep","position":2,"name":"Add your items with quantities and rates. Totals update as you type."},{"@type":"HowToStep","position":3,"name":"Set tax or a discount if you use them, and pick a due date."},{"@type":"HowToStep","position":4,"name":"Download the PDF and email it to your client."}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.ig-grid{display:grid;gap:16px;margin-top:14px}
@media(min-width:1020px){.ig-grid{grid-template-columns:450px 1fr;align-items:start}.ig-preview-wrap{position:sticky;top:14px}}
.ig-form label{font-weight:700;font-size:14px;display:block;margin-top:12px}
.ig-form input[type=text],.ig-form input[type=email],.ig-form input[type=number],.ig-form input[type=date],.ig-form select,.ig-form textarea{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px;box-sizing:border-box}
.ig-form input:focus,.ig-form select:focus,.ig-form textarea:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.ig-form textarea{resize:vertical;min-height:56px}
.ig-2col{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.ig-3col{display:grid;grid-template-columns:1.2fr 1fr 1fr;gap:10px}
.ig-section-title{font-family:Fraunces,Georgia,serif;font-size:1.25rem;margin:18px 0 2px}
.ig-items{width:100%;border-collapse:collapse;margin-top:8px}
.ig-items th{font-size:12px;text-align:left;opacity:.65;padding:4px 4px}
.ig-items td{padding:3px 4px;vertical-align:top}
.ig-items input{margin-top:0!important;padding:8px!important}
.ig-items .amt{font-weight:800;text-align:right;white-space:nowrap;padding-top:12px!important}
.ig-items .rm{background:none;border:none;cursor:pointer;font-size:16px;line-height:1;padding:10px 4px;color:#b3261e}
.mini-btn{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:6px 12px;font-weight:700;font-size:13px;cursor:pointer;color:inherit}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:16px;align-items:center}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
.ig-status{font-size:13px;font-weight:700;margin-top:10px;min-height:18px}
.ig-status.error{color:#b3261e}
.ig-note{font-size:13px;opacity:.75;margin-top:8px}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
.sheet{background:#fff;border:1px solid #e2d7bf;border-radius:12px;box-shadow:0 2px 10px rgba(90,72,38,.08);padding:30px 30px 24px;color:#23201a}
.sheet-head{display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap}
.sheet-biz{font-family:Fraunces,Georgia,serif;font-size:1.5rem;font-weight:700;line-height:1.1}
.sheet-meta{font-size:13px;opacity:.75;white-space:pre-line;margin-top:4px}
.sheet-title{font-family:Fraunces,Georgia,serif;font-size:2rem;font-weight:700;text-align:right;line-height:1}
.sheet-numbers{font-size:13px;text-align:right;margin-top:6px}
.sheet-numbers b{display:inline-block;min-width:86px;text-align:left;opacity:.6;font-weight:700}
.sheet-rule{height:5px;background:#ffd84d;border-radius:99px;margin:18px 0}
.sheet-billto{font-size:14px;white-space:pre-line}
.sheet-billto .who{font-weight:900}
.sheet table{width:100%;border-collapse:collapse;margin-top:16px;font-size:14px}
.sheet th{text-align:left;font-size:12px;letter-spacing:.04em;opacity:.6;border-bottom:2px solid #23201a;padding:6px 6px}
.sheet td{border-bottom:1px solid #eee3c8;padding:8px 6px;vertical-align:top}
.sheet .r{text-align:right}
.sheet-totals{margin-left:auto;width:min(280px,100%);margin-top:14px;font-size:14px}
.sheet-totals .row{display:flex;justify-content:space-between;padding:4px 6px}
.sheet-totals .grand{border-top:2px solid #23201a;font-weight:900;font-size:17px;margin-top:4px;padding-top:8px}
.sheet-notes{font-size:13px;opacity:.8;white-space:pre-line;margin-top:22px}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media print{header,footer,.ig-noprint,#formPanel,#faq,#guideSec,.next-tasks,#nextTasks{display:none!important}main.shell{width:100%!important}.sheet{border:none;box-shadow:none;border-radius:0}}
</style></head>
<body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Invoice Generator"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/invoice-generator.png" alt="Invoice Generator"><div><h1>Free Invoice Generator</h1><p class="lede bb-desc">Fill it in. Send it. Get paid. Build a clean invoice right in your browser and download the PDF.</p></div></div>
<section class="panel" id="formPanel">
<div class="ig-grid">
<form class="ig-form" id="invForm" autocomplete="off">
<h2 class="ig-section-title">From you</h2>
<label>Your business name<input type="text" id="fromName" placeholder="e.g. Maple Design Studio"></label>
<label>Your email<input type="email" id="fromEmail" placeholder="you@yourbusiness.com"></label>
<label>Your address<textarea id="fromAddress" placeholder="Street, city, province or state, postal code"></textarea></label>
<h2 class="ig-section-title">Bill to</h2>
<label>Client name<input type="text" id="toName" placeholder="e.g. Northwind Coffee Inc."></label>
<label>Client email<input type="email" id="toEmail" placeholder="accounts@client.com"></label>
<label>Client address<textarea id="toAddress" placeholder="Street, city, province or state, postal code"></textarea></label>
<h2 class="ig-section-title">Invoice details</h2>
<div class="ig-3col">
<div><label>Invoice number<input type="text" id="invNumber" value="INV-0001"></label></div>
<div><label>Issue date<input type="date" id="invIssue"></label></div>
<div><label>Due date<input type="date" id="invDue"></label></div>
</div>
<label>Currency<select id="currency">
<option value="USD">USD, US dollar</option>
<option value="CAD">CAD, Canadian dollar</option>
<option value="EUR">EUR, euro</option>
<option value="GBP">GBP, British pound</option>
<option value="AUD">AUD, Australian dollar</option>
</select></label>
<h2 class="ig-section-title">Items</h2>
<table class="ig-items"><thead><tr><th style="width:46%">Description</th><th style="width:14%">Qty</th><th style="width:20%">Rate</th><th style="width:14%;text-align:right">Amount</th><th></th></tr></thead><tbody id="itemsBody"></tbody></table>
<button type="button" class="mini-btn" id="addItemBtn">+ Add an item</button>
<h2 class="ig-section-title">Tax and discount</h2>
<div class="ig-3col">
<div><label>Tax name<input type="text" id="taxLabel" value="Tax"></label></div>
<div><label>Tax rate %<input type="number" id="taxRate" min="0" step="0.01" value="0"></label></div>
<div><label>Discount %<input type="number" id="discountRate" min="0" step="0.01" value="0"></label></div>
</div>
<label>Notes on the invoice<textarea id="notes">Payment is due by the due date above. Thank you for your business.</textarea></label>
<div class="btnrow">
<button type="button" class="button" id="downloadBtn">Download PDF</button>
<button type="button" class="button secondary" id="printBtn">Print</button>
<button type="button" class="mini-btn" id="clearBtn">Start over</button>
</div>
<p class="ig-status" id="statusLine" role="status"></p>
<p class="ig-note">Your business details save in this browser only, so the form is half filled in next time. Client details and items are never saved or sent anywhere.</p>
</form>
<div class="ig-preview-wrap">
<div class="sheet" id="invoiceSheet">
<div class="sheet-head">
<div><div class="sheet-biz" id="pvFromName">Your Business Name</div><div class="sheet-meta" id="pvFromMeta"></div></div>
<div><div class="sheet-title">INVOICE</div><div class="sheet-numbers">
<div><b>Number</b> <span id="pvNumber">INV-0001</span></div>
<div><b>Issued</b> <span id="pvIssue"></span></div>
<div><b>Due</b> <span id="pvDue"></span></div>
</div></div>
</div>
<div class="sheet-rule"></div>
<div class="sheet-billto"><span style="opacity:.6;font-size:12px;font-weight:800;letter-spacing:.04em">BILL TO</span><br><span class="who" id="pvToName">Client Name</span><div id="pvToMeta"></div></div>
<table><thead><tr><th style="width:52%">Description</th><th class="r" style="width:12%">Qty</th><th class="r" style="width:16%">Rate</th><th class="r" style="width:20%">Amount</th></tr></thead><tbody id="pvItems"></tbody></table>
<div class="sheet-totals">
<div class="row"><span>Subtotal</span><span id="pvSubtotal"></span></div>
<div class="row" id="pvDiscountRow" style="display:none"><span id="pvDiscountLabel">Discount</span><span id="pvDiscount"></span></div>
<div class="row" id="pvTaxRow" style="display:none"><span id="pvTaxLabel">Tax</span><span id="pvTax"></span></div>
<div class="row grand"><span>Total due</span><span id="pvTotal"></span></div>
</div>
<div class="sheet-notes" id="pvNotes"></div>
</div>
</div>
</div>
</section>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2>Questions, answered</h2>
<details><summary>Do I need an account?</summary><p>No. The Invoice Generator is free, uses no actions, and needs no sign-up. Fill it in and download the PDF.</p></details>
<details><summary>Where does my invoice data go?</summary><p>Nowhere. Everything runs in your browser and the PDF is built on your device. Your business details save in this browser only, so the form is half filled in next time. Nothing is sent to Bum Bum.</p></details>
<details><summary>Can it handle tax?</summary><p>Yes. Name the tax whatever you call it, HST, GST, VAT, or sales tax, and set the rate. You can also add a percentage discount. The discount comes off the subtotal first, then tax applies to what is left, and the total updates as you type.</p></details>
<details><summary>Which currencies does it support?</summary><p>US, Canadian, and Australian dollars, euros, and British pounds. Pick yours at the top of the form.</p></details>
<details><summary>Can I add my logo?</summary><p>Not yet. The layout is clean and fixed for now, with your business name set large at the top. Logo support is first on the list if people ask for it.</p></details>
<details><summary>What do I do after I send the invoice?</summary><p>Send the PDF to your client by email. If it goes unpaid, the <a href="/tools/invoice-chaser/">Invoice Reminder</a> in this toolbox tracks who owes you what and chases late payers politely for you.</p></details>
</section>
<section class="panel" id="guideSec" aria-label="Guide">
<h2>Invoice Generator, the short guide</h2>
<p>This one is for freelancers, consultants, tradespeople, and sellers who bill by email and want the paperwork finished in minutes. Type your details on the left and the invoice builds itself on the right, totals included. When it looks right, download the PDF and send it.</p>
<p>The math runs in a fixed order. Each item line multiplies quantity by rate. The discount, if you set one, comes off the subtotal. Tax then applies to the amount that is left. That matches how most invoices are written, and the preview shows every step so a client can follow it too.</p>
<p>Your business name, email, address, currency, and tax settings save in this browser, on this device only. Next time you bill someone, that half of the form is already done. Client details and items are never saved, and nothing about any invoice is sent to Bum Bum.</p>
<p>Made the invoice? The <a href="/tools/invoice-chaser/">Invoice Reminder</a> picks up from there. It tracks due dates and nudges late payers so you do not have to write the awkward email yourself.</p>
</section>
<?php $bbTool = 'invoice-generator'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
</main><?php require dirname(__DIR__, 2) . '/includes/site-footer.php'; ?>
<script src="/tools/pdf-merger/vendor/pdf-lib.min.js"></script>
<script>
(function(){
"use strict";
var $=function(id){return document.getElementById(id);};
var LS_KEY="bbInvGen1";
function pad(n){return (n<10?"0":"")+n;}
function isoLocal(d){return d.getFullYear()+"-"+pad(d.getMonth()+1)+"-"+pad(d.getDate());}
$("invIssue").value=isoLocal(new Date());
$("invDue").value=isoLocal(new Date(Date.now()+14*864e5));
try{var saved=JSON.parse(localStorage.getItem(LS_KEY)||"null");
if(saved){["fromName","fromEmail","fromAddress","currency","taxLabel","taxRate"].forEach(function(id){if(saved[id]!==undefined&&saved[id]!=="")$(id).value=saved[id];});}}catch(e){}
function num(v){var n=parseFloat(v);return isFinite(n)?n:0;}
function cur(){return $("currency").value;}
function money(n){try{return new Intl.NumberFormat("en-US",{style:"currency",currency:cur()}).format(n);}catch(e){return n.toFixed(2);}}
function fmtDate(iso){if(!iso)return"";var p=iso.split("-");if(p.length!==3)return iso;var d=new Date(+p[0],+p[1]-1,+p[2]);return d.toLocaleDateString("en-US",{year:"numeric",month:"short",day:"numeric"});}
function linesOf(id){return $(id).value.split("\n").map(function(s){return s.trim();}).filter(Boolean);}
function addItem(desc,qty,rate){
  var tr=document.createElement("tr");
  tr.innerHTML='<td><input type="text" class="it-desc" placeholder="What the work was"></td><td><input type="number" class="it-qty" min="0" step="any"></td><td><input type="number" class="it-rate" min="0" step="any"></td><td class="amt"></td><td><button type="button" class="rm" aria-label="Remove item">&times;</button></td>';
  tr.querySelector(".it-desc").value=desc||"";
  tr.querySelector(".it-qty").value=qty===undefined?1:qty;
  tr.querySelector(".it-rate").value=rate||"";
  tr.querySelector(".rm").addEventListener("click",function(){tr.remove();if(!$("itemsBody").children.length)addItem("",1,"");render();});
  $("itemsBody").appendChild(tr);
}
function collectItems(){
  var out=[];
  Array.prototype.forEach.call($("itemsBody").children,function(tr){
    var desc=tr.querySelector(".it-desc").value.trim();
    var qty=num(tr.querySelector(".it-qty").value);
    var rate=num(tr.querySelector(".it-rate").value);
    tr.querySelector(".amt").textContent=(qty||rate||desc)?money(qty*rate):"";
    out.push({desc:desc,qty:qty,rate:rate,amount:qty*rate});
  });
  return out;
}
function totals(items){
  var sub=items.reduce(function(s,i){return s+i.amount;},0);
  var discRate=num($("discountRate").value);
  var disc=sub*discRate/100;
  var taxRate=num($("taxRate").value);
  var tax=(sub-disc)*taxRate/100;
  return {sub:sub,discRate:discRate,disc:disc,taxRate:taxRate,tax:tax,total:sub-disc+tax};
}
function setText(id,txt){$(id).textContent=txt;}
function render(){
  var items=collectItems();var t=totals(items);
  setText("pvFromName",$("fromName").value.trim()||"Your Business Name");
  var fromMeta=[];if($("fromEmail").value.trim())fromMeta.push($("fromEmail").value.trim());fromMeta=fromMeta.concat(linesOf("fromAddress"));
  setText("pvFromMeta",fromMeta.join("\n"));
  setText("pvToName",$("toName").value.trim()||"Client Name");
  var toMeta=[];if($("toEmail").value.trim())toMeta.push($("toEmail").value.trim());toMeta=toMeta.concat(linesOf("toAddress"));
  setText("pvToMeta",toMeta.join("\n"));
  setText("pvNumber",$("invNumber").value.trim()||"INV-0001");
  setText("pvIssue",fmtDate($("invIssue").value));
  setText("pvDue",fmtDate($("invDue").value));
  var body=$("pvItems");body.textContent="";
  items.forEach(function(i){
    if(!i.desc&&!i.qty&&!i.rate)return;
    var tr=document.createElement("tr");
    [i.desc||"Item",String(i.qty||0),money(i.rate),money(i.amount)].forEach(function(v,ix){
      var td=document.createElement("td");td.textContent=v;if(ix>0)td.className="r";tr.appendChild(td);});
    body.appendChild(tr);
  });
  if(!body.children.length){var tr=document.createElement("tr");var td=document.createElement("td");td.colSpan=4;td.textContent="Your items show up here as you add them.";td.style.opacity=".6";tr.appendChild(td);body.appendChild(tr);}
  setText("pvSubtotal",money(t.sub));
  $("pvDiscountRow").style.display=t.disc>0?"":"none";
  setText("pvDiscountLabel","Discount ("+t.discRate+"%)");setText("pvDiscount","-"+money(t.disc));
  $("pvTaxRow").style.display=t.taxRate>0?"":"none";
  setText("pvTaxLabel",($("taxLabel").value.trim()||"Tax")+" ("+t.taxRate+"%)");setText("pvTax",money(t.tax));
  setText("pvTotal",money(t.total));
  setText("pvNotes",$("notes").value.trim());
  try{localStorage.setItem(LS_KEY,JSON.stringify({fromName:$("fromName").value,fromEmail:$("fromEmail").value,fromAddress:$("fromAddress").value,currency:cur(),taxLabel:$("taxLabel").value,taxRate:$("taxRate").value}));}catch(e){}
}
$("invForm").addEventListener("input",render);
$("currency").addEventListener("change",render);
$("addItemBtn").addEventListener("click",function(){addItem("",1,"");render();});
$("clearBtn").addEventListener("click",function(){
  ["toName","toEmail","toAddress"].forEach(function(id){$(id).value="";});
  $("itemsBody").textContent="";addItem("",1,"");
  $("discountRate").value="0";
  var n=$("invNumber").value;var m=n.match(/(\d+)\s*$/);if(m)$("invNumber").value=n.slice(0,n.length-m[1].length)+String(parseInt(m[1],10)+1).padStart(m[1].length,"0");
  $("invIssue").value=isoLocal(new Date());$("invDue").value=isoLocal(new Date(Date.now()+14*864e5));
  setStatus("Cleared. Ready for the next invoice.");render();
});
function setStatus(msg,isErr){var s=$("statusLine");s.textContent=msg||"";s.className="ig-status"+(isErr?" error":"");}
$("printBtn").addEventListener("click",function(){window.print();});
function wrapText(text,font,size,maxW){
  var words=String(text).split(/\s+/).filter(Boolean);var lines=[];var line="";
  words.forEach(function(w){var test=line?line+" "+w:w;
    if(font.widthOfTextAtSize(test,size)<=maxW){line=test;}
    else{if(line)lines.push(line);line=w;}});
  if(line)lines.push(line);return lines.length?lines:[""];
}
$("downloadBtn").addEventListener("click",async function(){
  var btn=$("downloadBtn");
  if(!window.PDFLib){setStatus("The PDF maker did not load. Check your connection and try again, or use Print.",true);return;}
  btn.disabled=true;var old=btn.textContent;btn.textContent="Building your PDF...";setStatus("");
  try{
    var items=collectItems().filter(function(i){return i.desc||i.qty||i.rate;});
    var t=totals(items);
    var PDFDocument=window.PDFLib.PDFDocument,StandardFonts=window.PDFLib.StandardFonts,rgb=window.PDFLib.rgb;
    var doc=await PDFDocument.create();
    var font=await doc.embedFont(StandardFonts.Helvetica);
    var bold=await doc.embedFont(StandardFonts.HelveticaBold);
    var ink=rgb(0.137,0.125,0.102),gray=rgb(0.45,0.43,0.40),yellow=rgb(1,0.847,0.302),hair=rgb(0.90,0.86,0.74);
    var M=54,W=612,H=792,CW=W-2*M;
    var page=doc.addPage([W,H]);var y=H-M;
    function right(txt,xRight,yy,o){var w=o.font.widthOfTextAtSize(txt,o.size);page.drawText(txt,{x:xRight-w,y:yy,size:o.size,font:o.font,color:o.color||ink});}
    function left(txt,x,yy,o){page.drawText(txt,{x:x,y:yy,size:o.size,font:o.font,color:o.color||ink});}
    var bizLines=wrapText($("fromName").value.trim()||"Your Business Name",bold,19,300);
    bizLines.forEach(function(l){left(l,M,y,{font:bold,size:19});y-=23;});
    var metaFrom=[];if($("fromEmail").value.trim())metaFrom.push($("fromEmail").value.trim());metaFrom=metaFrom.concat(linesOf("fromAddress"));
    metaFrom.forEach(function(l){wrapText(l,font,10,300).forEach(function(wl){left(wl,M,y,{font:font,size:10,color:gray});y-=13;});});
    var ry=H-M;
    right("INVOICE",W-M,ry,{font:bold,size:27});ry-=26;
    [["Number",$("invNumber").value.trim()||"INV-0001"],["Issued",fmtDate($("invIssue").value)],["Due",fmtDate($("invDue").value)]].forEach(function(pair){
      var labelW=bold.widthOfTextAtSize(pair[0],10);
      page.drawText(pair[0],{x:W-M-150,y:ry,size:10,font:bold,color:gray});
      right(pair[1],W-M,ry,{font:font,size:10});ry-=14;});
    y=Math.min(y,ry)-8;
    page.drawRectangle({x:M,y:y,width:CW,height:4,color:yellow});y-=30;
    left("BILL TO",M,y,{font:bold,size:8.5,color:gray});y-=15;
    left($("toName").value.trim()||"Client Name",M,y,{font:bold,size:12});y-=15;
    var metaTo=[];if($("toEmail").value.trim())metaTo.push($("toEmail").value.trim());metaTo=metaTo.concat(linesOf("toAddress"));
    metaTo.forEach(function(l){wrapText(l,font,10,320).forEach(function(wl){left(wl,M,y,{font:font,size:10});y-=13;});});
    y-=14;
    function tableHeader(){
      left("DESCRIPTION",M,y,{font:bold,size:9,color:gray});
      right("QTY",M+380,y,{font:bold,size:9,color:gray});
      right("RATE",M+445,y,{font:bold,size:9,color:gray});
      right("AMOUNT",W-M,y,{font:bold,size:9,color:gray});
      y-=6;page.drawLine({start:{x:M,y:y},end:{x:W-M,y:y},thickness:1.6,color:ink});y-=16;
    }
    tableHeader();
    items.forEach(function(i){
      var dlines=wrapText(i.desc||"Item",font,10.5,290);
      var rowH=Math.max(dlines.length*13,15);
      if(y-rowH<170){page=doc.addPage([W,H]);y=H-M;tableHeader();}
      dlines.forEach(function(l,ix){left(l,M,y-ix*13,{font:font,size:10.5});});
      right(String(i.qty||0),M+380,y,{font:font,size:10.5});
      right(money(i.rate),M+445,y,{font:font,size:10.5});
      right(money(i.amount),W-M,y,{font:bold,size:10.5});
      y-=rowH+4;
      page.drawLine({start:{x:M,y:y+2},end:{x:W-M,y:y+2},thickness:0.6,color:hair});
      y-=8;
    });
    y-=6;
    var lx=M+320;
    function totalRow(label,value,o){o=o||{};left(label,lx,y,{font:o.font||font,size:o.size||11});right(value,W-M,y,{font:o.font||font,size:o.size||11});y-=(o.gap||16);}
    totalRow("Subtotal",money(t.sub));
    if(t.disc>0)totalRow("Discount ("+t.discRate+"%)","-"+money(t.disc));
    if(t.taxRate>0)totalRow(($("taxLabel").value.trim()||"Tax")+" ("+t.taxRate+"%)",money(t.tax));
    page.drawLine({start:{x:lx,y:y+8},end:{x:W-M,y:y+8},thickness:1.6,color:ink});
    totalRow("Total due",money(t.total),{font:bold,size:13,gap:20});
    var notes=$("notes").value.trim();
    if(notes){y-=8;left("Notes",M,y,{font:bold,size:9,color:gray});y-=14;
      notes.split("\n").forEach(function(p){wrapText(p,font,10,320).forEach(function(l){left(l,M,y,{font:font,size:10});y-=13;});});}
    var pages=doc.getPages();var last=pages[pages.length-1];
    var foot="Made with the free Invoice Generator at leaveittobumbum.com";
    last.drawText(foot,{x:(W-font.widthOfTextAtSize(foot,8))/2,y:34,size:8,font:font,color:gray});
    var bytes=await doc.save();
    var blob=new Blob([bytes],{type:"application/pdf"});
    var a=document.createElement("a");a.href=URL.createObjectURL(blob);
    a.download="invoice-"+(($("invNumber").value.trim()||"draft").replace(/[^a-z0-9-_]+/gi,"-"))+".pdf";
    document.body.appendChild(a);a.click();a.remove();
    setTimeout(function(){URL.revokeObjectURL(a.href);},4000);
    setStatus("Downloaded. Send it to your client, then let the Invoice Reminder watch the due date.");
  }catch(err){
    setStatus("The PDF could not be built. Unusual characters can trip it up. Try Print instead; it uses the preview above.",true);
  }
  btn.disabled=false;btn.textContent=old;
});
addItem("",1,"");
render();
})();
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261030r1"></script></body></html>
