<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Email Signature Maker | Build a Professional Email Signature | Leave It to Bum Bum</title>
<meta name="description" content="Build a professional email signature in your browser. Fill in your details, pick a layout, copy it into Gmail or Outlook. Nothing is uploaded. Free to try.">
<meta property="og:title" content="Email Signature Maker | Build a Professional Email Signature | Leave It to Bum Bum">
<meta property="og:description" content="Fill in your details, pick a layout, copy a professional email signature into Gmail or Outlook. Built in your browser, nothing uploaded.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/email-signature-generator/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/icons/email-signature-generator-sticker.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Email Signature Maker | Leave It to Bum Bum">
<meta name="twitter:description" content="Fill in your details, pick a layout, copy a professional email signature into Gmail or Outlook. Built in your browser, nothing uploaded.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/icons/email-signature-generator-sticker.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/email-signature-generator/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Email Signature Maker",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Build a professional email signature in your browser. Fill in your details, pick a layout, copy it into Gmail or Outlook. Nothing is uploaded. Free to try.",
  "url": "https://leaveittobumbum.com/tools/email-signature-generator/"
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
        "text": "No. Designing your signature is free and needs no account. Copying or downloading the finished signature uses one action: 15 free as a guest, 75 every month with a free account. Tweaking and copying again in the same visit is free. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "Does my information leave my browser?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. The signature is built entirely on your device. Your details are not uploaded or stored. If you add a photo, it loads from the link you gave."
      }
    },
    {
      "@type": "Question",
      "name": "Why does the photo need a link?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Email apps cannot carry image files inside a pasted signature. Your photo has to already live at a public web address, like your website or LinkedIn image link, and the signature points at it."
      }
    },
    {
      "@type": "Question",
      "name": "Will it work in Gmail and Outlook?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. The signature is plain table-based HTML, the format email apps expect. The copy button pastes it in formatted, ready to save in Gmail, Outlook, or Apple Mail settings."
      }
    },
    {
      "@type": "Question",
      "name": "Can I change my signature later?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Any time. The form remembers your details on this device, so updating a title or phone number takes seconds. Paste the new version over the old one in your mail settings."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Email Signature Maker | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Email Signature Maker","item":"https://leaveittobumbum.com/tools/email-signature-generator/"}]}</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.sig-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;align-items:start;margin-top:20px}
.sig-card{background:#fff;border:2px solid var(--line);border-radius:14px;padding:16px}
.sig-card h2{margin:0 0 12px;font-size:18px}
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
.sig-preview-wrap{position:sticky;top:16px}
.sig-preview{background:#fff;border:2px solid var(--ink);border-radius:14px;padding:20px;box-shadow:4px 4px 0 var(--ink);overflow-x:auto}
.export-row{display:flex;gap:10px;flex-wrap:wrap;margin-top:14px}
.export-row .button{min-height:48px}
.hint{font-size:13px;opacity:.7;margin:10px 0 0}
.guide-grid{display:block}
.guide-row{display:grid;grid-template-columns:130px 1fr;gap:4px 16px;padding:14px 0;border-top:1px solid var(--line)}
.guide-row:first-child{padding-top:2px;border-top:0}
.guide-row h3{margin:0;font-size:16px}
.guide-row ol{margin:0;padding-left:20px}
.guide-row li{margin-bottom:4px}
@media(max-width:560px){.guide-row{grid-template-columns:1fr}}
@media(max-width:900px){.sig-grid{grid-template-columns:1fr}.sig-preview-wrap{position:static}}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Email Signature Maker"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/icons/email-signature-generator-sticker.png" alt="Email Signature Maker"><h1>Sign off like a pro.</h1><p class="lede bb-desc">Email Signature Maker builds a clean, professional email signature right in your browser. Fill in your details, pick a layout, and copy it straight into Gmail or Outlook. No signature subscription, and nothing you type leaves your device.</p>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/email-signature-generator/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<div class="sig-grid">
<form class="sig-card" id="sigForm" autocomplete="off">
<h2>Your details</h2>
<p class="grp">Basics</p>
<div class="fld"><label for="fName">Full name</label><input id="fName" type="text" placeholder="Jane Appleseed"></div>
<div class="fld"><label for="fTitle">Job title</label><input id="fTitle" type="text" placeholder="Founder"></div>
<div class="fld"><label for="fCompany">Company</label><input id="fCompany" type="text" placeholder="Appleseed Studio"></div>
<p class="grp">Contact</p>
<div class="fld"><label for="fEmail">Email</label><input id="fEmail" type="email" placeholder="jane@appleseed.co"></div>
<div class="fld"><label for="fPhone">Phone</label><input id="fPhone" type="tel" placeholder="416-555-0134"></div>
<div class="fld"><label for="fWebsite">Website</label><input id="fWebsite" type="text" placeholder="appleseed.co"></div>
<div class="fld"><label for="fPhoto">Photo or logo link (optional)</label><input id="fPhoto" type="url" placeholder="https://appleseed.co/me.jpg"><p class="note">Email signatures cannot carry image files, so the photo needs a public link. Used by the Photo layout.</p></div>
<p class="grp">Social links (optional)</p>
<div class="fld"><label for="fLinkedin">LinkedIn</label><input id="fLinkedin" type="url" placeholder="https://www.linkedin.com/in/jane"></div>
<div class="fld"><label for="fInstagram">Instagram</label><input id="fInstagram" type="url" placeholder="https://www.instagram.com/jane"></div>
<div class="fld"><label for="fX">X</label><input id="fX" type="url" placeholder="https://x.com/jane"></div>
<div class="fld"><label for="fFacebook">Facebook</label><input id="fFacebook" type="url" placeholder="https://www.facebook.com/jane"></div>
<div class="fld"><label for="fYoutube">YouTube</label><input id="fYoutube" type="url" placeholder="https://www.youtube.com/@jane"></div>
<div class="fld"><label for="fGithub">GitHub</label><input id="fGithub" type="url" placeholder="https://github.com/jane"></div>
<p class="grp">Layout</p>
<div class="seg" role="group" aria-label="Signature layout"><button type="button" data-tpl="classic" class="on">Classic</button><button type="button" data-tpl="compact">Compact</button><button type="button" data-tpl="sidebar">Sidebar</button><button type="button" data-tpl="photo">Photo</button></div>
<p class="grp">Accent color</p>
<div class="color-row"><button type="button" class="swatch on" data-color="#b3541e" style="background:#b3541e" aria-label="Rust"></button><button type="button" class="swatch" data-color="#2f6fed" style="background:#2f6fed" aria-label="Blue"></button><button type="button" class="swatch" data-color="#2f9e5f" style="background:#2f9e5f" aria-label="Green"></button><button type="button" class="swatch" data-color="#8e4ec6" style="background:#8e4ec6" aria-label="Purple"></button><button type="button" class="swatch" data-color="#1a1a1a" style="background:#1a1a1a" aria-label="Black"></button><input type="color" class="pick" id="customColor" value="#b3541e" aria-label="Custom color"></div>
</form>
<div class="sig-preview-wrap">
<h2 style="margin:0 0 12px;font-size:18px">Preview</h2>
<div class="sig-preview"><div id="sigPreview"></div></div>
<div class="export-row"><button type="button" class="button" id="copyBtn">Copy signature</button><button type="button" class="button secondary" id="dlBtn">Download HTML</button><button type="button" class="button secondary" id="codeBtn">Copy HTML code</button></div>
<p class="hint" id="sigStatus">Designing is free. Your first copy or download in a visit uses 1 action.</p>
</div>
</div>
<div id="upgrade-slot"></div>
<section class="panel" aria-label="Guide">
<h2 style="margin-top:0">Put it in your email</h2>
<div class="guide-grid">
<div class="guide-row"><h3>Gmail</h3><ol><li>Open Gmail and click the gear icon, then See all settings.</li><li>On the General tab, scroll to Signature and click Create new.</li><li>Click in the signature box and paste.</li><li>Scroll down and Save Changes.</li></ol></div>
<div class="guide-row"><h3>Outlook</h3><ol><li>Open Settings, then Mail, then Compose and reply.</li><li>Click New signature and paste into the box.</li><li>Pick it as the default for new messages.</li><li>Save.</li></ol></div>
<div class="guide-row"><h3>Apple Mail</h3><ol><li>Open Settings, then Signatures.</li><li>Pick your account and click + to add one.</li><li>Paste into the signature box.</li><li>If the fonts shift, untick Always match my default message font.</li></ol></div>
</div>
</section>
<script>
const FIELDS=['fName','fTitle','fCompany','fEmail','fPhone','fWebsite','fPhoto','fLinkedin','fInstagram','fX','fFacebook','fYoutube','fGithub'];
let tpl='classic',accent='#b3541e',exported=false;
function esc(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;')}
function val(id){const el=document.querySelector('#'+id);return el?el.value.trim():''}
function siteDisplay(u){return u.replace(/^https?:\/\//i,'').replace(/\/+$/,'')}
function siteHref(u){return /^https?:\/\//i.test(u)?u:'https://'+u}
function socials(){const out=[];const map=[['fLinkedin','LinkedIn','linkedin'],['fInstagram','Instagram','instagram'],['fX','X','x'],['fFacebook','Facebook','facebook'],['fYoutube','YouTube','youtube'],['fGithub','GitHub','github']];for(const[id,label,slug]of map){const u=val(id);if(u)out.push([label,siteHref(u),slug])}return out}
function socialsRow(d,preview){
  const base=(preview?location.origin:'https://leaveittobumbum.com')+'/bum/icons/social/';
  return '<table cellpadding="0" cellspacing="0" border="0"><tr>'+d.socials.map(x=>'<td style="padding-right:6px;"><a href="'+esc(x[1])+'"><img src="'+base+x[2]+'.png" width="20" height="20" alt="'+esc(x[0])+'" style="display:block;border:0;"></a></td>').join('')+'</tr></table>';
}
function data(preview){
  const ph=(v,f)=>preview&&!v?'<span style="opacity:.45">'+esc(f)+'</span>':esc(v);
  return {
    name:val('fName'),nameHtml:ph(val('fName'),'Your Name'),
    title:val('fTitle'),titleHtml:ph(val('fTitle'),'Job Title'),
    company:val('fCompany'),companyHtml:ph(val('fCompany'),'Company'),
    email:val('fEmail'),phone:val('fPhone'),website:val('fWebsite'),photo:val('fPhoto'),
    socials:socials()
  };
}
function roleLine(d,preview){
  const parts=[];
  if(d.title||preview)parts.push(d.titleHtml);
  if(d.company||preview)parts.push(d.companyHtml);
  return parts.join(' <span style="color:#999999;">&middot;</span> ');
}
function contactRows(d,withDivider,preview){
  let h='';
  if(withDivider)h+='<tr><td style="padding-top:9px;"><table cellpadding="0" cellspacing="0" border="0" width="56"><tr><td style="border-top:3px solid '+accent+';font-size:0;line-height:0;">&nbsp;</td></tr></table></td></tr>';
  if(d.email)h+='<tr><td style="font-size:13px;color:#222222;padding-top:'+(withDivider?8:6)+'px;"><a href="mailto:'+esc(d.email)+'" style="color:'+accent+';text-decoration:none;">'+esc(d.email)+'</a></td></tr>';
  if(d.phone)h+='<tr><td style="font-size:13px;color:#222222;padding-top:2px;"><a href="tel:'+esc(d.phone.replace(/[^0-9+]/g,''))+'" style="color:#222222;text-decoration:none;">'+esc(d.phone)+'</a></td></tr>';
  if(d.website)h+='<tr><td style="font-size:13px;color:#222222;padding-top:2px;"><a href="'+esc(siteHref(d.website))+'" style="color:'+accent+';text-decoration:none;">'+esc(siteDisplay(d.website))+'</a></td></tr>';
  if(d.socials.length)h+='<tr><td style="padding-top:8px;">'+socialsRow(d,preview)+'</td></tr>';
  return h;
}
function buildSignature(preview){
  const d=data(preview);
  const F='font-family:Arial,Helvetica,sans-serif;';
  const nameRow='<tr><td style="'+F+'font-size:16px;font-weight:bold;color:#1a1a1a;">'+d.nameHtml+'</td></tr>';
  const role=roleLine(d,preview);
  const roleRow=role?'<tr><td style="'+F+'font-size:13px;color:#555555;padding-top:2px;">'+role+'</td></tr>':'';
  if(tpl==='compact'){
    const bits=[];
    if(d.email)bits.push('<a href="mailto:'+esc(d.email)+'" style="color:'+accent+';text-decoration:none;">'+esc(d.email)+'</a>');
    if(d.phone)bits.push('<a href="tel:'+esc(d.phone.replace(/[^0-9+]/g,''))+'" style="color:#222222;text-decoration:none;">'+esc(d.phone)+'</a>');
    if(d.website)bits.push('<a href="'+esc(siteHref(d.website))+'" style="color:'+accent+';text-decoration:none;">'+esc(siteDisplay(d.website))+'</a>');
    let h='<table cellpadding="0" cellspacing="0" border="0"><tr><td style="'+F+'font-size:14px;color:#1a1a1a;"><b>'+d.nameHtml+'</b>'+(role?' <span style="color:#555555;">'+role+'</span>':'')+'</td></tr>';
    if(bits.length)h+='<tr><td style="'+F+'font-size:13px;color:#222222;padding-top:3px;">'+bits.join(' <span style="color:#999999;">&middot;</span> ')+'</td></tr>';
    if(d.socials.length)h+='<tr><td style="padding-top:5px;">'+socialsRow(d,preview)+'</td></tr>';
    return h+'</table>';
  }
  if(tpl==='sidebar'){
    return '<table cellpadding="0" cellspacing="0" border="0"><tr><td style="border-left:4px solid '+accent+';padding-left:12px;"><table cellpadding="0" cellspacing="0" border="0">'+nameRow+roleRow+contactRows(d,false,preview)+'</table></td></tr></table>';
  }
  if(tpl==='photo'){
    const inner='<table cellpadding="0" cellspacing="0" border="0"><tr><td style="'+F+'font-size:17px;font-weight:bold;color:#1a1a1a;">'+d.nameHtml+'</td></tr>'+roleRow+contactRows(d,true,preview)+'</table>';
    if(d.photo)return '<table cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right:14px;vertical-align:top;"><img src="'+esc(d.photo)+'" width="76" height="76" alt="" style="border-radius:50%;display:block;"></td><td style="vertical-align:top;">'+inner+'</td></tr></table>';
    return inner;
  }
  return '<table cellpadding="0" cellspacing="0" border="0">'+nameRow+roleRow+contactRows(d,true,preview)+'</table>';
}
function buildText(){
  const d=data(false);
  const lines=[d.name];
  const role=[d.title,d.company].filter(Boolean).join(' · ');
  if(role)lines.push(role);
  if(d.email)lines.push(d.email);
  if(d.phone)lines.push(d.phone);
  if(d.website)lines.push(siteDisplay(d.website));
  for(const s of d.socials)lines.push(s[0]+': '+s[1]);
  return lines.join('\n');
}
function render(){
  document.querySelector('#sigPreview').innerHTML=buildSignature(true);
  saveLocal();
}
function saveLocal(){
  try{const o={tpl:tpl,accent:accent};for(const id of FIELDS)o[id]=val(id);localStorage.setItem('bbSigMaker',JSON.stringify(o))}catch(e){}
}
function loadLocal(){
  try{const o=JSON.parse(localStorage.getItem('bbSigMaker')||'null');if(!o)return;
    for(const id of FIELDS){if(o[id])document.querySelector('#'+id).value=o[id]}
    if(o.tpl){tpl=o.tpl;document.querySelectorAll('[data-tpl]').forEach(b=>b.classList.toggle('on',b.dataset.tpl===tpl))}
    if(o.accent){accent=o.accent;document.querySelector('#customColor').value=accent;document.querySelectorAll('.swatch').forEach(b=>b.classList.toggle('on',b.dataset.color===accent))}
  }catch(e){}
}
async function spendSignatureAction(){
  if(exported)return true;
  try{
    const s=await fetch('/api/session.php').then(r=>r.json());
    const key='email-signature-generator-'+Date.now()+'-'+Math.random().toString(36).slice(2,10);
    const res=await fetch('/api/tools/email-signature-generator.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({csrf:s.csrf,idempotencyKey:key})});
    const respData=await res.json().catch(()=>({}));
    if(res.status===402){
      if(respData&&respData.signup_required)return false;
      document.querySelector('#upgrade-slot').innerHTML='<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">Copying or downloading your signature uses one action. Helper gives you 1,500 actions for $12/month.</p><p><a class="button" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" href="/account/">See your usage</a></p></div>';
      return false;
    }
    if(!res.ok){alert(respData.error||'Could not save. Try again.');return false}
    exported=true;
    if(respData&&respData.usage)document.dispatchEvent(new CustomEvent('bb:usage',{detail:respData.usage}));
    if(window.BBDropStrip)window.BBDropStrip.show('email-signature-generator');
    return true;
  }catch(e){alert('Could not reach Bum Bum. Check your connection and try again.');return false}
}
function setStatus(msg){document.querySelector('#sigStatus').textContent=msg}
async function guardExport(){
  if(!val('fName')){alert('Add your name first, it is the one line every signature needs.');document.querySelector('#fName').focus();return false}
  return spendSignatureAction();
}
function fallbackCopy(){
  const node=document.querySelector('#sigPreview');
  const range=document.createRange();range.selectNodeContents(node);
  const sel=window.getSelection();sel.removeAllRanges();sel.addRange(range);
  let ok=false;try{ok=document.execCommand('copy')}catch(e){}
  sel.removeAllRanges();return ok;
}
document.querySelector('#copyBtn').addEventListener('click',async()=>{
  if(!await guardExport())return;
  const html=buildSignature(false);
  let ok=false;
  try{
    await navigator.clipboard.write([new ClipboardItem({'text/html':new Blob([html],{type:'text/html'}),'text/plain':new Blob([buildText()],{type:'text/plain'})})]);
    ok=true;
  }catch(e){ok=fallbackCopy()}
  if(ok){setStatus('Copied. Now paste it into your email settings using the steps below.');try{bbTrack('signature_export',{how:'copy'})}catch(e){}}
  else setStatus('Copy did not work in this browser. Use Copy HTML code or Download HTML instead.');
});
document.querySelector('#codeBtn').addEventListener('click',async()=>{
  if(!await guardExport())return;
  try{await navigator.clipboard.writeText(buildSignature(false));setStatus('HTML code copied. Paste it wherever your mail app asks for signature HTML.');try{bbTrack('signature_export',{how:'code'})}catch(e){}}
  catch(e){setStatus('Copy did not work in this browser. Use Download HTML instead.')}
});
document.querySelector('#dlBtn').addEventListener('click',async()=>{
  if(!await guardExport())return;
  const blob=new Blob([buildSignature(false)],{type:'text/html'});
  const url=URL.createObjectURL(blob);
  const a=document.createElement('a');a.href=url;a.download='email-signature-generator.html';document.body.appendChild(a);a.click();a.remove();
  setTimeout(()=>URL.revokeObjectURL(url),4000);
  setStatus('Downloaded. Open the file, copy what you see, and paste it into your email settings.');
  try{bbTrack('signature_export',{how:'download'})}catch(e){}
});
FIELDS.forEach(id=>document.querySelector('#'+id).addEventListener('input',render));
document.querySelectorAll('[data-tpl]').forEach(b=>b.addEventListener('click',()=>{document.querySelectorAll('[data-tpl]').forEach(x=>x.classList.remove('on'));b.classList.add('on');tpl=b.dataset.tpl;render()}));
document.querySelectorAll('.swatch').forEach(b=>b.addEventListener('click',()=>{document.querySelectorAll('.swatch').forEach(x=>x.classList.remove('on'));b.classList.add('on');accent=b.dataset.color;document.querySelector('#customColor').value=accent;render()}));
document.querySelector('#customColor').addEventListener('input',e=>{accent=e.target.value;document.querySelectorAll('.swatch').forEach(x=>x.classList.remove('on'));render()});
document.querySelector('#sigForm').addEventListener('submit',e=>e.preventDefault());
loadLocal();
render();
try{bbTrack('tool_opened',{tool:'email-signature-generator'})}catch(e){}
</script><section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Do I need an account?</summary>
<p>No. Designing your signature is free and needs no account. Copying or downloading the finished signature uses one action: 15 free as a guest, 75 every month with a free account. Tweaking and copying again in the same visit is free. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>Does my information leave my browser?</summary>
<p>No. The signature is built entirely on your device. Your details are not uploaded or stored. If you add a photo, it loads from the link you gave.</p>
</details>
<details>
<summary>Why does the photo need a link?</summary>
<p>Email apps cannot carry image files inside a pasted signature. Your photo has to already live at a public web address, like your website or LinkedIn image link, and the signature points at it.</p>
</details>
<details>
<summary>Will it work in Gmail and Outlook?</summary>
<p>Yes. The signature is plain table-based HTML, the format email apps expect. The copy button pastes it in formatted, ready to save in Gmail, Outlook, or Apple Mail settings.</p>
</details>
<details>
<summary>Can I change my signature later?</summary>
<p>Any time. The form remembers your details on this device, so updating a title or phone number takes seconds. Paste the new version over the old one in your mail settings.</p>
</details>
</section>
<section class="panel" aria-label="More tiny tools">
<h2 style="margin-top:0">More tiny tools</h2>
<p><a href="/tools/day-log/">Day Log</a> - Talk through your day, get jot notes and a morning recap.</p>
<p><a href="/tools/invoice-chaser/">Invoice Chaser</a> - Chase late invoices without writing the awkward email yourself.</p>
<p><a href="/tools/purr-code/">QR Code Generator</a> - Your link, but cute.</p>
</section>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?><script src="/tools/drop-strip.js?v=20261030r1"></script></body></html>
