<?php
// "See it in action" demo strip for tool pages.
// Set $demo to one of: clips, notes, cutline, purrsuit, corporate-bum-bum, doodle
// before including. Outputs its own scoped <style> block plus the demo HTML.
// Pure CSS animations, infinite looping, no JavaScript.
$demoKey = isset($demo) ? (string) $demo : '';
$demoMeta = [
    'clips' => ['sub' => 'A 12-second tour: record, trim, download.', 'accent' => '#b7d6ff'],
    'notes' => ['sub' => 'Watch a meeting note type itself out.', 'accent' => '#ffd84d'],
    'cutline' => ['sub' => 'Three forgotten subscriptions meet the cutline.', 'accent' => '#ffc59b'],
    'purrsuit' => ['sub' => 'One contact, three stages, zero cold leads.', 'accent' => '#d6c9f5'],
    'corporate-bum-bum' => ['sub' => 'From applied to interview in one nudge.', 'accent' => '#b9f2cf'],
    'doodle' => ['sub' => 'A squiggle becomes a download.', 'accent' => '#b2e8dc'],
];
if (!isset($demoMeta[$demoKey])) return;
$demoSub = $demoMeta[$demoKey]['sub'];
$demoAccent = $demoMeta[$demoKey]['accent'];
?>
<style>
.bb-demo{margin:28px 0 10px;background:#fffdf4;border:2px solid #142015;border-radius:20px;box-shadow:6px 6px 0 #142015;padding:22px 22px 24px}
.bb-demo h2{font-family:Fraunces,Georgia,serif;font-size:1.65rem;line-height:1.05;margin:0 0 4px;letter-spacing:-.02em}
.bb-demo .bb-sub{margin:0 0 16px;font-size:.95rem;opacity:.78}
.bb-demo .bb-stage{position:relative;overflow:hidden;border:2px solid #142015;border-radius:14px;min-height:158px;max-height:220px;padding:14px;display:flex;flex-direction:column;justify-content:center;gap:8px}
/* generic vertical number reel (timer / count-up) */
.bb-reel{display:inline-block;height:1.5em;overflow:hidden;vertical-align:bottom;line-height:1.5em}
.bb-reel>span{display:block}
.bb-reel>span>span{display:block}
.bb-reel-8>span{animation:bb-reel8 12s steps(8) infinite}
.bb-reel-4>span{animation:bb-reel4 12s steps(4) infinite}
@keyframes bb-reel8{to{transform:translateY(-12em)}}
@keyframes bb-reel4{to{transform:translateY(-6em)}}
/* ---- clips: fake browser window, REC dot, timer, checkmarks ---- */
.bb-browser{background:#fffdf4;border:2px solid #142015;border-radius:12px;overflow:hidden;box-shadow:4px 4px 0 #142015}
.bb-bar{display:flex;gap:6px;align-items:center;background:#ffd84d;border-bottom:2px solid #142015;padding:8px 12px}
.bb-bar i{width:11px;height:11px;border-radius:50%;border:2px solid #142015;display:block}
.bb-bar i:nth-child(1){background:#ff7448}.bb-bar i:nth-child(2){background:#ffd84d}.bb-bar i:nth-child(3){background:#b9f2cf}
.bb-rec{display:flex;align-items:center;gap:8px;padding:14px 14px 6px;font-weight:900;font-size:.95rem}
.bb-rec-dot{width:13px;height:13px;border-radius:50%;background:#e5482f;border:2px solid #142015;animation:bb-blink 1.2s ease-in-out infinite}
@keyframes bb-blink{0%,100%{opacity:1}50%{opacity:.25}}
.bb-checks{display:flex;gap:10px;flex-wrap:wrap;padding:8px 14px 16px}
.bb-check{display:inline-flex;align-items:center;gap:6px;background:#b9f2cf;border:2px solid #142015;border-radius:999px;padding:6px 14px;font-weight:900;font-size:.88rem;opacity:0}
.bb-c1{animation:bb-pop1 12s infinite}
.bb-c2{animation:bb-pop2 12s infinite}
@keyframes bb-pop1{0%,50%{opacity:0;transform:translateY(8px)}57%,90%{opacity:1;transform:none}97%,100%{opacity:0;transform:translateY(8px)}}
@keyframes bb-pop2{0%,64%{opacity:0;transform:translateY(8px)}71%,90%{opacity:1;transform:none}97%,100%{opacity:0;transform:translateY(8px)}}
/* ---- notes: typewriter card + saved stamp ---- */
.bb-note{background:#fffdf4;border:2px solid #142015;border-radius:12px;box-shadow:4px 4px 0 #142015;padding:14px 16px}
.bb-note-head{display:flex;justify-content:space-between;font-size:.78rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;opacity:.7;margin-bottom:8px}
.bb-type{display:inline-block;overflow:hidden;white-space:nowrap;vertical-align:bottom;max-width:100%;font-weight:700;border-right:3px solid #142015;width:0;animation:bb-type 12s steps(48) infinite,bb-caret 1s steps(1) infinite}
@keyframes bb-type{0%{width:0}52%{width:48ch}82%{width:48ch}92%,100%{width:0}}
@keyframes bb-caret{0%,49%{border-right-color:#142015}50%,100%{border-right-color:transparent}}
.bb-stamp{display:inline-block;margin-top:12px;background:#b9f2cf;border:2px solid #142015;border-radius:10px;padding:6px 14px;font-weight:900;font-size:.85rem;transform:rotate(-8deg);opacity:0;animation:bb-stamp 12s infinite}
@keyframes bb-stamp{0%,56%{opacity:0;transform:scale(.4) rotate(-16deg)}64%,86%{opacity:1;transform:scale(1) rotate(-8deg)}94%,100%{opacity:0;transform:scale(1) rotate(-8deg)}}
/* ---- cutline: struck-through subs + savings count-up ---- */
.bb-sub-row{position:relative;display:flex;justify-content:space-between;align-items:center;background:#fffdf4;border:2px solid #142015;border-radius:12px;padding:8px 14px;font-weight:800}
.bb-sub-row b{font-weight:900}
.bb-sub-row::after{content:"";position:absolute;left:10px;right:10px;top:50%;height:3px;background:#e5482f;border-radius:2px;transform:scaleX(0);transform-origin:left center}
.bb-r1::after{animation:bb-cut1 12s infinite}
.bb-r2::after{animation:bb-cut2 12s infinite}
.bb-r3::after{animation:bb-cut3 12s infinite}
@keyframes bb-cut1{0%,12%{transform:scaleX(0)}20%,88%{transform:scaleX(1)}96%,100%{transform:scaleX(0)}}
@keyframes bb-cut2{0%,37%{transform:scaleX(0)}45%,88%{transform:scaleX(1)}96%,100%{transform:scaleX(0)}}
@keyframes bb-cut3{0%,62%{transform:scaleX(0)}70%,88%{transform:scaleX(1)}96%,100%{transform:scaleX(0)}}
.bb-save{align-self:center;background:#142015;color:#f6f0df;border-radius:999px;padding:8px 18px;font-weight:900;font-size:.95rem}
/* ---- purrsuit + corporate-bum-bum: card sliding across stages ---- */
.bb-track{position:relative}
.bb-pills{display:flex;gap:8px}
.bb-pills span{flex:1;text-align:center;border:2px solid #142015;border-radius:999px;padding:9px 4px;font-size:.78rem;font-weight:900;background:rgba(255,253,244,.75);white-space:nowrap;overflow:hidden}
.bb-mover{position:absolute;inset:0;pointer-events:none}
.bb-move-card{height:100%;display:flex;align-items:center;justify-content:center;gap:8px;background:#ffd84d;border:2px solid #142015;border-radius:999px;font-weight:900;font-size:.85rem;box-shadow:3px 3px 0 #142015}
.bb-mover-3 .bb-move-card{width:calc(33.333% - 6px);animation:bb-slide3 10s infinite}
.bb-mover-2 .bb-move-card{width:calc(50% - 5px);animation:bb-slide2 10s infinite}
@keyframes bb-slide3{0%,18%{transform:translateX(0);opacity:1}30%,52%{transform:translateX(104%);opacity:1}64%,88%{transform:translateX(208%);opacity:1}94%,100%{transform:translateX(208%);opacity:0}}
@keyframes bb-slide2{0%,22%{transform:translateX(0);opacity:1}34%,80%{transform:translateX(103%);opacity:1}88%,100%{transform:translateX(103%);opacity:0}}
.bb-tick{display:inline-grid;place-items:center;width:22px;height:22px;border-radius:50%;background:#b9f2cf;border:2px solid #142015;font-size:.72rem;opacity:0;animation:bb-tickpop 10s infinite}
@keyframes bb-tickpop{0%,62%{opacity:0;transform:scale(.3)}70%,90%{opacity:1;transform:scale(1)}96%,100%{opacity:0;transform:scale(.3)}}
.bb-env{position:absolute;right:6%;top:-14px;font-size:1.7rem;opacity:0;animation:bb-envpop 10s infinite}
@keyframes bb-envpop{0%,80%{opacity:0;transform:scale(.3) rotate(-12deg)}86%,94%{opacity:1;transform:scale(1.15) rotate(6deg)}98%,100%{opacity:0;transform:scale(1) rotate(6deg)}}
.bb-cols{display:flex;gap:10px}
.bb-col{flex:1;border:2px solid #142015;border-radius:12px;background:rgba(255,253,244,.6);padding:10px;text-align:center;font-size:.78rem;font-weight:900;letter-spacing:.06em;text-transform:uppercase}
/* ---- doodle: self-drawing squiggle + download pill ---- */
.bb-svg{width:100%;height:120px;display:block}
.bb-svg path{fill:none;stroke:#142015;stroke-width:5;stroke-linecap:round;stroke-dasharray:100;stroke-dashoffset:100;animation:bb-draw 8s infinite}
.bb-svg path.bb-sq2{stroke:#ff7448;stroke-width:4;animation-delay:.5s}
@keyframes bb-draw{0%{stroke-dashoffset:100;opacity:1}55%,86%{stroke-dashoffset:0;opacity:1}94%,100%{stroke-dashoffset:0;opacity:0}}
.bb-dl{align-self:center;background:#ffd84d;border:2px solid #142015;border-radius:999px;padding:8px 20px;font-weight:900;box-shadow:3px 3px 0 #142015;opacity:0;animation:bb-dlpop 8s infinite}
@keyframes bb-dlpop{0%,58%{opacity:0;transform:translateY(10px)}68%,90%{opacity:1;transform:none}96%,100%{opacity:0;transform:translateY(10px)}}
@media (prefers-reduced-motion:reduce){.bb-demo *{animation:none!important}}
@media (max-width:600px){.bb-demo{padding:18px}.bb-pills span{font-size:.68rem}}
</style>
<section class="bb-demo" aria-label="See it in action">
  <h2>See it in action</h2>
  <p class="bb-sub"><?= htmlspecialchars($demoSub) ?></p>
  <div class="bb-stage" style="background:<?= htmlspecialchars($demoAccent) ?>">
    <?php if ($demoKey === 'clips'): ?>
    <div class="bb-browser" aria-hidden="true">
      <div class="bb-bar"><i></i><i></i><i></i></div>
      <div class="bb-rec"><span class="bb-rec-dot"></span> REC <span class="bb-reel bb-reel-8"><span><span>00:01</span><span>00:02</span><span>00:03</span><span>00:04</span><span>00:05</span><span>00:06</span><span>00:07</span><span>00:08</span></span></span></div>
      <div class="bb-checks"><span class="bb-check bb-c1">Trimmed ✓</span><span class="bb-check bb-c2">Downloaded ✓</span></div>
    </div>
    <?php elseif ($demoKey === 'notes'): ?>
    <div class="bb-note" aria-hidden="true">
      <div class="bb-note-head"><span>Meeting note</span><span>2:14</span></div>
      <div><span class="bb-type">Team standup: launch approved, demo Friday at 3.</span></div>
      <div><span class="bb-stamp">Saved in your browser ✓</span></div>
    </div>
    <?php elseif ($demoKey === 'cutline'): ?>
    <div class="bb-sub-row bb-r1" aria-hidden="true"><span>Streamflix</span><b>$15.99/mo</b></div>
    <div class="bb-sub-row bb-r2" aria-hidden="true"><span>GymPal</span><b>$29.00/mo</b></div>
    <div class="bb-sub-row bb-r3" aria-hidden="true"><span>CloudBox</span><b>$9.99/mo</b></div>
    <div class="bb-save">You saved <span class="bb-reel bb-reel-4"><span><span>$0.00</span><span>$15.99</span><span>$44.99</span><span>$54.98</span></span></span>/mo</div>
    <?php elseif ($demoKey === 'purrsuit'): ?>
    <div class="bb-track" aria-hidden="true">
      <div class="bb-pills"><span>New</span><span>Followed up</span><span>Replied</span></div>
      <div class="bb-mover bb-mover-3"><div class="bb-move-card">Maya Chen <span class="bb-tick">✓</span></div></div>
    </div>
    <?php elseif ($demoKey === 'corporate-bum-bum'): ?>
    <div class="bb-track" aria-hidden="true">
      <span class="bb-env">✉️</span>
      <div class="bb-cols"><div class="bb-col">Applied</div><div class="bb-col">Interview</div></div>
      <div class="bb-mover bb-mover-2"><div class="bb-move-card">Acme Corp · Growth Marketer</div></div>
    </div>
    <?php elseif ($demoKey === 'doodle'): ?>
    <svg class="bb-svg" viewBox="0 0 220 120" aria-hidden="true">
      <path pathLength="100" d="M12 72 C 38 24, 62 112, 92 64 S 142 18, 168 66 S 196 96, 210 52"/>
      <path class="bb-sq2" pathLength="100" d="M30 96 C 80 104, 140 104, 192 92"/>
    </svg>
    <div class="bb-dl" aria-hidden="true">Download PNG</div>
    <?php endif; ?>
  </div>
</section>
