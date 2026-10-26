"use client";

import { useEffect, useRef, useState } from "react";
import { bbTrack, initTracking } from "../lib/track";
import { tools } from "../lib/tools";

// The front desk: the homepage "Log anything" experience. Same brain as
// the account dashboard's capture box: text goes to /api/capture.php,
// which routes it into Notes, Receipt Reader, Job Application Tracker,
// Invoice Reminder, or Subscription Tracker and spends exactly one
// action, the tool's. Files never touch the server: Bum Bum routes them
// by kind (audio to Voice Notes, video to the Trimmer, photos get a
// one-tap "what is it?" pick), stashes them in IndexedDB, and hands them
// to the tool page, which picks them up via /tools/capture-handoff.js.
// Guests are welcome; the account prompt does its own asking later, per
// the delayed-prompt pattern. On top of that brain, the desk layer:
// Bum Bum sits perched on the composer, rotates example jobs through the
// placeholder, swaps hats when he routes something, rings a bell, reads
// along while you type, peeks once if you leave him hanging, and carries
// your file across the screen on its way to the tool.

type CaptureResponse = {
  ok?: boolean;
  logged?: boolean;
  handoff?: boolean;
  message?: string;
  summary?: string;
  tool?: string;
  tool_name?: string;
  url?: string;
  via?: string;
  suggest_name?: string;
  suggest_url?: string;
  ambiguous?: boolean;
  guess?: string;
  fields?: Record<string, unknown>;
  options?: { tool: string; tool_name: string }[];
  text?: string;
  limit_reached?: boolean;
  error?: string;
};

type Result =
  | { kind: "idle" }
  | { kind: "working"; text: string }
  | { kind: "message"; text: string; suggestName?: string; suggestUrl?: string }
  | { kind: "success"; summary: string; url: string; toolName: string }
  | { kind: "error"; text: string; limit?: boolean };

const IMAGE_DESTINATIONS = [
  { name: "Receipt Reader", url: "/tools/receipt-reader/", label: "A receipt" },
  { name: "Poster to Calendar", url: "/tools/poster-to-calendar/", label: "An event poster" },
  { name: "Meal Scanner", url: "/tools/meal-scanner/", label: "A meal" },
];

const FACE_DEFAULT = "/bum/cat-wink-blep.png";
const FACE_WORK = "/bum/cat-laptop.png";
const FACE_EXCITED = "/bum/cat-excited-v2.png";
const FACE_PEEK = "/bum/cat-peeking.png";
const FACE_BOX = "/bum/cat-box-v2.png";

const PLACEHOLDERS = [
  "Drop a receipt, get a spreadsheet…",
  "Paste a messy email, get the reply drafted…",
  "Photo of a poster? Get the calendar events.",
  "Netflix $15.99 a month, file it…",
  "Say it out loud, get it in writing…",
  "Applied to Stripe today, log it…",
];

// One line per hat, in his voice. Keys are the router's tool keys plus
// the file destinations.
const HAT_LINES: Record<string, string> = {
  "receipt-reader": "Receipt hat on. Taking this to the reader.",
  notes: "Notebook hat on. Filing that away.",
  "invoice-chaser": "Chaser hat on. He is going on the list.",
  cutline: "Ledger hat on. That subscription is tracked now.",
  "corporate-bum-bum": "Briefcase hat on. Application logged.",
  "video-trimmer": "Director hat on. Taking this to the trimmer.",
  "poster-to-calendar": "Poster hat on. Events, coming right up.",
  "meal-scanner": "Chef hat on. Taking this to the scanner.",
};

function idem(): string {
  const a = new Uint8Array(24);
  window.crypto.getRandomValues(a);
  return Array.from(a, (b) => ("0" + b.toString(16)).slice(-2)).join("");
}

function fileKind(file: File): "audio" | "video" | "image" | "other" {
  if (file.type.startsWith("audio/")) return "audio";
  if (file.type.startsWith("video/")) return "video";
  if (file.type.startsWith("image/")) return "image";
  return "other";
}

