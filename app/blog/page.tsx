import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "Blog | Leave It to Bum Bum",
  description: "Comparisons and guides from the Leave It to Bum Bum team: free CRM software, small business software and business account software, compared side by side.",
};

const posts = [
  {
    href: "/blog/how-to-merge-a-pdf-online/",
    title: "How to Merge a PDF Online (Without Uploading Your Files)",
    description: "Merge several PDFs into one, in the order you choose, right in your browser. No uploads, no quality loss, nothing to install. Here is the whole process, plus the ordering tips that save a shuffle later.",
    date: "November 2, 2026",
  },
  {
    href: "/blog/how-to-write-an-sop/",
    title: "How to Write an SOP That People Actually Follow",
    description: "An SOP only works if someone else can follow it without asking you questions. Here is a simple way to write standard operating procedures for a small business, step by step.",
    date: "November 2, 2026",
  },
  {
    href: "/blog/invoice-reminder-templates/",
    title: "Invoice Reminder Templates for Every Stage of Late",
    description: "Copy-ready invoice reminder templates for each stage: the friendly heads-up before the due date, the gentle nudge, the firm follow-up, and the final notice.",
    date: "November 2, 2026",
  },
  {
    href: "/blog/how-to-track-brand-mentions-in-ai/",
    title: "How to Track When AI Mentions Your Brand",
    description: "Customers now ask ChatGPT and other AI assistants who to hire. Here is how to check what those answers say about your business, and how to keep checking without making it a second job.",
    date: "November 2, 2026",
  },
  {
    href: "/blog/how-to-make-an-audiogram/",
    title: "How to Make an Audiogram for Your Podcast Clips",
    description: "An audiogram turns your best podcast moment into a video people can actually stop scrolling for. Here is how to make one, from picking the clip to posting it.",
    date: "November 2, 2026",
  },
  {
    href: "/blog/how-to-turn-voice-notes-into-content/",
    title: "How to Turn Voice Notes Into Posts and Content",
    description: "You talk faster than you type, and your best ideas show up away from the keyboard. Here is a simple workflow for turning voice notes into posts, newsletters, and scripts.",
    date: "November 2, 2026",
  },
  {
    href: "/blog/how-to-trim-a-video-online/",
    title: "How to Trim a Video Online (No Editor Needed)",
    description: "Most video edits are just cutting the beginning, the end, or one bad section. Here is how to trim a video in your browser, plus the small details that make the cut look clean.",
    date: "November 2, 2026",
  },
  {
    href: "/blog/honeybook-alternatives-2026/",
    title: "5 Best HoneyBook Alternatives in 2026 for Solopreneurs",
    description: "Looking for a HoneyBook alternative after the price increases? Compare five options for invoicing, contracts, and client follow-ups, including simpler tools for freelancers and solopreneurs.",
    date: "November 2, 2026",
  },
  {
    href: "/blog/indy-alternatives-2026/",
    title: "5 Best Indy Alternatives in 2026 for Freelancers",
    description: "Looking for an Indy alternative after the price increases? Compare five options for proposals, invoicing, and follow-ups, including simpler tools that do one job well.",
    date: "November 2, 2026",
  },
  {
    href: "/blog/wave-alternatives-2026/",
    title: "5 Best Wave Alternatives in 2026 for Freelancers",
    description: "Looking for a Wave alternative after features moved behind the Pro paywall? Compare five options for invoicing, accounting, and chasing late payments.",
    date: "November 2, 2026",
  },
  {
    href: "/blog/dubsado-alternatives-2026/",
    title: "5 Best Dubsado Alternatives in 2026 (Simpler Client Follow-Ups)",
    description: "Looking for a Dubsado alternative without the setup marathon? Compare five options for client follow-ups, contracts, and invoicing, including tools that run in minutes.",
    date: "November 2, 2026",
  },
  {
    href: "/blog/capcut-alternatives-2026/",
    title: "5 Best CapCut Alternatives in 2026 for Quick Video Edits",
    description: "Looking for a CapCut alternative after the price increases and paywalls? Compare five options for trimming, converting, and editing short videos in 2026.",
    date: "November 2, 2026",
  },
  {
    href: "/blog/descript-alternatives-2026/",
    title: "5 Best Descript Alternatives in 2026 (Free & Simple Options)",
    description: "Explore five Descript alternatives in 2026 for screen recording, video editing, and content creation, including simpler tools for everyday recordings.",
    date: "October 30, 2026",
  },
  {
    href: "/blog/rocket-money-alternatives-2026/",
    title: "5 Best Rocket Money Alternatives in 2026 to Track Subscriptions",
    description: "Looking for a Rocket Money alternative? Compare five subscription trackers and budgeting apps, including simple options for managing recurring payments.",
    date: "October 30, 2026",
  },
  {
    href: "/blog/hubspot-crm-alternatives-2026/",
    title: "5 Best HubSpot CRM Alternatives in 2026 for Small Businesses",
    description: "Looking for a simpler HubSpot CRM alternative? Compare five tools for contact management, client follow-ups, and small business sales.",
    date: "October 30, 2026",
  },
  {
    href: "/blog/otter-ai-alternatives-2026/",
    title: "5 Best Otter.ai Alternatives in 2026 (Including Free Options)",
    description: "Need a simpler alternative to Otter.ai? Compare five transcription and voice note tools for meetings, personal notes, and everyday ideas.",
    date: "October 30, 2026",
  },
  {
    href: "/blog/loom-alternatives-2026/",
    title: "5 Best Loom Alternatives in 2026 (Free & Paid)",
    description: "Looking for a Loom alternative? Compare five screen recording tools for 2026, including free options for freelancers, small businesses, and teams.",
    date: "October 30, 2026",
  },
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
