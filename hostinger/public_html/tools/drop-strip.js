/* Bum Bum post-task drop strip.
 *
 * After a tool finishes a job (an action completes), Bum Bum shows up in
 * person: his per-tool portrait, a payoff line about the time he just
 * handed back, and a capture box that works like a compact version of
 * the homepage front desk: rotating example placeholders that teach the
 * @ move first, the full @ tool menu (same registry the desk reads,
 * fetched from /tools/registry.json), pins that route absolutely, the
 * ambiguous "Hmm" choices with the Other branch, attach / camera / mic
 * inputs, and the same /api/capture.php contract as the desk. One more
 * messy thing off the visitor's plate, one action, charged by the tool
 * that does the work.
 *
 * Triggers:
 *   - Automatically when a page fires bbTrack('action_completed', {tool})
 *     or bbTrack('gap_analyzed') (Gap Scout finishes client-side).
 *   - Manually via window.BBDropStrip.show('<tool-key>') from pages whose
 *     completion is a metered API call with no action_completed event.
 *
 * Dismissal persists for the session. No collections, no streaks, no
 * homework: the cat shows up, does a job, and offers to do the next one.
 */
(function () {
  if (window.BBDropStrip) return;

  var DISMISS_KEY = "bb_drop_strip_dismissed";
  var LINES = {
    "clips": "Screen recorded and saved, no wrestling with editing software. That chunk of your afternoon is yours again.",
    "notes": "You talked, Bum Bum wrote it down. One less thing rattling around your head.",
    "cutline": "That subscription is on the list now, where you can actually see it. No more mystery charges sneaking up on you.",
    "purrsuit": "Follow-up noted before it could slip. Clients love people who remember, and now you do.",
    "corporate-bum-bum": "Application logged and the follow-up clock is ticking where you can see it. The spreadsheet era of your job hunt just ended.",
    "doodle": "A masterpiece, preserved forever. Michelangelo had to lie on a scaffold for years, you got a download button.",
    "invoice-chaser": "That invoice is on the books and the chasing is Bum Bum's problem now, not yours.",
    "receipt-reader": "Receipts read, numbers tamed, tables ready. The shoebox method just retired.",
    "poster-to-calendar": "That poster is in your calendar now, not just in your camera roll. Future you says thanks.",
    "meal-scanner": "Meal logged, honest numbers, no app nagging you about a streak. Eat in peace.",
    "gap-scout": "Now you know exactly what your resume is missing. That guesswork is gone for good.",
    "video-trimmer": "Trimmed to the good part. All that scrubbing back and forth? Bum Bum's job now.",
    "image-converter": "Converted and handed back. No sketchy converter site, no watermark, no drama.",
    "image-cropper": "Cropped to the good part. One less trip through a giant photo editor.",
    "ghostwriter": "You rambled, Bum Bum made it content. Talking counts as working now.",
    "audiogram": "Your audio is dressed up as a video people might actually stop and watch. Fancy.",
    "purr-code": "Your link, but cute, and ready to print. Bum Bum insists the cat in the middle helps.",
    "sop-ify": "Brain dump in, playbook out. Next time someone asks how it works, they get the document, not your afternoon.",
    "hearsay": "Now you know what the robots say about you when you are not in the room.",
    "video-converter": "Converted with no sketchy website, no watermark, and no download-button roulette."
  };
  // Desk parity data, mirrored from the homepage front desk
  // (app/components/capture-bar.tsx). The tool list itself is NOT copied:
  // the strip fetches /tools/registry.json, the same file the desk's
  // registry import (app/lib/tools.ts) is built from.
  var PLACEHOLDERS = [
    "Start with @ to pick a tool, or just describe the job\u2026",
    "Drop a receipt, get a spreadsheet\u2026",
    "Photo of an event poster? Add it to your calendar",
    "Netflix $15.99 a month, track it as part of your subscriptions",
    "transcribe your spoken thoughts",
    "Photo of your meal? Turn it into tracked calories and macros"
  ];
  var PIN_PLACEHOLDERS = {
    clips: "Hit Log it! and the recorder opens, right in your browser\u2026",
    notes: "e.g. Idea: a loyalty card, but for dog groomers\u2026",
    cutline: "e.g. Netflix, $15.99, monthly",
    purrsuit: "e.g. Maria at Bloom Co, last spoke Tuesday, nudge her about the quote\u2026",
    "corporate-bum-bum": "e.g. Applied to Stripe for the Growth Lead role today",
    doodle: "Hit Log it! and the sketchpad opens. Draw it there\u2026",
    "invoice-chaser": "e.g. hud.io, $20.00, due in 2 weeks",
    "receipt-reader": "e.g. Starbucks, $6.40",
    "poster-to-calendar": "Drop the poster photo here\u2026",
    "meal-scanner": "Drop the meal photo here\u2026",
    "gap-scout": "Hit Log it! to open it, then drop your resume and the job post there\u2026",
    "video-trimmer": "Drop the video here to trim it\u2026",
    "image-converter": "Drop the image here to convert it\u2026",
    "image-cropper": "Drop the image here to crop it\u2026",
    ghostwriter: "e.g. Paste a ramble or transcript, get hooks, a script, and a caption\u2026",
    audiogram: "Hit Log it! to open it, then drop your MP3 there\u2026",
    "purr-code": "e.g. https://yourshop.com, whatever the code should open\u2026",
    "sop-ify": "e.g. First we check the inbox, then we chase anything older than a day\u2026",
    hearsay: "e.g. Just your brand name. He checks what the AIs say about it\u2026",
    "video-converter": "Drop the video here to convert it\u2026"
  };
  /* Where each tool's result lives, for completion placement (Item C):
   * when a job finishes, the strip renders directly below the result
   * and the page anchors to the result first. Control-like targets
   * (textarea, canvas) are lifted to the panel that holds them. Tools
   * mapped to "" have no identifiable result container (doodle: the
   * canvas IS the work) and fall back to below the main work panel. */
  var RESULT_TARGETS = {
    "clips": "#doneView",
    "notes": "#notesText",
    "cutline": "#sub-list",
    "purrsuit": "#followupPanel",
    "corporate-bum-bum": "#followupPanel",
    "doodle": "",
    "invoice-chaser": "#invoiceList",
    "receipt-reader": "#resultsPanel",
    "poster-to-calendar": "#resultsPanel",
    "meal-scanner": "#resultsPanel",
    "gap-scout": "#results",
    "video-trimmer": "#resultCard",
    "image-converter": "#resultList",
    "image-cropper": "#resultWrap",
    "ghostwriter": "#gwPack",
    "audiogram": "#agStudio",
    "purr-code": "#pcCanvas",
    "sop-ify": "#sopResult",
    "hearsay": "#hsResults",
    "video-converter": "#vcResult"
  };
  var NATIVE_TOOLS = ["notes", "receipt-reader", "corporate-bum-bum", "invoice-chaser", "cutline"];
  var FILE_HANDOFF_KINDS = {
    notes: ["audio"],
    "receipt-reader": ["image"],
    "poster-to-calendar": ["image"],
    "meal-scanner": ["image"],
    "image-converter": ["image"],
    "image-cropper": ["image"],
    "video-trimmer": ["video"],
    "video-converter": ["video"]
  };

  var shown = false;
  var currentTool = "";

  function track(event, props) {
    try { if (typeof window.bbTrack === "function") window.bbTrack(event, props || {}); } catch (e) {}
  }
  function esc(s) {
    return String(s).replace(/[&<>"']/g, function (c) {
      return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c];
    });
  }
  function idem() {
    var a = new Uint8Array(24);
    crypto.getRandomValues(a);
    return Array.from(a, function (b) { return ("0" + b.toString(16)).slice(-2); }).join("");
  }
  function dismissed() {
    try { return sessionStorage.getItem(DISMISS_KEY) === "1"; } catch (e) { return false; }
  }
  function dismiss() {
    try { sessionStorage.setItem(DISMISS_KEY, "1"); } catch (e) {}
  }
  function toolKeyFromUrl(url) {
    var m = String(url || "").match(/\/tools\/([^/]+)\//);
    return m ? m[1] : "";
  }
  // Leave a "from the desk" marker before a hop to a tool page, same as
  // the homepage desk: drop-strip.js's own banner reads it on arrival.
  function markDeskHandoff(toolKey, kind, extra) {
    if (!toolKey) return;
    try {
      var data = { tool: toolKey, kind: kind, ts: Date.now() };
      if (extra) { for (var k in extra) data[k] = extra[k]; }
      sessionStorage.setItem("bb-desk-handoff", JSON.stringify(data));
    } catch (e) {}
  }

  var CSS = [
    ".bb-strip{background:#fffdf4;border:2px solid var(--line,#1a291c);border-radius:22px;box-shadow:8px 8px 0 var(--line,#1a291c);padding:24px 26px;margin:30px 0;position:relative}",
    ".bb-strip-close{position:absolute;top:10px;right:12px;border:2px solid var(--line,#1a291c);background:#fff;border-radius:50%;width:32px;height:32px;font-weight:900;cursor:pointer;line-height:1;margin:0;padding:0}",
    ".bb-strip-head{display:flex;gap:18px;align-items:flex-start}",
    ".bb-strip-face{width:74px;height:74px;flex:none;object-fit:contain;background:#fff;border:2px solid var(--line,#1a291c);border-radius:18px;padding:5px;transform:rotate(-3deg)}",
    ".bb-strip-done{font-weight:900;text-transform:uppercase;letter-spacing:.1em;font-size:12px;margin:2px 0 6px}",
    ".bb-strip-line{font-size:19px;line-height:1.45;margin:0;font-weight:700}",
    ".bb-strip-invite{margin:8px 0 0;font-size:16px}",
    ".bb-strip-composer{position:relative;margin-top:16px}",
    ".bb-strip-pinrow{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:10px}",
    ".bb-strip-pinrow[hidden]{display:none}",
    ".bb-strip-pin{display:inline-flex;align-items:center;gap:8px;background:var(--yellow,#ffd23f);border:2px solid var(--line,#1a291c);border-radius:999px;padding:4px 6px 4px 8px;font-weight:900;box-shadow:3px 3px 0 var(--line,#1a291c)}",
    ".bb-strip-pin img{width:22px;height:22px;object-fit:contain;display:block}",
    ".bb-strip-pin-emoji{font-size:16px;line-height:1}",
    ".bb-strip-unpin{border:2px solid var(--line,#1a291c);background:#fff;border-radius:50%;width:22px;height:22px;line-height:1;font-weight:900;cursor:pointer;display:grid;place-items:center;padding:0;margin:0}",
    ".bb-strip-pin-note{font-size:13.5px;font-weight:700;opacity:.7}",
    ".bb-strip-input{width:100%;box-sizing:border-box;padding:13px 15px;font-size:16px;line-height:1.5;border:2px solid var(--line,#1a291c);border-radius:12px;font:inherit;resize:none;background:#fff;max-height:110px;overflow-y:auto;display:block}",
    ".bb-strip-input:focus{outline:3px solid var(--lime,#d8ff62);outline-offset:1px}",
    ".bb-strip-bar{display:flex;align-items:center;gap:8px;margin-top:10px}",
    ".bb-strip-icon{flex:none;width:42px;height:42px;border:2px solid var(--line,#1a291c);background:#fffdf4;color:var(--ink,#142015);border-radius:50%;font-size:18px;font-weight:900;line-height:1;cursor:pointer;display:grid;place-items:center;padding:0;margin:0}",
    ".bb-strip-icon.recording{background:#ff7a1a;color:#fff}",
    ".bb-strip-spacer{flex:1}",
    ".bb-strip-hint{margin:8px 2px 0;font-size:13.5px;font-weight:700;opacity:.75}",
    ".bb-strip-mention{position:absolute;left:0;right:0;top:calc(100% + 8px);z-index:60;background:#fff;border:2px solid var(--line,#1a291c);border-radius:14px;box-shadow:6px 6px 0 var(--line,#1a291c);max-height:300px;overflow-y:auto;padding:6px}",
    ".bb-strip-mention[hidden]{display:none}",
    ".bb-strip-mention-item{display:flex;align-items:center;gap:12px;width:100%;text-align:left;background:transparent;border:0;border-radius:10px;padding:8px 10px;font:inherit;color:inherit;cursor:pointer;margin:0}",
    ".bb-strip-mention-item.active{background:var(--yellow,#ffd23f)}",
    ".bb-strip-mention-item img{width:30px;height:30px;object-fit:contain;flex:none}",
    ".bb-strip-mention-emoji{font-size:20px;flex:none;width:30px;text-align:center}",
    ".bb-strip-mention-text{display:flex;flex-direction:column;min-width:0}",
    ".bb-strip-mention-text b{font-size:15px}",
    ".bb-strip-mention-text small{opacity:.72;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}",
    ".bb-strip-mention-empty{padding:12px 14px;font-weight:800}",
    ".bb-strip-recrow{display:flex;align-items:center;gap:10px;margin-top:10px;flex-wrap:wrap}",
    ".bb-strip-recrow[hidden]{display:none}",
    ".bb-strip-recdot{width:11px;height:11px;border-radius:50%;background:#ff6b35;flex:none;animation:bb-strip-rec-pulse 1.1s ease-in-out infinite}",
    "@keyframes bb-strip-rec-pulse{0%,100%{opacity:1}50%{opacity:.25}}",
    "@media(prefers-reduced-motion:reduce){.bb-strip-recdot{animation:none}}",
    ".bb-strip-wave{width:132px;height:28px;flex:none}",
    ".bb-strip-rectime{font-weight:900;font-variant-numeric:tabular-nums}",
    ".bb-strip-recnote{font-size:13px;font-weight:700;opacity:.8}",
    ".bb-strip-rechint{margin:6px 2px 0;font-size:13px;font-weight:700;opacity:.72}",
    ".bb-strip-rechint[hidden]{display:none}",
    ".bb-strip-preview{width:52px;height:52px;object-fit:cover;border:2px solid var(--line,#1a291c);border-radius:10px;background:#fff;flex:none}",
    ".bb-strip-preview-icon{display:grid;place-items:center;font-size:20px}",
    ".bb-strip-filechip{display:flex;align-items:center;gap:10px;margin-top:10px;font-weight:700}",
    ".bb-strip-filemeta{min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}",
    ".bb-strip-filex{flex:none;border:2px solid var(--line,#1a291c);background:#fff;border-radius:50%;width:26px;height:26px;font-weight:900;cursor:pointer;line-height:1;display:grid;place-items:center;padding:0;margin:0}",
    ".bb-strip-btn{flex:none;border:2px solid var(--line,#1a291c);background:#fff;color:var(--ink,#142015);font-weight:900;padding:12px 16px;border-radius:999px;cursor:pointer;font:inherit;margin:0}",
    ".bb-strip-btn:disabled{opacity:.6;cursor:wait}",
    ".bb-strip-go{background:var(--lime,#d8ff62)}",
    ".bb-strip-result p{margin:10px 0 0}",
    ".bb-strip-error{color:#9b2516;font-weight:700}",
    ".bb-strip-chip{margin:8px 8px 0 0}",
    ".bb-strip-other{margin-top:12px;display:grid;gap:14px}",
    ".bb-strip-other[hidden]{display:none}",
    ".bb-strip-other-path p{margin:0 0 8px;font-size:15px;line-height:1.5}",
    ".bb-strip-other-btn{display:inline-block;text-decoration:none;margin:0}",
    ".bb-strip.drag-over{background:#fff3bf}",
    ".bb-handoff{display:flex;gap:14px;align-items:center;background:#fff3bf;border:2px solid var(--line,#1a291c);border-radius:16px;padding:12px 16px;margin:18px 0 4px;position:relative;grid-column:1/-1;width:100%;box-sizing:border-box}",
    ".bb-handoff-face{width:46px;height:46px;flex:none;object-fit:contain;background:#fff;border:2px solid var(--line,#1a291c);border-radius:12px;padding:3px}",
    ".bb-handoff-line{margin:0;font-size:15.5px;line-height:1.45;flex:1}",
    ".bb-handoff-close{flex:none;border:2px solid var(--line,#1a291c);background:#fff;border-radius:50%;width:28px;height:28px;font-weight:900;cursor:pointer;line-height:1;margin:0;padding:0}",
    "@media(max-width:700px){.bb-strip{padding:20px 16px}.bb-strip-head{flex-direction:column;gap:10px}.bb-handoff{padding:10px 12px}.bb-handoff-face{width:38px;height:38px}}"
  ].join("\n");

  function ensureStyle() {
    if (document.getElementById("bb-strip-style")) return;
    var style = document.createElement("style");
    style.id = "bb-strip-style";
    style.textContent = CSS;
    document.head.appendChild(style);
  }

  function storeHandoff(file) {
    return new Promise(function (res, rej) {
      if (!("indexedDB" in window)) { rej(new Error("no-idb")); return; }
      var req = indexedDB.open("bumbum-capture", 1);
      req.onupgradeneeded = function (e) {
        var db = e.target.result;
        if (!db.objectStoreNames.contains("handoff")) db.createObjectStore("handoff");
      };
      req.onsuccess = function (e) {
        var db = e.target.result, tx;
        try { tx = db.transaction("handoff", "readwrite"); } catch (err) { rej(err); return; }
        tx.objectStore("handoff").put({ file: file, name: file.name, type: file.type, ts: Date.now() }, "pending");
        tx.oncomplete = function () { res(); };
        tx.onerror = function () { rej(tx.error || new Error("tx")); };
      };
      req.onerror = function () { rej(req.error || new Error("open")); };
    });
  }

  /* ---- The tool registry, shared with the desk ----
   * Fetched once from /tools/registry.json, the same source file the
   * homepage desk's tool list is built from, so the strip's @ menu can
   * never drift from the desk's. */
  var toolsCache = null;
  var toolsLoading = null;
  function loadTools() {
    if (toolsCache) return Promise.resolve(toolsCache);
    if (toolsLoading) return toolsLoading;
    toolsLoading = fetch("/tools/registry.json", { credentials: "same-origin" })
      .then(function (r) { return r.json(); })
      .then(function (d) { toolsCache = (d && d.tools) || []; return toolsCache; })
      .catch(function () { toolsCache = []; return toolsCache; });
    return toolsLoading;
  }

  /* Tiny subsequence fuzzy matcher, the desk's own logic: whole-string
   * for names and keys, per-word for tags and descriptions, bonuses for
   * consecutive runs and word starts. */
  function fuzzyScore(query, target) {
    var q = String(query).toLowerCase();
    var t = String(target).toLowerCase();
    var qi = 0, score = 0, run = 0, last = -2;
    for (var ti = 0; ti < t.length && qi < q.length; ti++) {
      if (t[ti] === q[qi]) {
        score += 1;
        if (ti === last + 1) { run += 1; score += run; } else { run = 0; }
        if (ti === 0 || t[ti - 1] === " " || t[ti - 1] === "-") score += 3;
        last = ti;
        qi += 1;
      }
    }
    return qi === q.length ? score : null;
  }
  function wordScore(query, text, weight) {
    var best = null;
    var words = String(text).split(/[^A-Za-z0-9]+/);
    for (var i = 0; i < words.length; i++) {
      if (!words[i]) continue;
      var s = fuzzyScore(query, words[i]);
      if (s !== null) best = Math.max(best === null ? -1 : best, s * weight);
    }
    return best;
  }
  function matchTools(query) {
    var list = toolsCache || [];
    var q = String(query).trim().toLowerCase();
    if (!q) return list.slice();
    var scored = [];
    list.forEach(function (tool) {
      var best = null;
      var nameScore = fuzzyScore(q, tool.name);
      if (nameScore !== null) best = Math.max(best === null ? -1 : best, nameScore * 3 + (tool.name.toLowerCase().indexOf(q) === 0 ? 8 : 0));
      var keyScore = fuzzyScore(q, tool.key);
      if (keyScore !== null) best = Math.max(best === null ? -1 : best, keyScore * 2);
      var tagScore = wordScore(q, tool.tag, 1.2);
      if (tagScore !== null) best = Math.max(best === null ? -1 : best, tagScore);
      var descScore = wordScore(q, tool.description, 1);
      if (descScore !== null) best = Math.max(best === null ? -1 : best, descScore);
      if (best !== null) scored.push({ tool: tool, score: best });
    });
    scored.sort(function (a, b) { return b.score - a.score; });
    return scored.map(function (s) { return s.tool; });
  }

  function build(tool) {
    ensureStyle();
    var face = "";
    var mascot = document.querySelector(".tool-mascot-page");
    if (mascot) face = mascot.getAttribute("src") || "";
    if (!face) face = "/bum/cat-wink-blep.png";

    var strip = document.createElement("section");
    strip.className = "bb-strip";
    strip.setAttribute("aria-label", "Bum Bum handled that. Log something else.");
    strip.innerHTML =
      '<button type="button" class="bb-strip-close" aria-label="Dismiss">&times;</button>' +
      '<div class="bb-strip-head"><img class="bb-strip-face" src="' + esc(face) + '" alt="Bum Bum">' +
      "<div>" +
      '<p class="bb-strip-done">Handled by Bum Bum</p>' +
      '<p class="bb-strip-line">' + esc(LINES[tool] || "Done, dusted, and off your plate. That is the Bum Bum promise working as advertised.") + "</p>" +
      '<p class="bb-strip-invite">Like, you saved all that time. Imagine what else he can take off your plate. Got something else messy? Drop it here.</p>' +
      "</div></div>" +
      '<div class="bb-strip-composer">' +
      '<div class="bb-strip-pinrow" hidden>' +
      '<span class="bb-strip-pin"><img class="bb-strip-pin-img" alt=""><span class="bb-strip-pin-emoji" aria-hidden="true"></span><span class="bb-strip-pin-name"></span>' +
      '<button type="button" class="bb-strip-unpin" aria-label="Unpin tool" title="Unpin">&times;</button></span>' +
      '<span class="bb-strip-pin-note"></span>' +
      "</div>" +
      '<textarea class="bb-strip-input" rows="1" placeholder="' + esc(PLACEHOLDERS[0]) + '" autocomplete="off" maxlength="2000" aria-label="Type anything to log it"></textarea>' +
      '<div class="bb-strip-bar">' +
      '<button type="button" class="bb-strip-icon bb-strip-attach" aria-label="Attach a photo, video, or audio file" title="Attach a file">+</button>' +
      '<button type="button" class="bb-strip-icon bb-strip-mic" aria-label="Dictate instead" title="Dictate instead">&#127908;</button>' +
      '<button type="button" class="bb-strip-icon bb-strip-cam" aria-label="Take a photo" title="Take a photo">&#128247;</button>' +
      '<span class="bb-strip-spacer"></span>' +
      '<button type="button" class="bb-strip-btn bb-strip-go">Log it</button>' +
      "</div>" +
      '<div class="bb-strip-recrow" hidden><span class="bb-strip-recdot" aria-hidden="true"></span><canvas class="bb-strip-wave" width="132" height="28" aria-hidden="true"></canvas><span class="bb-strip-rectime">0:00</span><span class="bb-strip-recnote">Recording&hellip; tap &#127908; to stop.</span></div>' +
      '<p class="bb-strip-rechint" hidden>Your words land in the box when you stop. Then @ sends them on: Voice Notes to Text keeps the note, Ghostwriter (Voice to Content) makes it posts, SOP Generator makes it a procedure.</p>' +
      '<div class="bb-strip-mention" role="listbox" aria-label="Tools you can call with @" hidden></div>' +
      "</div>" +
      '<p class="bb-strip-hint">Psst: type @ and Bum Bum shows you everything he can do.</p>' +
      '<input type="file" class="bb-strip-file" accept="image/*,video/*,audio/*" style="display:none">' +
      '<input type="file" class="bb-strip-camera" accept="image/*" capture="environment" style="display:none">' +
      '<div class="bb-strip-result" aria-live="polite"></div>';

    // Placement is NOT done here: placeStrip() inserts the built strip
    // directly below the tool's result container at completion time.

    var input = strip.querySelector(".bb-strip-input");
    var go = strip.querySelector(".bb-strip-go");
    var attach = strip.querySelector(".bb-strip-attach");
    var micBtn = strip.querySelector(".bb-strip-mic");
    var camBtn = strip.querySelector(".bb-strip-cam");
    var picker = strip.querySelector(".bb-strip-file");
    var cameraPicker = strip.querySelector(".bb-strip-camera");
    var result = strip.querySelector(".bb-strip-result");
    var hint = strip.querySelector(".bb-strip-hint");
    var pinRow = strip.querySelector(".bb-strip-pinrow");
    var pinImg = strip.querySelector(".bb-strip-pin-img");
    var pinEmoji = strip.querySelector(".bb-strip-pin-emoji");
    var pinName = strip.querySelector(".bb-strip-pin-name");
    var pinNote = strip.querySelector(".bb-strip-pin-note");
    var unpinBtn = strip.querySelector(".bb-strip-unpin");
    var mentionBox = strip.querySelector(".bb-strip-mention");
    var recRow = strip.querySelector(".bb-strip-recrow");
    var recCanvas = strip.querySelector(".bb-strip-wave");
    var recTime = strip.querySelector(".bb-strip-rectime");
    var recHint = strip.querySelector(".bb-strip-rechint");

    var stagedImage = null;
    var busy = false;
    var lastKey = "";
    var pinned = null;
    var mention = null;
    var mentionMatches = [];
    var mentionIndex = 0;
    var phIndex = 0;
    var focused = false;
    var recording = false;
    var recorder = null;
    var chunks = [];
    var recStream = null;
    var recCtx = null;
    var recAnalyser = null;
    var recRaf = 0;
    var recTimer = 0;
    var recStart = 0;
    var discardRec = false;
    var stripPreviewUrl = null;

    loadTools();

    function show(html) { result.innerHTML = html; }
    function growInput() {
      input.style.height = "auto";
      input.style.height = Math.min(input.scrollHeight, 110) + "px";
    }

    /* Rotating example jobs, like the desk: pauses while the box has
     * focus, the tab is hidden, or a tool is pinned (the pinned sample
     * placeholder owns the box then). */
    var rotTimer = setInterval(function () {
      // A staged photo owns the placeholder (the photo-mode line) until
      // it is removed or routed.
      if (focused || pinned || stagedImage || document.hidden) return;
      phIndex = (phIndex + 1) % PLACEHOLDERS.length;
      input.placeholder = PLACEHOLDERS[phIndex];
    }, 3400);

    strip.querySelector(".bb-strip-close").addEventListener("click", function () {
      clearInterval(rotTimer);
      if (recording && recorder) {
        // Dismissed mid-recording: stop everything, keep nothing.
        discardRec = true;
        try { recorder.stop(); } catch (e) {}
      }
      revokeStripPreview();
      dismiss();
      strip.remove();
    });

    function getSession() {
      return fetch("/api/session.php", { credentials: "same-origin" }).then(function (r) { return r.json(); });
    }
    function fetchCapture(text, toolKey, fields, key) {
      return getSession().then(function (sess) {
        return fetch("/api/capture.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          credentials: "same-origin",
          body: JSON.stringify({ text: text, tool: toolKey || "", fields: fields || {}, csrf: sess.csrf, idempotencyKey: key || idem() })
        });
      }).then(function (r) { return r.json(); });
    }
    function netError() {
      busy = false;
      go.disabled = false;
      show('<p class="bb-strip-error">Could not reach Bum Bum. Check your connection and try again.</p>');
    }

    /* ---- Pins and the @ menu ---- */
    function setPinned(tool) {
      pinned = tool;
      pinRow.hidden = !tool;
      if (tool) {
        if (tool.iconImg) {
          pinImg.src = tool.iconImg;
          pinImg.style.display = "";
          pinEmoji.style.display = "none";
        } else {
          pinImg.style.display = "none";
          pinEmoji.style.display = "";
          pinEmoji.textContent = tool.icon || "";
        }
        pinName.textContent = "@" + tool.name;
        pinNote.textContent = "Everything you log goes to " + tool.name + ".";
        input.placeholder = PIN_PLACEHOLDERS[tool.key] || PLACEHOLDERS[phIndex];
      } else {
        input.placeholder = PLACEHOLDERS[phIndex];
      }
      refreshPhotoMode();
    }

    /* Photo mode for the hint line under the box, mirroring the desk:
     * while a photo is staged, the hint (and, unpinned, the placeholder)
     * teaches what the photo can become instead of the generic Psst. */
    function refreshPhotoMode() {
      if (stagedImage) {
        hint.textContent = pinned
          ? "Photo ready for " + pinned.name + ". Hit Log it and off it goes."
          : "Photo ready. @Receipt Reader turns it into a spreadsheet, @Poster to Calendar pulls out the events, @Meal Scanner reads the plate. Pick one, then Log it!";
        if (!pinned) input.placeholder = "Photo ready. @Receipt Reader, @Poster to Calendar, or @Meal Scanner\u2026";
      } else {
        hint.textContent = "Psst: type @ and Bum Bum shows you everything he can do.";
        if (!pinned) input.placeholder = PLACEHOLDERS[phIndex];
      }
    }
    unpinBtn.addEventListener("click", function () {
      setPinned(null);
      input.focus();
    });

    function renderMention() {
      if (!mention) {
        mentionMatches = [];
        mentionBox.hidden = true;
        mentionBox.innerHTML = "";
        return;
      }
      mentionMatches = matchTools(mention.query);
      if (!mentionMatches.length) {
        if (mention.query.trim() !== "") {
          mentionBox.innerHTML = '<div class="bb-strip-mention-empty">No tool matches &ldquo;' + esc(mention.query) + '&rdquo;. Bum Bum is stumped.</div>';
          mentionBox.hidden = false;
        } else {
          mentionBox.hidden = true;
          mentionBox.innerHTML = "";
        }
        return;
      }
      if (mentionIndex >= mentionMatches.length) mentionIndex = mentionMatches.length - 1;
      if (mentionIndex < 0) mentionIndex = 0;
      mentionBox.innerHTML = mentionMatches.map(function (t, i) {
        return '<button type="button" role="option" aria-selected="' + (i === mentionIndex) + '" class="bb-strip-mention-item' + (i === mentionIndex ? " active" : "") + '" data-i="' + i + '">' +
          (t.iconImg ? '<img src="' + esc(t.iconImg) + '" alt="">' : '<span class="bb-strip-mention-emoji" aria-hidden="true">' + esc(t.icon || "") + "</span>") +
          '<span class="bb-strip-mention-text"><b>' + esc(t.name) + "</b><small>" + esc(t.description) + "</small></span></button>";
      }).join("");
      mentionBox.hidden = false;
      mentionBox.querySelectorAll(".bb-strip-mention-item").forEach(function (btn) {
        btn.addEventListener("mousedown", function (e) {
          e.preventDefault();
          var t = mentionMatches[Number(btn.getAttribute("data-i"))];
          if (t) selectMention(t);
        });
        btn.addEventListener("mouseenter", function () {
          mentionIndex = Number(btn.getAttribute("data-i"));
          mentionBox.querySelectorAll(".bb-strip-mention-item").forEach(function (b, j) {
            b.classList.toggle("active", j === mentionIndex);
          });
        });
      });
    }

    // Watch the caret for an @ that starts a mention: at the start of
    // the box or right after whitespace, with only name-ish characters
    // between it and the caret. Same rule as the desk.
    function syncMention(value, caret) {
      var start = -1;
      for (var i = caret - 1; i >= 0; i--) {
        var ch = value[i];
        if (ch === "@") { start = i; break; }
        if (/\s/.test(ch)) break;
      }
      if (start >= 0 && (start === 0 || /\s/.test(value[start - 1]))) {
        var query = value.slice(start + 1, caret);
        if (/^[A-Za-z0-9-]*$/.test(query)) {
          mention = { start: start, query: query };
          mentionIndex = 0;
          renderMention();
          return;
        }
      }
      if (mention) { mention = null; renderMention(); }
    }

    function selectMention(tool) {
      if (!mention) return;
      var caret = input.selectionStart != null ? input.selectionStart : input.value.length;
      var next = input.value.slice(0, mention.start) + input.value.slice(caret);
      var at = mention.start;
      input.value = next;
      mention = null;
      renderMention();
      setPinned(tool);
      track("desk_mention_selected", { tool: tool.key, surface: "strip" });
      growInput();
      input.focus();
      try { input.setSelectionRange(at, at); } catch (e) {}
    }

    /* ---- File routing (pin-aware, same as the desk) ---- */
    function revokeStripPreview() {
      if (stripPreviewUrl) {
        try { URL.revokeObjectURL(stripPreviewUrl); } catch (e) {}
        stripPreviewUrl = null;
      }
    }

    function stashAndGo(file, url, name, via) {
      revokeStripPreview();
      track("capture_routed", { destination: name, surface: "strip", via: via, tool: tool });
      markDeskHandoff(toolKeyFromUrl(url), "file", { name: file.name });
      show('<p>Sending "' + esc(file.name) + '" to ' + esc(name) + ". Your file never leaves your device.</p>");
      storeHandoff(file).then(function () {
        setTimeout(function () { location.href = url + "?from=capture"; }, 700);
      }).catch(function () {
        show('<p class="bb-strip-error">Bum Bum could not stage that file in this browser. <a href="' + esc(url) + '">Open ' + esc(name) + "</a> and drop it in there.</p>");
      });
    }

    function fileKind(file) {
      if (file.type.indexOf("audio/") === 0) return "audio";
      if (file.type.indexOf("video/") === 0) return "video";
      if (file.type.indexOf("image/") === 0) return "image";
      return "other";
    }

    function routeFile(file) {
      // A different file arriving retires any staged photo; the same
      // file (re-submitted from the composer) keeps its staging.
      if (file !== stagedImage) stagedImage = null;
      revokeStripPreview();
      refreshPhotoMode();
      track("capture_started", { surface: "strip", kind: "file", tool: tool });
      track("drop_strip_capture", { tool: tool, kind: "file" });
      var kind = fileKind(file);
      if (pinned && kind !== "other") {
        var kinds = FILE_HANDOFF_KINDS[pinned.key];
        if (kinds && kinds.indexOf(kind) >= 0) {
          // The pinned tool can swallow this file: hand it straight over.
          stashAndGo(file, pinned.url, pinned.name, "mention");
          return;
        }
        if (kinds) {
          show("<p>" + esc(pinned.name) + " cannot take a " + kind + " file. Unpin it above the box, or drop a different file.</p>");
          return;
        }
      }
      if (kind === "audio") { stashAndGo(file, "/tools/notes/", "Voice Notes to Text", "file"); return; }
      if (kind === "video") { stashAndGo(file, "/tools/video-trimmer/", "Video Trimmer", "file"); return; }
      if (kind === "image") {
        if (pinned) {
          // The pinned tool takes no files at all (the handoff-kinds
          // cases returned above): never stage a photo behind a pin
          // that can never receive it.
          show("<p>" + esc(pinned.name) + " cannot take a photo. Unpin it above the box, or @ one of the photo tools: Receipt Reader, Poster to Calendar, Meal Scanner.</p>");
          return;
        }
        stageStripImage(file);
        return;
      }
      show('<p>That file type has Bum Bum stumped. He takes photos, videos, and audio here. Anything else, <a href="/tools/">the toolbox</a> probably has a tool for it.</p>');
    }

    /* An unpinned photo stages in the strip: preview chip with a real
     * remove control, and the hint under the box switches to photo
     * mode (refreshPhotoMode). Nothing routes until a photo tool is
     * pinned and Log it is hit. */
    function stageStripImage(file) {
      stagedImage = file;
      try { stripPreviewUrl = URL.createObjectURL(file); } catch (e) { stripPreviewUrl = null; }
      var mb = (file.size / 1048576).toFixed(1);
      show('<div class="bb-strip-filechip">' +
        (stripPreviewUrl
          ? '<img class="bb-strip-preview" src="' + stripPreviewUrl + '" alt="">'
          : '<span class="bb-strip-preview bb-strip-preview-icon" aria-hidden="true">&#128247;</span>') +
        '<span class="bb-strip-filemeta">' + esc(file.name) + " (" + mb + " MB)</span>" +
        '<button type="button" class="bb-strip-filex" aria-label="Remove ' + esc(file.name) + '" title="Remove file">&times;</button></div>');
      var x = result.querySelector(".bb-strip-filex");
      if (x) x.addEventListener("click", removeStagedImage);
      refreshPhotoMode();
    }

    // The × on the staged preview: discard the photo completely. No
    // charge, no navigation; focus returns to the box.
    function removeStagedImage() {
      stagedImage = null;
      revokeStripPreview();
      show("");
      refreshPhotoMode();
      input.focus();
    }

    /* ---- Text routing ---- */
    function wireResultLinks() {
      result.querySelectorAll("a[data-bb-suggest]").forEach(function (a) {
        a.addEventListener("click", function () {
          markDeskHandoff(a.getAttribute("data-bb-suggest"), "text", { copied: false, text: input.value.trim() });
        });
      });
      result.querySelectorAll("a[data-bb-view]").forEach(function (a) {
        a.addEventListener("click", function () {
          markDeskHandoff(a.getAttribute("data-bb-view"), "logged", { excerpt: a.getAttribute("data-bb-summary") || "" });
        });
      });
    }

    function renderAmbiguous(d) {
      var chips = (d.options || []).map(function (o) {
        return '<button type="button" class="bb-strip-btn bb-strip-chip" data-tool="' + esc(o.tool) + '">' + esc(o.tool_name) + "</button>";
      }).join("");
      chips += '<button type="button" class="bb-strip-btn bb-strip-chip" data-other="1">Other</button>';
      show("<p>Hmm, where should this go?</p><div>" + chips + "</div>" +
        '<div class="bb-strip-other" hidden>' +
        '<div class="bb-strip-other-path"><p><b>Already exists?</b> It might be sitting in the toolbox, just not one of the guesses above.</p>' +
        '<a class="bb-strip-btn bb-strip-chip bb-strip-other-btn" href="/tools/">Check the toolbox</a></div>' +
        '<div class="bb-strip-other-path"><p><b>Does not exist yet?</b> Request it in the community queue. The queue is public and demand driven, so there is no promised timeline. Want it guaranteed? Operators get one built within 36 hours.</p>' +
        '<button type="button" class="bb-strip-btn bb-strip-chip bb-strip-other-btn" data-req="1">Request it</button></div>' +
        "</div>");
      result.querySelectorAll(".bb-strip-chip[data-tool]").forEach(function (chip) {
        chip.addEventListener("click", function () { postCapture(d.text, chip.getAttribute("data-tool"), d.fields, lastKey); });
      });
      var otherBtn = result.querySelector("[data-other]");
      var otherBox = result.querySelector(".bb-strip-other");
      if (otherBtn && otherBox) {
        otherBtn.addEventListener("click", function () { otherBox.hidden = !otherBox.hidden; });
      }
      var toolboxLink = result.querySelector(".bb-strip-other a");
      if (toolboxLink) {
        toolboxLink.addEventListener("click", function () { track("desk_other_selected", { path: "toolbox", surface: "strip" }); });
      }
      var reqBtn = result.querySelector("[data-req]");
      if (reqBtn) {
        reqBtn.addEventListener("click", function () { requestFromStrip(d.text); });
      }
    }

    // The ambiguous state's "Request it" path. /requests/ takes no
    // prefill, so your words ride along on the clipboard, same as the desk.
    function requestFromStrip(text) {
      track("desk_other_selected", { path: "request", surface: "strip" });
      var value = String(text || "");
      var copied = false;
      function go() {
        show("<p>" + (copied
          ? "Your words are copied. Paste them into the request form on the next page."
          : "The request form is on the next page. Take your words with you.") + " Taking you there&hellip;</p>");
        setTimeout(function () { location.href = "/requests/"; }, copied ? 1700 : 1000);
      }
      if (value && navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(value).then(function () { copied = true; go(); }, function () { go(); });
      } else {
        go();
      }
    }

    function handleResponse(d) {
      busy = false;
      go.disabled = false;
      if (d.ok && d.logged === false) {
        var html = "<p>" + esc(d.message || "Can't do that from here.") + "</p>";
        if (d.suggest_url) {
          track("capture_routed", { destination: d.suggest_name || "", surface: "strip", via: "suggest", tool: tool });
          html += '<p><a href="' + esc(d.suggest_url) + '" data-bb-suggest="' + esc(toolKeyFromUrl(d.suggest_url)) + '">Open ' + esc(d.suggest_name || "the tool") + " &rarr;</a></p>";
        }
        show(html);
      } else if (d.ok) {
        track("capture_routed", { destination: d.tool || "", surface: "strip", via: d.via || "", tool: tool });
        input.value = "";
        growInput();
        show("<p><b>&#10003; " + esc(d.summary || "Logged it.") + '</b> <a href="' + esc(d.url || "/tools/") + '" data-bb-view="' + esc(toolKeyFromUrl(d.url || "")) + '" data-bb-summary="' + esc(d.summary || "Logged it.") + '">View in ' + esc(d.tool_name || "the tool") + " &rarr;</a></p>");
      } else if (d.ambiguous) {
        renderAmbiguous(d);
        return;
      } else if (d.limit_reached) {
        show('<p class="bb-strip-error">' + esc(d.error || "Out of actions.") + ' <a href="/account/">See your account</a></p>');
      } else {
        show('<p class="bb-strip-error">' + esc(d.error || "Something went wrong.") + "</p>");
      }
      wireResultLinks();
    }

    function postCapture(text, toolKey, fields, key) {
      busy = true;
      go.disabled = true;
      show("<p>Filing it&hellip;</p>");
      fetchCapture(text, toolKey, fields, key).then(handleResponse).catch(netError);
    }

    // Open a pinned tool the capture box cannot file into itself. The
    // words ride in the handoff marker: tools in FILL_FIELDS get them
    // dropped into their field on arrival, the rest ride along on the
    // clipboard; the marker makes the arrival greet the visitor,
    // same as the desk.
    function openPinnedTool(tool) {
      track("capture_routed", { destination: tool.key, surface: "strip", via: "mention", tool: tool });
      var value = input.value.trim();
      var copied = false;
      function go() {
        markDeskHandoff(tool.key, "text", { excerpt: value.slice(0, 90), copied: copied, text: value });
        var dropsIn = value.length > 0 && Object.prototype.hasOwnProperty.call(FILL_FIELDS, tool.key);
        show("<p>" + (dropsIn
          ? "Off to " + esc(tool.name) + ". Your words will be dropped in when it opens."
          : copied
          ? "Your words are copied. Paste them into " + esc(tool.name) + " when it opens. Taking you there&hellip;"
          : "Taking you to " + esc(tool.name) + "&hellip;") + "</p>");
        setTimeout(function () { location.href = tool.url; }, dropsIn || copied ? 1500 : 900);
      }
      if (value && navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(value).then(function () { copied = true; go(); }, function () { go(); });
      } else {
        go();
      }
    }

    // A pinned native tool: the pin is ABSOLUTE, same contract as the
    // desk. One fields-less forced request with tool=<pinned>; the
    // server files to the pinned tool and extracts fields for it alone.
    // If the server flatly declines (a task, not a log entry), the @
    // call wins and the tool simply opens.
    function pinnedNativeSubmit(tool) {
      var t = input.value.trim();
      if (!t || busy) return;
      busy = true;
      go.disabled = true;
      show("<p>Filing it&hellip;</p>");
      var key = idem();
      lastKey = key;
      fetchCapture(t, tool.key, {}, key).then(function (d) {
        if (d.ambiguous) return fetchCapture(t, tool.key, d.fields, key);
        return d;
      }).then(function (d) {
        if (d.ok && d.logged === false) {
          busy = false;
          go.disabled = false;
          openPinnedTool(tool);
          return;
        }
        handleResponse(d);
      }).catch(netError);
    }

    function submit() {
      if (busy) return;
      if (stagedImage) {
        if (pinned) { routeFile(stagedImage); return; }
        // Never guess a photo's destination silently: teach the @ pick
        // and keep the photo staged for it.
        show("<p>That photo needs a destination first. @Receipt Reader makes it a spreadsheet, @Poster to Calendar pulls out the events, @Meal Scanner reads the plate. Pick one above the box, then Log it!</p>");
        return;
      }
      var t = input.value.trim();
      if (pinned) {
        track("capture_started", { surface: "strip", kind: "text", tool: tool });
        track("drop_strip_capture", { tool: tool, kind: "text" });
        if (!t) { openPinnedTool(pinned); return; }
        if (NATIVE_TOOLS.indexOf(pinned.key) >= 0) { pinnedNativeSubmit(pinned); return; }
        openPinnedTool(pinned);
        return;
      }
      if (!t) return;
      lastKey = idem();
      track("capture_started", { surface: "strip", kind: "text", tool: tool });
      track("drop_strip_capture", { tool: tool, kind: "text" });
      postCapture(t, "", null, lastKey);
    }

    /* ---- Mic, mirroring the desk: MediaRecorder into the same
     * /api/voice-transcribe.php flow; the transcript lands in the box. */
    function transcribe(blob) {
      show("<p>Transcribing&hellip;</p>");
      getSession().then(function (sess) {
        var fd = new FormData();
        fd.append("audio", blob, "capture.webm");
        fd.append("csrf", sess.csrf);
        return fetch("/api/voice-transcribe.php", { method: "POST", credentials: "same-origin", body: fd });
      }).then(function (r) { return r.json(); }).then(function (d) {
        if (d && d.transcript) {
          input.value = d.transcript;
          growInput();
          show("<p>Heard you. Tap Log it to file it, or @ a tool first: Voice Notes to Text keeps the note, Ghostwriter (Voice to Content) makes it posts, SOP Generator makes it a procedure.</p>");
          input.focus();
        } else {
          show('<p class="bb-strip-error">' + esc((d && d.error) || "Could not transcribe that.") + "</p>");
        }
      }).catch(function () {
        show('<p class="bb-strip-error">Transcription failed. Try typing it.</p>');
      });
    }
    /* Recording readout, mirroring the desk: a live waveform off a
     * Web Audio analyser on the same stream, a pulsing dot, and a
     * ticking clock. Any Web Audio failure leaves the plain recording
     * behaviour untouched. */
    function fmtStripRecTime(ms) {
      var s = Math.floor(ms / 1000);
      return Math.floor(s / 60) + ":" + ("0" + (s % 60)).slice(-2);
    }
    function drawStripWave() {
      if (!recCanvas) return;
      var ctx = recCanvas.getContext("2d");
      if (!ctx) return;
      var w = recCanvas.width, h = recCanvas.height;
      ctx.clearRect(0, 0, w, h);
      var bars = 24, gap = 3;
      var bw = (w - gap * (bars - 1)) / bars;
      var data = null;
      if (recAnalyser) {
        data = new Uint8Array(recAnalyser.frequencyBinCount);
        recAnalyser.getByteFrequencyData(data);
      }
      for (var i = 0; i < bars; i++) {
        var v = data ? data[Math.floor((i / bars) * data.length * 0.7)] / 255 : 0.3;
        var bh = Math.max(3, v * h);
        ctx.fillStyle = i % 5 === 4 ? "#ffd84d" : "#ff6b35";
        ctx.fillRect(i * (bw + gap), (h - bh) / 2, bw, bh);
      }
    }
    function startStripRecUi() {
      recStart = Date.now();
      recTime.textContent = "0:00";
      if (recTimer) clearInterval(recTimer);
      recTimer = setInterval(function () { recTime.textContent = fmtStripRecTime(Date.now() - recStart); }, 250);
      var reduce = !!(window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches);
      if (reduce) {
        // Static bars under reduced motion; dot and clock still live.
        setTimeout(drawStripWave, 60);
        return;
      }
      var loop = function () {
        drawStripWave();
        recRaf = requestAnimationFrame(loop);
      };
      recRaf = requestAnimationFrame(loop);
    }
    function stopStripRecUi() {
      if (recRaf) { cancelAnimationFrame(recRaf); recRaf = 0; }
      if (recTimer) { clearInterval(recTimer); recTimer = 0; }
      if (recCtx) {
        var actx = recCtx;
        recCtx = null;
        try { var p = actx.close(); if (p && p.catch) p.catch(function () {}); } catch (e) {}
      }
      recAnalyser = null;
      recRow.hidden = true;
      recHint.hidden = true;
    }

    function toggleMic() {
      if (recording && recorder) { recorder.stop(); return; }
      if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia || typeof MediaRecorder === "undefined") {
        show('<p class="bb-strip-error">Voice input is not supported in this browser. Type it instead.</p>');
        return;
      }
      // The AudioContext is born inside the click, while the browser
      // still counts it as a user gesture.
      var actx = null;
      try {
        var AC = window.AudioContext || window.webkitAudioContext;
        if (AC) {
          actx = new AC();
          if (actx.resume) { var rp = actx.resume(); if (rp && rp.catch) rp.catch(function () {}); }
        }
      } catch (e) { actx = null; }
      navigator.mediaDevices.getUserMedia({ audio: true }).then(function (stream) {
        chunks = [];
        discardRec = false;
        recStream = stream;
        var rec = new MediaRecorder(stream);
        recorder = rec;
        rec.ondataavailable = function (e) { if (e.data && e.data.size) chunks.push(e.data); };
        rec.onstop = function () {
          stream.getTracks().forEach(function (t) { t.stop(); });
          recStream = null;
          stopStripRecUi();
          recording = false;
          micBtn.classList.remove("recording");
          micBtn.title = "Dictate instead";
          micBtn.setAttribute("aria-label", "Dictate instead");
          if (discardRec) { discardRec = false; return; }
          transcribe(new Blob(chunks, { type: rec.mimeType || "audio/webm" }));
        };
        if (actx) {
          recCtx = actx;
          try {
            var src = actx.createMediaStreamSource(stream);
            var analyser = actx.createAnalyser();
            analyser.fftSize = 128;
            src.connect(analyser);
            recAnalyser = analyser;
          } catch (e) { recAnalyser = null; }
        }
        rec.start();
        recording = true;
        micBtn.classList.add("recording");
        micBtn.title = "Stop recording";
        micBtn.setAttribute("aria-label", "Stop recording");
        recRow.hidden = false;
        recHint.hidden = false;
        startStripRecUi();
        show("<p>Recording&hellip; tap the mic again to stop.</p>");
      }).catch(function () {
        if (actx) { try { var cp = actx.close(); if (cp && cp.catch) cp.catch(function () {}); } catch (e) {} }
        show('<p class="bb-strip-error">Microphone access was blocked. Type it instead.</p>');
      });
    }

    /* ---- Wiring ---- */
    go.addEventListener("click", submit);
    attach.addEventListener("click", function () { picker.click(); });
    camBtn.addEventListener("click", function () { cameraPicker.click(); });
    micBtn.addEventListener("click", toggleMic);
    picker.addEventListener("change", function () {
      var f = picker.files && picker.files[0];
      picker.value = "";
      if (f) routeFile(f);
    });
    cameraPicker.addEventListener("change", function () {
      var f = cameraPicker.files && cameraPicker.files[0];
      cameraPicker.value = "";
      if (f) routeFile(f);
    });
    unpinBtn.addEventListener("click", function () { setPinned(null); input.focus(); });
    input.addEventListener("input", function () {
      growInput();
      syncMention(input.value, input.selectionStart != null ? input.selectionStart : input.value.length);
    });
    input.addEventListener("focus", function () { focused = true; });
    input.addEventListener("blur", function () {
      focused = false;
      setTimeout(function () { if (mention) { mention = null; renderMention(); } }, 150);
    });
    input.addEventListener("click", function () {
      syncMention(input.value, input.selectionStart != null ? input.selectionStart : input.value.length);
    });
    input.addEventListener("keyup", function (e) {
      if (["ArrowLeft", "ArrowRight", "Home", "End"].indexOf(e.key) >= 0) {
        syncMention(input.value, input.selectionStart != null ? input.selectionStart : input.value.length);
      }
    });
    input.addEventListener("keydown", function (e) {
      if (mention && mentionMatches.length > 0) {
        if (e.key === "ArrowDown") {
          e.preventDefault();
          mentionIndex = (mentionIndex + 1) % mentionMatches.length;
          renderMention();
          return;
        }
        if (e.key === "ArrowUp") {
          e.preventDefault();
          mentionIndex = (mentionIndex - 1 + mentionMatches.length) % mentionMatches.length;
          renderMention();
          return;
        }
        if (e.key === "Enter" || e.key === "Tab") {
          e.preventDefault();
          var t = mentionMatches[Math.min(mentionIndex, mentionMatches.length - 1)];
          if (t) selectMention(t);
          return;
        }
        if (e.key === "Escape") {
          e.preventDefault();
          mention = null;
          renderMention();
          return;
        }
      } else if (mention && e.key === "Escape") {
        e.preventDefault();
        mention = null;
        renderMention();
        return;
      }
      if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        submit();
      }
    });
    strip.addEventListener("dragover", function (e) { e.preventDefault(); strip.classList.add("drag-over"); });
    strip.addEventListener("dragleave", function () { strip.classList.remove("drag-over"); });
    strip.addEventListener("drop", function (e) {
      e.preventDefault();
      strip.classList.remove("drag-over");
      var f = e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0];
      if (f) routeFile(f);
    });
    strip.addEventListener("paste", function (e) {
      var f = e.clipboardData && e.clipboardData.files && e.clipboardData.files[0];
      if (f) { e.preventDefault(); routeFile(f); }
    });

    return strip;
  }

  function show(tool) {
    if (shown || dismissed()) return;
    if (!tool || !Object.prototype.hasOwnProperty.call(LINES, tool)) return;
    shown = true;
    currentTool = tool;
    placeStrip(tool);
  }

  /* ---- Completion placement (Item C) ----
   * The result comes first, the strip second: the strip is inserted
   * directly below the tool's result/output container and the page
   * scrolls the result into view (smooth, instant under reduced
   * motion). Result containers can lag the completion event by a beat
   * (the page renders, then tracks), so resolution polls briefly
   * before falling back to below the main work panel. */
  function resolveResultTarget(tool) {
    var main = document.querySelector("main.shell") || document.querySelector("main");
    if (!main) return null;
    var sel = RESULT_TARGETS[tool];
    var el = null;
    if (sel) {
      try { el = main.querySelector(sel); } catch (e) {}
    } else if (sel !== "") {
      // Unmapped tool: first visible element whose id says result/output.
      var all = main.querySelectorAll("[id]");
      for (var i = 0; i < all.length; i++) {
        if (/result|output/i.test(all[i].id) && anchorVisible(all[i])) { el = all[i]; break; }
      }
    }
    if (!el) return null;
    var insertEl = el;
    var tag = el.tagName;
    if (tag === "TEXTAREA" || tag === "INPUT" || tag === "SELECT" || tag === "CANVAS") {
      var lift = el.closest ? (el.closest(".panel") || el.closest("section")) : null;
      if (lift && main.contains(lift)) insertEl = lift;
    }
    return { insertAfter: insertEl, scrollEl: el, visible: anchorVisible(el) };
  }

  function scrollToResult(el) {
    if (!el) return;
    try {
      var reduce = !!(window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches);
      if (typeof el.scrollIntoView === "function") {
        el.scrollIntoView({ behavior: reduce ? "auto" : "smooth", block: "start" });
      }
    } catch (e) {}
  }

  function placeStrip(tool) {
    var tries = 0;
    (function attempt() {
      var target = resolveResultTarget(tool);
      if ((target && target.visible) || tries >= 10) {
        var strip = build(tool);
        var main = document.querySelector("main.shell") || document.querySelector("main");
        var scrollEl = null;
        var placement = "panel";
        if (target && target.insertAfter && target.insertAfter.parentNode) {
          target.insertAfter.parentNode.insertBefore(strip, target.insertAfter.nextSibling);
          placement = "result";
          scrollEl = target.visible ? target.scrollEl : workAnchor();
        } else {
          var panel = workAnchor();
          if (panel && panel.parentNode) {
            panel.parentNode.insertBefore(strip, panel.nextSibling);
            scrollEl = panel;
          } else if (main) {
            main.appendChild(strip);
          } else {
            document.body.appendChild(strip);
          }
        }
        track("drop_strip_shown", { tool: tool, placement: placement });
        scrollToResult(scrollEl);
        return;
      }
      tries++;
      setTimeout(attempt, 150);
    })();
  }

  // Pages report finished jobs through bbTrack('action_completed', {tool}).
  // Wrap it so the strip appears with zero per-page trigger code.
  function hook() {
    if (typeof window.bbTrack !== "function" || window.bbTrack.__bbStripWrapped) return;
    var orig = window.bbTrack;
    var wrapped = function (event, props) {
      try { orig(event, props); } catch (e) {}
      try {
        if (event === "action_completed" && props && props.tool) show(String(props.tool));
        else if (event === "gap_analyzed") show("gap-scout");
      } catch (e) {}
    };
    wrapped.__bbStripWrapped = true;
    window.bbTrack = wrapped;
  }
  hook();
  if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", hook);

  window.BBDropStrip = { show: show };

  /* ---- Front desk handoff banner ----
   * The homepage front desk leaves a sessionStorage marker before any
   * hop to a tool page ({tool, kind, name/excerpt, ts}). On arrival the
   * banner makes the carry-over obvious: his face, one line in his
   * voice, and what came along (the file name for file handoffs, the
   * clipboard note for navigate-only arrivals). The marker is cleared
   * when the banner shows, so it appears at most once per handoff; the
   * × dismisses it, and it also clears the moment the visitor starts
   * using the tool itself. In-flow only, it never overlays the tool.
   * The banner parks directly above the tool's primary action area and
   * the page scrolls to it, so an arrival opens at the work (the
   * dropzone and whatever it now holds), not at the marketing header. */
  var HANDOFF_KEY = "bb-desk-handoff";

  /* A navigate-only text handoff can land IN the tool, not just on
   * the clipboard: each mapped tool's primary field, read from the
   * page's own markup. Tools not listed here have no single field
   * that fits the words the desk sends (purrsuit's structured
   * add-someone form wants name/company/email in separate boxes,
   * clips records, doodle draws, gap-scout and audiogram take
   * files), so those keep the clipboard path. */
  var FILL_FIELDS = {
    "purr-code": "#pcUrl",
    "ghostwriter": "#gwPasteText",
    "sop-ify": "#sopDump",
    "hearsay": "#hsBrand"
  };

  function fillHandoffField(toolKey, text) {
    var sel = FILL_FIELDS[toolKey];
    if (!sel || !text) return false;
    var el = null;
    try { el = document.querySelector(sel); } catch (e) {}
    if (!el || el.disabled || el.readOnly) return false;
    el.value = text;
    // Announce it exactly like typing, so the page's own handlers
    // (live previews, counters, enable-on-input buttons) react.
    try {
      el.dispatchEvent(new Event("input", { bubbles: true }));
      el.dispatchEvent(new Event("change", { bubbles: true }));
    } catch (e) {}
    return true;
  }

  function anchorVisible(el) {
    if (!el) return false;
    if (el.classList && el.classList.contains("hidden")) return false;
    try {
      var cs = window.getComputedStyle ? window.getComputedStyle(el) : null;
      if (cs && (cs.display === "none" || cs.visibility === "hidden")) return false;
    } catch (e) {}
    return true;
  }

  // The tool's primary action area: the panel the carried item lands
  // in. Photo and video tools mark it with a .dropzone; the rest are
  // found by scanning for the first visible panel that holds working
  // controls (guest sign-in panels hold none, so they are skipped).
  function workAnchor() {
    var main = document.querySelector("main.shell") || document.querySelector("main");
    if (!main) return null;
    var dz = main.querySelector(".dropzone");
    if (dz && anchorVisible(dz)) {
      var panel = dz.closest ? dz.closest(".panel") : null;
      return panel || dz;
    }
    var panels = main.querySelectorAll(".panel");
    for (var i = 0; i < panels.length; i++) {
      if (!anchorVisible(panels[i])) continue;
      if (panels[i].querySelector("input, textarea, select, form, canvas, button")) return panels[i];
    }
    var form = main.querySelector("form");
    if (form) return form;
    return null;
  }

  function pageToolKey() {
    var m = location.pathname.match(/\/tools\/([^/]+)\//);
    return m ? m[1] : "";
  }

  function handoffDetail(data, fileName) {
    if (data.kind === "file") {
      var name = fileName || data.name || "Your file";
      return "\u201C" + name + "\u201D came with you and is ready below.";
    }
    if (data.kind === "logged") {
      return data.excerpt ? "\u201C" + data.excerpt + "\u201D Already filed from the front desk." : "Already filed from the front desk.";
    }
    if (data.copied) return "Your words are on your clipboard, ready to paste in.";
    return "You called this one up from the front desk.";
  }

  function showHandoffBanner() {
    var raw = null;
    try { raw = sessionStorage.getItem(HANDOFF_KEY); } catch (e) {}
    if (!raw) return;
    var data = null;
    try { data = JSON.parse(raw); } catch (e) {}
    // The marker is spent either way: one banner per handoff, never a repeat.
    try { sessionStorage.removeItem(HANDOFF_KEY); } catch (e) {}
    if (!data || !data.tool) return;
    if (Date.now() - (data.ts || 0) > 10 * 60 * 1000) return;
    if (data.tool !== pageToolKey()) return;

    ensureStyle();
    var face = "";
    var mascot = document.querySelector(".tool-mascot-page");
    if (mascot) face = mascot.getAttribute("src") || "";
    if (!face) face = "/bum/cat-wink-blep.png";

    var banner = document.createElement("section");
    banner.className = "bb-handoff";
    banner.setAttribute("aria-label", "Carried over from the front desk");
    banner.innerHTML =
      '<img class="bb-handoff-face" src="' + esc(face) + '" alt="Bum Bum">' +
      '<p class="bb-handoff-line"><b>Bum Bum carried this over from the front desk.</b> <span class="bb-handoff-detail"></span></p>' +
      '<button type="button" class="bb-handoff-close" aria-label="Dismiss">&times;</button>';

    var detail = banner.querySelector(".bb-handoff-detail");
    var lineBold = banner.querySelector(".bb-handoff-line b");
    // The words themselves, dropped into the tool's field before any
    // dismissal listener exists, so nothing mistakes the fill for the
    // visitor's first interaction. No field, no fill: the clipboard
    // note (or the plain arrival line) carries on as before.
    var filled = false;
    if (data.kind === "text" && data.text) {
      filled = fillHandoffField(data.tool, String(data.text));
    }
    var consumedName = "";
    try { consumedName = window.__bbHandoffFileName || ""; } catch (e) {}
    function paintDetail() {
      if (filled) {
        if (lineBold) lineBold.textContent = "Bum Bum carried this over from the front desk and dropped it in for you.";
        detail.textContent = "";
        return;
      }
      detail.textContent = handoffDetail(data, consumedName);
    }
    paintDetail();

    var anchor = workAnchor();
    var main = document.querySelector("main.shell") || document.querySelector("main");
    // The banner is a full-width band, never a cell inside the tool's
    // own layout: climb from the work panel to the top-level block
    // inside <main> that contains it (on purr-code, the two-column
    // grid around the settings panel) and park the banner directly
    // above that block, spanning the whole content container.
    var host = anchor;
    if (anchor && main && main.contains(anchor)) {
      while (host.parentNode && host.parentNode !== main) host = host.parentNode;
    }
    if (host && host.parentNode) {
      host.parentNode.insertBefore(banner, host);
    } else if (main) {
      main.insertBefore(banner, main.firstChild);
    } else if (document.body) {
      document.body.insertBefore(banner, document.body.firstChild);
    } else {
      return;
    }
    track("desk_handoff_banner_shown", { tool: data.tool });

    // Land at the work: the banner sits directly above the tool's
    // action area, so scrolling the banner to the top of the viewport
    // puts the carried file and the primary action in view with it.
    // Reduced motion gets an instant jump instead of the glide.
    if (anchor) {
      try {
        var reduce = !!(window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches);
        if (typeof banner.scrollIntoView === "function") {
          banner.scrollIntoView({ behavior: reduce ? "auto" : "smooth", block: "start" });
        }
      } catch (e) {}
    }

    var gone = false;
    function remove() {
      if (gone) return;
      gone = true;
      document.removeEventListener("input", onUse, true);
      document.removeEventListener("change", onUse, true);
      document.removeEventListener("pointerdown", onUse, true);
      window.removeEventListener("bb-handoff-file", onFile);
      if (banner.parentNode) banner.parentNode.removeChild(banner);
    }
    function onUse(e) {
      if (banner.contains(e.target)) return;
      if (e.type === "pointerdown") {
        var t = e.target;
        if (!(t.closest && t.closest("input, textarea, select, button, a, [role='button']"))) return;
      }
      remove();
    }
    function onFile(e) {
      consumedName = (e && e.detail && e.detail.name) || consumedName;
      paintDetail();
    }
    banner.querySelector(".bb-handoff-close").addEventListener("click", remove);
    document.addEventListener("input", onUse, true);
    document.addEventListener("change", onUse, true);
    document.addEventListener("pointerdown", onUse, true);
    window.addEventListener("bb-handoff-file", onFile);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", showHandoffBanner);
  } else {
    showHandoffBanner();
  }
})();
