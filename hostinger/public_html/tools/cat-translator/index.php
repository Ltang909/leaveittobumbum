<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Cat Translator | Leave It to Bum Bum</title>
<meta name="description" content="Point your mic at your cat. Bum Bum listens to the meows and translates them in real time. Free, and your cat's audio never leaves your browser.">
<meta property="og:title" content="Cat Translator | Leave It to Bum Bum">
<meta property="og:description" content="Point your mic at your cat. Bum Bum listens to the meows and translates them in real time.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/cat-translator/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og-cat-translator.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Cat Translator | Leave It to Bum Bum">
<meta name="twitter:description" content="Point your mic at your cat. Bum Bum listens to the meows and translates them in real time.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og-cat-translator.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/cat-translator/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Bum Bum Cat Translator",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" },
  "description": "Point your mic at your cat. Bum Bum listens to the meows and translates them in real time. Free, and audio never leaves your browser.",
  "url": "https://leaveittobumbum.com/tools/cat-translator/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    { "@type": "Question", "name": "Is this real translation?", "acceptedAnswer": { "@type": "Answer", "text": "It is mood detection in a translator costume. Bum Bum analyzes pitch, duration, repetition, and noisiness to classify what your cat is probably feeling, then translates with total confidence and questionable accuracy." } },
    { "@type": "Question", "name": "Does my cat's audio leave my browser?", "acceptedAnswer": { "@type": "Answer", "text": "No. Everything happens on your device. Bum Bum hears it, translates it, and forgets it immediately, like everything else you say." } },
    { "@type": "Question", "name": "Why is Bum Bum so rude?", "acceptedAnswer": { "@type": "Answer", "text": "He is not rude. He is honest. There is a difference, and he will explain it at length." } },
    { "@type": "Question", "name": "Does it work on dogs?", "acceptedAnswer": { "@type": "Answer", "text": "No. Dogs already understand English perfectly. They simply choose not to dignify your questions with a response." } },
    { "@type": "Question", "name": "Is it free?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Translating cats is free and unlimited. Bum Bum considers it a public service." } }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Cat Translator | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Cat Translator","item":"https://leaveittobumbum.com/tools/cat-translator/"}]}</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem)}
