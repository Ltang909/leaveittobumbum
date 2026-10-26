<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Poster to Calendar | Turn Event Posters Into Calendar Events | Leave It to Bum Bum</title>
<meta name="description" content="Snap a photo of any event poster, flyer, or screenshot. Bum Bum reads the date, time, and place and builds a ready-to-save calendar event. Free to try.">
<meta property="og:title" content="Poster to Calendar | Turn Event Posters Into Calendar Events | Leave It to Bum Bum">
<meta property="og:description" content="Snap a photo of any event poster, flyer, or screenshot. Bum Bum reads the date, time, and place and builds a ready-to-save calendar event. Free to try.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/poster-to-calendar/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og-receipt-reader.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Poster to Calendar | Turn Event Posters Into Calendar Events | Leave It to Bum Bum">
<meta name="twitter:description" content="Snap a photo of any event poster, flyer, or screenshot. Bum Bum reads the date, time, and place and builds a ready-to-save calendar event. Free to try.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og-receipt-reader.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/poster-to-calendar/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Poster to Calendar",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Snap a photo of any event poster, flyer, or screenshot. Bum Bum reads the date, time, and place and builds a ready-to-save calendar event. Free to try.",
  "url": "https://leaveittobumbum.com/tools/poster-to-calendar/"
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
        "text": "No. Read posters as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "Where does my photo go?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Your photo is sent to Bum Bum's reader to pull out the event details, then discarded. It is never stored and never shared."
      }
    },
    {
      "@type": "Question",
      "name": "What if the poster lists more than one event?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Bum Bum builds an event for each one it finds, up to six. Download them one at a time or all together in a single calendar file."
      }
    },
    {
      "@type": "Question",
      "name": "Which calendars does it work with?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "All of them. Download the .ics file for Apple Calendar, Outlook, and most others, or use the Google Calendar button to add an event in one tap."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Poster to Calendar | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Poster to Calendar","item":"https://leaveittobumbum.com/tools/poster-to-calendar/"}]}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"HowTo","name":"How to use Poster to Calendar","description":"Snap a photo of an event poster and get a ready-to-save calendar event.","step":[{"@type":"HowToStep","position":1,"name":"Snap or upload a photo of the poster. Screenshots work too."},{"@type":"HowToStep","position":2,"name":"Bum Bum reads it and fills in the event title, date, time, and place."},{"@type":"HowToStep","position":3,"name":"Check the details and fix anything it misread."},{"@type":"HowToStep","position":4,"name":"Save it to your calendar with the download or the Google Calendar button."}]}</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
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
.event-card{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:14px;background:#fff;padding:16px;margin-bottom:14px}
.event-card .card-head{font-size:16px;margin-bottom:4px}
.fields-grid{display:grid;gap:10px;margin-top:12px}
@media(min-width:760px){.fields-grid{grid-template-columns:1fr 1fr}.fields-grid .span2{grid-column:span 2}}
.fields-grid label{font-weight:700;font-size:14px;display:block}
.fields-grid input,.fields-grid textarea{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px}
.fields-grid input:focus,.fields-grid textarea:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.fields-grid .check{font-weight:700;font-size:14px;display:flex;gap:8px;align-items:center;margin-top:10px}
.fields-grid .check input{width:auto;margin:0}
.read-note{background:#fdf8ef;border:1px solid #e7dcc3;border-radius:10px;padding:10px 14px;font-size:14px;margin:10px 0}
.guess-note{background:#fdf3e3;border:1px solid #e8cf9f;border-radius:10px;padding:10px 14px;font-size:14px;margin:10px 0}
.panel h2{margin-top:0}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
.queue-item{display:flex;gap:12px;align-items:center;border:1px solid #e2d7bf;border-radius:12px;background:#fff;padding:8px 12px;margin-top:8px}
.queue-item img{width:52px;height:52px;object-fit:cover;border-radius:8px;border:1px solid #e7dcc3}
.queue-item .meta{font-size:13px;opacity:.75}
.mini{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:6px 12px;font-weight:700;font-size:13px;cursor:pointer}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Poster to Calendar"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/icons/poster-to-calendar.png" alt="Poster to Calendar"><h1>Posters in, calendar events out.</h1><p class="lede bb-desc">See an event you want to go to? Snap the poster. Bum Bum reads the photo, flyer, or screenshot, pulls out the title, date, time, and place, and builds the calendar event for you. Check the details, fix anything it misread, then save it to your calendar with one tap. If the poster lists a whole lineup, you get an event for each one. One action per poster read. Your photo is read to pull the details, then discarded; it is never stored.</p>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to read posters</h2><a class="button" href="/account/?next=<?= urlencode('/tools/poster-to-calendar/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/poster-to-calendar/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/poster-to-calendar/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage && (int) $usage['used'] >= 2): ?><div class="nudge bb-guest-nudge" data-remaining="<?= (int) $usage['remaining'] ?>"><button type="button" class="bb-guest-nudge-dismiss" aria-label="Dismiss" style="float:right;border:none;background:none;cursor:pointer;font:inherit;font-weight:900;opacity:.55;padding:0 0 0 12px">&times;</button>You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions left. <a href="/account/?next=<?= urlencode('/tools/poster-to-calendar/') ?>">Create a free account</a> to save your work and keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>

<section class="panel" id="uploadPanel">
<h2>Snap or upload a poster</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Choose a poster photo">
<p style="font-size:40px;margin:0">🗓️</p>
<p><b>Drop a poster photo here</b>, paste a screenshot, or pick one below.</p>
<p class="meta" style="font-size:13px;opacity:.75">Tip: straight on, whole poster in the frame, no glare. Café wall posters, flyers, and screenshots all work. Up to 5 at a time.</p>
</div>
<input type="file" id="fileGallery" accept="image/*" multiple class="hidden">
<input type="file" id="fileCamera" accept="image/*" capture="environment" class="hidden">
<div class="btnrow">
<button type="button" class="button secondary" id="btnGallery">Upload images</button>
<button type="button" class="button secondary" id="btnCamera">Use camera</button>
</div>
<div id="queueList"></div>
<div class="btnrow"><button type="button" class="button hidden" id="processBtn">Read poster</button></div>
<div class="progress-wrap hidden" id="batchProgress"><div class="progress-bar"><div id="barFill"></div></div><p id="progText">Warming up...</p></div>
<div id="sceneSlot" style="margin-top:14px"></div>
<p id="batchError" class="error"></p>
</section>

<section class="panel hidden" id="resultsPanel">
<h2>Here is what Bum Bum found</h2>
<div id="saveAllWrap" class="btnrow hidden" style="margin-top:0;margin-bottom:14px">
<button type="button" class="button" id="dlAllBtn">Download all events (.ics)</button>
<button type="button" class="button secondary" id="newBatchBtn">New batch</button>
</div>
<div id="cardsWrap"></div>
</section>
<?php endif; ?>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Do I need an account?</summary>
<p>No. Read posters as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>Where does my photo go?</summary>
<p>It is sent to Bum Bum's reader to pull out the event details, then discarded. It is never stored and never shared.</p>
</details>
<details>
<summary>What if the poster lists more than one event?</summary>
<p>Bum Bum builds an event for each one it finds, up to six. A weekend market poster with three events on it becomes three calendar events. Download them one at a time, or all together in a single file.</p>
</details>
<details>
<summary>What if it misreads the date?</summary>
<p>Every field is editable before you save. When the poster is vague ("this Friday!"), Bum Bum flags that it had to work the date out, so you know to double-check that one.</p>
</details>
<details>
<summary>Which calendars does it work with?</summary>
<p>All of them. The .ics download works with Apple Calendar, Outlook, and most others. The Google Calendar button adds the event in one tap.</p>
</details>
</section>
<section class="panel" aria-label="More tiny tools">
<h2 style="margin-top:0">More tiny tools</h2>
<p><a href="/tools/receipt-reader/">Receipt Scanner</a> - Snap receipts, get clean tables.</p>
<p><a href="/tools/notes/">Voice Notes to Text</a> - Talk it out and get a live transcript you can copy or download.</p>
<p><a href="/tools/sop-ify/">SOP Generator</a> - Turn a chaotic process brain-dump into a clean step-by-step playbook.</p>
</section>
</main>
<script src="/tools/error-scenes.js"></script>
<script>
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/poster-to-calendar.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}bbTrack('tool_opened',{tool:'poster-to-calendar'});

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
const saveAllWrap=document.querySelector('#saveAllWrap');

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
  bbTrack('poster_error',{scene:state});
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
      if(dataUrl)queue.push({id:crypto.randomUUID(),dataUrl,name:f.name||'poster',status:'queued'});
    }
    if(queue.length>=MAX_BATCH)batchError.textContent='Bum Bum reads up to '+MAX_BATCH+' posters at a time. The first '+MAX_BATCH+' are queued.';
    renderQueue();
  })();
}

