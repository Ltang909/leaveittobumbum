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
// by kind (audio to Voice Notes, video to the Trimmer; an unpinned
// photo stages in the composer until the visitor @-picks one of the
// photo tools for it), stashes them in IndexedDB, and hands them
// to the tool page, which picks them up via /tools/capture-handoff.js.
// Guests are welcome; the account prompt does its own asking later, per
// the delayed-prompt pattern. On top of that brain, the desk layer:
// example jobs rotate through the placeholder, typing @name calls a
// tool directly (fuzzy-matched dropdown, the pick pins the destination),
// he swaps hats when he routes something, reads along while you type
// (his hero portrait does the faces now), peeks once if you leave him
// hanging, and carries your file across the screen on its way to the
// tool. Before any hop to a tool page the desk leaves a sessionStorage
// marker, so the tool page can greet you with a "carried over from the
// front desk" banner (rendered by /tools/drop-strip.js).

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

const FACE_WORK = "/bum/cat-laptop.png";
const FACE_EXCITED = "/bum/cat-excited-v2.png";
const FACE_PEEK = "/bum/cat-peeking.png";
const FACE_BOX = "/bum/cat-box-v2.png";
const FACE_PETPET = "/bum/petpet/box.png";

const PLACEHOLDERS = [
  "Start with @ to pick a tool, or just describe the job…",
  "Drop a receipt, get a spreadsheet…",
  "Photo of an event poster? Add it to your calendar",
  "Netflix $15.99 a month, track it as part of your subscriptions",
  "transcribe your spoken thoughts",
  "Photo of your meal? Turn it into tracked calories and macros",
];

// While a tool is pinned with @, the rotating examples step aside for a
// sample of what that tool actually wants. Log-style tools get an
// "e.g. ..." in their real input shape; file-first tools get the drop
// instruction instead, since the desk is their drop zone too.
const PIN_PLACEHOLDERS: Record<string, string> = {
  clips: "Hit Do it! and the recorder opens, right in your browser…",
  notes: "e.g. Idea: a loyalty card, but for dog groomers…",
  cutline: "e.g. Netflix, $15.99, monthly",
  purrsuit: "e.g. Maria at Bloom Co, last spoke Tuesday, nudge her about the quote…",
  "corporate-bum-bum": "e.g. Applied to Stripe for the Growth Lead role today",
  doodle: "Hit Do it! and the sketchpad opens. Draw it there…",
  "invoice-chaser": "e.g. hud.io, $20.00, due in 2 weeks",
  "receipt-reader": "e.g. Starbucks, $6.40",
  "poster-to-calendar": "Drop the poster photo here…",
  "meal-scanner": "Drop the meal photo here…",
  "gap-scout": "Hit Do it! to open it, then drop your resume and the job post there…",
  "video-trimmer": "Drop the video here to trim it…",
  "image-converter": "Drop the image here to convert it…",
  "image-cropper": "Drop the image here to crop it…",
  ghostwriter: "e.g. Paste a ramble or transcript, get hooks, a script, and a caption…",
  audiogram: "Hit Do it! to open it, then drop your MP3 there…",
  "purr-code": "e.g. https://yourshop.com, whatever the code should open…",
  "sop-ify": "e.g. First we check the inbox, then we chase anything older than a day…",
  hearsay: "e.g. Just your brand name. He checks what the AIs say about it…",
  "video-converter": "Drop the video here to convert it…",
};

