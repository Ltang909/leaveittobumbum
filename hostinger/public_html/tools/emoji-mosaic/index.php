<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Emoji Mosaic | Turn Any Photo Into Emoji Art | Leave It to Bum Bum</title>
<meta name="description" content="Drop in a photo and Bum Bum rebuilds it as a mosaic of emoji, right in your browser. Your photo never leaves your device. Download the PNG or copy the emoji text. Free to try.">
<meta property="og:title" content="Emoji Mosaic | Turn Any Photo Into Emoji Art | Leave It to Bum Bum">
<meta property="og:description" content="Drop in a photo and Bum Bum rebuilds it as a mosaic of emoji, right in your browser. Your photo never leaves your device. Download the PNG or copy the emoji text. Free to try.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/emoji-mosaic/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og-receipt-reader.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Emoji Mosaic | Turn Any Photo Into Emoji Art | Leave It to Bum Bum">
<meta name="twitter:description" content="Drop in a photo and Bum Bum rebuilds it as a mosaic of emoji, right in your browser. Your photo never leaves your device. Download the PNG or copy the emoji text. Free to try.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og-receipt-reader.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/emoji-mosaic/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Emoji Mosaic",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Drop in a photo and Bum Bum rebuilds it as a mosaic of emoji, right in your browser. Your photo never leaves your device. Download the PNG or copy the emoji text. Free to try.",
  "url": "https://leaveittobumbum.com/tools/emoji-mosaic/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Where does my photo go?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Nowhere. The mosaic is built entirely in your browser, on your device. Your photo is never uploaded, never stored, and never shared. The only thing that talks to Bum Bum is the one action counted when you download or copy your mosaic."
      }
    },
    {
      "@type": "Question",
      "name": "How do I get a sharper mosaic?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Raise the Detail setting. More cells means finer detail. Photos with strong shapes and clear light work best: portraits, pets, logos, and products come out cleaner than busy group shots."
      }
    },
    {
      "@type": "Question",
      "name": "Why does my mosaic look different on my friend's phone?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Every device draws its own emoji, so Apple, Google, and Samsung versions of the same emoji look a little different. Bum Bum picks the emoji using the set on your device, so your mosaic is tuned to what you see. Their phone renders the same picks in its own style."
      }
    },
    {
      "@type": "Question",
      "name": "What can I do with the emoji text?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Paste it anywhere words go: posts, comments, chats, bios. Copy Emoji Text puts the whole grid on your clipboard as rows of emoji. It reads best in a monospace font or wherever emoji render at a fixed size."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Emoji Mosaic | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Emoji Mosaic","item":"https://leaveittobumbum.com/tools/emoji-mosaic/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use Emoji Mosaic","description":"Turn a photo into a mosaic of emoji, right in your browser.","step":[{"@type":"HowToStep","position":1,"name":"Drop in a photo, or pick one from your device."},{"@type":"HowToStep","position":2,"name":"Bum Bum rebuilds it as a mosaic of emoji in your browser. Your photo never leaves your device."},{"@type":"HowToStep","position":3,"name":"Tune the Detail and pick a palette theme: Mixed, Faces, Food, Nature, or Hearts."},{"@type":"HowToStep","position":4,"name":"Download a big PNG, or copy the emoji text to paste anywhere."}]}</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px;align-items:center}
.error{color:#b3261e;font-weight:700}
.hidden{display:none!important}
.controls{display:flex;gap:10px;flex-wrap:wrap;align-items:stretch;margin-top:16px}
.tool-group{display:flex;gap:10px;align-items:center;flex-wrap:wrap;background:#fff;border:2px solid var(--line);border-radius:12px;padding:8px 12px}
.tool-group .lbl{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;opacity:.55;flex:none}
.seg{display:flex;height:44px;border:2px solid var(--ink);border-radius:12px;overflow:hidden;background:#fff;flex:none}
.seg button{border:0;background:transparent;margin:0;padding:0 14px;font:inherit;font-weight:800;cursor:pointer;height:100%;display:flex;align-items:center;color:var(--ink)}
.seg button.on{background:var(--ink);color:#fff}
select.theme{height:44px;border:2px solid var(--ink);border-radius:12px;background:#fff;font:inherit;font-weight:800;padding:0 10px;color:var(--ink);cursor:pointer}
.canvas-wrap{background:#fff;border:2px solid var(--ink);border-radius:14px;padding:10px;box-shadow:4px 4px 0 var(--ink);margin-top:16px}
#mosaicCanvas{display:block;width:100%;height:auto;border-radius:8px;background:#fff}
.hint{font-size:13px;opacity:.7;margin:8px 0 0}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
.shell .button:disabled{opacity:.45;cursor:default;box-shadow:none}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
.panel h2{margin-top:0}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Emoji Mosaic"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-excited-v2.png" alt="Bum Bum looking excited about emoji art"><h1>Your photo, rebuilt in emoji.</h1><p class="lede bb-desc">Drop in a photo and Bum Bum rebuilds it as a mosaic of emoji, right here in your browser. Your photo <b>never leaves your device</b>: nothing is uploaded, stored, or shared. Tune the detail, pick a palette theme, then download a big PNG for posting or copy the whole mosaic as emoji text to paste anywhere. Building and playing with your mosaic is free. Downloading or copying it uses one action.</p>
<?php if ($isGuest && $usage && (int) $usage['used'] >= 2): ?><div class="nudge bb-guest-nudge" data-remaining="<?= (int) $usage['remaining'] ?>"><button type="button" class="bb-guest-nudge-dismiss" aria-label="Dismiss" style="float:right;border:none;background:none;cursor:pointer;font:inherit;font-weight:900;opacity:.55;padding:0 0 0 12px">&times;</button>You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions left. <a href="/account/?next=<?= urlencode('/tools/emoji-mosaic/') ?>">Create a free account</a> to save your work and keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>

<section class="panel" id="mosaicPanel">
<h2>Drop in a photo</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Choose a photo">
<p style="font-size:40px;margin:0">🧩</p>
<p><b>Drop a photo here</b>, paste one, or pick one below.</p>
<p class="meta" style="font-size:13px;opacity:.75">Portraits, pets, logos, and products work best. Your photo stays on your device the whole time.</p>
</div>
<input type="file" id="fileInput" accept="image/*" class="hidden">
<div class="btnrow">
<button type="button" class="button secondary" id="btnPick">Choose a photo</button>
<button type="button" class="button secondary hidden" id="btnNew">Use a different photo</button>
</div>
<div id="sceneSlot" style="margin-top:14px"></div>
<p id="toolError" class="error"></p>
<div id="stage" class="hidden">
<div class="controls">
<div class="tool-group"><span class="lbl">Detail</span><div class="seg" role="group" aria-label="Grid detail"><button type="button" data-grid="32">32</button><button type="button" data-grid="48" class="on">48</button><button type="button" data-grid="64">64</button><button type="button" data-grid="96">96</button></div></div>
<div class="tool-group"><span class="lbl">Palette</span><select class="theme" id="themeSel" aria-label="Palette theme"><option value="mixed" selected>Mixed (everything)</option><option value="faces">Faces</option><option value="food">Food</option><option value="nature">Nature</option><option value="hearts">Hearts</option></select></div>
</div>
<div class="canvas-wrap"><canvas id="mosaicCanvas"></canvas></div>
<p class="hint" id="gridInfo"></p>
<div class="btnrow">
<button type="button" class="button" id="btnPng">Download PNG</button>
<button type="button" class="button secondary" id="btnText">Copy emoji text</button>
<span class="hint" style="margin:0">1 action per download or copy.</span>
</div>
</div>
<div id="upgrade-slot"></div>
</section>

<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Do I need an account?</summary>
<p>No. Build mosaics as a guest with 15 free actions, or create a free account for 75 actions every month. Making and previewing a mosaic is free; one action is counted when you download or copy it. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>Where does my photo go?</summary>
<p>Nowhere. The mosaic is built entirely in your browser, on your device. Your photo is never uploaded, never stored, and never shared. The only thing that talks to Bum Bum is the one action counted when you download or copy your mosaic.</p>
</details>
<details>
<summary>How do I get a sharper mosaic?</summary>
<p>Raise the Detail setting. More cells means finer detail. Photos with strong shapes and clear light work best: portraits, pets, logos, and products come out cleaner than busy group shots.</p>
</details>
<details>
<summary>Why does my mosaic look different on my friend's phone?</summary>
<p>Every device draws its own emoji, so Apple, Google, and Samsung versions of the same emoji look a little different. Bum Bum picks the emoji using the set on your device, so your mosaic is tuned to what you see. Their phone renders the same picks in its own style.</p>
</details>
<details>
<summary>What can I do with the emoji text?</summary>
<p>Paste it anywhere words go: posts, comments, chats, bios. Copy Emoji Text puts the whole grid on your clipboard as rows of emoji. It reads best in a monospace font or wherever emoji render at a fixed size.</p>
</details>
</section>
<section class="panel" aria-label="More tiny tools">
<h2 style="margin-top:0">More tiny tools</h2>
<p><a href="/tools/doodle/">Online Sketchpad</a> - A pocket sketchpad for signatures, diagrams, and masterpieces.</p>
<p><a href="/tools/image-converter/">Image Converter</a> - WebP to PNG, JPG to WebP, whatever to whatever, right in your browser.</p>
<p><a href="/tools/purr-code/">QR Code Generator</a> - Your link, but cute.</p>
</section>
<script src="/tools/error-scenes.js"></script>
<script>
bbTrack('tool_opened',{tool:'emoji-mosaic'});

/* Emoji candidate lists, grouped by theme. The palette is calibrated at
   runtime against the emoji set on the visitor's own device, so matches
   reflect the glyphs they actually see. */
const FACES=['😀','😃','😄','😁','😆','😅','😂','🤣','😊','😇','🙂','😉','😌','😍','🥰','😘','😋','😛','😝','😜','🤪','😎','🥳','😏','😴','🤤','😪','😵','🥴','🤢','🤮','🤧','😷','🤒','🤑','🤠','😈','👿','👹','👺','🤡','💩','👻','💀','👽','👾','🤖','🎃','😺','😸','😹','😻','😼','😽','🙀','😿','😾','🤯','🥺','😳','😱','😨','😰','🥵','🥶','😤','😠','😡'];
const ANIMALS=['🐶','🐱','🐭','🐹','🐰','🦊','🐻','🐼','🐨','🐯','🦁','🐮','🐷','🐸','🐵','🐔','🐧','🐦','🦆','🦅','🦉','🦇','🐺','🐴','🦄','🐝','🦋','🐌','🐞','🐢','🐍','🐙','🦀','🐠','🐟','🐬','🐳','🦈','🐊','🐅','🦓','🐘','🐎','🐖','🐑','🐐','🐕','🐈','🐓','🐇','🦔','🦩','🦢','🦚','🦜','🐳','🐋','🦭'];
const FOOD=['🍏','🍎','🍐','🍊','🍋','🍉','🍇','🍓','🫐','🍒','🍑','🥭','🍍','🥥','🥝','🍅','🍆','🥑','🥦','🥬','🥒','\u{1F336}\uFE0F','🌽','🥕','🥔','🍠','🥐','🥯','🍞','🥖','🧀','🥚','🍳','🥞','🧇','🥓','🥩','🍗','🌭','🍔','🍟','🍕','🥪','🥙','🌮','🌯','🥗','🍝','🍜','🍲','🍛','🍣','🍱','🍤','🍙','🍚','🍰','🎂','🍮','🍭','🍬','🍫','🍿','🍩','🍪','🍯','☕','🍵','🍺','🍷','🥤','🧃','🍾','🥛','🧋'];
const NATURE=['🌸','🌹','🥀','🌺','🌻','🌼','🌷','🌱','🌲','🌳','🌴','🌵','🌿','\u2618\uFE0F','🍀','🍁','🍂','🍄','🌍','🌞','🌝','🌚','🌕','🌑','⭐','🌟','💫','🔥','💧','🌊','\u2744\uFE0F','\u2603\uFE0F','⛄','🌈','\u2600\uFE0F','⛅','\u2601\uFE0F','🌩\uFE0F','🌧\uFE0F','🌪\uFE0F','🌾','🪴','🍃','🌊','🏝\uFE0F','🏔\uFE0F','🌋','🌅','🌄','🌠'];
const HEARTS=['\u2764\uFE0F','🧡','💛','💚','💙','💜','🖤','🤍','🤎','💔','💖','💗','💓','💞','💕','💟','\u2763\uFE0F','💝','💘','💖','🩷','🩵','🩶'];
const SHAPES=['🔴','🟠','🟡','🟢','🔵','🟣','🟤','⚫','⚪','🟥','🟧','🟨','🟩','🟦','🟪','🟫','\u2B1B\uFE0F','\u2B1C\uFE0F','🔶','🔷','🔸','🔹','💠'];
const EXTRAS=['✨','⚡','💥','💦','💨','🎈','🎁','🎀','👑','💎','🚗','✈️','🚀','⚽','🏀','🏈','⚾','🎾','🏐','🎱','🎸','🎺','🎻','🥁','🎲','🎯','💡','🔦','📚','🖍\uFE0F','✏\uFE0F','📌','🔔','🎵','🎶','🌟','🍀'];
const THEME_LISTS={
  faces:FACES.concat(ANIMALS.slice(0,2)),
  food:FOOD,
  nature:NATURE.concat(['🦋','🐝','🐞','🌸']),
  hearts:HEARTS.concat(SHAPES),
  mixed:[...new Set(FACES.concat(ANIMALS,FOOD,NATURE,HEARTS,SHAPES,EXTRAS))]
};
const THEME_LABELS={mixed:'Mixed',faces:'Faces',food:'Food',nature:'Nature',hearts:'Hearts'};
const EMOJI_FONT_STACK='"Apple Color Emoji","Segoe UI Emoji","Noto Color Emoji",sans-serif';

const dropzone=document.querySelector('#dropzone');
const fileInput=document.querySelector('#fileInput');
const btnPick=document.querySelector('#btnPick');
const btnNew=document.querySelector('#btnNew');
const stage=document.querySelector('#stage');
const sceneSlot=document.querySelector('#sceneSlot');
const toolError=document.querySelector('#toolError');
const previewCanvas=document.querySelector('#mosaicCanvas');
const previewCtx=previewCanvas.getContext('2d');
const gridInfo=document.querySelector('#gridInfo');
const themeSel=document.querySelector('#themeSel');
const btnPng=document.querySelector('#btnPng');
const btnText=document.querySelector('#btnText');

const MAX_FILE_BYTES=15*1024*1024;
let gridSize=48;
let currentTheme='mixed';
let sourceImg=null,sourceW=0,sourceH=0;
let gridChars=null,gridW=0,gridH=0;
let generatedForCurrent=false;
const paletteCache={};

function showScene(state,opts){
  sceneSlot.innerHTML='';
  if(window.BBErrorScenes)BBErrorScenes.mount(sceneSlot,state,opts||{});
  bbTrack('emoji_mosaic_error',{scene:state});
}
function clearScene(){sceneSlot.innerHTML=''}

/* Build the colour table for one theme: draw each candidate emoji once on a
   small canvas and average its opaque pixels. Cached per theme. */
function buildPalette(list){
  const size=32;
  const c=document.createElement('canvas');c.width=size;c.height=size;
  const g=c.getContext('2d',{willReadFrequently:true});
  g.font=size+'px '+EMOJI_FONT_STACK;
  g.textAlign='center';g.textBaseline='middle';
  const out=[];
  for(const ch of list){
    g.clearRect(0,0,size,size);
    g.fillText(ch,size/2,size/2+2);
    let d;
    try{d=g.getImageData(0,0,size,size).data}catch(e){continue}
    let r=0,gg=0,b=0,n=0;
    for(let i=0;i<d.length;i+=4){
      if(d[i+3]>24){r+=d[i];gg+=d[i+1];b+=d[i+2];n++}
    }
    if(n>10)out.push({ch:ch,r:r/n,g:gg/n,b:b/n});
  }
  return out;
}
function getPalette(theme){
  if(!paletteCache[theme])paletteCache[theme]=buildPalette(THEME_LISTS[theme]);
  return paletteCache[theme];
}
/* Perceptually weighted RGB distance (redmean approximation). */
function colorDist(r1,g1,b1,r2,g2,b2){
  const rm=(r1+r2)/2,dr=r1-r2,dg=g1-g2,db=b1-b2;
  return (2+rm/256)*dr*dr+4*dg*dg+(2+(255-rm)/256)*db*db;
}

function buildMosaic(){
  if(!sourceImg)return;
  const palette=getPalette(currentTheme);
  if(!palette.length){showScene('processing_failed',{});return}
  const gw=gridSize;
  const gh=Math.max(1,Math.round(gw*sourceH/sourceW));
  const off=document.createElement('canvas');off.width=gw;off.height=gh;
  const og=off.getContext('2d',{willReadFrequently:true});
  og.fillStyle='#ffffff';og.fillRect(0,0,gw,gh);
  og.drawImage(sourceImg,0,0,gw,gh);
  const px=og.getImageData(0,0,gw,gh).data;
  gridChars=new Array(gw*gh);gridW=gw;gridH=gh;
  for(let i=0;i<gw*gh;i++){
    const r=px[i*4],g=px[i*4+1],b=px[i*4+2];
    let bd=Infinity,bc=palette[0].ch;
    for(let j=0;j<palette.length;j++){
      const p=palette[j];
      const d=colorDist(r,g,b,p.r,p.g,p.b);
      if(d<bd){bd=d;bc=p.ch}
    }
    gridChars[i]=bc;
  }
  drawMosaic(previewCtx,previewCanvas,previewCell());
  gridInfo.textContent=gw+' across, '+gh+' down, '+(gw*gh).toLocaleString()+' emoji. Matched against '+palette.length+' emoji in the '+THEME_LABELS[currentTheme]+' palette, using the emoji set on your device.';
  if(!generatedForCurrent){
    generatedForCurrent=true;
    bbTrack('emoji_mosaic_generated',{cells:gw*gh,theme:currentTheme});
  }
}
function previewCell(){return gridW<=32?24:gridW<=48?18:gridW<=64?14:11}
function drawMosaic(g,cv,cell){
  cv.width=gridW*cell;cv.height=gridH*cell;
  g.fillStyle='#ffffff';g.fillRect(0,0,cv.width,cv.height);
  g.font=Math.round(cell*0.92)+'px '+EMOJI_FONT_STACK;
  g.textAlign='center';g.textBaseline='middle';
  for(let y=0;y<gridH;y++){
    for(let x=0;x<gridW;x++){
      g.fillText(gridChars[y*gridW+x],x*cell+cell/2,y*cell+cell/2+1);
    }
  }
}

function loadFile(file){
  toolError.textContent='';clearScene();
  if(!file)return;
  if(!file.type||!file.type.startsWith('image/')){
    showScene('invalid_type',{bumbum:'That format is not one I can open. Try one of these instead: JPG, PNG, WebP, or GIF.'});
    return;
  }
  if(file.size>MAX_FILE_BYTES){
    showScene('file_too_large',{});
    return;
  }
  const url=URL.createObjectURL(file);
  const img=new Image();
  img.onload=()=>{
    sourceImg=img;sourceW=img.naturalWidth;sourceH=img.naturalHeight;
    generatedForCurrent=false;
    stage.classList.remove('hidden');
    btnNew.classList.remove('hidden');
    buildMosaic();
    URL.revokeObjectURL(url);
  };
  img.onerror=()=>{
    URL.revokeObjectURL(url);
    showScene('upload_failed',{onCta:()=>{clearScene();fileInput.click()}});
  };
  img.src=url;
}

btnPick.addEventListener('click',()=>fileInput.click());
btnNew.addEventListener('click',()=>fileInput.click());
dropzone.addEventListener('click',()=>fileInput.click());
dropzone.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();fileInput.click()}});
dropzone.addEventListener('dragover',e=>{e.preventDefault();dropzone.classList.add('over')});
dropzone.addEventListener('dragleave',()=>dropzone.classList.remove('over'));
dropzone.addEventListener('drop',e=>{e.preventDefault();dropzone.classList.remove('over');if(e.dataTransfer.files.length)loadFile(e.dataTransfer.files[0])});
document.addEventListener('paste',e=>{if(e.clipboardData&&e.clipboardData.files.length)loadFile(e.clipboardData.files[0])});
fileInput.addEventListener('change',()=>{if(fileInput.files.length)loadFile(fileInput.files[0]);fileInput.value=''});

document.querySelectorAll('[data-grid]').forEach(b=>b.addEventListener('click',()=>{
  document.querySelectorAll('[data-grid]').forEach(x=>x.classList.remove('on'));
  b.classList.add('on');
  gridSize=+b.dataset.grid;
  buildMosaic();
}));
themeSel.addEventListener('change',()=>{currentTheme=themeSel.value;buildMosaic()});

/* Export spend: mirrors the Online Sketchpad. Previewing is free; one action
   is counted only when a download or copy actually happens. */
async function spendMosaicAction(){
  try{
    const s=await fetch('/api/session.php').then(r=>r.json());
    const key='emoji-mosaic-'+Date.now()+'-'+Math.random().toString(36).slice(2,10);
    const res=await fetch('/api/tools/emoji-mosaic.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({csrf:s.csrf,idempotencyKey:key})});
    const data=await res.json().catch(()=>({}));
    if(res.status===402){
      if(data&&data.signup_required)return false;
      document.querySelector('#upgrade-slot').innerHTML='<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">Downloading or copying a mosaic uses one action. Helper gives you 1,500 actions for $12/month.</p><p><a class="button" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" href="/account/">See your usage</a></p></div>';
      return false;
    }
    if(!res.ok){alert(data.error||'Could not save. Try again.');return false}
    if(data&&data.usage)document.dispatchEvent(new CustomEvent('bb:usage',{detail:data.usage}));
    return true;
  }catch(e){alert('Could not reach Bum Bum. Check your connection and try again.');return false}
}

btnPng.addEventListener('click',async()=>{
  if(!gridChars)return;
  if(!await spendMosaicAction())return;
  const cell=Math.min(28,Math.max(8,Math.floor(3200/gridW)));
  const off=document.createElement('canvas');
  drawMosaic(off.getContext('2d'),off,cell);
  const a=document.createElement('a');
  a.href=off.toDataURL('image/png');
  a.download='emoji-mosaic.png';
  document.body.appendChild(a);a.click();a.remove();
  bbTrack('emoji_mosaic_export',{how:'png',cells:gridW*gridH});
});

btnText.addEventListener('click',async()=>{
  if(!gridChars)return;
  if(!await spendMosaicAction())return;
  const rows=[];
  for(let y=0;y<gridH;y++)rows.push(gridChars.slice(y*gridW,(y+1)*gridW).join(''));
  const text=rows.join('\n');
  let ok=false;
  try{await navigator.clipboard.writeText(text);ok=true}
  catch(e){
    const ta=document.createElement('textarea');
    ta.value=text;ta.style.position='fixed';ta.style.opacity='0';
    document.body.appendChild(ta);ta.select();
    try{ok=document.execCommand('copy')}catch(x){}
    ta.remove();
  }
  if(ok){
    bbTrack('emoji_mosaic_export',{how:'text',cells:gridW*gridH});
    btnText.textContent='Copied!';
    setTimeout(()=>btnText.textContent='Copy emoji text',1600);
  }else{
    alert('Copy is not available in this browser. Use Download PNG instead.');
  }
});
</script>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
