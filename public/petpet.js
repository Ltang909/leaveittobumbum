/* Pet Pet easter eggs — middle management has arrived.
 * Desktop only. Random placement, random timing. She does nothing, proudly.
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
    'i don\u2019t make decisions. i delegate them.'
  ];
  var LOUNGERS = ['napping.png', 'ceo.png', 'blep.png', 'bowtie.png', 'yawn.png', 'headset.png'];

  function pick(arr) { return arr[Math.floor(Math.random() * arr.length)]; }

  var css = [
    '.pp-wrap{position:fixed;z-index:2147483000;pointer-events:auto;cursor:pointer;line-height:0}',
    '.pp-wrap img{display:block;width:100%;height:auto;border-radius:18px;user-select:none;-webkit-user-drag:none}',
    '.pp-peek{width:150px;overflow:hidden}',
    '.pp-peek .pp-crop{height:64px;overflow:hidden;border-radius:18px 18px 0 0}',
    '.pp-peek .pp-crop img{width:150px;max-width:none}',
    '.pp-bob{animation:pp-bob 2.6s ease-in-out infinite}',
    '@keyframes pp-bob{0%,100%{transform:translateY(0)}50%{transform:translateY(-7px)}}',
    '.pp-slide-in{animation:pp-slide-in .7s cubic-bezier(.2,.9,.25,1.2)}',
    '@keyframes pp-slide-in{from{transform:translateY(120%);opacity:0}to{transform:translateY(0);opacity:1}}',
    '.pp-side-in{animation:pp-side-in .7s cubic-bezier(.2,.9,.25,1.2)}',
    '@keyframes pp-side-in{from{transform:translateX(120%);opacity:0}to{transform:translateX(0);opacity:1}}',
    '.pp-leave{transition:transform .6s ease-in,opacity .6s ease-in;transform:translateY(130%)!important;opacity:0!important}',
    '.pp-leave-side{transition:transform .6s ease-in,opacity .6s ease-in;transform:translateX(130%)!important;opacity:0!important}',
    '.pp-tag{font:600 10px/1.4 -apple-system,"Segoe UI",sans-serif;letter-spacing:.08em;text-transform:uppercase;color:#8C857B;background:rgba(232,235,233,.92);border:1px solid rgba(140,133,123,.35);border-radius:999px;padding:3px 9px;margin-top:6px;display:inline-block;line-height:1.4}',
    '.pp-bubble{position:absolute;bottom:calc(100% + 10px);right:0;min-width:170px;max-width:230px;background:#E8EBE9;color:#1E2321;border:1px solid rgba(140,133,123,.4);border-radius:14px;padding:10px 13px;font:500 13px/1.45 -apple-system,"Segoe UI",sans-serif;box-shadow:0 8px 24px rgba(30,35,33,.18);line-height:1.45;cursor:pointer}',
    '.pp-bubble:after{content:"";position:absolute;top:100%;right:34px;border:8px solid transparent;border-top-color:#E8EBE9}',
    '.pp-bubble small{display:block;margin-top:6px;font-size:10px;letter-spacing:.08em;text-transform:uppercase;color:#8C857B}'
  ].join('\n');

  var style = document.createElement('style');
  style.textContent = css;
  document.head.appendChild(style);

  function makeWrap(img, cls, label) {
    var wrap = document.createElement('div');
    wrap.className = 'pp-wrap ' + cls;
    wrap.setAttribute('role', 'img');
    wrap.setAttribute('aria-label', 'Pet Pet, middle management, is here');
    wrap.title = 'pet pet · middle management';
    var im = document.createElement('img');
    im.src = BASE + img;
    im.alt = 'Pet Pet the cat';
    im.draggable = false;
    wrap.appendChild(im);
    if (label) {
      var tag = document.createElement('span');
      tag.className = 'pp-tag';
      tag.textContent = 'pet pet · middle mgmt';
      wrap.appendChild(tag);
    }
    document.body.appendChild(wrap);
    return wrap;
  }

  function bubble(wrap) {
    var b = document.createElement('div');
    b.className = 'pp-bubble';
    b.innerHTML = '';
    b.appendChild(document.createTextNode(pick(QUIPS)));
    var s = document.createElement('small');
    s.textContent = 'pet pet · middle management';
    b.appendChild(s);
    wrap.appendChild(b);
    var t = setTimeout(function () { if (b.parentNode) b.parentNode.removeChild(b); }, 4500);
    b.addEventListener('click', function (ev) { ev.stopPropagation(); clearTimeout(t); leave(wrap, true); });
    return b;
  }

  function leave(wrap, fast) {
    var side = wrap.classList.contains('pp-side');
    wrap.classList.remove('pp-bob', 'pp-slide-in', 'pp-side-in');
    wrap.classList.add(side ? 'pp-leave-side' : 'pp-leave');
    setTimeout(function () { if (wrap.parentNode) wrap.parentNode.removeChild(wrap); }, fast ? 650 : 650);
  }

  function wire(wrap, sideExit) {
    var open = false;
    if (sideExit) wrap.classList.add('pp-side');
    wrap.addEventListener('click', function () {
      if (open) { leave(wrap); open = false; return; }
      open = true;
      var b = bubble(wrap);
      var mo = new MutationObserver(function () {
        if (!b.parentNode) { open = false; mo.disconnect(); }
      });
      mo.observe(wrap, { childList: true });
    });
    // She gets bored and wanders off on her own eventually.
    setTimeout(function () { if (wrap.parentNode && !open) leave(wrap); }, 45000);
  }

  function visible(el) {
    var r = el.getBoundingClientRect();
    var cs = window.getComputedStyle(el);
    return r.width > 0 && r.height > 0 && cs.visibility !== 'hidden' && cs.display !== 'none';
  }

  /* Mode 1: poking her head over the top of a form. */
  function formPeek() {
    var forms = Array.prototype.filter.call(document.querySelectorAll('form'), function (f) {
      if (!visible(f)) return false;
      var r = f.getBoundingClientRect();
      return r.width >= 280 && r.height >= 120;
    });
    if (!forms.length) return false;
    var form = forms[0];
    var wrap = makeWrap('peek-form.png', 'pp-peek pp-bob', false);
    var inner = document.createElement('div');
    inner.className = 'pp-crop';
    var img = wrap.querySelector('img');
    wrap.insertBefore(inner, img);
    inner.appendChild(img);

    var raf = 0;
    function place() {
      raf = 0;
      var r = form.getBoundingClientRect();
      if (r.bottom < -80 || r.top > window.innerHeight + 80) {
        wrap.style.display = 'none';
        return;
      }
      wrap.style.display = '';
      var x = r.left + r.width * (0.25 + Math.random() * 0.5) - 75;
      x = Math.max(8, Math.min(window.innerWidth - 158, x));
      wrap.style.left = x + 'px';
      wrap.style.top = (r.top - 62) + 'px';
    }
    function schedule() { if (!raf) raf = requestAnimationFrame(place); }
    // Randomize once per pageview, not on every scroll.
    var lockedX = null;
    var origPlace = place;
    place = function () {
      origPlace();
      if (lockedX === null && wrap.style.left) lockedX = wrap.style.left;
      else if (lockedX !== null) wrap.style.left = lockedX;
    };
    window.addEventListener('scroll', schedule, { passive: true });
    window.addEventListener('resize', schedule);
    place();
    wire(wrap, false);
    return true;
  }

  /* Mode 2: lounging at the bottom corner of the page. */
  function bottomLounge() {
    var wrap = makeWrap(pick(LOUNGERS), 'pp-slide-in', true);
    wrap.style.right = '18px';
    wrap.style.bottom = '-26px';
    wrap.style.width = '150px';
    wrap.style.display = 'none';
    setTimeout(function () {
      if (!wrap.parentNode) return;
      wrap.style.display = '';
      // re-trigger entrance now that she's visible
      wrap.classList.remove('pp-slide-in');
      void wrap.offsetWidth;
      wrap.classList.add('pp-slide-in');
    }, 2000 + Math.random() * 5000);
    wire(wrap, false);
    return true;
  }

  /* Mode 3: peeking out from behind a content card. */
  function cardPeek() {
    var cands = Array.prototype.filter.call(
      document.querySelectorAll('main section, main article, .card, .tool-card'),
      function (el) {
        if (!visible(el)) return false;
        var r = el.getBoundingClientRect();
        return r.width >= 240 && r.height >= 160 && r.top > -200 && r.bottom < window.innerHeight + 400;
      }
    );
    if (!cands.length) return false;
    var el = pick(cands);
    var wrap = makeWrap('peek-box.png', 'pp-side pp-side-in', false);
    wrap.style.width = '110px';
    var raf = 0;
    function place() {
      raf = 0;
      var r = el.getBoundingClientRect();
      if (r.bottom < -60 || r.top > window.innerHeight + 60) { wrap.style.display = 'none'; return; }
      wrap.style.display = '';
      wrap.style.left = Math.min(window.innerWidth - 46, r.right - 64) + 'px';
      wrap.style.top = (r.top + r.height * (0.3 + Math.random() * 0.3)) + 'px';
    }
    function schedule() { if (!raf) raf = requestAnimationFrame(place); }
    window.addEventListener('scroll', schedule, { passive: true });
    window.addEventListener('resize', schedule);
    place();
    wire(wrap, true);
    return true;
  }

  function start() {
    // Weighted: form peek and bottom lounge are the stars.
    var modes = [formPeek, formPeek, bottomLounge, bottomLounge, bottomLounge, cardPeek, cardPeek];
    // Shuffle lightly and try until one sticks.
    modes.sort(function () { return Math.random() - 0.5; });
    for (var i = 0; i < modes.length; i++) {
      try { if (modes[i]()) return; } catch (e) { /* try next */ }
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', start);
  } else {
    start();
  }
})();
