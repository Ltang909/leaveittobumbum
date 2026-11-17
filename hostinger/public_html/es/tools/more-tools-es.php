<?php
// "More tools in Spanish" cross-link panel for the /es/tools/ pages.
// Set $bbEsTool to the current page's slug before requiring this file.
// Cards link the other localized tools; the last card points at the full
// English toolbox. Renders nothing for an unknown slug.
$__esTools = [
  'heic-to-jpg' => ['Convertir HEIC a JPG', 'Tus fotos de iPhone, como JPG que se abren en todas partes.', '📸'],
  'split-pdf' => ['Dividir PDF', 'Extrae las páginas que necesitas o separa un PDF página por página.', '📄'],
  'pdf-merger' => ['Unir PDF', 'Junta varios PDF en uno solo, en el orden que elijas.', '📑'],
  'jpg-to-pdf' => ['Convertir JPG a PDF', 'Reúne tus imágenes JPG y PNG en un único PDF.', '🖼️'],
  'pdf-compressor' => ['Comprimir PDF', 'Reduce un PDF hasta que quepa en el correo o en el formulario.', '🗜️'],
];
$__esCur = isset($bbEsTool) ? (string) $bbEsTool : '';
if (isset($__esTools[$__esCur])):
?>
<section class="panel" aria-label="Más herramientas en español">
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
<h2 style="margin-top:0">Más herramientas en español</h2>
<div class="bb-next-grid">
<?php foreach ($__esTools as $__esSlug => $__esT): if ($__esSlug === $__esCur) continue; ?>
<a class="bb-next-card" href="/es/tools/<?= $__esSlug ?>/"><span class="bb-next-icon" aria-hidden="true"><?= $__esT[2] ?></span><span class="bb-next-main"><span class="bb-next-name"><?= $__esT[0] ?></span><span class="bb-next-desc"><?= $__esT[1] ?></span></span><span class="bb-next-arrow" aria-hidden="true">&#8599;</span></a>
<?php endforeach; ?>
<a class="bb-next-card" href="/tools/"><span class="bb-next-icon" aria-hidden="true">🧰</span><span class="bb-next-main"><span class="bb-next-name">Todas las herramientas</span><span class="bb-next-desc">La caja de herramientas completa de Bum Bum, en inglés.</span></span><span class="bb-next-arrow" aria-hidden="true">&#8599;</span></a>
</div>
</section>
<?php endif; unset($__esTools, $__esCur, $__esSlug, $__esT); ?>
