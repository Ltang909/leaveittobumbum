import { AuthCta } from "./auth-cta";

export function SiteHeader() {
  return (
    <header className="nav shell">
      <a className="brand" href="/" aria-label="Leave It to Bum Bum home" title="psst… boop the cat on the homepage"><img className="brand-cat" src="/bum/favicon-cat.png" alt="Bum Bum the cat" /><span>Leave It to<br /><b>Bum Bum</b></span></a>
      <nav aria-label="Main navigation"><a href="/tools/">Tools</a><a href="/#pricing">Pricing</a><a href="/#guarantee">36 hours</a><a href="/account/">Account</a></nav>
      <AuthCta />
    </header>
  );
}

export function SiteFooter() {
  return (
    <footer className="footer shell">
      <a className="brand" href="/"><img className="brand-cat" src="/bum/favicon-cat.png" alt="Bum Bum the cat" /><span>Leave It to<br /><b>Bum Bum</b></span></a>
      <p>Useful little tools for busy little businesses.<br />© {new Date().getFullYear()} Leave It to Bum Bum</p>
      <div><a href="mailto:hello@leaveittobumbum.com">hello@leaveittobumbum.com</a><a href="/about/">About</a><a href="/privacy/">Privacy</a><a href="/terms/">Terms</a></div>
      <p className="exe"><img src="/bum/cat-butt.png" alt="" aria-hidden="true" /> BumBum.exe is running…</p>
    </footer>
  );
}
