/* Bum Bum capture handoff: receive a file the user attached in the dashboard
   capture box ("Log anything"). The file travels via IndexedDB on this same
   origin and is NEVER uploaded to the server, matching how these tools
   already work (browser-side processing). Inert unless ?from=capture is in
   the URL. The page exposes window.__bbCaptureHandoff(file) to accept it.
 *
 * Auto-run: a handed-off file should not just sit in the tool waiting for
 * a second click the desk already implied. Once the file is delivered,
 * tools with a real primary action run it, by clicking the page's OWN
 * button (same handler, same metering, same error scenes, same usage
 * walls). Tools whose work is inherently interactive (picking a crop
 * box, setting trim points) stay load-only: there is no honest default
 * run to trigger for them.
 *   notes              transcribes inside its own handoff handler
 *   receipt-reader     clicks #processBtn once a read is queued
 *   poster-to-calendar clicks #processBtn once a read is queued
 *   meal-scanner       clicks #processBtn once a scan is queued
 *   image-converter    clicks #convertBtn (format select keeps its
 *                      preselected default)
 *   video-converter    clicks Continue, then Convert (the format step
 *                      preselects MP4, the page's own default)
 *   image-cropper      load-only (the crop region is the user's call)
 *   video-trimmer      load-only (the trim points are the user's call)
 * Auto-run fires at most once per handoff, only on the fresh page load
 * that consumed it, and reports desk_autorun per tool that runs. */
(function () {
  var q;
  try { q = new URLSearchParams(location.search); } catch (e) { return; }
  if (!q || q.get('from') !== 'capture') return;
  if (!('indexedDB' in window)) return;

  function toolKey() {
    var m = location.pathname.match(/\/tools\/([^/]+)\//);
    return m ? m[1] : '';
  }
  function track(event, props) {
    try { if (typeof window.bbTrack === 'function') window.bbTrack(event, props || {}); } catch (e) {}
  }

  var autorunStarted = false;
  function clickWhenReady(sel, timeoutMs, done) {
    var started = Date.now();
    (function poll() {
      var btn = document.querySelector(sel);
      if (btn && !btn.disabled && !btn.classList.contains('hidden')) { done(btn); return; }
      if (Date.now() - started < timeoutMs) setTimeout(poll, 120);
    })();
  }

  function scheduleAutorun() {
    if (autorunStarted) return;
    var tool = toolKey();
    if (tool === 'notes') {
      // The notes page starts transcribing inside its own
      // __bbCaptureHandoff, so delivery already is the run.
      autorunStarted = true;
      track('desk_autorun', { tool: tool, surface: 'tool' });
      return;
    }
    if (tool === 'receipt-reader' || tool === 'poster-to-calendar' || tool === 'meal-scanner') {
      autorunStarted = true;
      clickWhenReady('#processBtn', 8000, function (btn) {
        track('desk_autorun', { tool: tool, surface: 'tool' });
        btn.click();
      });
      return;
    }
    if (tool === 'image-converter') {
      autorunStarted = true;
      clickWhenReady('#convertBtn', 8000, function (btn) {
        track('desk_autorun', { tool: tool, surface: 'tool' });
        btn.click();
      });
      return;
    }
    if (tool === 'video-converter') {
      autorunStarted = true;
      clickWhenReady('#vcToFmt', 8000, function (toFmt) {
        track('desk_autorun', { tool: tool, surface: 'tool' });
        toFmt.click();
        setTimeout(function () {
          var conv = document.querySelector('#vcConvert');
          if (conv) conv.click();
        }, 60);
      });
      return;
    }
    // image-cropper / video-trimmer: load-only, by design (see header).
  }

  var openReq = indexedDB.open('bumbum-capture', 1);
  openReq.onupgradeneeded = function (e) {
    var db = e.target.result;
    if (!db.objectStoreNames.contains('handoff')) db.createObjectStore('handoff');
  };
  openReq.onsuccess = function (e) {
    var db = e.target.result;
    var tx;
    try { tx = db.transaction('handoff', 'readwrite'); } catch (err) { return; }
    var store = tx.objectStore('handoff');
    var get = store.get('pending');
    get.onsuccess = function () {
      var rec = get.result;
      try { store.delete('pending'); } catch (err) {}
      if (!rec || !rec.file) return;
      if (Date.now() - (rec.ts || 0) > 10 * 60 * 1000) return; // stale handoff
      // Tell the page which file was actually consumed, so the front
      // desk handoff banner (drop-strip.js) can name it.
      try {
        window.__bbHandoffFileName = rec.name || (rec.file && rec.file.name) || '';
        window.dispatchEvent(new CustomEvent('bb-handoff-file', { detail: { name: window.__bbHandoffFileName } }));
      } catch (err) {}
      var tries = 0;
      (function wait() {
        if (window.__bbCaptureHandoff) {
          try { window.__bbCaptureHandoff(rec.file); } catch (err) {}
          scheduleAutorun();
        } else if (++tries < 100) {
          setTimeout(wait, 100);
        }
      })();
    };
    get.onerror = function () {};
  };
  openReq.onerror = function () {};
})();