function renderQueue(){
  queueList.innerHTML='';
  queue.forEach(q=>{
    const div=document.createElement('div');div.className='queue-item';
    const label=q.status==='done'?'Read':q.status==='reading'?'Reading...':q.status==='error'?'Could not read it':q.status==='blocked'?'Waiting on actions':'Queued';
    div.innerHTML='<img src="'+q.dataUrl+'" alt=""><div><b>'+esc(q.name)+'</b><div class="meta">'+label+'</div></div>';
    queueList.appendChild(div);
  });
  const pending=queue.filter(q=>q.status==='queued');
  processBtn.classList.toggle('hidden',!pending.length||batchRunning);
  processBtn.textContent=pending.length>1?'Read '+pending.length+' posters ('+pending.length+' actions)':'Read this poster (1 action)';
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

function localToday(){
  const d=new Date();
  const pad=n=>String(n).padStart(2,'0');
  return{date:d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate()),
    weekday:d.toLocaleDateString('en-US',{weekday:'long'}),
    tz:(Intl.DateTimeFormat().resolvedOptions().timeZone||'')};
}

processBtn.addEventListener('click',async()=>{
  if(batchRunning)return;
  batchRunning=true;batchError.textContent='';clearScene();
  renderQueue();
  batchProgress.classList.remove('hidden');
  barFill.style.width='5%';
  const todo=queue.filter(q=>q.status==='queued');
  let done=0,blocked=false,lastFail=null;
  for(const q of todo){
    q.status='reading';renderQueue();
    progText.textContent='Reading poster '+(done+1)+' of '+todo.length+'...';
    try{
      const small=await downscale(q.dataUrl);
      const t=localToday();
      const{response,data}=await apiCall({action:'parse',image:small,idempotencyKey:q.id,todayLocal:t.date,weekdayLocal:t.weekday,tzName:t.tz});
      if(response.status===402)throw new Error('actions');
      if(!response.ok){const err=new Error(data.error||'read failed');err.serverMsg=data.error||'';throw err}
      (data.events||[]).forEach(ev=>addCard(ev,q.name));
      q.status='done';
      bbTrack('poster_parsed',{events:(data.events||[]).length});
    }catch(e){
      if(e.message==='actions'){q.status='blocked';blocked=true}
      else{q.status='error';lastFail=e}
    }
    done++;
    barFill.style.width=(5+done/todo.length*90)+'%';
    renderQueue();
    if(blocked)break;
  }
  if(queue.some(q=>q.status==='done')&&window.BBDropStrip)window.BBDropStrip.show('poster-to-calendar');
  if(blocked){
    queue.forEach(q=>{if(q.status==='queued')q.status='blocked'});
    batchError.textContent='Out of actions for this month. The posters above were read; the rest are waiting.';
    renderQueue();
  }else if(lastFail&&done===todo.length&&queue.some(q=>q.status==='error')){
    showScene('processing_failed',{onCta:()=>{queue.forEach(q=>{if(q.status==='error')q.status='queued'});clearScene();renderQueue();processBtn.click()}});
    if(lastFail.serverMsg)batchError.textContent=lastFail.serverMsg;
  }
  barFill.style.width='100%';
  progText.textContent='Done.';
  batchRunning=false;
  renderQueue();
  setTimeout(()=>batchProgress.classList.add('hidden'),800);
});

