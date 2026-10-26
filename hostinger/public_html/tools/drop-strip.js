/* Bum Bum post-task drop strip.
 *
 * After a tool finishes a job (an action completes), Bum Bum shows up in
 * person: his per-tool portrait, a payoff line about the time he just
 * handed back, and a compact "Log anything" box wired to the same
 * /api/capture.php router as the dashboard and homepage boxes. One more
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
  var IMAGE_DESTINATIONS = [
    { name: "Receipt Reader", url: "/tools/receipt-reader/", label: "A receipt" },
    { name: "Poster to Calendar", url: "/tools/poster-to-calendar/", label: "An event poster" },
    { name: "Meal Scanner", url: "/tools/meal-scanner/", label: "A meal" }
  ];

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

  var CSS = [
    ".bb-strip{background:#fffdf4;border:2px solid var(--line,#1a291c);border-radius:22px;box-shadow:8px 8px 0 var(--line,#1a291c);padding:24px 26px;margin:30px 0;position:relative}",
    ".bb-strip-close{position:absolute;top:10px;right:12px;border:2px solid var(--line,#1a291c);background:#fff;border-radius:50%;width:32px;height:32px;font-weight:900;cursor:pointer;line-height:1;margin:0;padding:0}",
    ".bb-strip-head{display:flex;gap:18px;align-items:flex-start}",
    ".bb-strip-face{width:74px;height:74px;flex:none;object-fit:contain;background:#fff;border:2px solid var(--line,#1a291c);border-radius:18px;padding:5px;transform:rotate(-3deg)}",
    ".bb-strip-done{font-weight:900;text-transform:uppercase;letter-spacing:.1em;font-size:12px;margin:2px 0 6px}",
    ".bb-strip-line{font-size:19px;line-height:1.45;margin:0;font-weight:700}",
    ".bb-strip-invite{margin:8px 0 0;font-size:16px}",
    ".bb-strip-row{display:flex;gap:10px;align-items:stretch;margin-top:16px}",
    ".bb-strip-row input{flex:1;min-width:0;padding:14px 16px;font-size:16px;border:2px solid var(--line,#1a291c);border-radius:10px;font:inherit}",
    ".bb-strip-row input:focus{outline:3px solid var(--lime,#d8ff62);outline-offset:1px}",
    ".bb-strip-btn{flex:none;border:2px solid var(--line,#1a291c);background:#fff;color:var(--ink,#142015);font-weight:900;padding:12px 16px;border-radius:999px;cursor:pointer;font:inherit;margin:0}",
    ".bb-strip-go{background:var(--lime,#d8ff62)}",
    ".bb-strip-result p{margin:10px 0 0}",
    ".bb-strip-error{color:#9b2516;font-weight:700}",
    ".bb-strip-chip{margin:8px 8px 0 0}",
    ".bb-strip.drag-over{background:#fff3bf}",
    ".bb-handoff{display:flex;gap:14px;align-items:center;background:#fff3bf;border:2px solid var(--line,#1a291c);border-radius:16px;padding:12px 16px;margin:18px 0 4px;position:relative}",
    ".bb-handoff-face{width:46px;height:46px;flex:none;object-fit:contain;background:#fff;border:2px solid var(--line,#1a291c);border-radius:12px;padding:3px}",
    ".bb-handoff-line{margin:0;font-size:15.5px;line-height:1.45;flex:1}",
    ".bb-handoff-close{flex:none;border:2px solid var(--line,#1a291c);background:#fff;border-radius:50%;width:28px;height:28px;font-weight:900;cursor:pointer;line-height:1;margin:0;padding:0}",
    "@media(max-width:700px){.bb-strip{padding:20px 16px}.bb-strip-head{flex-direction:column;gap:10px}.bb-strip-row{flex-wrap:wrap}.bb-strip-row input{flex:1 1 100%}.bb-handoff{padding:10px 12px}.bb-handoff-face{width:38px;height:38px}}"
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
      '<div class="bb-strip-row">' +
      '<input type="text" class="bb-strip-input" placeholder="Netflix $15.99 a month &hellip; invoice Acme $400 &hellip; idea: a loyalty card for regulars" autocomplete="off" maxlength="2000" aria-label="Type anything to log it">' +
      '<button type="button" class="bb-strip-btn bb-strip-attach" title="Attach a photo, video, or audio file">&#128206;</button>' +
      '<button type="button" class="bb-strip-btn bb-strip-go">Log it</button>' +
      "</div>" +
      '<input type="file" class="bb-strip-file" accept="image/*,video/*,audio/*" style="display:none">' +
      '<div class="bb-strip-result" aria-live="polite"></div>';

    var main = document.querySelector("main.shell") || document.querySelector("main");
    var firstSection = main ? main.querySelector("section") : null;
    if (main && firstSection && firstSection.parentNode === main) {
      main.insertBefore(strip, firstSection.nextSibling);
    } else if (main) {
      main.appendChild(strip);
    } else {
      document.body.appendChild(strip);
    }

    var input = strip.querySelector(".bb-strip-input");
    var go = strip.querySelector(".bb-strip-go");
    var attach = strip.querySelector(".bb-strip-attach");
    var picker = strip.querySelector(".bb-strip-file");
    var result = strip.querySelector(".bb-strip-result");
    var attachedFile = null;
    var pendingImage = null;
    var busy = false;
    var lastKey = "";

    function show(html) { result.innerHTML = html; }

    strip.querySelector(".bb-strip-close").addEventListener("click", function () {
      dismiss();
      strip.remove();
    });

    function stashAndGo(file, url, name, via) {
      track("capture_routed", { destination: name, surface: "strip", via: via, tool: tool });
      try {
        var m = url.match(/\/tools\/([^/]+)\//);
        sessionStorage.setItem("bb-desk-handoff", JSON.stringify({ tool: m ? m[1] : "", kind: "file", name: file.name, ts: Date.now() }));
      } catch (e) {}
      show('<p>Sending "' + esc(file.name) + '" to ' + esc(name) + ". Your file never leaves your device.</p>");
      storeHandoff(file).then(function () {
        setTimeout(function () { location.href = url + "?from=capture"; }, 700);
      }).catch(function () {
        show('<p class="bb-strip-error">Bum Bum could not stage that file in this browser. <a href="' + esc(url) + '">Open ' + esc(name) + "</a> and drop it in there.</p>");
      });
    }

    function routeFile(file) {
      track("capture_started", { surface: "strip", kind: "file", tool: tool });
      track("drop_strip_capture", { tool: tool, kind: "file" });
      var kind = file.type.indexOf("audio/") === 0 ? "audio"
        : file.type.indexOf("video/") === 0 ? "video"
        : file.type.indexOf("image/") === 0 ? "image" : "other";
      if (kind === "audio") { stashAndGo(file, "/tools/notes/", "Voice Notes to Text", "file"); return; }
      if (kind === "video") { stashAndGo(file, "/tools/video-trimmer/", "Video Trimmer", "file"); return; }
      if (kind === "image") {
        pendingImage = file;
        var chips = IMAGE_DESTINATIONS.map(function (d) {
          return '<button type="button" class="bb-strip-btn bb-strip-chip" data-url="' + esc(d.url) + '" data-name="' + esc(d.name) + '">' + esc(d.label) + "</button>";
        }).join("");
        show("<p>Got it. What is this a photo of?</p><div>" + chips + "</div>");
        result.querySelectorAll(".bb-strip-chip").forEach(function (chip) {
          chip.addEventListener("click", function () {
            var f = pendingImage;
            pendingImage = null;
            if (f) stashAndGo(f, chip.getAttribute("data-url"), chip.getAttribute("data-name"), "file-picker");
          });
        });
        return;
      }
      show('<p>That file type has Bum Bum stumped. He takes photos, videos, and audio here. Anything else, <a href="/tools/">the toolbox</a> probably has a tool for it.</p>');
    }

    function send(text, forcedTool, fields, key) {
      show("<p>Filing it&hellip;</p>");
      busy = true;
      go.disabled = true;
      fetch("/api/session.php").then(function (r) { return r.json(); }).then(function (sess) {
        return fetch("/api/capture.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ text: text, tool: forcedTool || "", fields: fields || {}, csrf: sess.csrf, idempotencyKey: key || idem() })
        });
      }).then(function (r) { return r.json().then(function (d) { return { status: r.status, data: d }; }); }).then(function (res) {
        busy = false;
        go.disabled = false;
        var d = res.data;
        if (d.ok && d.logged === false) {
          var html = "<p>" + esc(d.message || "Can't do that from here.") + "</p>";
          if (d.suggest_url) {
            track("capture_routed", { destination: d.suggest_name || "", surface: "strip", via: "suggest", tool: tool });
            html += '<p><a href="' + esc(d.suggest_url) + '">Open ' + esc(d.suggest_name || "the tool") + " &rarr;</a></p>";
          }
          show(html);
        } else if (d.ok) {
          track("capture_routed", { destination: d.tool || "", surface: "strip", via: d.via || "", tool: tool });
          input.value = "";
          attachedFile = null;
          show("<p><b>&#10003; " + esc(d.summary || "Logged it.") + '</b> <a href="' + esc(d.url || "/tools/") + '">View in ' + esc(d.tool_name || "the tool") + " &rarr;</a></p>");
        } else if (d.ambiguous) {
          var chips = (d.options || []).map(function (o) {
            return '<button type="button" class="bb-strip-btn bb-strip-chip" data-tool="' + esc(o.tool) + '">' + esc(o.tool_name) + "</button>";
          }).join("");
          show("<p>Hmm, where should this go?</p><div>" + chips + "</div>");
          result.querySelectorAll(".bb-strip-chip").forEach(function (chip) {
            chip.addEventListener("click", function () { send(d.text, chip.getAttribute("data-tool"), d.fields, lastKey); });
          });
        } else if (d.limit_reached) {
          show('<p class="bb-strip-error">' + esc(d.error || "Out of actions.") + ' <a href="/account/">See your account</a></p>');
        } else {
          show('<p class="bb-strip-error">' + esc(d.error || "Something went wrong.") + "</p>");
        }
      }).catch(function () {
        busy = false;
        go.disabled = false;
        show('<p class="bb-strip-error">Could not reach Bum Bum. Check your connection and try again.</p>');
      });
    }

    function submit() {
      if (busy) return;
      if (attachedFile) { routeFile(attachedFile); return; }
      var t = input.value.trim();
      if (!t) return;
      lastKey = idem();
      track("capture_started", { surface: "strip", kind: "text", tool: tool });
      track("drop_strip_capture", { tool: tool, kind: "text" });
      send(t, "", null, lastKey);
    }

    go.addEventListener("click", submit);
    input.addEventListener("keydown", function (e) { if (e.key === "Enter") submit(); });
    attach.addEventListener("click", function () { picker.click(); });
    picker.addEventListener("change", function () {
      var f = picker.files && picker.files[0];
      picker.value = "";
      if (f) routeFile(f);
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
    build(tool);
    track("drop_strip_shown", { tool: tool });
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
    var consumedName = "";
    try { consumedName = window.__bbHandoffFileName || ""; } catch (e) {}
    function paintDetail() { detail.textContent = handoffDetail(data, consumedName); }
    paintDetail();

    var anchor = workAnchor();
    var main = document.querySelector("main.shell") || document.querySelector("main");
    if (anchor && anchor.parentNode) {
      anchor.parentNode.insertBefore(banner, anchor);
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
