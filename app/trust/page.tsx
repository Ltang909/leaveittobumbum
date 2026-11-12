import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  alternates: { canonical: "/trust/" },
  openGraph: { url: "/trust/" },
  title: "Trust & Safety | Leave It to Bum Bum",
  description: "Who operates Leave It to Bum Bum, what happens to your files and data, how they are protected, and how cancelling works.",
};

export default function Trust() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">Trust &amp; Safety</p>
        <h1>Small business,<br />straight answers.</h1>
        <p className="lede">Who runs this site, what happens to your files, and how cancelling works. The short version, in one place.</p>
      </section>
      <div className="page-body shell">
        <div className="prose">
          <h2>Who operates this site</h2>
          <p>Leave It To Bum Bum is a one-person business operated by Leon Tang, based in Aurora, Ontario, Canada. The cat is real. The business is small on purpose: one operator, one support inbox, no call centre. Reach a human at <a href="mailto:hello@leaveittobumbum.com"><strong>hello@leaveittobumbum.com</strong></a>.</p>
          <h2>What happens to your files</h2>
          <ul>
            <li><strong>Most tools never see your files.</strong> The PDF, image, and video tools, and the voice recorder in Notes, run entirely in your browser. Files processed by them never leave your device and are never uploaded to a server.</li>
            <li><strong>Server-side tools keep only what the job needs.</strong> Trackers store the entries you type in. Day Log stores your log text against your account so your recap email and top list can be built. Every tool&apos;s page in the <a href="/docs/">docs</a> carries a data note saying exactly what that tool keeps.</li>
            <li><strong>Payments are handled by Stripe.</strong> We never see or store your card number.</li>
          </ul>
          <h2>How your data is protected</h2>
          <ul>
            <li><strong>Encryption in transit:</strong> every connection between your browser, our servers, and Google runs over HTTPS (TLS), so data stays encrypted while it moves.</li>
            <li><strong>Passwords are stored hashed</strong>, never in plain text.</li>
            <li><strong>Restricted storage and use:</strong> account data and optional connection tokens (such as the Google Calendar token for Day Log) live only in our production database on access-controlled servers, and are used only to run the features you turned on.</li>
            <li><strong>Never sold, never shared for ads, never used to train AI models.</strong> Details are in the <a href="/privacy/">privacy policy</a>, including how the optional Google Calendar connection works and how to disconnect it.</li>
          </ul>
          <h2>Cancelling and refunds</h2>
          <p>Cancel or downgrade to free in two clicks from your account page. There are no contracts and no cancellation flow designed to wear you down. A cancelled plan runs until the end of the billing period. We do not refund partial months, as stated in the <a href="/terms/">terms</a>.</p>
          <h2>Your data, your call</h2>
          <p>Email <a href="mailto:hello@leaveittobumbum.com"><strong>hello@leaveittobumbum.com</strong></a> any time to ask what we hold about you, correct it, or have it deleted. We handle requests within 30 days.</p>
        </div>
      </div>
      <SiteFooter />
    </main>
  );
}
