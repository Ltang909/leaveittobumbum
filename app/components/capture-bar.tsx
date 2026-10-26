"use client";

import { useEffect, useRef, useState } from "react";
import { bbTrack, initTracking } from "../lib/track";
import { tools, Tool } from "../lib/tools";
import { useDeskFace } from "../lib/desk-face";

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
// example jobs rotate through the placeholder, typing @name calls a
// tool directly (fuzzy-matched dropdown, the pick pins the destination),
// he swaps hats when he routes something, rings a bell, reads along
// while you type (his hero portrait does the faces now), peeks once if
// you leave him hanging, and carries your file across the screen on its
// way to the tool.

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

// While a tool is pinned with @, the rotating examples step aside for a
// sample of what that tool actually wants. Log-style tools get an
// "e.g. ..." in their real input shape; file-first tools get the drop
// instruction instead, since the desk is their drop zone too.
const PIN_PLACEHOLDERS: Record<string, string> = {
  clips: "Hit Log it and the recorder opens, right in your browser…",
  notes: "e.g. Idea: a loyalty card, but for dog groomers…",
  cutline: "e.g. Netflix, $15.99, monthly",
  purrsuit: "e.g. Maria at Bloom Co, last spoke Tuesday, nudge her about the quote…",
  "corporate-bum-bum": "e.g. Applied to Stripe for the Growth Lead role today",
  doodle: "Hit Log it and the sketchpad opens. Draw it there…",
  "invoice-chaser": "e.g. hud.io, $20.00, due in 2 weeks",
  "receipt-reader": "e.g. Starbucks, $6.40",
  "poster-to-calendar": "Drop the poster photo here…",
  "meal-scanner": "Drop the meal photo here…",
  "gap-scout": "Hit Log it to open it, then drop your resume and the job post there…",
  "video-trimmer": "Drop the video here to trim it…",
  "image-converter": "Drop the image here to convert it…",
  "image-cropper": "Drop the image here to crop it…",
  ghostwriter: "e.g. Paste a ramble or transcript, get hooks, a script, and a caption…",
  audiogram: "Hit Log it to open it, then drop your MP3 there…",
  "purr-code": "e.g. https://yourshop.com, whatever the code should open…",
  "sop-ify": "e.g. First we check the inbox, then we chase anything older than a day…",
  hearsay: "e.g. Just your brand name. He checks what the AIs say about it…",
  "video-converter": "Drop the video here to convert it…",
};

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

// The tools /api/capture.php can file into natively. An @-pin on one of
// these stays in the capture flow; the pin picks the destination and the
// router just does the field extraction.
const NATIVE_TOOLS = ["notes", "receipt-reader", "corporate-bum-bum", "invoice-chaser", "cutline"];

// Tools whose pages receive a file straight from the desk: they define
// window.__bbCaptureHandoff and load /tools/capture-handoff.js, so the
// IndexedDB stash actually gets picked up on arrival. Values are the
// file kinds each page can swallow.
const FILE_HANDOFF_KINDS: Record<string, ("audio" | "video" | "image")[]> = {
  notes: ["audio"],
  "receipt-reader": ["image"],
  "poster-to-calendar": ["image"],
  "meal-scanner": ["image"],
  "image-converter": ["image"],
  "image-cropper": ["image"],
  "video-trimmer": ["video"],
  "video-converter": ["video"],
};

// Tiny subsequence fuzzy matcher for the @ menu. Whole-string for names
// and keys; per-word for tags and descriptions, so a three-letter query
// cannot "match" a whole paragraph by accident. Bonuses for consecutive
// runs and word starts, so "rec" loves Receipt and "inv" loves Invoice.
function fuzzyScore(query: string, target: string): number | null {
  const q = query.toLowerCase();
  const t = target.toLowerCase();
  let qi = 0;
  let score = 0;
  let run = 0;
  let last = -2;
  for (let ti = 0; ti < t.length && qi < q.length; ti++) {
    if (t[ti] === q[qi]) {
      score += 1;
      if (ti === last + 1) {
        run += 1;
        score += run;
      } else {
        run = 0;
      }
      if (ti === 0 || t[ti - 1] === " " || t[ti - 1] === "-") score += 3;
      last = ti;
      qi += 1;
    }
  }
  return qi === q.length ? score : null;
}

function wordScore(query: string, text: string, weight: number): number | null {
  let best: number | null = null;
  for (const w of text.split(/[^A-Za-z0-9]+/)) {
    if (!w) continue;
    const s = fuzzyScore(query, w);
    if (s !== null) best = Math.max(best === null ? -1 : best, s * weight);
  }
  return best;
}

