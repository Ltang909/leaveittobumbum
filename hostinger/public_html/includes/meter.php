<?php
// Header usage pill for signed-in users and guests.
// Expects $usage (from subjectUsage()). Guests see their 15 free actions and
// the pill links to signup with the current page preserved.
if (!empty($usage)) :
    $mpGuest = !empty($usage['is_guest']);
    $mpNext = '/account/' . ($mpGuest ? '?next=' . urlencode((string) ($_SERVER['REQUEST_URI'] ?? '/tools/')) : '');
    if (!empty($usage['unlimited'])) : ?>
<a class="usage-pill" href="/account/">Unlimited actions</a>
<?php elseif ((int) $usage['limit'] > 0) : ?>
<a class="usage-pill<?= $mpGuest ? ' guest-pill' : '' ?>" href="<?= $mpNext ?>"<?= $mpGuest ? ' title="Create a free account to keep your actions"' : '' ?>><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?> actions left</a>
<?php endif; endif; unset($mpGuest, $mpNext); ?>
