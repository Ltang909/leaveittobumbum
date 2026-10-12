import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "How to track job applications (a system that survives 50 applications) | Leave It to Bum Bum",
  description: "Spreadsheets break down around 30 applications. The columns that matter, a follow-up cadence that keeps you top of mind, and a 15-minute weekly review that keeps nothing stale.",
};

export default function TrackJobApplicationsGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">Guide</p>
        <h1>How to track job applications (a system that survives 50 applications)</h1>
        <p className="lede">Ten applications fit in your head. Thirty live in a spreadsheet. Fifty need a system, one that tells you who to follow up with before the trail goes cold.</p>
      </section>
      <section className="shell">
        <div className="prose">
          <p>You applied on a Tuesday. Or was it a Thursday? The recruiter's name was Sarah, or possibly Sara, and you were definitely going to follow up last week. Multiply that fog by forty applications and you have the standard job search: a spreadsheet you stopped trusting around week two.</p>
          <p>A tracker only helps if it stays current. Here is a system simple enough that you will actually keep it up, even when the applications pile past fifty.</p>

          <h2>Why the spreadsheet breaks down around 30 applications</h2>
          <p>The first twenty rows feel fine. Then the cracks show up. You add a column for the recruiter, then a column for the referral, then a colour code only you understand, and only on a good day. Rows stop matching reality because updating the sheet feels like a second job search running alongside the first one.</p>
          <p>The deeper problem is that a spreadsheet just sits there. It never taps you on the shoulder. A follow-up that was due on Monday looks exactly like one due next Friday, so both quietly slip. The tool is not the failure. The missing piece is a system that tells you what needs attention today.</p>

          <h2>The columns that actually matter</h2>
          <p>Six columns carry the whole system. Everything else is decoration you will abandon by week three.</p>
          <ul>
          <li><strong>Company.</strong> The employer, spelled the way they spell it.</li>
          <li><strong>Role.</strong> The exact job title from the posting. Postings vanish, and the title helps you find your notes later.</li>
          <li><strong>Date applied.</strong> The day you sent it. Every follow-up decision hangs off this date.</li>
          <li><strong>Contact.</strong> A name beats an inbox. Recruiter, hiring manager, or the friend who referred you.</li>
          <li><strong>Next step.</strong> One action, written as a verb. Send portfolio. Nudge Ana. Prep for Thursday's call.</li>
          <li><strong>Follow-up date.</strong> The day that next step comes due. This is the column that runs your mornings.</li>
          </ul>
          <p>Notice what is missing: salary guesses, excitement ratings, a tab for companies you might apply to someday. Someday lists belong in a notebook. The tracker is for live applications with real next steps.</p>

          <h2>The follow-up cadence: when to nudge, and what to say</h2>
          <p>Most applications deserve two nudges, timed like this:</p>
          <ul>
          <li><strong>Day 7 after applying.</strong> A short check-in if you have heard nothing.</li>
          <li><strong>Day 14.</strong> One more nudge, with something useful added: a relevant project, a link, a line about why this role.</li>
          <li><strong>After any interview.</strong> A thank-you the same day, then a check-in one week later if the timeline they gave you has passed.</li>
          </ul>
          <p>Keep the message tiny. Here is one that works:</p>
          <p>"Hi Ana, I applied for the Growth Marketing Manager role on May 4 and I am still very keen. I led paid acquisition at my last company and grew signups 40% in a year, which looks close to what your posting describes. Happy to share more detail if helpful. Thanks, Leon"</p>
          <p>Three things make it work. It names the role and the date, so Ana can find you in seconds. It offers one proof point instead of a life story. It ends with an easy yes. After the day 14 nudge, let it rest. Two nudges show persistence. Five show a pattern recruiters warn each other about.</p>

          <h2>The 15-minute weekly review</h2>
          <p>Pick one slot each week, Monday morning works well, and run the same pass every time. Sort by follow-up date. Anything overdue gets nudged, rescheduled, or marked closed. Dead applications get archived the same day you learn they are dead, not three weeks later. Then scan the coming week's dates and move anything you already know will slip.</p>
          <p>Fifteen minutes keeps the whole board honest. Skip a week and you will spend forty minutes rebuilding your memory instead of your search.</p>

          <h2>When a list is enough, and when a tracker earns its keep</h2>
          <p>If you are applying to five roles at companies you know well, a notebook page is fine. The tracker earns its keep the moment you cannot hold every application's next step in your head. For most people that moment arrives around application fifteen, well before the spreadsheet meltdown at thirty. The other sign: you are applying through referrals and recruiters at the same time, and the follow-ups start colliding.</p>

          <h2>The easier way</h2>
          <p>The <a href="/tools/corporate-bum-bum/">Job Application Tracker</a> keeps those six columns for you and does the shoulder-tapping part. Every morning it tells you who to follow up with today and helps you draft what to say. You run the job search. It remembers the dates.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
