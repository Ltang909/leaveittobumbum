<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject);
// Shared engine for PDF to Image and its locked-format pair pages.
// $bbPdfMode is 'jpg' or 'png'; unset means the parent tool with its format picker.
$bbModes = [
  'pdf-to-image' => [
    'name' => 'PDF to Image',
    'title' => 'PDF to Image | Turn PDF Pages Into JPG, PNG, or WebP in Your Browser | Leave It to Bum Bum',
    'desc' => 'Turn every page of a PDF into a JPG, PNG, or WebP image, right in your browser. Fast, private, files never leave your device. Free to try, no account needed.',
    'h1' => 'PDF to Image',
    'lede' => 'PDF pages, as pictures. Turn every page into a clean JPG, PNG, or WebP, right in your browser.',
    'dropBold' => 'Drop PDFs here',
    'dropTail' => 'Every page becomes an image.',
    'howtoName' => 'How to use PDF to Image',
    'howtoDesc' => 'Turn every page of a PDF into an image.',
    'howtoSteps' => ['Drop a PDF into PDF to Image.', 'Pick a format, resolution, and quality.', 'Bum Bum converts every page right in your browser; your file never leaves your device.', 'Download each page, or grab them all in a zip.'],
    'faq' => [
      ['Do I need an account?', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/.', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.'],
      ['Do my PDFs upload anywhere?', 'No. Your files never leave your device. Conversion happens right in your browser.', 'No. Your files never leave your device. Conversion happens right in your browser.'],
      ['What do I get from a multi-page PDF?', 'One image per page, in order, in the format you picked. Download each page on its own, or grab them all together in a zip file.', 'One image per page, in order, in the format you picked. Download each page on its own, or grab them all together in a zip file.'],
      ['Which format should I pick?', 'JPG is the safe default almost everything accepts. PNG keeps text and lines perfectly crisp at a bigger file size. WebP makes the smallest files for websites that accept it.', 'JPG is the safe default almost everything accepts. PNG keeps text and lines perfectly crisp at a bigger file size. WebP makes the smallest files for websites that accept it.'],
      ['Which resolution should I pick?', 'Standard is plenty for forms and uploads. High is the pick for anything printed or read closely, and Extra high is for pages with small print. Bigger means bigger files.', 'Standard is plenty for forms and uploads. High is the pick for anything printed or read closely, and Extra high is for pages with small print. Bigger means bigger files.'],
      ['Why did my PDF fail?', 'Usually because it is password-protected or damaged. Unlock it with the password in your usual PDF app, save an unprotected copy, and drop that in.', 'Usually because it is password-protected or damaged. Unlock it with the password in your usual PDF app, save an unprotected copy, and drop that in.'],
    ],
    'guideTitle' => 'Turning PDF pages into images, the short guide',
    'guide' => [
      ['How it works here', 'Each page of your PDF is drawn in your browser at the resolution you pick, then saved as an image in the format you chose. Your file never leaves your device: there is no upload, no server copy, and nothing to delete afterwards. Multi-page PDFs come out as one image per page, in order, with a zip when you want them all at once.'],
      ['Picking a format', 'JPG is the safe default: every form, platform, and email client accepts it, and the files stay small. PNG keeps text and sharp lines perfectly crisp, which suits pages people will read closely, at a bigger file size. WebP makes the smallest files of the three and is the pick for putting pages on a website that accepts it.'],
      ['Resolution, in plain terms', 'Standard is plenty when the destination is an upload form or a quick share. High is the pick for anything printed or read closely. Extra high is for pages with small print, like terms sheets and scanned forms. Because a PDF page is drawn fresh at the size you pick, text stays sharp instead of being stretched from a small image.'],
      ['Popular conversions', 'Preset pages for the common outputs: <a href="/tools/pdf-to-jpg/">PDF to JPG</a> and <a href="/tools/pdf-to-png/">PDF to PNG</a>. The <a href="/tools/pdf-converter/">PDF Converter</a> page lists every way in and out of PDF on a single page.'],
    ],
  ],
  'pdf-to-jpg' => [
    'name' => 'PDF to JPG Converter',
    'title' => 'PDF to JPG Converter | Turn PDF Pages Into JPG Images in Your Browser | Leave It to Bum Bum',
    'desc' => 'Turn every page of a PDF into a JPG image, right in your browser. One image per page, ready for forms and uploads. Files never leave your device. Free to try, no account needed.',
    'h1' => 'PDF to JPG Converter',
    'lede' => 'Every page of your PDF, as a JPG image. Converted in your browser, files never uploaded.',
    'dropBold' => 'Drop PDFs here',
    'dropTail' => 'Every page becomes a JPG.',
    'dstName' => 'JPG', 'mime' => 'image/jpeg', 'ext' => 'jpg',
    'dstNote' => 'This page makes JPGs. <a href="/tools/pdf-to-image/">PDF to Image</a> also makes PNG and WebP.',
    'howtoName' => 'How to convert PDF to JPG',
    'howtoDesc' => 'Turn every page of a PDF into a JPG image.',
    'howtoSteps' => ['Drop a PDF into the converter.', 'Pick a resolution and quality.', 'Bum Bum converts every page right in your browser; your file never leaves your device.', 'Download each page, or grab them all in a zip.'],
    'faq' => [
      ['Do I need an account?', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/.', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.'],
      ['Do my PDFs upload anywhere?', 'No. Your files never leave your device. Conversion happens right in your browser.', 'No. Your files never leave your device. Conversion happens right in your browser.'],
      ['What do I get from a multi-page PDF?', 'One JPG per page, in order. Download each page on its own, or grab them all together in a zip file.', 'One JPG per page, in order. Download each page on its own, or grab them all together in a zip file.'],
      ['Why JPG?', 'JPG is the safe default almost everything accepts: upload forms, email, marketplaces, and application portals all take it, and the files stay small. For PNG or WebP output, use PDF to Image.', 'JPG is the safe default almost everything accepts: upload forms, email, marketplaces, and application portals all take it, and the files stay small. For PNG or WebP output, use <a href="/tools/pdf-to-image/">PDF to Image</a>.'],
      ['Which resolution should I pick?', 'Standard is plenty for forms and uploads. High is the pick for anything printed or read closely, and Extra high is for pages with small print. Bigger means bigger files.', 'Standard is plenty for forms and uploads. High is the pick for anything printed or read closely, and Extra high is for pages with small print. Bigger means bigger files.'],
      ['Why did my PDF fail?', 'Usually because it is password-protected or damaged. Unlock it with the password in your usual PDF app, save an unprotected copy, and drop that in.', 'Usually because it is password-protected or damaged. Unlock it with the password in your usual PDF app, save an unprotected copy, and drop that in.'],
    ],
    'guideTitle' => 'PDF to JPG, the short guide',
    'guide' => [
      ['How it works here', 'Each page of your PDF is drawn in your browser at the resolution you pick, then saved as a JPG. Your file never leaves your device: there is no upload, no server copy, and nothing to delete afterwards. Multi-page PDFs come out as one JPG per page, in order, with a zip when you want them all at once.'],
      ['Resolution, in plain terms', 'Standard is plenty when the destination is an upload form or a quick share. High is the pick for anything printed or read closely. Extra high is for pages with small print, like terms sheets and scanned forms. Because a PDF page is drawn fresh at the size you pick, text stays sharp instead of being stretched from a small image.'],
      ['The rest of the family', '<a href="/tools/pdf-to-image/">PDF to Image</a> makes JPG, PNG, or WebP from the same PDF with a format picker. The <a href="/tools/pdf-converter/">PDF Converter</a> page lists every PDF conversion in one place, and <a href="/tools/jpg-to-pdf/">JPG to PDF</a> runs the trip in reverse.'],
    ],
  ],
  'pdf-to-png' => [
    'name' => 'PDF to PNG Converter',
    'title' => 'PDF to PNG Converter | Turn PDF Pages Into PNG Images in Your Browser | Leave It to Bum Bum',
    'desc' => 'Turn every page of a PDF into a crisp PNG image, right in your browser. One image per page, text and lines kept sharp. Files never leave your device. Free to try, no account needed.',
    'h1' => 'PDF to PNG Converter',
    'lede' => 'Every page of your PDF, as a crisp PNG image. Converted in your browser, no uploads.',
    'dropBold' => 'Drop PDFs here',
    'dropTail' => 'Every page becomes a PNG.',
    'dstName' => 'PNG', 'mime' => 'image/png', 'ext' => 'png',
    'dstNote' => 'This page makes PNGs. <a href="/tools/pdf-to-image/">PDF to Image</a> also makes JPG and WebP.',
    'howtoName' => 'How to convert PDF to PNG',
    'howtoDesc' => 'Turn every page of a PDF into a PNG image.',
    'howtoSteps' => ['Drop a PDF into the converter.', 'Pick a resolution.', 'Bum Bum converts every page right in your browser; your file never leaves your device.', 'Download each page, or grab them all in a zip.'],
    'faq' => [
      ['Do I need an account?', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/.', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.'],
      ['Do my PDFs upload anywhere?', 'No. Your files never leave your device. Conversion happens right in your browser.', 'No. Your files never leave your device. Conversion happens right in your browser.'],
      ['What do I get from a multi-page PDF?', 'One PNG per page, in order. Download each page on its own, or grab them all together in a zip file.', 'One PNG per page, in order. Download each page on its own, or grab them all together in a zip file.'],
      ['Why PNG?', 'PNG keeps text and sharp lines perfectly crisp, which suits pages people will read closely, contracts, diagrams, and screenshots of documents. The files are bigger than JPG. For JPG or WebP output, use PDF to Image.', 'PNG keeps text and sharp lines perfectly crisp, which suits pages people will read closely, contracts, diagrams, and screenshots of documents. The files are bigger than JPG. For JPG or WebP output, use <a href="/tools/pdf-to-image/">PDF to Image</a>.'],
      ['Which resolution should I pick?', 'Standard is plenty for forms and uploads. High is the pick for anything printed or read closely, and Extra high is for pages with small print. Bigger means bigger files.', 'Standard is plenty for forms and uploads. High is the pick for anything printed or read closely, and Extra high is for pages with small print. Bigger means bigger files.'],
      ['Why did my PDF fail?', 'Usually because it is password-protected or damaged. Unlock it with the password in your usual PDF app, save an unprotected copy, and drop that in.', 'Usually because it is password-protected or damaged. Unlock it with the password in your usual PDF app, save an unprotected copy, and drop that in.'],
    ],
    'guideTitle' => 'PDF to PNG, the short guide',
    'guide' => [
      ['How it works here', 'Each page of your PDF is drawn in your browser at the resolution you pick, then saved as a PNG. Your file never leaves your device: there is no upload, no server copy, and nothing to delete afterwards. Multi-page PDFs come out as one PNG per page, in order, with a zip when you want them all at once.'],
      ['When PNG is the right call', 'Pick PNG when the page will be read closely or reused: contract pages, diagrams, forms with small print, document screenshots for a report. Text edges stay exact. When the page is headed for a quick upload or an email, JPG is smaller and usually the better trade.'],
      ['The rest of the family', '<a href="/tools/pdf-to-image/">PDF to Image</a> makes JPG, PNG, or WebP from the same PDF with a format picker. The <a href="/tools/pdf-converter/">PDF Converter</a> page lists every PDF conversion in one place, and <a href="/tools/jpg-to-pdf/">JPG to PDF</a> runs the trip in reverse.'],
    ],
  ],
];
$bbSlug = 'pdf-to-image';
if (isset($bbPdfMode) && $bbPdfMode === 'jpg') $bbSlug = 'pdf-to-jpg';
if (isset($bbPdfMode) && $bbPdfMode === 'png') $bbSlug = 'pdf-to-png';
$bbM = $bbModes[$bbSlug];
$bbLocked = $bbSlug !== 'pdf-to-image';
$bbUrl = 'https://leaveittobumbum.com/tools/' . $bbSlug . '/';
$bbMimeJs = $bbLocked ? $bbM['mime'] : '';
$bbFaqLd = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => []];
foreach ($bbM['faq'] as $bbQa) { $bbFaqLd['mainEntity'][] = ['@type' => 'Question', 'name' => $bbQa[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $bbQa[1]]]; }
$bbHowSteps = [];
foreach ($bbM['howtoSteps'] as $bbI => $bbStep) { $bbHowSteps[] = ['@type' => 'HowToStep', 'position' => $bbI + 1, 'name' => $bbStep]; }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title><?= htmlspecialchars($bbM['title']) ?></title>
<meta name="description" content="<?= htmlspecialchars($bbM['desc']) ?>">
<meta property="og:title" content="<?= htmlspecialchars($bbM['title']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($bbM['desc']) ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?= $bbUrl ?>">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/<?= $bbSlug ?>.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="<?= htmlspecialchars($bbM['title']) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($bbM['desc']) ?>">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/<?= $bbSlug ?>.jpg">
<link rel="canonical" href="<?= $bbUrl ?>">
<script type="application/ld+json">
<?= json_encode(['@context' => 'https://schema.org', '@type' => 'SoftwareApplication', 'name' => $bbM['name'], 'applicationCategory' => 'BusinessApplication', 'operatingSystem' => 'Web', 'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD'], 'description' => $bbM['desc'], 'url' => $bbUrl], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?>
</script>
<script type="application/ld+json">
<?= json_encode($bbFaqLd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?>
</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"<?= htmlspecialchars($bbM['name']) ?> | Leave It to Bum Bum","speakable":{"@type":"SpeakableSpecification","cssSelector":["#faq summary","#faq details p"]}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"<?= htmlspecialchars($bbM['name']) ?>","item":"<?= $bbUrl ?>"}]}</script>
<script type="application/ld+json"><?= json_encode(['@context' => 'https://schema.org', '@type' => 'HowTo', 'name' => $bbM['howtoName'], 'description' => $bbM['howtoDesc'], 'step' => $bbHowSteps], JSON_UNESCAPED_SLASHES) ?></script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s;min-height:300px;display:flex;flex-direction:column;align-items:center;justify-content:center}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px;align-items:center}
.controls{display:grid;gap:12px;margin-top:14px}
@media(min-width:760px){.controls{grid-template-columns:1fr 1fr 1fr}}
.controls label{font-weight:700;font-size:14px;display:block}
.controls select{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px}
.controls select:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.controls input[type=range]{width:100%;margin-top:8px;accent-color:#2f2a22}
.time-hint{font-size:13px;opacity:.7;margin-top:4px}
.progress-wrap{margin-top:14px}
.progress-bar{height:10px;border-radius:999px;background:#f0e8d4;overflow:hidden}
#barFill{height:100%;width:0%;border-radius:999px;background:#2f2a22;transition:width .2s}
#progText{font-size:13px;font-weight:700;margin:8px 0 0;opacity:.75}
.error{color:#b3261e;font-weight:700}
.hidden{display:none!important}
.result-block{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:12px;background:#fff;padding:12px;margin-top:12px}
.result-block .meta{font-size:13px;opacity:.75}
.result-head{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.result-head .grow{flex:1;min-width:160px}
.thumbs{display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:10px;margin-top:12px}
.thumbs figure{margin:0}
.thumbs img{width:100%;border-radius:8px;border:1px solid #e7dcc3;background:#f7f2e7;display:block}
.thumbs figcaption{font-size:12px;margin-top:4px;text-align:center}
.dl{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:8px 16px;font-weight:700;font-size:13px;cursor:pointer;text-decoration:none;color:inherit;display:inline-block}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>$bbM['name']]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/pdf-to-image.png" alt="<?= htmlspecialchars($bbM['name']) ?>"><div><h1><?= htmlspecialchars($bbM['h1']) ?></h1><p class="lede bb-desc"><?= htmlspecialchars($bbM['lede']) ?></p></div></div>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to convert PDFs</h2><a class="button" href="/account/?next=<?= urlencode('/tools/' . $bbSlug . '/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/' . $bbSlug . '/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/' . $bbSlug . '/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/' . $bbSlug . '/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>

<section class="panel" id="uploadPanel">
<h2>Pick a PDF</h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Choose PDF files">
<p style="font-size:40px;margin:0">🖼️</p>
<p><b><?= htmlspecialchars($bbM['dropBold']) ?></b>, or pick them below. <?= htmlspecialchars($bbM['dropTail']) ?></p>
<p class="meta" style="font-size:13px;opacity:.75">Password-protected PDFs need to be unlocked first. Everything happens on your device.</p>
</div>
<input type="file" id="fileInput" accept="application/pdf,.pdf" multiple class="hidden">
<div class="btnrow"><button type="button" class="button secondary" id="btnPick">Choose PDFs</button></div>
<div class="controls">
<?php if (!$bbLocked): ?><div><label>Format<select id="formatSel"><option value="image/jpeg">JPG: accepted everywhere</option><option value="image/png">PNG: crispest text</option><option value="image/webp">WebP: smallest files</option></select></label></div>
<?php else: ?><div><label>Format</label><p style="margin:6px 0 0;font-weight:800"><?= htmlspecialchars($bbM['dstName']) ?></p><p class="time-hint"><?= $bbM['dstNote'] ?></p></div>
<?php endif; ?>
<div><label>Resolution<select id="scaleSel"><option value="1">Standard: fine for forms and uploads</option><option value="2" selected>High: crisp for reading and printing</option><option value="3">Extra high: for small print</option></select></label></div>
<div id="qualityWrap"><label>Quality: <span id="qualityVal">90</span>%<input type="range" id="qualityRange" min="50" max="100" step="5" value="90"></label><p class="time-hint">Higher quality means bigger files. 90% is the sweet spot. PNG is always lossless, so quality applies to JPG and WebP.</p></div>
</div>
<div class="btnrow"><button type="button" class="button hidden" id="convertBtn">Convert</button><button type="button" class="button secondary hidden" id="clearBtn">Clear</button></div>
<div class="progress-wrap hidden" id="convProgress"><div class="progress-bar"><div id="barFill"></div></div><p id="progText">Working...</p></div>
<p id="convError" class="error"></p>
<div id="resultList"></div>
</section>
<?php endif; ?>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<?php foreach ($bbM['faq'] as $bbQa): ?><details>
<summary><?= htmlspecialchars($bbQa[0]) ?></summary>
<p><?= $bbQa[2] ?></p>
</details>
<?php endforeach; ?>
</section>
<section class="panel" aria-label="Guide">
<h2 style="margin-top:0"><?= htmlspecialchars($bbM['guideTitle']) ?></h2>
<?php foreach ($bbM['guide'] as $bbG): ?><h3><?= htmlspecialchars($bbG[0]) ?></h3>
<p><?= $bbG[1] ?></p>
<?php endforeach; ?>
</section>
<?php $bbTool = $bbSlug; require dirname(__DIR__, 2) . '/includes/next-tasks.php'; ?>
</main>
<script src="/tools/pdf-merger/vendor/pdf.min.js"></script>
<script src="/tools/pdf-merger/vendor/jszip.min.js"></script>
<script>
const BB_TOOL_KEY='<?= $bbSlug ?>',BB_LOCKED_MIME='<?= $bbMimeJs ?>';
pdfjsLib.GlobalWorkerOptions.workerSrc='/tools/pdf-merger/vendor/pdf.worker.min.js';
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/pdf-to-image.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}
bbTrack('tool_opened',{tool:BB_TOOL_KEY});

const dropzone=document.querySelector('#dropzone'),fileInput=document.querySelector('#fileInput'),convError=document.querySelector('#convError');
const formatSel=document.querySelector('#formatSel'),scaleSel=document.querySelector('#scaleSel'),qualityRange=document.querySelector('#qualityRange'),qualityVal=document.querySelector('#qualityVal'),qualityWrap=document.querySelector('#qualityWrap');
const convertBtn=document.querySelector('#convertBtn'),clearBtn=document.querySelector('#clearBtn'),resultList=document.querySelector('#resultList');
const convProgress=document.querySelector('#convProgress'),barFill=document.querySelector('#barFill'),progText=document.querySelector('#progText');
let files=[],convKey=0;
const EXT={'image/jpeg':'jpg','image/png':'png','image/webp':'webp'};
if(BB_LOCKED_MIME==='image/png'&&qualityWrap)qualityWrap.style.opacity='0.45';

function refreshButtons(){convertBtn.classList.toggle('hidden',!files.length);clearBtn.classList.toggle('hidden',!files.length && !resultList.children.length);convertBtn.textContent=files.length>1?('Convert '+files.length+' PDFs ('+files.length+' actions)'):('Convert this PDF (1 action)')}
qualityRange.addEventListener('input',()=>{qualityVal.textContent=qualityRange.value});
if(formatSel)formatSel.addEventListener('change',()=>{qualityWrap.style.opacity=formatSel.value==='image/png'?'0.45':'1'});

async function probePdf(f){
  const buf=await f.arrayBuffer();
  const doc=await pdfjsLib.getDocument({data:buf}).promise;
  const n=doc.numPages;
  await doc.destroy();
  return n;
}
async function addFiles(list){
  convError.textContent='';
  let added=0;
  for(const f of list){
    if(files.length>=10)break;
    const isPdf=f.type==='application/pdf'||/\.pdf$/i.test(f.name||'');
    if(!isPdf)continue;
    try{await probePdf(f);files.push(f);added++}
    catch(e){
      const block=document.createElement('div');block.className='result-block';
      const why=e&&e.name==='PasswordException'?'It is password-protected. Unlock it first, then drop the unlocked copy in.':'Bum Bum could not read that file. It may be damaged.';
      block.innerHTML='<div class="grow"><b>'+esc(f.name)+'</b><br><span class="meta">'+why+'</span></div>';
      resultList.prepend(block);
    }
  }
  if(added)refreshButtons();
  else if(!files.length && !resultList.children.length)convError.textContent='No readable PDF files in that drop.';
  else if(!added)refreshButtons();
}
if(dropzone){
  dropzone.addEventListener('click',()=>fileInput.click());
  dropzone.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();fileInput.click()}});
  ['dragover','dragenter'].forEach(ev=>dropzone.addEventListener(ev,e=>{e.preventDefault();dropzone.classList.add('over')}));
  ['dragleave','drop'].forEach(ev=>dropzone.addEventListener(ev,e=>{e.preventDefault();dropzone.classList.remove('over')}));
  dropzone.addEventListener('drop',e=>{addFiles(e.dataTransfer.files)});
  document.querySelector('#btnPick').addEventListener('click',()=>fileInput.click());
  fileInput.addEventListener('change',()=>{addFiles(fileInput.files);fileInput.value=''});
}
window.__bbCaptureHandoff=function(f){addFiles([f]);};
clearBtn.addEventListener('click',()=>{files=[];resultList.innerHTML='';convError.textContent='';refreshButtons()});

function canvasBlob(canvas,mime,quality){return new Promise((res,rej)=>{canvas.toBlob(b=>{b?res(b):rej(new Error('encode'))},mime,quality)})}

convertBtn.addEventListener('click',async()=>{
  if(!files.length)return;
  convError.textContent='';convertBtn.disabled=true;convProgress.classList.remove('hidden');
  const mime=BB_LOCKED_MIME?BB_LOCKED_MIME:formatSel.value,ext=EXT[mime],scale=parseFloat(scaleSel.value),quality=qualityRange.value/100;
  const queue=files.slice();files=[];refreshButtons();
  let blocked=false;
  for(const f of queue){
    const base=(f.name||'pdf').replace(/\.pdf$/i,'');
    const block=document.createElement('div');block.className='result-block';
    block.innerHTML='<div class="result-head"><div class="grow"><b>'+esc(f.name)+'</b><br><span class="meta">Converting...</span></div></div>';
    resultList.prepend(block);
    const meta=block.querySelector('.meta');
    try{
      const key='conv-'+Date.now().toString(36)+'-'+(++convKey)+'-'+Math.random().toString(36).slice(2,8);
      const{response,data}=await apiCall({action:'convert',idempotencyKey:key});
      if(response.status===402)throw new Error('actions');
      if(!response.ok)throw new Error(data.error||'metering failed');
      const buf=await f.arrayBuffer();
      const doc=await pdfjsLib.getDocument({data:buf}).promise;
      const total=doc.numPages;
      const pages=[];
      const grid=document.createElement('div');grid.className='thumbs';
      for(let p=1;p<=total;p++){
        const page=await doc.getPage(p);
        const viewport=page.getViewport({scale});
        const canvas=document.createElement('canvas');
        canvas.width=Math.ceil(viewport.width);canvas.height=Math.ceil(viewport.height);
        const ctx=canvas.getContext('2d');
        ctx.fillStyle='#ffffff';ctx.fillRect(0,0,canvas.width,canvas.height);
        await page.render({canvasContext:ctx,viewport}).promise;
        const blob=await canvasBlob(canvas,mime,quality);
        const url=URL.createObjectURL(blob);
        const pageName=base+'-page-'+p+'.'+ext;
        pages.push({name:pageName,blob});
        const fig=document.createElement('figure');
        fig.innerHTML='<img alt="Page '+p+' of '+esc(f.name)+'"><figcaption><a class="dl" download="'+esc(pageName)+'">Page '+p+'</a></figcaption>';
        fig.querySelector('img').src=url;
        fig.querySelector('a').href=url;
        grid.appendChild(fig);
        barFill.style.width=(p/total*100)+'%';
        progText.textContent='Converting '+esc(f.name)+': page '+p+' of '+total+'...';
      }
      await doc.destroy();
      block.appendChild(grid);
      meta.textContent=total+' page'+(total===1?'':'s')+' converted to '+ext.toUpperCase()+'.';
      if(total>1){
        const zipBtn=document.createElement('button');zipBtn.type='button';zipBtn.className='dl';zipBtn.textContent='Download all (zip)';
        zipBtn.addEventListener('click',async()=>{
          zipBtn.disabled=true;zipBtn.textContent='Zipping...';
          const zip=new JSZip();
          pages.forEach(pg=>zip.file(pg.name,pg.blob));
          const zb=await zip.generateAsync({type:'blob'});
          const a=document.createElement('a');a.href=URL.createObjectURL(zb);a.download=base+'-pages.zip';a.click();
          zipBtn.disabled=false;zipBtn.textContent='Download all (zip)';
        });
        block.querySelector('.result-head').appendChild(zipBtn);
      }
      bbTrack('convert_done',{tool:'pdf-to-image',pages:total,format:ext});
      if(window.BBDropStrip)window.BBDropStrip.show('pdf-to-image');
    }catch(e){
      if(e.message==='actions'){blocked=true;meta.textContent='Out of actions for this month.';break}
      meta.textContent='Something went wrong converting that one. Try a smaller PDF or the Standard resolution.';
    }
  }
  barFill.style.width='100%';progText.textContent=blocked?'Stopped: out of actions.':'Done.';
  convertBtn.disabled=false;
  setTimeout(()=>convProgress.classList.add('hidden'),900);
  resultList.scrollIntoView({behavior:'smooth',block:'center'});
});
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script></main><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
