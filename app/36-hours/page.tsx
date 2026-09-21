import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "The 36-Hour Promise | Leave It to Bum Bum",
  description: "Operator members get one scoped tool request each month. Bum Bum ships it within 36 hours, or the next month is on us.",
};

export default function ThirtySixHours() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">The Bum Bum Promise</p>
        <h1>36 hours,<br />or next month is on us.</h1>
        <p className="lede">Operator members get one scoped tool request each month. Once we agree on the tiny, useful version, Bum Bum ships it within 36 hours.</p>
      </section>
      <section className="shell" style={{ paddingBottom: 110 }}>
        <div className="prose">
          <h2>How it works</h2>
          <ul>
            <li><strong>You request.</strong> Tell us the one annoying task from your account page or the request form.</li>
            <li><strong>We scope it together.</strong> We reply with the tiny, useful version we can actually build. Nothing vague ships.</li>
            <li><strong>The clock starts.</strong> 36 hours begins when scope <em>and</em> access are confirmed &mdash; we can&rsquo;t build on a maybe.</li>
            <li><strong>Bum Bum ships.</strong> The tool lands in your toolbox, tested and working.</li>
          </ul>
          <h2>What counts as a scoped request</h2>
          <p>One focused workflow that can be built in about four working hours. It can use approved existing services, but it can&rsquo;t include:</p>
          <ul>
            <li>Regulated data (health, finance credentials, that neighborhood)</li>
            <li>Complex data migrations</li>
            <li>Mobile app store review timelines</li>
            <li>Work stuck waiting on a third party</li>
          </ul>
          <p>If your idea is bigger than that, we&rsquo;ll carve out the most useful slice and tell you honestly what the rest would take.</p>
          <h2>The fine print (short, we promise)</h2>
          <ul>
            <li><strong>One per month</strong> on the Operator plan. Unused requests don&rsquo;t roll over.</li>
            <li><strong>Weekends and US federal holidays</strong> don&rsquo;t count against the clock. Bum Bum naps.</li>
            <li><strong>The clock pauses</strong> if we&rsquo;re waiting on you for access, answers, or approvals.</li>
          </ul>
          <h2>If we miss</h2>
          <p>Your next month is on us &mdash; credited automatically, no forms, no begging. The credit applies to your Operator subscription and shows up on your account page.</p>
          <h2>Questions, probably answered</h2>
          <details><summary>Does the request have to be my idea? <span>+</span></summary><p>Nope. Describe the pain however you like &mdash; &ldquo;I hate doing X every week&rdquo; is a perfectly good brief. We&rsquo;ll shape it into something buildable.</p></details>
          <details><summary>What if my request isn&rsquo;t eligible? <span>+</span></summary><p>We&rsquo;ll tell you within a day, with the reason in plain language and the closest eligible version. An ineligible request never burns your monthly slot.</p></details>
          <details><summary>Can I watch it being built? <span>+</span></summary><p>You&rsquo;ll get updates as it ships, and you can reply to the thread any time. Hovering is optional but permitted.</p></details>
          <p style={{ marginTop: 40 }}><a className="button" href="/checkout/?plan=operator">Get the promise <span aria-hidden="true">↗</span></a></p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
