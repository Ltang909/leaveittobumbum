<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject);
// Shared engine for Image Converter and its locked-output pair pages.
// $bbImageMode is the pair slug (webp-to-png, webp-to-jpg, png-to-webp,
// jpg-to-webp, jpg-to-png); unset means the parent tool with its format picker.
$bbModes = [
  'image-converter' => [
    'name' => 'Image Converter',
    'title' => 'Image Converter | Convert WebP, PNG, and JPG in Your Browser | Leave It to Bum Bum',
    'desc' => 'Convert images between WebP, PNG, and JPG in your browser. Fast, private, files never leave your device. Free to try, no account needed.',
    'h1' => 'Image Converter',
    'lede' => 'Whatever to whatever. Convert WebP, PNG, and JPG images right in your browser.',
    'panel' => 'Pick some images',
    'dropBold' => 'Drop images here',
    'dropMeta' => 'PNG, JPG, WebP, GIF and friends. (Phone HEIC photos are not supported by browsers yet.)',
    'howtoName' => 'How to use Image Converter',
    'howtoDesc' => 'WebP to PNG, JPG to WebP, whatever to whatever.',
    'howtoSteps' => ['Drop an image into Image Converter.', 'Pick the format you want: WebP, PNG, or JPG.', 'Bum Bum converts it right in your browser; your file never leaves your device.', 'Download the converted image.'],
    'faq' => [
      ['Do I need an account?', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/.', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.'],
      ['Do my images upload anywhere?', 'No. Your files never leave your device. Conversion happens right in your browser.', 'No. Your files never leave your device. Conversion happens right in your browser.'],
      ['Which formats are supported?', 'WebP, PNG, and JPG, in any direction.', 'WebP, PNG, and JPG, in any direction.'],
      ['Is there a size limit?', 'If your browser can open it, Image Converter can convert it.', 'If your browser can open it, Image Converter can convert it.'],
    ],
    'guideTitle' => 'Converting images, the short guide',
    'guide' => [
      ['How it works here', 'WebP to PNG, JPG to WebP, PNG to JPG, whatever to whatever, converted right in your browser and handed back in the format you wanted. Your files never leave your device. One action per image converted, and it is batch friendly: line up several images and convert them all the same way. Picking and previewing are free.'],
      ['The two directions', 'Convert to WebP to shrink images for your website while keeping them sharp. Convert from WebP when some upload form refuses it: a screenshot stuck as a WebP becomes a friendly PNG here, which is the format every other tool and form on the internet accepts.'],
      ['Which format when', 'PNG is the safe default almost everything accepts. JPG suits photos and keeps files small. WebP is the pick for your own website, where the smaller file loads faster and modern browsers all support it.'],
      ['Popular conversions', 'Preset pages for the common directions: <a href="/tools/webp-to-png/">WebP to PNG</a>, <a href="/tools/webp-to-jpg/">WebP to JPG</a>, <a href="/tools/png-to-webp/">PNG to WebP</a>, <a href="/tools/jpg-to-webp/">JPG to WebP</a>, <a href="/tools/jpg-to-png/">JPG to PNG</a>, and <a href="/tools/png-to-jpg/">PNG to JPG</a>. The <a href="/tools/png-converter/">PNG Converter</a>, <a href="/tools/jpg-converter/">JPG Converter</a>, and <a href="/tools/webp-converter/">WebP Converter</a> pages list every direction for one format on a single page.'],
    ],
  ],
  'webp-to-png' => [
    'name' => 'WebP to PNG Converter',
    'title' => 'WebP to PNG Converter | Convert WebP Images in Your Browser | Leave It to Bum Bum',
    'desc' => 'Convert WebP images to PNG in your browser. The format every form and tool accepts, with every pixel kept. Files never leave your device. Free to try, no account needed.',
    'h1' => 'WebP to PNG Converter',
    'lede' => 'Forms refuse WebP. Turn WebP images into PNGs every tool accepts, right in your browser.',
    'panel' => 'Pick some WebPs',
    'dropBold' => 'Drop WebP images here',
    'dropMeta' => 'WebPs come in, PNGs go out. Other image types work too and also come out as PNG.',
    'srcName' => 'WebP', 'dstName' => 'PNG', 'mime' => 'image/png', 'ext' => 'png',
    'dstNote' => 'Want JPG or WebP out instead? The <a href="/tools/image-converter/">Image Converter</a> runs every direction.',
    'howtoName' => 'How to convert WebP to PNG',
    'howtoDesc' => 'Turn a WebP image into a PNG in your browser.',
    'howtoSteps' => ['Drop a WebP image into the converter.', 'Bum Bum converts it right in your browser; your file never leaves your device.', 'Download the PNG.'],
    'faq' => [
      ['Do I need an account?', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/.', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.'],
      ['Do my images upload anywhere?', 'No. Your files never leave your device. Conversion happens right in your browser.', 'No. Your files never leave your device. Conversion happens right in your browser.'],
      ['Why would I turn a WebP into a PNG?', 'Some upload forms, editors, and older apps still refuse WebP files. PNG is accepted everywhere, and it keeps the image exactly as it was, pixel for pixel.', 'Some upload forms, editors, and older apps still refuse WebP files. PNG is accepted everywhere, and it keeps the image exactly as it was, pixel for pixel.'],
      ['Will the file get bigger?', 'Usually, yes. PNG keeps every pixel, so the file grows compared to WebP. That is the trade for a format everything accepts.', 'Usually, yes. PNG keeps every pixel, so the file grows compared to WebP. That is the trade for a format everything accepts.'],
      ['Can I convert a batch?', 'Yes, up to 20 images at a time. Each converted image uses one action.', 'Yes, up to 20 images at a time. Each converted image uses one action.'],
    ],
    'guideTitle' => 'WebP to PNG, the short guide',
    'guide' => [
      ['Why make the switch', 'WebP is a website format. It loads fast in browsers, and then you try to upload it somewhere and the form says no. PNG is the format everything else on the internet accepts: editors, marketplaces, application forms, office tools. Converting takes seconds and the picture stays exactly the same.'],
      ['What you gain and what it costs', 'PNG keeps every pixel, including see-through areas, so nothing about the image changes. The cost is file size: a PNG usually weighs more than the WebP it came from. Keep the PNG for using and sharing, and keep the WebP if the image is headed back onto a website.'],
      ['The rest of the family', 'More WebP directions live on the <a href="/tools/webp-converter/">WebP Converter</a> page, and every PNG option is on the <a href="/tools/png-converter/">PNG Converter</a> page. The <a href="/tools/image-converter/">Image Converter</a> runs all three formats with a picker, for anything outside the preset pages.'],
    ],
  ],
  'webp-to-jpg' => [
    'name' => 'WebP to JPG Converter',
    'title' => 'WebP to JPG Converter | Convert WebP Images in Your Browser | Leave It to Bum Bum',
    'desc' => 'Convert WebP images to JPG in your browser. Smaller files that open anywhere, with a quality slider to set the size. Files never leave your device. Free to try, no account needed.',
    'h1' => 'WebP to JPG Converter',
    'lede' => 'Turn WebP images into JPGs that open anywhere, right in your browser. No uploads, ever.',
    'panel' => 'Pick some WebPs',
    'dropBold' => 'Drop WebP images here',
    'dropMeta' => 'WebPs come in, JPGs go out. Other image types work too and also come out as JPG.',
    'srcName' => 'WebP', 'dstName' => 'JPG', 'mime' => 'image/jpeg', 'ext' => 'jpg',
    'dstNote' => 'Want PNG or WebP out instead? The <a href="/tools/image-converter/">Image Converter</a> runs every direction.',
    'howtoName' => 'How to convert WebP to JPG',
    'howtoDesc' => 'Turn a WebP image into a JPG in your browser.',
    'howtoSteps' => ['Drop a WebP image into the converter.', 'Set the quality slider. The default suits most photos and screenshots.', 'Bum Bum converts it right in your browser; your file never leaves your device.', 'Download the JPG.'],
    'faq' => [
      ['Do I need an account?', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/.', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.'],
      ['Do my images upload anywhere?', 'No. Your files never leave your device. Conversion happens right in your browser.', 'No. Your files never leave your device. Conversion happens right in your browser.'],
      ['What happens to transparent parts of a WebP?', 'JPG has no transparency, so see-through areas come out white. If you need the transparency kept, convert to PNG instead.', 'JPG has no transparency, so see-through areas come out white. If you need the transparency kept, convert to <a href="/tools/webp-to-png/">PNG</a> instead.'],
      ['How much smaller will the JPG be?', 'Photos and screenshots usually shrink compared to the WebP, and the slider trades size for detail: lower for a smaller file, higher to keep every edge crisp.', 'Photos and screenshots usually shrink compared to the WebP, and the slider trades size for detail: lower for a smaller file, higher to keep every edge crisp.'],
      ['Can I convert a batch?', 'Yes, up to 20 images at a time. Each converted image uses one action.', 'Yes, up to 20 images at a time. Each converted image uses one action.'],
    ],
    'guideTitle' => 'WebP to JPG, the short guide',
    'guide' => [
      ['Why make the switch', 'A WebP you saved from a website often will not go back into one: upload forms, editors, and older apps ask for JPG. JPG opens in every app and form on the internet, so converting ends the argument and the file usually stays small.'],
      ['Picking a quality', 'The default 90% suits photos and screenshots: visibly the same picture, a fraction of the size. Drop toward 70% when the file only needs to be looked at once. Push toward 100% for images headed to print or further editing.'],
      ['The rest of the family', 'More WebP directions live on the <a href="/tools/webp-converter/">WebP Converter</a> page, and every JPG option is on the <a href="/tools/jpg-converter/">JPG Converter</a> page. The <a href="/tools/image-converter/">Image Converter</a> runs all three formats with a picker, for anything outside the preset pages.'],
    ],
  ],
  'png-to-webp' => [
    'name' => 'PNG to WebP Converter',
    'title' => 'PNG to WebP Converter | Convert PNG Images in Your Browser | Leave It to Bum Bum',
    'desc' => 'Convert PNG images to WebP in your browser. Much smaller files for your website, with transparency kept. Files never leave your device. Free to try, no account needed.',
    'h1' => 'PNG to WebP Converter',
    'lede' => 'Shrink PNGs for your website. Turn them into WebP files, right in your browser.',
    'panel' => 'Pick some PNGs',
    'dropBold' => 'Drop PNG images here',
    'dropMeta' => 'PNGs come in, WebPs go out. Other image types work too and also come out as WebP.',
    'srcName' => 'PNG', 'dstName' => 'WebP', 'mime' => 'image/webp', 'ext' => 'webp',
    'dstNote' => 'Want PNG or JPG out instead? The <a href="/tools/image-converter/">Image Converter</a> runs every direction.',
    'howtoName' => 'How to convert PNG to WebP',
    'howtoDesc' => 'Turn a PNG image into a WebP in your browser.',
    'howtoSteps' => ['Drop a PNG image into the converter.', 'Set the quality slider. The default keeps images looking the same.', 'Bum Bum converts it right in your browser; your file never leaves your device.', 'Download the WebP.'],
    'faq' => [
      ['Do I need an account?', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/.', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.'],
      ['Do my images upload anywhere?', 'No. Your files never leave your device. Conversion happens right in your browser.', 'No. Your files never leave your device. Conversion happens right in your browser.'],
      ['Why turn a PNG into a WebP?', 'WebP keeps the picture looking the same at a much smaller file size, which is why websites use it. Smaller images load faster and cost less to store and serve.', 'WebP keeps the picture looking the same at a much smaller file size, which is why websites use it. Smaller images load faster and cost less to store and serve.'],
      ['Does WebP keep transparency?', 'Yes. See-through areas stay see-through in WebP, and the quality slider controls how much detail is kept.', 'Yes. See-through areas stay see-through in WebP, and the quality slider controls how much detail is kept.'],
      ['Can I convert a batch?', 'Yes, up to 20 images at a time. Each converted image uses one action.', 'Yes, up to 20 images at a time. Each converted image uses one action.'],
    ],
    'guideTitle' => 'PNG to WebP, the short guide',
    'guide' => [
      ['Why make the switch', 'PNG is a heavy format: it keeps every pixel, and product photos and screenshots can weigh megabytes each. On your own website that weight slows pages down. WebP was built for exactly this job, and the same image usually lands at a third of the size or less, looking the same.'],
      ['What to check after converting', 'Open the WebP next to the PNG once and look at edges and text. At the default quality they should match. If a graphic has fine lines, push the slider up a notch. Keep the PNG as your master copy for editing; publish the WebP.'],
      ['The rest of the family', 'More PNG directions live on the <a href="/tools/png-converter/">PNG Converter</a> page, and every WebP option is on the <a href="/tools/webp-converter/">WebP Converter</a> page. The <a href="/tools/image-converter/">Image Converter</a> runs all three formats with a picker, for anything outside the preset pages.'],
    ],
  ],
  'jpg-to-webp' => [
    'name' => 'JPG to WebP Converter',
    'title' => 'JPG to WebP Converter | Convert JPG Images in Your Browser | Leave It to Bum Bum',
    'desc' => 'Convert JPG images to WebP in your browser. Smaller files for your website at the same visible quality. Files never leave your device. Free to try, no account needed.',
    'h1' => 'JPG to WebP Converter',
    'lede' => 'Shrink JPGs for your website. Turn them into WebP files, right in your browser.',
    'panel' => 'Pick some JPGs',
    'dropBold' => 'Drop JPG images here',
    'dropMeta' => 'JPGs come in, WebPs go out. Other image types work too and also come out as WebP.',
    'srcName' => 'JPG', 'dstName' => 'WebP', 'mime' => 'image/webp', 'ext' => 'webp',
    'dstNote' => 'Want PNG or JPG out instead? The <a href="/tools/image-converter/">Image Converter</a> runs every direction.',
    'howtoName' => 'How to convert JPG to WebP',
    'howtoDesc' => 'Turn a JPG image into a WebP in your browser.',
    'howtoSteps' => ['Drop a JPG image into the converter.', 'Set the quality slider. The default keeps photos looking the same.', 'Bum Bum converts it right in your browser; your file never leaves your device.', 'Download the WebP.'],
    'faq' => [
      ['Do I need an account?', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/.', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.'],
      ['Do my images upload anywhere?', 'No. Your files never leave your device. Conversion happens right in your browser.', 'No. Your files never leave your device. Conversion happens right in your browser.'],
      ['Why turn a JPG into a WebP?', 'WebP makes a smaller file than JPG at the same visible quality, which is why websites use it. A page of product photos loads faster and costs less to serve.', 'WebP makes a smaller file than JPG at the same visible quality, which is why websites use it. A page of product photos loads faster and costs less to serve.'],
      ['Will my photo look different?', 'At the default quality, no. The slider trades size for detail: lower for a smaller file, higher to keep faces and fine detail crisp.', 'At the default quality, no. The slider trades size for detail: lower for a smaller file, higher to keep faces and fine detail crisp.'],
      ['Can I convert a batch?', 'Yes, up to 20 images at a time. Each converted image uses one action.', 'Yes, up to 20 images at a time. Each converted image uses one action.'],
    ],
    'guideTitle' => 'JPG to WebP, the short guide',
    'guide' => [
      ['Why make the switch', 'JPG has been the web photo format for decades, and WebP simply compresses better. The same photo at the same visible quality comes out smaller, so pages load faster and image folders take less room. Every modern browser displays WebP, so there is no compatibility catch for your own site.'],
      ['One honest caution', 'Converting a JPG to WebP shrinks the file; it cannot add back detail the JPG already threw away. Start from the best JPG you have, keep it as the master, and publish the WebP. Converting back and forth repeatedly only loses quality.'],
      ['The rest of the family', 'More JPG directions live on the <a href="/tools/jpg-converter/">JPG Converter</a> page, and every WebP option is on the <a href="/tools/webp-converter/">WebP Converter</a> page. The <a href="/tools/image-converter/">Image Converter</a> runs all three formats with a picker, for anything outside the preset pages.'],
    ],
  ],
  'jpg-to-png' => [
    'name' => 'JPG to PNG Converter',
    'title' => 'JPG to PNG Converter | Convert JPG Images in Your Browser | Leave It to Bum Bum',
    'desc' => 'Convert JPG images to PNG in your browser. The format editors prefer, with every pixel kept from here on. Files never leave your device. Free to try, no account needed.',
    'h1' => 'JPG to PNG Converter',
    'lede' => 'Turn JPG photos into PNGs that keep every pixel, right in your browser.',
    'panel' => 'Pick some JPGs',
    'dropBold' => 'Drop JPG images here',
    'dropMeta' => 'JPGs come in, PNGs go out. Other image types work too and also come out as PNG.',
    'srcName' => 'JPG', 'dstName' => 'PNG', 'mime' => 'image/png', 'ext' => 'png',
    'dstNote' => 'Want JPG or WebP out instead? The <a href="/tools/image-converter/">Image Converter</a> runs every direction.',
    'howtoName' => 'How to convert JPG to PNG',
    'howtoDesc' => 'Turn a JPG image into a PNG in your browser.',
    'howtoSteps' => ['Drop a JPG image into the converter.', 'Bum Bum converts it right in your browser; your file never leaves your device.', 'Download the PNG.'],
    'faq' => [
      ['Do I need an account?', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/.', 'No. Convert as a guest with 15 free actions, or create a free account for 75 actions every month. <a href="/pricing/">See pricing</a>.'],
      ['Do my images upload anywhere?', 'No. Your files never leave your device. Conversion happens right in your browser.', 'No. Your files never leave your device. Conversion happens right in your browser.'],
      ['Why turn a JPG into a PNG?', 'PNG is the format editors and design tools prefer for further work, and from here on nothing more is thrown away when you save. It is also the safer pick for graphics with text or sharp lines.', 'PNG is the format editors and design tools prefer for further work, and from here on nothing more is thrown away when you save. It is also the safer pick for graphics with text or sharp lines.'],
      ['Will the picture get sharper?', 'No, and any tool that promises that is stretching the truth. Converting cannot bring back detail the JPG already compressed away. What PNG does is stop further loss from this point on.', 'No, and any tool that promises that is stretching the truth. Converting cannot bring back detail the JPG already compressed away. What PNG does is stop further loss from this point on.'],
      ['Will the file get bigger?', 'Yes, often several times bigger. PNG keeps every pixel instead of compressing like JPG. That trade is worth it for editing, and the wrong one for emailing.', 'Yes, often several times bigger. PNG keeps every pixel instead of compressing like JPG. That trade is worth it for editing, and the wrong one for emailing.'],
      ['Can I convert a batch?', 'Yes, up to 20 images at a time. Each converted image uses one action.', 'Yes, up to 20 images at a time. Each converted image uses one action.'],
    ],
    'guideTitle' => 'JPG to PNG, the short guide',
    'guide' => [
      ['Why make the switch', 'JPG saves space by throwing detail away every time it is saved. That is fine for a finished photo and a poor plan for an image you are about to edit, annotate, or reuse. PNG stops the loss: open it, edit it, save it ten times, and the picture stays put.'],
      ['Set expectations first', 'The PNG will look exactly like the JPG you started with, artefacts and all, and it will weigh a lot more. Convert when the next step is editing or design work. Leave photos as JPG when the next step is sending or uploading them somewhere.'],
      ['The rest of the family', 'More JPG directions live on the <a href="/tools/jpg-converter/">JPG Converter</a> page, and every PNG option is on the <a href="/tools/png-converter/">PNG Converter</a> page. The <a href="/tools/image-converter/">Image Converter</a> runs all three formats with a picker, for anything outside the preset pages.'],
    ],
  ],
];
$bbSlug = isset($bbImageMode) && isset($bbModes[$bbImageMode]) ? $bbImageMode : 'image-converter';
$bbM = $bbModes[$bbSlug];
$bbLocked = $bbSlug !== 'image-converter';
$bbUrl = 'https://leaveittobumbum.com/tools/' . $bbSlug . '/';
$bbMimeJs = $bbLocked ? $bbM['mime'] : '';
$bbExtJs = $bbLocked ? $bbM['ext'] : '';
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
.dropzone{border:2px dashed #d9cdae;border-radius:14px;background:#fffdf8;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s;min-height:500px;display:flex;flex-direction:column;align-items:center;justify-content:center}
@media(max-width:860px){.dropzone{min-height:360px}}
.dropzone:hover,.dropzone.over{border-color:#b3a37e;background:#fff}
.dropzone p{margin:6px 0}
.btnrow{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px;align-items:center}
.controls{display:grid;gap:12px;margin-top:14px}
@media(min-width:760px){.controls{grid-template-columns:1fr 1fr}}
.controls label{font-weight:700;font-size:14px;display:block}
.controls select,.controls input[type=text]{width:100%;padding:10px;border:1px solid #ddd1b8;border-radius:10px;font:inherit;background:#fff;margin-top:4px}
.controls select:focus{border-color:#b3a37e;box-shadow:0 0 0 3px rgba(179,163,126,.18);outline:none}
.controls input[type=range]{width:100%;margin-top:8px;accent-color:#2f2a22}
.time-hint{font-size:13px;opacity:.7;margin-top:4px}
.progress-wrap{margin-top:14px}
.progress-bar{height:10px;border-radius:999px;background:#f0e8d4;overflow:hidden}
#barFill{height:100%;width:0%;border-radius:999px;background:#2f2a22;transition:width .2s}
#progText{font-size:13px;font-weight:700;margin:8px 0 0;opacity:.75}
.error{color:#b3261e;font-weight:700}
.hidden{display:none!important}
.conv-item{display:flex;gap:12px;align-items:center;border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08);border-radius:12px;background:#fff;padding:10px 12px;margin-top:10px;flex-wrap:wrap}
.conv-item img{width:64px;height:64px;object-fit:cover;border-radius:8px;border:1px solid #e7dcc3;background:#f7f2e7}
.conv-item .thumb{display:inline-block;line-height:0;border-radius:8px;cursor:zoom-in}
.conv-item .meta{font-size:13px;opacity:.75}
.conv-item .grow{flex:1;min-width:160px}
.conv-item .dl{border:1px solid #ddd1b8;background:#fff;border-radius:999px;padding:8px 16px;font-weight:700;font-size:13px;cursor:pointer;text-decoration:none;color:inherit;display:inline-block}
.shell .button{box-shadow:0 2px 0 #2f2a22;font-weight:700}
.shell .button:active{box-shadow:none;transform:translateY(2px)}
.shell .button.secondary{box-shadow:none;border:1px solid #ddd1b8}
section.panel{border:1px solid #e2d7bf;box-shadow:0 2px 10px rgba(90,72,38,.08)}
details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}
@media (max-width:860px){main.shell{display:flex;flex-direction:column}main.shell>p.lede.bb-desc{order:40}main.shell>section#faq{order:50}main.shell>section[aria-label="More tiny tools"]{order:60}main.shell>section[aria-label="Guide"]{order:70}}
</style></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>$bbM['name']]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum's toolbox</p><div class="tool-head"><img class="tool-mascot-page" src="/bum/icons/image-converter.png" alt="<?= htmlspecialchars($bbM['name']) ?>"><div><h1><?= htmlspecialchars($bbM['h1']) ?></h1><p class="lede bb-desc"><?= htmlspecialchars($bbM['lede']) ?></p></div></div>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to convert images</h2><a class="button" href="/account/?next=<?= urlencode('/tools/' . $bbSlug . '/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/' . $bbSlug . '/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/' . $bbSlug . '/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage && (int) $usage['used'] >= 2): ?><div class="nudge bb-guest-nudge" data-remaining="<?= (int) $usage['remaining'] ?>"><button type="button" class="bb-guest-nudge-dismiss" aria-label="Dismiss" style="float:right;border:none;background:none;cursor:pointer;font:inherit;font-weight:900;opacity:.55;padding:0 0 0 12px">&times;</button>You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions left. <a href="/account/?next=<?= urlencode('/tools/image-converter/') ?>">Create a free account</a> to save your work and keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>

<section class="panel" id="uploadPanel">
<h2><?= htmlspecialchars($bbM['panel']) ?></h2>
<div class="dropzone" id="dropzone" role="button" tabindex="0" aria-label="Choose image files">
<p style="font-size:40px;margin:0">🖼️</p>
<p><b><?= htmlspecialchars($bbM['dropBold']) ?></b>, or pick them below. Several at once works.</p>
<p class="meta" style="font-size:13px;opacity:.75"><?= htmlspecialchars($bbM['dropMeta']) ?></p>
</div>
<input type="file" id="fileInput" accept="image/*" multiple class="hidden">
<div class="btnrow"><button type="button" class="button secondary" id="btnPick">Choose images</button></div>
<div class="controls">
<?php if (!$bbLocked): ?><div><label>Convert to<select id="formatSel"><option value="image/png">PNG</option><option value="image/jpeg">JPG</option><option value="image/webp">WebP</option></select></label></div>
<div id="qualityWrap"><label>Quality: <span id="qualityVal">90</span>%<input type="range" id="qualityRange" min="10" max="100" step="5" value="90"></label><p class="time-hint">PNG is always lossless, so quality only applies to JPG and WebP.</p></div>
<?php else: ?><div><label>Convert to</label><p style="margin:6px 0 0;font-weight:800"><?= htmlspecialchars($bbM['dstName']) ?></p><p class="time-hint"><?= $bbM['dstNote'] ?></p></div>
<?php if ($bbM['mime'] === 'image/png'): ?><div><label>Quality</label><p class="time-hint" style="margin-top:6px">PNG is lossless, so there is no quality setting. What goes in is what comes out, pixel for pixel.</p></div>
<?php else: ?><div id="qualityWrap"><label>Quality: <span id="qualityVal">90</span>%<input type="range" id="qualityRange" min="10" max="100" step="5" value="90"></label><p class="time-hint">Higher keeps more detail. Lower makes a smaller file.</p></div>
<?php endif; ?>
<?php endif; ?>
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
<script>
const BB_TOOL_KEY='<?= $bbSlug ?>',BB_LOCKED_MIME='<?= $bbMimeJs ?>',BB_LOCKED_EXT='<?= $bbExtJs ?>';
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function apiCall(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/image-converter.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}
bbTrack('tool_opened',{tool:BB_TOOL_KEY});

const dropzone=document.querySelector('#dropzone'),fileInput=document.querySelector('#fileInput'),convError=document.querySelector('#convError');
const formatSel=document.querySelector('#formatSel'),qualityRange=document.querySelector('#qualityRange'),qualityVal=document.querySelector('#qualityVal'),qualityWrap=document.querySelector('#qualityWrap');
const convertBtn=document.querySelector('#convertBtn'),clearBtn=document.querySelector('#clearBtn'),resultList=document.querySelector('#resultList');
const convProgress=document.querySelector('#convProgress'),barFill=document.querySelector('#barFill'),progText=document.querySelector('#progText');
let files=[],convKey=0;
const EXT={'image/png':'png','image/jpeg':'jpg','image/webp':'webp'};
const LOCKED=BB_LOCKED_MIME?{mime:BB_LOCKED_MIME,ext:BB_LOCKED_EXT}:null;

function refreshButtons(){convertBtn.classList.toggle('hidden',!files.length);clearBtn.classList.toggle('hidden',!files.length && !resultList.children.length);convertBtn.textContent=files.length>1?('Convert '+files.length+' images ('+files.length+' actions)'):('Convert this image (1 action)')}
if(qualityRange)qualityRange.addEventListener('input',()=>{qualityVal.textContent=qualityRange.value});
function syncQualityDim(){if(!qualityWrap)return;const m=LOCKED?LOCKED.mime:(formatSel?formatSel.value:'');qualityWrap.style.opacity=m==='image/png'?'0.45':'1'}
if(formatSel)formatSel.addEventListener('change',syncQualityDim);
if(LOCKED)syncQualityDim();

if(dropzone){
  dropzone.addEventListener('click',()=>fileInput.click());
  dropzone.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();fileInput.click()}});
  ['dragover','dragenter'].forEach(ev=>dropzone.addEventListener(ev,e=>{e.preventDefault();dropzone.classList.add('over')}));
  ['dragleave','drop'].forEach(ev=>dropzone.addEventListener(ev,e=>{e.preventDefault();dropzone.classList.remove('over')}));
  dropzone.addEventListener('drop',e=>{addFiles(e.dataTransfer.files)});
  document.querySelector('#btnPick').addEventListener('click',()=>fileInput.click());
  fileInput.addEventListener('change',()=>{addFiles(fileInput.files);fileInput.value=''});
}
function addFiles(list){convError.textContent='';let added=0;for(const f of list){if(!f.type.startsWith('image/'))continue;if(files.length>=20)break;files.push(f);added++}if(added)refreshButtons();else if(!files.length)convError.textContent='No image files in that drop.';else convError.textContent='Up to 20 images at a time.'}
window.__bbCaptureHandoff=function(f){addFiles([f]);};
clearBtn.addEventListener('click',()=>{files=[];resultList.innerHTML='';convError.textContent='';refreshButtons()});

function loadImage(f){return new Promise((res,rej)=>{const url=URL.createObjectURL(f);const img=new Image();img.onload=()=>{URL.revokeObjectURL(url);res(img)};img.onerror=()=>{URL.revokeObjectURL(url);rej(new Error('decode'))};img.src=url})}
function convertOne(img,mime,quality){return new Promise((res,rej)=>{const c=document.createElement('canvas');c.width=img.naturalWidth;c.height=img.naturalHeight;const ctx=c.getContext('2d');if(mime==='image/jpeg'){ctx.fillStyle='#ffffff';ctx.fillRect(0,0,c.width,c.height)}ctx.drawImage(img,0,0);c.toBlob(b=>{b?res(b):rej(new Error('encode'))},mime,quality)})}

convertBtn.addEventListener('click',async()=>{
  if(!files.length)return;
  convError.textContent='';convertBtn.disabled=true;convProgress.classList.remove('hidden');
  const mime=LOCKED?LOCKED.mime:formatSel.value,ext=LOCKED?LOCKED.ext:EXT[mime],quality=qualityRange?qualityRange.value/100:0.92;
  const total=files.length;let done=0,blocked=false,okCount=0;
  for(const f of files){
    done++;barFill.style.width=(done/total*90)+'%';progText.textContent='Converting '+done+' of '+total+'...';
    const row=document.createElement('div');row.className='conv-item';
    try{
      const key='conv-'+Date.now().toString(36)+'-'+(++convKey)+'-'+done;
      const{response,data}=await apiCall({action:'convert',idempotencyKey:key});
      if(response.status===402)throw new Error('actions');
      if(!response.ok)throw new Error(data.error||'metering failed');
      const img=await loadImage(f);
      const blob=await convertOne(img,mime,quality);
      const url=URL.createObjectURL(blob);
      const base=(f.name||'image').replace(/\.[^.]+$/,'');
      row.innerHTML='<a class="thumb" target="_blank" rel="noopener" title="Open full-size preview"><img alt="Converted image preview"></a><div class="grow"><b>'+esc(base+'.'+ext)+'</b><br><span class="meta">'+esc(f.name)+' &rarr; '+(blob.size/1024).toFixed(0)+' KB</span></div>';
      const thumb=row.querySelector('.thumb');thumb.href=url;thumb.querySelector('img').src=url;
      const a=document.createElement('a');a.className='dl';a.href=url;a.download=base+'.'+ext;a.textContent='Download';
      row.appendChild(a);resultList.appendChild(row);
      bbTrack('convert_done',{from:f.type,to:mime});
      okCount++;
    }catch(e){
      if(e.message==='actions'){blocked=true;row.innerHTML='<div class="grow"><b>'+esc(f.name)+'</b><br><span class="meta">Out of actions for this month.</span></div>';resultList.appendChild(row);break}
      row.innerHTML='<div class="grow"><b>'+esc(f.name)+'</b><br><span class="meta">Bum Bum could not read that file. Try a PNG or JPG?</span></div>';resultList.appendChild(row);
    }
  }
  barFill.style.width='100%';progText.textContent=blocked?'Stopped: out of actions.':'Done.';
  if(okCount>0&&window.BBDropStrip)window.BBDropStrip.show('image-converter');
  files=[];refreshButtons();convertBtn.disabled=false;
  setTimeout(()=>convProgress.classList.add('hidden'),900);
  resultList.scrollIntoView({behavior:'smooth',block:'center'});
});
</script><script src="/tools/capture-handoff.js?v=20261026r5"></script></main><script src="/tools/drop-strip.js?v=20261111r1"></script></body></html>