// The task chips under the desk: a pool of twenty, one per tool. When a
// session starts, four are drawn at random and kept for the whole session
// (persisted in sessionStorage), so different visitors meet different
// tools first and the set never reshuffles mid-session. "picker" chips
// open the file picker with the destination locked; the rest pin and
// focus the box (type-style tools file what you type; opens-style tools
// open on Do it! with an empty box, per their pin placeholders). "chip"
// is the tracking identity the desk_chip event has always carried.
type ChipDef = { key: string; chip: string; label: string; picker?: string };
const CHIP_POOL: ChipDef[] = [
  { key: "receipt-reader", chip: "receipts", label: "Read my receipts", picker: "image/*" },
  { key: "invoice-chaser", chip: "invoice", label: "Chase an invoice" },
  { key: "poster-to-calendar", chip: "poster", label: "Event posters to your calendar", picker: "image/*" },
  { key: "notes", chip: "voice-note", label: "Transcribe a voice note", picker: "audio/*" },
  { key: "cutline", chip: "subscription", label: "Track a subscription" },
  { key: "corporate-bum-bum", chip: "job-application", label: "Log a job application" },
  { key: "purrsuit", chip: "nudge", label: "Nudge a client" },
  { key: "meal-scanner", chip: "meal", label: "Scan my meal", picker: "image/*" },
  { key: "image-converter", chip: "image-convert", label: "Convert an image", picker: "image/*" },
  { key: "image-cropper", chip: "image-crop", label: "Crop an image", picker: "image/*" },
  { key: "video-trimmer", chip: "video-trim", label: "Trim a video", picker: "video/*" },
  { key: "video-converter", chip: "video-convert", label: "Convert a video", picker: "video/*" },
  { key: "clips", chip: "screen-record", label: "Record my screen" },
  { key: "doodle", chip: "doodle", label: "Doodle something" },
  { key: "gap-scout", chip: "resume-gaps", label: "Check my resume gaps" },
  { key: "ghostwriter", chip: "ramble", label: "Turn a ramble into posts" },
  { key: "audiogram", chip: "audiogram", label: "Make an audiogram" },
  { key: "purr-code", chip: "qr-code", label: "Make a QR code" },
  { key: "sop-ify", chip: "sop", label: "Turn my process into an SOP" },
  { key: "hearsay", chip: "ai-mentions", label: "Check my AI mentions" },
];
// The original four: what the static HTML ships with and what first
// paint renders, so hydration matches; the session draw swaps in on
// mount when it differs.
const DEFAULT_CHIP_KEYS = ["receipt-reader", "invoice-chaser", "poster-to-calendar", "notes"];
const CHIPS_STORAGE_KEY = "bb-desk-chips";

function drawChipKeys(): string[] {
  const keys = CHIP_POOL.map((c) => c.key);
  for (let i = keys.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    const t = keys[i];
    keys[i] = keys[j];
    keys[j] = t;
  }
  return keys.slice(0, 4);
}

function validChipKeys(v: unknown): v is string[] {
  return (
    Array.isArray(v) &&
    v.length === 4 &&
    new Set(v).size === 4 &&
    v.every((k) => typeof k === "string" && CHIP_POOL.some((c) => c.key === k))
  );
}

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

