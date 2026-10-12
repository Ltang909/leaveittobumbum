import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "How to write an invoice reminder email (with templates) | Leave It to Bum Bum",
  description: "When to send an invoice reminder, what to say at 7, 14, and 30 days late, and four copy-paste email templates that get you paid without burning the relationship.",
};

export default function InvoiceReminderEmailGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">Guide</p>
        <h1>How to write an invoice reminder email (with templates)</h1>
        <p className="lede">Chasing late payments is nobody's favorite job. A steady ladder of short, polite reminders gets most invoices paid without a single awkward phone call.</p>
      </section>
      <section className="shell">
        <div className="prose">
          <h2>How to write an invoice reminder email (with templates)</h2>
          <p>You did the work. You sent the invoice. Now the due date drifts past and you get to play debt collector in your own inbox. Chasing late payment is nobody's favourite job, but a short, polite, well timed email gets most invoices paid without any drama. This guide gives you four copy-paste templates and a schedule for sending them, so you never have to stare at a blank email wondering how firm to sound.</p>
          <h2>When to send each reminder</h2>
          <p>Timing does half the work for you. Send a gentle note before the due date, a clear one on the day, and firmer ones as the days stack up.</p>
          <ul>
          <li><strong>Three days before the due date:</strong> the friendly heads-up. Many clients pay on this one alone.</li>
          <li><strong>On the due date:</strong> a short, factual note that payment is due today.</li>
          <li><strong>Seven days late:</strong> direct and specific. Name the number of days overdue.</li>
          <li><strong>Fourteen days late:</strong> firmer. Mention next steps, such as pausing work.</li>
          <li><strong>Thirty days late:</strong> the final notice. Keep it calm, keep it short, and say exactly what happens next.</li>
          </ul>
          <h2>Email 1: the friendly nudge (before the due date)</h2>
          <p><strong>Subject:</strong> Quick heads-up: invoice #1042 is due Friday</p>
          <p>Hi Sam,</p>
          <p>Just a quick heads-up that invoice #1042 for $1,450 is due this Friday, June 12. You can pay it here: [payment link].</p>
          <p>Thanks so much,</p>
          <p>Alex</p>
          <h2>Email 2: due today</h2>
          <p><strong>Subject:</strong> Invoice #1042 is due today</p>
          <p>Hi Sam,</p>
          <p>A quick note that invoice #1042 for $1,450 is due today. The payment link is here: [payment link]. If you have already sent it, thank you, and please ignore this note.</p>
          <p>Best,</p>
          <p>Alex</p>
          <h2>Email 3: seven days late</h2>
          <p><strong>Subject:</strong> Invoice #1042 is 7 days overdue</p>
          <p>Hi Sam,</p>
          <p>Invoice #1042 for $1,450 was due on June 12 and is now 7 days overdue. Could you let me know when payment will go out? You can pay here: [payment link]. If something looks off on the invoice, tell me and I will sort it today.</p>
          <p>Thanks,</p>
          <p>Alex</p>
          <h2>Email 4: the final notice (thirty days late)</h2>
          <p><strong>Subject:</strong> Final notice: invoice #1042, 30 days overdue</p>
          <p>Hi Sam,</p>
          <p>Invoice #1042 for $1,450 is now 30 days overdue, and I have not heard back on my earlier notes. Please pay by Friday using this link: [payment link]. After Friday I will pause work on your account and add the late fee set out in our agreement. If payment is already on its way, a quick reply with the date keeps us both sorted.</p>
          <p>Regards,</p>
          <p>Alex</p>
          <h2>What every reminder must include</h2>
          <ul>
          <li><strong>The invoice number.</strong> Accounts teams file by number, and your email gets forwarded around until it lands with the person who can pay. Give them the reference they need.</li>
          <li><strong>The exact amount.</strong> Write the full figure, not "the outstanding balance."</li>
          <li><strong>The due date.</strong> Say when it was due, and how many days late it now is once it slips.</li>
          <li><strong>A payment link.</strong> One click should finish the job. Every extra step ("log into the portal, then search...") costs you days.</li>
          <li><strong>One clear ask.</strong> Pay by this date, or reply and tell me what is happening. Pick one.</li>
          </ul>
          <h2>Mistakes that quietly delay payment</h2>
          <ul>
          <li><strong>Sending it to the wrong person.</strong> Your friendly contact often cannot release money. Ask early, "Who should invoices go to on your side?" and send reminders to that person, with your contact copied in.</li>
          <li><strong>Burying the payment link.</strong> If paying takes effort, your invoice joins a pile. Put the link in the first few lines, every single time.</li>
          <li><strong>Apologizing for asking.</strong> "Sorry to bother you, just wondering if maybe..." reads as optional. You delivered the work, so a plain, friendly request is the right tone.</li>
          <li><strong>Waiting a month to send the first note.</strong> By then your invoice is buried under forty others. The three-day heads-up feels early the first time you send it. Send it anyway.</li>
          <li><strong>Getting heated at fourteen days.</strong> Firm beats angry. State the facts, name the next step, and leave the door open for a fast reply.</li>
          </ul>
          <h2>The easier way</h2>
          <p>Writing these from scratch for every late invoice eats real time, and remembering who got which email is its own headache. <a href="/tools/invoice-chaser/">Invoice Reminder</a> writes the chase email for you, sends it as your billing assistant, and learns which tone gets each client to pay. You approve the words, it does the awkward part.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
