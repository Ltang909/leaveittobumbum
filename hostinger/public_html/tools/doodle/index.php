<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $user = currentUser(); $usage = $user ? usageFor(billingUser($user)) : null; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Doodle | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css?v=5"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700;9..144,900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1,.lede{max-width:none}
.doodle-layout{display:grid;gap:16px;margin-top:20px}
.toolbar{display:flex;flex-wrap:wrap;gap:10px;align-items:stretch}
.tool-group{display:flex;gap:10px;align-items:center;flex-wrap:wrap;background:#fff;border:2px solid var(--line);border-radius:12px;padding:8px 12px}
.tool-group .lbl{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;opacity:.55;flex:none}
.seg{display:flex;height:44px;border:2px solid var(--ink);border-radius:12px;overflow:hidden;background:#fff;flex:none}
.seg button{border:0;background:transparent;margin:0;padding:0 16px;font:inherit;font-weight:800;cursor:pointer;height:100%;display:flex;align-items:center;color:var(--ink)}
.seg button.on{background:var(--ink);color:#fff}
.swatch{width:36px;height:36px;border-radius:50%;border:2px solid var(--ink);cursor:pointer;padding:0;margin:0;flex:none}
.swatch.on{outline:3px solid var(--ink);outline-offset:2px}
input[type=color].pick{width:36px;height:36px;border:2px solid var(--ink);border-radius:50%;padding:2px;background:#fff;cursor:pointer;margin:0;flex:none}
.size-wrap{display:flex;align-items:center;gap:10px;height:44px;flex:none}
.size-wrap b{min-width:2ch;text-align:right}
input[type=range].size{width:140px;accent-color:var(--ink);margin:0}
.iconbtn{border:2px solid var(--ink);border-radius:12px;background:#fff;margin:0;padding:0 16px;font:inherit;font-weight:800;cursor:pointer;height:44px;display:inline-flex;align-items:center;flex:none;color:var(--ink)}
.iconbtn:disabled{opacity:.35;cursor:default}
.iconbtn.danger{border-color:#e5484d;color:#e5484d}
.canvas-wrap{background:#fff;border:2px solid var(--ink);border-radius:14px;padding:10px;box-shadow:4px 4px 0 var(--ink)}
#pad{display:block;width:100%;height:auto;touch-action:none;user-select:none;-webkit-user-select:none;border-radius:8px;cursor:crosshair}
#pad.eraser{cursor:cell}
.export-row{display:flex;gap:10px;flex-wrap:wrap}
.export-row .button{min-height:48px}
.hint{font-size:13px;opacity:.7;margin:0}
@media(max-width:560px){.tool-group{width:100%;justify-content:flex-start}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Doodle"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-paws-up.png" alt="Bum Bum ready to doodle"><h1>A tiny canvas for big ideas.</h1><p class="lede">Doodle is a pocket sketchpad. Draw with a finger, stylus, or mouse. Drawing is free and nothing leaves your browser. Saving or copying your doodle uses one action.</p>
<?php $demo = 'doodle'; require dirname(__DIR__, 2) . '/includes/tool-demo.php'; ?>
<div class="doodle-layout">
<div class="toolbar">
<div class="tool-group"><span class="lbl">Tool</span><div class="seg" role="group" aria-label="Pen or eraser"><button type="button" id="penBtn" class="on">Pen</button><button type="button" id="eraserBtn">Eraser</button></div></div>
<div class="tool-group"><span class="lbl">Color</span><button type="button" class="swatch on" data-color="#1a1a1a" style="background:#1a1a1a" aria-label="Black"></button><button type="button" class="swatch" data-color="#e5484d" style="background:#e5484d" aria-label="Red"></button><button type="button" class="swatch" data-color="#2f9e5f" style="background:#2f9e5f" aria-label="Green"></button><button type="button" class="swatch" data-color="#2f6fed" style="background:#2f6fed" aria-label="Blue"></button><button type="button" class="swatch" data-color="#f5a623" style="background:#f5a623" aria-label="Orange"></button><button type="button" class="swatch" data-color="#8e4ec6" style="background:#8e4ec6" aria-label="Purple"></button><input type="color" class="pick" id="customColor" value="#1a1a1a" aria-label="Custom color"></div>
<div class="tool-group"><span class="lbl">Size</span><div class="size-wrap"><input type="range" class="size" id="sizeRange" min="2" max="60" value="8"><b id="sizeVal">8</b></div></div>
<div class="tool-group"><span class="lbl">History</span><button type="button" class="iconbtn" id="undoBtn" disabled>Undo</button><button type="button" class="iconbtn" id="redoBtn" disabled>Redo</button><button type="button" class="iconbtn danger" id="clearBtn">Clear</button></div>
<div class="tool-group"><span class="lbl">Paper</span><div class="seg" role="group" aria-label="Background"><button type="button" data-bg="transparent" class="on">Clear</button><button type="button" data-bg="#ffffff">White</button><button type="button" data-bg="#fdf6e3">Cream</button></div></div>
<div class="tool-group"><span class="lbl">Shape</span><div class="seg" role="group" aria-label="Canvas shape"><button type="button" data-aspect="wide" class="on">Classic</button><button type="button" data-aspect="sig">Signature</button><button type="button" data-aspect="square">Square</button></div></div>
</div>
<div class="export-row"><?php if (!$user): ?><a class="button" href="/account/?next=<?= urlencode('/tools/doodle/') ?>">Sign in to save your doodle</a><span class="hint" style="margin:0">Saving or copying uses one action.</span><?php else: ?><button type="button" class="button" id="pngBtn">Download PNG</button><button type="button" class="button secondary" id="svgBtn">Download SVG</button><button type="button" class="button secondary" id="copyBtn">Copy</button><span class="hint" style="margin:0">1 action per save.</span><?php endif; ?></div>
<div class="canvas-wrap"><canvas id="pad"></canvas></div>
<p class="hint">Tip: pick Signature shape and Clear paper for a transparent signature you can drop onto any document.</p>
</div>
<div id="upgrade-slot"></div>
<script>
const canvas=document.querySelector('#pad'),ctx=canvas.getContext('2d');
const ASPECTS={wide:[3,2],sig:[3,1],square:[1,1]};
const LOGICAL_W=1200;
let aspect='wide',bg='transparent',color='#1a1a1a',size=8,eraser=false;
let strokes=[],history=[],redoHist=[];
function logicalH(){const[a,b]=ASPECTS[aspect];return Math.round(LOGICAL_W*b/a)}
function fitCanvas(){
  const wrapW=canvas.parentElement.clientWidth-20;
  const dpr=Math.min(window.devicePixelRatio||1,3);
  const h=logicalH(),cssW=wrapW,cssH=Math.round(wrapW*h/LOGICAL_W);
  canvas.style.height=cssH+'px';
  canvas.width=Math.round(cssW*dpr);canvas.height=Math.round(cssH*dpr);
  render(ctx,canvas.width,canvas.height);
}
function drawStroke(c,s,w,h){
  const pts=s.pts;if(!pts.length)return;
  c.globalCompositeOperation=s.eraser?'destination-out':'source-over';
  c.lineCap='round';c.lineJoin='round';
  const X=p=>p.x*w,Y=p=>p.y*h;
  if(pts.length===1){const pw=s.size*(0.4+0.6*(pts[0].p||0.5))/LOGICAL_W*w;c.fillStyle=s.eraser?'#000':s.color;c.beginPath();c.arc(X(pts[0]),Y(pts[0]),pw/2,0,7);c.fill();return}
  c.strokeStyle=s.eraser?'#000':s.color;
  c.lineWidth=s.size*(0.4+0.6*(s.avgP||0.5))/LOGICAL_W*w;
  c.beginPath();
  c.moveTo(X(pts[0]),Y(pts[0]));
  for(let i=1;i<pts.length-1;i++){const mx=(X(pts[i])+X(pts[i+1]))/2,my=(Y(pts[i])+Y(pts[i+1]))/2;c.quadraticCurveTo(X(pts[i]),Y(pts[i]),mx,my)}
  const l=pts[pts.length-1];c.lineTo(X(l),Y(l));c.stroke();
}
function render(c,w,h){
  c.save();c.setTransform(1,0,0,1,0,0);c.clearRect(0,0,w,h);
  if(bg!=='transparent'){c.fillStyle=bg;c.fillRect(0,0,w,h)}
  for(const s of strokes)drawStroke(c,s,w,h);
  if(drawing&&cur&&cur.pts.length)drawStroke(c,cur,w,h);
  c.restore();c.globalCompositeOperation='source-over';
}
function updateHistBtns(){document.querySelector('#undoBtn').disabled=!history.length;document.querySelector('#redoBtn').disabled=!redoHist.length}
function pushHist(entry){history.push(entry);redoHist=[];updateHistBtns()}
let drawing=false,cur=null;
function pt(e){const r=canvas.getBoundingClientRect();return{x:(e.clientX-r.left)/r.width,y:(e.clientY-r.top)/r.height,p:e.pressure||0.5}}
canvas.addEventListener('pointerdown',e=>{e.preventDefault();canvas.setPointerCapture(e.pointerId);drawing=true;const p0=pt(e);cur={color,size,eraser,pts:[p0],avgP:p0.p};redraw()});
canvas.addEventListener('pointermove',e=>{if(!drawing)return;e.preventDefault();const evs=e.getCoalescedEvents?e.getCoalescedEvents():[e];for(const ev of evs){const p=pt(ev);cur.pts.push(p);cur.avgP=(cur.avgP*(cur.pts.length-1)+p.p)/cur.pts.length}redraw()});
function endStroke(){if(!drawing)return;drawing=false;if(cur.pts.length){strokes.push(cur);pushHist({t:'add',s:cur})}cur=null;redraw()}
canvas.addEventListener('pointerup',endStroke);canvas.addEventListener('pointercancel',endStroke);
function redraw(){render(ctx,canvas.width,canvas.height)}
document.querySelector('#penBtn').addEventListener('click',()=>{eraser=false;canvas.classList.remove('eraser');document.querySelector('#penBtn').classList.add('on');document.querySelector('#eraserBtn').classList.remove('on')});
document.querySelector('#eraserBtn').addEventListener('click',()=>{eraser=true;canvas.classList.add('eraser');document.querySelector('#eraserBtn').classList.add('on');document.querySelector('#penBtn').classList.remove('on')});
document.querySelectorAll('.swatch').forEach(b=>b.addEventListener('click',()=>{document.querySelectorAll('.swatch').forEach(x=>x.classList.remove('on'));b.classList.add('on');color=b.dataset.color;document.querySelector('#customColor').value=color;if(eraser)document.querySelector('#penBtn').click()}));
document.querySelector('#customColor').addEventListener('input',e=>{color=e.target.value;document.querySelectorAll('.swatch').forEach(x=>x.classList.remove('on'));if(eraser)document.querySelector('#penBtn').click()});
document.querySelector('#sizeRange').addEventListener('input',e=>{size=+e.target.value;document.querySelector('#sizeVal').textContent=size});
document.querySelector('#undoBtn').addEventListener('click',()=>{const h=history.pop();if(!h)return;redoHist.push(h);if(h.t==='add')strokes=strokes.filter(s=>s!==h.s);else if(h.t==='clear')strokes=h.strokes;updateHistBtns();redraw()});
document.querySelector('#redoBtn').addEventListener('click',()=>{const h=redoHist.pop();if(!h)return;history.push(h);if(h.t==='add')strokes.push(h.s);else if(h.t==='clear')strokes=[];updateHistBtns();redraw()});
document.querySelector('#clearBtn').addEventListener('click',()=>{if(!strokes.length)return;if(!confirm('Clear the whole canvas?'))return;pushHist({t:'clear',strokes});strokes=[];redraw()});
document.querySelectorAll('[data-bg]').forEach(b=>b.addEventListener('click',()=>{document.querySelectorAll('[data-bg]').forEach(x=>x.classList.remove('on'));b.classList.add('on');bg=b.dataset.bg;redraw()}));
document.querySelectorAll('[data-aspect]').forEach(b=>b.addEventListener('click',()=>{document.querySelectorAll('[data-aspect]').forEach(x=>x.classList.remove('on'));b.classList.add('on');aspect=b.dataset.aspect;fitCanvas()}));
function download(name,url){const a=document.createElement('a');a.href=url;a.download=name;document.body.appendChild(a);a.click();a.remove()}
async function spendDoodleAction(){
  try{
    const s=await fetch('/api/session.php').then(r=>r.json());
    const key='doodle-'+Date.now()+'-'+Math.random().toString(36).slice(2,10);
    const res=await fetch('/api/tools/doodle.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({csrf:s.csrf,idempotencyKey:key})});
    const data=await res.json().catch(()=>({}));
    if(res.status===402){
      document.querySelector('#upgrade-slot').innerHTML='<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">Saving a doodle uses one action. Helper gives you 1,500 actions for $12/month.</p><p><a class="button" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" href="/account/">See your usage</a></p></div>';
      return false;
    }
    if(!res.ok){alert(data.error||'Could not save. Try again.');return false}
    if(data&&data.usage)document.dispatchEvent(new CustomEvent('bb:usage',{detail:data.usage}));
    return true;
  }catch(e){alert('Could not reach Bum Bum. Check your connection and try again.');return false}
}
const pngBtn=document.querySelector('#pngBtn');if(pngBtn)pngBtn.addEventListener('click',async()=>{
  if(!await spendDoodleAction())return;
  const w=LOGICAL_W*2,h=logicalH()*2,off=document.createElement('canvas');off.width=w;off.height=h;
  render(off.getContext('2d'),w,h);
  download('doodle.png',off.toDataURL('image/png'));
});
function escXml(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;')}
const svgBtn=document.querySelector('#svgBtn');if(svgBtn)svgBtn.addEventListener('click',async()=>{
  if(!await spendDoodleAction())return;
  const w=LOGICAL_W,h=logicalH();
  const d=s=>'M'+s.pts.map(p=>(p.x*w).toFixed(1)+' '+(p.y*h).toFixed(1)).join(' L');
  const sw=s=>(s.size*(0.4+0.6*(s.avgP||0.5))).toFixed(1);
  const pens=strokes.filter(s=>!s.eraser),ers=strokes.filter(s=>s.eraser);
  let svg=`<svg xmlns="http://www.w3.org/2000/svg" width="${w}" height="${h}" viewBox="0 0 ${w} ${h}">`;
  if(ers.length){svg+=`<defs><mask id="ez"><rect width="${w}" height="${h}" fill="white"/>`+ers.map(s=>`<path d="${d(s)}" stroke="black" stroke-width="${sw(s)}" fill="none" stroke-linecap="round" stroke-linejoin="round"/>`).join('')+`</mask></defs>`}
  if(bg!=='transparent')svg+=`<rect width="${w}" height="${h}" fill="${escXml(bg)}"/>`;
  const inner=pens.map(s=>`<path d="${d(s)}" stroke="${escXml(s.color)}" stroke-width="${sw(s)}" fill="none" stroke-linecap="round" stroke-linejoin="round"/>`).join('');
  svg+=ers.length?`<g mask="url(#ez)">${inner}</g>`:inner;
  svg+='</svg>';
  const blob=new Blob([svg],{type:'image/svg+xml'});
  const url=URL.createObjectURL(blob);download('doodle.svg',url);setTimeout(()=>URL.revokeObjectURL(url),4000);
});
const copyBtn=document.querySelector('#copyBtn');if(copyBtn)copyBtn.addEventListener('click',async()=>{
  const btn=copyBtn;
  if(!await spendDoodleAction())return;
  try{
    const w=LOGICAL_W*2,h=logicalH()*2,off=document.createElement('canvas');off.width=w;off.height=h;
    render(off.getContext('2d'),w,h);
    const blob=await new Promise(r=>off.toBlob(r,'image/png'));
    await navigator.clipboard.write([new ClipboardItem({'image/png':blob})]);
    btn.textContent='Copied!';setTimeout(()=>btn.textContent='Copy',1500);
  }catch(e){alert('Copy is not available in this browser. Use Download PNG instead.')}
});
window.addEventListener('resize',fitCanvas);
fitCanvas();
</script></main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
