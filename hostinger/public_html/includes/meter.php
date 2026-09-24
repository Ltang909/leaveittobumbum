<?php
// Header usage pill for signed-in users. Requires $user and $usage (from usageFor()).
if (!empty($user) && !empty($usage)) :
    if (!empty($usage['unlimited'])) : ?>
<a class="usage-pill" href="/account/">Unlimited actions</a>
<?php elseif ((int) $usage['limit'] > 0) : ?>
<a class="usage-pill" href="/account/"><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?> actions left</a>
<?php endif; endif; ?>
