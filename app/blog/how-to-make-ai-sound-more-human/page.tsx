import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";
import ToolCallout from "../../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/blog/how-to-make-ai-sound-more-human/" },
  openGraph: { url: "/blog/how-to-make-ai-sound-more-human/" },
  title: "How to Make AI Sound More Human | Leave It to Bum Bum",
  description: "AI writing gets spotted for specific, nameable habits: formulaic contrast writing, performative profundity, spatial metaphor inflation, and a filler vocabulary. What each one is, how to spot it, and the prompt instructions that fix it.",
};

const SLUG = "how-to-make-ai-sound-more-human";
const HEADLINE = "How to Make AI Sound More Human";
const DESC = "AI writing gets spotted for specific, nameable habits: formulaic contrast writing, performative profundity, spatial metaphor inflation, and a filler vocabulary. What each one is, how to spot it, and the prompt instructions that fix it.";

const postingLd = {
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": HEADLINE,
  "description": DESC,
  "datePublished": "2026-11-08",
  "dateModified": "2026-11-08",
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
    { "@type": "Question", "name": "What is formulaic contrast writing?", "acceptedAnswer": { "@type": "Answer", "text": "Formulaic contrast writing is a canned form of rhetorical antithesis, where a claim is framed against an opposite nobody stated: \"It's not X, it's Y\", \"X. Never Y.\", \"You're not doing X, you're doing Y\". AI models reach for it constantly because it sounds decisive. Readers spot it because the negated half is usually a straw claim, so the sentence decorates a point instead of making one." } },
    { "@type": "Question", "name": "What is performative profundity?", "acceptedAnswer": { "@type": "Answer", "text": "Performative profundity is a sentence shaped like wisdom that carries no information: aphorism-style closers and sweeping openers that would still work with any topic's nouns swapped in. The test is substitution. If the sentence survives unchanged for a bakery, a bank, and a software company, it says nothing about any of them." } },
    { "@type": "Question", "name": "What is spatial metaphor inflation?", "acceptedAnswer": { "@type": "Answer", "text": "Spatial metaphor inflation is the habit of describing abstract subjects with physical and spatial language: the landscape of an industry, the intersection of two fields, unlocking opportunities, an ecosystem of tools. A single metaphor can clarify. Inflation is when the metaphors arrive in every paragraph and the plain claim, what something does and what it costs, never shows up." } },
    { "@type": "Question", "name": "Can a prompt really change how AI writes?", "acceptedAnswer": { "@type": "Answer", "text": "Yes, for style. Current models follow explicit writing instructions closely when the instructions sit at the end of the prompt: banned phrases, sentence-length variety, direct statement over contrast formulas. A prompt cannot supply your facts, so the content still needs you. The style, though, is very fixable by instruction." } },
    { "@type": "Question", "name": "Will this fool AI detectors?", "acceptedAnswer": { "@type": "Answer", "text": "That is the wrong target. AI detectors are unreliable in both directions, flagging human writing and passing generated writing every day. Writing that follows the fixes in this article reads better to people, which is the result worth having. Detector scores are a side effect at best." } }
  ]
};

export default function HowToMakeAiSoundMoreHuman() {
  return (
    <main>
      <SiteHeader />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(postingLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqLd) }} />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>How to Make AI Sound More Human</h1>
        <p className="lede">Updated November 8, 2026 · By the LeaveItToBumBum team</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="Sound Human"
            href="/tools/sound-human/"
            heading="Just want the fixes ready to paste into your prompt?"
            blurb="Sound Human holds seven copy-ready prompt add-ons, one for each habit AI writing gets spotted for. Free, with no limits."
            cta="Open Sound Human"
          />

          <h2>What to do</h2>

          <h3>Add these instructions to the end of your prompt</h3>
          <p>Every fix for the habits in the questions below is an instruction, and instructions can ride along with your prompt. Put them at the end, after your request, because models weight the last instructions heavily. One starter block looks like this:</p>
          <ul>
            <li>State the point directly. Skip contrast formulas like &quot;It is not X, it is Y&quot;.</li>
            <li>Use concrete nouns and direct verbs. Say what something does, what it costs, and who it is for.</li>
            <li>Vary sentence length. Mix short sentences with longer ones.</li>
            <li>Do not use: delve, tapestry, testament to, game-changer, furthermore, moreover.</li>
          </ul>
          <p><a href="/tools/sound-human/">Sound Human</a> holds seven prompt add-ons, each one aimed at a habit in this article, with a combiner that stacks your picks onto your own prompt. Copy a block once, save it in your notes, and your future prompts start from human-sounding by default. Your facts still come from you. The habits are the part the instructions can carry.</p>

          <h2>Cost</h2>
          <ul>
          <li>Adding the instructions to your own prompts: free.</li>
          <li>Sound Human: free, with no limits.</li>
          </ul>

          <h2>FAQs</h2>
          <h3>What is formulaic contrast writing?</h3>
          <p>Formulaic contrast writing is a canned form of rhetorical antithesis, where a claim is framed against an opposite nobody stated: &quot;It&apos;s not X, it&apos;s Y&quot;, &quot;X. Never Y.&quot;, &quot;You&apos;re not doing X, you&apos;re doing Y&quot;. AI models reach for it constantly because it sounds decisive. Readers spot it because the negated half is usually a straw claim, so the sentence decorates a point instead of making one.</p>
          <h3>What is performative profundity?</h3>
          <p>Performative profundity is a sentence shaped like wisdom that carries no information: aphorism-style closers and sweeping openers that would still work with any topic&apos;s nouns swapped in. The test is substitution. If the sentence survives unchanged for a bakery, a bank, and a software company, it says nothing about any of them.</p>
          <h3>What is spatial metaphor inflation?</h3>
          <p>Spatial metaphor inflation is the habit of describing abstract subjects with physical and spatial language: the landscape of an industry, the intersection of two fields, unlocking opportunities, an ecosystem of tools. A single metaphor can clarify. Inflation is when the metaphors arrive in every paragraph and the plain claim, what something does and what it costs, never shows up.</p>
          <h3>Can a prompt really change how AI writes?</h3>
          <p>Yes, for style. Current models follow explicit writing instructions closely when the instructions sit at the end of the prompt: banned phrases, sentence-length variety, direct statement over contrast formulas. A prompt cannot supply your facts, so the content still needs you. The style, though, is very fixable by instruction.</p>
          <h3>Will this fool AI detectors?</h3>
          <p>That is the wrong target. AI detectors are unreliable in both directions, flagging human writing and passing generated writing every day. Writing that follows the fixes in this article reads better to people, which is the result worth having. Detector scores are a side effect at best.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
