<?php
require dirname(__DIR__, 2) . '/api/_bootstrap.php';
require dirname(__DIR__, 2) . '/includes/purr-code-lib.php';
purrcode_handle_redirect(); // /tools/purr-code/?go=CODE logs the scan and 302s.
$subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Purr Code | Leave It to Bum Bum</title>
<meta name="description" content="QR codes, but cute. Soft dots, pretty colors, a cat in the middle. Preview free, export uses one action. Optional scan tracking.">
<meta property="og:title" content="Purr Code | Leave It to Bum Bum">
<meta property="og:description" content="QR codes, but cute. Soft dots, pretty colors, a cat in the middle. Preview free, export uses one action. Optional scan tracking.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/purr-code/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/favicon-cat.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Purr Code | Leave It to Bum Bum">
<meta name="twitter:description" content="QR codes, but cute. Soft dots, pretty colors, a cat in the middle. Preview free, export uses one action. Optional scan tracking.">
<link rel="canonical" href="https://leaveittobumbum.com/tools/purr-code/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Purr Code",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "QR codes, but cute. Soft dots, pretty colors, a cat in the middle. Preview free, export uses one action. Optional scan tracking.",
  "url": "https://leaveittobumbum.com/tools/purr-code/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Is Purr Code free?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Previewing is free and unlimited. Exporting a QR code uses one action, and guests get 15 free actions with no signup."
      }
    },
    {
      "@type": "Question",
      "name": "Do I need an account?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Design as a guest. A free account saves your tracked codes."
      }
    },
    {
      "@type": "Question",
      "name": "Can I track how many times my code is scanned?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. Turn on tracking and Purr Code counts every scan."
      }
    },
    {
      "@type": "Question",
      "name": "Can I customize the look?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. Pick from six color palettes or set your own colors, with soft, dots, or classic styles, plus a cat in the middle."
      }
    },
    {
      "@type": "Question",
      "name": "Do the QR codes expire?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Your codes work as long as your link does."
      }
    }
  ]
}
</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.pc-grid{display:grid;grid-template-columns:1fr;gap:18px}
@media(min-width:900px){.pc-grid{grid-template-columns:1fr 1fr;align-items:start}}
.pc-field{margin:0 0 16px}
.pc-field label{display:block;font-weight:800;margin-bottom:8px}
.pc-field input[type=text]{width:100%;padding:14px 16px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff;box-sizing:border-box}
.swatches{display:flex;gap:10px;flex-wrap:wrap}
.swatch{width:52px;height:52px;border-radius:14px;border:3px solid transparent;cursor:pointer;padding:0;overflow:hidden;flex:none}
.swatch[aria-pressed=true]{border-color:#1E2321}
.swatch span{display:block;width:100%;height:100%}
.dotstyles{display:flex;gap:10px;flex-wrap:wrap}
.dotstyle{border:2px solid var(--line);border-radius:10px;background:#fff;padding:10px 16px;font:inherit;font-weight:800;cursor:pointer}
.dotstyle[aria-pressed=true]{border-color:#1E2321;background:#1E2321;color:#fff}
.customcolors{display:flex;gap:12px;align-items:center;margin-top:10px;flex-wrap:wrap}
.customcolors input[type=color]{width:52px;height:40px;border:2px solid var(--line);border-radius:8px;padding:2px;background:#fff;cursor:pointer}
.toggle-row{display:flex;align-items:center;gap:10px;font-weight:800}
.toggle-row input{width:22px;height:22px;accent-color:#1E2321}
#pcCanvas{width:100%;max-width:420px;height:auto;display:block;border-radius:18px;box-shadow:0 8px 30px rgba(30,35,33,.12);background:#fff}
.pc-preview{display:flex;flex-direction:column;align-items:center;gap:14px}
.pc-btnrow{display:flex;gap:10px;flex-wrap:wrap;justify-content:center}
#pcDl{font-size:18px;padding:14px 26px}
.pc-status{font-weight:700;min-height:1.5em;margin:10px 0 0;text-align:center}
.pc-tip{color:var(--muted);font-size:14px;margin:8px 0 0}
.pc-trackstats{width:100%;max-width:420px;background:#FFF9F2;border:2px solid var(--line);border-radius:12px;padding:12px 16px;text-align:center}
.pc-trackstats p{margin:6px 0}
.pc-short{font-size:14px;word-break:break-all}
.linklike{background:none;border:none;color:inherit;text-decoration:underline;cursor:pointer;font:inherit;font-weight:800;padding:0}
#pcMyCodes{margin-top:18px}
#pcMyCodes h3{margin:0 0 8px;font-size:1rem}
#pcCodesList{list-style:none;margin:0;padding:0}
#pcCodesList li{display:flex;align-items:center;gap:12px;border:2px solid var(--line);border-radius:10px;padding:8px 12px;margin:0 0 8px;background:#fff;font-size:14px}
#pcCodesList .t{flex:1 1 auto;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
#pcCodesList .meta{display:flex;align-items:center;gap:12px;flex:none}
#pcCodesList .s{font-weight:900;white-space:nowrap}
#pcCodesList .qrthumb{width:60px;height:60px;padding:5px;border:2px solid var(--line);border-radius:10px;background:#fff;cursor:pointer;flex:none;display:flex;align-items:center;justify-content:center}
#pcCodesList .qrthumb:hover{border-color:#1E2321}
#pcCodesList .qrthumb canvas{width:46px;height:46px;display:block}
.hidden{display:none!important}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Purr Code"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-paws-up.png" alt="Bum Bum showing off"><h1>QR codes, but cute.</h1><p class="lede">Your link, dressed up. Soft dots, pretty colors, and a cat in the middle. Preview as much as you like, exporting uses one action. Turn on tracking to see how many times your code gets scanned.</p>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to use Purr Code</h2><a class="button" href="/account/?next=<?= urlencode('/tools/purr-code/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/purr-code/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/purr-code/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/purr-code/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php $out = empty($usage['unlimited']) && ($usage['remaining'] ?? 0) <= 0; ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<?php if ($out): ?>
<div id="upgrade-slot"></div>
<?php else: ?>
<div class="pc-grid">
<section class="panel">
<div class="pc-field"><label for="pcUrl">Your link</label><input id="pcUrl" type="text" inputmode="url" autocomplete="url" placeholder="https://yourshop.com" value="https://leaveittobumbum.com"></div>
<div class="pc-field"><label>Colors</label><div class="swatches" id="pcSwatches"></div>
<div class="customcolors"><label for="pcFg" style="margin:0">Ink</label><input id="pcFg" type="color" value="#C05B2B"><label for="pcBg" style="margin:0">Paper</label><input id="pcBg" type="color" value="#FFF9F2"></div></div>
<div class="pc-field"><label>Dot style</label><div class="dotstyles" id="pcDots">
<button type="button" class="dotstyle" data-style="soft" aria-pressed="true">Soft</button>
<button type="button" class="dotstyle" data-style="dots" aria-pressed="false">Dots</button>
<button type="button" class="dotstyle" data-style="classic" aria-pressed="false">Classic</button>
</div></div>
<div class="pc-field"><label>Size</label><div class="dotstyles" id="pcSizes">
<button type="button" class="dotstyle" data-size="512" aria-pressed="false">Small</button>
<button type="button" class="dotstyle" data-size="1024" aria-pressed="true">Medium</button>
<button type="button" class="dotstyle" data-size="2048" aria-pressed="false">Print</button>
</div></div>
<div class="pc-field"><div class="toggle-row"><input id="pcLogo" type="checkbox" checked><label for="pcLogo" style="margin:0">Cat in the middle</label></div></div>
<div class="pc-field"><div class="toggle-row"><input id="pcTrack" type="checkbox"><label for="pcTrack" style="margin:0">Track scans with a short link</label></div>
<p class="pc-tip" id="pcTrackHint" style="display:none">1 action creates your tracked code. Downloads and copies are free after that, and you will see how many times it gets scanned.</p></div>
<div class="pc-field" id="pcTrackCreateWrap" style="display:none"><button id="pcCreate" class="button" type="button">Create tracked QR code</button></div>
</section>
<section class="panel pc-preview">
<canvas id="pcCanvas" width="1024" height="1024"></canvas>
<div class="pc-trackstats" id="pcTrackStats" style="display:none">
<p><strong id="pcScanCount">0</strong> scans so far</p>
<p class="pc-short"><a id="pcShortLink" href="#" target="_blank" rel="noopener"></a> <button id="pcCopyLink" class="linklike" type="button">Copy</button></p>
<button id="pcRefresh" class="button secondary" type="button">Refresh stats</button>
</div>
<div class="pc-btnrow">
<button id="pcDl" class="button" type="button">Download PNG</button>
<button id="pcCopy" class="button secondary" type="button">Copy PNG</button>
</div>
<p id="pcStatus" class="pc-status"></p>
<p id="pcUsage"></p>
<p class="pc-tip">Tip: scan it with your phone camera before you print a hundred of them.</p>
</section>
</div>
<section class="panel" id="pcMyCodes"><h3>My tracked codes</h3><ul id="pcCodesList"></ul><p class="pc-tip" id="pcCodesEmpty" style="display:none">No tracked codes yet. Flip on "Track scans" to make your first one.</p></section>
<div id="upgrade-slot"></div>
<?php endif; ?>
<script src="qrcode.min.js"></script>
<script>
const TOOL_KEY='purr-code';
const PERIOD=<?= json_encode($usage['period'] ?? '') ?>;
if(<?= $low ? 'true' : 'false' ?>){const seen='bb_m80_'+PERIOD;if(!localStorage.getItem(seen)){localStorage.setItem(seen,'1');bbTrack('usage_milestone_80',{tool:TOOL_KEY,used:<?= (int) ($usage['used'] ?? 0) ?>,limit:<?= (int) ($usage['limit'] ?? 0) ?>})}}
function upgradeCard(){return `<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) ($usage['limit'] ?? 75) ?> free actions this month. Helper gives you 1,500 actions for $12/month. Operator gives you 6,000 actions plus a custom tool built for you in 36 hours for $49/month.</p><p><a class="button" data-plan="helper" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" data-plan="operator" href="/checkout/?plan=operator">Get Operator, $49/mo</a></p><p><a href="/account/">See your usage</a></p></div>`}
function bindUpgradeClicks(root,context){root.querySelectorAll('[data-plan]').forEach(a=>a.addEventListener('click',()=>bbTrack('upgrade_clicked',{plan:a.dataset.plan,context:context,tool:TOOL_KEY})))}
<?php if ($out): ?>
document.querySelector('#upgrade-slot').innerHTML=upgradeCard();
bindUpgradeClicks(document,'page_load');
bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'page_load'});
<?php else: ?>
const PALETTES=[
{name:'Bum Bum',fg:'#C05B2B',bg:'#FFF9F2'},
{name:'Strawberry',fg:'#C2437B',bg:'#FFF2F6'},
{name:'Matcha',fg:'#3F7A52',bg:'#F2F7EC'},
{name:'Boba',fg:'#8A5A33',bg:'#FAF3E3'},
{name:'Blueberry',fg:'#3D5A99',bg:'#EFF3FB'},
{name:'Midnight',fg:'#EDE8DF',bg:'#23282A'}
];
const urlEl=document.querySelector('#pcUrl');
const fgEl=document.querySelector('#pcFg');
const bgEl=document.querySelector('#pcBg');
const swWrap=document.querySelector('#pcSwatches');
const dotsWrap=document.querySelector('#pcDots');
const sizesWrap=document.querySelector('#pcSizes');
const logoEl=document.querySelector('#pcLogo');
const trackEl=document.querySelector('#pcTrack');
const trackHint=document.querySelector('#pcTrackHint');
const trackCreateWrap=document.querySelector('#pcTrackCreateWrap');
const createBtn=document.querySelector('#pcCreate');
const trackStats=document.querySelector('#pcTrackStats');
const scanCountEl=document.querySelector('#pcScanCount');
const shortLinkEl=document.querySelector('#pcShortLink');
const copyLinkBtn=document.querySelector('#pcCopyLink');
const refreshBtn=document.querySelector('#pcRefresh');
const myCodesEl=document.querySelector('#pcMyCodes');
const codesListEl=document.querySelector('#pcCodesList');
const codesEmpty=document.querySelector('#pcCodesEmpty');
const canvas=document.querySelector('#pcCanvas');
const ctx=canvas.getContext('2d');
const dlBtn=document.querySelector('#pcDl');
const copyBtn=document.querySelector('#pcCopy');
const statusEl=document.querySelector('#pcStatus');
const usageEl=document.querySelector('#pcUsage');
let dotStyle='soft', outSize=1024, dlKey=crypto.randomUUID(), tracked=null;
const catImg=new Image();
catImg.onload=()=>draw();
catImg.src='/bum/favicon-cat.png';
PALETTES.forEach((p,i)=>{
const b=document.createElement('button');
b.type='button';b.className='swatch';b.title=p.name;b.setAttribute('aria-pressed',i===0?'true':'false');
b.innerHTML=`<span style="background:linear-gradient(135deg,${p.fg} 50%,${p.bg} 50%)"></span>`;
b.addEventListener('click',()=>{swWrap.querySelectorAll('.swatch').forEach(x=>x.setAttribute('aria-pressed','false'));b.setAttribute('aria-pressed','true');fgEl.value=p.fg;bgEl.value=p.bg;draw();bbTrack('palette_picked',{tool:TOOL_KEY,palette:p.name});});
swWrap.appendChild(b);
});
function pressOnly(wrap,btn){wrap.querySelectorAll('[aria-pressed]').forEach(x=>x.setAttribute('aria-pressed','false'));btn.setAttribute('aria-pressed','true');}
dotsWrap.querySelectorAll('.dotstyle').forEach(b=>b.addEventListener('click',()=>{pressOnly(dotsWrap,b);dotStyle=b.dataset.style;draw();}));
sizesWrap.querySelectorAll('.dotstyle').forEach(b=>b.addEventListener('click',()=>{pressOnly(sizesWrap,b);outSize=parseInt(b.dataset.size,10);draw();}));
[fgEl,bgEl].forEach(el=>el.addEventListener('input',()=>{swWrap.querySelectorAll('.swatch').forEach(x=>x.setAttribute('aria-pressed','false'));draw();}));
logoEl.addEventListener('change',draw);
function qrText(){
if(tracked)return tracked.shortUrl;
const t=urlEl.value.trim();
return t||'https://leaveittobumbum.com';
}
let drawT=0;
urlEl.addEventListener('input',()=>{if(tracked){tracked=null;trackStats.style.display='none';}clearTimeout(drawT);drawT=setTimeout(draw,250);});
trackEl.addEventListener('change',()=>{
const on=trackEl.checked;
trackHint.style.display=on?'':'none';
trackCreateWrap.style.display=on?'':'none';
if(!on){tracked=null;trackStats.style.display='none';}
else{loadMyCodes();}
draw();
});
function rr(x,y,w,h,r){ctx.beginPath();ctx.moveTo(x+r,y);ctx.arcTo(x+w,y,x+w,y+h,r);ctx.arcTo(x+w,y+h,x,y+h,r);ctx.arcTo(x,y+h,x,y,r);ctx.arcTo(x,y,x+w,y,r);ctx.closePath();}
function inFinder(r,c,n){return (r<8&&c<8)||(r<8&&c>=n-8)||(r>=n-8&&c<8);}
function drawEye(sr,sc,m,fg,bg){
const x=sc*m,y=sr*m,s=7*m;
ctx.fillStyle=fg;rr(x,y,s,s,1.6*m);ctx.fill();
ctx.fillStyle=bg;rr(x+m,y+m,s-2*m,s-2*m,1.1*m);ctx.fill();
ctx.fillStyle=fg;rr(x+2*m,y+2*m,s-4*m,s-4*m,0.8*m);ctx.fill();
}
function draw(){
let text=qrText(),qr;
try{qr=qrcode(0,'H');qr.addData(text);qr.make();}
catch(e){statusEl.textContent='That link is too long for a QR code. Try a shorter URL.';statusEl.classList.add('error');return;}
statusEl.textContent='';statusEl.classList.remove('error');
const n=qr.getModuleCount(),quiet=4,total=n+quiet*2,m=outSize/total;
canvas.width=outSize;canvas.height=outSize;
const fg=fgEl.value,bg=bgEl.value;
ctx.fillStyle=bg;ctx.fillRect(0,0,outSize,outSize);
ctx.fillStyle=fg;
for(let r=0;r<n;r++)for(let c=0;c<n;c++){
if(!qr.isDark(r,c)||inFinder(r,c,n))continue;
const x=(c+quiet)*m,y=(r+quiet)*m;
if(dotStyle==='dots'){ctx.beginPath();ctx.arc(x+m/2,y+m/2,m*0.44,0,Math.PI*2);ctx.fill();}
else if(dotStyle==='soft'){rr(x+m*0.06,y+m*0.06,m*0.88,m*0.88,m*0.3);ctx.fill();}
else{ctx.fillRect(x,y,m,m);}
}
drawEye(quiet,quiet,m,fg,bg);
drawEye(quiet,quiet+n-7,m,fg,bg);
drawEye(quiet+n-7,quiet,m,fg,bg);
if(logoEl.checked&&catImg.complete&&catImg.naturalWidth){
const ls=outSize*0.24,lx=(outSize-ls)/2,ly=(outSize-ls)/2;
ctx.fillStyle='#ffffff';rr(lx,ly,ls,ls,ls*0.22);ctx.fill();
const pad=ls*0.14;
ctx.drawImage(catImg,lx+pad,ly+pad,ls-pad*2,ls-pad*2);
}
}
async function meteredExport(kind){
const text=urlEl.value.trim();
if(!tracked&&!text){statusEl.textContent='Give me a link first.';statusEl.classList.add('error');urlEl.focus();return;}
const key=dlKey;dlKey=crypto.randomUUID();
const doExport=()=>{
canvas.toBlob(blob=>{
if(!blob){statusEl.textContent='Could not make the image. Try again.';statusEl.classList.add('error');return;}
if(kind==='download'){
const a=document.createElement('a');
a.href=URL.createObjectURL(blob);a.download='purr-code.png';
document.body.appendChild(a);a.click();a.remove();
setTimeout(()=>URL.revokeObjectURL(a.href),4000);
statusEl.textContent='Done. Go stick it on everything.';
}else{
if(!(navigator.clipboard&&window.ClipboardItem)){statusEl.textContent='Copy is not supported in this browser. Download instead.';statusEl.classList.add('error');return;}
navigator.clipboard.write([new ClipboardItem({'image/png':blob})]).then(
()=>{statusEl.textContent='Copied. Paste it anywhere.';},
()=>{statusEl.textContent='Copy was blocked. Download instead.';statusEl.classList.add('error');});
}
},'image/png');};
if(tracked){doExport();bbTrack('tracked_export',{tool:TOOL_KEY,kind:kind});return;}
dlBtn.disabled=true;copyBtn.disabled=true;statusEl.textContent='Making it cute...';statusEl.classList.remove('error');
try{
const session=await fetch('/api/session.php').then(r=>r.json());
const response=await fetch('/api/tools/purr-code.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'download',text:text,idempotencyKey:key,csrf:session.csrf})});
let data={};try{data=await response.json()}catch(e){}
if(!response.ok){
if(response.status===402){bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});document.querySelector('#upgrade-slot').innerHTML=upgradeCard();bindUpgradeClicks(document.querySelector('#upgrade-slot'),'limit');statusEl.textContent='This export was not counted.';}
else{statusEl.textContent=data.error||'Purr Code hiccup. Try again.';statusEl.classList.add('error');}
return;}
bbTrack('action_completed',{tool:TOOL_KEY,used:data.usage.used,limit:data.usage.limit,remaining:data.usage.remaining});
usageEl.textContent=data.usage.unlimited?'Unlimited actions.':data.usage.remaining+' actions left.';
doExport();
}catch(e){statusEl.textContent='Purr Code hiccup. Try again.';statusEl.classList.add('error');}
finally{dlBtn.disabled=false;copyBtn.disabled=false;}
}
dlBtn.addEventListener('click',()=>meteredExport('download'));
copyBtn.addEventListener('click',()=>meteredExport('copy'));
function showTracked(t,scans){
tracked=t;draw();
scanCountEl.textContent=scans;
shortLinkEl.textContent=t.shortUrl;shortLinkEl.href=t.shortUrl;
trackStats.style.display='';
}
createBtn.addEventListener('click',async()=>{
const text=urlEl.value.trim();
if(!text){statusEl.textContent='Give me a link first.';statusEl.classList.add('error');urlEl.focus();return;}
const key=dlKey;dlKey=crypto.randomUUID();
createBtn.disabled=true;statusEl.textContent='Minting your short link...';statusEl.classList.remove('error');
try{
const session=await fetch('/api/session.php').then(r=>r.json());
const response=await fetch('/api/tools/purr-code.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'create_link',text:text,idempotencyKey:key,csrf:session.csrf})});
let data={};try{data=await response.json()}catch(e){}
if(!response.ok){
if(response.status===402){bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});document.querySelector('#upgrade-slot').innerHTML=upgradeCard();bindUpgradeClicks(document.querySelector('#upgrade-slot'),'limit');statusEl.textContent='This code was not counted.';}
else if(response.status===503){statusEl.textContent='Scan tracking is still being set up. Try again soon.';statusEl.classList.add('error');}
else{statusEl.textContent=data.error||'Purr Code hiccup. Try again.';statusEl.classList.add('error');}
return;}
bbTrack('action_completed',{tool:TOOL_KEY,used:data.usage.used,limit:data.usage.limit,remaining:data.usage.remaining,action:'create_link'});
usageEl.textContent=data.usage.unlimited?'Unlimited actions.':data.usage.remaining+' actions left.';
showTracked({code:data.code,shortUrl:data.shortUrl,target:data.target},0);
statusEl.textContent='Tracked code ready. Downloads and copies are free from here.';
loadMyCodes();
}catch(e){statusEl.textContent='Purr Code hiccup. Try again.';statusEl.classList.add('error');}
finally{createBtn.disabled=false;}
});
copyLinkBtn.addEventListener('click',()=>{
if(!tracked)return;
navigator.clipboard.writeText(tracked.shortUrl).then(()=>{copyLinkBtn.textContent='Copied!';setTimeout(()=>copyLinkBtn.textContent='Copy',1500);});
});
function drawThumb(cv,text){
let qr;try{qr=qrcode(0,'M');qr.addData(text);qr.make();}catch(e){return;}
const n=qr.getModuleCount(),c=cv.getContext('2d'),s=cv.width/n;
c.fillStyle='#ffffff';c.fillRect(0,0,cv.width,cv.height);
c.fillStyle='#1E2321';
for(let r=0;r<n;r++)for(let col=0;col<n;col++){if(qr.isDark(r,col))c.fillRect(Math.floor(col*s),Math.floor(r*s),Math.ceil(s),Math.ceil(s));}
}
async function loadMyCodes(){
try{
const session=await fetch('/api/session.php').then(r=>r.json());
const response=await fetch('/api/tools/purr-code.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'my_links',csrf:session.csrf})});
let data={};try{data=await response.json()}catch(e){}
if(!response.ok)return;
const links=data.links||[];
codesListEl.innerHTML='';
codesEmpty.style.display=links.length?'none':'';
links.forEach(l=>{
const li=document.createElement('li');
const info=document.createElement('span');info.className='t';info.title=l.target_url;info.textContent=l.target_url;
const s=document.createElement('span');s.className='s';s.textContent=l.scans+(l.scans===1?' scan':' scans');
const b=document.createElement('button');b.type='button';b.className='qrthumb';b.title='Load this code into the preview';
const cv=document.createElement('canvas');cv.width=138;cv.height=138;b.appendChild(cv);
drawThumb(cv,l.shortUrl);
b.addEventListener('click',()=>{trackEl.checked=true;trackEl.dispatchEvent(new Event('change'));showTracked({code:l.code,shortUrl:l.shortUrl,target:l.target_url},l.scans);statusEl.textContent='Loaded. Downloads and copies are free for tracked codes.';document.querySelector('.pc-preview').scrollIntoView({behavior:'smooth',block:'nearest'});});
const meta=document.createElement('span');meta.className='meta';
meta.appendChild(s);meta.appendChild(b);
li.appendChild(info);li.appendChild(meta);
codesListEl.appendChild(li);
});
}catch(e){}
}
refreshBtn.addEventListener('click',async()=>{
if(!tracked)return;
refreshBtn.disabled=true;
await loadMyCodes();
try{
const session=await fetch('/api/session.php').then(r=>r.json());
const response=await fetch('/api/tools/purr-code.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'my_links',csrf:session.csrf})});
const data=await response.json();
const mine=(data.links||[]).find(l=>l.code===tracked.code);
if(mine)scanCountEl.textContent=mine.scans;
statusEl.textContent='Stats refreshed.';
}catch(e){statusEl.textContent='Could not refresh. Try again.';statusEl.classList.add('error');}
finally{refreshBtn.disabled=false;}
});
loadMyCodes();
draw();
<?php endif; ?>
</script><?php endif; ?><section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<h3>Is Purr Code free?</h3>
<p>Previewing is free and unlimited. Exporting a QR code uses one action, and guests get 15 free actions with no signup.</p>
<h3>Do I need an account?</h3>
<p>No. Design as a guest. A free account saves your tracked codes.</p>
<h3>Can I track how many times my code is scanned?</h3>
<p>Yes. Turn on tracking and Purr Code counts every scan.</p>
<h3>Can I customize the look?</h3>
<p>Yes. Pick from six color palettes or set your own colors, with soft, dots, or classic styles, plus a cat in the middle.</p>
<h3>Do the QR codes expire?</h3>
<p>No. Your codes work as long as your link does.</p>
</section>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
