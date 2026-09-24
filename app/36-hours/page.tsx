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
      <section className="shell">
        <div className="promise-steps">
          <article className="promise-step ps-1"><b>Step 1</b><h3>You request</h3><p>Tell us the one annoying task, from your account page or the request form.</p></article>
          <article className="promise-step ps-2"><b>Step 2</b><h3>We scope it together</h3><p>We reply with the tiny, useful version we can actually build. Nothing vague ships.</p></article>
          <article className="promise-step ps-3"><b>Step 3</b><h3>The clock starts</h3><p>36 hours begins when scope <em>and</em> access are confirmed. We can&rsquo;t build on a maybe.</p></article>
          <article className="promise-step ps-4"><b>Step 4</b><h3>Bum Bum ships</h3><p>The tool lands in your toolbox, tested and working.</p></article>
        </div>
        <div className="prose">
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
        </div>
        <div className="promise-callout">
          <h2>If we miss, next month is on us.</h2>
          <p>Credited automatically, no forms, no begging. The credit applies to your Operator subscription and shows up on your account page.</p>
          <p><a href="/checkout/?plan=operator">Get the promise <span aria-hidden="true">↗</span></a></p>
        </div>
        <div className="prose">
          <h2>Two ways to get a tool built</h2>
          <p>Not every idea needs the 36-hour treatment. Pick the lane that fits.</p>
        </div>
        <div className="promise-compare">
          <div>
            <h3>Community queue</h3>
            <p><strong>Free.</strong> Your request joins the public queue where everyone can upvote it. We build the most-wanted tools as fast as we can: no timeline promise, but no cost either.</p>
            <a className="button outline" href="/requests/">See the queue <span aria-hidden="true">↗</span></a>
          </div>
          <div>
            <h3>Operator</h3>
            <p><strong>$49/mo.</strong> One scoped request every month, built within 36 hours of agreed scope, or your next month is free.</p>
            <a className="button" href="/checkout/?plan=operator">Go Operator <span aria-hidden="true">↗</span></a>
          </div>
        </div>
        <div className="prose">
          <h2>Third option: build it yourself</h2>
          <p>Allowed and respected. But before you open a blank repo at 11pm, <a href="/build-vs-buy/"><strong>read the honest build-vs-buy math</strong></a>.</p>
        </div>
        <div className="prose" style={{ paddingBottom: 110 }}>
          <h2>Questions, probably answered</h2>
          <details><summary>Does the request have to be my idea? <span>+</span></summary><p>Nope. Describe the pain however you like &mdash; &ldquo;I hate doing X every week&rdquo; is a perfectly good brief. We&rsquo;ll shape it into something buildable.</p></details>
          <details><summary>What if my request isn&rsquo;t eligible? <span>+</span></summary><p>We&rsquo;ll tell you within a day, with the reason in plain language and the closest eligible version. An ineligible request never burns your monthly slot.</p></details>
          <details><summary>Can I watch it being built? <span>+</span></summary><p>You&rsquo;ll get updates as it ships, and you can reply to the thread any time. Hovering is optional but permitted.</p></details>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