function storeHandoff(file: File): Promise<void> {
  return new Promise((resolve, reject) => {
    if (!("indexedDB" in window)) {
      reject(new Error("no-idb"));
      return;
    }
    const req = window.indexedDB.open("bumbum-capture", 1);
    req.onupgradeneeded = () => {
      const db = req.result;
      if (!db.objectStoreNames.contains("handoff")) db.createObjectStore("handoff");
    };
    req.onsuccess = () => {
      const db = req.result;
      let tx: IDBTransaction;
      try {
        tx = db.transaction("handoff", "readwrite");
      } catch (err) {
        reject(err instanceof Error ? err : new Error("tx"));
        return;
      }
      tx.objectStore("handoff").put({ file, name: file.name, type: file.type, ts: Date.now() }, "pending");
      tx.oncomplete = () => resolve();
      tx.onerror = () => reject(tx.error || new Error("tx"));
    };
    req.onerror = () => reject(req.error || new Error("open"));
  });
}

function reducedMotion(): boolean {
  return typeof window !== "undefined" && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
}

function ssGet(key: string): string | null {
  try {
    return window.sessionStorage.getItem(key);
  } catch {
    return null;
  }
}

function ssSet(key: string, value: string): void {
  try {
    window.sessionStorage.setItem(key, value);
  } catch {
    // Private mode and friends: the desk just skips its once-per-session tricks.
  }
}

