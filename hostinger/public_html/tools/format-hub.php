<?php require dirname(__DIR__) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject);
// Shared renderer for the format hub pages (/tools/<format>-converter/).
// A hub takes no file input: it routes a search arrival to the exact pair
// page, where the format choice is already made. $bbHub is the format key.
$bbHubs = [
  'png' => [
    'name' => 'PNG Converter',
    'title' => 'PNG Converter | Convert PNG Images To and From Other Formats | Leave It to Bum Bum',
    'desc' => 'Every PNG conversion in one place: PNG to JPG, PNG to WebP, and WebP, JPG, or HEIC into PNG. Each converter runs in your browser; files never leave your device.',
    'h1' => 'PNG Converter',
    'lede' => 'Every PNG conversion in one place. Pick the direction; the format is already set.',
    'icon' => '/bum/icons/image-converter.png',
    'groups' => [
      ['Convert PNG to', [
        ['/tools/png-to-jpg/', 'PNG to JPG Converter', 'Turn PNG images into smaller JPGs that open anywhere.'],
        ['/tools/png-to-webp/', 'PNG to WebP Converter', 'Turn PNG images into much smaller WebP files for your website.'],
      ]],
      ['Convert to PNG', [
        ['/tools/webp-to-png/', 'WebP to PNG Converter', 'Turn WebP images into PNGs every form and tool accepts.'],
        ['/tools/jpg-to-png/', 'JPG to PNG Converter', 'Turn JPG photos into PNGs that keep every pixel.'],
        ['/tools/heic-to-png/', 'HEIC to PNG Converter', 'Turn iPhone HEIC photos into PNGs.'],
      ]],
      ['The full tool', [
        ['/tools/image-converter/', 'Image Converter', 'Any image in, PNG, JPG, or WebP out, with a format picker for anything outside the preset pages.'],
        ['/tools/jpg-to-pdf/', 'JPG to PDF', 'Gather PNG images (and JPGs) into one tidy PDF, in your order.'],
      ]],
    ],
    'faq' => [
      ['Do the converters upload my files?', 'No. Every converter on this page runs in your browser, on your device. Your files are never sent to Bum Bum or anyone else.'],
      ['Which PNG conversion do most people need?', 'PNG to JPG, for sending and uploading: the file shrinks a lot and JPG opens everywhere. PNG to WebP is the pick for images going on your own website.'],
      ['Does converting cost anything?', 'Conversions use actions: guests get 15 free actions, and a free account gets 75 every month. The HEIC to PNG converter is free and uses no actions.'],
    ],
    'guide' => [
      ['How these pages work', 'Each converter above is preset: the page you land on already knows the input and output formats, so the job is drop, convert, download. Nothing to configure, and nothing uploads. If your direction is not listed, the Image Converter at the bottom runs all three formats with a picker.'],
      ['PNG, in one paragraph', 'PNG keeps every pixel and supports see-through areas, which makes it the format for editing, screenshots, and graphics. It is also heavy, which is why most PNG conversions are about shrinking the file (to JPG for sending, to WebP for websites) or about escaping a format something else refused (WebP and HEIC into PNG).'],
    ],
  ],
  'jpg' => [
    'name' => 'JPG Converter',
    'title' => 'JPG Converter | Convert JPG Images To and From Other Formats | Leave It to Bum Bum',
    'desc' => 'Every JPG conversion in one place: JPG to PNG, JPG to WebP, and WebP, PNG, or HEIC into JPG. Each converter runs in your browser; files never leave your device.',
    'h1' => 'JPG Converter',
    'lede' => 'Every JPG conversion in one place. Pick the direction; the format is already set.',
    'icon' => '/bum/icons/image-converter.png',
    'groups' => [
      ['Convert JPG to', [
        ['/tools/jpg-to-png/', 'JPG to PNG Converter', 'Turn JPG photos into PNGs that keep every pixel.'],
        ['/tools/jpg-to-webp/', 'JPG to WebP Converter', 'Turn JPG photos into smaller WebP files for your website.'],
        ['/tools/jpg-to-pdf/', 'JPG to PDF', 'Gather JPG images into one tidy PDF, in your order.'],
      ]],
      ['Convert to JPG', [
        ['/tools/webp-to-jpg/', 'WebP to JPG Converter', 'Turn WebP images into JPGs that open anywhere.'],
        ['/tools/png-to-jpg/', 'PNG to JPG Converter', 'Turn PNG images into smaller JPGs that open anywhere.'],
        ['/tools/heic-to-jpg/', 'HEIC to JPG Converter', 'Turn iPhone HEIC photos into JPGs.'],
      ]],
      ['The full tool', [
        ['/tools/image-converter/', 'Image Converter', 'Any image in, PNG, JPG, or WebP out, with a format picker for anything outside the preset pages.'],
      ]],
    ],
    'faq' => [
      ['Do the converters upload my files?', 'No. Every converter on this page runs in your browser, on your device. Your files are never sent to Bum Bum or anyone else.'],
      ['Which JPG conversion do most people need?', 'Into JPG, from whatever a phone or a website handed them: HEIC from an iPhone, WebP saved from a page, PNG screenshots that are too big to send. Out of JPG, WebP is the pick for websites and PNG is the pick for editing.'],
      ['Does converting cost anything?', 'Conversions use actions: guests get 15 free actions, and a free account gets 75 every month. JPG to PDF and the HEIC to JPG converter are free and use no actions.'],
    ],
    'guide' => [
      ['How these pages work', 'Each converter above is preset: the page you land on already knows the input and output formats, so the job is drop, convert, download. Nothing to configure, and nothing uploads. If your direction is not listed, the Image Converter at the bottom runs all three formats with a picker.'],
      ['JPG, in one paragraph', 'JPG is the universal photo format: small files, opens in every app, form, and email client on the internet. It saves space by throwing a little detail away each time it is saved, which is why conversions out of JPG are usually about stopping that loss (PNG for editing) or shrinking further (WebP for websites), and conversions into JPG are about ending a format argument.'],
    ],
  ],
  'webp' => [
    'name' => 'WebP Converter',
    'title' => 'WebP Converter | Convert WebP Images To and From Other Formats | Leave It to Bum Bum',
    'desc' => 'Every WebP conversion in one place: WebP to PNG, WebP to JPG, and PNG or JPG into WebP. Each converter runs in your browser; files never leave your device.',
    'h1' => 'WebP Converter',
    'lede' => 'Every WebP conversion in one place. Pick the direction; the format is already set.',
    'icon' => '/bum/icons/image-converter.png',
    'groups' => [
      ['Convert WebP to', [
        ['/tools/webp-to-png/', 'WebP to PNG Converter', 'Turn WebP images into PNGs every form and tool accepts.'],
        ['/tools/webp-to-jpg/', 'WebP to JPG Converter', 'Turn WebP images into JPGs that open anywhere.'],
      ]],
      ['Convert to WebP', [
        ['/tools/png-to-webp/', 'PNG to WebP Converter', 'Turn PNG images into much smaller WebP files for your website.'],
        ['/tools/jpg-to-webp/', 'JPG to WebP Converter', 'Turn JPG photos into smaller WebP files for your website.'],
      ]],
      ['The full tool', [
        ['/tools/image-converter/', 'Image Converter', 'Any image in, PNG, JPG, or WebP out, with a format picker for anything outside the preset pages.'],
      ]],
    ],
    'faq' => [
      ['Do the converters upload my files?', 'No. Every converter on this page runs in your browser, on your device. Your files are never sent to Bum Bum or anyone else.'],
      ['Why does WebP cause trouble?', 'WebP is a website format: browsers display it happily, and then upload forms, editors, and older apps refuse it. Converting to PNG or JPG ends the argument. Going the other way, WebP makes image files much smaller for your own website.'],
      ['Does converting cost anything?', 'Conversions use actions: guests get 15 free actions, and a free account gets 75 every month.'],
    ],
    'guide' => [
      ['How these pages work', 'Each converter above is preset: the page you land on already knows the input and output formats, so the job is drop, convert, download. Nothing to configure, and nothing uploads. If your direction is not listed, the Image Converter at the bottom runs all three formats with a picker.'],
      ['WebP, in one paragraph', 'WebP was built by Google for websites: the same picture at a much smaller file size, with transparency kept. Every modern browser shows it. The friction is everywhere else: forms, editors, and marketplaces that still ask for PNG or JPG. This page covers both trips, out of WebP when something refused your file, and into WebP when your website needs smaller images.'],
    ],
  ],
  'heic' => [
    'name' => 'HEIC Converter',
    'title' => 'HEIC Converter | Convert iPhone HEIC Photos to JPG, PNG, or PDF | Leave It to Bum Bum',
    'desc' => 'Convert iPhone HEIC photos to JPG, PNG, or a single PDF, right in your browser. Free, no sign-up, and your photos never leave your device.',
    'h1' => 'HEIC Converter',
    'lede' => 'Your iPhone photos, in formats everything else accepts. Free, in your browser.',
    'icon' => '/bum/icons/heic-to-jpg.png',
    'groups' => [
      ['Convert HEIC to', [
        ['/tools/heic-to-jpg/', 'HEIC to JPG Converter', 'Turn iPhone HEIC photos into JPGs that open anywhere.'],
        ['/tools/heic-to-png/', 'HEIC to PNG Converter', 'Turn iPhone HEIC photos into PNGs that keep every pixel.'],
        ['/tools/heic-to-pdf/', 'HEIC to PDF Converter', 'Turn a set of iPhone HEIC photos into one tidy PDF.'],
      ]],
      ['After converting', [
        ['/tools/jpg-to-pdf/', 'JPG to PDF', 'Gather the converted JPGs into one PDF, in your order.'],
        ['/tools/image-converter/', 'Image Converter', 'Convert the JPGs and PNGs onward, to WebP for a website or between formats.'],
      ]],
    ],
    'faq' => [
      ['Is the HEIC converter really free?', 'Yes. The HEIC converters use no actions and need no account. Drop your photos in and download the converted files.'],
      ['Do my photos upload anywhere?', 'No. Conversion happens right in your browser, on your device. Your photos are never sent to Bum Bum or anyone else.'],
      ['Why do my iPhone photos end in .heic?', 'HEIC is the photo format iPhones have saved in since iOS 11. It keeps files small on the phone, but many websites, upload forms, and Windows PCs still cannot open it. JPG and PNG open everywhere.'],
    ],
    'guide' => [
      ['One tool, three outfits', 'The three converters above are the same free tool with the output preset: JPG for sharing and uploading, PNG when every pixel matters, PDF when a whole set should travel as one file. Pick the page for the format you need and the choice is already made.'],
      ['HEIC, in one paragraph', 'HEIC keeps iPhone photos small on the phone, and that is where its advantages end. The moment a photo needs to go into a form, a listing, a shared drive, or a Windows PC, it needs to become JPG or PNG first. Converting on your own device means the photos never sit on anyone else\'s server while that happens.'],
    ],
  ],
  'pdf' => [
    'name' => 'PDF Converter',
    'title' => 'PDF Converter | Convert PDFs To and From Images and Other PDFs | Leave It to Bum Bum',
    'desc' => 'Every PDF conversion in one place: PDF pages to JPG or PNG, images into PDF, plus merge, split, and compress. Each tool runs in your browser; files never leave your device.',
    'h1' => 'PDF Converter',
    'lede' => 'Every PDF conversion in one place. Pick the job; the tool is already set up for it.',
    'icon' => '/bum/icons/pdf-to-image.png',
    'groups' => [
      ['Convert PDF to', [
        ['/tools/pdf-to-jpg/', 'PDF to JPG Converter', 'Turn PDF pages into JPG images, one per page.'],
        ['/tools/pdf-to-png/', 'PDF to PNG Converter', 'Turn PDF pages into crisp PNG images, one per page.'],
      ]],
      ['Convert to PDF', [
        ['/tools/jpg-to-pdf/', 'JPG to PDF', 'Gather JPG and PNG images into one tidy PDF, in your order.'],
        ['/tools/heic-to-pdf/', 'HEIC to PDF Converter', 'Turn a set of iPhone HEIC photos into one tidy PDF.'],
      ]],
      ['More PDF tools', [
        ['/tools/pdf-to-image/', 'PDF to Image', 'PDF pages to JPG, PNG, or WebP with a format and resolution picker.'],
        ['/tools/pdf-merger/', 'Merge PDF', 'Stitch several PDFs into one, in your order.'],
        ['/tools/pdf-compressor/', 'PDF Compressor', 'Shrink a PDF until it fits the email or upload limit.'],
        ['/tools/split-pdf/', 'Split PDF', 'Pull the pages you need out of a PDF, or split it into separate files.'],
      ]],
    ],
    'faq' => [
      ['Do the converters upload my files?', 'No. Every tool on this page runs in your browser, on your device. Your files are never sent to Bum Bum or anyone else.'],
      ['Which PDF conversion do most people need?', 'PDF to JPG, when a form or portal wants an image instead of a document. After that, JPG to PDF for gathering scans and photos into one file, and Merge PDF for combining documents.'],
      ['Does converting cost anything?', 'Most PDF tools use actions: guests get 15 free actions, and a free account gets 75 every month. JPG to PDF, Split PDF, and the HEIC to PDF converter are free and use no actions.'],
    ],
    'guide' => [
      ['How these pages work', 'Each tool above is preset for one job: the page you land on already knows what comes in and what goes out, so the work is drop, convert, download. Nothing uploads, and a multi-page PDF keeps its page order everywhere it matters.'],
      ['PDF, in one paragraph', 'PDF is the format documents travel in, and the friction starts when the other side wants something else: an image for a form, one file instead of six, a smaller file for an email limit. This page gathers the whole family: out of PDF into images, into PDF from images and phone photos, and the reshaping tools (merge, split, compress) for documents that stay PDF.'],
    ],
  ],
];
$bbKey = isset($bbHub) && isset($bbHubs[$bbHub]) ? $bbHub : 'png';
$bbH = $bbHubs[$bbKey];
$bbSlug = $bbKey . '-converter';
$bbUrl = 'https://leaveittobumbum.com/tools/' . $bbSlug . '/';
$bbItems = [];
foreach ($bbH['groups'] as $bbG) { foreach ($bbG[1] as $bbC) { $bbItems[] = ['@type' => 'ListItem', 'position' => count($bbItems) + 1, 'name' => $bbC[1], 'url' => 'https://leaveittobumbum.com' . $bbC[0]]; } }
$bbFaqLd = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => []];
foreach ($bbH['faq'] as $bbQa) { $bbFaqLd['mainEntity'][] = ['@type' => 'Question', 'name' => $bbQa[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $bbQa[1]]]; }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no,date=no,address=no,email=no"><title><?= htmlspecialchars($bbH['title']) ?></title>
<meta name="description" content="<?= htmlspecialchars($bbH['desc']) ?>">
<meta property="og:title" content="<?= htmlspecialchars($bbH['title']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($bbH['desc']) ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?= $bbUrl ?>">
<meta property="og:image" content="https://leaveittobumbum.com/bum/og/<?= $bbSlug ?>.jpg">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="<?= htmlspecialchars($bbH['title']) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($bbH['desc']) ?>">
<meta name="twitter:image" content="https://leaveittobumbum.com/bum/og/<?= $bbSlug ?>.jpg">
<link rel="canonical" href="<?= $bbUrl ?>">
<script type="application/ld+json"><?= json_encode(['@context' => 'https://schema.org', '@type' => 'WebPage', 'name' => $bbH['name'] . ' | Leave It to Bum Bum', 'description' => $bbH['desc'], 'url' => $bbUrl], JSON_UNESCAPED_SLASHES) ?></script>
<script type="application/ld+json"><?= json_encode(['@context' => 'https://schema.org', '@type' => 'ItemList', 'name' => $bbH['name'], 'itemListElement' => $bbItems], JSON_UNESCAPED_SLASHES) ?></script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Toolbox","item":"https://leaveittobumbum.com/tools/"},{"@type":"ListItem","position":2,"name":"<?= htmlspecialchars($bbH['name']) ?>","item":"<?= $bbUrl ?>"}]}</script>
<script type="application/ld+json">
<?= json_encode($bbFaqLd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?>
</script><link rel="stylesheet" href="/app.css?v=11"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__) . '/includes/analytics.php'; ?><style>
h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}
.hub-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-top:16px}
@media(max-width:860px){.hub-grid{grid-template-columns:1fr}}
.hub-card{display:flex;gap:12px;align-items:flex-start;border:1px solid #e2d7bf;border-radius:12px;padding:16px;background:#fff;text-decoration:none;color:inherit;box-shadow:0 2px 10px rgba(90,72,38,.08)}
.hub-card:hover{border-color:#b3a37e}
.hub-card .hub-name{display:block;font-weight:900;font-size:17px}
.hub-card .hub-desc{display:block;font-size:14px;line-height:1.5;opacity:.78;margin-top:3px}
.hub-card .hub-arrow{margin-left:auto;font-weight:900;flex:none}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
</style></head><body><?php require dirname(__DIR__) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>$bbH['name']]]; require dirname(__DIR__)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="<?= $bbH['icon'] ?>" alt="<?= htmlspecialchars($bbH['name']) ?>"><div><h1><?= htmlspecialchars($bbH['h1']) ?></h1><p class="lede bb-desc"><?= htmlspecialchars($bbH['lede']) ?></p></div></div>
<?php foreach ($bbH['groups'] as $bbG): ?><section class="panel" aria-label="<?= htmlspecialchars($bbG[0]) ?>">
<h2 style="margin-top:0"><?= htmlspecialchars($bbG[0]) ?></h2>
<div class="hub-grid">
<?php foreach ($bbG[1] as $bbC): ?><a class="hub-card" href="<?= $bbC[0] ?>"><span><span class="hub-name"><?= htmlspecialchars($bbC[1]) ?></span><span class="hub-desc"><?= htmlspecialchars($bbC[2]) ?></span></span><span class="hub-arrow" aria-hidden="true">&#8599;</span></a>
<?php endforeach; ?>
</div>
</section>
<?php endforeach; ?>
<section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<?php foreach ($bbH['faq'] as $bbQa): ?><details>
<summary><?= htmlspecialchars($bbQa[0]) ?></summary>
<p><?= htmlspecialchars($bbQa[1]) ?></p>
</details>
<?php endforeach; ?>
</section>
<section class="panel" aria-label="Guide">
<h2 style="margin-top:0"><?= htmlspecialchars($bbH['name']) ?>, the short guide</h2>
<?php foreach ($bbH['guide'] as $bbG): ?><h3><?= htmlspecialchars($bbG[0]) ?></h3>
<p><?= $bbG[1] ?></p>
<?php endforeach; ?>
</section>
</main><?php require dirname(__DIR__) . '/includes/site-footer.php'; ?>
</body></html>
