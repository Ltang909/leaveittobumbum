import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "Blog | Leave It to Bum Bum",
  description: "Comparisons and guides from the Leave It to Bum Bum team: free CRM software, small business software and business account software, compared side by side.",
};

const posts = [
  {
    href: "/blog/how-to-add-an-email-signature/",
    title: "How to Add an Email Signature in Gmail, Outlook, and Apple Mail",
    description: "Add a professional email signature in minutes: what to include, how to build one free, the exact steps for Gmail, Outlook, and Apple Mail, and fixes for images that do not show.",
    date: "October 30, 2026",
  },
  {
    href: "/blog/best-free-client-follow-up-tracker/",
    title: "Best Free Client Follow-Up Trackers in 2026: 9 Tools Compared",
    description: "Compare 9 free client follow-up trackers for freelancers and solopreneurs: LeaveItToBumBum Purrsuit, ClientPlot, Bigin, HubSpot, Zoho CRM, Capsule, Trello, FWD Tools and Google Sheets, by free plan, limits and best fit.",
    date: "October 29, 2026",
  },
  {
    href: "/blog/how-to-track-your-daily-work-progress/",
    title: "How to Track Your Daily Work Progress (In Just 5 Minutes)",
    description: "A daily work log takes five minutes: what to track, the minute-by-minute routine, how to review it weekly and monthly, and how Day Log scores yesterday's priorities for you.",
    date: "October 28, 2026",
  },
  {
    href: "/blog/best-free-crm-small-business/",
    title: "12 Best Free CRM Software for Small Businesses in 2026 (Real Free Plans)",
    description: "Compare 12 free CRM tools for small businesses by user limits, contact caps, features and upgrade traps. Includes HubSpot, Zoho, Attio and LeaveItToBumBum.",
    date: "October 26, 2026",
  },
  {
    href: "/blog/best-small-business-software/",
    title: "12 Best Small Business Software Tools in 2026 (Free and Paid)",
    description: "The 12 best small business software tools for everyday work: invoicing, CRM, email, projects, design, automation and selling. Find the right tool without subscription bloat.",
    date: "October 26, 2026",
  },
  {
    href: "/blog/best-business-account-software/",
    title: "12 Best Business Account Software for Small Businesses in 2026",
    description: "Compare 12 small-business accounting and money-management tools, including QuickBooks, Xero, Wave, Zoho Books and free lightweight tools from LeaveItToBumBum.",
    date: "October 26, 2026",
  },
];

export default function BlogIndex() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>Blog</h1>
        <p className="lede">Comparisons and guides from the Leave It to Bum Bum team.</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          {posts.map((p) => (
            <article key={p.href}>
              <h2><a href={p.href}>{p.title}</a></h2>
              <p>{p.description}</p>
              <p>{p.date}</p>
            </article>
          ))}
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
