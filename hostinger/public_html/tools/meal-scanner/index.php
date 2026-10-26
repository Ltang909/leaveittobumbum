<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Meal Scanner | Snap Your Food, Get Calories and Macros | Leave It to Bum Bum</title>
<meta name="description" content="Snap a photo of your meal. Bum Bum estimates the calories, protein, carbs, and fat in seconds, with an honest range instead of a fake-exact number. Free to try.">
<meta property="og:title" content="Meal Scanner | Snap Your Food, Get Calories and Macros | Leave It to Bum Bum">
<meta property="og:description" content="Snap a photo of your meal. Bum Bum estimates the calories, protein, carbs, and fat in seconds, with an honest range instead of a fake-exact number. Free to try.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/meal-scanner/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og-receipt-reader.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Meal Scanner | Snap Your Food, Get Calories and Macros | Leave It to Bum Bum">
<meta name="twitter:description" content="Snap a photo of your meal. Bum Bum estimates the calories, protein, carbs, and fat in seconds, with an honest range instead of a fake-exact number. Free to try.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og-receipt-reader.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/meal-scanner/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Meal Scanner",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Snap a photo of your meal. Bum Bum estimates the calories, protein, carbs, and fat in seconds, with an honest range instead of a fake-exact number. Free to try.",
  "url": "https://leaveittobumbum.com/tools/meal-scanner/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "How accurate is it?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "It is an approximation, on purpose. Every scan gives you a range, not a single fake-exact number, because portion size is the swing factor in any photo estimate. Expect it to land in the right neighborhood, not on the exact gram."
      }
    },
    {
      "@type": "Question",
      "name": "Where does my photo go?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Your photo is sent to Bum Bum's reader to pull out the food and numbers, then discarded. It is never stored and never shared. Your daily totals live only in your browser, on your device."
      }
    },
    {
      "@type": "Question",
      "name": "What if it gets my meal wrong?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Every item is editable. Fix the numbers, change the portion with the size picker, remove what it hallucinated, or add what it missed."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Meal Scanner | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Meal Scanner","item":"https://leaveittobumbum.com/tools/meal-scanner/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use Meal Scanner","description":"Snap a photo of your meal and get estimated calories and macros.","step":[{"@type":"HowToStep","position":1,"name":"Snap or upload a photo of your meal. Screenshots work too."},{"@type":"HowToStep","position":2,"name":"Bum Bum reads the plate and estimates each item's calories, protein, carbs, and fat, with a range."},{"@type":"HowToStep","position":3,"name":"Fix anything it misjudged, especially portions."},{"@type":"HowToStep","position":4,"name":"Add the meal to your running total for the day, kept right in your browser."}]}</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s;min-height:500px;display:flex;flex-direction:column;align-items:center;justify-content:center}
@media(max-width:860px){.dropzone{min-height:360px}}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
.progress-wrap{margin-top:14px}
.progress-bar{height:10px;border-radius:999px;background:#f0e8d4;overflow:hidden}
#barFill{height:100%;width:0%;border-radius:999px;background:#2f2a22;transition:width .2s}
#progText{font-size:13px;font-weight:700;margin:8px 0 0;opacity:.75}
.error{color:#b3261e;font-weight:700}
.hidden{display:none!important}
.meal-card{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:14px;background:#fff;padding:16px;margin-bottom:14px}
.meal-card .card-head input{font:inherit;font-weight:800;font-size:17px;border:none;border-bottom:1px dashed #ddd1b8;background:transparent;width:100%;padding:2px 0}
.meal-card .card-head input:focus{border-color:#b3a37e;outline:none}
.table-scroll{overflow-x:auto;margin-top:8px}
.items-table{width:100%;border-collapse:collapse;min-width:640px}
.items-table th{text-align:left;font-size:12px;font-weight:800;opacity:.7;padding:6px 8px;border-bottom:1px solid #e7dcc3;white-space:nowrap}
.items-table td{padding:4px;border-bottom:1px solid #f2ecdc;vertical-align:middle}
.items-table input{width:100%;padding:8px 10px;border:1px solid #ddd1b8;border-radius:8px;font:inherit;background:#fff}
.items-table input:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.items-table td.num{width:86px}
.items-table td.scale{width:104px}
.items-table td.scale select{width:100%;padding:8px 6px;border:1px solid #ddd1b8;border-radius:8px;font:inherit;background:#fff}
.items-table td.del{width:44px;text-align:center}
.row-del{border:1px solid #ddd1b8;background:#fff;border-radius:999px;width:30px;height:30px;font-size:15px;cursor:pointer;line-height:1;margin-top:0}
/* On phones the item columns squeeze into mush, so each item becomes
   a small card of its own: every field gets its own full-width row
   with its label on top (the data-labels the row builder stamps),
   and the remove button pins to the card's top corner, obviously
   attached to its item. Desktop keeps the table exactly as it was. */
@media(max-width:860px){
.table-scroll{overflow-x:visible}
.items-table{min-width:0}
.items-table thead{display:none}
.items-table,.items-table tbody,.items-table tr,.items-table td{display:block}
.items-table tr{position:relative;border:1px solid #e7dcc3;border-radius:10px;background:#fff;padding:12px;margin-bottom:10px}
.items-table td{border-bottom:none;padding:5px 0;width:auto}
.items-table td.num,.items-table td.scale{width:auto}
.items-table td[data-label]::before{content:attr(data-label);display:block;font-size:11px;font-weight:800;letter-spacing:.05em;text-transform:uppercase;opacity:.55;margin-bottom:4px}
.items-table td:first-child{padding-right:46px}
.items-table td.del{position:absolute;top:10px;right:10px;width:auto;padding:0}
}
.unc{font-weight:900;color:#b3541e;cursor:help}
.totals-bar{background:#fdf8ef;border:1px solid #e7dcc3;border-radius:10px;padding:12px 14px;margin-top:14px;font-size:15px}
.totals-bar b{font-size:17px}
.read-note{background:#fdf8ef;border:1px solid #e7dcc3;border-radius:10px;padding:10px 14px;font-size:14px;margin:10px 0}
.note-line{font-size:14px;margin:4px 0}
.panel h2{margin-top:0}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
.queue-item{display:flex;gap:12px;align-items:center;border:1px solid #e2d7bf;border-radius:12px;background:#fff;padding:8px 12px;margin-top:8px}
.queue-item img{width:52px;height:52px;object-fit:cover;border-radius:8px;border:1px solid #e7dcc3}
.queue-item .meta{font-size:13px;opacity:.75}
.mini{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:6px 12px;font-weight:700;font-size:13px;cursor:pointer}
.day-bar{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Meal Scanner"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/icons/meal-scanner.png" alt="Meal Scanner"><h1>Plates in, numbers out.</h1><p class="lede bb-desc">Too busy to type your lunch into a tracker? Snap the plate instead. Bum Bum reads the photo and estimates the calories, protein, carbs, and fat in seconds. No database searching, no barcode scanning, no weighing anything. Every estimate comes as an <b>honest range</b>, not a fake-exact number, because any app that claims to know your burrito to the calorie is lying to you. Fix what it misjudged, then add the meal to your running total for the day. One action per meal scanned. Your photo is read to pull the numbers, then discarded; your totals live only in your browser.</p>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to scan meals</h2><a class="button" href="/account/?next=<?= urlencode('/tools/meal-scanner/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/meal-scanner/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/meal-scanner/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage && (int) $usage['used'] >= 2): ?><div class="nudge bb-guest-nudge" data-remaining="<?= (int) $usage['remaining'] ?>"><button type="button" class="bb-guest-nudge-dismiss" aria-label="Dismiss" style="float:right;border:none;background:none;cursor:pointer;font:inherit;font-weight:900;opacity:.55;padding:0 0 0 12px">&times;</button>You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions left. <a href="/account/?next=<?= urlencode('/tools/meal-scanner/') ?>">Create a free account</a> to save your work and keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>

<section class="panel" id="uploadPanel">
<h2>Snap or upload your meal</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Choose a meal photo">
<p style="font-size:40px;margin:0">🥗</p>
<p><b>Drop a meal photo here</b>, paste a screenshot, or pick one below.</p>
<p class="meta" style="font-size:13px;opacity:.75">Tip: shoot from above, whole plate in the frame. Desk lunches, client dinners, and drive-thru bags all count. Up to 5 at a time.</p>
</div>
<input type="file" id="fileGallery" accept="image/*" multiple class="hidden">
<input type="file" id="fileCamera" accept="image/*" capture="environment" class="hidden">
<div class="btnrow">
<button type="button" class="button secondary" id="btnGallery">Upload images</button>
<button type="button" class="button secondary" id="btnCamera">Use camera</button>
</div>
<div id="queueList"></div>
<div class="btnrow"><button type="button" class="button hidden" id="processBtn">Scan meal</button></div>
<div class="progress-wrap hidden" id="batchProgress"><div class="progress-bar"><div id="barFill"></div></div><p id="progText">Warming up...</p></div>
<div id="sceneSlot" style="margin-top:14px"></div>
<p id="batchError" class="error"></p>
</section>

<section class="panel hidden" id="dayPanel">
<h2>Today so far</h2>
<div class="day-bar">
<div id="dayTotals" style="font-size:15px"></div>
<div style="flex:1"></div>
<button type="button" class="mini" id="dayReset">Reset day</button>
</div>
<p class="meta" style="font-size:13px;opacity:.75;margin-bottom:0">Kept only in this browser, on this device. Nothing is uploaded or saved to an account.</p>
</section>

<section class="panel hidden" id="resultsPanel">
<h2>Here is what Bum Bum sees</h2>
<div class="btnrow" style="margin-top:0;margin-bottom:14px">
<button type="button" class="button secondary" id="newBatchBtn">New batch</button>
</div>
<div id="cardsWrap"></div>
<p class="meta" style="font-size:13px;opacity:.75">Estimates are for general information only and are not medical or dietary advice.</p>
</section>
<?php endif; ?>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Do I need an account?</summary>
<p>No. Scan as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>How accurate is it?</summary>
<p>It is an approximation, on purpose. Portion size is the swing factor in any photo estimate, so every scan shows a range instead of pretending to be exact. Expect the right neighborhood: a big restaurant plate lands higher than the same dish at home, and the range widens for fried food, sauces, and mixed dishes. Fix the portion with the size picker when it looks off; you know your plate better than any camera.</p>
</details>
<details>
<summary>Where does my photo go?</summary>
<p>It is sent to Bum Bum's reader to pull out the food and the numbers, then discarded. It is never stored and never shared. Your daily totals never leave this browser.</p>
</details>
<details>
<summary>What if it gets my meal wrong?</summary>
<p>Every number is editable. Change a portion with the size picker, type over any figure, remove what it imagined, or add the side of fries it politely overlooked.</p>
</details>
</section>
<section class="panel" aria-label="More tiny tools">
<h2 style="margin-top:0">More tiny tools</h2>
<p><a href="/tools/receipt-reader/">Receipt Scanner</a> - Snap receipts, get clean tables.</p>
<p><a href="/tools/poster-to-calendar/">Poster to Calendar</a> - Snap a poster, get a ready-to-save calendar event.</p>
<p><a href="/tools/notes/">Voice Notes to Text</a> - Talk it out and get a live transcript you can copy or download.</p>
</section>
</main>
<script src="/tools/error-scenes.js"></script>
<script>
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/meal-scanner.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}bbTrack('tool_opened',{tool:'meal-scanner'});

const dropzone=document.querySelector('#dropzone');
const fileGallery=document.querySelector('#fileGallery');
const fileCamera=document.querySelector('#fileCamera');
const queueList=document.querySelector('#queueList');
const processBtn=document.querySelector('#processBtn');
const batchError=document.querySelector('#batchError');
const sceneSlot=document.querySelector('#sceneSlot');
const batchProgress=document.querySelector('#batchProgress');
const barFill=document.querySelector('#barFill');
const progText=document.querySelector('#progText');
const resultsPanel=document.querySelector('#resultsPanel');
const cardsWrap=document.querySelector('#cardsWrap');

const MAX_BATCH=5;
const MAX_FILE_BYTES=15*1024*1024;
let queue=[];
let batchRunning=false;
let cardCount=0;

document.querySelector('#btnGallery').addEventListener('click',()=>fileGallery.click());
document.querySelector('#btnCamera').addEventListener('click',()=>fileCamera.click());
dropzone.addEventListener('click',()=>fileGallery.click());
dropzone.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();fileGallery.click()}});
dropzone.addEventListener('dragover',e=>{e.preventDefault();dropzone.classList.add('over')});
dropzone.addEventListener('dragleave',()=>dropzone.classList.remove('over'));
dropzone.addEventListener('drop',e=>{e.preventDefault();dropzone.classList.remove('over');loadFiles(e.dataTransfer.files)});
document.addEventListener('paste',e=>{if(e.clipboardData&&e.clipboardData.files.length)loadFiles(e.clipboardData.files)});
fileGallery.addEventListener('change',()=>{loadFiles(fileGallery.files);fileGallery.value=''});
fileCamera.addEventListener('change',()=>{loadFiles(fileCamera.files);fileCamera.value=''});

function showScene(state,opts){
  sceneSlot.innerHTML='';
  if(window.BBErrorScenes)BBErrorScenes.mount(sceneSlot,state,opts||{});
  bbTrack('meal_error',{scene:state});
}
function clearScene(){sceneSlot.innerHTML=''}

function loadFiles(fileList){
  batchError.textContent='';clearScene();
  const files=[...fileList];
  if(!files.length)return;
  const imgs=files.filter(f=>f.type.startsWith('image/'));
  if(!imgs.length){
    showScene('invalid_type',{bumbum:'That format is not one I can open. Try one of these instead: JPG, PNG, or WebP.'});
    return;
  }
  if(imgs.some(f=>f.size>MAX_FILE_BYTES)){
    showScene('file_too_large',{});
    return;
  }
  const readOne=f=>new Promise(res=>{const r=new FileReader();r.onload=()=>res(r.result);r.onerror=()=>res('');r.readAsDataURL(f)});
  (async()=>{
    for(const f of imgs){
      if(queue.length>=MAX_BATCH)break;
      const dataUrl=await readOne(f);
      if(dataUrl)queue.push({id:crypto.randomUUID(),dataUrl,name:f.name||'meal',status:'queued'});
    }
    if(queue.length>=MAX_BATCH)batchError.textContent='Bum Bum scans up to '+MAX_BATCH+' meals at a time. The first '+MAX_BATCH+' are queued.';
    renderQueue();
  })();
}

function renderQueue(){
  queueList.innerHTML='';
  queue.forEach(q=>{
    const div=document.createElement('div');div.className='queue-item';
    const label=q.status==='done'?'Scanned':q.status==='reading'?'Scanning...':q.status==='error'?(q.noFood?'No food found':'Could not scan it'):q.status==='blocked'?'Waiting on actions':'Queued';
    div.innerHTML='<img src="'+q.dataUrl+'" alt=""><div><b>'+esc(q.name)+'</b><div class="meta">'+label+'</div></div>';
    queueList.appendChild(div);
  });
  const pending=queue.filter(q=>q.status==='queued');
  processBtn.classList.toggle('hidden',!pending.length||batchRunning);
  processBtn.textContent=pending.length>1?'Scan '+pending.length+' meals ('+pending.length+' actions)':'Scan this meal (1 action)';
}

function downscale(dataUrl){
  return new Promise((resolve,reject)=>{
    const img=new Image();
    img.onload=()=>{
      const maxDim=1568;
      let w=img.naturalWidth,h=img.naturalHeight;
      const s=Math.min(1,maxDim/Math.max(w,h));
      w=Math.max(1,Math.round(w*s));h=Math.max(1,Math.round(h*s));
      const c=document.createElement('canvas');c.width=w;c.height=h;
      const ctx=c.getContext('2d');
      ctx.fillStyle='#ffffff';ctx.fillRect(0,0,w,h);
      ctx.drawImage(img,0,0,w,h);
      resolve(c.toDataURL('image/jpeg',0.87));
    };
    img.onerror=reject;
    img.src=dataUrl;
  });
}

processBtn.addEventListener('click',async()=>{
  if(batchRunning)return;
  batchRunning=true;batchError.textContent='';clearScene();
  renderQueue();
  batchProgress.classList.remove('hidden');
  barFill.style.width='5%';
  const todo=queue.filter(q=>q.status==='queued');
  let done=0,blocked=false,lastFail=null,sawNoFood=false;
  for(const q of todo){
    q.status='reading';renderQueue();
    progText.textContent='Scanning meal '+(done+1)+' of '+todo.length+'...';
    try{
      const small=await downscale(q.dataUrl);
      const{response,data}=await apiCall({action:'parse',image:small,idempotencyKey:q.id});
      if(response.status===402)throw new Error('actions');
      if(!response.ok){const err=new Error(data.error||'scan failed');err.serverMsg=data.error||'';err.noFood=data.code==='no_food';throw err}
      addCard(data.meal,q.name);
      q.status='done';
      bbTrack('meal_scanned',{items:(data.meal.items||[]).length});
    }catch(e){
      if(e.message==='actions'){q.status='blocked';blocked=true}
      else{q.status='error';q.noFood=!!e.noFood;if(e.noFood)sawNoFood=true;else lastFail=e}
    }
    done++;
    barFill.style.width=(5+done/todo.length*90)+'%';
    renderQueue();
    if(blocked)break;
  }
  if(queue.some(q=>q.status==='done')&&window.BBDropStrip)window.BBDropStrip.show('meal-scanner');
  if(blocked){
    queue.forEach(q=>{if(q.status==='queued')q.status='blocked'});
    batchError.textContent='Out of actions for this month. The meals above were scanned; the rest are waiting.';
    renderQueue();
  }else if(sawNoFood){
    showScene('empty_input',{petpet:'I do not see food here.',bumbum:'No plate, no numbers. Try a photo of the meal itself, close up, in good light.',cta:'Try another photo',onCta:()=>{clearScene();document.querySelector('#uploadPanel').scrollIntoView({behavior:'smooth'})}});
  }else if(lastFail&&queue.some(q=>q.status==='error')){
    showScene('processing_failed',{onCta:()=>{queue.forEach(q=>{if(q.status==='error'&&!q.noFood)q.status='queued'});clearScene();renderQueue();processBtn.click()}});
    if(lastFail.serverMsg)batchError.textContent=lastFail.serverMsg;
  }
  barFill.style.width='100%';
  progText.textContent='Done.';
  batchRunning=false;
  renderQueue();
  setTimeout(()=>batchProgress.classList.add('hidden'),800);
});

/* ---------- Meal cards ---------- */
function addCard(meal,fileName){
  cardCount++;
  resultsPanel.classList.remove('hidden');
  const card=document.createElement('div');
  card.className='meal-card';
  const wide=(meal.items||[]).some(it=>it.uncertain);
  card.innerHTML=
    '<div class="card-head"><input class="fMealName" type="text" maxlength="120" value="'+esc(meal.mealName||'Meal ' + cardCount)+'" aria-label="Meal name"></div>'+
    '<div class="read-note">Bum Bum\'s best estimate, not a lab result. The range is the honest part: portion size swings everything. Fix what it misjudged'+(wide?', and treat the items marked ~ as extra rough':'')+'.</div>'+
    '<div class="table-scroll"><table class="items-table"><thead><tr><th>Item</th><th>Portion</th><th>Cal</th><th>Protein g</th><th>Carbs g</th><th>Fat g</th><th>Size</th><th></th></tr></thead><tbody class="itemsBody"></tbody></table></div>'+
    '<div class="btnrow"><button type="button" class="mini addRow">+ Add item</button></div>'+
    '<div class="totals-bar"></div>'+
    ((meal.notes||[]).length?'<div style="margin-top:10px">'+meal.notes.map(n=>'<p class="note-line">Watch out: '+esc(n)+'</p>').join('')+'</div>':'')+
    '<div class="btnrow"><button type="button" class="button countBtn">Add to today\'s total</button><button type="button" class="mini removeCard">Remove</button></div>'+
    '<p class="card-note meta" style="font-size:13px"></p>';
  const body=card.querySelector('.itemsBody');
  (meal.items||[]).forEach(it=>body.appendChild(itemRow(it)));
  card.querySelector('.addRow').addEventListener('click',()=>{body.appendChild(itemRow(null));refreshTotals(card)});
  card.querySelector('.removeCard').addEventListener('click',()=>{card.remove();if(!cardsWrap.children.length)resultsPanel.classList.add('hidden')});
  card.querySelector('.countBtn').addEventListener('click',()=>{
    const t=collectTotals(card);
    if(!t||t.calories<=0){card.querySelector('.card-note').textContent='Nothing to add yet.';return}
    addToDay(t);
    card.dataset.counted='1';
    const btn=card.querySelector('.countBtn');btn.disabled=true;btn.textContent='Counted in today';
    card.querySelector('.card-note').textContent='Added to today\'s total below.';
    bbTrack('meal_counted',{calories:t.calories});
  });
  card.addEventListener('input',()=>refreshTotals(card));
  cardsWrap.appendChild(card);
  refreshTotals(card);
  if(cardsWrap.children.length===1)resultsPanel.scrollIntoView({behavior:'smooth',block:'start'});
}

function itemRow(it){
  it=it||{name:'',portion:'',calories:0,caloriesLow:0,caloriesHigh:0,protein:0,carbs:0,fat:0,uncertain:false};
  const tr=document.createElement('tr');
  tr.dataset.baseCal=it.calories||0;tr.dataset.baseLow=it.caloriesLow||0;tr.dataset.baseHigh=it.caloriesHigh||0;
  tr.dataset.baseP=it.protein||0;tr.dataset.baseC=it.carbs||0;tr.dataset.baseF=it.fat||0;
  tr.innerHTML=
    '<td data-label="Item"><input type="text" class="iName" maxlength="120" value="'+esc(it.name)+'" aria-label="Item name">'+(it.uncertain?' <span class="unc" title="Hard to eyeball, so this one is extra rough">~</span>':'')+'</td>'+
    '<td data-label="Portion"><input type="text" class="iPortion" maxlength="80" value="'+esc(it.portion)+'" aria-label="Portion"></td>'+
    '<td class="num" data-label="Calories"><input type="text" class="iCal" inputmode="numeric" value="'+(it.calories||'')+'" aria-label="Calories"></td>'+
    '<td class="num" data-label="Protein g"><input type="text" class="iP" inputmode="numeric" value="'+(it.protein||'')+'" aria-label="Protein grams"></td>'+
    '<td class="num" data-label="Carbs g"><input type="text" class="iC" inputmode="numeric" value="'+(it.carbs||'')+'" aria-label="Carbs grams"></td>'+
    '<td class="num" data-label="Fat g"><input type="text" class="iF" inputmode="numeric" value="'+(it.fat||'')+'" aria-label="Fat grams"></td>'+
    '<td class="scale" data-label="Size"><select class="iScale" aria-label="Portion size"><option value="0.5">Half</option><option value="0.75">Bit less</option><option value="1" selected>As seen</option><option value="1.5">Bit more</option><option value="2">Double</option></select></td>'+
    '<td class="del"><button type="button" class="row-del" aria-label="Remove item">×</button></td>';
  tr.querySelector('.row-del').addEventListener('click',()=>{const card=tr.closest('.meal-card');tr.remove();if(card)refreshTotals(card)});
  tr.querySelector('.iScale').addEventListener('change',()=>{
    const s=parseFloat(tr.querySelector('.iScale').value)||1;
    const set=(sel,base)=>{const el=tr.querySelector(sel);el.value=base>0?Math.round(base*s):''};
    set('.iCal',+tr.dataset.baseCal);set('.iP',+tr.dataset.baseP);set('.iC',+tr.dataset.baseC);set('.iF',+tr.dataset.baseF);
    tr.dataset.curLow=Math.round((+tr.dataset.baseLow)*s);tr.dataset.curHigh=Math.round((+tr.dataset.baseHigh)*s);
    refreshTotals(tr.closest('.meal-card'));
  });
  // A hand-typed number becomes the new truth at the current size.
  ['.iCal','.iP','.iC','.iF'].forEach(sel=>{
    tr.querySelector(sel).addEventListener('change',()=>{
      const s=parseFloat(tr.querySelector('.iScale').value)||1;
      const map={'.iCal':'baseCal','.iP':'baseP','.iC':'baseC','.iF':'baseF'};
      const v=parseFloat(tr.querySelector(sel).value)||0;
      tr.dataset[map[sel]]=s>0?v/s:v;
      if(sel==='.iCal'){tr.dataset.curLow=Math.round(v*0.85);tr.dataset.curHigh=Math.round(v*1.2);tr.dataset.baseLow=tr.dataset.curLow/s;tr.dataset.baseHigh=tr.dataset.curHigh/s}
      refreshTotals(tr.closest('.meal-card'));
    });
  });
  tr.dataset.curLow=it.caloriesLow||0;tr.dataset.curHigh=it.caloriesHigh||0;
  return tr;
}

function numVal(el){const v=parseFloat(el.value);return isNaN(v)?0:v}
function collectTotals(card){
  let cal=0,low=0,high=0,p=0,c=0,f=0;
  card.querySelectorAll('.itemsBody tr').forEach(tr=>{
    cal+=numVal(tr.querySelector('.iCal'));
    low+=+tr.dataset.curLow||0;high+=+tr.dataset.curHigh||0;
    p+=numVal(tr.querySelector('.iP'));c+=numVal(tr.querySelector('.iC'));f+=numVal(tr.querySelector('.iF'));
  });
  return{calories:Math.round(cal),low:Math.round(low),high:Math.round(high),protein:Math.round(p),carbs:Math.round(c),fat:Math.round(f)};
}
function refreshTotals(card){
  if(!card)return;
  const t=collectTotals(card);
  card.querySelector('.totals-bar').innerHTML='About <b>~'+t.calories+' cal</b> (roughly '+t.low+' to '+t.high+') &middot; Protein '+t.protein+' g &middot; Carbs '+t.carbs+' g &middot; Fat '+t.fat+' g';
}

/* ---------- Day total, kept in this browser only ---------- */
const DAY_KEY='bb_meal_day_v1';
function todayStr(){const d=new Date();const pad=n=>String(n).padStart(2,'0');return d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate())}
function loadDay(){
  try{
    const raw=localStorage.getItem(DAY_KEY);
    if(!raw)return null;
    const d=JSON.parse(raw);
    return d&&d.date===todayStr()?d:null;
  }catch(e){return null}
}
function saveDay(d){try{localStorage.setItem(DAY_KEY,JSON.stringify(d))}catch(e){}}
function renderDay(){
  const panel=document.querySelector('#dayPanel');
  const d=loadDay();
  if(!d||!d.meals){panel.classList.add('hidden');return}
  panel.classList.remove('hidden');
  document.querySelector('#dayTotals').innerHTML='<b>~'+d.calories+' cal</b> (roughly '+d.low+' to '+d.high+') across '+d.meals+(d.meals===1?' meal':' meals')+' &middot; Protein '+d.protein+' g &middot; Carbs '+d.carbs+' g &middot; Fat '+d.fat+' g';
}
function addToDay(t){
  const d=loadDay()||{date:todayStr(),calories:0,low:0,high:0,protein:0,carbs:0,fat:0,meals:0};
  d.calories+=t.calories;d.low+=t.low;d.high+=t.high;d.protein+=t.protein;d.carbs+=t.carbs;d.fat+=t.fat;d.meals+=1;
  saveDay(d);renderDay();
  document.querySelector('#dayPanel').scrollIntoView({behavior:'smooth',block:'center'});
}
document.querySelector('#dayReset').addEventListener('click',()=>{
  saveDay({date:todayStr(),calories:0,low:0,high:0,protein:0,carbs:0,fat:0,meals:0});
  renderDay();
});
renderDay();

document.querySelector('#newBatchBtn').addEventListener('click',()=>{
  queue=[];cardCount=0;cardsWrap.innerHTML='';
  queueList.innerHTML='';batchError.textContent='';clearScene();
  resultsPanel.classList.add('hidden');
  renderQueue();
  document.querySelector('#uploadPanel').scrollIntoView({behavior:'smooth'});
});
</script></main><script>window.__bbCaptureHandoff=function(f){try{loadFiles([f])}catch(e){}};</script><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261026r14"></script></body></html>