export default function CaptureBar() {
  const [text, setText] = useState("");
  const [file, setFile] = useState<File | null>(null);
  const [pendingImage, setPendingImage] = useState<File | null>(null);
  const [busy, setBusy] = useState(false);
  const [dragOver, setDragOver] = useState(false);
  const [result, setResult] = useState<Result>({ kind: "idle" });
  const [ambiguous, setAmbiguous] = useState<CaptureResponse | null>(null);
  const [moodFace, setMoodFace] = useState<string | null>(null);
  const [hatFace, setHatFace] = useState<string | null>(null);
  const [hatLine, setHatLine] = useState<string | null>(null);
  const [popping, setPopping] = useState(false);
  const [recording, setRecording] = useState(false);
  const [peek, setPeek] = useState(false);
  const [crossing, setCrossing] = useState<{ img: string; name: string } | null>(null);
  const [phIndex, setPhIndex] = useState(0);
  const fileInputRef = useRef<HTMLInputElement>(null);
  const textRef = useRef<HTMLTextAreaElement>(null);
  const focusedRef = useRef(false);
  const moodTimer = useRef<number | undefined>(undefined);
  const recorderRef = useRef<MediaRecorder | null>(null);
  const chunksRef = useRef<Blob[]>([]);
  const idemRef = useRef<string>("");

  // Rotating example jobs in the placeholder. Pauses while the desk has
  // your attention (focused) or the tab is hidden.
  useEffect(() => {
    const t = window.setInterval(() => {
      if (focusedRef.current || document.hidden) return;
      setPhIndex((i) => (i + 1) % PLACEHOLDERS.length);
    }, 3400);
    return () => window.clearInterval(t);
  }, []);

  // The composer grows with what you give it, like a chat box should.
  useEffect(() => {
    const el = textRef.current;
    if (!el) return;
    el.style.height = "auto";
    el.style.height = Math.min(el.scrollHeight, 180) + "px";
  }, [text]);

  // One peek per session if the page sits untouched for a while. Any
  // interaction re-arms the wait; once he has peeked, that is it.
  useEffect(() => {
    if (ssGet("bb-desk-peeked")) return;
    let timer: number | undefined;
    let hideTimer: number | undefined;
    let done = false;
    const events = ["pointerdown", "keydown", "touchstart", "wheel"] as const;
    function cleanup() {
      events.forEach((ev) => window.removeEventListener(ev, onInteract));
      window.clearTimeout(timer);
    }
    function onInteract() {
      if (!done) arm();
    }
    function arm() {
      window.clearTimeout(timer);
      timer = window.setTimeout(() => {
        if (done) return;
        done = true;
        ssSet("bb-desk-peeked", "1");
        setPeek(true);
        hideTimer = window.setTimeout(() => setPeek(false), 3400);
        cleanup();
      }, 25000);
    }
    events.forEach((ev) => window.addEventListener(ev, onInteract, { passive: true }));
    arm();
    return () => {
      cleanup();
      window.clearTimeout(hideTimer);
    };
  }, []);

  function flashFace(src: string, ms: number) {
    setMoodFace(src);
    window.clearTimeout(moodTimer.current);
    moodTimer.current = window.setTimeout(() => setMoodFace(null), ms);
  }

  function wearHat(toolKey: string | undefined, toolName: string) {
    const tool = tools.find((t) => t.key === toolKey);
    if (tool && tool.iconImg) setHatFace(tool.iconImg);
    setHatLine(HAT_LINES[toolKey || ""] || toolName + " hat on. On it.");
  }

  function clearHat() {
    setHatFace(null);
    setHatLine(null);
  }

  function ringBell() {
    try {
      const Ctor =
        window.AudioContext ||
        (window as unknown as { webkitAudioContext?: typeof AudioContext }).webkitAudioContext;
      if (Ctor) {
        const ctx = new Ctor();
        const t0 = ctx.currentTime;
        [
          { freq: 1567.98, vol: 0.22 },
          { freq: 2093.0, vol: 0.1 },
        ].forEach(({ freq, vol }) => {
          const osc = ctx.createOscillator();
          const gain = ctx.createGain();
          osc.type = "sine";
          osc.frequency.value = freq;
          gain.gain.setValueAtTime(vol, t0);
          gain.gain.exponentialRampToValueAtTime(0.0001, t0 + 1.1);
          osc.connect(gain);
          gain.connect(ctx.destination);
          osc.start(t0);
          osc.stop(t0 + 1.15);
        });
        window.setTimeout(() => {
          void ctx.close();
        }, 1400);
      }
    } catch {
      // No audio, no problem. The face still does the bit.
    }
    flashFace(FACE_EXCITED, 1100);
    if (!reducedMotion()) {
      setPopping(true);
      window.setTimeout(() => setPopping(false), 520);
    }
  }

  async function getSession() {
    const session = await fetch("/api/session.php", { credentials: "same-origin" }).then((r) => r.json());
    initTracking(session);
    return session as { csrf: string };
  }

  async function transcribe(blob: Blob) {
    setResult({ kind: "working", text: "Transcribing…" });
    try {
      const session = await getSession();
      const fd = new FormData();
      fd.append("audio", blob, "capture.webm");
      fd.append("csrf", session.csrf);
      const res = await fetch("/api/voice-transcribe.php", {
        method: "POST",
        credentials: "same-origin",
        body: fd,
      });
      const d = (await res.json()) as { transcript?: string; error?: string };
      if (d && d.transcript) {
        setText(d.transcript);
        setResult({ kind: "message", text: "Heard you. Tap Log it and I will file it." });
        if (textRef.current) textRef.current.focus();
      } else {
        setResult({ kind: "error", text: (d && d.error) || "Could not transcribe that." });
      }
    } catch {
      setResult({ kind: "error", text: "Transcription failed. Try typing it." });
    }
  }

  function toggleMic() {
    if (recording && recorderRef.current) {
      recorderRef.current.stop();
      return;
    }
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia || typeof MediaRecorder === "undefined") {
      setResult({ kind: "error", text: "Voice input is not supported in this browser. Type it instead." });
      return;
    }
    navigator.mediaDevices
      .getUserMedia({ audio: true })
      .then((stream) => {
        chunksRef.current = [];
        const rec = new MediaRecorder(stream);
        recorderRef.current = rec;
        rec.ondataavailable = (e) => {
          if (e.data && e.data.size) chunksRef.current.push(e.data);
        };
        rec.onstop = () => {
          stream.getTracks().forEach((t) => t.stop());
          setRecording(false);
          void transcribe(new Blob(chunksRef.current, { type: rec.mimeType || "audio/webm" }));
        };
        rec.start();
        setRecording(true);
        setResult({ kind: "working", text: "Listening… tap the mic again to stop." });
      })
      .catch(() => {
        setResult({ kind: "error", text: "Microphone access was blocked. Type it instead." });
      });
  }

  function openFilePicker(accept: string) {
    const input = fileInputRef.current;
    if (!input) return;
    input.accept = accept;
    input.click();
  }

  function prefillInvoice() {
    bbTrack("desk_chip", { chip: "invoice" });
    clearHat();
    setText("Invoice reminder: ");
    const el = textRef.current;
    if (el) {
      el.focus();
      window.setTimeout(() => {
        el.setSelectionRange(el.value.length, el.value.length);
      }, 0);
    }
  }

  function stashAndGo(f: File, url: string, name: string, via: string) {
    bbTrack("capture_routed", { destination: name, surface: "home", via });
    const tool = tools.find((t) => t.url === url);
    wearHat(tool ? tool.key : undefined, name);
    setResult({ kind: "working", text: "Sending \"" + f.name + "\" to " + name + ". Your file never leaves your device." });
    storeHandoff(f)
      .then(() => {
        // Once per session, he personally carries the file across the
        // screen on its way to the tool; the handoff waits for him.
        if (!reducedMotion() && !ssGet("bb-desk-crossed")) {
          ssSet("bb-desk-crossed", "1");
          setCrossing({ img: tool && tool.iconImg ? tool.iconImg : "", name });
          window.setTimeout(() => {
            window.location.href = url + "?from=capture";
          }, 4500);
        } else {
          window.setTimeout(() => {
            window.location.href = url + "?from=capture";
          }, 700);
        }
      })
      .catch(() => {
        setResult({
          kind: "message",
          text: "Bum Bum could not stage that file in this browser.",
          suggestName: name,
          suggestUrl: url,
        });
      });
  }

  function routeFile(f: File) {
    const kind = fileKind(f);
    bbTrack("capture_started", { surface: "home", kind: "file", file_kind: kind });
    setAmbiguous(null);
    clearHat();
    if (kind === "audio") {
      stashAndGo(f, "/tools/notes/", "Voice Notes to Text", "file");
    } else if (kind === "video") {
      stashAndGo(f, "/tools/video-trimmer/", "Video Trimmer", "file");
    } else if (kind === "image") {
      setPendingImage(f);
      setResult({ kind: "idle" });
    } else {
      setResult({
        kind: "message",
        text: "That file type has Bum Bum stumped. He takes photos, videos, and audio here. Anything else, the toolbox probably has a tool for it.",
        suggestName: "the toolbox",
        suggestUrl: "/tools/",
      });
    }
  }

  function pickImageDestination(url: string, name: string) {
    if (!pendingImage) return;
    const f = pendingImage;
    setPendingImage(null);
    setFile(null);
    stashAndGo(f, url, name, "file-picker");
  }

  async function sendText(tool?: string, fields?: Record<string, unknown>, key?: string) {
    const value = text.trim();
    if (!value || busy) return;
    setBusy(true);
    setAmbiguous(null);
    setResult({ kind: "working", text: "Filing it…" });
    try {
      const session = await getSession();
      const res = await fetch("/api/capture.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        credentials: "same-origin",
        body: JSON.stringify({
          text: value,
          tool: tool || "",
          fields: fields || {},
          csrf: session.csrf,
          idempotencyKey: key || idemRef.current || idem(),
        }),
      });
      const d = (await res.json()) as CaptureResponse;
      setBusy(false);
      if (d.ok && d.logged === false) {
        if (d.suggest_url) {
          bbTrack("capture_routed", { destination: d.suggest_name || "", surface: "home", via: "suggest" });
        }
        setResult({ kind: "message", text: d.message || "Can't do that from the log box.", suggestName: d.suggest_name, suggestUrl: d.suggest_url });
      } else if (d.ok) {
        bbTrack("capture_routed", { destination: d.tool || "", surface: "home", via: d.via || "" });
        wearHat(d.tool, d.tool_name || "That");
        setText("");
        setResult({ kind: "success", summary: d.summary || "Logged it.", url: d.url || "/tools/", toolName: d.tool_name || "the tool" });
      } else if (d.ambiguous) {
        idemRef.current = key || idemRef.current;
        setAmbiguous(d);
        setResult({ kind: "idle" });
      } else if (d.limit_reached) {
        setResult({ kind: "error", text: d.error || "Out of actions for this period.", limit: true });
      } else {
        setResult({ kind: "error", text: d.error || "Something went wrong." });
      }
    } catch {
      setBusy(false);
      setResult({ kind: "error", text: "Could not reach Bum Bum. Check your connection and try again." });
    }
  }

  function submit() {
    if (file) {
      routeFile(file);
      return;
    }
    if (!text.trim() || busy) return;
    clearHat();
    idemRef.current = idem();
    bbTrack("capture_started", { surface: "home", kind: "text" });
    void sendText(undefined, undefined, idemRef.current);
  }

  const faceSrc = moodFace || hatFace || FACE_DEFAULT;

  return (
    <section
      className={"capture-home front-desk shell" + (dragOver ? " drag-over" : "")}
      aria-label="Log anything at the front desk"
      onDragOver={(e) => {
        e.preventDefault();
        setDragOver(true);
      }}
      onDragLeave={() => setDragOver(false)}
      onDrop={(e) => {
        e.preventDefault();
        setDragOver(false);
        const f = e.dataTransfer.files && e.dataTransfer.files[0];
        if (f) {
          setFile(f);
          routeFile(f);
        }
      }}
      onPaste={(e) => {
        const f = e.clipboardData && e.clipboardData.files && e.clipboardData.files[0];
        if (f) {
          e.preventDefault();
          setFile(f);
          routeFile(f);
          return;
        }
        const pasted = e.clipboardData ? e.clipboardData.getData("text") : "";
        if (pasted && pasted.length > 300) flashFace(FACE_EXCITED, 1900);
      }}
    >
      <div className="capture-home-card desk-card">
        <div className="desk-head">
          <div>
            <p className="kicker">The front desk</p>
            <h2 className="desk-greeting">What are we handling today?</h2>
            <p className="capture-home-invite">
              Drop it here, I will handle it. A receipt, a subscription you keep meaning to track, an invoice
              somebody owes you, a half-formed idea. Type it, say it, paste it, or drop a file on me, and I will
              put it where it belongs and tell you what I did. One action, same as using the tool itself.
            </p>
          </div>
          <button type="button" className="desk-bell" onClick={ringBell} aria-label="Ring the front desk bell" title="Ring for service">
            🔔
          </button>
        </div>
        <div className="desk-composer-wrap">
          <div className={"desk-cat" + (popping ? " pop" : "")} aria-hidden="true">
            <img src={faceSrc} alt="" width="112" height="112" />
          </div>
          <div className="desk-composer">
            <textarea
              ref={textRef}
              rows={1}
              value={text}
              onChange={(e) => {
                setText(e.target.value);
                if (e.target.value.trim()) flashFace(FACE_WORK, 1500);
              }}
              onFocus={() => {
                focusedRef.current = true;
              }}
              onBlur={() => {
                focusedRef.current = false;
              }}
              onKeyDown={(e) => {
                if (e.key === "Enter" && !e.shiftKey) {
                  e.preventDefault();
                  submit();
                }
              }}
              placeholder={PLACEHOLDERS[phIndex]}
              aria-label="Type anything to log it"
              autoComplete="off"
              maxLength={2000}
            />
            <div className="desk-composer-bar">
              <button
                type="button"
                className="desk-icon-btn"
                onClick={() => openFilePicker("image/*,video/*,audio/*")}
                aria-label="Attach a photo, video, or audio file"
                title="Attach a file"
              >
                +
              </button>
              <button
                type="button"
                className={"desk-icon-btn" + (recording ? " recording" : "")}
                onClick={toggleMic}
                aria-label={recording ? "Stop recording" : "Dictate instead"}
                title={recording ? "Stop recording" : "Dictate instead"}
              >
                🎤
              </button>
              <span className="desk-composer-spacer" />
              <button type="button" className="button" onClick={submit} disabled={busy}>
                Log it
              </button>
            </div>
          </div>
        </div>
        <div className="desk-chips-row">
          <div className="capture-home-chips desk-task-chips">
            <button
              type="button"
              className="capture-home-chip"
              onClick={() => {
                bbTrack("desk_chip", { chip: "receipts" });
                openFilePicker("image/*");
              }}
            >
              Read my receipts
            </button>
            <button type="button" className="capture-home-chip" onClick={prefillInvoice}>
              Chase an invoice
            </button>
            <button
              type="button"
              className="capture-home-chip"
              onClick={() => {
                bbTrack("desk_chip", { chip: "poster" });
                openFilePicker("image/*");
              }}
            >
              Turn a poster into events
            </button>
            <button
              type="button"
              className="capture-home-chip"
              onClick={() => {
                bbTrack("desk_chip", { chip: "voice-note" });
                openFilePicker("audio/*");
              }}
            >
              Transcribe a voice note
            </button>
          </div>
          <a className="desk-browse" href="#toolbox">
            or browse the toolbox ↓
          </a>
        </div>
        <input
          ref={fileInputRef}
          type="file"
          accept="image/*,video/*,audio/*"
          style={{ display: "none" }}
          onChange={(e) => {
            const f = e.target.files && e.target.files[0];
            e.target.value = "";
            if (f) {
              setFile(f);
              routeFile(f);
            }
          }}
        />
        {file && !pendingImage && (
          <p className="capture-home-filechip">
            📎 {file.name} ({(file.size / 1048576).toFixed(1)} MB){" "}
            <button
              type="button"
              onClick={() => {
                setFile(null);
                setPendingImage(null);
              }}
              aria-label="Remove file"
            >
              ×
            </button>
          </p>
        )}
        <div aria-live="polite">
          {hatLine && <p className="desk-hat-line">{hatLine}</p>}
          {result.kind === "working" && <p className="capture-home-note">{result.text}</p>}
          {result.kind === "message" && (
            <p className="capture-home-note">
              {result.text}{" "}
              {result.suggestUrl && (
                <a href={result.suggestUrl}>Open {result.suggestName || "the tool"} →</a>
              )}
            </p>
          )}
          {result.kind === "success" && (
            <p className="capture-home-success">
              <b>✓ {result.summary}</b> <a href={result.url}>View in {result.toolName} →</a>
            </p>
          )}
          {result.kind === "error" && (
            <p className="capture-home-error">
              {result.text} {result.limit && <a href="/account/">See your account</a>}
            </p>
          )}
          {pendingImage && (
            <div className="capture-home-picker">
              <p className="capture-home-note">Got it. What is this a photo of?</p>
              <div className="capture-home-chips">
                {IMAGE_DESTINATIONS.map((d) => (
                  <button key={d.url} type="button" className="capture-home-chip" onClick={() => pickImageDestination(d.url, d.name)}>
                    {d.label}
                  </button>
                ))}
              </div>
            </div>
          )}
          {ambiguous && ambiguous.options && (
            <div className="capture-home-picker">
              <p className="capture-home-note">Hmm, where should this go?</p>
              <div className="capture-home-chips">
                {ambiguous.options.map((o) => (
                  <button
                    key={o.tool}
                    type="button"
                    className="capture-home-chip"
                    onClick={() => void sendText(o.tool, ambiguous.fields, idemRef.current)}
                  >
                    {o.tool_name}
                  </button>
                ))}
              </div>
            </div>
          )}
        </div>
      </div>
      {peek && <img className="desk-peek" src={FACE_PEEK} alt="" aria-hidden="true" />}
      {crossing && (
        <div className="desk-crossing" aria-hidden="true">
          <img className="desk-crossing-cat" src={FACE_BOX} alt="" />
          {crossing.img && <img className="desk-crossing-item" src={crossing.img} alt="" />}
          <span className="desk-crossing-label">Taking that to {crossing.name}…</span>
        </div>
      )}
    </section>
  );
}
