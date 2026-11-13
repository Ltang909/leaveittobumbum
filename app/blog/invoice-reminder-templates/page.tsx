import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";

export const metadata: Metadata = {
  alternates: { canonical: "/blog/invoice-reminder-templates/" },
  openGraph: { url: "/blog/invoice-reminder-templates/" },
  title: "Invoice Reminder Templates for Every Stage of Late | Leave It to Bum Bum",
  description: "Copy-ready invoice reminder templates for each stage: the friendly heads-up before the due date, the gentle nudge, the firm follow-up, and the final notice.",
};

const SLUG = "invoice-reminder-templates";
const HEADLINE = "Invoice Reminder Templates for Every Stage of Late";
const DESC = "Copy-ready invoice reminder templates for each stage: the friendly heads-up before the due date, the gentle nudge, the firm follow-up, and the final notice.";

const postingLd = {
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": HEADLINE,
  "description": DESC,
  "datePublished": "2026-11-02",
  "dateModified": "2026-11-12",
  "author": { "@type": "Organization", "name": "LeaveItToBumBum", "url": "https://leaveittobumbum.com/" },
  "publisher": {
    "@type": "Organization",
    "name": "LeaveItToBumBum",
    "url": "https://leaveittobumbum.com/",
    "logo": { "@type": "ImageObject", "url": "https://leaveittobumbum.com/bum/favicon-cat.png" }
  },
  "mainEntityOfPage": { "@type": "WebPage", "@id": `https://leaveittobumbum.com/blog/${SLUG}/` }
};

const breadcrumbLd = {
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://leaveittobumbum.com/" },
    { "@type": "ListItem", "position": 2, "name": "Blog", "item": "https://leaveittobumbum.com/blog/" },
    { "@type": "ListItem", "position": 3, "name": HEADLINE, "item": `https://leaveittobumbum.com/blog/${SLUG}/` }
  ]
};

const faqLd = {
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    { "@type": "Question", "name": "When should I send the first invoice reminder?", "acceptedAnswer": { "@type": "Answer", "text": "A few days before the due date, as a friendly heads-up with the invoice attached. It reads as good admin rather than chasing, and it prevents a large share of late payments, because most invoices are late through inattention rather than intent." } },
    { "@type": "Question", "name": "How many reminders should I send before escalating?", "acceptedAnswer": { "@type": "Answer", "text": "Four stages cover most situations: a heads-up before the due date, a gentle nudge in the first week, a firm follow-up around two weeks, and a final notice at thirty days with a named consequence. Beyond that, the problem is no longer wording." } },
    { "@type": "Question", "name": "Should I mention late fees in a reminder?", "acceptedAnswer": { "@type": "Answer", "text": "Only if your contract or invoice terms stated them up front. Introducing a fee mid-chase damages trust and may not be enforceable. If late fees matter to you, put them in your terms on the next invoice instead of this email." } },
    { "@type": "Question", "name": "What should every invoice reminder include?", "acceptedAnswer": { "@type": "Answer", "text": "The invoice number, the amount, the original due date, how overdue it now is, a fresh copy of the invoice attached, and one clear next step. Vague reminders get postponed; specific ones get paid." } },
    { "@type": "Question", "name": "Can I automate invoice reminders?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. A tracker like Bum Bum's Invoice Reminder follows the sequence for you, sending or drafting each stage on schedule. Automation handles the remembering; you still approve anything past the firm stage before it goes out." } }
  ]
};

