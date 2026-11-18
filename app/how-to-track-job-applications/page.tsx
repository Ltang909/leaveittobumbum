import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";
import ToolCallout from "../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/how-to-track-job-applications/" },
  openGraph: { url: "/how-to-track-job-applications/" },
  title: "How to track job applications (a system that survives 50 applications) | Leave It to Bum Bum",
  description: "Spreadsheets break down around 30 applications. The columns that matter, a follow-up cadence that keeps you top of mind, and a 15-minute weekly review that keeps nothing stale.",
};

export default function TrackJobApplicationsGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell blog-head">
        <p className="kicker">Guide</p>
        <h1>How to track job applications (a system that survives 50 applications)</h1>
        <p className="lede">Ten applications fit in your head. Thirty live in a spreadsheet. Fifty need a system, one that tells you who to follow up with before the trail goes cold.</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="Job Application Tracker"
            href="/tools/corporate-bum-bum/"
            heading="Just want the follow-up dates remembered?"
            blurb="Job Application Tracker keeps your applications in one place and tells you each morning who to follow up with. Free, with no limits."
            cta="Open Job Application Tracker"
          />

          <h2>What to do</h2>

          <h3>Track six columns</h3>
          <p>Six columns carry the whole system. Everything else is decoration you will abandon by week three.</p>
          <ul>
          <li><strong>Company.</strong> The employer, spelled the way they spell it.</li>
          <li><strong>Role.</strong> The exact job title from the posting. Postings vanish, and the title helps you find your notes later.</li>
          <li><strong>Date applied.</strong> The day you sent it. Every follow-up decision hangs off this date.</li>
          <li><strong>Contact.</strong> A name beats an inbox. Recruiter, hiring manager, or the friend who referred you.</li>
          <li><strong>Next step.</strong> One action, written as a verb. Send portfolio. Nudge Ana. Prep for Thursday's call.</li>
          <li><strong>Follow-up date.</strong> The day that next step comes due. This is the column that runs your mornings.</li>
          </ul>

          <h3>Follow the follow-up cadence</h3>
          <p>Most applications deserve two nudges, timed like this:</p>
          <ul>
          <li><strong>Day 7 after applying.</strong> A short check-in if you have heard nothing.</li>
          <li><strong>Day 14.</strong> One more nudge, with something useful added: a relevant project, a link, a line about why this role.</li>
          <li><strong>After any interview.</strong> A thank-you the same day, then a check-in one week later if the timeline they gave you has passed.</li>
          </ul>
          <p>Keep the message tiny. Here is one that works:</p>
          <p>"Hi Ana, I applied for the Growth Marketing Manager role on May 4 and I am still very keen. I led paid acquisition at my last company and grew signups 40% in a year, which looks close to what your posting describes. Happy to share more detail if helpful. Thanks, Leon"</p>
          <p>After the day 14 nudge, let it rest. Two nudges is a reasonable limit for one application. More than that starts to work against you with recruiters.</p>

          <h3>Run the 15-minute weekly review</h3>
          <p>Pick one slot each week, Monday morning works well, and run the same pass every time. Sort by follow-up date. Anything overdue gets nudged, rescheduled, or marked closed. Dead applications get archived the same day you learn they are dead, not three weeks later. Then scan the coming week's dates and move anything you already know will slip.</p>

          <h2>Cost</h2>
          <ul>
          <li>A notebook or spreadsheet: free.</li>
          <li>Job Application Tracker: free, with no limits.</li>
          </ul>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
