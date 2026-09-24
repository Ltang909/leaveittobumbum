<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $user = currentUser(); $usage = $user ? usageFor(billingUser($user)) : null; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title>Gap Scout | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
.error{color:#b3261e;font-weight:700}
.hidden{display:none!important}
details.raw{margin-top:14px}
details.raw summary{cursor:pointer;font-weight:700;font-size:14px}
details.raw pre{background:#fdf8ef;border:1px solid #e7dcc3;border-radius:10px;padding:12px;font-size:12px;white-space:pre-wrap;max-height:200px;overflow-y:auto}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
.shell .button:disabled{opacity:.45;cursor:not-allowed;box-shadow:none;transform:none}
.stepnum{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:999px;background:#2f2a22;color:#fff;font-weight:900;font-size:15px;margin-right:8px}
.panel h2{margin-top:0;display:flex;align-items:center}
.jd-card{border:1px solid #e2d7bf;border-radius:14px;background:#fffdf8;padding:14px;margin-bottom:12px}
.jd-card input[type=text],.jd-card textarea{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:6px}
.jd-card input[type=text]:focus,.jd-card textarea:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.jd-card textarea{min-height:110px;resize:vertical}
.jd-head{display:flex;align-items:center;gap:8px}
.jd-head .spacer{flex:1}
.jd-title{font-weight:900;font-size:16px}
.mini{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:6px 12px;font-weight:700;font-size:13px;cursor:pointer}
.mini.danger{color:#b3261e;border-color:#e5b8b3}
.or-row{display:flex;align-items:center;gap:10px;margin:8px 0}
.or-row .line{flex:1;height:1px;background:#e7dcc3}
.or-row span{font-size:12px;font-weight:800;opacity:.6}
.fetch-row{display:flex;gap:8px}
.fetch-row input{flex:1}
.stat-grid{display:grid;gap:10px;margin:16px 0}
@media(min-width:640px){.stat-grid{grid-template-columns:repeat(3,1fr)}}
.stat{background:#fff;border:1px solid #e2d7bf;border-radius:14px;padding:14px 16px;box-shadow:0 2px 10px rgba(90,72,38,.08)}
.stat .n{font-family:Fraunces,Georgia,serif;font-size:2rem;font-weight:700;line-height:1}
.stat .l{font-size:13px;font-weight:700;opacity:.75;margin-top:4px}
.gap-card{border:1px solid #e2d7bf;border-left:5px solid #e11d48;border-radius:14px;background:#fff;padding:14px 16px;margin-bottom:10px;box-shadow:0 2px 10px rgba(90,72,38,.08)}
.gap-card .top{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.gap-card .skill{font-weight:900;font-size:18px}
.catchip{font-size:11px;font-weight:900;color:#fff;border-radius:999px;padding:3px 10px;letter-spacing:.02em}
.gap-card .freq{font-size:13px;font-weight:800;margin-top:6px}
.gap-card .freq b{color:#b3261e}
.evidence{font-size:13.5px;font-style:italic;opacity:.85;border-left:3px solid #e7dcc3;padding-left:10px;margin:8px 0 0}
.match-chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:8px}
.match-chip{background:#eef7ee;border:1px solid #bfe0bf;border-radius:999px;padding:6px 12px;font-size:13px;font-weight:800;color:#1d5c1d}
.miss-chip{background:#fdf0f0;border:1px solid #f0c6c6;border-radius:999px;padding:6px 12px;font-size:13px;font-weight:800;color:#8c1f1f}
.jd-break{margin-top:8px}
.jd-break summary{cursor:pointer;font-weight:800;font-size:15px;padding:8px 0}
.ok-note{background:#eef7ee;border:1px solid #bfe0bf;border-radius:10px;padding:10px 14px;font-size:14px;margin-top:10px}
.warn-note{background:#fdf6e3;border:1px solid #e7d08a;border-radius:10px;padding:10px 14px;font-size:14px;margin-top:10px}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Gap Scout"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-curious.png" alt="Bum Bum the curious cat detective, on the hunt for missing skills"><h1>Find the gaps before they do.</h1><p class="lede">Upload your resume, drop in up to 5 job descriptions, and Bum Bum shows you the skills they keep asking for that your resume never mentions, with the exact line from each posting as proof. Paste the text or a link and Bum Bum fetches it. Everything happens in your browser, your resume never leaves your device, and it is free.</p>
<?php if (!$user): ?><section class="panel"><h2>Sign in to scout gaps</h2><a class="button" href="/account/?next=<?= urlencode('/tools/gap-scout/') ?>">Sign in or create an account</a></section><?php else: ?>
<?php $low = $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>

<section class="panel" id="step1">
<h2><span class="stepnum">1</span>Drop in your resume</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Choose your resume file">
<p style="font-size:40px;margin:0">📄</p>
<p><b>Drop your resume here</b>, or pick it below.</p>
<p style="font-size:13px;opacity:.75">PDF or DOCX. Bum Bum reads it right in your browser, nothing is uploaded.</p>
</div>
<input type="file" id="resumeFile" accept=".pdf,.docx,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="hidden">
<div class="btnrow"><button type="button" class="button secondary" id="btnPick">Choose file</button></div>
<p id="resumeStatus" style="font-weight:700"></p>
<p id="resumeError" class="error"></p>
<div id="resumeInfo" class="hidden">
<div class="ok-note" id="resumeSummary"></div>
<details class="raw"><summary>Peek at the text Bum Bum read</summary><pre id="resumePreview"></pre></details>
</div>
</section>

<section class="panel" id="step2">
<h2><span class="stepnum">2</span>Add job descriptions</h2>
<div id="jdList"></div>
<div class="btnrow"><button type="button" class="button secondary" id="addJdBtn">+ Add another job description</button></div>
</section>

<section class="panel" id="step3">
<h2><span class="stepnum">3</span>See your gaps</h2>
<p style="opacity:.8">Bum Bum compares every description against your resume and ranks the missing skills by how many postings ask for them.</p>
<div class="btnrow">
<button type="button" class="button" id="analyzeBtn" disabled>Scout my gaps</button>
<button type="button" class="button secondary hidden" id="copyBtn">Copy missing skills</button>
<button type="button" class="button secondary hidden" id="resetBtn">Start over</button>
</div>
<p id="analyzeError" class="error"></p>
<div id="results" class="hidden">
<div class="stat-grid">
<div class="stat"><div class="n" id="statJds">0</div><div class="l">job descriptions analyzed</div></div>
<div class="stat"><div class="n" id="statMissing" style="color:#b3261e">0</div><div class="l">skills missing from your resume</div></div>
<div class="stat"><div class="n" id="statMatched" style="color:#1d5c1d">0</div><div class="l">skills you already have</div></div>
</div>
<h3 style="font-family:Fraunces,Georgia,serif;font-size:1.5rem;margin:18px 0 10px">Your biggest gaps</h3>
<div id="gapList"></div>
<div id="noGaps" class="ok-note hidden">No gaps found. Every skill these postings ask for already shows up on your resume. Go get them.</div>
<h3 style="font-family:Fraunces,Georgia,serif;font-size:1.5rem;margin:18px 0 10px">Already on your resume</h3>
<div class="match-chips" id="matchChips"></div>
<h3 style="font-family:Fraunces,Georgia,serif;font-size:1.5rem;margin:18px 0 10px">Posting by posting</h3>
<div id="jdBreakdown"></div>
</div>
</section>

<?php endif; ?>
<script src="https://unpkg.com/mammoth@1.6.0/mammoth.browser.min.js"></script>
<script src="https://unpkg.com/pdfjs-dist@3.11.174/build/pdf.min.js"></script>
<script>
bbTrack('tool_opened',{tool:'gap-scout'});
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function apiCall(url,payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch(url,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}

/* ---------- skill taxonomy ---------- */
const CATS={
 'Paid Media':'#e11d48','SEO & Content':'#d97706','Analytics & Data':'#2563eb',
 'MarTech & Ops':'#7c3aed','Lifecycle & Email':'#059669','Strategy & Leadership':'#ea580c','AI & Automation':'#0d9488'
};
const SKILLS=[
 ['Google Ads','Paid Media',['google ads','adwords']],
 ['Microsoft Ads','Paid Media',['microsoft ads','bing ads']],
 ['Meta Ads','Paid Media',['meta ads','facebook ads','instagram ads']],
 ['LinkedIn Ads','Paid Media',['linkedin ads']],
 ['TikTok Ads','Paid Media',['tiktok ads']],
 ['Snapchat Ads','Paid Media',['snapchat ads']],
 ['X Ads','Paid Media',['twitter ads','x ads']],
 ['Reddit Ads','Paid Media',['reddit ads']],
 ['YouTube Ads','Paid Media',['youtube ads']],
 ['Display Advertising','Paid Media',['display advertising','display ads']],
 ['Programmatic Advertising','Paid Media',['programmatic advertising','programmatic']],
 ['Retargeting','Paid Media',['retargeting','remarketing']],
 ['Paid Search','Paid Media',['paid search','sem']],
 ['Paid Social','Paid Media',['paid social']],
 ['Performance Max','Paid Media',['performance max','pmax']],
 ['Amazon Ads','Paid Media',['amazon ads']],
 ['SEO','SEO & Content',['seo','search engine optimization']],
 ['Technical SEO','SEO & Content',['technical seo']],
 ['Keyword Research','SEO & Content',['keyword research']],
 ['Link Building','SEO & Content',['link building']],
 ['Content Marketing','SEO & Content',['content marketing']],
 ['Content Strategy','SEO & Content',['content strategy']],
 ['Copywriting','SEO & Content',['copywriting']],
 ['AEO','SEO & Content',['aeo','answer engine optimization','generative engine optimization','geo']],
 ['Local SEO','SEO & Content',['local seo']],
 ['Social Media Management','SEO & Content',['social media management','social media marketing']],
 ['Community Management','SEO & Content',['community management']],
 ['Influencer Marketing','SEO & Content',['influencer marketing']],
 ['Video Marketing','SEO & Content',['video marketing']],
 ['GA4','Analytics & Data',['ga4','google analytics 4']],
 ['Google Analytics','Analytics & Data',['google analytics','universal analytics']],
 ['Google Tag Manager','Analytics & Data',['google tag manager']],
 ['BigQuery','Analytics & Data',['bigquery','big query']],
 ['SQL','Analytics & Data',['sql']],
 ['Looker Studio','Analytics & Data',['looker studio','data studio','looker']],
 ['Tableau','Analytics & Data',['tableau']],
 ['Power BI','Analytics & Data',['power bi']],
 ['PostHog','Analytics & Data',['posthog']],
 ['Mixpanel','Analytics & Data',['mixpanel']],
 ['Amplitude','Analytics & Data',['amplitude']],
 ['Heap','Analytics & Data',['heap analytics','heap']],
 ['Hotjar','Analytics & Data',['hotjar','heatmaps','heat maps']],
 ['Multi-Touch Attribution','Analytics & Data',['multi-touch attribution','multi touch attribution']],
 ['Incrementality Testing','Analytics & Data',['incrementality','incrementality testing','lift test','lift study']],
 ['Marketing Mix Modeling','Analytics & Data',['marketing mix modeling','media mix modeling']],
 ['A/B Testing','Analytics & Data',['a/b testing','ab testing','split testing']],
 ['CRO','Analytics & Data',['conversion rate optimization','cro']],
 ['Funnel Analysis','Analytics & Data',['funnel analysis']],
 ['Cohort Analysis','Analytics & Data',['cohort analysis']],
 ['Excel','Analytics & Data',['excel','google sheets','spreadsheets']],
 ['HubSpot','MarTech & Ops',['hubspot']],
 ['Salesforce','MarTech & Ops',['salesforce','sfdc']],
 ['Marketo','MarTech & Ops',['marketo']],
 ['Pardot','MarTech & Ops',['pardot','account engagement']],
 ['ActiveCampaign','MarTech & Ops',['activecampaign','active campaign']],
 ['Mailchimp','MarTech & Ops',['mailchimp']],
 ['Customer.io','MarTech & Ops',['customer.io','customerio']],
 ['Braze','MarTech & Ops',['braze']],
 ['Iterable','MarTech & Ops',['iterable']],
 ['Klaviyo','MarTech & Ops',['klaviyo']],
 ['Zapier','MarTech & Ops',['zapier']],
 ['Marketing Automation','MarTech & Ops',['marketing automation']],
 ['Lead Scoring','MarTech & Ops',['lead scoring']],
 ['Lead Routing','MarTech & Ops',['lead routing']],
 ['CRM','MarTech & Ops',['crm']],
 ['CDP','MarTech & Ops',['cdp','customer data platform']],
 ['Webhooks','MarTech & Ops',['webhook','webhooks']],
 ['APIs','MarTech & Ops',['api','apis']],
 ['Email Marketing','Lifecycle & Email',['email marketing']],
 ['Lifecycle Marketing','Lifecycle & Email',['lifecycle marketing','life cycle marketing']],
 ['Drip Campaigns','Lifecycle & Email',['drip campaign','drip campaigns','nurture campaign','nurture sequence']],
 ['Segmentation','Lifecycle & Email',['segmentation']],
 ['Personalization','Lifecycle & Email',['personalization','personalized']],
 ['Push Notifications','Lifecycle & Email',['push notification','push notifications']],
 ['SMS Marketing','Lifecycle & Email',['sms marketing']],
 ['Newsletters','Lifecycle & Email',['newsletter','newsletters']],
 ['Demand Generation','Strategy & Leadership',['demand generation','demand gen']],
 ['Lead Generation','Strategy & Leadership',['lead generation','lead gen']],
 ['Account-Based Marketing','Strategy & Leadership',['account-based marketing','account based marketing','abm']],
 ['Go-to-Market','Strategy & Leadership',['go-to-market','go to market']],
 ['Growth Strategy','Strategy & Leadership',['growth strategy']],
 ['Pipeline Marketing','Strategy & Leadership',['pipeline marketing']],
 ['Product Marketing','Strategy & Leadership',['product marketing']],
 ['Customer Marketing','Strategy & Leadership',['customer marketing']],
 ['Partner Marketing','Strategy & Leadership',['partner marketing']],
 ['Event Marketing','Strategy & Leadership',['event marketing','field marketing']],
 ['Webinars','Strategy & Leadership',['webinar','webinars']],
 ['Budget Management','Strategy & Leadership',['budget management','managing budgets','budget ownership']],
 ['Forecasting','Strategy & Leadership',['forecasting']],
 ['Agency Management','Strategy & Leadership',['agency management','managing agencies']],
 ['Cross-Functional Leadership','Strategy & Leadership',['cross-functional']],
 ['Stakeholder Management','Strategy & Leadership',['stakeholder management']],
 ['Team Leadership','Strategy & Leadership',['team leadership','people management']],
 ['AI Workflows','AI & Automation',['ai workflow','ai workflows','ai-assisted','ai-powered marketing']],
 ['LLMs','AI & Automation',['llm','llms','large language model','chatgpt','generative ai']],
 ['Prompt Engineering','AI & Automation',['prompt engineering']],
 ['Workflow Automation','AI & Automation',['workflow automation','automate workflows']]
];
const norm=s=>s.toLowerCase().replace(/[^a-z0-9]+/g,' ').replace(/\s+/g,' ').trim();
const skillRegexes=SKILLS.map(([name,cat,aliases])=>({name,cat,regexes:aliases.map(a=>new RegExp('\\b'+norm(a).replace(/ /g,'\\s+')+'\\b','g'))}));
function matchSkills(text){
  const t=' '+norm(text)+' ';
  const found=new Map();
  skillRegexes.forEach(s=>{
    let count=0;
    s.regexes.forEach(r=>{r.lastIndex=0;let m;while((m=r.exec(t))){count++;if(count>50)break}});
    if(count>0)found.set(s.name,{cat:s.cat,count});
  });
  return found;
}
function evidenceFor(text,name){
  const skill=skillRegexes.find(s=>s.name===name);
  const sentences=String(text).split(/(?<=[.!?\n])\s+/);
  for(const sen of sentences){
    const n=' '+norm(sen)+' ';
    if(skill.regexes.some(r=>{r.lastIndex=0;return r.test(n)})){
      const s=sen.trim().replace(/\s+/g,' ');
      return s.length>220?s.slice(0,220)+'…':s;
    }
  }
  return '';
}

/* ---------- resume upload ---------- */
let resumeText='',resumeSkillCount=0;
const dropzone=document.getElementById('dropzone'),fileInput=document.getElementById('resumeFile');
document.getElementById('btnPick').addEventListener('click',()=>fileInput.click());
dropzone.addEventListener('click',()=>fileInput.click());
dropzone.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();fileInput.click()}});
['dragover','dragenter'].forEach(ev=>dropzone.addEventListener(ev,e=>{e.preventDefault();dropzone.classList.add('over')}));
['dragleave','drop'].forEach(ev=>dropzone.addEventListener(ev,e=>{e.preventDefault();dropzone.classList.remove('over')}));
dropzone.addEventListener('drop',e=>{if(e.dataTransfer.files.length)handleResumeFile(e.dataTransfer.files[0])});
fileInput.addEventListener('change',()=>{if(fileInput.files.length)handleResumeFile(fileInput.files[0])});

async function handleResumeFile(file){
  const status=document.getElementById('resumeStatus'),err=document.getElementById('resumeError');
  err.textContent='';document.getElementById('resumeInfo').classList.add('hidden');
  const name=file.name.toLowerCase();
  status.textContent='Reading '+file.name+'…';
  try{
    let text='';
    if(name.endsWith('.docx')){
      const buf=await file.arrayBuffer();
      const res=await mammoth.extractRawText({arrayBuffer:buf});
      text=res.value||'';
    }else if(name.endsWith('.pdf')){
      pdfjsLib.GlobalWorkerOptions.workerSrc='https://unpkg.com/pdfjs-dist@3.11.174/build/pdf.worker.min.js';
      const buf=await file.arrayBuffer();
      const pdf=await pdfjsLib.getDocument({data:buf}).promise;
      const parts=[];
      for(let i=1;i<=Math.min(pdf.numPages,20);i++){
        const page=await pdf.getPage(i);
        const content=await page.getTextContent();
        parts.push(content.items.map(it=>it.str).join(' '));
      }
      text=parts.join('\n');
    }else{throw new Error('Please use a PDF or DOCX file.')}
    text=text.replace(/\s+\n/g,'\n').trim();
    if(text.length<100)throw new Error('Bum Bum could not read much text from that file. If it is a scanned image, export a real PDF or DOCX and try again.');
    resumeText=text;
    const found=matchSkills(text);
    resumeSkillCount=found.size;
    const words=text.split(/\s+/).length;
    status.textContent='';
    document.getElementById('resumeSummary').innerHTML='Got it: <b>'+esc(file.name)+'</b>. '+words.toLocaleString()+' words, <b>'+found.size+' recognizable skills</b> found.'+(found.size===0?' Bum Bum could not match any skills, so the gap report may be noisy.':'');
    document.getElementById('resumePreview').textContent=text.slice(0,4000);
    document.getElementById('resumeInfo').classList.remove('hidden');
    bbTrack('gap_resume_parsed',{skills:found.size});
    updateAnalyzeBtn();
  }catch(e){status.textContent='';err.textContent='Could not read that file: '+(e.message||e)}
}

/* ---------- job descriptions ---------- */
const jdList=document.getElementById('jdList');
let jdCounter=0;
const MAX_JDS=5;
function addJdCard(){
  if(jdList.children.length>=MAX_JDS)return;
  jdCounter++;
  const card=document.createElement('div');
  card.className='jd-card';
  card.innerHTML=
   '<div class="jd-head"><span class="jd-title">Job description '+jdCounter+'</span><span class="spacer"></span><button type="button" class="mini danger">Remove</button></div>'+
   '<div class="fetch-row"><input type="text" inputmode="url" placeholder="Paste a job posting link, then hit Fetch" aria-label="Job posting link"><button type="button" class="mini fetchBtn">Fetch</button></div>'+
   '<div class="or-row"><div class="line"></div><span>OR PASTE THE TEXT</span><div class="line"></div></div>'+
   '<textarea placeholder="Paste the full job description here…" aria-label="Job description text"></textarea>'+
   '<p class="fetchMsg" style="font-size:13px;font-weight:700;margin:6px 0 0"></p>';
  card.querySelector('.mini.danger').addEventListener('click',()=>{card.remove();updateAnalyzeBtn()});
  const urlInput=card.querySelector('input[type=text]'),ta=card.querySelector('textarea'),msg=card.querySelector('.fetchMsg');
  card.querySelector('.fetchBtn').addEventListener('click',async()=>{
    const url=urlInput.value.trim();
    if(!url){msg.innerHTML='<span class="error">Paste a link first.</span>';return}
    msg.textContent='Fetching…';
    try{
      const{response,data}=await apiCall('/api/tools/gap-scout-fetch.php',{url});
      if(!response.ok||!data.ok)throw new Error(data.error||'Fetch failed.');
      ta.value=data.text;
      if(data.title)card.querySelector('.jd-title').textContent=data.title;
      msg.innerHTML='<span style="color:#1d5c1d">Fetched '+data.text.length.toLocaleString()+' characters.</span>';
      bbTrack('gap_jd_fetched',{});
      updateAnalyzeBtn();
    }catch(e){msg.innerHTML='<span class="error">'+esc(e.message)+'</span>'}
  });
  ta.addEventListener('input',updateAnalyzeBtn);
  jdList.appendChild(card);
  updateAnalyzeBtn();
}
document.getElementById('addJdBtn').addEventListener('click',()=>{addJdCard()});
addJdCard();

function getJds(){
  return [...jdList.children].map(c=>({
    title:c.querySelector('.jd-title').textContent,
    text:c.querySelector('textarea').value.trim()
  })).filter(j=>j.text.length>=50);
}
function updateAnalyzeBtn(){
  const ok=resumeText.length>0&&getJds().length>0;
  document.getElementById('analyzeBtn').disabled=!ok;
}

/* ---------- analysis ---------- */
let lastGaps=[];
document.getElementById('analyzeBtn').addEventListener('click',()=>{
  const err=document.getElementById('analyzeError');err.textContent='';
  const jds=getJds();
  if(!resumeText||!jds.length){err.textContent='Add your resume and at least one job description first.';return}
  const resumeSkills=matchSkills(resumeText);
  const perJd=jds.map(j=>({title:j.title,text:j.text,skills:matchSkills(j.text)}));
  const union=new Map();
  perJd.forEach((j,ji)=>{
    j.skills.forEach((v,name)=>{
      if(!union.has(name))union.set(name,{cat:v.cat,jdIdxs:[],mentions:0});
      const u=union.get(name);u.jdIdxs.push(ji);u.mentions+=v.count;
    });
  });
  const missing=[],matched=[];
  union.forEach((u,name)=>{
    const entry={name,cat:u.cat,jdCount:u.jdIdxs.length,mentions:u.mentions,evidence:evidenceFor(perJd[u.jdIdxs[0]].text,name)};
    (resumeSkills.has(name)?matched:missing).push(entry);
  });
  const byRank=(a,b)=>b.jdCount-a.jdCount||b.mentions-a.mentions;
  missing.sort(byRank);matched.sort(byRank);
  lastGaps=missing;

  document.getElementById('statJds').textContent=jds.length;
  document.getElementById('statMissing').textContent=missing.length;
  document.getElementById('statMatched').textContent=matched.length;

  const gapList=document.getElementById('gapList');gapList.innerHTML='';
  document.getElementById('noGaps').classList.toggle('hidden',missing.length>0);
  missing.forEach(g=>{
    const d=document.createElement('div');d.className='gap-card';
    d.innerHTML='<div class="top"><span class="skill">'+esc(g.name)+'</span><span class="catchip" style="background:'+(CATS[g.cat]||'#666')+'">'+esc(g.cat)+'</span></div>'+
     '<div class="freq">Asked for in <b>'+g.jdCount+' of '+jds.length+' posting'+(jds.length>1?'s':'')+'</b> · '+g.mentions+' mention'+(g.mentions>1?'s':'')+'</div>'+
     (g.evidence?'<p class="evidence">&ldquo;'+esc(g.evidence)+'&rdquo;</p>':'');
    gapList.appendChild(d);
  });

  const mc=document.getElementById('matchChips');mc.innerHTML='';
  matched.forEach(m=>{
    const s=document.createElement('span');s.className='match-chip';
    s.textContent=m.name+' ('+m.jdCount+'/'+jds.length+')';
    mc.appendChild(s);
  });
  if(!matched.length)mc.innerHTML='<span style="opacity:.7;font-size:14px">None yet. That is what the gaps above are for.</span>';

  const bd=document.getElementById('jdBreakdown');bd.innerHTML='';
  perJd.forEach(j=>{
    const jm=[...j.skills.keys()].filter(n=>!resumeSkills.has(n));
    const jh=[...j.skills.keys()].filter(n=>resumeSkills.has(n));
    const det=document.createElement('details');det.className='jd-break';
    det.innerHTML='<summary>'+esc(j.title)+' — '+jm.length+' missing, '+jh.length+' matched</summary>'+
     '<div style="margin:6px 0 2px;font-weight:800;font-size:13px;color:#8c1f1f">Missing</div><div class="match-chips" style="margin-top:0">'+(jm.map(n=>'<span class="miss-chip">'+esc(n)+'</span>').join('')||'<span style="opacity:.7;font-size:13px">none</span>')+'</div>'+
     '<div style="margin:10px 0 2px;font-weight:800;font-size:13px;color:#1d5c1d">Matched</div><div class="match-chips" style="margin-top:0">'+(jh.map(n=>'<span class="match-chip">'+esc(n)+'</span>').join('')||'<span style="opacity:.7;font-size:13px">none</span>')+'</div>';
    bd.appendChild(det);
  });

  document.getElementById('results').classList.remove('hidden');
  document.getElementById('copyBtn').classList.remove('hidden');
  document.getElementById('resetBtn').classList.remove('hidden');
  document.getElementById('results').scrollIntoView({behavior:'smooth',block:'start'});
  bbTrack('gap_analyzed',{jds:jds.length,missing:missing.length,matched:matched.length});
});

document.getElementById('copyBtn').addEventListener('click',async()=>{
  const lines=lastGaps.map(g=>'- '+g.name+' (asked for in '+g.jdCount+' posting'+(g.jdCount>1?'s':'')+')');
  const txt='Skills missing from my resume:\n'+lines.join('\n');
  try{await navigator.clipboard.writeText(txt);document.getElementById('copyBtn').textContent='Copied!';setTimeout(()=>document.getElementById('copyBtn').textContent='Copy missing skills',1800)}
  catch(e){
    const ta=document.createElement('textarea');ta.value=txt;document.body.appendChild(ta);ta.select();
    try{document.execCommand('copy')}catch(_){}
    ta.remove();document.getElementById('copyBtn').textContent='Copied!';
    setTimeout(()=>document.getElementById('copyBtn').textContent='Copy missing skills',1800);
  }
  bbTrack('gap_copied',{count:lastGaps.length});
});

document.getElementById('resetBtn').addEventListener('click',()=>{
  resumeText='';fileInput.value='';
  document.getElementById('resumeInfo').classList.add('hidden');
  document.getElementById('resumeStatus').textContent='';
  jdList.innerHTML='';jdCounter=0;addJdCard();
  document.getElementById('results').classList.add('hidden');
  document.getElementById('copyBtn').classList.add('hidden');
  document.getElementById('resetBtn').classList.add('hidden');
  updateAnalyzeBtn();
  window.scrollTo({top:0,behavior:'smooth'});
});
</script></main></body></html>
