import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  alternates: { canonical: "/terms/" },
  openGraph: { url: "/terms/" },
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
        <p className="lede">Last updated: November 2026. Be reasonable, and don&apos;t use tiny tools for giant crimes.</p>
      </section>
      <div className="page-body shell">
        <div className="prose">
          <h2>The service</h2>
          <p>Leave It to Bum Bum provides small, single-purpose web tools for small businesses, plus optional custom tool builds through the 36-hour Promise. We aim for &ldquo;just works,&rdquo; but software has moods &mdash; we don&apos;t guarantee uninterrupted or error-free service.</p>
          <h2>Pricing</h2>
          <ul>
            <li><strong>Every tool is free</strong>, with no usage limits, no counts, and no subscription. An account is optional and also free.</li>
            <li>The only paid offer is the <strong>36-hour Promise</strong>: a one-time $49 per request, described below. Payment is arranged when a request is scoped.</li>
            <li>Paid plans sold before November 17, 2026 (Helper, Operator) are retired. An existing subscription can be cancelled anytime from your account page; it runs until the end of its billing period. No refunds for partial months, but no hard feelings either.</li>
          </ul>
          <h2>The 36-hour Promise</h2>
          <p>One scoped request per Promise: one focused workflow buildable in about four working hours. It can use approved existing services, but can&apos;t include regulated data, complex migrations, app-store review work, or anything waiting on a third party. The clock starts when scope and access are confirmed; weekends and US federal holidays are excluded. If we miss the window, the request is on us: you don&apos;t pay for it.</p>
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
