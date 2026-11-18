import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";
import ToolCallout from "../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/how-to-tailor-your-resume-to-a-job-description/" },
  openGraph: { url: "/how-to-tailor-your-resume-to-a-job-description/" },
  title: "How to tailor your resume to a job description | Leave It to Bum Bum",
  description: "How recruiters and screening software scan your resume, how to pull the skills a posting repeats, and the two-column gap check that shows exactly what to fix. Honest tailoring only.",
};

export default function TailorResumeGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell blog-head">
        <p className="kicker">Guide</p>
        <h1>How to tailor your resume to a job description</h1>
        <p className="lede">If your resume is not landing, the usual problem is fit: it was written for a different job posting. Tailoring means answering the one in front of you, with experience you actually have.</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="Resume Gap Checker"
            href="/tools/gap-scout/"
            heading="Just want the gaps in your resume found?"
            blurb="Resume Gap Checker compares your resume with the posting and shows the skills it keeps asking for that your resume never mentions. Free, with no limits."
            cta="Open Resume Gap Checker"
          />

          <h2>What to do</h2>

          <h3>Pull out the skills the posting repeats, and rank them</h3>
          <p>Open the posting and a blank page. Copy down every skill, tool, and responsibility it names. Then count repeats. A skill mentioned in the title, the requirements, and the day-to-day section is the job's centre of gravity. A skill mentioned once in a long wish list is decoration.</p>
          <p>Rank what you find into three piles:</p>
          <ul>
          <li><strong>Core skills.</strong> Repeated, and usually sitting in the first half of the posting. These must appear in your resume, in the posting's own words, wherever you have genuinely done them.</li>
          <li><strong>Supporting skills.</strong> Mentioned once or twice. Worth a bullet if you have the experience.</li>
          <li><strong>Wish list.</strong> The "bonus points" paragraph. Nice if you have it, fine if you skip it.</li>
          </ul>

          <h3>Run the two-column gap check</h3>
          <p>Draw two columns. On the left, the posting's core and supporting skills, in its words. On the right, the exact line of your resume that proves each one. Work down the list and mark each row one of three ways:</p>
          <ul>
          <li><strong>Covered.</strong> Your resume says it, using close to the posting's wording.</li>
          <li><strong>Buried.</strong> You have done it, but it is hiding in old wording or three roles back. Rewrite or promote it.</li>
          <li><strong>Missing.</strong> You have not done it. Leave it blank and move on.</li>
          </ul>
          <p>Most resumes come out of this check with two or three buried skills and one honest gap. Twenty minutes of rewriting the buried ones is the highest-value editing in your whole search.</p>

          <h3>Rewrite the buried bullets</h3>
          <p>Say the posting asks for "experience running lifecycle email campaigns that improve retention."</p>
          <p><strong>Before:</strong> "Responsible for email marketing and newsletters."</p>
          <p><strong>After:</strong> "Built a 6-part lifecycle email series for new customers; repeat purchase rate rose 18% in two quarters."</p>
          <p>Both bullets describe the same person doing the same job. The second version uses the posting's vocabulary, adds the scale of the work, and attaches a result. That is the whole method.</p>

          <h2>Cost</h2>
          <ul>
          <li>By hand: free, about twenty minutes per application.</li>
          <li>Resume Gap Checker: free, with no limits.</li>
          </ul>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
