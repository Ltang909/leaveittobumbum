import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "How to find subscriptions you forgot about (and cancel them) | Leave It to Bum Bum",
  description: "Forgotten subscriptions hide in app stores, PayPal, and old email receipts. A 20-minute audit finds them all, plus the calendar trick that stops free trials from billing you.",
};

export default function FindSubscriptionsGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">Guide</p>
        <h1>How to find subscriptions you forgot about (and cancel them)</h1>
        <p className="lede">The average forgotten subscription is small. The pile of them is not. Twenty minutes of digging finds every recurring charge you are paying for and getting nothing from.</p>
      </section>
      <section className="shell">
        <div className="prose">
          <h2>How to find subscriptions you forgot about (and cancel them)</h2>
          <p>Somewhere in your accounts there is a meditation app from 2023 quietly taking $13.99 a month. You are not careless. Sign-ups take ten seconds and life moves fast, so charges pile up in the background and blend into your statements. The good news: a focused twenty minute audit finds nearly all of them, and most people free up real money in the first pass. Here is where to look and what to do with what you find.</p>
          <h2>Where forgotten subscriptions hide</h2>
          <ul>
          <li><strong>App stores.</strong> Anything you subscribed to inside an app on your phone lives in your Apple or Google account, often under a name you barely recognize. Puzzle games and photo editors are the classic offenders.</li>
          <li><strong>PayPal.</strong> Plenty of services bill through PayPal pre-approved payments. They never show a card charge, so they slip past a statement scan.</li>
          <li><strong>Card and bank statements.</strong> Charges hide behind odd merchant names. "SP *XYZ MEDIA" might be the streaming add-on you trialled last winter.</li>
          <li><strong>Your email receipts.</strong> Search your inbox for "receipt", "renewal", and "your subscription". Every paid service writes to you at least once a year, usually right after taking your money.</li>
          <li><strong>Free trials that converted.</strong> The seven day trial from two phones ago may still be billing you monthly.</li>
          </ul>
          <h2>The 20-minute audit, step by step</h2>
          <ul>
          <li><strong>Minutes 1 to 5:</strong> Open your phone's subscription list. On iPhone, go to Settings, tap your name, then Subscriptions. On Android, open the Play Store, tap your profile, then Payments and subscriptions. Screenshot anything you do not instantly recognize.</li>
          <li><strong>Minutes 5 to 10:</strong> Log into PayPal and open Settings, then Payments, then Manage automatic payments. Cancel anything stale right there.</li>
          <li><strong>Minutes 10 to 15:</strong> Scan three months of card and bank statements. Highlight every repeating charge, even the tiny ones. Small charges are the longest running ones because nobody notices $4.99.</li>
          <li><strong>Minutes 15 to 20:</strong> Search your email for "renewal" and "receipt" and cross-check against your list. Put every subscription you find into one list with its price and next renewal date. That list is your whole picture, probably for the first time.</li>
          </ul>
          <h2>Free trials and the calendar trick</h2>
          <p>Trials get you because the charge lands weeks after you stopped caring about the app. The fix takes ten seconds at sign-up time: the moment you start a trial, add a calendar event for two days before it ends, titled "Cancel the trial?" Future you gets a nudge while there is still time to act. Do the same for annual renewals on anything pricey. A $180 charge you see coming is a decision. The same charge out of nowhere is just a leak.</p>
          <h2>Cancel, pause, or downgrade: which stops the money?</h2>
          <ul>
          <li><strong>Cancel</strong> ends the billing. On most services you keep access until the end of the paid period, so cancel the day you decide, not the day before renewal when you will forget.</li>
          <li><strong>Pause</strong> stops charges for a set stretch, then restarts on its own. Handy for a service you genuinely return to, like a fitness app during an injury. Set a reminder for the restart date, because the restart will not remind you.</li>
          <li><strong>Downgrade</strong> lowers the price but keeps the meter running. Pick this when you still use the thing and only the fancy tier was the mistake.</li>
          <li><strong>Watch the wording.</strong> Some services label the buttons to trick you, and "confirm cancellation" hides two screens deep. Click through until you see a clear "cancelled" message, and keep the confirmation email.</li>
          </ul>
          <h2>Staying ahead after the audit</h2>
          <p>An audit cleans the slate once. Renewal reminders keep it clean. Put every renewal date in one place and get a nudge before each charge, while cancelling is still one click and a shrug. Review the list once a quarter, ideally with a coffee in hand, and question anything you have not opened in a month. Ten quiet minutes four times a year beats one painful audit every few years.</p>
          <h2>The easier way</h2>
          <p>Keeping that list current by hand is exactly the kind of chore that slips by February. <a href="/tools/cutline/">Subscription Tracker</a> keeps every subscription in one place and nudges you before each renewal, so free trials stop billing you in secret. Add them once, and the reminders handle the rest.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