/* ---------- Event cards ---------- */
function addCard(ev,fileName){
  cardCount++;
  resultsPanel.classList.remove('hidden');
  const todayStr=localToday().date;
  const location=[ev.location,ev.address].filter(Boolean).join(', ');
  const desc=[ev.description,ev.url?('Info and tickets: '+ev.url):''].filter(Boolean).join('\n');
  const endGuess=!ev.allDay&&ev.startTime&&!ev.endTime;
  const card=document.createElement('div');
  card.className='event-card';
  card.innerHTML=
    '<div class="card-head"><b>Event '+cardCount+'</b></div>'+
    '<div class="read-note">Bum Bum\'s best read of the poster, not gospel. Fix anything it misread, then save it to your calendar.</div>'+
    (ev.dateGuess?'<div class="guess-note">The poster was vague about the date, so Bum Bum worked this one out. Double-check it before you save.</div>':'')+
    (!ev.dateGuess&&ev.date&&ev.date<todayStr?'<div class="guess-note">This date is in the past. The poster may be for next year; check the year before you save.</div>':'')+
    (ev.note?'<div class="read-note">'+esc(ev.note)+'</div>':'')+
    (endGuess?'<div class="read-note">The poster gave no end time, so Bum Bum guessed 2 hours. Fix it if the night runs longer.</div>':'')+
    '<div class="fields-grid">'+
    '<label class="span2">Event title<input class="fTitle" type="text" maxlength="160" value="'+esc(ev.title)+'"></label>'+
    '<label>Date<input class="fDate" type="date" value="'+esc(ev.date)+'"></label>'+
    '<label>Location<input class="fLocation" type="text" maxlength="240" value="'+esc(location)+'" placeholder="Venue, address"></label>'+
    '<label>Starts<input class="fStart" type="time" value="'+esc(ev.startTime||'')+'"></label>'+
    '<label>Ends<input class="fEnd" type="time" value="'+esc(ev.endTime||'')+'"></label>'+
    '<label class="span2">Notes<textarea class="fDesc" rows="3" maxlength="1000">'+esc(desc)+'</textarea></label>'+
    '</div>'+
    '<label class="check" style="font-weight:700;font-size:14px;display:flex;gap:8px;align-items:center;margin-top:10px"><input class="fAllDay" type="checkbox" '+(ev.allDay?'checked':'')+'> All-day event (no set time)</label>'+
    '<div class="btnrow"><button type="button" class="button dlOne">Download .ics</button>'+
    '<a class="button secondary gcalOne" target="_blank" rel="noopener" style="text-decoration:none">Add to Google Calendar</a>'+
    '<button type="button" class="mini removeCard">Remove</button></div>'+
    '<p class="card-note meta" style="font-size:13px"></p>';
  card.querySelector('.removeCard').addEventListener('click',()=>{card.remove();updateDlAll()});
  card.querySelector('.dlOne').addEventListener('click',()=>{
    const e=collectCard(card);
    if(!e)return;
    downloadIcs([e],icsName(e.title));
    card.querySelector('.card-note').textContent='Saved. Open the file and your calendar does the rest.';
    bbTrack('ics_downloaded',{events:1});
  });
  const g=card.querySelector('.gcalOne');
  const refreshGcal=()=>{const e=collectCard(card,true);g.href=e?gcalUrl(e):'#';};
  card.addEventListener('input',refreshGcal);
  card.addEventListener('change',refreshGcal);
  refreshGcal();
  cardsWrap.appendChild(card);
  updateDlAll();
  if(cardsWrap.children.length===1)resultsPanel.scrollIntoView({behavior:'smooth',block:'start'});
}

