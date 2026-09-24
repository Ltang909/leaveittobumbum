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

type Session = { authenticated: boolean; user?: { email?: string } };

function RequestToolModal({ open, prefill, onClose }: { open: boolean; prefill: string; onClose: () => void }) {
  const [state, setState] = useState<"idle" | "sending" | "sent" | "error">("idle");
  const [session, setSession] = useState<Session | null>(null);
  const [showForm, setShowForm] = useState(false);

  useEffect(() => {
    if (!open) return;
    setState("idle");
    setSession(null);
    setShowForm(false);
    fetch("/api/session.php", { credentials: "same-origin" })
      .then((r) => r.json())
      .then((s) => {
        setSession(s && s.authenticated ? { authenticated: true, user: s.user } : { authenticated: false });
        if (s && s.authenticated) setShowForm(true);
      })
      .catch(() => setSession({ authenticated: false }));
  }, [open]);

  if (!open) return null;

  async function submitRequest(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setState("sending");
    const form = new FormData(event.currentTarget);
    const response = await fetch("/api/tool-requests.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(Object.fromEntries(form)),
    });
    setState(response.ok ? "sent" : "error");
  }

  const loggedOut = session && !session.authenticated;

  return (
    <div className="modal-backdrop" onMouseDown={(event) => event.target === event.currentTarget && onClose()}>
      <section className="modal" role="dialog" aria-modal="true" aria-labelledby="request-title">
        <button className="modal-close" aria-label="Close" onClick={onClose}>×</button>
        <img className="modal-peek" src="/bum/cat-peeking.png" alt="" aria-hidden="true" />
        {state === "sent" ? (
          <div className="success">
            <span>✓</span>
            <h2>Bum Bum is on it.</h2>
            <p>Your idea just joined the community request queue.</p>
            <p><a className="button" href="/requests/">See the queue <span aria-hidden="true">↗</span></a></p>
            <button className="button outline" onClick={onClose}>Done</button>
          </div>
        ) : loggedOut && !showForm ? (
          <>
            <p className="kicker">Request a tool</p>
            <h2 id="request-title">Two quick things first.</h2>
            <div className="request-nudge">
              <p><strong>1. Checked the toolbox already?</strong> Your annoying task might already be solved. <a href="/tools/">Browse the toolbox</a> or <a href="/requests/">see what&rsquo;s already requested</a>.</p>
              <p><strong>2. You&rsquo;ll need a free account.</strong> Requests live in the community queue under your workspace, so we can follow up when your tool ships.</p>
              <p className="request-nudge-cta">
                <a className="button" href="/account/">Create a free account <span aria-hidden="true">↗</span></a>
                <a className="button outline" href="/account/">Sign in</a>
              </p>
              <p className="request-skip">Just want to send the idea? <button type="button" className="linklike" onClick={() => setShowForm(true)}>Continue to the form</button></p>
            </div>
            <RequestPaths />
          </>
        ) : (
          <>
            <p className="kicker">Request a tool</p>
            <h2 id="request-title">What do you wish would just do itself?</h2>
            <p className="queue-first">First, <a href="/requests/">check the community queue</a>. Your tool might already be requested, and upvotes decide what gets built first.</p>
            <form onSubmit={submitRequest}>
              <label>Your name<input name="name" required autoFocus /></label>
              <label>Work email<input name="email" type="email" required defaultValue={session?.user?.email ?? ""} /></label>
              <label>The annoying task<textarea name="problem" required placeholder="Every Friday I copy..." rows={4} key={prefill} defaultValue={prefill} /></label>
              <label>What would “done” look like?<textarea name="outcome" required placeholder="I want to click once and get..." rows={3} /></label>
              <label className="public-consent"><input type="checkbox" required /> I get it: my request goes into the public community queue. My task and “done” description will be visible to everyone; my name and email stay private.</label>
              <input name="website" style={{ display: "none" }} tabIndex={-1} autoComplete="off" aria-hidden="true" />
              <button className="button" disabled={state === "sending"}>{state === "sending" ? "Sending…" : "Send to Bum Bum"} <span aria-hidden="true">↗</span></button>
              {state === "error" && <p className="form-error">That did not go through. Email hello@leaveittobumbum.com and we will pick it up.</p>}
            </form>
            <RequestPaths />
          </>
        )}
      </section>
    </div>
  );
}

function RequestPaths() {
  return (
    <details className="request-paths">
      <summary>Two ways to get a tool built <span>+</span></summary>
      <div className="request-paths-grid">
        <div>
          <strong>Community queue</strong>
          <p>Free. Your request joins the public queue where everyone can upvote it. We build the most-wanted tools as fast as we can, but there&rsquo;s no timeline promise.</p>
          <p><a href="/requests/">See the queue</a></p>
        </div>
        <div>
          <strong>Operator</strong>
          <p>$49/mo. One scoped request every month, built within 36 hours of agreed scope, or your next month is on us.</p>
          <p><a href="/36-hours/">Read the promise</a></p>
        </div>
      </div>
    </details>
  );
}
