import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "Privacy Policy | Leave It to Bum Bum",
  description: "What Leave It to Bum Bum collects, what it doesn't, and how to ask for your data.",
};

export default function Privacy() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">Privacy Policy</p>
        <h1>Bum Bum is a cat,<br />not a data broker.</h1>
        <p className="lede">Last updated: September 2026. Here&apos;s the plain version of what we collect and why.</p>
      </section>
      <div className="page-body shell">
        <div className="prose">
          <h2>What we collect</h2>
          <ul>
            <li><strong>When you request a tool:</strong> your name, work email, and whatever you type about the annoying task. We need this to build the thing and reply to you.</li>
            <li><strong>When you subscribe:</strong> your email and plan details. Payments are processed by Stripe &mdash; we never see or store your card number.</li>
            <li><strong>Usage:</strong> how many actions you&apos;ve used, so we can meter your plan (and warn you at 80% and 100%, like we promised).</li>
          </ul>
          <h2>What we don&apos;t do</h2>
          <ul>
            <li>We don&apos;t sell your data. Ever. Bum Bum would rather nap.</li>
            <li>We don&apos;t share your info with advertisers or third parties, except the services required to run the site (hosting, Stripe for payments, email delivery).</li>
            <li>We don&apos;t collect anything we don&apos;t need to run the tools you asked for.</li>
          </ul>
          <h2>Cookies</h2>
          <p>We use the bare minimum: what&apos;s needed for the site to work (like keeping you signed in) and basic, privacy-friendly analytics so we know which pages are confusing. No creepy cross-site tracking.</p>
          <h2>Your data, your call</h2>
          <p>Email <a href="mailto:hello@leaveittobumbum.com"><strong>hello@leaveittobumbum.com</strong></a> any time to ask what we have, fix it, or delete it. We&apos;ll handle it within 30 days.</p>
          <h2>Changes</h2>
          <p>If this policy changes in a way that matters, we&apos;ll say so plainly on this page and update the date above. We won&apos;t bury it in legalese.</p>
        </div>
      </div>
      <SiteFooter />
    </main>
  );
}
