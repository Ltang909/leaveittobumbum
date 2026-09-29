<?php
// Guest conversion modal: shown when a guest hits the 15-action limit
// (bb:signup-required event from any /api/tools/ response carrying
// signup_required: true). Included by the shared site header.
$gcGuest = !empty($isGuest) && !empty($guestId);
$gcNext = urlencode((string) ($_SERVER['REQUEST_URI'] ?? '/tools/'));
?>
<div id="bbGuestModal" style="position:fixed;inset:0;z-index:200;display:none;align-items:center;justify-content:center;padding:20px;background:rgba(30,35,33,.55)">
  <div class="upgrade-card" style="max-width:440px;margin:0;text-align:center">
    <img src="/bum/favicon-cat.png" alt="Bum Bum the cat" style="width:64px;height:64px">
    <h2>You are out of free actions</h2>
    <p class="lede" id="bbGuestModalText">You used all 15 free actions. Create a free account to keep going.</p>
    <p><a class="button" id="bbGuestModalCta" href="/account/?next=<?= $gcNext ?>">Create a free account</a></p>
    <p><button type="button" class="button secondary" id="bbGuestModalLater" style="border:none;background:none;cursor:pointer;font:inherit;opacity:.7">Not yet</button></p>
  </div>
</div>
<script>
(function(){
  var modal = document.getElementById('bbGuestModal');
  if (!modal) return;
  var shown = false;
  function show(usage) {
    if (shown) return;
    shown = true;
    window.bbGuestAtLimit = true;
    var used = usage && typeof usage.used !== 'undefined' ? usage.used : 15;
    var limit = usage && typeof usage.limit !== 'undefined' ? usage.limit : 15;
    var text = document.getElementById('bbGuestModalText');
    if (text) text.textContent = 'You used all ' + used + ' free actions. Create a free account to keep going.';
    modal.style.display = 'flex';
    try { bbTrack('guest_signup_prompt_shown', {used: used}); } catch (e) {}
    // Tool pages inject their own (signed-in) upgrade card into #upgrade-slot
    // on a 402; it runs after this handler, so swap it for the guest card a
    // beat later. The modal overlay covers the swap.
    setTimeout(function(){
      var next = encodeURIComponent(location.pathname + location.search);
      var html = '<div class="upgrade-card"><h2>Out of free actions.</h2>' +
        '<p class="lede">You used all ' + used + ' of your ' + limit + ' free actions. ' +
        '<a href="/account/?next=' + next + '">Create a free account</a> to keep going.</p>' +
        '<p><a class="button" href="/account/?next=' + next + '">Create a free account</a></p></div>';
      document.querySelectorAll('#upgrade-slot').forEach(function(slot){ slot.innerHTML = html; });
    }, 60);
  }
  function hide() { modal.style.display = 'none'; }
  document.getElementById('bbGuestModalLater').addEventListener('click', hide);
  modal.addEventListener('click', function(e){ if (e.target === modal) hide(); });
  document.getElementById('bbGuestModalCta').addEventListener('click', function(){
    try { bbTrack('guest_signup_clicked', {}); } catch (e) {}
  });
  document.addEventListener('bb:signup-required', function(e){ show(e.detail && e.detail.usage); });
  <?php if ($gcGuest): ?>
  bbIdentify('guest_<?= htmlspecialchars((string) $guestId, ENT_QUOTES) ?>');
  <?php endif; ?>
})();
</script>
<?php unset($gcGuest, $gcNext); ?>
