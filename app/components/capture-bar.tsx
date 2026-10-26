"use client";

import { useRef, useState } from "react";
import { bbTrack, initTracking } from "../lib/track";

// The homepage "Log anything" box. Same brain as the account dashboard's
// capture box: text goes to /api/capture.php, which routes it into Notes,
// Receipt Reader, Job Application Tracker, Invoice Reminder, or Subscription
// Tracker and spends exactly one action, the tool's. Files never touch the
// server: Bum Bum routes them by kind (audio to Voice Notes, video to the
// Trimmer, photos get a one-tap "what is it?" pick), stashes them in
// IndexedDB, and hands them to the tool page, which picks them up via
// /tools/capture-handoff.js. Guests are welcome; the account prompt does
// its own asking later, per the delayed-prompt pattern.

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

export default function CaptureBar() {
  const [text, setText] = useState("");
  const [file, setFile] = useState<File | null>(null);
  const [pendingImage, setPendingImage] = useState<File | null>(null);
  const [busy, setBusy] = useState(false);
  const [dragOver, setDragOver] = useState(false);
  const [result, setResult] = useState<Result>({ kind: "idle" });
  const [ambiguous, setAmbiguous] = useState<CaptureResponse | null>(null);
  const fileInputRef = useRef<HTMLInputElement>(null);
  const idemRef = useRef<string>("");

  async function getSession() {
    const session = await fetch("/api/session.php", { credentials: "same-origin" }).then((r) => r.json());
    initTracking(session);
    return session as { csrf: string };
  }

  function stashAndGo(f: File, url: string, name: string, via: string) {
    bbTrack("capture_routed", { destination: name, surface: "home", via });
    setResult({ kind: "working", text: "Sending \"" + f.name + "\" to " + name + ". Your file never leaves your device." });
    storeHandoff(f)
      .then(() => {
        window.setTimeout(() => {
          window.location.href = url + "?from=capture";
        }, 700);
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
    idemRef.current = idem();
    bbTrack("capture_started", { surface: "home", kind: "text" });
    void sendText(undefined, undefined, idemRef.current);
  }

  return (
    <section
      className={"capture-home shell" + (dragOver ? " drag-over" : "")}
      aria-label="Log anything"
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
        }
      }}
    >
      <div className="capture-home-card">
        <div className="capture-home-head">
          <img src="/bum/cat-wink-blep.png" alt="Bum Bum" width="72" height="72" />
          <div>
            <p className="kicker">The front desk</p>
            <h2>Log anything. Bum Bum files it.</h2>
            <p className="capture-home-invite">
              Drop it here, I will handle it. A receipt, a subscription you keep meaning to track, an invoice
              somebody owes you, a half-formed idea. Type it, paste it, or drop a file on me, and I will put it
              where it belongs and tell you what I did. One action, same as using the tool itself.
            </p>
          </div>
        </div>
        <div className="capture-home-row">
          <input
            type="text"
            value={text}
            onChange={(e) => setText(e.target.value)}
            onKeyDown={(e) => {
              if (e.key === "Enter") submit();
            }}
            placeholder="Netflix $15.99 a month … applied to Stripe … idea: neon backgrounds"
            aria-label="Type anything to log it"
            autoComplete="off"
            maxLength={2000}
          />
          <button type="button" className="capture-home-attach" onClick={() => fileInputRef.current?.click()} title="Attach a photo, video, or audio file">
            📎 Attach
          </button>
          <button type="button" className="button" onClick={submit} disabled={busy}>
            Log it
          </button>
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
    </section>
  );
}