function collectCard(card,silent){
  const v=sel=>card.querySelector(sel).value.trim();
  const title=v('.fTitle'),date=v('.fDate');
  const allDay=card.querySelector('.fAllDay').checked;
  let start=v('.fStart'),end=v('.fEnd');
  if(!title||!/^\d{4}-\d{2}-\d{2}$/.test(date)){
    if(!silent)card.querySelector('.card-note').textContent='Give the event a title and a date first.';
    return null;
  }
  if(!allDay){
    if(!start)start='12:00';
    if(!end){
      const[h,m]=start.split(':').map(Number);
      const d=new Date(2000,0,1,h,m);d.setHours(d.getHours()+2);
      end=String(d.getHours()).padStart(2,'0')+':'+String(d.getMinutes()).padStart(2,'0');
    }
  }
  return{title,date,startTime:allDay?'':start,endTime:allDay?'':end,allDay,
    location:v('.fLocation'),description:v('.fDesc')};
}

function updateDlAll(){
  const n=cardsWrap.children.length;
  saveAllWrap.classList.toggle('hidden',n===0);
  document.querySelector('#dlAllBtn').textContent=n>1?'Download all '+n+' events (.ics)':'Download event (.ics)';
}
document.querySelector('#dlAllBtn').addEventListener('click',()=>{
  const events=[];
  for(const card of[...cardsWrap.children]){
    const e=collectCard(card,true);
    if(e)events.push(e);
  }
  if(!events.length)return;
  downloadIcs(events,'poster-events.ics');
  bbTrack('ics_downloaded',{events:events.length});
});
document.querySelector('#newBatchBtn').addEventListener('click',()=>{
  queue=[];cardCount=0;cardsWrap.innerHTML='';
  queueList.innerHTML='';batchError.textContent='';clearScene();
  resultsPanel.classList.add('hidden');
  renderQueue();updateDlAll();
  document.querySelector('#uploadPanel').scrollIntoView({behavior:'smooth'});
});

