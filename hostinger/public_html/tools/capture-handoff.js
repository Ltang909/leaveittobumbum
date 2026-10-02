/* Bum Bum capture handoff: receive a file the user attached in the dashboard
   capture box ("Log anything"). The file travels via IndexedDB on this same
   origin and is NEVER uploaded to the server, matching how these tools
   already work (browser-side processing). Inert unless ?from=capture is in
   the URL. The page exposes window.__bbCaptureHandoff(file) to accept it. */
(function () {
  var q;
  try { q = new URLSearchParams(location.search); } catch (e) { return; }
  if (!q || q.get('from') !== 'capture') return;
  if (!('indexedDB' in window)) return;
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
      var tries = 0;
      (function wait() {
        if (window.__bbCaptureHandoff) {
          try { window.__bbCaptureHandoff(rec.file); } catch (err) {}
        } else if (++tries < 100) {
          setTimeout(wait, 100);
        }
      })();
    };
    get.onerror = function () {};
  };
  openReq.onerror = function () {};
})();
