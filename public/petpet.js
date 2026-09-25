/* Pet Pet easter eggs — middle management has arrived.
 * Desktop only. She ONLY ever peeks over the top edge of a page element
 * (never floats free), and never appears near the top of the page (no heroes).
 * Include once per page with <script src="/petpet.js" defer></script>.
 */
(function () {
  'use strict';
  if (window.__petpetRan) return;
  window.__petpetRan = true;
  try { if (window.self !== window.top) return; } catch (e) { return; }

  // Desktop only, and respect reduced motion.
  if (!window.matchMedia('(pointer: fine) and (min-width: 1024px)').matches) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  // It's an easter egg, not a feature: roughly half of pageviews.
  if (Math.random() > 0.5) return;

  var BASE = '/bum/petpet/';
  var IMGS = ['peek.png', 'box.png', 'crown.png', 'wand.png', 'angry.png',
              'spa.png', 'sleeping.png', 'pencil.png', 'pinkbow.png', 'headset.png'];
  var QUIPS = [
    'leave it to bum bum.',
    'per my last email…',
    'let\u2019s circle back on that.',
    'i\u2019ll take that offline.',
    'great question \u2014 forwarding to bum bum.',
    'my door is always open. (it isn\u2019t.)',
    'sorry, i\u2019m in back-to-backs all day.',
    'can you put that in a deck?',
    'i approved the snacks. you\u2019re welcome.',
    'that\u2019s above my pay grade. (my pay is zero.)',
    'have we tried\u2026 actually, bum bum handles that.',
    'i don\u2019t make decisions. i delegate them.',
    'this meeting could have been an email.',
    'i\u2019m just here so i won\u2019t get fined.'
  ];

  function pick(arr) { return arr[Math.floor(Math.random() * arr.length)]; }

  var css = [
    '.pp-wrap{position:fixed;z-index:2147483000;pointer-events:auto;cursor:pointer;line-height:0;width:150px}',
    '.pp-crop{overflow:hidden;border-radius:18px 18px 0 0}',
    '.pp-crop img{display:block;width:150px;max-width:none;height:auto;user-select:none;-webkit-user-drag:none}',
    '.pp-duck{transition:transform .55s ease-in,opacity .55s ease-in;transform:translateY(70px)!important;opacity:0!important}',
    '.pp-bubble{position:absolute;bottom:calc(100% + 10px);right:0;min-width:170px;max-width:230px;background:#E8EBE9;color:#1E2321;border:1px solid rgba(140,133,123,.4);border-radius:14px;padding:10px 13px;font:500 13px/1.45 -apple-system,"Segoe UI",sans-serif;box-shadow:0 8px 24px rgba(30,35,33,.18);line-height:1.45;cursor:pointer}',
    '.pp-bubble:after{content:"";position:absolute;top:100%;right:34px;border:8px solid transparent;border-top-color:#E8EBE9}',
    '.pp-bubble small{display:block;margin-top:6px;font-size:10px;letter-spacing:.08em;text-transform:uppercase;color:#8C857B}'
  ].join('\n');

  var style = document.createElement('style');
  style.textContent = css;
  document.head.appendChild(style);

  function visible(el) {
    var r = el.getBoundingClientRect();
    var cs = window.getComputedStyle(el);
    return r.width > 0 && r.height > 0 && cs.visibility !== 'hidden' && cs.display !== 'none';
  }

  // Anchor candidates: roomy elements sitting at/below the fold — never the hero.
  function findAnchor() {
    var fold = window.innerHeight * 0.75;
    var cands = Array.prototype.filter.call(
      document.querySelectorAll('main section, main article, main form, form, .card, .tool-card, footer.footer'),
      function (el) {
        if (!visible(el)) return false;
        if (el.closest('header')) return false;
        var r = el.getBoundingClientRect();
        return r.width >= 280 && r.height >= 160 && r.top >= fold;
      }
    );
    if (!cands.length) return null;
    // She has a soft spot for looming over forms.
    var forms = cands.filter(function (el) { return el.tagName === 'FORM'; });
    if (forms.length && Math.random() < 0.6) return pick(forms);
    return pick(cands);
  }

  // Pick a horizontal perch with no text under her face: sample candidate
  // spots with caretRangeFromPoint and take the first one clear of text.
  // Runs while her wrap is display:none, so she never samples herself.
  function overText(x, y) {
    try {
      var range = null;
      if (document.caretRangeFromPoint) range = document.caretRangeFromPoint(x, y);
      if (!range) return false;
      var node = range.startContainer;
      return !!node && node.nodeType === 3 && /\S/.test(node.textContent || '');
    } catch (e) { return false; }
  }
  function findClearX(anchor, cropH) {
    var r = anchor.getBoundingClientRect();
    var y = r.top - cropH * 0.5;
    var ratios = [0.85, 0.7, 0.15, 0.3, 0.5, 0.6, 0.4];
    for (var i = 0; i < ratios.length; i++) {
      var cx = r.left + r.width * ratios[i];
      if (y < 0 || y > window.innerHeight) return ratios[i];
      var blocked = overText(cx - 45, y) || overText(cx, y) || overText(cx + 45, y);
      if (!blocked) return ratios[i];
    }
    return 0.85;
  }

  function start() {
    var anchor;
    try { anchor = findAnchor(); } catch (e) { anchor = null; }
    if (!anchor) return; // No suitable element below the fold: she sits this one out.

    var wrap = document.createElement('div');
    wrap.className = 'pp-wrap';
    wrap.setAttribute('role', 'img');
    wrap.setAttribute('aria-label', 'Pet Pet, middle management, is peeking over this section');
    wrap.title = 'pet pet · middle management';
    var crop = document.createElement('div');
    crop.className = 'pp-crop';
    var img = document.createElement('img');
    img.src = BASE + pick(IMGS);
    img.alt = 'Pet Pet the cat peeking over the page';
    img.draggable = false;
    crop.appendChild(img);
    wrap.appendChild(crop);
    wrap.style.display = 'none';
    document.body.appendChild(wrap);

    // Show most of her face: reveal ~80% of the frame, chin tucked behind the edge.
    var cropH = 100; // fallback until the image reports its real size
    function sizeCrop() {
      if (img.naturalWidth && img.naturalHeight) {
        cropH = Math.round(150 * (img.naturalHeight / img.naturalWidth) * 0.8);
      }
      crop.style.height = cropH + 'px';
      schedule();
    }
    if (img.complete && img.naturalWidth) sizeCrop();
    else { img.addEventListener('load', sizeCrop); setTimeout(sizeCrop, 1500); }

    // Lock her horizontal perch per pageview (sampled for whitespace BEFORE
    // she exists in the DOM); track the anchor vertically.
    var xRatio = 0.5;
    try { xRatio = findClearX(anchor, cropH); } catch (e) {}
    var raf = 0;
    function place() {
      raf = 0;
      var r = anchor.getBoundingClientRect();
      if (r.bottom < -40 || r.top > window.innerHeight + 40) {
        wrap.style.display = 'none';
        return;
      }
      wrap.style.display = '';
      var x = r.left + r.width * xRatio - 75;
      x = Math.max(8, Math.min(window.innerWidth - 158, x));
      wrap.style.left = x + 'px';
      // Perched in the whitespace gap above the element, chin resting right on its edge.
      wrap.style.top = (r.top - cropH + 2) + 'px';
    }
    function schedule() { if (!raf) raf = requestAnimationFrame(place); }
    window.addEventListener('scroll', schedule, { passive: true });
    window.addEventListener('resize', schedule);
    // Reveal after a beat, so she pops in as a surprise mid-scroll.
    setTimeout(place, 1200 + Math.random() * 2500);

    var open = false;
    function duck() {
      wrap.classList.add('pp-duck');
      setTimeout(function () { if (wrap.parentNode) wrap.parentNode.removeChild(wrap); }, 600);
    }
    wrap.addEventListener('click', function () {
      if (open) { duck(); return; }
      open = true;
      var b = document.createElement('div');
      b.className = 'pp-bubble';
      b.appendChild(document.createTextNode(pick(QUIPS)));
      var s = document.createElement('small');
      s.textContent = 'pet pet · middle management';
      b.appendChild(s);
      wrap.appendChild(b);
      var t = setTimeout(function () {
        if (b.parentNode) b.parentNode.removeChild(b);
        open = false;
      }, 4500);
      b.addEventListener('click', function (ev) { ev.stopPropagation(); clearTimeout(t); duck(); });
    });
    // She stays put — middle management never leaves early.
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', start);
  } else {
    start();
  }
})();
