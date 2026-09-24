"use client";

import { useEffect, useState } from "react";
import { AuthCta } from "./auth-cta";

type Usage = { remaining: number; limit: number };

function ActionsPill() {
  const [usage, setUsage] = useState<Usage | null>(null);

  useEffect(() => {
    fetch("/api/usage.php", { credentials: "same-origin" })
      .then((r) => (r.ok ? r.json() : null))
      .then((d) => {
        if (d && d.usage && typeof d.usage.remaining === "number") setUsage(d.usage);
      })
      .catch(() => {});
    const onUsage = (e: Event) => {
      const u = (e as CustomEvent).detail as Usage | undefined;
      if (u && typeof u.remaining === "number" && typeof u.limit === "number") setUsage(u);
    };
    document.addEventListener("bb:usage", onUsage);
    return () => document.removeEventListener("bb:usage", onUsage);
  }, []);

  if (!usage || !(usage.limit > 0)) return null;
  return (
    <a className="usage-pill" href="/account/">
      {usage.remaining} of {usage.limit} actions left
    </a>
  );
}

export function SiteHeader() {
  const [open, setOpen] = useState(false);
  return (
    <header className="nav shell">
      <a className="brand" href="/" aria-label="Leave It to Bum Bum home" title="psst… boop the cat on the homepage"><img className="brand-cat" src="/bum/favicon-cat.png" alt="Bum Bum the cat" /><span>Leave It to<br /><b>Bum Bum</b></span></a>
      <nav aria-label="Main navigation" className={open ? "open" : ""}>
        <a href="/tools/" onClick={() => setOpen(false)}>Tools</a>
        <a href="/pricing/" onClick={() => setOpen(false)}>Pricing</a>
        <a href="/36-hours/" onClick={() => setOpen(false)}>36 hours</a>
        <a href="/account/" onClick={() => setOpen(false)}>Account</a>
        <span className="nav-menu-cta"><ActionsPill /><AuthCta /></span>
      </nav>
      <span className="nav-desktop-cta"><ActionsPill /><AuthCta /></span>
      <button type="button" className="menu-toggle" aria-expanded={open} aria-label={open ? "Close menu" : "Open menu"} onClick={() => setOpen((v) => !v)}>
        <span aria-hidden="true" /><span aria-hidden="true" /><span aria-hidden="true" />
      </button>
    </header>
  );
}

export function SiteFooter() {
  return (
    <footer className="footer shell">
      <a className="brand" href="/"><img className="brand-cat" src="/bum/favicon-cat.png" alt="Bum Bum the cat" /><span>Leave It to<br /><b>Bum Bum</b></span></a>
      <p>Useful little tools for busy little businesses.<br />© {new Date().getFullYear()} Leave It to Bum Bum</p>
      <div><a href="mailto:hello@leaveittobumbum.com">hello@leaveittobumbum.com</a><a href="/about/">About</a><a href="/team/">Team</a><a href="/privacy/">Privacy</a><a href="/terms/">Terms</a><a href="/docs/">Docs</a></div>
      <p className="exe"><img src="/bum/cat-butt-v2.png" alt="" aria-hidden="true" /> BumBum.exe is running…</p>
    </footer>
  );
}
