<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<?php
$gcalReady = false; $gcalConn = null; $gcalNote = (string) ($_GET['gcal'] ?? '');
if ($user) {
    require_once dirname(__DIR__, 2) . '/includes/google-calendar.php';
    try {
        $gcalReady = gcal_configured();
        if ($gcalReady) $gcalConn = gcal_connection(db(), (int) $user['id']);
    } catch (Throwable $e) { $gcalReady = false; }
}
?>
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
      "acceptedAnswer": { "@type": "Answer", "text": "Recordings are transcribed and then discarded. Only the text of your log is stored, in your account." }
    },
    {
      "@type": "Question",
      "name": "What lands in the email?",
      "acceptedAnswer": { "@type": "Answer", "text": "A recap every morning: your most recent log in clean jot notes, yesterday's list scored, and your next top priorities. Miss a day and you get one gentle nudge; after that, no emails until you log again." }
    },
    {
      "@type": "Question",
      "name": "What if I skip a day?",
      "acceptedAnswer": { "@type": "Answer", "text": "Nothing breaks. The scorecard compares against your most recent filed day, whenever that was." }
    },
    {
      "@type": "Question",
      "name": "How does the scorecard work?",
      "acceptedAnswer": { "@type": "Answer", "text": "When you file today, Bum Bum takes the tomorrow list from your most recent filed day and checks each item against what you said in today's log: done, partial, or not mentioned. It only ever uses your own words, and it never marks something done from the plan alone." }
    },
    {
      "@type": "Question",
      "name": "Can I type instead of talking?",
      "acceptedAnswer": { "@type": "Answer", "text": "Yes. The one big box takes typing too. Talk, type, or mix both in the same log." }
    },
    {
      "@type": "Question",
      "name": "Can I fix a day after filing it?",
      "acceptedAnswer": { "@type": "Answer", "text": "Yes. Open the day in Recent logs to edit any jot note, flip a scorecard mark, or delete the day entirely; editing and deleting are free. Filing again the same day also replaces the earlier version and does not cost another action." }
    },
    {
      "@type": "Question",
      "name": "Can Day Log copy my logs to Notion?",
      "acceptedAnswer": { "@type": "Answer", "text": "Yes. In Advanced settings, connect a Notion page or database with your own integration token and each filed day becomes its own Notion page of jot notes, titled with the date. Re-filing a day rewrites its page." }
    },
    {
      "@type": "Question",
      "name": "Can I capture a learning before I file?",
      "acceptedAnswer": { "@type": "Answer", "text": "Yes. Jot learnings into Day Log as they happen during the day, free and as often as you like. When you file that evening, they are appended to the day's learnings in your own words. Jot one after filing and it still joins the day's record." }
    }
  ]
}
</script>
<link rel="stylesheet" href="/app.css?v=9"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
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
.editor h4{margin:14px 0 4px;font-size:15px}
.edit-row{display:flex;gap:6px;align-items:center;margin:5px 0}
.edit-row input[type=text]{flex:1;padding:8px 10px;border:1px solid #d9c9a3;border-radius:8px;font:inherit;font-size:14.5px;background:#fff;min-width:0}
.edit-row select{padding:8px;border:1px solid #d9c9a3;border-radius:8px;font:inherit;font-size:13.5px;background:#fff}
.edit-row .x{border:1px solid #d9c9a3;background:#fff;border-radius:8px;width:32px;height:32px;cursor:pointer;font-weight:900;flex:none;display:flex;align-items:center;justify-content:center;padding:0;line-height:1;font-size:15px}
.mini-btn{border:1px solid #d9c9a3;background:#fff;border-radius:8px;padding:6px 10px;cursor:pointer;font:inherit;font-size:13.5px;font-weight:700}
.edit-actions{display:flex;gap:8px;margin-top:12px;flex-wrap:wrap;align-items:center}
.del-log{color:#a33327}
.del-log.armed{background:#a33327;color:#fff;border-color:#a33327}
.filed-head{display:flex;justify-content:space-between;align-items:center;gap:10px}
.wave-strip{display:flex;align-items:center;gap:12px;margin-top:10px;padding:14px;border:1px dashed #d9c9a3;border-radius:12px;background:#fffdf6;font-weight:600}
.wave-strip.big{padding:30px 14px;justify-content:center;font-size:16px}
.wave-bars{display:flex;align-items:center;gap:4px;height:34px}
.wave-bars span{display:block;width:6px;height:10px;border-radius:3px;background:#b3541e}
.wave-strip.wait{min-height:170px;align-items:flex-start}
.wave-strip.anim .wave-bars span{animation:wavePulse 1s ease-in-out infinite}
.wave-strip.anim .wave-bars span:nth-child(2){animation-delay:.1s}.wave-strip.anim .wave-bars span:nth-child(3){animation-delay:.22s}.wave-strip.anim .wave-bars span:nth-child(4){animation-delay:.34s}.wave-strip.anim .wave-bars span:nth-child(5){animation-delay:.46s}.wave-strip.anim .wave-bars span:nth-child(6){animation-delay:.58s}.wave-strip.anim .wave-bars span:nth-child(7){animation-delay:.7s}
@keyframes wavePulse{0%,100%{height:8px}50%{height:30px}}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
.dl-tabs{display:flex;border-bottom:2px solid #e2d7bf}
.dl-tab{flex:1;text-align:center;border:2px solid transparent;border-bottom:none;background:transparent;font:inherit;font-weight:800;padding:11px 10px;border-radius:14px 14px 0 0;cursor:pointer;color:#6b6350;margin-bottom:-2px}
.dl-tab.active{background:#f8f2df;border-color:#e2d7bf;color:#142015}
.tab-body{background:#f8f2df;border:2px solid #e2d7bf;border-top:none;border-radius:0 0 14px 14px;padding:4px 18px 18px}
.rec-zone{text-align:center;padding:20px 12px 4px}
.rec-zone .wave-strip{margin-bottom:14px;text-align:left}
.mic-round{width:78px;height:78px;border-radius:50%;border:2px solid #1a291c;background:#d8ff62;font-size:30px;cursor:pointer;box-shadow:0 3px 0 #2f2a22;line-height:1}
.mic-round.recording{background:#ff7448;font-size:15px;font-weight:900}
.rec-label{font-weight:800;margin:10px 0 2px}
.rec-or{opacity:.6;margin:2px 0 8px;font-size:13.5px}
.yday{background:#fffdf6;border:1px solid #eee3cb;border-radius:10px;padding:10px 14px;font-size:14.5px;margin:14px 0 0}
.yday ul{margin:6px 0;padding-left:20px}
.yday .hint{font-size:13px;opacity:.75;margin:6px 0 0}
.prompt-chips{display:flex;gap:10px;flex-wrap:wrap;justify-content:center;margin:16px 0 4px}
.chip{background:#fffdf6;border:1px solid #e2d7bf;border-radius:10px;padding:9px 14px;font-weight:700;font-size:14.5px}
.dl-file{width:100%;padding:14px;font-size:16.5px;margin-top:12px}
.log-cards{display:flex;gap:12px;overflow-x:auto;padding:4px 2px 10px;scroll-snap-type:x proximity}
.log-cards .log-card{flex:0 0 232px;scroll-snap-align:start}
.log-card{text-align:left;background:#fff;border:2px solid #e2d7bf;border-radius:14px;padding:14px;cursor:pointer;font:inherit;display:flex;flex-direction:column;gap:5px}
.log-card:hover{border-color:#b3a37e}
.log-card.active{border-color:#1a291c;box-shadow:3px 3px 0 var(--line)}
.log-card.latest{background:#eef7dc;border-color:#9db87a}
.log-card-date{font-weight:900;font-size:15.5px}
.log-card-snip{font-size:14px;opacity:.85}
.log-card-meta{font-size:12.5px;font-weight:800;opacity:.65}
#historyDetail{margin-top:14px}
.jot-form{border-top:1px solid #eee3cb;margin-top:16px;padding-top:14px}
.jot-form h3{margin:0 0 4px;font-size:15.5px}
.jot-form .hint{font-size:13.5px;opacity:.75;margin:0 0 10px}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Day Log"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><img class="tool-mascot-page" src="/bum/icons/day-log-sticker.png" alt="Day Log"><h1>Day Log</h1><p class="lede bb-desc">5 min/day. Increase your productivity. You're busy out there working on your business. Sometimes things get forgotten or deprioritized; when you look back, you forget what you did the past week. Spend 5 min every evening with Bum Bum, brain dump what you did, challenges faced, lessons learned and what to achieve. Bum bum reads what you've logged and sends you a daily email keeping you on track! He also summarizes highlights and growth on a weekly and monthly basis.</p>
<?php if (!$user): ?>
<section class="panel"><h2>Day Log lives in your account</h2><p class="lede">The scorecard only works if yesterday's list is waiting for you, so Day Log needs a free account to file into. Your logs are your words, and nobody else sees them.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/day-log/') ?>">Sign in or create a free account</a></p></section>
<?php else: ?>

<section class="panel" id="logPanel">
<div class="dl-tabs">
<button type="button" class="dl-tab active" id="tabTalk">Talk through your day</button>
<button type="button" class="dl-tab" id="tabJot">Note a mid day learning</button>
</div>
<div class="tab-body">
<div id="panelTalk">
<div class="rec-zone">
<div id="waveStrip" class="wave-strip hidden"><div class="wave-bars"><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div><span id="waveLabel"></span></div>
<button type="button" class="mic-round" id="micBtn" aria-label="Record">&#127908;</button>
<p class="rec-label" id="recLabel">Tap to start recording</p>
<p class="rec-or">or</p>
<button type="button" class="mini-btn" id="typeBtn">Type instead</button>
<p class="mic-status" id="micStatus"></p>
</div>
<div id="typeZone" class="hidden">
<textarea id="logText" placeholder="Talk through your day, or type it here..." style="width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;resize:vertical;min-height:170px"></textarea>
</div>
<div id="yesterdaySlot"></div>
<div class="prompt-chips">
<span class="chip">&#127919; What did you do today?</span>
<span class="chip">&#9889; What challenged you?</span>
<span class="chip">&#128197; What are you committing to tomorrow?</span>
</div>
<button type="button" class="button dl-file" id="fileBtn" disabled style="opacity:.45">File my day</button>
<p id="fileStatus" class="mic-status" style="margin-top:8px"></p>
<p id="batchError" class="error"></p>
</div>
<div id="panelJot" class="hidden">
<div class="jot-form" style="border-top:0;margin-top:2px;padding-top:0">
<h3 style="font-size:21px;margin:8px 0 10px">Learnings as they land</h3>
<p class="hint" style="line-height:1.6;margin:0 0 18px">A lesson landed mid-day? Jot it here while it is fresh. When you file tonight, it lands in the day's Learnings in your own words. Jotting is free, and one jotted after filing still joins today's record.</p>
<textarea id="jotText" maxlength="1000" rows="5" placeholder="What did you just learn?" style="width:100%;padding:14px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;resize:vertical;line-height:1.55"></textarea>
<div style="margin-top:16px"><button type="button" class="button secondary" id="jotBtn" style="padding:11px 26px">Jot it down</button></div>
<p id="jotStatus" class="mic-status" style="margin-top:12px"></p>
<p id="jotError" class="error"></p>
</div>
</div>
</div>
</section>


<section class="panel hidden" id="resultPanel">
<div class="filed-head"><h2 style="margin:0">Your day, summarized</h2><button type="button" class="mini-btn" id="filedToggle">Show</button></div>
<div id="resultCollapse" class="hidden">
<div class="jot" id="resultBody" style="margin-top:12px"></div>
<p class="read-note" id="emailNote"></p>
<p class="read-note">Want to fix a line? This day lives in Recent logs below, where you can edit or delete it.</p>
</div>
</section>

<section class="panel" id="historyPanel">
<h2>Recent logs</h2>
<div id="historyList"><p class="meta">Loading your past logs...</p></div>
</section>

<section class="panel" id="lookbackPanel">
<details style="border-top:0;border-bottom:0;padding:0">
<summary style="font-size:21px;font-weight:900">Look back</summary>
<p class="meta" style="margin-top:10px">Turn a stretch of logs into highlights: accomplishments, learnings, and how you are growing over time. One action per fresh summary; re-opening the same stretch is free.</p>
<div class="btnrow"><button type="button" class="button secondary" id="sumWeek">Summarize my week</button><button type="button" class="button secondary" id="sumMonth">Summarize my month</button></div>
<p id="sumStatus" class="mic-status" style="margin-top:8px"></p>
<p id="sumError" class="error"></p>
<div id="sumBody"></div>
</details>
</section>

<section class="panel" id="advancedPanel">
<details style="border-top:0;border-bottom:0;padding:0">
<summary style="font-size:21px;font-weight:900">Advanced settings</summary>
<h3 style="margin:14px 0 4px">A new Notion page for each filed day</h3>
<p class="meta" style="margin-top:0">Pick a page or a database in Notion. Every day you file becomes its own page there, titled with the date, holding that day’s jot notes. Re-filing a day rewrites its page. Bum Bum stays the source of truth.</p>
<div id="notionState"><p class="meta">Checking your connections...</p></div>
<div id="notionForm">
<label style="display:block;font-weight:700;margin:10px 0 4px">Notion integration token</label>
<input type="password" id="notionToken" placeholder="ntn_... or secret_..." autocomplete="off" style="width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff">
<p class="meta" style="margin:4px 0 0">Create one free under your Notion settings, in Integrations: make an internal integration for your workspace, then copy its secret.</p>
<label style="display:block;font-weight:700;margin:12px 0 4px">Notion page or database link</label>
<input type="text" id="notionPage" placeholder="https://www.notion.so/... your page" autocomplete="off" style="width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff">
<p class="meta" style="margin:4px 0 0">Open the parent page or database in Notion, invite your integration (Share menu on a page, the connections menu on a database), then paste its link here. Each day’s page is created inside it.</p>
<div class="btnrow"><button type="button" class="button secondary" id="notionSave">Connect Notion</button></div>
</div>
<p id="notionMsg" class="mic-status" style="margin-top:8px"></p>
<p id="notionError" class="error"></p>
<h3 style="margin:18px 0 4px">Your top list on your calendar</h3>
<p class="meta" style="margin-top:0">Emails get buried. If you connect Google Calendar, your top list also lands as an all-day event on the mornings your recap goes out. It is optional, and you can disconnect any time.</p>
<?php if ($gcalNote === 'connected'): ?><p class="mic-status">Google Calendar connected. Your next recap morning puts the top list on your calendar too.</p><?php elseif ($gcalNote === 'error'): ?><p class="error">That connection did not finish. Try again.</p><?php elseif ($gcalNote === 'unavailable'): ?><p class="error">Calendar connection is not available right now.</p><?php endif; ?>
<?php if (!$gcalReady): ?>
<p class="meta">Calendar connection is not switched on yet.</p>
<?php elseif ($gcalConn): ?>
<p class="meta">Connected. Your top list lands on your calendar as an all-day event on recap mornings, next to the email.</p>
<div class="btnrow"><button type="button" class="button secondary" id="gcalTest">Send a test event</button><button type="button" class="button secondary" id="gcalDisconnect">Disconnect Google Calendar</button></div>
<p id="gcalMsg" class="mic-status" style="margin-top:8px"></p>
<?php else: ?>
<div class="btnrow"><a class="button secondary" href="/api/auth/google-calendar.php">Connect Google Calendar</a></div>
<?php endif; ?>
</details>
</section>
<?php endif; ?>

<section id="faq">
<h2>Day Log questions</h2>
<details><summary>Where does my voice go?</summary><p>Recordings are transcribed and then discarded. Only the text of your log is stored, in your account.</p></details>
<details><summary>What lands in the email?</summary><p>A recap every morning: your most recent log in clean jot notes, yesterday's list scored, and your next top priorities. Miss a day and you get one gentle nudge; after that, no emails until you log again.</p></details>
<details><summary>What if I skip a day?</summary><p>Nothing breaks. The scorecard compares against your most recent filed day, whenever that was.</p></details>
<details><summary>How does the scorecard work?</summary><p>When you file today, Bum Bum takes the tomorrow list from your most recent filed day and checks each item against what you said in today's log: done, partial, or not mentioned. It only ever uses your own words, and it never marks something done from the plan alone.</p></details>
<details><summary>Can I type instead of talking?</summary><p>Yes. The one big box takes typing too. Talk, type, or mix both in the same log.</p></details>
<details><summary>Can I fix a day after filing it?</summary><p>Yes. Open the day in Recent logs to edit any jot note, flip a scorecard mark, or delete the day entirely; editing and deleting are free. Filing again the same day also replaces the earlier version and does not cost another action.</p></details>
<details><summary>Can Day Log copy my logs to Notion?</summary><p>Yes. In Advanced settings, connect a Notion page or database with your own integration token and each filed day becomes its own Notion page of jot notes, titled with the date. Re-filing a day rewrites its page.</p></details>
<details><summary>Can I capture a learning before I file?</summary><p>Yes. Jot learnings into Day Log as they happen during the day, free and as often as you like. When you file that evening, they are appended to the day's learnings in your own words. Jot one after filing and it still joins the day's record.</p></details>
</section>

<section class="panel" aria-label="Guide">
<h2 style="margin-top:0">Closing your day in five minutes, the short guide</h2>
<h3>How it works here</h3>
<p>Talk through the prompts above one box: what got done and what got in the way, what you learned, and what tomorrow needs. Bum Bum turns the ramble into clean jot notes, scores yesterday's top list against what you actually did, and files the day in your account. The next morning, a recap email arrives with tomorrow's priorities on top. One action per day filed, and re-filing the same day replaces the earlier version at no extra cost. Day Log needs a free account, because the scorecard only works if yesterday's list is filed under you. Lessons that land mid-day do not have to wait for the evening: jot them down under the log box the moment they happen, and they join the day's learnings in your own words when you file.</p>
<h3>Your voice is never kept</h3>
<p>Recordings are transcribed and discarded; only the text of your log is stored, in your account, and it is never shared. Transcription tries your browser's live transcription first; on browsers without it, the recording is transcribed by Whisper and then discarded. The recording is only a way to get your words into the box.</p>
<h3>Write tomorrow's list so it can be scored</h3>
<p>'Send the proposal' is easy to score tomorrow. 'Work on business stuff' is too vague to mark done or not. File before you plan tomorrow anywhere else, because the log's list is the one that gets scored. Over a stretch of days, week and month summaries pull out the accomplishments, learnings, and growth, and advanced settings can file each day into Notion with your own integration token.</p>
</section>

<?php $bbTool = 'day-log'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
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
let activeRec=null; // {mode, recog|recorder, chunks, stream, base, stopped, raf, ctx}

function setStatus(msg){document.querySelector('#micStatus').textContent=msg||''}
function setBtn(recording){micBtn.textContent=recording?'Stop':'\u{1F3A4}';micBtn.classList.toggle('recording',!!recording);const l=document.querySelector('#recLabel');if(l)l.textContent=recording?'Recording. Tap the mic to stop.':'Tap to start recording'}
const waveStrip=document.querySelector('#waveStrip');
const waveLabel=document.querySelector('#waveLabel');
const waveBars=Array.from(waveStrip.querySelectorAll('.wave-bars span'));
function showWave(label,anim){waveStrip.classList.remove('hidden');waveStrip.classList.toggle('anim',!!anim);waveLabel.textContent=label||''}
function hideWave(){waveStrip.classList.add('hidden');waveStrip.classList.remove('anim','big','wait')}
function stopLevels(a){if(a&&a.raf)cancelAnimationFrame(a.raf)}

async function stopActive(){
  const a=activeRec; if(!a) return; activeRec=null;
  setBtn(false); stopLevels(a);
  if(a.mode==='sr'){a.stopped=true;try{a.recog.stop()}catch(e){}hideWave();setStatus('');return}
  if(a.mode==='mr'){try{a.recorder.stop()}catch(e){}}
}

function startSR(){
  const recog=new SR();
  recog.continuous=true; recog.interimResults=true;
  recog.lang=navigator.language||'en-US';
  const a={mode:'sr',recog,base:ta.value,stopped:false};
  activeRec=a;
  recog.onresult=e=>{
    let finalText='';
    for(let i=e.resultIndex;i<e.results.length;i++){if(e.results[i].isFinal)finalText+=e.results[i][0].transcript}
    if(finalText){a.base=(a.base?a.base.replace(/\s+$/,'')+' ':'')+finalText.trim();ta.value=a.base;openType();refreshFile()}
  };
  recog.onend=()=>{if(activeRec===a&&!a.stopped){try{recog.start()}catch(e){}}};
  recog.onerror=e=>{
    if(e.error==='not-allowed'||e.error==='service-not-allowed'){
      // SpeechRecognition can report not-allowed WITHOUT ever showing a
      // permission prompt (notably iOS Safari), while a plain getUserMedia
      // call prompts properly, which is why Notes works on the same phone.
      // Try the recorder before declaring the mic blocked; if the recorder
      // is refused too, its own message says so.
      a.stopped=true; if(activeRec===a)activeRec=null; setBtn(false); hideWave();
      try{recog.stop()}catch(err){}
      startMR(false);
      return;
    }
    if(e.error==='aborted'||e.error==='no-speech')return;
    // Live transcription failed (audio-capture, network, ...): record instead,
    // and remember it so this browser goes straight to recording next time.
    a.stopped=true; if(activeRec===a)activeRec=null; setBtn(false); hideWave();
    try{recog.stop()}catch(err){}
    try{localStorage.setItem('bbDayLogRecOnly','1')}catch(err){}
    startMR(true);
  };
  try{recog.start();setBtn(true);showWave('Listening. Your words land in the box as you talk.',true);setStatus('')}
  catch(e){if(activeRec===a)activeRec=null;startMR(true)}
}

async function startMR(fellBack){
  if(!navigator.mediaDevices||!window.MediaRecorder){hideWave();setStatus('This browser cannot record here. Type instead.');return}
  try{
    const stream=await navigator.mediaDevices.getUserMedia({audio:true});
    const mime=MediaRecorder.isTypeSupported('audio/webm')?'audio/webm':(MediaRecorder.isTypeSupported('audio/mp4')?'audio/mp4':'');
    const recorder=mime?new MediaRecorder(stream,{mimeType:mime}):new MediaRecorder(stream);
    const a={mode:'mr',recorder,stream,chunks:[],base:ta.value,raf:0,ctx:null};
    activeRec=a;
    try{
      const Ctx=window.AudioContext||window.webkitAudioContext;
      const ctx=new Ctx();
      const srcNode=ctx.createMediaStreamSource(stream);
      const analyser=ctx.createAnalyser(); analyser.fftSize=64; analyser.smoothingTimeConstant=0.75; srcNode.connect(analyser);
      if(ctx.state==='suspended'){ctx.resume().catch(()=>{})}
      const data=new Uint8Array(analyser.frequencyBinCount);
      a.ctx=ctx;
      const tick=()=>{if(activeRec!==a)return;if(ctx.state==='suspended'&&!a._resumed){a._resumed=true;ctx.resume().catch(()=>{})}analyser.getByteFrequencyData(data);waveBars.forEach((bar,i)=>{const v=(data[2+i*2]||0)/255;bar.style.height=(6+v*28)+'px'});a.raf=requestAnimationFrame(tick)};
      tick();
    }catch(e){}
    recorder.ondataavailable=e=>{if(e.data&&e.data.size)a.chunks.push(e.data)};
    recorder.onstop=async()=>{
      stream.getTracks().forEach(t=>t.stop()); stopLevels(a);
      if(a.ctx){try{a.ctx.close()}catch(e){}}
      if(activeRec===a)activeRec=null;
      setBtn(false);
      if(!a.chunks.length){hideWave();ta.classList.remove('hidden');setStatus('Nothing was captured. Try again, or type.');return}
      showWave('Transcribing your ramble...',true); setStatus('');
      const blob=new Blob(a.chunks,{type:recorder.mimeType||'audio/webm'});
      let dataUrl=await new Promise(res=>{const r=new FileReader();r.onload=()=>res(r.result);r.onerror=()=>res('');r.readAsDataURL(blob)});
      if(dataUrl){
        // Normalize to a clean base type: iOS reports things like
        // audio/mp4;codecs=... or audio/x-m4a, which strict checks reject.
        let base=((recorder.mimeType||'audio/mp4').split(';')[0]||'audio/mp4').trim().toLowerCase();
        if(base==='audio/x-m4a'||base==='audio/m4a'||base==='audio/aac')base='audio/mp4';
        dataUrl=dataUrl.replace(/^data:[^;,]+/,'data:'+base);
      }
      if(!dataUrl){hideWave();ta.classList.remove('hidden');setStatus('Recording could not be read. Type instead.');return}
      // Server transcription is the slow stretch: the loading strip
      // takes the text box's place, message at the top of the box,
      // until the words actually land (owner request), and the mic
      // locks so a second take cannot race the transcription
      // already in flight.
      showWave('Bum Bum is finishing up writing...',true);
      waveStrip.classList.remove('big'); waveStrip.classList.add('wait');
      micBtn.disabled=true; micBtn.style.opacity='.45';
      const{response,data}=await apiCall({action:'transcribe',audio:dataUrl});
      hideWave(); ta.classList.remove('hidden'); micBtn.disabled=false; micBtn.style.opacity='';
      if(response.ok&&data.text){ta.value=(a.base?a.base.replace(/\s+$/,'')+' ':'')+data.text;openType();refreshFile();setStatus('Transcribed. Fix anything, then file when ready.')}
      else setStatus((data&&data.error)||'Transcription failed. Type instead.');
    };
    recorder.start();
    setBtn(true);
    ta.classList.add('hidden');
    waveStrip.classList.add('big');
    showWave(fellBack?'Live transcription could not reach a mic here, so I am recording instead. Keep talking; your words appear when you press Stop.':'Recording. Keep talking; your words appear when you press Stop.',false);
    setStatus('');
  }catch(e){hideWave();ta.classList.remove('hidden');setStatus('Mic is blocked or busy. Allow the microphone, and check no other app is using it, or type instead.')}
}

micBtn.addEventListener('click',()=>{
  if(activeRec){stopActive();return}
  let recOnly=false;
  try{recOnly=localStorage.getItem('bbDayLogRecOnly')==='1'}catch(e){}
  if(SR&&!recOnly){startSR()}else{startMR(false)}
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
let dlResult=null;
document.querySelector('#filedToggle').addEventListener('click',()=>{
  const c=document.querySelector('#resultCollapse');
  const open=c.classList.toggle('hidden')===false;
  document.querySelector('#filedToggle').textContent=open?'Minimize':'Show';
});
function renderResult(structured,emailNote,date){
  dlResult={structured:structured,note:emailNote||'',date:date||localToday()};
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

/* --- Edit / delete a filed day --- */
const EDIT_SECTIONS=[['done','Done today'],['missed','Set out but did not happen'],['challenges','Challenges'],['overcome','Beating them'],['tomorrow','Tomorrow\u2019s top list'],['learnings','Learnings']];
function editRowHtml(v){return '<div class="edit-row"><input type="text" value="'+esc(v)+'"><button type="button" class="x" aria-label="Remove item">&times;</button></div>'}
function editorHtml(s){
  let h='<div class="editor">';
  if(s.reflection&&s.reflection.length){
    h+='<h4>Yesterday\u2019s list, scored</h4>'+s.reflection.map(r=>'<div class="edit-row" data-refl="1"><select class="refl-status"><option value="done"'+(r.status==='done'?' selected':'')+'>done</option><option value="partial"'+(r.status==='partial'?' selected':'')+'>partial</option><option value="not_mentioned"'+(r.status==='not_mentioned'?' selected':'')+'>not mentioned</option></select><input type="text" class="refl-item" value="'+esc(r.item)+'"><button type="button" class="x" aria-label="Remove item">&times;</button></div>').join('');
  }
  EDIT_SECTIONS.forEach(([key,label])=>{
    h+='<h4>'+label+'</h4><div class="edit-sec" data-sec="'+key+'">'+((s[key]||[]).map(editRowHtml).join(''))+'</div><button type="button" class="mini-btn add-item" data-sec="'+key+'">+ Add</button>';
  });
  h+='<div class="edit-actions"><button type="button" class="button save-edit">Save changes</button><button type="button" class="button secondary cancel-edit">Cancel</button><span class="mic-status edit-status"></span></div></div>';
  return h;
}
function collectEditor(ed){
  const s={reflection:[],done:[],missed:[],challenges:[],overcome:[],tomorrow:[],learnings:[]};
  ed.querySelectorAll('[data-refl]').forEach(row=>{const item=row.querySelector('.refl-item').value.trim();if(item)s.reflection.push({item,status:row.querySelector('.refl-status').value})});
  ed.querySelectorAll('.edit-sec').forEach(sec=>{sec.querySelectorAll('input').forEach(inp=>{const v=inp.value.trim();if(v)s[sec.dataset.sec].push(v)})});
  return s;
}
function bindEditor(container,date,structured,onRefresh){
  container.innerHTML=editorHtml(structured);
  const ed=container.querySelector('.editor');
  ed.addEventListener('click',async e=>{
    const x=e.target.closest('.x');
    if(x){x.closest('.edit-row').remove();return}
    const add=e.target.closest('.add-item');
    if(add){const sec=ed.querySelector('.edit-sec[data-sec="'+add.dataset.sec+'"]');sec.insertAdjacentHTML('beforeend',editRowHtml(''));sec.querySelector('.edit-row:last-child input').focus();return}
    if(e.target.closest('.cancel-edit')){onRefresh(false);return}
    if(e.target.closest('.save-edit')){
      const status=ed.querySelector('.edit-status');
      status.textContent='Saving...';
      const{response,data}=await apiCall({action:'update',date,structured:collectEditor(ed)});
      if(!response.ok){status.textContent=(data&&data.error)||'Saving tripped. Try once more.';return}
      let note='Edits saved.';
      if(data.notion&&data.notion.connected&&data.notion.ok)note+=' Your Notion page was updated to match.';
      onRefresh(true,data.structured,note);
    }
  });
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
  let metaMsg=data.first_today
    ? 'Filed. Your recap lands tomorrow morning. Tomorrow, this list is the scorecard.'
    : 'Updated. Re-filing the same day replaces the earlier version and costs no extra action.';
  if(data.notion&&data.notion.connected){
    metaMsg+= data.notion.ok ? (data.notion.created?' A Notion page was made for today.':' Today’s Notion page was updated.') : ' '+(data.notion.error||'');
  }
  ta.value=''; refreshFile();
  renderResult(data.structured, metaMsg, localToday());
  if(window.BBDropStrip)window.BBDropStrip.show('day-log');
  loadHistory();
});

/* --- Ad-hoc learnings: jot during the day, appended at filing --- */
const jotText=document.querySelector('#jotText');
const jotBtn=document.querySelector('#jotBtn');
function syncFiledLearnings(data){
  if(!data||!data.structured||!dlResult||dlResult.date!==localToday())return;
  dlResult.structured=data.structured;
  const s=data.structured;
  document.querySelector('#resultBody').innerHTML=reflectionHtml(s.reflection)
    +sectionHtml('Done today',s.done)
    +sectionHtml('Set out but did not happen',s.missed)
    +sectionHtml('Challenges',s.challenges)
    +sectionHtml('Beating them',s.overcome)
    +sectionHtml('Tomorrow\u2019s top list',s.tomorrow)
    +sectionHtml('Learnings',s.learnings);
}
async function addJot(){
  const text=jotText.value.trim();
  const err=document.querySelector('#jotError');
  const status=document.querySelector('#jotStatus');
  err.textContent='';
  if(!text){err.textContent='Type the learning first, then jot it down.';return}
  jotBtn.disabled=true; status.textContent='Saving...';
  const{response,data}=await apiCall({action:'jot',text,todayLocal:localToday()});
  jotBtn.disabled=false;
  if(!response.ok){status.textContent='';err.textContent=(data&&data.error)||'That jotting tripped. Try once more.';return}
  jotText.value='';
  status.textContent=data.dupe?'Already jotted today.':(data.filed?'Saved, and added to today\u2019s filed log.':'Saved. It lands in tonight\u2019s Learnings when you file.');
  syncFiledLearnings(data);
  bbTrack('daylog_jot',{filed:!!data.filed});
}
jotBtn.addEventListener('click',addJot);
jotText.addEventListener('keydown',e=>{if(e.key==='Enter'&&(e.metaKey||e.ctrlKey)){e.preventDefault();addJot()}});

/* --- Tabs: talk through your day, or note a mid day learning --- */
function openType(){document.querySelector('#typeZone').classList.remove('hidden')}
document.querySelector('#typeBtn').addEventListener('click',()=>{openType();ta.focus()});
const tabTalk=document.querySelector('#tabTalk');
const tabJot=document.querySelector('#tabJot');
function showTab(jot){
  tabTalk.classList.toggle('active',!jot);
  tabJot.classList.toggle('active',!!jot);
  document.querySelector('#panelTalk').classList.toggle('hidden',!!jot);
  document.querySelector('#panelJot').classList.toggle('hidden',!jot);
  if(jot)document.querySelector('#jotText').focus();
}
tabTalk.addEventListener('click',()=>showTab(false));
tabJot.addEventListener('click',()=>showTab(true));

/* --- Context + history on load --- */
(async()=>{
  const{response,data}=await apiCall({action:'context',todayLocal:localToday()});
  if(response.ok&&data.yesterday&&data.yesterday.tomorrow&&data.yesterday.tomorrow.length){
    const items=data.yesterday.tomorrow.map(i=>'<li>'+esc(i)+'</li>').join('');
    document.querySelector('#yesterdaySlot').innerHTML='<div class="yday"><b>Yesterday ('+esc(data.yesterday.date)+') you committed to:</b><ul>'+items+'</ul><p class="hint">Talk through the prompts and Bum Bum scores this list: done, partial, or not mentioned. Not mentioned is a fine answer.</p></div>';
  }
  if(response.ok&&data.today&&data.today.structured){
    renderResult(data.today.structured,'Already filed today. Filing again updates it and costs no extra action.');
  }
})();
let dlHistory=[];
function fmtDay(dateStr){
  try{return new Date(dateStr+'T12:00:00').toLocaleDateString('en-US',{weekday:'short',month:'short',day:'numeric'})}catch(e){return dateStr}
}
function renderDetail(date){
  const box=document.querySelector('#historyDetail');
  if(!box)return;
  const l=dlHistory.find(x=>x.date===date);
  document.querySelectorAll('.log-card').forEach(c=>c.classList.toggle('active',c.dataset.date===date));
  if(!l){box.innerHTML='';return}
  const s=l.structured||{};
  box.innerHTML='<div class="jot">'+reflectionHtml(s.reflection)+sectionHtml('Done today',s.done)+sectionHtml('Set out but did not happen',s.missed)+sectionHtml('Challenges',s.challenges)+sectionHtml('Beating them',s.overcome)+sectionHtml('Tomorrow\u2019s top list',s.tomorrow)+sectionHtml('Learnings',s.learnings)+'</div>'
    +'<div class="edit-actions"><button type="button" class="button secondary" id="hdEdit">Edit jot notes</button><button type="button" class="mini-btn del-log" id="hdDelete">Delete this day</button></div>';
  document.querySelector('#hdEdit').addEventListener('click',()=>{
    bindEditor(box,date,s,async()=>{await loadHistory();renderDetail(date)});
  });
  const del=document.querySelector('#hdDelete');
  let armed=null;
  del.addEventListener('click',async()=>{
    if(!armed){
      del.textContent='Tap again to delete this day';
      del.classList.add('armed');
      armed=setTimeout(()=>{del.textContent='Delete this day';del.classList.remove('armed');armed=null},3000);
      return;
    }
    clearTimeout(armed);
    del.disabled=true; del.textContent='Deleting...';
    const{response}=await apiCall({action:'delete',date});
    if(!response.ok){del.disabled=false;del.textContent='Delete this day';del.classList.remove('armed');armed=null;return}
    if(date===localToday()){document.querySelector('#resultPanel').classList.add('hidden');dlResult=null}
    loadHistory();
  });
}
async function loadHistory(){
  const list=document.querySelector('#historyList');
  const{response,data}=await apiCall({action:'history'});
  if(!response.ok||!data.logs||!data.logs.length){dlHistory=[];list.innerHTML='<p class="meta">No logs yet. Your first filed day will show up here.</p>';return}
  dlHistory=data.logs;
  const cards=data.logs.map((l,i)=>{
    const s=l.structured||{};
    const refl=s.reflection||[];
    const kept=refl.filter(r=>r.status==='done').length;
    const score=refl.length?kept+' of '+refl.length+' kept':'';
    const snip=((s.done&&s.done[0])||(s.learnings&&s.learnings[0])||'Filed day');
    const allKept=refl.length>0&&refl.every(r=>r.status!=='not_mentioned');
    return '<button type="button" class="log-card'+(i===0?' latest':'')+'" data-date="'+esc(l.date)+'">'
      +'<span class="log-card-date">'+esc(fmtDay(l.date))+(allKept?' &#10003;':'')+'</span>'
      +'<span class="log-card-snip">'+esc(snip.length>90?snip.slice(0,90)+'\u2026':snip)+'</span>'
      +'<span class="log-card-meta">'+(s.done?s.done.length:0)+' done'+(score?' &middot; '+esc(score):'')+'</span></button>';
  }).join('');
  list.innerHTML='<div class="log-cards">'+cards+'</div><div id="historyDetail"></div>';
  list.querySelectorAll('.log-card').forEach(c=>c.addEventListener('click',()=>renderDetail(c.dataset.date)));
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

/* --- Advanced settings: Notion destination --- */
const notionState=document.querySelector('#notionState');
const notionForm=document.querySelector('#notionForm');
const notionMsg=document.querySelector('#notionMsg');
const notionError=document.querySelector('#notionError');
function renderNotion(notion){
  if(notion&&notion.target_id){
    notionState.innerHTML='<p class="meta" style="margin:0">Connected. Every day you file becomes a new page in your Notion target.</p><div class="btnrow"><button type="button" class="button secondary" id="notionOff">Disconnect Notion</button></div>';
    notionForm.classList.add('hidden');
    document.querySelector('#notionOff').addEventListener('click',async()=>{
      await apiCall({action:'destination_disconnect'});
      notionMsg.textContent='Notion disconnected. Pages already made stay in Notion; new filings will not create pages.';
      renderNotion(null);
    });
  } else {
    notionState.innerHTML='';
    notionForm.classList.remove('hidden');
  }
}
(async()=>{
  const{response,data}=await apiCall({action:'destination_get'});
  renderNotion(response.ok?data.notion:null);
})();
document.querySelector('#notionSave').addEventListener('click',async()=>{
  notionError.textContent=''; notionMsg.textContent='Checking the connection with Notion...';
  const{response,data}=await apiCall({action:'destination_save',token:document.querySelector('#notionToken').value,page:document.querySelector('#notionPage').value});
  notionMsg.textContent='';
  if(!response.ok){notionError.textContent=(data&&data.error)||'That connection tripped. Try once more.';return}
  notionMsg.textContent='Connected. The next day you file gets its own Notion page.';
  document.querySelector('#notionToken').value='';
  renderNotion(data.notion);
});
const gcalTest=document.querySelector('#gcalTest');
if(gcalTest){gcalTest.addEventListener('click',async()=>{
  const msg=document.querySelector('#gcalMsg'); msg.textContent='Sending a test event...';
  const session=await fetch('/api/session.php').then(r=>r.json());
  const response=await fetch('/api/auth/google-calendar-test.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({csrf:session.csrf})});
  const data=await response.json().catch(()=>({}));
  if(response.ok&&data.status==='created'){msg.textContent='Sent. It is on your calendar tomorrow as an all-day event.';}
  else if(data.status==='no top list'){msg.textContent='Your most recent log has no top list, so there was nothing to send.';}
  else if(data.status==='not connected'){msg.textContent='Not connected yet.';}
  else{msg.textContent='The test did not land: '+(data.status||'unknown error')+'.';}
});}
const gcalBtn=document.querySelector('#gcalDisconnect');
if(gcalBtn){gcalBtn.addEventListener('click',async()=>{
  const msg=document.querySelector('#gcalMsg'); msg.textContent='Disconnecting...';
  const session=await fetch('/api/session.php').then(r=>r.json());
  const response=await fetch('/api/auth/google-calendar-disconnect.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({csrf:session.csrf})});
  if(response.ok){location.href='/tools/day-log/';}else{msg.textContent='That disconnect tripped. Try once more.';}
});}
</script>
<?php endif; ?>
<script src="/tools/drop-strip.js?v=20261030r1"></script></body></html>
