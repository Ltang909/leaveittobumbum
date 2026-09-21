export function SiteHeader() {
  return (
    <header className="nav shell">
      <a className="brand" href="/" aria-label="Leave It to Bum Bum home"><span className="brand-mark">BB</span><span>Leave It to<br /><b>Bum Bum</b></span></a>
      <nav aria-label="Main navigation"><a href="/tools/">Tools</a><a href="/#pricing">Pricing</a><a href="/#guarantee">36 hours</a><a href="/account/">Account</a></nav>
      <a className="button button-small" href="/#top">Ask Bum Bum <span aria-hidden="true">↗</span></a>
    </header>
  );
}

export function SiteFooter() {
  return (
    <footer className="footer shell">
      <a className="brand" href="/"><span className="brand-mark">BB</span><span>Leave It to<br /><b>Bum Bum</b></span></a>
      <p>Useful little tools for busy little businesses.<br />© {new Date().getFullYear()} Leave It to Bum Bum</p>
      <div><a href="mailto:hello@leaveittobumbum.com">hello@leaveittobumbum.com</a><a href="/about/">About</a><a href="/privacy/">Privacy</a><a href="/terms/">Terms</a></div>
    </footer>
  );
}