.ct-layout{display:grid;gap:16px;margin-top:20px}
.seg{display:inline-flex;border:2px solid var(--ink);border-radius:12px;overflow:hidden;background:#fff}
.seg button{border:0;background:transparent;margin:0;padding:12px 20px;font:inherit;font-weight:800;cursor:pointer;color:var(--ink)}
.seg button.on{background:var(--ink);color:#fff}
.panel-card{background:#fff;border:2px solid var(--ink);border-radius:14px;padding:20px;box-shadow:4px 4px 0 var(--ink)}
.mic-wrap{display:flex;flex-direction:column;align-items:center;gap:14px;padding:8px 0}
#micBtn{width:110px;height:110px;border-radius:50%;border:3px solid var(--ink);background:#fdf0d5;font-size:44px;cursor:pointer;box-shadow:4px 4px 0 var(--ink);transition:transform .1s}
#micBtn:active{transform:scale(.94)}
#micBtn.live{background:#e2f2e6;animation:pulse 1.6s infinite}
@keyframes pulse{0%,100%{box-shadow:4px 4px 0 var(--ink)}50%{box-shadow:4px 4px 0 #1d7a3a}}
.meter{width:min(420px,100%);height:14px;border:2px solid var(--ink);border-radius:8px;background:#faf8f2;overflow:hidden}
#meterFill{height:100%;width:0%;background:#1d7a3a;transition:width .08s}
#listenStatus{font-weight:800;min-height:1.6em;text-align:center}
.translation{display:none;margin-top:6px;border:2px dashed var(--ink);border-radius:12px;padding:16px;background:#fdf0d5}
.translation.show{display:block;animation:pop .25s}
@keyframes pop{from{transform:scale(.97);opacity:.4}to{transform:scale(1);opacity:1}}
.translation .who{display:flex;align-items:center;gap:10px;font-weight:900;margin-bottom:6px}
.translation .who .avatar{width:40px;height:40px;border-radius:50%;background:#1d7a3a;color:#fff;display:flex;align-items:center;justify-content:center;font-size:22px;flex:none}
.translation .said{font-family:Fraunces,Georgia,serif;font-size:1.35rem;line-height:1.3;margin:0 0 8px}
.translation .meta{font-size:12px;opacity:.65;margin:0 0 10px}
.translation .row{display:flex;gap:8px;flex-wrap:wrap}
.chips{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px}
.chip{border:2px solid var(--ink);border-radius:12px;background:#fff;padding:12px 10px;font:inherit;font-weight:800;cursor:pointer;text-align:center;box-shadow:3px 3px 0 var(--ink)}
.chip:active{transform:translate(2px,2px);box-shadow:1px 1px 0 var(--ink)}
.chip small{display:block;font-weight:400;font-size:12px;opacity:.65;margin-top:4px}
.log{margin-top:14px;display:grid;gap:8px}
.log-item{background:#faf8f2;border:1px solid var(--line);border-radius:10px;padding:10px 12px;font-size:14px}
.log-item b{font-weight:900}
.log-item .t{opacity:.55;font-size:12px}
.iconbtn{border:2px solid var(--ink);border-radius:12px;background:#fff;margin:0;padding:0 16px;font:inherit;font-weight:800;cursor:pointer;min-height:44px;display:inline-flex;align-items:center;color:var(--ink)}
.hint{font-size:13px;opacity:.7;margin:0;text-align:center;max-width:52ch}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Cat Translator"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/cat-paws-up.png" alt="Bum Bum ready to translate"><h1>Your cat has been talking.<br>Now you'll know what about.</h1><p class="lede">Point your mic at your cat. Bum Bum listens to the meows, reads the mood in real time, and translates with total confidence. Free, unlimited, and your cat's audio never leaves your browser.</p>
<div class="ct-layout">
<div><div class="seg" role="group" aria-label="Translator mode"><button type="button" id="tabListen" class="on">🎙️ Listen</button><button type="button" id="tabBody">🐈 Body language</button></div></div>

<section id="panel-listen" class="panel-card" aria-label="Listen to your cat">
<div class="mic-wrap">
<button type="button" id="micBtn" aria-label="Start listening">🎙️</button>
<div class="meter" aria-hidden="true"><div id="meterFill"></div></div>
<p id="listenStatus">Tap the mic and let your cat speak.</p>
<p class="hint">Best with the mic close to the cat and the room reasonably quiet. Bum Bum works with meows, chirps, purrs, yowls, and hisses. Silence is also data.</p>
</div>
<div class="translation" id="liveTranslation">
<div class="who"><span class="avatar">🐱</span><span>Bum Bum translates</span></div>
<p class="said" id="liveSaid"></p>
<p class="meta" id="liveMeta"></p>
<div class="row">
<button type="button" class="iconbtn" id="copyBtn">Copy translation</button>
<button type="button" class="iconbtn" id="shareBtn">Share</button>
</div>
</div>
<div class="log" id="sessionLog" aria-live="polite"></div>
</section>

<section id="panel-body" class="panel-card" hidden aria-label="Body language decoder">
<p style="margin-top:0;font-weight:800">No mic? No cat handy? Tap what you see. Bum Bum decodes the body language.</p>
<div class="chips" id="bodyChips"></div>
<div class="translation" id="bodyTranslation" style="margin-top:14px">
<div class="who"><span class="avatar">🐱</span><span>Bum Bum decodes</span></div>
<p class="said" id="bodySaid"></p>
<p class="meta" id="bodyMeta"></p>
<div class="row">
<button type="button" class="iconbtn" id="copyBtn2">Copy translation</button>
<button type="button" class="iconbtn" id="shareBtn2">Share</button>
</div>
</div>
</section>
</div>

<section id="faq" class="panel" aria-label="Questions" style="margin-top:28px">
<h2 style="margin-top:0">Questions</h2>
<details><summary>Is this real translation?</summary><p>It is mood detection in a translator costume. Bum Bum analyzes pitch, duration, repetition, and noisiness to classify what your cat is probably feeling, then translates with total confidence and questionable accuracy.</p></details>
<details><summary>Does my cat's audio leave my browser?</summary><p>No. Everything happens on your device. Bum Bum hears it, translates it, and forgets it immediately, like everything else you say.</p></details>
<details><summary>Why is Bum Bum so rude?</summary><p>He is not rude. He is honest. There is a difference, and he will explain it at length.</p></details>
<details><summary>Does it work on dogs?</summary><p>No. Dogs already understand English perfectly. They simply choose not to dignify your questions with a response.</p></details>
<details><summary>Is it free?</summary><p>Yes. Translating cats is free and unlimited. Bum Bum considers it a public service.</p></details>
</section>
<section class="panel" aria-label="More tiny tools">
<h2 style="margin-top:0">More tiny tools</h2>
<p><a href="/tools/notes/">Bum Bum Notes</a> - Think out loud, get a transcript.</p>
<p><a href="/tools/ghostwriter/">Ghostwriter</a> - Ramble for a minute, get three hooks, a 60-second script, and a caption ready to post.</p>
<p><a href="/tools/purr-code/">Purr Code</a> - Your link, but cute.</p>
</section>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?>
<script>
(function(){
"use strict";
/* ---------- tabs ---------- */
var tabListen=document.getElementById('tabListen'),tabBody=document.getElementById('tabBody'),
    panelListen=document.getElementById('panel-listen'),panelBody=document.getElementById('panel-body');
function showTab(which){
  var listen=which==='listen';
  tabListen.classList.toggle('on',listen);tabBody.classList.toggle('on',!listen);
  panelListen.hidden=!listen;panelBody.hidden=listen;
}
tabListen.addEventListener('click',function(){showTab('listen')});
tabBody.addEventListener('click',function(){showTab('body')});

/* ---------- translations: Bum Bum's voice ---------- */
var SAY={
  demanding:{label:"Demanding",lines:[
    "Feed me. This isn't a request, I've already filed the paperwork.",
    "The bowl situation has become unacceptable and I need you to fix it immediately.",
    "I've asked three times. I'm now addressing the void. The void is you.",
    "Dinner is 40 minutes late. I am documenting everything."]},
  greeting:{label:"Greeting",lines:[
    "Oh. You're home. Took you long enough.",
    "I acknowledge your existence. Briefly.",
    "Welcome back. I kept your spot warm. You're welcome."]},
  chirp:{label:"Excited",lines:[
    "Prey detected! Unfortunately it's behind glass. Useless.",
    "Something moved and I am READY. For what, unclear. But ready.",
    "Did you see that?! No? Then it never happened. Forget it."]},
  purr:{label:"Content",lines:[
    "I am tolerating this. Continue.",
    "Current satisfaction levels: acceptable. Do not stop.",
    "This is fine. Everything is fine. Keep doing exactly this."]},
  yowl:{label:"Distressed",lines:[
    "Something is wrong and I have decided it is your fault.",
    "I am announcing my displeasure to the entire household.",
    "This is not a drill. I repeat: not a drill."]},
  hiss:{label:"Back off",lines:[
    "Absolutely not. Leave.",
    "That was the wrong answer. Try silence.",
    "I have set a boundary. Respect the boundary."]},
  mystery:{label:"Unclear",lines:[
    "Even I don't know what that was. Let's never speak of it.",
    "The translation module crashed. The cat did not.",
    "Inconclusive. The cat declines to elaborate."]}
};
var BODY={
  "Tail straight up|the universal sign of a friendly cat":"I'm feeling friendly. Don't ruin it.",
  "Tail puffed to twice its size|fear mode engaged":"I am now twice my normal size and twice as afraid. Act accordingly.",
  "Slow blink|the highest honor":"I trust you. This is the highest honor I bestow. Do not move.",
  "Ears flat back|concerns have been raised":"I have concerns about your recent behavior.",
  "Kneading paws|making biscuits":"I am making biscuits. You are the furniture. Stay still.",
  "Belly on display|do not touch":"This is a trap. Admire from a safe distance.",
  "Headbutt|you've been claimed":"You are mine now. Congratulations on the promotion.",
  "Zoomies at 3am|the hallway is a racetrack":"The hallway is a racetrack and rent is due. In speed.",
  "Staring into your soul|reading your thoughts":"I am reading your thoughts. They are disappointing.",
  "Sitting on your stuff|property law":"If I sit on it, it's mine. Those are the rules. I wrote them."
};
function pick(a){return a[Math.floor(Math.random()*a.length)]}
function confidence(){return 88+Math.floor(Math.random()*12)}

/* ---------- audio engine ---------- */
var micBtn=document.getElementById('micBtn'),meterFill=document.getElementById('meterFill'),
    listenStatus=document.getElementById('listenStatus'),
    liveBox=document.getElementById('liveTranslation'),liveSaid=document.getElementById('liveSaid'),
    liveMeta=document.getElementById('liveMeta'),sessionLog=document.getElementById('sessionLog');
var audioCtx=null,analyser=null,micStream=null,rafId=null,listening=false;
var noiseFloor=0.02,calibFrames=0,calibSum=0;
var curEvent=null,lastEnd=0,recentEnds=[];
var FFT=2048;

function frameFeatures(td,fd,sampleRate){
  var n=td.length,sum=0,zc=0,i;
  for(i=0;i<n;i++){sum+=td[i]*td[i];if(i>0&&((td[i]>=0)!==(td[i-1]>=0)))zc++;}
  var rms=Math.sqrt(sum/n),zcr=zc/n;
  var binCount=fd.length,peak=-Infinity,peakBin=0,magSum=0,wSum=0;
  for(i=1;i<binCount;i++){
    var mag=fd[i]; // dB, -100..0
    if(mag>peak){peak=mag;peakBin=i;}
    var lin=Math.pow(10,mag/20);
    magSum+=lin;wSum+=lin*i;
  }
  var domFreq=peakBin*sampleRate/FFT;
  var centroid=magSum>0?(wSum/magSum)*sampleRate/FFT:0;
  return {rms:rms,zcr:zcr,domFreq:domFreq,centroid:centroid};
}
function classify(ev){
  // ev: {dur, meanFreq, freqVar, meanZcr, meanRms, rep}
  if(ev.dur>1.4&&ev.meanFreq<420)return 'purr';
  if(ev.meanZcr>0.32&&ev.dur<0.9)return 'hiss';
  if(ev.dur>1.0&&ev.meanRms>0.10&&ev.meanFreq<700)return 'yowl';
  if(ev.rep>=2)return 'demanding';
  if(ev.dur<0.45)return ev.freqVar>90000?'chirp':'greeting';
  if(ev.meanFreq>750&&ev.freqVar>60000)return 'chirp';
  if(ev.dur<0.9&&ev.meanFreq>500)return 'demanding';
  if(ev.dur<0.9)return 'greeting';
  return 'mystery';
}
function endEvent(now){
  if(!curEvent||curEvent.frames.length<3){curEvent=null;return;}
  var fr=curEvent.frames,n=fr.length;
  var dur=(curEvent.end-curEvent.start)/1000;
  var mf=0,mz=0,mr=0,fmin=Infinity,fmax=0,i;
  for(i=0;i<n;i++){mf+=fr[i].domFreq;mz+=fr[i].zcr;mr+=fr[i].rms;
    if(fr[i].domFreq>fmax)fmax=fr[i].domFreq;if(fr[i].domFreq<fmin)fmin=fr[i].domFreq;}
  mf/=n;mz/=n;mr/=n;
  var fv=0;for(i=0;i<n;i++){fv+=(fr[i].domFreq-mf)*(fr[i].domFreq-mf);}fv/=n;
  while(recentEnds.length&&now-recentEnds[0]>3000)recentEnds.shift();
  var rep=recentEnds.length+1;
  var cat=classify({dur:dur,meanFreq:mf,freqVar:fv,meanZcr:mz,meanRms:mr,rep:rep});
  recentEnds.push(now);
  curEvent=null;
  showTranslation(cat);
}
function trackEvent(f,now){
  var thresh=Math.max(noiseFloor*3.2,0.025);
  if(f.rms>thresh){
    if(!curEvent)curEvent={start:now,frames:[],end:now};
    curEvent.frames.push(f);curEvent.end=now;lastEnd=now;
  }else if(curEvent){
    if(now-lastEnd>280)endEvent(now);
  }
}
function loop(){
  if(!listening)return;
  var td=new Float32Array(analyser.fftSize);
  analyser.getFloatTimeDomainData(td);
  var fd=new Float32Array(analyser.frequencyBinCount);
  analyser.getFloatFrequencyData(fd);
  var f=frameFeatures(td,fd,audioCtx.sampleRate);
  var now=performance.now();
  if(calibFrames<40){calibSum+=f.rms;calibFrames++;
    if(calibFrames===40)noiseFloor=Math.max(0.008,calibSum/40);
  }else{
    trackEvent(f,now);
  }
  meterFill.style.width=Math.min(100,Math.round(f.rms*900))+'%';
  rafId=requestAnimationFrame(loop);
}
function showTranslation(cat){
  var s=SAY[cat]||SAY.mystery,line=pick(s.lines),conf=confidence();
  liveSaid.textContent='\u201C'+line+'\u201D';
  liveMeta.textContent='Detected: '+s.label+' \u00B7 Bum Bum is '+conf+'% confident \u00B7 '+new Date().toLocaleTimeString();
  liveBox.classList.remove('show');void liveBox.offsetWidth;liveBox.classList.add('show');
  var item=document.createElement('div');item.className='log-item';
  var t=document.createElement('span');t.className='t';t.textContent=new Date().toLocaleTimeString()+' \u00B7 '+s.label+' \u00B7 ';
  var b=document.createElement('b');b.textContent=line;
  item.appendChild(t);item.appendChild(b);
  sessionLog.prepend(item);
  while(sessionLog.children.length>8)sessionLog.removeChild(sessionLog.lastChild);
}
async function startListening(){
  try{
    if(!navigator.mediaDevices||!navigator.mediaDevices.getUserMedia)throw new Error('no-mic-api');
    micStream=await navigator.mediaDevices.getUserMedia({audio:{echoCancellation:true,noiseSuppression:true}});
  }catch(e){
    listenStatus.textContent='No mic access. Bum Bum cannot hear your cat. The body language decoder below works mic-free.';
    showTab('body');
    return;
  }
  try{
    audioCtx=audioCtx||new (window.AudioContext||window.webkitAudioContext)();
    if(audioCtx.state==='suspended')await audioCtx.resume();
  }catch(e){}
  var src=audioCtx.createMediaStreamSource(micStream);
  analyser=audioCtx.createAnalyser();analyser.fftSize=FFT;analyser.smoothingTimeConstant=0.4;
  src.connect(analyser);
  listening=true;calibFrames=0;calibSum=0;curEvent=null;recentEnds=[];
  micBtn.classList.add('live');micBtn.textContent='🛑';
  listenStatus.textContent='Listening\u2026 let your cat speak. Bum Bum is analyzing.';
  loop();
}
function stopListening(){
  listening=false;
  if(rafId)cancelAnimationFrame(rafId);
  if(micStream)micStream.getTracks().forEach(function(t){t.stop();});
  micBtn.classList.remove('live');micBtn.textContent='🎙️';
  meterFill.style.width='0%';
  if(curEvent)endEvent(performance.now());
  listenStatus.textContent='Paused. Tap the mic to keep listening.';
}
micBtn.addEventListener('click',function(){listening?stopListening():startListening();});

/* ---------- body language decoder ---------- */
var chipsEl=document.getElementById('bodyChips'),
    bodyBox=document.getElementById('bodyTranslation'),
    bodySaid=document.getElementById('bodySaid'),bodyMeta=document.getElementById('bodyMeta');
Object.keys(BODY).forEach(function(k){
  var parts=k.split('|');
  var b=document.createElement('button');b.type='button';b.className='chip';
  b.innerHTML='';var s=document.createElement('span');s.textContent=parts[0];
  var sm=document.createElement('small');sm.textContent=parts[1];
  b.appendChild(s);b.appendChild(sm);
  b.addEventListener('click',function(){
    bodySaid.textContent='\u201C'+BODY[k]+'\u201D';
    bodyMeta.textContent='Decoded: '+parts[0]+' \u00B7 Bum Bum is '+confidence()+'% confident';
    bodyBox.classList.remove('show');void bodyBox.offsetWidth;bodyBox.classList.add('show');
  });
  chipsEl.appendChild(b);
});

/* ---------- share / copy ---------- */
function currentText(box,said){return said.textContent.replace(/^[\u201C]/,'').replace(/[\u201D]$/,'');}
function wireCopy(btnId,saidEl){
  document.getElementById(btnId).addEventListener('click',function(){
    var t='Bum Bum translates: "'+currentText(null,saidEl)+'"';
    if(navigator.clipboard&&navigator.clipboard.writeText){
      navigator.clipboard.writeText(t).then(function(){flash(btnId,'Copied!');},function(){fallbackCopy(t,btnId);});
    }else fallbackCopy(t,btnId);
  });
}
function fallbackCopy(t,btnId){
  var ta=document.createElement('textarea');ta.value=t;document.body.appendChild(ta);ta.select();
  try{document.execCommand('copy');flash(btnId,'Copied!');}catch(e){flash(btnId,'Copy failed');}
  document.body.removeChild(ta);
}
function flash(btnId,msg){
  var b=document.getElementById(btnId),old=b.textContent;b.textContent=msg;
  setTimeout(function(){b.textContent=old;},1400);
}
function wireShare(btnId,saidEl){
  document.getElementById(btnId).addEventListener('click',function(){
    var t='Bum Bum translates: "'+currentText(null,saidEl)+'"';
    if(navigator.share){navigator.share({title:'Bum Bum Cat Translator',text:t}).catch(function(){});}
    else fallbackCopy(t,btnId);
  });
}
wireCopy('copyBtn',liveSaid);wireShare('shareBtn',liveSaid);
wireCopy('copyBtn2',bodySaid);wireShare('shareBtn2',bodySaid);
})();
</script>
</body></html>
