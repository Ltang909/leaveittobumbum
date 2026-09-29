<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Purr Code | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
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
#pcDl{font-size:18px;padding:14px 26px}
.pc-status{font-weight:700;min-height:1.5em;margin:10px 0 0}
.pc-tip{color:var(--muted);font-size:14px;margin:8px 0 0}
.hidden{display:none!important}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Purr Code"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-paws-up.png" alt="Bum Bum showing off"><h1>QR codes, but cute.</h1><p class="lede">Your link, dressed up. Soft dots, pretty colors, and a cat in the middle. Preview as much as you like, downloading uses one action.</p>
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
</section>
<section class="panel pc-preview">
<canvas id="pcCanvas" width="1024" height="1024"></canvas>
<button id="pcDl" class="button" type="button">Download PNG</button>
<p id="pcStatus" class="pc-status"></p>
<p id="pcUsage"></p>
<p class="pc-tip">Tip: scan it with your phone camera before you print a hundred of them.</p>
</section>
</div>
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
const canvas=document.querySelector('#pcCanvas');
const ctx=canvas.getContext('2d');
const dlBtn=document.querySelector('#pcDl');
const statusEl=document.querySelector('#pcStatus');
const usageEl=document.querySelector('#pcUsage');
let dotStyle='soft', outSize=1024, dlKey=crypto.randomUUID();
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
let drawT=0;
urlEl.addEventListener('input',()=>{clearTimeout(drawT);drawT=setTimeout(draw,250);});
function rr(x,y,w,h,r){ctx.beginPath();ctx.moveTo(x+r,y);ctx.arcTo(x+w,y,x+w,y+h,r);ctx.arcTo(x+w,y+h,x,y+h,r);ctx.arcTo(x,y+h,x,y,r);ctx.arcTo(x,y,x+w,y,r);ctx.closePath();}
function inFinder(r,c,n){return (r<8&&c<8)||(r<8&&c>=n-8)||(r>=n-8&&c<8);}
function drawEye(sr,sc,m,fg,bg){
const x=sc*m,y=sr*m,s=7*m;
ctx.fillStyle=fg;rr(x,y,s,s,1.6*m);ctx.fill();
ctx.fillStyle=bg;rr(x+m,y+m,s-2*m,s-2*m,1.1*m);ctx.fill();
ctx.fillStyle=fg;rr(x+2*m,y+2*m,s-4*m,s-4*m,0.8*m);ctx.fill();
}
function draw(){
let text=urlEl.value.trim();
if(!text)text='https://leaveittobumbum.com';
let qr;
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
dlBtn.addEventListener('click',async()=>{
const text=urlEl.value.trim();
if(!text){statusEl.textContent='Give me a link first.';statusEl.classList.add('error');urlEl.focus();return;}
const key=dlKey;dlKey=crypto.randomUUID();
dlBtn.disabled=true;statusEl.textContent='Making it cute...';statusEl.classList.remove('error');
try{
const session=await fetch('/api/session.php').then(r=>r.json());
const response=await fetch('/api/tools/purr-code.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'download',text:text,idempotencyKey:key,csrf:session.csrf})});
let data={};try{data=await response.json()}catch(e){}
if(!response.ok){
if(response.status===402){bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});document.querySelector('#upgrade-slot').innerHTML=upgradeCard();bindUpgradeClicks(document.querySelector('#upgrade-slot'),'limit');statusEl.textContent='This download was not counted.';}
else{statusEl.textContent=data.error||'Purr Code hiccup. Try again.';statusEl.classList.add('error');}
return;}
bbTrack('action_completed',{tool:TOOL_KEY,used:data.usage.used,limit:data.usage.limit,remaining:data.usage.remaining});
canvas.toBlob(blob=>{
const a=document.createElement('a');
a.href=URL.createObjectURL(blob);a.download='purr-code.png';
document.body.appendChild(a);a.click();a.remove();
setTimeout(()=>URL.revokeObjectURL(a.href),4000);
statusEl.textContent='Done. Go stick it on everything.';
usageEl.textContent=data.usage.unlimited?'Unlimited actions.':data.usage.remaining+' actions left.';
},'image/png');
}catch(e){statusEl.textContent='Purr Code hiccup. Try again.';statusEl.classList.add('error');}
finally{dlBtn.disabled=false;}
});
draw();
<?php endif; ?>
</script><?php endif; ?></main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
