<?php
// Guest conversion modal: REMOVED by the 2026-11-17 pricing reset. Guests no
// longer hit an action limit, so there is no out-of-actions prompt to show.
// What remains is the guest analytics identity: guests are identified to
// PostHog so guest work can be tied to the account they later create.
// Included by the shared site header.
$gcGuest = !empty($isGuest) && !empty($guestId);
?>
<?php if ($gcGuest): ?>
<script>
  bbIdentify('guest_<?= htmlspecialchars((string) $guestId, ENT_QUOTES) ?>');
</script>
<?php endif; ?>
<?php unset($gcGuest); ?>
