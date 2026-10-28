<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Day Log | Close the Day, Queue Up Tomorrow | Leave It to Bum Bum</title>
<meta name="description" content="Talk through your day in a few minutes. Bum Bum turns it into jot notes, scores yesterday's list against today, and emails you tomorrow's top priorities.">
<meta property="og:title" content="Day Log | Close the Day, Queue Up Tomorrow | Leave It to Bum Bum">
<meta property="og:description" content="Talk through your day in a few minutes. Bum Bum turns it into jot notes, scores yesterday's list against today, and emails you tomorrow's top priorities.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/day-log/">
<meta name="twitter:card" content="summary">
<link rel="canonical" href="https://leaveittobumbum.com/tools/day-log/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Day Log",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" },
  "description": "Talk through your day in a few minutes. Bum Bum turns it into jot notes, scores yesterday's list against today, and emails you tomorrow's top priorities.",
  "url": "https://leaveittobumbum.com/tools/day-log/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Where does my voice go?",
      "acceptedAnswer": { "@type": "Answer", "text": "Your words are kept, your voice is not. Recordings are transcribed and then discarded. Only the text of your log is stored, in your account." }
    },
    {
      "@type": "Question",
      "name": "What lands in the email?",
      "acceptedAnswer": { "@type": "Answer", "text": "One email each day you file: yesterday's list scored against today, your done list, challenges, and tomorrow's top priorities in clean jot notes." }
    },
    {
      "@type": "Question",
      "name": "What if I skip a day?",
      "acceptedAnswer": { "@type": "Answer", "text": "Nothing breaks. The scorecard compares against your most recent filed day, whenever that was." }
    },
    {
      "@type": "Question",
      "name": "Can I type instead of talking?",
      "acceptedAnswer": { "@type": "Answer", "text": "Yes. The one big box takes typing too. Talk, type, or mix both in the same log." }
    }
  ]
}
</script>
<link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
.error{color:#b3261e;font-weight:700}
.hidden{display:none!important}
.panel h2{margin-top:0}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
.read-note{background:#fdf8ef;border:1px solid #e7dcc3;border-radius:10px;padding:10px 14px;font-size:14px;margin:10px 0}
.prompt-card{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:14px;background:#fff;padding:16px;margin-bottom:14px}
.prompt-card h3{margin:0 0 4px;font-size:17px}
.prompt-card .hint{font-size:13.5px;opacity:.75;margin:0 0 10px}
.prompt-card textarea{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;resize:vertical;min-height:74px}
.prompt-card textarea:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.mic-row{display:flex;gap:8px;align-items:center;margin-top:10px;flex-wrap:wrap}
.mic-btn{border:2px solid #1a291c;background:#d8ff62;color:#142015;border-radius:999px;padding:8px 16px;font:inherit;font-weight:900;font-size:14px;cursor:pointer}
.mic-btn.recording{background:#ff7448}
.mic-status{font-size:13px;font-weight:700;opacity:.75}
.jot h4{margin:16px 0 6px;font-size:15px}
.jot ul{margin:0;padding-left:20px}
.jot li{margin:3px 0}
.score-item{display:flex;gap:8px;align-items:baseline;margin:4px 0}
.score-tag{flex:none;font-size:12px;font-weight:900;border:1.5px solid #1a291c;border-radius:999px;padding:2px 10px}
.score-done{background:#d8ff62}.score-partial{background:#ffe9b8}.score-not{background:#fff;opacity:.75}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
.log-table{width:100%;border-collapse:collapse;font-size:14.5px}
.log-table th{text-align:left;font-size:12.5px;text-transform:uppercase;letter-spacing:.04em;opacity:.65;padding:6px 8px;border-bottom:2px solid #e2d7bf}
.log-table td{padding:9px 8px;border-bottom:1px solid #eee3cb;vertical-align:top}
.log-table .log-row{cursor:pointer}
.log-table .log-row:hover{background:#fffdf6}
.log-table .log-detail td{background:#fffdf8}
.log-table .chev{font-weight:900;text-align:right;white-space:nowrap}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Day Log"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/icons/day-log.png" alt="Day Log"><h1>5 min/day. Increase your productivity.</h1><p class="lede bb-desc">You're busy out there working on your business. Sometimes things get forgotten or deprioritized; when you look back, you forget what you did the past week. Spend 5 min every evening with Bum Bum, brain dump what you did, challenges faced, lessons learned and what to achieve. Bum bum reads what you've logged and sends you a daily email keeping you on track! He also summarizes highlights and growth on a weekly and monthly basis.</p>
<?php if (!$user): ?>
<section class="panel"><h2>Day Log lives in your account</h2><p class="lede">The scorecard only works if yesterday's list is waiting for you, so Day Log needs a free account to file into. Your logs, your words, nobody else's.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/day-log/') ?>">Sign in or create a free account</a></p></section>
<?php else: ?>

<div id="yesterdaySlot"></div>

<section class="panel" id="logPanel">
<h2>Today's log</h2>
<div class="read-note" style="margin-top:0">
<b>talk through these in any order, brain dump is fine!</b>
<ul style="margin:8px 0;padding-left:20px">
<li>What did you do today, what challenges did you face and how do you plan to overcome them?</li>
<li>What did you learn today?</li>
<li>What do you plan on achieving tomorrow?</li>
</ul>
<p style="margin:0">One record button, one ramble. Bum Bum sorts it into jot notes when you file.</p>
</div>
<textarea id="logText" placeholder="Tap Record and talk through your day, or start typing..." style="width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;resize:vertical;min-height:170px"></textarea>
<div class="mic-row"><button type="button" class="mic-btn" id="micBtn">Record</button><button type="button" class="button" id="fileBtn" disabled style="opacity:.45">File my day</button><span class="mic-status" id="micStatus"></span></div>
<p id="fileStatus" class="mic-status" style="margin-top:8px"></p>
<p id="batchError" class="error"></p>
</section>

<section class="panel hidden" id="resultPanel">
<h2>Filed. Here is your day in jot notes.</h2>
<div class="jot" id="resultBody"></div>
<p class="read-note" id="emailNote"></p>
</section>

<section class="panel" id="historyPanel">
<h2>Past logs</h2>
<div id="historyList"><p class="meta">Loading your past logs...</p></div>
</section>

<section class="panel" id="lookbackPanel">
<h2>Look back</h2>
<p class="meta" style="margin-top:0">Turn a stretch of logs into highlights: accomplishments, learnings, and how you are growing over time. One action per fresh summary; re-opening the same stretch is free.</p>
<div class="btnrow"><button type="button" class="button secondary" id="sumWeek">Summarize my week</button><button type="button" class="button secondary" id="sumMonth">Summarize my month</button></div>
<p id="sumStatus" class="mic-status" style="margin-top:8px"></p>
<p id="sumError" class="error"></p>
<div id="sumBody"></div>
</section>
<?php endif; ?>

<section id="faq">
<h2>Day Log questions</h2>
<details><summary>Where does my voice go?</summary><p>Your words are kept, your voice is not. Recordings are transcribed and then discarded. Only the text of your log is stored, in your account.</p></details>
<details><summary>What lands in the email?</summary><p>One email each day you file: yesterday's list scored against today, your done list, challenges, and tomorrow's top priorities in clean jot notes. No log, no email.</p></details>
<details><summary>What if I skip a day?</summary><p>Nothing breaks. The scorecard compares against your most recent filed day, whenever that was.</p></details>
<details><summary>Can I type instead of talking?</summary><p>Yes. The one big box takes typing too. Talk, type, or mix both in the same log.</p></details>
<details><summary>Can I fix a day after filing it?</summary><p>Yes. File again the same day and the new version replaces the old one. Re-filing the same day does not cost another action.</p></details>
</section>

<section aria-label="More tiny tools">
<h2>More tiny tools</h2>
<p class="lede">Day Log is one of the tools in Bum Bum's toolbox. <a href="/tools/">See the whole toolbox</a>.</p>
</section>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?>
<?php if ($user): ?>
<script>
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/day-log.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}
bbTrack('tool_opened',{tool:'day-log'});

const ta=document.querySelector('#logText');
const micBtn=document.querySelector('#micBtn');
function refreshFile(){const has=ta.value.trim().length>0;fileBtn.disabled=!has;fileBtn.style.opacity=has?'1':'.45'}
ta.addEventListener('input',refreshFile);

function localToday(){const d=new Date();const pad=n=>String(n).padStart(2,'0');return d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate())}
function newKey(){try{return crypto.randomUUID()}catch(e){return 'k'+Date.now()+'-'+Math.random().toString(36).slice(2,12)}}

/* --- Recording: live browser transcription first (the loom-ish cascade),
       recorded audio to server Whisper when live transcription is absent. --- */
const SR=window.SpeechRecognition||window.webkitSpeechRecognition;
let activeRec=null; // {mode, recog|recorder, chunks, stream, base, stopped}

function setStatus(msg){document.querySelector('#micStatus').textContent=msg||''}
function setBtn(recording){micBtn.textContent=recording?'Stop':'Record';micBtn.classList.toggle('recording',!!recording)}

async function stopActive(){
  const a=activeRec; if(!a) return; activeRec=null;
  setBtn(false);
  if(a.mode==='sr'){a.stopped=true;try{a.recog.stop()}catch(e){}setStatus('');return}
  if(a.mode==='mr'){try{a.recorder.stop()}catch(e){}}
}

micBtn.addEventListener('click',async()=>{
    if(activeRec){stopActive();return}
    if(SR){
      const recog=new SR();
      recog.continuous=true; recog.interimResults=true;
      recog.lang=navigator.language||'en-US';
      const a={mode:'sr',recog,base:ta.value,stopped:false};
      activeRec=a;
      recog.onresult=e=>{
        let finalText='';
        for(let i=e.resultIndex;i<e.results.length;i++){if(e.results[i].isFinal)finalText+=e.results[i][0].transcript}
        if(finalText){a.base=(a.base?a.base.replace(/\s+$/,'')+' ':'')+finalText.trim();ta.value=a.base;refreshFile()}
      };
      recog.onend=()=>{if(activeRec===a&&!a.stopped){try{recog.start()}catch(e){}}};
      recog.onerror=e=>{if(e.error==='not-allowed'){setStatus('Mic is blocked. Allow the microphone, or type instead.');stopActive()}else if(e.error!=='aborted'&&e.error!=='no-speech'){setStatus('Listening hiccup: '+e.error+'. Type instead if it keeps happening.')}};
      try{recog.start();setBtn(true);setStatus('Listening. Talk away, then tap Stop.')}catch(e){setStatus('Could not start listening. Type instead.');activeRec=null}
      return;
    }
    if(!navigator.mediaDevices||!window.MediaRecorder){setStatus('This browser cannot record here. Type instead.');return}
    try{
      const stream=await navigator.mediaDevices.getUserMedia({audio:true});
      const mime=MediaRecorder.isTypeSupported('audio/webm')?'audio/webm':(MediaRecorder.isTypeSupported('audio/mp4')?'audio/mp4':'');
      const recorder=mime?new MediaRecorder(stream,{mimeType:mime}):new MediaRecorder(stream);
      const a={mode:'mr',recorder,stream,chunks:[],base:ta.value};
      activeRec=a;
      recorder.ondataavailable=e=>{if(e.data&&e.data.size)a.chunks.push(e.data)};
      recorder.onstop=async()=>{
        stream.getTracks().forEach(t=>t.stop());
        if(activeRec===a)activeRec=null;
        setBtn(false);
        if(!a.chunks.length){setStatus('Nothing was captured. Try again, or type.');return}
        setStatus('Transcribing...');
        const blob=new Blob(a.chunks,{type:recorder.mimeType||'audio/webm'});
        const dataUrl=await new Promise(res=>{const r=new FileReader();r.onload=()=>res(r.result);r.onerror=()=>res('');r.readAsDataURL(blob)});
        if(!dataUrl){setStatus('Recording could not be read. Type instead.');return}
        const{response,data}=await apiCall({action:'transcribe',audio:dataUrl});
        if(response.ok&&data.text){ta.value=(a.base?a.base.replace(/\s+$/,'')+' ':'')+data.text;refreshFile();setStatus('Transcribed. Fix anything, then file when ready.')}
        else setStatus((data&&data.error)||'Transcription failed. Type instead.');
      };
      recorder.start();
      setBtn(true);setStatus('Recording. Tap Stop when done.');
    }catch(e){setStatus('Mic is blocked. Allow the microphone, or type instead.')}
});

/* --- Rendering structured jot notes --- */
function sectionHtml(title,items){
  if(!items||!items.length)return '';
  return '<h4>'+esc(title)+'</h4><ul>'+items.map(i=>'<li>'+esc(i)+'</li>').join('')+'</ul>';
}
function reflectionHtml(reflection){
  if(!reflection||!reflection.length)return '';
  const tag=s=>s==='done'?'<span class="score-tag score-done">done</span>':s==='partial'?'<span class="score-tag score-partial">partial</span>':'<span class="score-tag score-not">not mentioned</span>';
  return '<h4>Yesterday\u2019s list, scored</h4>'+reflection.map(r=>'<div class="score-item">'+tag(r.status)+'<span>'+esc(r.item)+'</span></div>').join('');
}
function renderResult(structured,emailNote){
  const body=document.querySelector('#resultBody');
  body.innerHTML=reflectionHtml(structured.reflection)
    +sectionHtml('Done today',structured.done)
    +sectionHtml('Set out but did not happen',structured.missed)
    +sectionHtml('Challenges',structured.challenges)
    +sectionHtml('Beating them',structured.overcome)
    +sectionHtml('Tomorrow\u2019s top list',structured.tomorrow)
    +sectionHtml('Learnings',structured.learnings);
  document.querySelector('#emailNote').textContent=emailNote;
  const panel=document.querySelector('#resultPanel');
  panel.classList.remove('hidden');
  panel.scrollIntoView({behavior:'smooth'});
}

/* --- File the day --- */
const fileBtn=document.querySelector('#fileBtn');
fileBtn.addEventListener('click',async()=>{
  if(activeRec)await stopActive();
  const text=ta.value.trim();
  const err=document.querySelector('#batchError');
  const status=document.querySelector('#fileStatus');
  err.textContent='';
  if(text.length<10){err.textContent='There is nothing to file yet. Talk or type a little about your day first.';return}
  fileBtn.disabled=true; status.textContent='Bum Bum is structuring your day...';
  const{response,data}=await apiCall({action:'save',text,todayLocal:localToday(),tzName:(Intl.DateTimeFormat().resolvedOptions().timeZone||''),idempotencyKey:newKey()});
  fileBtn.disabled=false; status.textContent=''; refreshFile();
  if(!response.ok){err.textContent=(data&&data.error)||'Filing tripped. Your words are still in the box; try once more.';return}
  bbTrack('daylog_filed',{first_today:!!data.first_today,reflection:(data.reflection||[]).length});
  renderResult(data.structured, data.first_today
    ? (data.email_sent?'These jot notes were just emailed to you. Tomorrow, this list is the scorecard.':'Filed. The email copy is not switched on in this environment, but your log is saved below and in Past logs.')
    : 'Updated. Re-filing the same day replaces the earlier version and costs no extra action.');
  if(window.BBDropStrip)window.BBDropStrip.show('day-log');
  loadHistory();
});

/* --- Context + history on load --- */
(async()=>{
  const{response,data}=await apiCall({action:'context',todayLocal:localToday()});
  if(response.ok&&data.yesterday&&data.yesterday.tomorrow&&data.yesterday.tomorrow.length){
    const items=data.yesterday.tomorrow.map(i=>'<li>'+esc(i)+'</li>').join('');
    document.querySelector('#yesterdaySlot').innerHTML='<div class="read-note"><b>Yesterday ('+esc(data.yesterday.date)+') you aimed for:</b><ul style="margin:6px 0 0;padding-left:20px">'+items+'</ul><p style="margin:6px 0 0">Today\u2019s first prompt scores this list. Answer honestly; not mentioned is a fine answer.</p></div>';
  }
  if(response.ok&&data.today&&data.today.structured){
    renderResult(data.today.structured,'Already filed today. Filing again updates it and costs no extra action.');
  }
})();
async function loadHistory(){
  const list=document.querySelector('#historyList');
  const{response,data}=await apiCall({action:'history'});
  if(!response.ok||!data.logs||!data.logs.length){list.innerHTML='<p class="meta">No logs yet. Your first filed day will show up here.</p>';return}
  const rows=data.logs.map((l,i)=>{
    const s=l.structured||{};
    const refl=s.reflection||[];
    const kept=refl.filter(r=>r.status==='done').length;
    const score=refl.length?kept+' of '+refl.length+' kept':'';
    const inner='<div class="jot">'+reflectionHtml(s.reflection)+sectionHtml('Done today',s.done)+sectionHtml('Set out but did not happen',s.missed)+sectionHtml('Challenges',s.challenges)+sectionHtml('Beating them',s.overcome)+sectionHtml('Tomorrow\u2019s top list',s.tomorrow)+sectionHtml('Learnings',s.learnings)+'</div>';
    return '<tr class="log-row" data-i="'+i+'"><td><b>'+esc(l.date)+'</b></td><td>'+(s.done?s.done.length:0)+' done</td><td>'+(s.tomorrow?s.tomorrow.length:0)+' planned</td><td>'+esc(score)+'</td><td class="chev" id="chev-'+i+'">+</td></tr>'
      +'<tr class="log-detail hidden" id="detail-'+i+'"><td colspan="5">'+inner+'</td></tr>';
  }).join('');
  list.innerHTML='<table class="log-table"><thead><tr><th>Date</th><th>Done</th><th>Planned</th><th>Scorecard</th><th></th></tr></thead><tbody>'+rows+'</tbody></table>';
  list.querySelectorAll('.log-row').forEach(row=>{
    row.addEventListener('click',()=>{
      const i=row.dataset.i;
      const det=document.querySelector('#detail-'+i);
      const open=det.classList.toggle('hidden')===false;
      document.querySelector('#chev-'+i).textContent=open?'\u2212':'+';
    });
  });
}
loadHistory();

/* --- Look back: week and month summaries --- */
function summaryHtml(entry){
  const s=entry.summary||{};
  const t=entry.tallies||{};
  const label=entry.range==='week'?'Your week':'Your month';
  const tallyLine=(t.days||0)+' days logged &middot; '+(t.done_total||0)+' things done'
    +((t.scored_total||0)?' &middot; scorecard: '+(t.scored_done||0)+' done, '+(t.scored_partial||0)+' partial, '+(t.scored_not||0)+' not mentioned':'');
  return '<div style="margin-top:14px"><h3 style="margin:0 0 2px">'+label+': '+esc(entry.window_start)+' to '+esc(entry.window_end)+'</h3>'
    +'<p class="meta" style="margin:0 0 6px">'+tallyLine+'</p><div class="jot">'
    +sectionHtml('Accomplishments',s.accomplishments)
    +sectionHtml('Learnings',s.learnings)
    +sectionHtml('Growth over time',s.growth)
    +sectionHtml('Themes that kept coming up',s.themes)
    +'</div></div>';
}
async function runSummary(range){
  const status=document.querySelector('#sumStatus');
  const err=document.querySelector('#sumError');
  err.textContent=''; status.textContent='Bum Bum is reading your '+(range==='week'?'week':'month')+'...';
  const{response,data}=await apiCall({action:'summarize',range,todayLocal:localToday()});
  status.textContent='';
  if(!response.ok){err.textContent=(data&&data.error)||'That summary tripped. Try once more.';return}
  bbTrack('daylog_summary',{range,cached:!!data.cached});
  document.querySelector('#sumBody').innerHTML=summaryHtml(data);
  if(window.BBDropStrip)window.BBDropStrip.show('day-log');
}
document.querySelector('#sumWeek').addEventListener('click',()=>runSummary('week'));
document.querySelector('#sumMonth').addEventListener('click',()=>runSummary('month'));
(async()=>{
  const{response,data}=await apiCall({action:'summaries'});
  if(response.ok&&data.summaries&&data.summaries.length){
    document.querySelector('#sumBody').innerHTML=data.summaries.map(summaryHtml).join('');
  }
})();
</script>
<?php endif; ?>
<script src="/tools/drop-strip.js?v=20261027r2"></script></body></html>
