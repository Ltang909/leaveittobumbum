import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "Terms of Service | Leave It to Bum Bum",
  description: "The plain-language terms for using Leave It to Bum Bum's tiny tools.",
};

export default function Terms() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">Terms of Service</p>
        <h1>The short version.</h1>
        <p className="lede">Last updated: September 2026. Be reasonable, pay for the plan you picked, and don&apos;t use tiny tools for giant crimes.</p>
      </section>
      <div className="page-body shell">
        <div className="prose">
          <h2>The service</h2>
          <p>Leave It to Bum Bum provides small, single-purpose web tools for small businesses, plus optional custom tool builds for Operator members. We aim for &ldquo;just works,&rdquo; but software has moods &mdash; we don&apos;t guarantee uninterrupted or error-free service.</p>
          <h2>Plans and actions</h2>
          <ul>
            <li><strong>Poke Around</strong> is free: 75 actions/month.</li>
            <li><strong>Helper</strong> ($12/month) and <strong>Operator</strong> ($49/month) are billed monthly through Stripe.</li>
            <li>One action = one useful result (one quote, one cleaned-up note, one follow-up). Actions reset monthly and don&apos;t roll over. We warn you at 80% and 100%.</li>
            <li>You can cancel anytime; your plan runs until the end of the billing period. No refunds for partial months, but no hard feelings either.</li>
          </ul>
          <h2>The 36-hour promise (Operator)</h2>
          <p>One scoped request per month: one focused workflow buildable in about four working hours. It can use approved existing services, but can&apos;t include regulated data, complex migrations, app-store review work, or anything waiting on a third party. The clock starts when scope and access are confirmed; weekends and US federal holidays are excluded. If we miss the window, your next month is on us.</p>
          <h2>Acceptable use</h2>
          <p>Don&apos;t use the tools for anything illegal, abusive, or wildly outside their purpose. Don&apos;t try to break, scrape, or resell the service. We may suspend accounts that do &mdash; after a warning, unless it&apos;s egregious.</p>
          <h2>Your content</h2>
          <p>What you type into the tools is yours. By using the service you give us permission to process it so the tools can do their job. We don&apos;t claim ownership of your quotes, notes, or follow-ups.</p>
          <h2>Liability</h2>
          <p>To the maximum extent allowed by law, we&apos;re not liable for indirect or consequential damages (lost profits, lost naps). Our total liability is limited to what you paid us in the 12 months before the claim.</p>
          <h2>Changes</h2>
          <p>We&apos;ll post updated terms here with a new date. Continued use after changes means you accept them. For big changes, we&apos;ll email you first.</p>
        </div>
      </div>
      <SiteFooter />
    </main>
  );
}