export default function InvoiceReminderTemplates() {
  return (
    <main>
      <SiteHeader />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(postingLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqLd) }} />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>Invoice Reminder Templates for Every Stage of Late</h1>
        <p className="lede">Updated November 12, 2026 · By the LeaveItToBumBum team</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <p>Most late invoices are not a money problem. They are an attention problem: your invoice is sitting in an inbox under forty other things. A good reminder sequence fixes that without damaging the relationship, as long as each message matches how late the payment actually is.</p>
          <p>Here are templates for the four stages. Swap in your details, keep the invoice number and amount in every message, and attach the invoice again each time. Making someone search for the original is how reminders go to die. If the original invoice still needs making, the <a href="/tools/invoice-generator/">Invoice Generator</a> builds one in your browser, so you always have a clean copy ready to attach.</p>
          <h2>Stage 1: The heads-up (a few days before the due date)</h2>
          <p><strong>Subject: Invoice [number] due [date]</strong></p>
          <p>Hi [name], a quick heads-up that invoice [number] for [amount] is due on [date]. I have attached a copy here so it is handy. Let me know if you need anything else from me to process it. Thanks, [your name]</p>
          <p>This message feels like admin because it is admin, and that is the point. A large share of late payments never happen because the client got this note while there was still time.</p>
          <h2>Stage 2: The gentle nudge (1 to 7 days overdue)</h2>
          <p><strong>Subject: Quick check on invoice [number]</strong></p>
          <p>Hi [name], just checking in on invoice [number] for [amount], which was due on [date]. It may already be on its way, in which case ignore this. If it is stuck somewhere, tell me what you need and I will send it over. Thanks, [your name]</p>
          <p>Assume a mistake before you assume intent. The tone here buys you goodwill you will want if a later stage turns serious.</p>
          <h2>Stage 3: The firm follow-up (around 14 days overdue)</h2>
          <p><strong>Subject: Invoice [number] is now 14 days overdue</strong></p>
          <p>Hi [name], invoice [number] for [amount] was due on [date] and is now two weeks overdue. Please arrange payment this week, or let me know today if there is a problem I am not aware of. I have attached the invoice again for convenience. Regards, [your name]</p>
          <p>Two changes from stage 2: a specific timeframe (this week) and an opening for them to name a problem. Silence after this message is information.</p>
          <h2>Stage 4: The final notice (30 days and beyond)</h2>
          <p><strong>Subject: Final notice: invoice [number]</strong></p>
          <p>Hi [name], this is a final notice for invoice [number] for [amount], due on [date]. If payment is not received by [specific date], I will need to pause work on [project or account] and pass this to collections. If something has gone wrong, call me at [number] and we will sort it out directly. Regards, [your name]</p>
          <p>Only send consequences you will actually carry out. A pause on work is usually consequence enough, and it is honest.</p>
          <h2>Sending these without living in your inbox</h2>
          <p>The templates are the easy half. The hard half is remembering to send stage 2 on day three when you are busy doing the work itself. The <a href="/tools/invoice-chaser/">Invoice Reminder</a> tool from Leave It to Bum Bum keeps the sequence for you: it tracks who owes what, writes the reminder in the right tone for how late they are, and keeps nudging on schedule so you do not have to hold it in your head.</p>
          <h2>Frequently asked questions</h2>
          <h3>When should I send the first invoice reminder?</h3>
          <p>A few days before the due date, as a friendly heads-up with the invoice attached. It reads as good admin rather than chasing, and it prevents a large share of late payments, because most invoices are late through inattention rather than intent.</p>
          <h3>How many reminders should I send before escalating?</h3>
          <p>Four stages cover most situations: a heads-up before the due date, a gentle nudge in the first week, a firm follow-up around two weeks, and a final notice at thirty days with a named consequence. Beyond that, the problem is no longer wording.</p>
          <h3>Should I mention late fees in a reminder?</h3>
          <p>Only if your contract or invoice terms stated them up front. Introducing a fee mid-chase damages trust and may not be enforceable. If late fees matter to you, put them in your terms on the next invoice instead of this email.</p>
          <h3>What should every invoice reminder include?</h3>
          <p>The invoice number, the amount, the original due date, how overdue it now is, a fresh copy of the invoice attached, and one clear next step. Vague reminders get postponed; specific ones get paid.</p>
          <h3>Can I automate invoice reminders?</h3>
          <p>Yes. A tracker like Bum Bum's Invoice Reminder follows the sequence for you, sending or drafting each stage on schedule. Automation handles the remembering; you still approve anything past the firm stage before it goes out.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
