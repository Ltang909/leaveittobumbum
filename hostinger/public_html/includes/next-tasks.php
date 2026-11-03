<?php
// "Next task" cross-promotion strip for tool pages.
//
// The follow-on tools for each page live in ONE hand-kept map,
// tools/next-tasks.json, keyed by tool slug: three tools chosen by real
// task adjacency (the tool that takes this tool's output as its input, or
// finishes the job it started). Names, descriptions, icons, and links come
// from tools/registry.json. Card colors match the toolbox page: registry
// order modulo the 8 card colors.
//
// Usage on a tool page, in place of the old hand-written section:
// set $bbTool to the page's tool slug, then require this file.
//
// Renders nothing when the slug has no map entry, so a page can adopt the
// include before its adjacencies are mapped.
$__bbColors = ['#ffd84d', '#f7a9ca', '#83d6b2', '#8eb5ff', '#ffc59b', '#d6c9f5', '#b2e8dc', '#ffb3b3'];
$__bbRoot = dirname(__DIR__);
$__bbReg = json_decode(@file_get_contents($__bbRoot . '/tools/registry.json'), true);
$__bbMap = json_decode(@file_get_contents($__bbRoot . '/tools/next-tasks.json'), true);
$__bbSlug = isset($bbTool) ? (string) $bbTool : '';
$__bbNext = (is_array($__bbMap) && isset($__bbMap[$__bbSlug]) && is_array($__bbMap[$__bbSlug])) ? $__bbMap[$__bbSlug] : [];
$__bbByKey = [];
if (is_array($__bbReg) && !empty($__bbReg['tools']) && is_array($__bbReg['tools'])) {
    foreach ($__bbReg['tools'] as $__bbI => $__bbT) {
        if (!isset($__bbT['key'])) continue;
        $__bbT['__color'] = $__bbColors[$__bbI % 8];
        $__bbByKey[$__bbT['key']] = $__bbT;
    }
}
$__bbCards = [];
foreach ($__bbNext as $__bbKey) {
    if (isset($__bbByKey[$__bbKey])) $__bbCards[] = $__bbByKey[$__bbKey];
}
if (count($__bbCards) > 0):
?>
<section class="panel" aria-label="More tiny tools">
<style>
.bb-next-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:16px}
.bb-next-card{display:flex;gap:12px;align-items:flex-start;border:2px solid var(--line,#1a291c);border-radius:16px;padding:16px;background:#fff;text-decoration:none;color:inherit;box-shadow:4px 4px 0 var(--line,#1a291c)}
.bb-next-card:hover{transform:translate(-1px,-1px);box-shadow:5px 5px 0 var(--line,#1a291c)}
.bb-next-icon{flex:none;width:44px;height:44px;border:2px solid var(--line,#1a291c);border-radius:12px;display:grid;place-items:center;font-size:20px;line-height:1}
.bb-next-name{display:block;font-weight:900}
.bb-next-desc{display:block;font-size:13.5px;line-height:1.45;opacity:.78;margin-top:2px}
.bb-next-arrow{margin-left:auto;font-weight:900;flex:none}
@media(max-width:860px){.bb-next-grid{grid-template-columns:1fr}}
</style>
<h2 style="margin-top:0">Done here? Your next task is probably one of these</h2>
<div class="bb-next-grid">
<?php foreach ($__bbCards as $__bbT): ?>
<a class="bb-next-card" href="<?= htmlspecialchars($__bbT['url'], ENT_QUOTES, 'UTF-8') ?>" data-next-from="<?= htmlspecialchars($__bbSlug, ENT_QUOTES, 'UTF-8') ?>" data-next-to="<?= htmlspecialchars($__bbT['key'], ENT_QUOTES, 'UTF-8') ?>"><span class="bb-next-icon" style="background:<?= $__bbT['__color'] ?>" aria-hidden="true"><?= htmlspecialchars($__bbT['icon'], ENT_QUOTES, 'UTF-8') ?></span><span class="bb-next-main"><span class="bb-next-name"><?= htmlspecialchars($__bbT['name'], ENT_QUOTES, 'UTF-8') ?></span><span class="bb-next-desc"><?= htmlspecialchars($__bbT['description'], ENT_QUOTES, 'UTF-8') ?></span></span><span class="bb-next-arrow" aria-hidden="true">&#8599;</span></a>
<?php endforeach; ?>
</div>
</section>
<script>document.addEventListener('click',function(e){var t=e.target;var a=t&&t.closest?t.closest('a.bb-next-card'):null;if(a&&window.bbTrack)bbTrack('cross_promo_click',{from_tool:a.getAttribute('data-next-from'),to_tool:a.getAttribute('data-next-to')})});</script>
<?php endif; unset($__bbColors, $__bbRoot, $__bbReg, $__bbMap, $__bbSlug, $__bbNext, $__bbByKey, $__bbCards, $__bbT, $__bbKey, $__bbI); ?>
