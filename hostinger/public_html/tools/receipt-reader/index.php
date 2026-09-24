<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $user = currentUser(); $usage = $user ? usageFor(billingUser($user)) : null; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Receipt Reader | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
#preview{max-width:100%;max-height:320px;border-radius:12px;border:1px solid #e2d7bf;margin-top:12px;display:block}
.progress-wrap{margin-top:14px}
.progress-bar{height:10px;border-radius:999px;background:#f0e8d4;overflow:hidden}
#barFill{height:100%;width:0%;border-radius:999px;background:#2f2a22;transition:width .2s}
#progText{font-size:13px;font-weight:700;margin:8px 0 0;opacity:.75}
.error{color:#b3261e;font-weight:700}
.hidden{display:none!important}
.items-table{width:100%;border-collapse:collapse;margin-top:8px}
.items-table th{text-align:left;font-size:12px;font-weight:800;opacity:.7;padding:6px 8px;border-bottom:1px solid #e7dcc3}
.items-table td{padding:4px;border-bottom:1px solid #f2ecdc;vertical-align:middle}
.items-table input{width:100%;padding:8px 10px;border:1px solid #ddd1b8;border-radius:8px;font:inherit;background:#fff}
.items-table input:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.items-table td.amt{width:110px}
.items-table td.del{width:44px;text-align:center}
.row-del{border:1px solid #ddd1b8;background:#fff;border-radius:999px;width:30px;height:30px;font-size:15px;cursor:pointer;line-height:1}
.totals-grid{display:grid;gap:10px;margin-top:14px}
@media(min-width:760px){.totals-grid{grid-template-columns:1fr 1fr 1fr}}
.totals-grid label{font-weight:700;font-size:14px;display:block}
.totals-grid input{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px}
.totals-grid input:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.top-fields{display:grid;gap:10px;margin:12px 0}
@media(min-width:760px){.top-fields{grid-template-columns:1fr 1fr}}
.top-fields label{font-weight:700;font-size:14px;display:block}
.top-fields input{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px}
.top-fields input:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.log-row{display:flex;gap:10px;align-items:center;flex-wrap:wrap;border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:12px;background:#fff;padding:10px 12px;margin-bottom:8px}
.log-row b{font-size:15px}
.log-row .meta{font-size:13px;opacity:.75}
.log-row .spacer{flex:1}
.mini{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:6px 12px;font-weight:700;font-size:13px;cursor:pointer}
details.raw{margin-top:14px}
details.raw summary{cursor:pointer;font-weight:700;font-size:14px}
details.raw pre{background:#fdf8ef;border:1px solid #e7dcc3;border-radius:10px;padding:12px;font-size:12px;white-space:pre-wrap;max-height:240px;overflow-y:auto}
.panel h2{margin-top:0}
.read-note{background:#fdf8ef;border:1px solid #e7dcc3;border-radius:10px;padding:10px 14px;font-size:14px;margin:10px 0}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
.queue-item{display:flex;gap:12px;align-items:center;border:1px solid #e2d7bf;border-radius:12px;background:#fff;padding:8px 12px;margin-top:8px}
.queue-item img{width:52px;height:52px;object-fit:cover;border-radius:8px;border:1px solid #e7dcc3}
.queue-item .meta{font-size:13px;opacity:.75}
.receipt-card{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:14px;background:#fff;padding:16px;margin-bottom:14px}
.receipt-card .card-head{font-size:16px;margin-bottom:4px}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Receipt Reader"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-glasses.png" alt="Bum Bum with reading glasses, squinting at a receipt"><h1>Receipts in, spreadsheets out.</h1><p class="lede">Snap photos of your receipts. Bum Bum reads them <b>right in your browser</b>, several at a time,, pulls out the vendor, date, line items, tax, and total, and hands you a clean table you can fix up and export. Your photos never leave your device; only the typed-up numbers are saved, in your account. One action per receipt scanned. Saving and exports are free.</p>
<?php if (!$user): ?><section class="panel"><h2>Sign in to read receipts</h2><a class="button" href="/account/?next=<?= urlencode('/tools/receipt-reader/') ?>">Sign in or create an account</a></section><?php else: ?>
<?php $low = $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>

<section class="panel" id="uploadPanel">
<h2>Snap or upload receipts</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Choose receipt photos">
<p style="font-size:40px;margin:0">📸</p>
<p><b>Drop receipt photos here</b>, or pick them below. Several at once works.</p>
<p class="meta" style="font-size:13px;opacity:.75">Tip: flat surface, good light, each receipt filling the frame. Up to 10 at a time.</p>
</div>
<input type="file" id="fileGallery" accept="image/*" multiple class="hidden">
<input type="file" id="fileCamera" accept="image/*" capture="environment" class="hidden">
<div class="btnrow">
<button type="button" class="button secondary" id="btnGallery">Upload images</button>
<button type="button" class="button secondary" id="btnCamera">Use camera</button>
</div>
<div id="queueList"></div>
<div class="btnrow"><button type="button" class="button hidden" id="processBtn">Read receipts</button></div>
<div class="progress-wrap hidden" id="batchProgress"><div class="progress-bar"><div id="barFill"></div></div><p id="progText">Warming up...</p></div>
<p id="batchError" class="error"></p>
</section>

<section class="panel hidden" id="resultsPanel">
<h2>Here is what Bum Bum read</h2>
<div id="saveAllWrap" class="btnrow hidden" style="margin-top:0;margin-bottom:14px">
<button type="button" class="button" id="saveAllBtn">Save to log</button>
<button type="button" class="button secondary" id="newBatchBtn">New batch</button>
</div>
<div id="cardsWrap"></div>
</section>

<section class="panel">
<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap"><h2 style="margin:0">Receipt log</h2><button type="button" class="mini hidden" id="dlAllCsv">Download CSV</button></div>
<div id="logList" style="margin-top:10px"><p class="meta" style="font-size:13px;opacity:.75">Nothing saved yet. After a scan, hit &ldquo;Save to log&rdquo; and it will live here, in your account.</p></div>
</section>
<?php endif; ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
<script>
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/receipt-reader.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}bbTrack('tool_opened',{tool:'receipt-reader'});

const dropzone=document.querySelector('#dropzone');
const fileGallery=document.querySelector('#fileGallery');
const fileCamera=document.querySelector('#fileCamera');
const queueList=document.querySelector('#queueList');
const processBtn=document.querySelector('#processBtn');
const batchError=document.querySelector('#batchError');
const batchProgress=document.querySelector('#batchProgress');
const barFill=document.querySelector('#barFill');
const progText=document.querySelector('#progText');
const resultsPanel=document.querySelector('#resultsPanel');
const cardsWrap=document.querySelector('#cardsWrap');
const saveAllWrap=document.querySelector('#saveAllWrap');
const saveAllBtn=document.querySelector('#saveAllBtn');

const MAX_BATCH=10;
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
fileGallery.addEventListener('change',()=>{loadFiles(fileGallery.files);fileGallery.value=''});
fileCamera.addEventListener('change',()=>{loadFiles(fileCamera.files);fileCamera.value=''});

function loadFiles(fileList){
  batchError.textContent='';
  const imgs=[...fileList].filter(f=>f.type.startsWith('image/'));
  if(!imgs.length){batchError.textContent='No images in that selection. Try JPG or PNG photos.';return}
  const readOne=f=>new Promise(res=>{const r=new FileReader();r.onload=()=>res(r.result);r.onerror=()=>res('');r.readAsDataURL(f)});
  (async()=>{
    for(const f of imgs){
      if(queue.length>=MAX_BATCH)break;
      const dataUrl=await readOne(f);
      if(dataUrl)queue.push({id:crypto.randomUUID(),dataUrl,name:f.name||'receipt',status:'queued'});
    }
    if(queue.length>=MAX_BATCH)batchError.textContent='Bum Bum reads up to '+MAX_BATCH+' receipts at a time. The first '+MAX_BATCH+' are queued.';
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
  processBtn.textContent=pending.length>1?'Read '+pending.length+' receipts ('+pending.length+' actions)':'Read this receipt (1 action)';
}

processBtn.addEventListener('click',async()=>{
  if(batchRunning)return;
  batchRunning=true;batchError.textContent='';
  renderQueue();
  batchProgress.classList.remove('hidden');
  barFill.style.width='5%';
  let worker=null;
  try{
    progText.textContent='Loading the reader (first scan downloads it, about 15 MB)...';
    worker=await Tesseract.createWorker('eng',1,{logger:()=>{}});
    const todo=queue.filter(q=>q.status==='queued');
    let done=0,blocked=false;
    for(const q of todo){
      q.status='reading';renderQueue();
      progText.textContent='Reading receipt '+(done+1)+' of '+todo.length+'...';
      try{
        const canvas=await preprocess(q.dataUrl);
        const{data:{text}}=await worker.recognize(canvas);
        if(!text||text.replace(/\s/g,'').length<10)throw new Error('empty');
        const{response,data}=await apiCall({action:'scan',idempotencyKey:q.id});
        if(response.status===402)throw new Error('actions');
        if(!response.ok)throw new Error(data.error||'metering failed');
        addCard(parseReceiptText(text),text,q.name);
        q.status='done';
        bbTrack('scan_done',{batch:true});
      }catch(e){
        q.status=e.message==='actions'?'blocked':e.message==='empty'?'error':'error';
        if(e.message==='actions')blocked=true;
      }
      done++;
      barFill.style.width=(5+done/todo.length*90)+'%';
      renderQueue();
      if(blocked)break;
    }
    if(blocked){
      queue.forEach(q=>{if(q.status==='queued')q.status='blocked'});
      batchError.textContent='Out of actions for this month. The receipts above were read; the rest are waiting.';
      renderQueue();
    }
    barFill.style.width='100%';
    progText.textContent='Done.';
  }catch(e){
    batchError.textContent='Something went wrong reading those photos. Try again?';
  }finally{
    if(worker){try{await worker.terminate()}catch(e){}}
    batchRunning=false;
    renderQueue();
    setTimeout(()=>batchProgress.classList.add('hidden'),800);
  }
});

function addCard(p,rawText,fileName){
  cardCount++;
  resultsPanel.classList.remove('hidden');
  const card=document.createElement('div');
  card.className='receipt-card';
  card.dataset.currency=p.currency;
  card.innerHTML=
    '<div class="card-head"><b>Receipt '+cardCount+(p.vendor?' &middot; '+esc(p.vendor):'')+'</b></div>'+
    '<div class="read-note">Bum Bum\'s best read, not gospel. Fix anything it misread, then save.</div>'+
    '<div class="top-fields"><label>Vendor<input class="fVendor" type="text" maxlength="120" value="'+esc(p.vendor)+'" placeholder="Store name"></label>'+
    '<label>Date<input class="fDate" type="date" value="'+esc(p.date)+'"></label></div>'+
    '<table class="items-table"><thead><tr><th>Item</th><th>Amount</th><th></th></tr></thead><tbody class="itemsBody"></tbody></table>'+
    '<div class="btnrow"><button type="button" class="mini addRow">+ Add line</button></div>'+
    '<div class="totals-grid"><label>Subtotal<input class="fSubtotal" type="text" inputmode="decimal" placeholder="0.00" value="'+esc(p.subtotal)+'"></label>'+
    '<label><span class="taxLabel">'+esc(p.taxLabel)+'</span><input class="fTax" type="text" inputmode="decimal" placeholder="0.00" value="'+esc(p.tax)+'"></label>'+
    '<label>Total<input class="fTotal" type="text" inputmode="decimal" placeholder="0.00" value="'+esc(p.total)+'"></label></div>'+
    '<div class="btnrow"><button type="button" class="button saveOne">Save to log</button><button type="button" class="mini removeCard">Remove</button></div>'+
    '<p class="card-note meta" style="font-size:13px"></p>'+
    '<details class="raw"><summary>Raw OCR text</summary><pre class="rawText"></pre></details>';
  const body=card.querySelector('.itemsBody');
  p.items.forEach(it=>body.appendChild(itemRow(it.name,it.amount)));
  card.querySelector('.rawText').textContent=rawText;
  card.querySelector('.addRow').addEventListener('click',()=>body.appendChild(itemRow('','')));
  card.querySelector('.removeCard').addEventListener('click',()=>{card.remove();updateSaveAll()});
  card.querySelector('.saveOne').addEventListener('click',()=>saveCard(card));
  cardsWrap.appendChild(card);
  updateSaveAll();
  if(cardsWrap.children.length===1)resultsPanel.scrollIntoView({behavior:'smooth',block:'start'});
}

function collectCard(card){
  const v=sel=>card.querySelector(sel).value;
  const items=[];
  card.querySelectorAll('.itemsBody tr').forEach(tr=>{
    const ins=tr.querySelectorAll('input');
    const name=ins[0].value.trim(),amount=ins[1].value.trim().replace(/[^0-9.]/g,'');
    if(name||amount)items.push({name:name||'(unnamed)',amount:amount||'0.00'});
  });
  return{vendor:v('.fVendor').trim(),date:v('.fDate'),currency:card.dataset.currency||'USD',items,
    subtotal:v('.fSubtotal').trim().replace(/[^0-9.]/g,''),tax:v('.fTax').trim().replace(/[^0-9.]/g,''),
    taxLabel:card.querySelector('.taxLabel').textContent,total:v('.fTotal').trim().replace(/[^0-9.]/g,'')};
}

async function saveCard(card){
  const note=card.querySelector('.card-note');
  const d=collectCard(card);
  if(!d.items.length&&!d.total){note.textContent='Nothing to save yet.';return false}
  const btn=card.querySelector('.saveOne');btn.disabled=true;
  const{response,data}=await apiCall({action:'save',vendor:d.vendor,date:d.date,currency:d.currency,items:d.items,subtotal:d.subtotal,tax:d.tax,taxLabel:d.taxLabel,total:d.total});
  btn.disabled=false;
  if(!response.ok){note.textContent=data.error||'Could not save. Try again?';return false}
  card.dataset.saved='1';
  btn.disabled=true;btn.textContent='Saved';
  note.textContent='Saved to your receipt log.';
  bbTrack('receipt_saved',{items:d.items.length});
  fetchLogs();
  updateSaveAll();
  return true;
}

function updateSaveAll(){
  const unsaved=[...cardsWrap.children].filter(c=>!c.dataset.saved);
  saveAllWrap.classList.toggle('hidden',!unsaved.length);
  saveAllBtn.textContent=unsaved.length>1?'Save all '+unsaved.length+' to log':'Save to log';
}
saveAllBtn.addEventListener('click',async()=>{
  saveAllBtn.disabled=true;
  for(const card of[...cardsWrap.children]){
    if(!card.dataset.saved)await saveCard(card);
  }
  saveAllBtn.disabled=false;
});
document.querySelector('#newBatchBtn').addEventListener('click',()=>{
  queue=[];cardCount=0;cardsWrap.innerHTML='';
  queueList.innerHTML='';batchError.textContent='';
  resultsPanel.classList.add('hidden');
  renderQueue();
  document.querySelector('#uploadPanel').scrollIntoView({behavior:'smooth'});
});

function preprocess(dataUrl){
  return new Promise((resolve,reject)=>{
    const img=new Image();
    img.onload=()=>{
      const maxDim=2200;
      let w=img.naturalWidth,h=img.naturalHeight;
      const s=Math.min(1,maxDim/Math.max(w,h))*(Math.max(w,h)<1200?1.6:1);
      w=Math.round(w*s);h=Math.round(h*s);
      const c=document.createElement('canvas');c.width=w;c.height=h;
      const ctx=c.getContext('2d',{willReadFrequently:true});
      ctx.drawImage(img,0,0,w,h);
      const d=ctx.getImageData(0,0,w,h),px=d.data;
      for(let i=0;i<px.length;i+=4){
        const g=0.299*px[i]+0.587*px[i+1]+0.114*px[i+2];
        const cc=g<128?Math.max(0,(g-128)*1.5+128):Math.min(255,(g-128)*1.2+128);
        px[i]=px[i+1]=px[i+2]=cc;
      }
      ctx.putImageData(d,0,0);
      resolve(c);
    };
    img.onerror=reject;
    img.src=dataUrl;
  });
}
/* ---------- Receipt parsing: Bum Bum's best read ---------- */
function parseReceiptText(text){
  const rawLines=text.split('\n').map(l=>l.replace(/\s+/g,' ').trim()).filter(l=>l.length>1);
  const priceEnd=/(\d{1,3}(?:[,\s]\d{3})*\.\d{2})\s*$/;
  const num=s=>parseFloat(String(s).replace(/[,\s]/g,''));
  const out={vendor:'',date:'',currency:'USD',items:[],subtotal:'',tax:'',taxLabel:'Tax',total:''};

  if(/£/.test(text))out.currency='GBP';
  else if(/€/.test(text))out.currency='EUR';
  else if(/\b(HST|GST|PST|TPS|TVQ|QST)\b/i.test(text))out.currency='CAD';

  for(const l of rawLines.slice(0,6)){
    if(/[a-zA-Z]{2,}/.test(l)&&!priceEnd.test(l)&&!/receipt|invoice|order|table|server|clerk|store\s*#|www\.|https?:/i.test(l)&&l.length<=60){
      out.vendor=l.replace(/[*#]+/g,'').trim();break;
    }
  }

  const months={jan:'01',feb:'02',mar:'03',apr:'04',may:'05',jun:'06',jul:'07',aug:'08',sep:'09',oct:'10',nov:'11',dec:'12'};
  const pad=n=>String(n).padStart(2,'0');
  for(const l of rawLines){
    let m=l.match(/(\d{4})[-\/.](\d{1,2})[-\/.](\d{1,2})/);
    if(m){out.date=m[1]+'-'+pad(m[2])+'-'+pad(m[3]);break}
    m=l.match(/(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)[a-z]*\.?\s+(\d{1,2})(?:st|nd|rd|th)?,?\s+(\d{4})/i);
    if(m){out.date=m[3]+'-'+months[m[1].slice(0,3).toLowerCase()]+'-'+pad(m[2]);break}
    m=l.match(/(\d{1,2})[\/.](\d{1,2})[\/.](\d{2,4})/);
    if(m){
      let a=+m[1],b=+m[2];const y=m[3].length===2?2000+ +m[3]:+m[3];
      let mo=a>12?b:a,dy=a>12?a:b;
      if(mo>=1&&mo<=12&&dy>=1&&dy<=31){out.date=y+'-'+pad(mo)+'-'+pad(dy);break}
    }
  }

  const amtNear=re=>{
    for(let i=rawLines.length-1;i>=0;i--){
      if(re.test(rawLines[i])){
        const mm=rawLines[i].match(/(\d[\d,]*\.\d{2})/);
        if(mm)return num(mm[1]).toFixed(2);
        for(let j=i+1;j<Math.min(i+3,rawLines.length);j++){
          const pm=rawLines[j].match(priceEnd);
          if(pm)return num(pm[1]).toFixed(2);
        }
      }
    }
    return'';
  };
  out.total=amtNear(/grand\s*total|amount\s*due|balance\s*due/i)||amtNear(/\btotal\b/i);
  out.subtotal=amtNear(/sub\s*-?\s*total/i);
  const taxSeen=[];
  for(const l of rawLines){
    const tm=l.match(/\b(HST|GST|PST|QST|TPS|TVQ|TAX|TVA)\b/i);
    if(tm&&!/total/i.test(l)){
      // The amount sits at the end of the line; a rate like 8.875% earlier in the line is not it.
      const end=l.match(/(\d[\d,]*\.\d{2})\s*$/);
      const all=[...l.matchAll(/(\d[\d,]*\.\d{2})/g)];
      const mm=end||all[all.length-1];
      if(mm)taxSeen.push({label:tm[1].toUpperCase(),amount:num(mm[1])});
    }
  }
  if(taxSeen.length){
    out.tax=taxSeen.reduce((s,t)=>s+t.amount,0).toFixed(2);
    const labels=[...new Set(taxSeen.map(t=>t.label))];
    out.taxLabel=labels.join(' + ');
  }

  const skipItem=/total|sub\s*total|balance|amount due|change|cash|tender|debit|credit|visa|mastercard|\bmc\b|amex|interac|tip|gratuity|discount|saving|coupon|\btax\b|hst|gst|pst|qst|tps|tvq|receipt|invoice|order|table|server|clerk|\btrans\b|auth|approval|\bref\b|store|www\.|https?:|phone|\btel\b|thank|you saved/i;
  for(const l of rawLines){
    const pm=l.match(priceEnd);
    if(!pm)continue;
    if(skipItem.test(l))continue;
    const amount=num(pm[1]).toFixed(2);
    let name=l.slice(0,l.length-pm[0].length).trim()
      .replace(/^(\d+)\s*[xX@]\s*/,'')
      .replace(/^\d+\s+(?=[A-Za-z])/,'') /* "2 Coke" -> "Coke"; "7-Eleven" keeps its 7 */
      .replace(/\s*@\s*\d[\d.,]*\s*$/,'')
      .replace(/[*#]+/g,'').trim();
    if(name.length<2||/^[0-9\s.,-]+$/.test(name))continue;
    if(name.length>60)name=name.slice(0,60);
    out.items.push({name,amount});
  }
  return out;
}

function itemRow(name,amount){
  const tr=document.createElement('tr');
  tr.innerHTML='<td><input type="text" maxlength="60" value="'+esc(name)+'" aria-label="Item name"></td><td class="amt"><input type="text" inputmode="decimal" value="'+esc(amount)+'" aria-label="Item amount"></td><td class="del"><button type="button" class="row-del" aria-label="Remove line">×</button></td>';
  tr.querySelector('.row-del').addEventListener('click',()=>tr.remove());
  return tr;
}
function csvCell(s){s=String(s??'');return /[",\n]/.test(s)?'"'+s.replace(/"/g,'""')+'"':s}
/* Server-backed receipt log. Only structured data is stored; photos never leave this device. */
let cachedLogs=[];
async function fetchLogs(){
  const{response,data}=await apiCall({action:'list'});
  if(!response.ok){document.querySelector('#logList').innerHTML='<p class="error">Could not load your receipt log.</p>';return}
  cachedLogs=data.logs||[];
  document.querySelector('#dlAllCsv').classList.toggle('hidden',!cachedLogs.length);
  renderLog(cachedLogs);
}
function buildAllCsv(logs){
  const L=[['Vendor','Date','Currency','Item','Amount','Subtotal','Tax','Total'].map(csvCell).join(',')];
  logs.forEach(e=>{
    const head=[e.vendor||'',e.date||'',e.currency||''];
    const sums=[e.subtotal||'',e.tax||'',e.total||''];
    if(e.items.length)e.items.forEach(it=>L.push([...head,it.name,it.amount,...sums].map(csvCell).join(',')));
    else L.push([...head,'','',...sums].map(csvCell).join(','));
  });
  return L.join(String.fromCharCode(10));
}
document.querySelector('#dlAllCsv').addEventListener('click',()=>{
  if(!cachedLogs.length)return;
  const blob=new Blob([buildAllCsv(cachedLogs)],{type:'text/csv'});
  const a=document.createElement('a');
  a.href=URL.createObjectURL(blob);
  a.download='receipt-log-'+new Date().toISOString().slice(0,10)+'.csv';
  document.body.appendChild(a);a.click();a.remove();
  setTimeout(()=>URL.revokeObjectURL(a.href),4000);
  bbTrack('receipt_export',{how:'log_all'});
});
function renderLog(log){
  const list=document.querySelector('#logList');
  if(!log.length){list.innerHTML='<p class="meta" style="font-size:13px;opacity:.75">Nothing saved yet. After a scan, hit &ldquo;Save to log&rdquo; and it will live here, in your account.</p>';return}
  list.innerHTML='';
  log.forEach(entry=>{
    const row=document.createElement('div');row.className='log-row';
    const totalTxt=entry.total?entry.currency+' '+entry.total:'no total';
    row.innerHTML='<div><b>'+esc(entry.vendor||'Unnamed receipt')+'</b><div class="meta">'+esc(entry.date||'no date')+' · '+entry.items.length+' lines · '+esc(totalTxt)+'</div></div><div class="spacer"></div>';
    const load=document.createElement('button');load.type='button';load.className='mini';load.textContent='Load';
    load.addEventListener('click',()=>{addCard(entry,'');bbTrack('receipt_log_load',{id:entry.id})});
    const del=document.createElement('button');del.type='button';del.className='mini';del.textContent='Delete';
    del.addEventListener('click',async()=>{
      if(!confirm('Delete this receipt from your log?'))return;
      const{response}=await apiCall({action:'delete',id:entry.id});
      if(response.ok)fetchLogs();
    });
    row.appendChild(load);row.appendChild(del);
    list.appendChild(row);
  });
}
fetchLogs();
</script></main></body></html>
