import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";
import ToolCallout from "../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/how-to-find-subscriptions-you-forgot-about/" },
  openGraph: { url: "/how-to-find-subscriptions-you-forgot-about/" },
  title: "How to find subscriptions you forgot about (and cancel them) | Leave It to Bum Bum",
  description: "Forgotten subscriptions hide in app stores, PayPal, and old email receipts. A 20-minute audit finds them all, plus the calendar trick that stops free trials from billing you.",
};

export default function FindSubscriptionsGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell blog-head">
        <p className="kicker">Guide</p>
        <h1>How to find subscriptions you forgot about (and cancel them)</h1>
        <p className="lede">Forgotten subscriptions are usually small individually, but they add up. Twenty minutes of digging finds every recurring charge you are paying for and getting nothing from.</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="Subscription Tracker"
            href="/tools/cutline/"
            heading="Want every renewal in one place?"
            blurb="Subscription Tracker keeps every subscription in one list and nudges you before each renewal. Free, with no limits."
            cta="Open Subscription Tracker"
          />

          <h2>What to do</h2>

          <h3>Know where forgotten subscriptions hide</h3>
          <ul>
          <li><strong>App stores.</strong> Anything you subscribed to inside an app on your phone lives in your Apple or Google account, often under a name you barely recognize. Puzzle games and photo editors are the classic offenders.</li>
          <li><strong>PayPal.</strong> Plenty of services bill through PayPal pre-approved payments. They never show a card charge, so they slip past a statement scan.</li>
          <li><strong>Card and bank statements.</strong> Charges hide behind odd merchant names. "SP *XYZ MEDIA" might be the streaming add-on you trialled last winter.</li>
          <li><strong>Your email receipts.</strong> Search your inbox for "receipt", "renewal", and "your subscription". Every paid service writes to you at least once a year, usually right after taking your money.</li>
          <li><strong>Free trials that converted.</strong> The seven day trial from two phones ago may still be billing you monthly.</li>
          </ul>

          <h3>Run the 20-minute audit</h3>
          <ul>
          <li><strong>Minutes 1 to 5:</strong> Open your phone's subscription list. On iPhone, go to Settings, tap your name, then Subscriptions. On Android, open the Play Store, tap your profile, then Payments and subscriptions. Screenshot anything you do not instantly recognize.</li>
          <li><strong>Minutes 5 to 10:</strong> Log into PayPal and open Settings, then Payments, then Manage automatic payments. Cancel anything stale right there.</li>
          <li><strong>Minutes 10 to 15:</strong> Scan three months of card and bank statements. Highlight every repeating charge, even the tiny ones. Small charges are the longest running ones because nobody notices $4.99.</li>
          <li><strong>Minutes 15 to 20:</strong> Search your email for "renewal" and "receipt" and cross-check against your list. Put every subscription you find into one list with its price and next renewal date. That list is your whole picture, probably for the first time.</li>
          </ul>

          <h3>Stop free trials from billing you</h3>
          <p>Trials get you because the charge lands weeks after you stopped caring about the app. The fix takes ten seconds at sign-up time: the moment you start a trial, add a calendar event for two days before it ends, titled "Cancel the trial?" Future you gets a nudge while there is still time to act. Do the same for annual renewals on anything pricey. A calendar reminder turns a $180 renewal into a decision you make ahead of time, instead of a charge you only notice after it lands.</p>

          <h3>Cancel, pause, or downgrade: which stops the money?</h3>
          <ul>
          <li><strong>Cancel</strong> ends the billing. On most services you keep access until the end of the paid period, so cancel the day you decide, not the day before renewal when you will forget.</li>
          <li><strong>Pause</strong> stops charges for a set stretch, then restarts on its own. Handy for a service you genuinely return to, like a fitness app during an injury. Set a reminder for the restart date, because the restart will not remind you.</li>
          <li><strong>Downgrade</strong> lowers the price but keeps the meter running. Pick this when you still use the thing and only the fancy tier was the mistake.</li>
          <li><strong>Watch the wording.</strong> Some services label the buttons to trick you, and "confirm cancellation" hides two screens deep. Click through until you see a clear "cancelled" message, and keep the confirmation email.</li>
          </ul>

          <h2>Cost</h2>
          <ul>
          <li>The audit by hand: free, about twenty minutes.</li>
          <li>Subscription Tracker: free, with no limits.</li>
          </ul>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
