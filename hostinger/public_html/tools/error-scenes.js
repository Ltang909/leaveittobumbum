/* Bum Bum error scenes: shared renderer for tool error states.
   "Pet Pet reports it. Bum Bum handles it."
   Copy source of truth: docs/error-scenes-spec.md (use its strings verbatim).
   Usage:
     BBErrorScenes.mount(el, "upload_failed");                       // full scene
     BBErrorScenes.mount(el, "empty_input", { size: "compact" });    // compact
     BBErrorScenes.mount(el, "upload_failed", { onCta: retryFn });   // wire the CTA
   Overrides (img, alt, petpet, bumbum, cta) are supported for tool-specific
   variants such as the Purr Code copy swaps in the spec.
   Styles are injected once, every selector carries the es- prefix. */
(function () {
  "use strict";

  var SCENES = {
    empty_input: {
      img: "/bum/error-empty.jpg",
      alt: "Pet Pet the white cat holding an empty tray, looking puzzled, while Bum Bum sits beside a stack of folders",
      petpet: "There is nothing in this.",
      bumbum: "Nothing in, nothing out. Add your file or text and I will get to work.",
      cta: "Add something"
    },
    upload_failed: {
      img: "/bum/error-upload.jpg",
      alt: "Bum Bum pushing a thick folder through a cat flap while Pet Pet watches, worried",
      petpet: "This one would not go through. Not my department.",
      bumbum: "It is mine. Try the upload once more. If it sulks again, a smaller file or a different format usually fixes it.",
      cta: "Try again"
    },
    file_too_large: {
      img: "/bum/error-too-large.jpg",
      alt: "Pet Pet flattened under a huge cardboard box of papers while Bum Bum steadies the box",
      petpet: "It is very large. I have concerns.",
      bumbum: "Fair concern. Shrink it a little and hand it back to me.",
      cta: "Choose a smaller file"
    },
    connection_lost: {
      img: "/bum/error-offline.jpg",
      alt: "Bum Bum holding an unplugged network cable next to a router while Pet Pet gasps",
      petpet: "The internet has left the building.",
      bumbum: "Your work is still right here. Reconnect and pick up where you left off.",
      cta: "Reconnect"
    },
    processing_failed: {
      /* Placeholder art: the cat flap scene stands in until the smoking
         machine image lands at /bum/error-processing.jpg. Swap img then;
         the alt below already describes the scene shown. */
      img: "/bum/error-upload.jpg",
      alt: "Bum Bum pushing a thick folder through a cat flap while Pet Pet watches, worried",
      petpet: "Found a problem. Escalating it.",
      bumbum: "And I have got it from here. Something jammed on my side, not yours. Your file is safe. Give me one more go.",
      cta: "Run it again"
    },
    invalid_type: {
      img: "/bum/error-upload.jpg",
      alt: "Bum Bum pushing a thick folder through a cat flap while Pet Pet watches, worried",
      petpet: "It will not fit. I measured twice.",
      bumbum: "That format is not one I can open. Try one of these instead: [tool lists its accepted formats].",
      cta: "Choose a different file"
    }
  };

  var CSS = [
    ".es-scene{font-family:inherit;color:var(--ink,#142015)}",
    ".es-full{background:#fff;border:2px solid var(--line,#1a291c);border-radius:22px;box-shadow:8px 8px 0 var(--line,#1a291c);padding:26px;text-align:left;max-width:560px}",
    ".es-full .es-img{display:block;width:100%;max-width:420px;height:auto;border:2px solid var(--line,#1a291c);border-radius:16px;margin:0 0 18px}",
    ".es-petpet{margin:0 0 6px;font-size:15px;font-weight:700;color:var(--muted,#667064)}",
    ".es-bumbum{margin:0;font-size:18px;font-weight:700;line-height:1.5}",
    ".es-cta{display:inline-block;margin-top:16px;border:2px solid var(--line,#1a291c);background:var(--lime,#d8ff62);color:var(--ink,#142015);font:inherit;font-weight:900;font-size:16px;padding:12px 20px;border-radius:999px;cursor:pointer;text-decoration:none}",
    ".es-cta:hover{background:var(--orange,#ff7448)}",
    ".es-compact{display:flex;gap:14px;align-items:flex-start;background:#fff;border:2px solid var(--line,#1a291c);border-radius:16px;padding:14px 16px;max-width:560px}",
    ".es-compact .es-img{flex:none;width:96px;height:96px;object-fit:cover;border:2px solid var(--line,#1a291c);border-radius:14px}",
    ".es-compact .es-lines{min-width:0}",
    ".es-compact .es-petpet{font-size:13.5px;margin:0 0 3px}",
    ".es-compact .es-bumbum{font-size:15.5px}",
    ".es-compact .es-cta{margin-top:10px;font-size:14px;padding:8px 14px}"
  ].join("\n");

  var stylesInjected = false;
  function injectStyles() {
    if (stylesInjected) return;
    stylesInjected = true;
    var style = document.createElement("style");
    style.setAttribute("data-es-scenes", "");
    style.textContent = CSS;
    document.head.appendChild(style);
  }

  function esc(s) {
    return String(s)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  /* render(state, opts) -> HTML string.
     opts: { size: "full"|"compact", img, alt, petpet, bumbum, cta } */
  function render(state, opts) {
    opts = opts || {};
    var base = SCENES[state] || {};
    var s = {
      img: opts.img || base.img || "",
      alt: opts.alt || base.alt || "",
      petpet: opts.petpet || base.petpet || "",
      bumbum: opts.bumbum || base.bumbum || "",
      cta: opts.cta || base.cta || ""
    };
    var size = opts.size === "compact" ? "compact" : "full";
    var html =
      '<div class="es-scene es-' + size + '" role="alert">' +
      (s.img ? '<img class="es-img" src="' + esc(s.img) + '" alt="' + esc(s.alt) + '">' : "") +
      (size === "compact" ? '<div class="es-lines">' : "") +
      (s.petpet ? '<p class="es-petpet">' + esc(s.petpet) + "</p>" : "") +
      (s.bumbum ? '<p class="es-bumbum">' + esc(s.bumbum) + "</p>" : "") +
      (s.cta ? '<button type="button" class="es-cta">' + esc(s.cta) + "</button>" : "") +
      (size === "compact" ? "</div>" : "") +
      "</div>";
    return html;
  }

  /* mount(el, state, opts): renders into el and wires the CTA.
     opts.onCta, when given, runs on click. Without it the button is inert
     and its click is simply swallowed (preview pages rely on this). */
  function mount(el, state, opts) {
    if (!el) return null;
    opts = opts || {};
    injectStyles();
    el.innerHTML = render(state, opts);
    var btn = el.querySelector(".es-cta");
    if (btn) {
      btn.addEventListener("click", function (ev) {
        ev.preventDefault();
        if (typeof opts.onCta === "function") opts.onCta(ev);
      });
    }
    return el.firstElementChild;
  }

  window.BBErrorScenes = { SCENES: SCENES, render: render, mount: mount };
})();
