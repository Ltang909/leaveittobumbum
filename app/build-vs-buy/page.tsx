import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";
import DiyCalculator from "./calculator";

export const metadata: Metadata = {
  title: "Build vs. Buy: The Honest Math | Leave It to Bum Bum",
  description: "Could you vibe-code it yourself? Honestly, yeah. Here is what that actually costs once you count building, QA, and maintenance, and why the 36-hour promise exists.",
};

const DIY_WORK = [
  ["The happy path", "The fun 20%. The part you picture when you say \u201cI could build that in a weekend\u201d."],
  ["QA on devices you don't own", "Your laptop is not your users. Old phones, weird browsers, spotty wifi: they all get a vote."],
  ["Every edge case", "Your users will find in one afternoon what you missed in three weekends. They always do."],
  ["Auth and accounts", "Signups, logins, password resets, sessions that stay alive. None of it is the idea, all of it is required."],
  ["Hosting and deploys", "SSL certs, uptime, backups, and the deploy that breaks at the worst possible moment."],
  ["The 11pm bug report", "Software does not care about your evening plans. Someone will find the bug during dinner."],
  ["API changes under you", "The service you built on top of ships a breaking change. Your weekend project now has a roadmap."],
  ["Docs for future-you", "In six months you will stare at your own code like it was written by a stranger. Because it was."],
];

export default function BuildVsBuy() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">Build vs. buy</p>
        <h1>Sure, you could<br />build it yourself.</h1>
        <p className="lede">Honestly? Yeah, probably. Vibe-coding a little tool over a weekend is genuinely fun, and we know because we did exactly that. This page is the honest accounting of what happens after the weekend.</p>
      </section>
      <section className="shell">
        <div className="prose">
          <h2>The weekend is the cheap part</h2>
          <p>Getting something working on your laptop is the first 20% of shipping a tool. The other 80% is the part nobody posts about. Here is the full list, the one that turns a weekend project into a six-month situationship:</p>
        </div>
        <ul className="diy-list">
          {DIY_WORK.map(([title, body]) => (
            <li key={title}><b>{title}</b>{body}</li>
          ))}
        </ul>
        <div className="prose">
          <p>None of this is hard in the way that stops you. It is hard in the way that eats your evenings, one small papercut at a time, until the tool you were excited about becomes the chore you avoid.</p>
        </div>
        <DiyCalculator />
        <div className="promise-callout">
          <h2>Or skip the project entirely.</h2>
          <p>Describe the annoying task once. We agree on the tiny, useful version together. It lands in your toolbox within 36 hours, tested and working, and we keep it working. You never open a repo, never QA on a phone you do not own, and never get the 11pm bug report. If we miss the window, your next month is on us.</p>
          <p><a href="/checkout/?plan=operator">Get the promise <span aria-hidden="true">↗</span></a></p>
        </div>
        <div className="prose">
          <h2>The two lanes, side by side</h2>
        </div>
        <div className="promise-compare">
          <div>
            <h3>Build it yourself</h3>
            <p><strong>~25 hours and counting.</strong> Full control, full responsibility. You build it, you test it, you host it, you fix it at 11pm, you maintain it forever. Great if the building is the point.</p>
          </div>
          <div>
            <h3>Request it</h3>
            <p><strong>$49/mo.</strong> One scoped request every month, in your toolbox within 36 hours of agreed scope. Tested, hosted, and maintained by us. If we miss, next month is free.</p>
            <a className="button" href="/checkout/?plan=operator">Go Operator <span aria-hidden="true">↗</span></a>
          </div>
        </div>
        <div className="prose" style={{ paddingBottom: 40 }}>
          <h2>Not ready to pay? Fair.</h2>
          <p>The <a href="/requests/"><strong>community queue</strong></a> is free: request the tool, everyone upvotes, we build the most-wanted ones as fast as we can. No timeline promise, but no cost either. It is the perfect place to test whether your idea is as good as you think it is.</p>
        </div>
        <div className="prose" style={{ paddingBottom: 110 }}>
          <h2>Questions, probably answered</h2>
          <details><summary>But I genuinely enjoy building things? <span>+</span></summary><p>Then build! Seriously, the weekend project is a joy and we are not here to take that from you. And if you get stuck halfway, or the QA grind eats your third weekend, the request form will be right here. No judgment, only tools.</p></details>
          <details><summary>What does the $49 actually cover? <span>+</span></summary><p>One scoped tool request every month, plus 6,000 actions across every tool in the box, 10 team seats, and all the hosting, fixes, and maintenance. The maintenance is the part of DIY nobody budgets for.</p></details>
          <details><summary>What if my idea is too big for 36 hours? <span>+</span></summary><p>We carve out the most useful slice and tell you honestly what the rest would take. A sharp little tool this month beats a perfect system that never ships.</p></details>
          <details><summary>Do I own the tool? <span>+</span></summary><p>It lives in your Bum Bum toolbox as long as your subscription does, maintained and working. Cancel anytime and your data comes with you. The 11pm bug reports stay with us either way.</p></details>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
