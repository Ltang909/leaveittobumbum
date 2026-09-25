import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "Docs | Leave It to Bum Bum",
  description: "Everything about Bum Bum in plain English: actions, plans, requesting tools, the 36-hour promise, and the toolbox.",
};

export default function Docs() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">Docs</p>
        <h1>Everything,<br />in plain English.</h1>
        <p className="lede">How Bum Bum works, what things cost, and how to get a tool built. No legalese, no maze.</p>
      </section>
      <section className="shell" style={{ paddingBottom: 110 }}>
        <div className="prose">
          <h2>Getting started</h2>
          <p>Create a free account and you get a workspace with 75 actions every month. <a href="/tools/"><strong>Browse the toolbox</strong></a>, pick the annoying task with your name on it, and hand over the messy bits. Clicking around and previewing is free. You only spend an action when Bum Bum finishes something useful.</p>

          <h2>What is an action?</h2>
          <p>An action is one completed result: a trimmed video, an audit report, a cleaned-up subscription list. Your whole team and every tool share the same monthly bucket: no per-tool limits, no per-seat math. Actions reset on your billing date and don&rsquo;t roll over. We nudge you at 80% and again at 100%, so the meter never surprises you.</p>

          <h2>Plans</h2>
          <ul>
            <li><strong>Poke around: $0.</strong> 75 actions a month, all current tools, 1 workspace, and the community request queue. No card needed.</li>
            <li><strong>Helper: $12/mo.</strong> 1,500 actions a month, everything in Poke around, 3 team members, email support, plus $6 per extra 1,000 actions if you run dry.</li>
            <li><strong>Operator: $49/mo.</strong> Everything in Helper, 6,000 actions a month, 10 team members, and one scoped tool request each month with the 36-hour guarantee.</li>
          </ul>
          <p>Upgrades apply immediately; downgrades take effect at the next billing date. Pause or cancel anytime from your account page. No contracts, no cancellation maze. <a href="/pricing/"><strong>See pricing</strong></a>.</p>

          <h2>Requesting a tool</h2>
          <p>There are two lanes. Pick the one that fits your urgency.</p>
          <ul>
            <li><strong>Community queue: free.</strong> Your request joins the <a href="/requests/"><strong>public queue</strong></a> where everyone can upvote it. We build the most-wanted tools as fast as we can. No timeline promise, no cost.</li>
            <li><strong>Operator: $49/mo.</strong> One scoped request every month, built within 36 hours of agreed scope, or your next month is free. Operator requests also appear in the <a href="/requests/"><strong>public queue</strong></a> with an operator badge so everyone can follow along. <a href="/36-hours/"><strong>Read the full promise</strong></a>.</li>
          </ul>
          <p>To request: hit <strong>Request a tool</strong> anywhere on the site. If you&rsquo;re not signed in, we&rsquo;ll nudge you to peek at the toolbox first (your task might already be solved) and create a free account.</p>

          <h2>The 36-hour promise, briefly</h2>
          <p>Operator members get one scoped tool request per month: one focused workflow buildable in about four working hours. The 36-hour clock starts when scope <em>and</em> access are confirmed; weekends and US federal holidays don&rsquo;t count, and the clock pauses while we wait on you. Miss the window and your next month is on us, credited automatically.</p>

          <h2>The toolbox</h2>
          <ul>
            <li><strong>Bum Bum Clips:</strong> record your screen right in the browser, trim it, download it.</li>
            <li><strong>Notes:</strong> voice notes that stay in your browser, with playback and download.</li>
            <li><strong>Cutline:</strong> every subscription you forgot about, in one place, with renewal nudges.</li>
            <li><strong>Purrsuit:</strong> a tiny CRM for following up with people.</li>
            <li><strong>Corporate Bum:</strong> track job applications without the spreadsheet dread.</li>
            <li><strong>Doodle:</strong> sketch something, export it, done.</li>
          </ul>

          <h2>Teams</h2>
          <p>Helper workspaces fit 3 team members, Operator fits 10. Everyone on the team draws from the same monthly action bucket, and the plan is billed to the team owner. Invite people from your account page with a shareable link, no email back-and-forth required.</p>

          <h2>Questions, probably answered</h2>
          <details><summary>Do unused actions roll over? <span>+</span></summary><p>No. The bucket refills fresh each month, which is how we keep the prices this low.</p></details>
          <details><summary>What happens if I hit 100% mid-month? <span>+</span></summary><p>Tools pause politely and tell you. Top up with an action pack or wait for the reset. Your data and settings stay put either way.</p></details>
          <details><summary>Can I switch plans later? <span>+</span></summary><p>Yes. Upgrades apply immediately, downgrades take effect at the next billing date. Either way takes about ten seconds.</p></details>
          <details><summary>Is my data used to train AI models? <span>+</span></summary><p>No. Your workspace contents are yours. Notes recordings never even leave your browser.</p></details>
          <details><summary>How do I cancel? <span>+</span></summary><p>From your account page, through the Stripe billing portal: two clicks. You keep what you paid for until the end of the billing period.</p></details>
          <p style={{ marginTop: 40 }}>Still stuck? <a href="mailto:hello@leaveittobumbum.com"><strong>hello@leaveittobumbum.com</strong></a> reaches a human (and a cat).</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
