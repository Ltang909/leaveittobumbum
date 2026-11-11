<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<?php
$addons = [
  ["id" => "contrast", "title" => "Skip the contrast formula", "blurb" => "AI loves setting up a fake either-or. This add-on tells it to state the point plainly.", "text" => "State the point directly. Skip contrast formulas like \"It is not X, it is Y\", \"X. Never Y.\", and \"You are not doing X, you are doing Y\". When two ideas differ, describe each one in its own words."],
  ["id" => "concrete", "title" => "Use concrete words", "blurb" => "Abstract phrases give AI writing away. This one swaps them for plain nouns and verbs.", "text" => "Use concrete nouns and direct verbs. Avoid phrases like \"the landscape of\", \"the shape of\", \"at the intersection of\", \"the fabric of\", \"unlock the potential\", and \"move the needle\". Say what something does, what it costs, and who it is for."],
  ["id" => "rhythm", "title" => "Vary the rhythm", "blurb" => "AI sentences tend to run one length. This add-on mixes short and long lines so the text reads like speech.", "text" => "Vary sentence length. Mix short sentences with longer ones, and let a fragment land now and then. If three sentences in a row run about the same length, rewrite one of them."],
  ["id" => "talk", "title" => "Write like you talk", "blurb" => "Contractions and everyday words. The fastest way to make a draft sound like a person sent it.", "text" => "Write the way a person talks. Use contractions. Choose everyday words over formal ones. Skip \"furthermore\", \"moreover\", \"additionally\", and \"in conclusion\". Start a sentence with And or But when it sounds natural."],
  ["id" => "filler", "title" => "Cut the AI filler", "blurb" => "A short banned list. Delve, tapestry, game-changer, and the rest of the usual suspects.", "text" => "Do not use these words and phrases: delve, tapestry, testament to, game-changer, revolutionize, ever-evolving, look no further, in today's fast-paced world. Replace each one with a plain fact, or delete the sentence."],
  ["id" => "specific", "title" => "Be specific", "blurb" => "General claims sound generated because they usually are. Real numbers and names fix that.", "text" => "Use specific details: real numbers, names, dates, and prices. If a sentence could describe any business in any industry, rewrite it with a detail only this business can claim, or cut it."],
];
$defaultOn = ["contrast", "concrete", "filler"];
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Sound Human | Prompt Add-Ons for Human-Sounding AI Writing | Leave It to Bum Bum</title>
<meta name="description" content="Free prompt add-ons to paste into ChatGPT or Claude. Plain instructions that make AI writing sound like a person wrote it. No account, no actions, runs in your browser.">
<meta property="og:title" content="Sound Human | Prompt Add-Ons for Human-Sounding AI Writing | Leave It to Bum Bum">
<meta property="og:description" content="Free prompt add-ons to paste into ChatGPT or Claude. Plain instructions that make AI writing sound like a person wrote it.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/sound-human/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/icons/sound-human.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Sound Human | Prompt Add-Ons for Human-Sounding AI Writing | Leave It to Bum Bum">
<meta name="twitter:description" content="Free prompt add-ons to paste into ChatGPT or Claude. Plain instructions that make AI writing sound like a person wrote it.">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/icons/sound-human.png">
<link rel="canonical" href="https://leaveittobumbum.com/tools/sound-human/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Sound Human",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Free prompt add-ons to paste into ChatGPT or Claude. Plain instructions that make AI writing sound like a person wrote it. No account, no actions, runs in your browser.",
  "url": "https://leaveittobumbum.com/tools/sound-human/"
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
        "text": "No. Sound Human is free, uses no actions, and needs no sign-up. Copy what you need and go."
      }
    },
    {
      "@type": "Question",
      "name": "Does my prompt leave my browser?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. The combiner runs on this page. Your prompt text stays in your browser; nothing is sent to Bum Bum or anywhere else."
      }
    },
    {
      "@type": "Question",
      "name": "Which AI tools does this work with?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Any AI that follows written instructions: ChatGPT, Claude, Gemini, and the rest. Paste the add-on at the end of your prompt, after your request."
      }
    },
    {
      "@type": "Question",
      "name": "Will this beat an AI detector?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "That is the wrong target. Detectors are unreliable, and chasing their scores makes writing worse. These add-ons aim at a better result: text a reader can get through without their eyes glazing over."
      }
    }
  ]
}
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Sound Human | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"Sound Human","item":"https://leaveittobumbum.com/tools/sound-human/"}]}</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.sh-label{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;opacity:.55;margin:0 0 8px}
.sh-box{width:100%;border:2px solid var(--ink);border-radius:12px;background:#fff;padding:12px 14px;font:inherit;font-size:.98rem;line-height:1.5;box-sizing:border-box}
textarea.sh-box{resize:vertical;min-height:110px}
textarea.sh-out{min-height:170px;background:#FFF9E9}
.chips{display:flex;flex-wrap:wrap;gap:8px;margin:14px 0}
.chip{display:inline-flex;align-items:center;gap:7px;border:2px solid var(--ink);border-radius:999px;background:#fff;padding:8px 14px;font-weight:800;font-size:.92rem;cursor:pointer;user-select:none}
.chip input{accent-color:var(--ink);width:16px;height:16px;margin:0;cursor:pointer}
.chip.on{background:var(--ink);color:#fff}
.sh-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:12px}
.sh-actions .button{min-height:48px}
.addon-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px}
.addon-card{background:#fff;border:2px solid var(--line);border-radius:12px;padding:16px;box-shadow:4px 4px 0 var(--ink)}
.addon-card h3{margin:0 0 6px;font-size:1.15rem}
.addon-card .blurb{margin:0 0 12px;font-size:.95rem;opacity:.85}
.snippet{border:2px solid var(--line);border-radius:10px;background:#F5EDDA;padding:12px 14px;font-size:.93rem;line-height:1.5;margin:0 0 12px}
.addon-card .button{min-height:44px}
@media(max-width:700px){.addon-grid{grid-template-columns:1fr}}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Sound Human"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/sound-human.png" alt="Sound Human"><div><h1>Make AI Sound Human</h1><p class="lede bb-desc">Sound Human is short instructions for your prompts, so AI replies read like a person wrote them.</p></div></div>
<section class="panel" aria-label="Prompt combiner">
<h2 style="margin-top:0">Build your prompt</h2>
<p class="sh-label"><label for="promptIn">Your prompt</label></p>
<textarea class="sh-box" id="promptIn" placeholder="Example: Write a short Instagram caption for my bakery's sourdough class next Saturday."></textarea>
<p class="sh-label" style="margin-top:16px">Add-ons to attach</p>
<div class="chips" id="chipRow">
<?php foreach ($addons as $a): ?><label class="chip<?= in_array($a["id"], $defaultOn, true) ? " on" : "" ?>"><input type="checkbox" value="<?= htmlspecialchars($a["id"]) ?>"<?= in_array($a["id"], $defaultOn, true) ? " checked" : "" ?>><?= htmlspecialchars($a["title"]) ?></label>
<?php endforeach; ?></div>
<p class="sh-label"><label for="combinedOut">Ready to paste</label></p>
<textarea class="sh-box sh-out" id="combinedOut" readonly></textarea>
<div class="sh-actions"><button type="button" class="button" id="copyCombinedBtn">Copy the whole thing</button><button type="button" class="button secondary" id="clearBtn">Clear</button></div>
<p class="hint" style="margin:12px 0 0;font-size:13px;opacity:.7">Paste the combined block into your AI chat as one message. The instructions sit at the end, where AIs pay the most attention to them.</p>
</section>
<section aria-label="The add-ons">
<h2 style="margin:26px 0 0">The add-ons, one by one</h2>
<div class="addon-grid">
<?php foreach ($addons as $i => $a): ?><article class="addon-card"><h3><?= htmlspecialchars($a["title"]) ?></h3><p class="blurb"><?= htmlspecialchars($a["blurb"]) ?></p><p class="snippet"><?= htmlspecialchars($a["text"]) ?></p><button type="button" class="button secondary" data-copy="<?= $i ?>">Copy this add-on</button></article>
<?php endforeach; ?></div>
</section>
<script>
const ADDONS = <?= json_encode(array_map(fn($a) => ["id" => $a["id"], "title" => $a["title"], "text" => $a["text"]], $addons), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
const promptIn = document.querySelector('#promptIn');
const combinedOut = document.querySelector('#combinedOut');
function selectedAddons(){ return Array.from(document.querySelectorAll('#chipRow input:checked')).map(cb => ADDONS.find(a => a.id === cb.value)).filter(Boolean); }
function buildCombined(){
  const picked = selectedAddons();
  const lines = picked.map(a => '- ' + a.text);
  let out = '';
  const p = promptIn.value.trim();
  if (p) out += p;
  if (lines.length) out += (out ? '\n\n' : '') + 'Writing instructions:\n' + lines.join('\n');
  combinedOut.value = out;
}
document.querySelectorAll('#chipRow input').forEach(cb => cb.addEventListener('change', () => { cb.closest('.chip').classList.toggle('on', cb.checked); buildCombined(); }));
promptIn.addEventListener('input', buildCombined);
async function copyText(text, btn, doneLabel){
  try { await navigator.clipboard.writeText(text); }
  catch(e){
    const ta = document.createElement('textarea'); ta.value = text; document.body.appendChild(ta); ta.select();
    try { document.execCommand('copy'); } catch(x){}
    ta.remove();
  }
  const old = btn.textContent; btn.textContent = doneLabel; setTimeout(() => btn.textContent = old, 1500);
}
document.querySelector('#copyCombinedBtn').addEventListener('click', e => { if (!combinedOut.value) return; copyText(combinedOut.value, e.currentTarget, 'Copied!'); try{bbTrack('sound_human_copy',{how:'combined',addons:selectedAddons().map(a=>a.id).join(',')})}catch(x){} });
document.querySelector('#clearBtn').addEventListener('click', () => { promptIn.value = ''; buildCombined(); promptIn.focus(); });
document.querySelectorAll('[data-copy]').forEach(btn => btn.addEventListener('click', () => { const a = ADDONS[+btn.dataset.copy]; copyText(a.text, btn, 'Copied!'); try{bbTrack('sound_human_copy',{how:'single',addon:a.id})}catch(x){} }));
buildCombined();
try{bbTrack('tool_opened',{tool:'sound-human'})}catch(e){}
</script>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Do I need an account?</summary>
<p>No. Sound Human is free, uses no actions, and needs no sign-up. Copy what you need and go.</p>
</details>
<details>
<summary>Does my prompt leave my browser?</summary>
<p>No. The combiner runs on this page. Your prompt text stays in your browser; nothing is sent to Bum Bum or anywhere else.</p>
</details>
<details>
<summary>Which AI tools does this work with?</summary>
<p>Any AI that follows written instructions: ChatGPT, Claude, Gemini, and the rest. Paste the add-on at the end of your prompt, after your request.</p>
</details>
<details>
<summary>Will this beat an AI detector?</summary>
<p>That is the wrong target. Detectors are unreliable, and chasing their scores makes writing worse. These add-ons aim at a better result: text a reader can get through without their eyes glazing over.</p>
</details>
</section>
<section class="panel" aria-label="Guide">
<h2 style="margin-top:0">Prompt add-ons, the short guide</h2>
<h3>How add-ons work</h3>
<p>An add-on is a plain instruction written for the AI, kept where you can grab it in one tap. AIs follow the last instructions in a prompt closely, so an add-on goes at the end, after the request itself. One add-on changes one habit. Two or three together cover most of the habits that make AI writing easy to spot.</p>
<h3>Start with two</h3>
<p>New to this? Start with Skip the contrast formula and Cut the AI filler. Those two remove the most common AI habits. Add Vary the rhythm when a draft reads flat, and Be specific when a draft could describe anyone's business.</p>
<h3>Keep your own set</h3>
<p>Once a combo works for you, save the combined block in your notes app and reuse it. Your prompts stay yours; this page just saves you retyping the instructions every time.</p>
<h3>The longer version</h3>
<p>Each habit above has a name and a reason it gives AI writing away. The full rundown, with examples, is in our article: <a href="/blog/how-to-make-ai-sound-more-human/">How to Make AI Sound More Human</a>.</p>
</section>
<?php $bbTool = 'sound-human'; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?><script src="/tools/drop-strip.js?v=20261030r1"></script></body></html>
