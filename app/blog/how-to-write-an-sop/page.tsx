import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";

export const metadata: Metadata = {
  title: "How to Write an SOP That People Actually Follow | Leave It to Bum Bum",
  description: "An SOP only works if someone else can follow it without asking you questions. Here is a simple way to write standard operating procedures for a small business, step by step.",
};

const SLUG = "how-to-write-an-sop";
const HEADLINE = "How to Write an SOP That People Actually Follow";
const DESC = "An SOP only works if someone else can follow it without asking you questions. Here is a simple way to write standard operating procedures for a small business, step by step.";

const postingLd = {
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": HEADLINE,
  "description": DESC,
  "datePublished": "2026-11-02",
  "dateModified": "2026-11-02",
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
    { "@type": "Question", "name": "What is an SOP in a small business?", "acceptedAnswer": { "@type": "Answer", "text": "A standard operating procedure is a short, step-by-step document for one repeatable job, written so someone else can do the job the same way without asking questions. In a small business it is how the owner stops being the only person who knows how things work." } },
    { "@type": "Question", "name": "How long should an SOP be?", "acceptedAnswer": { "@type": "Answer", "text": "As short as the job allows. Most small-business procedures fit on one or two pages: a one-sentence purpose, numbered steps, and the details that trip people up. If it runs much longer, it is probably covering more than one job and should be split." } },
    { "@type": "Question", "name": "What is the difference between an SOP and a checklist?", "acceptedAnswer": { "@type": "Answer", "text": "A checklist is the short list you tick off while doing the work. An SOP explains how and why each step is done, including decision rules and warnings. Many teams keep both: the SOP for learning and reference, the checklist for the daily run." } },
    { "@type": "Question", "name": "How often should SOPs be updated?", "acceptedAnswer": { "@type": "Answer", "text": "Whenever the process changes, and reviewed at least once or twice a year. The fastest way to keep them honest is to fix them during real use: any time someone gets stuck or improvises, that moment is an edit waiting to happen." } },
    { "@type": "Question", "name": "Can AI write an SOP for me?", "acceptedAnswer": { "@type": "Answer", "text": "It can draft one. Tools like the SOP Generator turn your description of a job into a structured draft, which saves the blank-page hour. The draft still needs your judgment: the decision rules, timings, and common mistakes are the parts only you know." } }
  ]
};

export default function HowToWriteAnSop() {
  return (
    <main>
      <SiteHeader />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(postingLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqLd) }} />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>How to Write an SOP That People Actually Follow</h1>
        <p className="lede">Updated November 2, 2026 · By the LeaveItToBumBum team</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <p>Most SOPs fail the same way: they are written like essays. Long paragraphs, background nobody needs, and the actual steps buried somewhere in the middle. The person reading it gives up and asks you instead, which is the exact thing the SOP was supposed to prevent.</p>
          <p>A good standard operating procedure is closer to a recipe than a report. Do this, then this, then check this. Here is how to write one that survives contact with a real workday.</p>
          <h2>Step 1: Pick one process, and only one</h2>
          <p>An SOP covers a single repeatable job: onboarding a client, sending an invoice, closing the books each month. If the document tries to cover three jobs, it will be vague about all of them. Write down the job in one sentence at the top, along with the result that tells you it was done right.</p>
          <h2>Step 2: List the steps in the order they happen</h2>
          <p>Walk the process yourself, slowly, and write each action as you take it. Use verbs: open, send, check, save. Number the steps. If a step has a decision in it (if the client has not replied, do X), write the branch down instead of leaving it to judgment.</p>
          <h2>Step 3: Add the details that trip people up</h2>
          <ul>
            <li>Where things live: file names, folders, links, logins</li>
            <li>The exact template, script, or example to copy</li>
            <li>Timings: when it starts, how long it should take, what is time-sensitive</li>
            <li>The two or three mistakes everyone makes the first time</li>
          </ul>
          <p>These details are the difference between a document and a procedure. They are also the parts that live only in your head right now, which is why writing them down feels slow. It is still faster than explaining them twelve times.</p>
          <h2>Step 4: Cut everything that is not a step</h2>
          <p>History, strategy, and motivation belong in a different document. Every sentence in an SOP should either be an action, a decision rule, or a warning. If a paragraph does none of those, delete it. Short SOPs get followed; long ones get skimmed.</p>
          <h2>Step 5: Draft it fast, then fix it in real use</h2>
          <p>Staring at a blank page is the slow way to start. The <a href="/tools/sop-ify/">SOP Generator</a> from Leave It to Bum Bum turns a rough description of the job into a structured first draft you can edit, which gets you to the useful part sooner: handing it to someone and watching where they get stuck. Every stuck point is an edit. Two or three rounds of that and the document is genuinely yours.</p>
          <h2>Step 6: Store it where the work happens</h2>
          <p>An SOP nobody can find is a diary entry. Keep procedures with the tools and files they reference, name them by the job (not by date or author), and update the same document instead of saving copies. When the process changes, change the SOP the same day, or it quietly becomes fiction.</p>
          <h2>Frequently asked questions</h2>
          <h3>What is an SOP in a small business?</h3>
          <p>A standard operating procedure is a short, step-by-step document for one repeatable job, written so someone else can do the job the same way without asking questions. In a small business it is how the owner stops being the only person who knows how things work.</p>
          <h3>How long should an SOP be?</h3>
          <p>As short as the job allows. Most small-business procedures fit on one or two pages: a one-sentence purpose, numbered steps, and the details that trip people up. If it runs much longer, it is probably covering more than one job and should be split.</p>
          <h3>What is the difference between an SOP and a checklist?</h3>
          <p>A checklist is the short list you tick off while doing the work. An SOP explains how and why each step is done, including decision rules and warnings. Many teams keep both: the SOP for learning and reference, the checklist for the daily run.</p>
          <h3>How often should SOPs be updated?</h3>
          <p>Whenever the process changes, and reviewed at least once or twice a year. The fastest way to keep them honest is to fix them during real use: any time someone gets stuck or improvises, that moment is an edit waiting to happen.</p>
          <h3>Can AI write an SOP for me?</h3>
          <p>It can draft one. Tools like the SOP Generator turn your description of a job into a structured draft, which saves the blank-page hour. The draft still needs your judgment: the decision rules, timings, and common mistakes are the parts only you know.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