function toolKeyFromUrl(url: string): string {
  const m = url.match(/\/tools\/([^/]+)\//);
  return m ? m[1] : "";
}

// Leave a "from the front desk" marker before any hop to a tool page.
// /tools/drop-strip.js reads it on arrival and renders the handoff
// banner, then clears it: at most one banner per handoff.
function markDeskHandoff(
  toolKey: string,
  kind: "file" | "text" | "logged",
  extra?: { name?: string; excerpt?: string; copied?: boolean; text?: string }
): void {
  if (!toolKey) return;
  try {
    window.sessionStorage.setItem(
      "bb-desk-handoff",
      JSON.stringify({ tool: toolKey, kind, origin: "desk", ...(extra || {}), ts: Date.now() })
    );
  } catch {
    // No marker, no banner. The handoff itself still works.
  }
}

// Tiny staged-file preview: a thumbnail for images, a muted first
// frame for video, an icon chip for audio and anything else. One
// object URL per file, revoked when the file goes away or is swapped.
function FilePreview({ file }: { file: File }) {
  const [url, setUrl] = useState<string | null>(null);
  useEffect(() => {
    if (!file.type.startsWith("image/") && !file.type.startsWith("video/")) {
      setUrl(null);
      return;
    }
    const u = URL.createObjectURL(file);
    setUrl(u);
    return () => {
      URL.revokeObjectURL(u);
    };
  }, [file]);
  const cls = "bb-file-preview";
  if (file.type.startsWith("image/") && url) return <img className={cls} src={url} alt="" />;
  if (file.type.startsWith("video/") && url)
    return <video className={cls} src={url} preload="metadata" muted playsInline aria-hidden="true" />;
  return (
    <span className={cls + " bb-file-preview-icon"} aria-hidden="true">
      {file.type.startsWith("audio/") ? "🎵" : file.type.startsWith("video/") ? "🎬" : "📎"}
    </span>
  );
}

export default function CaptureBar() {
  const [text, setText] = useState("");
  const [file, setFile] = useState<File | null>(null);
  const [stagedImage, setStagedImage] = useState<File | null>(null);
  const [busy, setBusy] = useState(false);
  const [dragOver, setDragOver] = useState(false);
  const [result, setResult] = useState<Result>({ kind: "idle" });
  const [ambiguous, setAmbiguous] = useState<CaptureResponse | null>(null);
  const [otherOpen, setOtherOpen] = useState(false);
  const [otherNote, setOtherNote] = useState("");
  const [hatLine, setHatLine] = useState<string | null>(null);
  const [pinned, setPinned] = useState<Tool | null>(null);
  const [mention, setMention] = useState<{ start: number; query: string } | null>(null);
  const [mentionIndex, setMentionIndex] = useState(0);
  const deskFace = useDeskFace();
  const [recording, setRecording] = useState(false);
  const [peek, setPeek] = useState(false);
  const [crossing, setCrossing] = useState<{ img: string; name: string } | null>(null);
  const [phIndex, setPhIndex] = useState(0);
  const [chipKeys, setChipKeys] = useState<string[]>(DEFAULT_CHIP_KEYS);
  const fileInputRef = useRef<HTMLInputElement>(null);
  const cameraInputRef = useRef<HTMLInputElement>(null);
  const textRef = useRef<HTMLTextAreaElement>(null);
  const focusedRef = useRef(false);
  const pinnedRef = useRef<Tool | null>(null);
  const sessionWarmRef = useRef(false);
  const recorderRef = useRef<MediaRecorder | null>(null);
  const chunksRef = useRef<Blob[]>([]);
  const idemRef = useRef<string>("");
  // The last unsubmitted composer draft (trimmed, 3+ chars) that has
  // not been reported as abandoned yet; null when there is nothing
  // abandonable in the box. See the draft-tracking effects below.
  const lastDraftRef = useRef<string | null>(null);
  const cardRef = useRef<HTMLDivElement>(null);
  const resultRef = useRef<HTMLDivElement>(null);
  const waveCanvasRef = useRef<HTMLCanvasElement>(null);
  const audioCtxRef = useRef<AudioContext | null>(null);
  const analyserRef = useRef<AnalyserNode | null>(null);
  const rafRef = useRef(0);
  const recStreamRef = useRef<MediaStream | null>(null);
  const recStartRef = useRef(0);
  const recTimerRef = useRef(0);
  const discardRecRef = useRef(false);
  const [recElapsed, setRecElapsed] = useState("0:00");

  // Keep a ref mirror of the pin so the rotation timer can see it.
  useEffect(() => {
    pinnedRef.current = pinned;
  }, [pinned]);

  // The session chip draw: four prompts drawn once when a session
  // starts and kept in sessionStorage, so every visitor meets a
  // different set of tools and the set stays put for the whole session.
  // First paint renders the default four (identical to the static HTML,
  // so no hydration mismatch); this swaps in the session's draw right
  // after mount. A missing or corrupt stored draw means a fresh draw.
  useEffect(() => {
    let keys: string[] | null = null;
    const raw = ssGet(CHIPS_STORAGE_KEY);
    if (raw) {
      try {
        const parsed: unknown = JSON.parse(raw);
        if (validChipKeys(parsed)) keys = parsed;
      } catch {
        keys = null;
      }
    }
    if (!keys) {
      keys = drawChipKeys();
      ssSet(CHIPS_STORAGE_KEY, JSON.stringify(keys));
    }
    setChipKeys(keys);
  }, []);

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

  // Composer draft tracking for PostHog. The latest unsubmitted draft
  // (3+ trimmed chars) is remembered as it changes; any submit stamps
  // its words as submitted (trackComposerSubmitted clears the ref),
  // so only words that were never sent on can count as abandoned.
  // Clearing the box back to empty discards the draft: report it once,
  // with the last draft text, at the moment the box becomes empty.
  useEffect(() => {
    const t = text.trim();
    if (t.length >= 3) {
      lastDraftRef.current = t;
    } else if (t === "") {
      const d = lastDraftRef.current;
      if (d) {
        lastDraftRef.current = null;
        bbTrack("composer_abandoned", { text: d.slice(0, 2000), surface: "desk" });
      }
    }
  }, [text]);

  // Leaving the page with a draft still in the box abandons it too.
  // The ref is cleared before firing, so a draft already reported on
  // clear can never be reported a second time here.
  useEffect(() => {
    const onHide = () => {
      const d = lastDraftRef.current;
      if (d) {
        lastDraftRef.current = null;
        bbTrack("composer_abandoned", { text: d.slice(0, 2000), surface: "desk" });
      }
    };
    window.addEventListener("pagehide", onHide);
    return () => window.removeEventListener("pagehide", onHide);
  }, []);

  // When a submit resolves, the confirmation lives in the slot under
  // the composer. On a phone the keyboard closing and the layout
  // settling can leave it off-screen, so if the result block is not
  // fully in view, glide it into view (nearest edge, so a block that
  // is already comfortably visible never moves the page). "working"
  // states are skipped on purpose: they change under the user's
  // fingers (recording, sending) and must never yank the page.
  useEffect(() => {
    const terminal =
      result.kind === "success" || result.kind === "error" || result.kind === "message";
    if (!terminal && !ambiguous) return;
    const el = resultRef.current;
    if (!el) return;
    const raf = window.requestAnimationFrame(() => {
      try {
        const rect = el.getBoundingClientRect();
        if (rect.height === 0) return;
        const vh = window.innerHeight || document.documentElement.clientHeight;
        if (rect.top >= 0 && rect.bottom <= vh) return;
        el.scrollIntoView({ behavior: reducedMotion() ? "auto" : "smooth", block: "nearest" });
      } catch {
        /* scrolling is a nicety, never a blocker */
      }
    });
    return () => window.cancelAnimationFrame(raf);
  }, [result, ambiguous]);

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

  async function getSession() {
    const session = await fetch("/api/session.php", { credentials: "same-origin" }).then((r) => r.json());
    initTracking(session);
    return session as { csrf: string };
  }

  function fmtRecTime(ms: number): string {
    const s = Math.floor(ms / 1000);
    return Math.floor(s / 60) + ":" + String(s % 60).padStart(2, "0");
  }

  // One frame of the recording waveform: frequency bars off the
  // analyser, ink palette with a yellow accent bar. With no analyser
  // (Web Audio unavailable) it draws a calm static pattern instead.
  function drawWave() {
    const canvas = waveCanvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext("2d");
    if (!ctx) return;
    const w = canvas.width;
    const h = canvas.height;
    ctx.clearRect(0, 0, w, h);
    const bars = 28;
    const gap = 3;
    const bw = (w - gap * (bars - 1)) / bars;
    let data: Uint8Array<ArrayBuffer> | null = null;
    const analyser = analyserRef.current;
    if (analyser) {
      data = new Uint8Array(analyser.frequencyBinCount);
      analyser.getByteFrequencyData(data);
    }
    for (let i = 0; i < bars; i++) {
      const v = data ? data[Math.floor((i / bars) * data.length * 0.7)] / 255 : 0.3;
      const bh = Math.max(3, v * h);
      ctx.fillStyle = i % 5 === 4 ? "#ffd84d" : "#ff6b35";
      ctx.fillRect(i * (bw + gap), (h - bh) / 2, bw, bh);
    }
  }

  // Start the readout clock and the wave loop (a single static frame
  // under reduced motion; the dot and timer still run).
  function startRecUi() {
    recStartRef.current = Date.now();
    setRecElapsed("0:00");
    if (recTimerRef.current) window.clearInterval(recTimerRef.current);
    recTimerRef.current = window.setInterval(() => {
      setRecElapsed(fmtRecTime(Date.now() - recStartRef.current));
    }, 250);
    if (reducedMotion()) {
      window.setTimeout(drawWave, 60);
      return;
    }
    const loop = () => {
      drawWave();
      rafRef.current = window.requestAnimationFrame(loop);
    };
    rafRef.current = window.requestAnimationFrame(loop);
  }

  // Tear down every piece of the recording display machinery.
  function stopRecUi() {
    if (rafRef.current) {
      window.cancelAnimationFrame(rafRef.current);
      rafRef.current = 0;
    }
    if (recTimerRef.current) {
      window.clearInterval(recTimerRef.current);
      recTimerRef.current = 0;
    }
    if (audioCtxRef.current) {
      const actx = audioCtxRef.current;
      audioCtxRef.current = null;
      void actx.close().catch(() => {});
    }
    analyserRef.current = null;
  }

  // Leaving mid-recording: stop everything, keep nothing.
  useEffect(() => {
    return () => {
      discardRecRef.current = true;
      if (recorderRef.current && recorderRef.current.state !== "inactive") {
        try {
          recorderRef.current.stop();
        } catch {
          // Already stopped.
        }
      }
      if (recStreamRef.current) {
        recStreamRef.current.getTracks().forEach((t) => t.stop());
        recStreamRef.current = null;
      }
      stopRecUi();
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

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
        setResult({
          kind: "message",
          text: "Heard you. Tap Do it! to file it, or @ a tool first: Voice Notes to Text keeps the note, Voice to Content makes it posts, SOP Generator makes it a procedure.",
        });
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
    // The AudioContext is born inside the click, while the browser
    // still counts it as a user gesture; the waveform analyser hangs
    // off it once the stream arrives. If Web Audio is unavailable the
    // recording itself carries on exactly as before.
    let actx: AudioContext | null = null;
    try {
      const AC =
        window.AudioContext ||
        (window as unknown as { webkitAudioContext?: typeof AudioContext }).webkitAudioContext;
      if (AC) {
        actx = new AC();
        void actx.resume().catch(() => {});
      }
    } catch {
      actx = null;
    }
    navigator.mediaDevices
      .getUserMedia({ audio: true })
      .then((stream) => {
        chunksRef.current = [];
        discardRecRef.current = false;
        recStreamRef.current = stream;
        const rec = new MediaRecorder(stream);
        recorderRef.current = rec;
        rec.ondataavailable = (e) => {
          if (e.data && e.data.size) chunksRef.current.push(e.data);
        };
        rec.onstop = () => {
          stream.getTracks().forEach((t) => t.stop());
          recStreamRef.current = null;
          stopRecUi();
          setRecording(false);
          if (discardRecRef.current) {
            discardRecRef.current = false;
            return;
          }
          void transcribe(new Blob(chunksRef.current, { type: rec.mimeType || "audio/webm" }));
        };
        if (actx) {
          audioCtxRef.current = actx;
          try {
            const src = actx.createMediaStreamSource(stream);
            const analyser = actx.createAnalyser();
            analyser.fftSize = 128;
            src.connect(analyser);
            analyserRef.current = analyser;
          } catch {
            analyserRef.current = null;
          }
        }
        rec.start();
        setRecording(true);
        startRecUi();
        setResult({ kind: "working", text: "Recording… tap the mic again to stop." });
      })
      .catch(() => {
        if (actx) {
          void actx.close().catch(() => {});
        }
        setResult({ kind: "error", text: "Microphone access was blocked. Type it instead." });
      });
  }

  function openFilePicker(accept: string) {
    const input = fileInputRef.current;
    if (!input) return;
    input.accept = accept;
    input.click();
  }

  // A task chip is a shortcut to the same call the @ menu makes: it
  // pins the chip's tool (pill, pinned placeholder, the lot), measurably
  // identical to picking the tool by name. File-first chips then open
  // the picker with the destination already locked, so the picked file
  // routes straight to the pinned tool and nobody gets asked what the
  // file is. Text-first chips pin and focus the composer.
  // The chips live below the box, so on a phone a tap can pin a tool
  // without the user ever seeing the pill land. After any chip tap,
  // bring the desk card itself into view: the card carries a
  // scroll-margin, so its top settles a little below the viewport top
  // instead of jammed against it, with the pin pill and box on screen.
  function scrollDeskToTop() {
    const el = cardRef.current;
    if (!el) return;
    window.requestAnimationFrame(() => {
      try {
        el.scrollIntoView({ behavior: reducedMotion() ? "auto" : "smooth", block: "start" });
      } catch {
        /* scrolling is a nicety, never a blocker */
      }
    });
  }

  function pinFromChip(toolKey: string, chip: string, pickerAccept?: string) {
    const tool = tools.find((t) => t.key === toolKey);
    if (!tool) return;
    bbTrack("desk_chip", { chip });
    setAmbiguous(null);
    setOtherOpen(false);
    // A staged photo survives the pin on purpose: pin a photo tool and
    // the staged photo can be submitted straight to it.
    clearHat();
    setPinned(tool);
    bbTrack("desk_mention_selected", { tool: tool.key, via: "chip" });
    if (pickerAccept) {
      openFilePicker(pickerAccept);
    } else if (textRef.current) {
      textRef.current.focus();
    }
    scrollDeskToTop();
  }

  function stashAndGo(f: File, url: string, name: string, via: string) {
    bbTrack("capture_routed", { destination: name, surface: "home", via });
    const tool = tools.find((t) => t.url === url);
    markDeskHandoff(tool ? tool.key : toolKeyFromUrl(url), "file", { name: f.name });
    wearHat(tool ? tool.key : undefined, name);
    setResult({ kind: "working", text: "Sending \"" + f.name + "\" to " + name + ". Your file never leaves your device." });
    storeHandoff(f)
      .then(() => {
        // Once per session, he personally carries the file across the
        // desk on its way to the tool, Pet Pet dragged along behind
        // him; the handoff waits for them. The trip is the width of
        // the desk card and takes ~4 seconds, and any click or tap
        // skips it and goes straight to the tool.
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
          window.setTimeout(go, 4000);
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
        // The pinned tool can swallow this file: hand it straight over.
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
      if (kind === "image") {
        // The pinned tool takes no files at all: do not stage a photo
        // behind a pin that can never receive it.
        setResult({
          kind: "message",
          text: pinned.name + " cannot take a photo. Unpin it above the box, or @ one of the photo tools: Receipt Reader, Poster to Calendar, Meal Scanner.",
        });
        return;
      }
    }
    if (kind === "audio") {
      stashAndGo(f, "/tools/notes/", "Voice Notes to Text", "file");
    } else if (kind === "video") {
      stashAndGo(f, "/tools/video-trimmer/", "Video Trimmer", "file");
    } else if (kind === "image") {
      // No pin: the photo stages in the composer and waits. The hint
      // under the box teaches the @ move; nothing routes until the
      // visitor picks a photo tool and hits Do it!.
      setStagedImage(f);
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

  // The × on the staged file chip: throw the file away completely.
  // No charge, no navigation, no leftover state; the FilePreview
  // component revokes its object URL when it unmounts, and focus goes
  // back to the box.
  function removeStagedFile() {
    setFile(null);
    setStagedImage(null);
    setResult({ kind: "idle" });
    clearHat();
    if (textRef.current) textRef.current.focus();
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
    setOtherOpen(false);
    setOtherNote("");
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

  // Navigate-only tools whose page drops handed-off words straight
  // into a field on arrival; drop-strip.js keeps the field map
  // (FILL_FIELDS). Keep this list in step with it.
  const DROP_IN_TOOLS = ["purr-code", "ghostwriter", "sop-ify", "hearsay"];

  // Open a pinned tool the capture box cannot file into itself. The
  // words ride in the handoff marker: tools with a field that fits
  // them get the words dropped straight in on arrival; the rest keep
  // the clipboard copy. The hat swap acknowledges the call either way.
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
    // The full words ride in the marker too: tools with a field that
    // fits them (drop-strip.js keeps the map) get the words dropped
    // straight in on arrival; the clipboard copy stays as the
    // fallback for the tools with no field.
    markDeskHandoff(tool.key, "text", { excerpt: value.slice(0, 90), copied, text: value });
    const dropsIn = value.length > 0 && DROP_IN_TOOLS.includes(tool.key);
    setResult({
      kind: "message",
      text: dropsIn
        ? "Off to " + tool.name + ". Your words will be dropped in when it opens."
        : copied
          ? "Your words are copied. Paste them into " + tool.name + " when it opens. Taking you there…"
          : "Taking you to " + tool.name + "…",
    });
    window.setTimeout(() => {
      window.location.href = tool.url;
    }, dropsIn || copied ? 1500 : 900);
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

  // The ambiguous state's "Request it" path. /requests/ takes no
  // prefill, so the desk does what it does elsewhere: your words ride
  // along on the clipboard, and the desk says so before it takes you
  // there. Nothing is charged for any of this; the ambiguous state
  // never spends an action.
  async function requestFromDesk() {
    bbTrack("desk_other_selected", { path: "request" });
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
    setOtherNote(
      copied
        ? "Your words are copied. Paste them into the request form on the next page."
        : "The request form is on the next page. Take your words with you."
    );
    window.setTimeout(() => {
      window.location.href = "/requests/";
    }, copied ? 1700 : 1000);
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

  // Every submit that carries words reports them to PostHog, and the
  // words stop being an abandonable draft from that moment on (later
  // edits build a fresh draft). Empty submits report nothing.
  function trackComposerSubmitted() {
    const t = text.trim();
    if (!t) return;
    bbTrack("composer_submitted", { text: t.slice(0, 2000), surface: "desk" });
    lastDraftRef.current = null;
  }

  function submit() {
    if (stagedImage) {
      trackComposerSubmitted();
      if (pinned) {
        // The pin is the destination: hand the photo straight over.
        routeFile(stagedImage);
      } else {
        // Never guess a photo's destination silently: teach the @ pick
        // and keep the photo staged for it.
        setResult({
          kind: "message",
          text: "That photo needs a destination first. @Receipt Reader makes it a spreadsheet, @Poster to Calendar pulls out the events, @Meal Scanner reads the plate. Pick one, then Do it!",
        });
      }
      return;
    }
    if (file) {
      trackComposerSubmitted();
      routeFile(file);
      return;
    }
    if (pinned) {
      if (busy) return;
      trackComposerSubmitted();
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
    trackComposerSubmitted();
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
      <div className="capture-home-card desk-card" ref={cardRef}>
        <div className="desk-head">
          <h2 className="desk-greeting">What are we handling today?</h2>
        </div>
        <p className="desk-hint">Psst: type @ and Bum Bum shows you everything he can do.</p>
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
              placeholder={
                stagedImage && !pinned
                  ? "Photo ready. @Receipt Reader, @Poster to Calendar, or @Meal Scanner…"
                  : pinned
                    ? PIN_PLACEHOLDERS[pinned.key] || PLACEHOLDERS[phIndex]
                    : PLACEHOLDERS[phIndex]
              }
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
              <button
                type="button"
                className="desk-icon-btn"
                onClick={() => {
                  if (cameraInputRef.current) cameraInputRef.current.click();
                }}
                aria-label="Take a photo"
                title="Take a photo"
              >
                📷
              </button>
              <span className="desk-composer-spacer" />
              <button type="button" className="button" onClick={submit} disabled={busy}>
                Do it!
              </button>
            </div>
            {recording && (
              <>
                <div className="desk-rec-row">
                  <span className="desk-rec-dot" aria-hidden="true" />
                  <canvas ref={waveCanvasRef} className="desk-rec-wave" width={168} height={32} aria-hidden="true" />
                  <span className="desk-rec-time">{recElapsed}</span>
                  <span className="desk-rec-note">Recording… tap 🎤 to stop.</span>
                </div>
                <p className="desk-rec-hint">
                  Your words land in the box when you stop. Then @ sends them on: Voice Notes to Text keeps the note,
                  Voice to Content makes it posts, SOP Generator makes it a procedure.
                </p>
              </>
            )}
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
        {stagedImage && (
          <p className="desk-hint">
            {pinned
              ? "Photo ready for " + pinned.name + ". Hit Do it! and off it goes."
              : "Photo ready. @Receipt Reader turns it into a spreadsheet, @Poster to Calendar pulls out the events, @Meal Scanner reads the plate. Pick one, then Do it!"}
          </p>
        )}
        <div aria-live="polite" ref={resultRef}>
          {hatLine && <p className="desk-hat-line">{hatLine}</p>}
          {result.kind === "working" && <p className="capture-home-note">{result.text}</p>}
          {result.kind === "message" && (
            <p className="capture-home-note">
              {result.text}{" "}
              {result.suggestUrl && (
                <a
                  href={result.suggestUrl}
                  onClick={() => {
                    const key = toolKeyFromUrl(result.suggestUrl || "");
                    if (key) markDeskHandoff(key, "text", { copied: false, text: text.trim() });
                  }}
                >
                  Open {result.suggestName || "the tool"} →
                </a>
              )}
            </p>
          )}
          {result.kind === "success" && (
            <p className="capture-home-success">
              <b>✓ {result.summary}</b>{" "}
              <a
                href={result.url}
                onClick={() => {
                  const key = toolKeyFromUrl(result.url);
                  if (key) markDeskHandoff(key, "logged", { excerpt: result.summary });
                }}
              >
                View in {result.toolName} →
              </a>
            </p>
          )}
          {result.kind === "error" && (
            <p className="capture-home-error">
              {result.text} {result.limit && <a href="/account/">See your account</a>}
            </p>
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
                <button type="button" className="capture-home-chip" onClick={() => setOtherOpen((v) => !v)}>
                  Other
                </button>
              </div>
              {otherOpen && (
                <div className="desk-other">
                  <div className="desk-other-path">
                    <p>
                      <b>Already exists?</b> It might be sitting in the toolbox, just not one of the guesses above.
                    </p>
                    <a
                      className="capture-home-chip desk-other-btn"
                      href="#toolbox"
                      onClick={() => bbTrack("desk_other_selected", { path: "toolbox" })}
                    >
                      Check the toolbox
                    </a>
                  </div>
                  <div className="desk-other-path">
                    <p>
                      <b>Does not exist yet?</b> Request it in the community queue. The queue is public and
                      demand driven, so there is no promised timeline. Want it guaranteed? Operators get one
                      built within 36 hours.
                    </p>
                    <button type="button" className="capture-home-chip desk-other-btn" onClick={() => void requestFromDesk()}>
                      Request it
                    </button>
                    {otherNote && <p className="capture-home-note">{otherNote}</p>}
                  </div>
                </div>
              )}
            </div>
          )}
        </div>
        <div className="desk-chips-row">
          <div className="capture-home-chips desk-task-chips">
            {chipKeys.map((k) => {
              const def = CHIP_POOL.find((c) => c.key === k);
              if (!def) return null;
              return (
                <button
                  key={def.key}
                  type="button"
                  className="capture-home-chip"
                  onClick={() => pinFromChip(def.key, def.chip, def.picker)}
                >
                  {def.label}
                </button>
              );
            })}
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
        <input
          ref={cameraInputRef}
          type="file"
          accept="image/*"
          capture="environment"
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
        {file && (
          <div className="capture-home-filechip">
            <FilePreview file={file} />
            <span>
              {file.name} ({(file.size / 1048576).toFixed(1)} MB)
            </span>
            <button type="button" onClick={removeStagedFile} aria-label={"Remove " + file.name} title="Remove file">
              ×
            </button>
          </div>
        )}
      </div>
      {peek && <img className="desk-peek" src={FACE_PEEK} alt="" aria-hidden="true" />}
      {crossing && (
        <div className="desk-crossing-lane" aria-hidden="true">
          <div className="desk-crossing">
            <img className="desk-crossing-cat" src={FACE_BOX} alt="" />
            <img className="desk-crossing-petpet" src={FACE_PETPET} alt="" />
            {crossing.img && <img className="desk-crossing-item" src={crossing.img} alt="" />}
            <span className="desk-crossing-label">Taking that to {crossing.name}…</span>
          </div>
        </div>
      )}
    </section>
  );
}