function matchTools(query: string): Tool[] {
  const q = query.trim().toLowerCase();
  if (!q) return tools;
  const scored: { tool: Tool; score: number }[] = [];
  for (const tool of tools) {
    let best: number | null = null;
    const nameScore = fuzzyScore(q, tool.name);
    if (nameScore !== null) best = Math.max(best === null ? -1 : best, nameScore * 3 + (tool.name.toLowerCase().startsWith(q) ? 8 : 0));
    const keyScore = fuzzyScore(q, tool.key);
    if (keyScore !== null) best = Math.max(best === null ? -1 : best, keyScore * 2);
    const tagScore = wordScore(q, tool.tag, 1.2);
    if (tagScore !== null) best = Math.max(best === null ? -1 : best, tagScore);
    const descScore = wordScore(q, tool.description, 1);
    if (descScore !== null) best = Math.max(best === null ? -1 : best, descScore);
    if (best !== null) scored.push({ tool, score: best });
  }
  scored.sort((a, b) => b.score - a.score);
  return scored.map((s) => s.tool);
}

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
  const [hatLine, setHatLine] = useState<string | null>(null);
  const [pinned, setPinned] = useState<Tool | null>(null);
  const [mention, setMention] = useState<{ start: number; query: string } | null>(null);
  const [mentionIndex, setMentionIndex] = useState(0);
  const deskFace = useDeskFace();
  const [recording, setRecording] = useState(false);
  const [peek, setPeek] = useState(false);
  const [crossing, setCrossing] = useState<{ img: string; name: string } | null>(null);
  const [phIndex, setPhIndex] = useState(0);
  const fileInputRef = useRef<HTMLInputElement>(null);
  const textRef = useRef<HTMLTextAreaElement>(null);
  const focusedRef = useRef(false);
  const pinnedRef = useRef<Tool | null>(null);
  const sessionWarmRef = useRef(false);
  const recorderRef = useRef<MediaRecorder | null>(null);
  const chunksRef = useRef<Blob[]>([]);
  const idemRef = useRef<string>("");

  // Keep a ref mirror of the pin so the rotation timer can see it.
  useEffect(() => {
    pinnedRef.current = pinned;
  }, [pinned]);

  // Rotating example jobs in the placeholder. Pauses while the desk has
  // your attention (focused), the tab is hidden, or a tool is pinned
  // (the pinned sample placeholder owns the box then).
  useEffect(() => {
    const t = window.setInterval(() => {
      if (focusedRef.current || document.hidden || pinnedRef.current) return;
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
    deskFace.flashMood(src, ms);
  }

  function wearHat(toolKey: string | undefined, toolName: string) {
    const tool = tools.find((t) => t.key === toolKey);
    if (tool && tool.iconImg) deskFace.setHat(tool.iconImg);
    setHatLine(HAT_LINES[toolKey || ""] || toolName + " hat on. On it.");
  }

  function clearHat() {
    deskFace.setHat(null);
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
    if (!reducedMotion()) deskFace.pop();
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
        // screen on its way to the tool; the handoff waits for him. The
        // walk takes ~9 seconds now (half the old speed), and any click
        // or tap skips it and goes straight to the tool.
        if (!reducedMotion() && !ssGet("bb-desk-crossed")) {
          ssSet("bb-desk-crossed", "1");
          setCrossing({ img: tool && tool.iconImg ? tool.iconImg : "", name });
          let gone = false;
          const go = () => {
            if (gone) return;
            gone = true;
            window.removeEventListener("pointerdown", go);
            window.location.href = url + "?from=capture";
          };
          window.addEventListener("pointerdown", go);
          window.setTimeout(go, 9000);
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
    if (pinned && kind !== "other") {
      const kinds = FILE_HANDOFF_KINDS[pinned.key];
      if (kinds && kinds.includes(kind)) {
        // The pinned tool can swallow this file: skip the "what is it?"
        // pick and hand it straight over.
        stashAndGo(f, pinned.url, pinned.name, "mention");
        return;
      }
      if (kinds) {
        setResult({
          kind: "message",
          text: pinned.name + " cannot take a " + kind + " file. Unpin it above the box, or drop a different file.",
        });
        return;
      }
    }
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

  async function fetchCapture(value: string, tool?: string, fields?: Record<string, unknown>, key?: string): Promise<CaptureResponse> {
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
        idempotencyKey: key || idem(),
      }),
    });
    return (await res.json()) as CaptureResponse;
  }

  async function sendText(tool?: string, fields?: Record<string, unknown>, key?: string) {
    const value = text.trim();
    if (!value || busy) return;
    setBusy(true);
    setAmbiguous(null);
    setResult({ kind: "working", text: "Filing it…" });
    try {
      const d = await fetchCapture(value, tool, fields, key || idemRef.current || idem());
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

  // Open a pinned tool the capture box cannot file into itself. Any
  // words the user typed ride along on the clipboard, since tool pages
  // have no text prefill; the hat swap acknowledges the call either way.
  async function openPinnedTool(tool: Tool) {
    wearHat(tool.key, tool.name);
    bbTrack("capture_routed", { destination: tool.key, surface: "home", via: "mention" });
    const value = text.trim();
    let copied = false;
    if (value && typeof navigator !== "undefined" && navigator.clipboard && navigator.clipboard.writeText) {
      try {
        await navigator.clipboard.writeText(value);
        copied = true;
      } catch {
        copied = false;
      }
    }
    setResult({
      kind: "message",
      text: copied
        ? "Your words are copied. Paste them into " + tool.name + " when it opens. Taking you there…"
        : "Taking you to " + tool.name + "…",
    });
    window.setTimeout(() => {
      window.location.href = tool.url;
    }, copied ? 1500 : 900);
  }

  // A pinned native tool: the pin is ABSOLUTE. It rides along on the
  // first (and only) request, and the server files to the pinned tool,
  // extracting fields for that tool alone; the receipt fast path and the
  // router's own tool choice never get a say. If the server flatly
  // declines (a task, not a log entry), the @ call wins and the tool
  // simply opens.
  async function pinnedNativeSubmit(tool: Tool) {
    const value = text.trim();
    if (!value || busy) return;
    setBusy(true);
    setAmbiguous(null);
    setResult({ kind: "working", text: "Filing it…" });
    const key = idem();
    idemRef.current = key;
    bbTrack("capture_started", { surface: "home", kind: "text", via: "mention", tool: tool.key });
    try {
      let d = await fetchCapture(value, tool.key, undefined, key);
      if (d.ambiguous) {
        // Should not happen with a pin (the server resolves it there),
        // but answer with the guess in hand rather than stranding it.
        d = await fetchCapture(value, tool.key, d.fields, key);
      }
      setBusy(false);
      if (d.ok && d.logged === false) {
        await openPinnedTool(tool);
      } else if (d.ok) {
        bbTrack("capture_routed", { destination: d.tool || "", surface: "home", via: d.via || "mention" });
        wearHat(d.tool, d.tool_name || "That");
        setText("");
        setResult({ kind: "success", summary: d.summary || "Logged it.", url: d.url || "/tools/", toolName: d.tool_name || "the tool" });
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

  // Watch the caret for an @ that starts a mention: at the start of the
  // box or right after whitespace, with only name-ish characters between
  // it and the caret. Anything else (an email address, a stray @) is
  // just text.
  function syncMention(value: string, caret: number) {
    let start = -1;
    for (let i = caret - 1; i >= 0; i--) {
      const ch = value[i];
      if (ch === "@") {
        start = i;
        break;
      }
      if (/\s/.test(ch)) break;
    }
    if (start >= 0 && (start === 0 || /\s/.test(value[start - 1]))) {
      const query = value.slice(start + 1, caret);
      if (/^[A-Za-z0-9-]*$/.test(query)) {
        setMention({ start, query });
        setMentionIndex(0);
        if (!sessionWarmRef.current) {
          // Warm up tracking so the selection event is not dropped.
          sessionWarmRef.current = true;
          void getSession().catch(() => {});
        }
        return;
      }
    }
    setMention(null);
  }

  function selectMention(tool: Tool) {
    if (!mention) return;
    const el = textRef.current;
    const caret = el && el.selectionStart !== null ? el.selectionStart : text.length;
    const next = text.slice(0, mention.start) + text.slice(caret);
    const at = mention.start;
    setText(next);
    setMention(null);
    setPinned(tool);
    bbTrack("desk_mention_selected", { tool: tool.key });
    window.requestAnimationFrame(() => {
      const ta = textRef.current;
      if (ta) {
        ta.focus();
        ta.setSelectionRange(at, at);
      }
    });
  }

  function submit() {
    if (file) {
      routeFile(file);
      return;
    }
    if (pinned) {
      if (busy) return;
      if (!text.trim()) {
        void openPinnedTool(pinned);
        return;
      }
      clearHat();
      if (NATIVE_TOOLS.includes(pinned.key)) {
        void pinnedNativeSubmit(pinned);
      } else {
        void openPinnedTool(pinned);
      }
      return;
    }
    if (!text.trim() || busy) return;
    clearHat();
    idemRef.current = idem();
    bbTrack("capture_started", { surface: "home", kind: "text" });
    void sendText(undefined, undefined, idemRef.current);
  }

  const mentionMatches = mention ? matchTools(mention.query) : [];
  const activeMentionIndex = mentionMatches.length ? Math.min(mentionIndex, mentionMatches.length - 1) : 0;

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
            <p className="capture-home-invite">Drop it here, I will handle it. One action, same as using the tool itself.</p>
          </div>
          <button type="button" className="desk-bell" onClick={ringBell} aria-label="Ring the front desk bell" title="Ring for service">
            🔔
          </button>
        </div>
        <div className="desk-composer-wrap">
          <div className="desk-composer">
            {pinned && (
              <div className="desk-pin-row">
                <span className="desk-pin">
                  {pinned.iconImg ? <img src={pinned.iconImg} alt="" /> : <span aria-hidden="true">{pinned.icon}</span>}
                  <span>@{pinned.name}</span>
                  <button
                    type="button"
                    onClick={() => {
                      setPinned(null);
                      if (textRef.current) textRef.current.focus();
                    }}
                    aria-label={"Unpin " + pinned.name}
                    title="Unpin"
                  >
                    ×
                  </button>
                </span>
                <span className="desk-pin-note">Everything you log goes to {pinned.name}.</span>
              </div>
            )}
            <textarea
              ref={textRef}
              rows={1}
              value={text}
              onChange={(e) => {
                setText(e.target.value);
                syncMention(e.target.value, e.target.selectionStart ?? e.target.value.length);
                if (e.target.value.trim()) flashFace(FACE_WORK, 1500);
              }}
              onFocus={() => {
                focusedRef.current = true;
              }}
              onBlur={() => {
                focusedRef.current = false;
                window.setTimeout(() => setMention(null), 150);
              }}
              onSelect={(e) => {
                syncMention(e.currentTarget.value, e.currentTarget.selectionStart ?? e.currentTarget.value.length);
              }}
              onKeyDown={(e) => {
                if (mention && mentionMatches.length > 0) {
                  if (e.key === "ArrowDown") {
                    e.preventDefault();
                    setMentionIndex((i) => (i + 1) % mentionMatches.length);
                    return;
                  }
                  if (e.key === "ArrowUp") {
                    e.preventDefault();
                    setMentionIndex((i) => (i - 1 + mentionMatches.length) % mentionMatches.length);
                    return;
                  }
                  if (e.key === "Enter" || e.key === "Tab") {
                    e.preventDefault();
                    const tool = mentionMatches[activeMentionIndex];
                    if (tool) selectMention(tool);
                    return;
                  }
                  if (e.key === "Escape") {
                    e.preventDefault();
                    setMention(null);
                    return;
                  }
                }
                if (e.key === "Enter" && !e.shiftKey) {
                  e.preventDefault();
                  submit();
                }
              }}
              placeholder={pinned ? PIN_PLACEHOLDERS[pinned.key] || PLACEHOLDERS[phIndex] : PLACEHOLDERS[phIndex]}
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
          {mention && mentionMatches.length > 0 && (
            <div className="desk-mention-list" role="listbox" aria-label="Tools you can call with @">
              {mentionMatches.map((tool, i) => (
                <button
                  key={tool.key}
                  type="button"
                  role="option"
                  aria-selected={i === activeMentionIndex}
                  className={"desk-mention-item" + (i === activeMentionIndex ? " active" : "")}
                  onMouseDown={(e) => {
                    e.preventDefault();
                    selectMention(tool);
                  }}
                  onMouseEnter={() => setMentionIndex(i)}
                >
                  {tool.iconImg ? <img src={tool.iconImg} alt="" /> : <span className="desk-mention-emoji" aria-hidden="true">{tool.icon}</span>}
                  <span className="desk-mention-text">
                    <b>{tool.name}</b>
                    <small>{tool.description}</small>
                  </span>
                </button>
              ))}
            </div>
          )}
          {mention && mention.query.trim() !== "" && mentionMatches.length === 0 && (
            <div className="desk-mention-list desk-mention-empty" role="status">
              No tool matches “{mention.query}”. Bum Bum is stumped.
            </div>
          )}
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
