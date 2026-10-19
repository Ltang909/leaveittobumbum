<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><meta name="robots" content="noindex,nofollow"><title>Error Scenes Preview | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css?v=7"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700;9..144,900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?><style>
.es-demo-label{font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;font-size:13px;font-weight:700;background:var(--cream,#f6f0df);border:2px solid var(--line,#1a291c);border-radius:999px;display:inline-block;padding:5px 12px;margin:0 0 18px}
.es-demo .es-scene{margin-top:2px}
</style></head><body><?php require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><p class="eyebrow">Internal preview</p><h1>Error scenes</h1><p class="lede">Every tool error state, rendered by the one shared renderer (tools/error-scenes.js) from the copy in docs/error-scenes-spec.md. Pet Pet reports it, Bum Bum handles it. The buttons are inert here; in a live tool each button runs that state&#8217;s retry.</p>

<section class="panel es-demo"><p class="es-demo-label">empty_input &middot; compact</p><div id="demo-empty-compact"></div></section>

<section class="panel es-demo"><p class="es-demo-label">empty_input &middot; full (repeat failure escalation)</p><div id="demo-empty-full"></div></section>

<section class="panel es-demo"><p class="es-demo-label">upload_failed &middot; full</p><div id="demo-upload"></div></section>

<section class="panel es-demo"><p class="es-demo-label">file_too_large &middot; full</p><div id="demo-toolarge"></div></section>

<section class="panel es-demo"><p class="es-demo-label">connection_lost &middot; full</p><div id="demo-offline"></div></section>

<section class="panel es-demo"><p class="es-demo-label">processing_failed &middot; full (placeholder art until the smoking machine image exists)</p><div id="demo-processing"></div></section>

<section class="panel es-demo"><p class="es-demo-label">invalid_type &middot; compact (sample format list filled in)</p><div id="demo-invalid"></div></section>

<section class="panel es-demo"><p class="es-demo-label">purr_code empty link &middot; compact (copy swap)</p><div id="demo-purr-empty"></div></section>

<section class="panel es-demo"><p class="es-demo-label">purr_code dead link &middot; compact (warning shown under a finished code)</p><div id="demo-purr-dead"></div></section>

</main><?php require dirname(__DIR__, 2) . '/includes/site-footer.php'; ?><script src="/tools/error-scenes.js"></script><script>
BBErrorScenes.mount(document.getElementById("demo-empty-compact"), "empty_input", { size: "compact" });
BBErrorScenes.mount(document.getElementById("demo-empty-full"), "empty_input", { size: "full" });
BBErrorScenes.mount(document.getElementById("demo-upload"), "upload_failed");
BBErrorScenes.mount(document.getElementById("demo-toolarge"), "file_too_large");
BBErrorScenes.mount(document.getElementById("demo-offline"), "connection_lost");
BBErrorScenes.mount(document.getElementById("demo-processing"), "processing_failed");
BBErrorScenes.mount(document.getElementById("demo-invalid"), "invalid_type", { size: "compact", bumbum: "That format is not one I can open. Try one of these instead: PNG, JPG, or WebP." });
BBErrorScenes.mount(document.getElementById("demo-purr-empty"), "empty_input", { size: "compact", petpet: "There is no link in this.", bumbum: "A Purr Code needs somewhere to point. Paste a link and I will do the rest.", cta: "Add a link" });
BBErrorScenes.mount(document.getElementById("demo-purr-dead"), "empty_input", { size: "compact", petpet: "That page does not exist. I checked twice.", bumbum: "The code would still work, it would just take people to a dead end. Fix the link and I will remake it.", cta: "Fix the link" });
</script></body></html>