/* ---------- Calendar export ---------- */
function icsEscape(s){return String(s??'').replace(/\\/g,'\\\\').replace(/;/g,'\\;').replace(/,/g,'\\,').replace(/\r?\n/g,'\\n')}
function icsFold(line){
  if(line.length<=75)return line;
  let out=line.slice(0,75),rest=line.slice(75);
  while(rest.length>0){out+='\r\n '+rest.slice(0,74);rest=rest.slice(74)}
  return out;
}
function icsStamp(){
  const d=new Date();
  const pad=n=>String(n).padStart(2,'0');
  return d.getUTCFullYear()+pad(d.getUTCMonth()+1)+pad(d.getUTCDate())+'T'+pad(d.getUTCHours())+pad(d.getUTCMinutes())+pad(d.getUTCSeconds())+'Z';
}
function icsDate(dateStr,plusDays){
  const[y,m,dd]=dateStr.split('-').map(Number);
  const d=new Date(Date.UTC(y,m-1,dd+(plusDays||0)));
  const pad=n=>String(n).padStart(2,'0');
  return d.getUTCFullYear()+pad(d.getUTCMonth()+1)+pad(d.getUTCDate());
}
function buildIcs(events){
  const lines=['BEGIN:VCALENDAR','VERSION:2.0','PRODID:-//Leave It to Bum Bum//Poster to Calendar//EN','CALSCALE:GREGORIAN'];
  events.forEach(e=>{
    lines.push('BEGIN:VEVENT');
    lines.push('UID:'+(crypto.randomUUID?crypto.randomUUID():('evt-'+Date.now()+'-'+Math.random().toString(36).slice(2)))+'@leaveittobumbum.com');
    lines.push('DTSTAMP:'+icsStamp());
    if(e.allDay){
      lines.push('DTSTART;VALUE=DATE:'+icsDate(e.date,0));
      lines.push('DTEND;VALUE=DATE:'+icsDate(e.date,1));
    }else{
      const rollsOver=e.endTime<=e.startTime;
      lines.push('DTSTART:'+icsDate(e.date,0)+'T'+e.startTime.replace(':','')+'00');
      lines.push('DTEND:'+icsDate(e.date,rollsOver?1:0)+'T'+e.endTime.replace(':','')+'00');
    }
    lines.push('SUMMARY:'+icsEscape(e.title));
    if(e.location)lines.push('LOCATION:'+icsEscape(e.location));
    if(e.description)lines.push('DESCRIPTION:'+icsEscape(e.description));
    lines.push('END:VEVENT');
  });
  lines.push('END:VCALENDAR');
  return lines.map(icsFold).join('\r\n')+'\r\n';
}
function icsName(title){
  const slug=String(title||'event').toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-+|-+$/g,'').slice(0,40);
  return (slug||'event')+'.ics';
}
function downloadIcs(events,filename){
  const blob=new Blob([buildIcs(events)],{type:'text/calendar'});
  const a=document.createElement('a');
  a.href=URL.createObjectURL(blob);
  a.download=filename;
  document.body.appendChild(a);a.click();a.remove();
  setTimeout(()=>URL.revokeObjectURL(a.href),4000);
}
function gcalUrl(e){
  const base='https://calendar.google.com/calendar/render?action=TEMPLATE';
  let dates;
  if(e.allDay)dates=icsDate(e.date,0)+'/'+icsDate(e.date,1);
  else dates=icsDate(e.date,0)+'T'+e.startTime.replace(':','')+'00/'+icsDate(e.date,0)+'T'+e.endTime.replace(':','')+'00';
  const tz=Intl.DateTimeFormat().resolvedOptions().timeZone||'';
  return base+'&text='+encodeURIComponent(e.title)+'&dates='+dates+
    (e.description?'&details='+encodeURIComponent(e.description):'')+
    (e.location?'&location='+encodeURIComponent(e.location):'')+
    (tz?'&ctz='+encodeURIComponent(tz):'');
}
</script></main><script>window.__bbCaptureHandoff=function(f){try{loadFiles([f])}catch(e){}};</script><script src="/tools/capture-handoff.js?v=20261026r5"></script><script src="/tools/drop-strip.js?v=20261026r10"></script></body></html>
