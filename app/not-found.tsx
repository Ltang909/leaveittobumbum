import { SiteHeader, SiteFooter } from "./components/chrome";

export default function NotFound() {
  return (
    <main>
      <SiteHeader />
      <section className="shell page-404">
        <img className="sticker" src="/bum/cat-sleepy.png" alt="Bum Bum napping" />
        <p className="kicker">404</p>
        <h1>This page is napping.</h1>
        <p className="lede">Bum Bum could not find that one. It might have wandered off with the TV remote.</p>
        <p>
          <a className="button" href="/">Back home</a>{" "}
          <a className="button secondary" href="/tools/">Browse the toolbox</a>
        </p>
        <p className="fine">If I fits, I ships. This one did not fit.</p>
      </section>
      <SiteFooter />
    </main>
  );
}
