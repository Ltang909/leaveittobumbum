import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "How to tailor your resume to a job description | Leave It to Bum Bum",
  description: "How recruiters and screening software scan your resume, how to pull the skills a posting repeats, and the two-column gap check that shows exactly what to fix. Honest tailoring only.",
};

export default function TailorResumeGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">Guide</p>
        <h1>How to tailor your resume to a job description</h1>
        <p className="lede">Your resume is probably not weak. It is probably answering a different job posting. Tailoring means answering the one in front of you, with experience you actually have.</p>
      </section>
      <section className="shell">
        <div className="prose">
          <p>Your resume is good. It is also generic, because you wrote it for every job at once, which means it lands perfectly for none of them. Tailoring fixes that. It takes about twenty minutes per application once you have a method, and the method is mostly reading the posting more carefully than the last fifty applicants did.</p>

          <h2>How recruiters and screening software actually scan a resume</h2>
          <p>Before a person reads your resume, software often scores it. The screening system looks for the words the posting uses: job titles, skills, tools, certifications. If the posting says "email lifecycle marketing" three times and your resume only says "newsletters," the software may never pass you along, even though you did the work.</p>
          <p>Then a recruiter gives it roughly six seconds. They scan your most recent title, your most recent company, and the first line under each role. They are answering one question: does this person already do a version of this job? Tailoring is how you make that six-second answer a yes, honestly.</p>

          <h2>Pull out the skills the posting repeats, and rank them</h2>
          <p>Open the posting and a blank page. Copy down every skill, tool, and responsibility it names. Then count repeats. A skill mentioned in the title, the requirements, and the day-to-day section is the job's centre of gravity. A skill mentioned once in a long wish list is decoration.</p>
          <p>Rank what you find into three piles:</p>
          <ul>
          <li><strong>Core skills.</strong> Repeated, and usually sitting in the first half of the posting. These must appear in your resume, in the posting's own words, wherever you have genuinely done them.</li>
          <li><strong>Supporting skills.</strong> Mentioned once or twice. Worth a bullet if you have the experience.</li>
          <li><strong>Wish list.</strong> The "bonus points" paragraph. Nice if you have it, fine if you skip it.</li>
          </ul>
          <p>Most candidates do this backwards. They match the easy wish-list words and miss the core skill hiding in plain sight.</p>

          <h2>Tailor honestly: surface what you have, invent nothing</h2>
          <p>This part has a hard line, and it matters. Tailoring means reordering, rewording, and promoting experience you truly have. It never means inventing experience you lack. Recruiters verify. Reference calls, work samples, and a second interview question will find a fabricated skill within the hour, and a candidate caught padding one bullet loses trust in every other bullet too.</p>
          <p>The honest version is nearly always available. You did not "manage a team"? You trained two new hires and reviewed their work weekly, which is real supervision experience, described accurately. You never owned a budget? You recommended spend changes that your manager approved, and you can say exactly that. Precise language about real work beats grand language about imaginary work, every time.</p>
          <p>And when a core skill is genuinely missing, leave the gap visible. Plenty of hires happen at 70% of the posting. A clean gap reads as self-awareness. A fake skill reads as a warning.</p>

          <h2>The two-column gap check</h2>
          <p>Draw two columns. On the left, the posting's core and supporting skills, in its words. On the right, the exact line of your resume that proves each one. Work down the list and mark each row one of three ways:</p>
          <ul>
          <li><strong>Covered.</strong> Your resume says it, using close to the posting's wording.</li>
          <li><strong>Buried.</strong> You have done it, but it is hiding in old wording or three roles back. Rewrite or promote it.</li>
          <li><strong>Missing.</strong> You have not done it. Leave it blank and move on.</li>
          </ul>
          <p>Most resumes come out of this check with two or three buried skills and one honest gap. Twenty minutes of rewriting the buried ones is the highest-value editing in your whole search.</p>

          <h2>Before and after: one bullet, rewritten</h2>
          <p>Say the posting asks for "experience running lifecycle email campaigns that improve retention."</p>
          <p><strong>Before:</strong> "Responsible for email marketing and newsletters."</p>
          <p><strong>After:</strong> "Built a 6-part lifecycle email series for new customers; repeat purchase rate rose 18% in two quarters."</p>
          <p>Same person, same job, same truth. The second version borrows the posting's vocabulary, adds the scale, and attaches a result. That is the entire craft.</p>

          <h2>The easier way</h2>
          <p>The <a href="/tools/gap-scout/">Resume Gap Checker</a> runs the two-column check for you. Paste your resume and up to five job descriptions, and it shows you the skills the postings keep asking for that your resume never mentions. You still write the honest bullets. It just finds the gaps in seconds instead of an evening.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
