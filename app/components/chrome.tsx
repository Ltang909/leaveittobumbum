"use client";

import { useEffect, useState } from "react";
import { AuthCta } from "./auth-cta";

type Usage = { remaining: number; limit: number; unlimited?: boolean };

function ActionsPill() {
  const [usage, setUsage] = useState<Usage | null>(null);

  useEffect(() => {
    fetch("/api/usage.php", { credentials: "same-origin" })
      .then((r) => (r.ok ? r.json() : null))
      .then((d) => {
        if (d && d.usage && (d.usage.unlimited || typeof d.usage.remaining === "number")) setUsage(d.usage);
      })
      .catch(() => {});
    const onUsage = (e: Event) => {
      const u = (e as CustomEvent).detail as Usage | undefined;
      if (u && (u.unlimited || (typeof u.remaining === "number" && typeof u.limit === "number"))) setUsage(u);
    };
    document.addEventListener("bb:usage", onUsage);
    return () => document.removeEventListener("bb:usage", onUsage);
  }, []);

  if (!usage) return null;
  if (usage.unlimited) {
    return (
      <a className="usage-pill unlimited" href="/account/">
        Unlimited actions
      </a>
    );
  }
  if (!(usage.limit > 0)) return null;
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

const SOCIALS = [
  { label: 'LinkedIn', href: 'https://www.linkedin.com/in/leaveittobumbum/', path: 'M20.45 20.45h-3.55v-5.57c0-1.33-.03-3.04-1.85-3.04-1.86 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.47-.9 1.63-1.85 3.36-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12zM7.12 20.45H3.55V9h3.57v11.45z' },
  { label: 'Instagram', href: 'https://www.instagram.com/leaveittobumbum/', path: 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z' },
  { label: 'Pinterest', href: 'https://ca.pinterest.com/0s5nekxyh3lt53n8j9balgylnaznz9/', path: 'M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z' },
];

export function SiteFooter() {
  return (
    <footer className="footer shell">
      <div className="footer-brand">
        <a className="brand" href="/"><img className="brand-cat" src="/bum/favicon-cat.png" alt="Bum Bum the cat" /><span>Leave It to<br /><b>Bum Bum</b></span></a>
        <p>Useful little tools for busy little businesses.</p>
        <div className="socials">
          {SOCIALS.map((s) => (
            <a key={s.label} href={s.href} target="_blank" rel="noopener" aria-label={s.label}>
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d={s.path} /></svg>
            </a>
          ))}
        </div>
      </div>
      <nav className="footer-links" aria-label="Company">
        <b>Bum Bum</b>
        <a href="/about/">About</a>
        <a href="/team/">Team</a>
        <a href="/docs/">Docs</a>
        <a href="/ai-instructions/">AI Instructions</a>
      </nav>
      <nav className="footer-links" aria-label="Legal">
        <b>Fine print</b>
        <a href="/privacy/">Privacy</a>
        <a href="/terms/">Terms</a>
        <a href="mailto:hello@leaveittobumbum.com">Contact</a>
      </nav>
      <div className="footer-bottom">
        <p>© {new Date().getFullYear()} Leave It to Bum Bum</p>
        <p className="exe"><img src="/bum/cat-butt-v2.png" alt="" aria-hidden="true" /> BumBum.exe is running…</p>
      </div>
      <script src="/petpet.js?v=6" defer></script>
    </footer>
  );
}
