"use client";

import { FormEvent, useEffect, useState } from "react";

export function useRequestTool() {
  const [requestOpen, setRequestOpen] = useState(false);
  const [prefill, setPrefill] = useState("");

  function openRequest(prefillText = "") {
    setPrefill(prefillText);
    setRequestOpen(true);
  }

  const requestModal = (
    <RequestToolModal open={requestOpen} prefill={prefill} onClose={() => setRequestOpen(false)} />
  );

  return { openRequest, requestModal };
}

function RequestToolModal({ open, prefill, onClose }: { open: boolean; prefill: string; onClose: () => void }) {
  const [state, setState] = useState<"idle" | "sending" | "sent" | "error">("idle");

  useEffect(() => {
    if (open) setState("idle");
  }, [open ]);

  if (!open) return null;

  async function submitRequest(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setState("sending");
    const form = new FormData(event.currentTarget);
    const response = await fetch("/api/tool-requests", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(Object.fromEntries(form)),
    });
    setState(response.ok ? "sent" : "error");
  }

  return (
    <div className="modal-backdrop" onMouseDown={(event) => event.target === event.currentTarget && onClose()}>
      <section className="modal" role="dialog" aria-modal="true" aria-labelledby="request-title">
        <button className="modal-close" aria-label="Close" onClick={onClose}>×</button>
        <img className="modal-peek" src="/bum/cat-peek.png" alt="" aria-hidden="true" />
        {state === "sent" ? (
          <div className="success">
            <span>✓</span>
            <h2>Bum Bum is on it.</h2>
            <p>We have your request and will follow up with a sensible tiny version.</p>
            <button className="button" onClick={onClose}>Done</button>
          </div>
        ) : (
          <>
            <p className="kicker">Request a tool</p>
            <h2 id="request-title">What do you wish would just do itself?</h2>
            <form onSubmit={submitRequest}>
              <label>Your name<input name="name" required autoFocus /></label>
              <label>Work email<input name="email" type="email" required /></label>
              <label>The annoying task<textarea name="problem" required placeholder="Every Friday I copy..." rows={4} key={prefill} defaultValue={prefill} /></label>
              <label>What would “done” look like?<textarea name="outcome" required placeholder="I want to click once and get..." rows={3} /></label>
              <button className="button" disabled={state === "sending"}>{state === "sending" ? "Sending…" : "Send to Bum Bum"} <span aria-hidden="true">↗</span></button>
              {state === "error" && <p className="form-error">That did not go through. Email hello@leaveittobumbum.com and we will pick it up.</p>}
            </form>
          </>
        )}
      </section>
    </div>
  );
}
