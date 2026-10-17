/* Live transcription helper, shared by Notes, Voice to Content, SOP
 * Generator, the account capture box, and Audiogram Maker.
 *
 * Shows words on screen while the user is still talking, on every
 * browser:
 *
 *   Strategy A (SpeechRecognition): Chrome and Android browsers have
 *   live speech recognition built in. We run it continuous with
 *   interim results and report final and interim text as it grows.
 *
 *   Strategy B (relay segments): iPhone Safari and Firefox have no
 *   SpeechRecognition. A second MediaRecorder listens to the same mic
 *   stream and restarts every ~5 seconds, so each segment is a
 *   complete standalone audio file. Every segment is POSTed to
 *   /api/voice-transcribe.php with live=1 and the returned transcript
 *   is appended to the running text. Segments are sent in order;
 *   a failed segment is skipped silently and the next one carries on.
 *
 * Audio is never stored anywhere: segments live in memory only long
 * enough to be transcribed, nothing is written to disk or storage.
 *
 * Usage:
 *   BBLiveTranscribe.start({
 *     stream: micStream,          // MediaStream (needed for relay mode)
 *     lang: 'en-US',              // BCP 47 language tag
 *     mode: 'auto',               // 'auto' (default), 'sr', or 'relay'
 *     onMode: function (mode) {}, // optional; 'sr' or 'relay' once known
 *     onText: function (finalText, interimText) {}
 *   });
 *   BBLiveTranscribe.stop();
 *
 * onText always receives the full accumulated final text plus the
 * current interim text, never a delta. After stop() no further
 * onText calls fire, so a page's own after-stop transcription can
 * safely replace the provisional live text.
 */
