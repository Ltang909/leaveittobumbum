import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  alternates: { canonical: "/pricing/" },
  openGraph: { url: "/pricing/" },
  title: "Pricing | Leave It to Bum Bum",
  description: "Every tool is free, with no limits and no subscription. The only paid offer is the 36-hour Promise: one scoped custom tool built for you, $49 one-time per request.",
};

export default function Pricing() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">Pricing</p>
        <h1>Every tool is free.<br />No limits, no meter.</h1>
        <p className="lede">Use every tool as much as you like, as a guest or with a free account. There are no action counts, no monthly buckets, and no subscription tiers for tool use. An account just gives your trackers, logs, and saved work a home.</p>
        <p><a className="button" href="/tools/">Open the toolbox</a></p>
      </section>
      <section className="shell" style={{ paddingBottom: 90 }}>
        <div className="prose">
          <h2>The one paid thing: the 36-hour Promise</h2>
          <p><strong>$49, one-time, per request.</strong> Not a subscription. When the tool you need does not exist yet, send one scoped request and Bum Bum builds it within 36 hours of the scope being agreed. If we miss the 36 hours, the request is on us.</p>
          <ul>
            <li><strong>What it includes:</strong> one scoped custom tool request, built and tested, landing in the toolbox within 36 hours of agreed scope.</li>
            <li><strong>What scoped means:</strong> one focused task, the tiny useful version of it. Bigger ideas get carved into a first slice that fits, and we tell you honestly what the rest would take.</li>
            <li><strong>How to request:</strong> send it from your <a href="/account/#requests">account page</a> and pay the $49 at checkout. We agree the scope with you by email, and the clock starts when scope and access are confirmed. If we cannot scope it, the $49 comes back.</li>
          </ul>
          <p><a href="/36-hours/"><strong>Read the full promise</strong></a>.</p>
          <h2>The free lane: the community queue</h2>
          <p>Not in a rush? The <a href="/requests/">community queue</a> is free and public. Requests sit where everyone can see them, people upvote the ones they want, and the most-wanted tools get built. No timeline promise on this lane, and no cost, ever.</p>
          <h2>Questions, probably answered</h2>
          <details><summary>Is it really free? <span>+</span></summary><p>Yes. Every tool, unlimited use, no card, no trial clock. We would rather you come back tomorrow than count what you did today.</p></details>
          <details><summary>Do I need an account? <span>+</span></summary><p>No. Guests use the tools right away. A free account is worth it if you want your trackers, logs, and saved work to live somewhere and follow you across devices.</p></details>
          <details><summary>Is there a subscription? <span>+</span></summary><p>No. Tool use has no tiers and no monthly fee. The only thing that costs money is a 36-hour Promise request, and that is a one-time $49 each time.</p></details>
          <details><summary>What happens if Bum Bum misses my 36 hours? <span>+</span></summary><p>The request is on us. The tool still gets built, and you do not pay for it.</p></details>
          <details><summary>I had a paid plan from before. What happens to it? <span>+</span></summary><p>Your tools keep working and are free now either way. Manage or cancel an existing subscription from your account page, through the billing portal, whenever you like.</p></details>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
