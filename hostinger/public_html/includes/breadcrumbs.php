<?php
// Breadcrumb trail. Set $crumbTrail = [['label' => '...', 'url' => '...'], ...]
// before including; the last item is the current page (no url).
$trail = isset($crumbTrail) && is_array($crumbTrail) ? $crumbTrail : [];
?>
<nav class="crumbs" aria-label="Breadcrumb"><a href="/">Home</a><?php foreach ($trail as $c): ?><span aria-hidden="true"> › </span><?php if (!empty($c['url'])): ?><a href="<?= htmlspecialchars((string) $c['url']) ?>"><?= htmlspecialchars((string) $c['label']) ?></a><?php else: ?><span aria-current="page"><?= htmlspecialchars((string) $c['label']) ?></span><?php endif; ?><?php endforeach; ?></nav>