(function () {
  'use strict';

  var SR = window.SpeechRecognition || window.webkitSpeechRecognition;
  var SEGMENT_MS = 5000;

  var session = null;

  function injectCss() {
    if (document.getElementById('bb-live-css')) return;
    var st = document.createElement('style');
    st.id = 'bb-live-css';
    st.textContent = '.bb-dot{display:inline-block;width:9px;height:9px;border-radius:50%;background:#ff7448;margin-right:7px;animation:bbPulse 1.1s ease-in-out infinite}'
      + '@keyframes bbPulse{0%,100%{opacity:.2;transform:scale(.75)}50%{opacity:1;transform:scale(1.12)}}';
    (document.head || document.documentElement).appendChild(st);
  }

  function pickAudioMime() {
    var c = ['audio/webm;codecs=opus', 'audio/webm', 'audio/mp4', 'audio/ogg;codecs=opus'];
    for (var i = 0; i < c.length; i++) {
      if (window.MediaRecorder && MediaRecorder.isTypeSupported(c[i])) return c[i];
    }
    return '';
  }

  function extFor(mimeType) {
    var m = (mimeType || '').split(';')[0];
    if (m.indexOf('mp4') >= 0) return 'm4a';
    if (m.indexOf('ogg') >= 0) return 'ogg';
    if (m.indexOf('wav') >= 0) return 'wav';
    return 'webm';
  }

  function joinText(finalText, extra) {
    var t = (extra || '').trim();
    if (!t) return finalText;
    return finalText ? finalText + ' ' + t : t;
  }

  /* --- Strategy A: browser SpeechRecognition --- */

  function startSR(s) {
    var r = new SR();
    r.lang = s.lang;
    r.continuous = true;
    r.interimResults = true;
    r.onresult = function (e) {
      if (session !== s) return;
      var interim = '';
      for (var i = e.resultIndex; i < e.results.length; i++) {
        var res = e.results[i];
        var txt = res[0].transcript;
        if (res.isFinal) s.finalText = joinText(s.finalText, txt);
        else interim += txt;
      }
      s.interimText = interim;
      s.onText(s.finalText, interim);
    };
    r.onerror = function (e) {
      if (session !== s) return;
      var err = e && e.error;
      /* SR exists but is blocked: fall back to relay segments so the
       * user still sees live words. */
      if ((err === 'not-allowed' || err === 'service-not-allowed') && s.stream && window.MediaRecorder) {
        s.mode = 'relay';
        try { r.onend = null; r.onresult = null; r.onerror = null; r.stop(); } catch (_) {}
        if (s.recog === r) s.recog = null;
        if (s.onMode) s.onMode('relay');
        startSegments(s);
      }
    };
    r.onend = function () {
      if (session === s && s.mode === 'sr') {
        try { r.start(); } catch (_) {}
      }
    };
    s.recog = r;
    try { r.start(); } catch (_) {}
  }

  /* --- Strategy B: relay segments via /api/voice-transcribe.php --- */

  function getCsrf(s) {
    if (!s.csrfPromise) {
      s.csrfPromise = fetch('/api/session.php')
        .then(function (r) { return r.json(); })
        .then(function (d) { return d.csrf || ''; })
        .catch(function () { return ''; });
    }
    return s.csrfPromise;
  }

  function postSegment(s, blob, mimeType) {
    return getCsrf(s).then(function (csrf) {
      var fd = new FormData();
      fd.append('audio', blob, 'live.' + extFor(mimeType));
      fd.append('language', s.lang);
      fd.append('csrf', csrf || '');
      fd.append('live', '1');
      return fetch('/api/voice-transcribe.php', { method: 'POST', body: fd });
    }).then(function (r) {
      return r.json().catch(function () { return {}; }).then(function (d) {
        return { ok: r.ok, data: d };
      });
    }).then(function (res) {
      if (!res.ok) return; /* busy or failed: skip this segment silently */
      var t = ((res.data && res.data.transcript) || '').trim();
      if (!t) return;
      if (session !== s) return; /* stopped while this was in flight */
      s.finalText = joinText(s.finalText, t);
      s.onText(s.finalText, s.interimText);
    }).catch(function () { /* skip silently, next segment carries on */ });
  }

  function queueSegment(s, blob, mimeType) {
    s.queue = s.queue.then(function () { return postSegment(s, blob, mimeType); });
  }

  function startSegments(s) {
    if (session !== s) return;
    var mime = pickAudioMime();
    var rec;
    try {
      rec = new MediaRecorder(s.stream, mime ? { mimeType: mime } : undefined);
    } catch (e) { return; }
    var parts = [];
    rec.ondataavailable = function (e) {
      if (e.data && e.data.size) parts.push(e.data);
    };
    rec.onstop = function () {
      if (parts.length) {
        queueSegment(s, new Blob(parts, { type: rec.mimeType || mime || 'audio/webm' }), rec.mimeType || mime || '');
      }
      if (session === s && s.mode === 'relay') startSegments(s);
    };
    s.segRecorder = rec;
    try { rec.start(); } catch (e) { return; }
    s.segTimer = setTimeout(function () {
      try { rec.stop(); } catch (e) {}
    }, SEGMENT_MS);
  }

  /* --- Public API --- */

  function start(opts) {
    stop();
    opts = opts || {};
    injectCss();
    var s = {
      lang: opts.lang || 'en',
      onText: typeof opts.onText === 'function' ? opts.onText : function () {},
      onMode: typeof opts.onMode === 'function' ? opts.onMode : null,
      stream: opts.stream || null,
      finalText: '',
      interimText: '',
      mode: '',
      recog: null,
      segRecorder: null,
      segTimer: 0,
      queue: Promise.resolve(),
      csrfPromise: null
    };
    session = s;

    var want = opts.mode || 'auto';
    var canSR = !!SR;
    var canRelay = !!(s.stream && window.MediaRecorder);

    if (want === 'relay') {
      if (canRelay) { s.mode = 'relay'; startSegments(s); }
      else if (canSR) { s.mode = 'sr'; startSR(s); }
    } else if (want === 'sr') {
      if (canSR) { s.mode = 'sr'; startSR(s); }
      else if (canRelay) { s.mode = 'relay'; startSegments(s); }
    } else {
      if (canSR) { s.mode = 'sr'; startSR(s); }
      else if (canRelay) { s.mode = 'relay'; startSegments(s); }
    }

    if (s.mode && s.onMode) s.onMode(s.mode);
    return s.mode;
  }

  function stop() {
    var s = session;
    if (!s) return;
    session = null;
    if (s.segTimer) { clearTimeout(s.segTimer); s.segTimer = 0; }
    if (s.recog) {
      try { s.recog.onend = null; s.recog.onresult = null; s.recog.onerror = null; s.recog.stop(); } catch (e) {}
      s.recog = null;
    }
    if (s.segRecorder) {
      try { s.segRecorder.onstop = null; s.segRecorder.stop(); } catch (e) {}
      s.segRecorder = null;
    }
  }

  window.BBLiveTranscribe = { start: start, stop: stop };
})();
