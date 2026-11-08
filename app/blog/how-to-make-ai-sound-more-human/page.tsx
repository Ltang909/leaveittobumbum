import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";

export const metadata: Metadata = {
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
          <p>You can usually tell AI wrote a paragraph within two sentences. The giveaway is rarely a wrong fact. It is a set of writing habits that repeat from draft to draft, and each habit has a name. Named habits are fixable habits: once you can point at the pattern, you can hand the AI an instruction that removes it.</p>
          <p>Here are the four habits that give AI writing away, what each one is called, and how to fix each one in the prompt.</p>
          <h2>Tell 1: Formulaic contrast writing</h2>
          <p>The technical name underneath this one is rhetorical antithesis: a claim balanced against its opposite in parallel clauses. Orators have used antithesis for centuries, and used it sparingly, for lines they wanted remembered. AI models use a canned version of it constantly. You have read all three of these forms this week:</p>
          <ul>
            <li>&quot;It&apos;s not X, it&apos;s Y&quot;: &quot;It&apos;s not about the features. It&apos;s about the feeling.&quot;</li>
            <li>&quot;X. Never Y.&quot;: &quot;Quality. Never compromise.&quot;</li>
            <li>&quot;You&apos;re not doing X, you&apos;re doing Y&quot;: &quot;You&apos;re not managing tasks. You&apos;re buying back your time.&quot;</li>
            <li>&quot;Less X, more Y&quot;: &quot;Less hustle, more flow.&quot;</li>
            <li>&quot;Say goodbye to X, say hello to Y&quot;: &quot;Say goodbye to spreadsheets, say hello to calm.&quot;</li>
            <li>&quot;The old way: X. The new way: Y.&quot;: the two-column comparison table on half the landing pages you visited this month.</li>
          </ul>
          <p>It reads as generated because the negated half is almost always a claim nobody made. No reader walked in believing the opposite, so the sentence spends its energy defeating a ghost and lands as decoration. The fix is plain: state the claim directly, with the fact that supports it. &quot;It&apos;s not about working harder, it&apos;s about working smarter&quot; becomes &quot;The team shipped more after cutting two standing meetings and keeping Fridays meeting-free.&quot; One of those sentences tells you something. The other performs telling you something.</p>
          <h2>Tell 2: Performative profundity</h2>
          <p>Performative profundity is a sentence shaped like wisdom that carries no information. &quot;In a world where change is the only constant, adaptability is everything.&quot; &quot;At the end of the day, it is about people.&quot; These lines sound like conclusions. They conclude nothing.</p>
          <p>The obvious ones:</p>
          <ul>
            <li>&quot;In a world where change is the only constant...&quot;</li>
            <li>&quot;At the end of the day, it is about people.&quot;</li>
            <li>&quot;It is more than a product. It is a movement.&quot;</li>
            <li>&quot;The future belongs to those who dare to begin.&quot;</li>
            <li>&quot;Because you deserve more.&quot;</li>
            <li>&quot;True success is measured by the lives we touch.&quot;</li>
          </ul>
          <p>Picture the first one at the top of a press release about accounting software. It fits, and it would fit a press release about dog food just as well. That is the whole problem.</p>
          <p>There is a simple test: substitution. Swap the topic nouns for a different business&apos;s nouns. If the sentence still works word-for-word for a bakery, a bank, and a software company, it says nothing about any of them. Models produce these lines because profundity-shaped sentences are common in their training text and read as a safe way to open or close anything. The fix is to end on something checkable: a fact, a decision, a price, a next step. Readers trust specifics. They skim aphorisms.</p>
          <h2>Tell 3: Spatial metaphor inflation</h2>
          <p>Spatial metaphor inflation is the habit of describing abstract subjects in physical, spatial language. &quot;The landscape of modern marketing.&quot; &quot;At the intersection of design and technology.&quot; &quot;Unlocking new opportunities.&quot; &quot;A robust ecosystem of tools.&quot; Individually, a metaphor can clarify an idea. Inflation is the pattern: paragraph after paragraph reaches for terrain, buildings, containers, and keys, while the plain claim never arrives. What does the product do? What does it cost? Who is it for? Those sentences are missing, and readers feel the absence even when they cannot name it.</p>
          <p>The phrase list worth banning outright:</p>
          <ul>
            <li>&quot;The shape of this&quot; industry, project, moment, or market</li>
            <li>&quot;The landscape of&quot; anything that has no terrain</li>
            <li>&quot;At the intersection of&quot; two ideas</li>
            <li>&quot;The contours of&quot;, &quot;the fabric of&quot;, &quot;the DNA of&quot; a company or a plan</li>
            <li>&quot;Navigating the complexities of&quot; a task people just do</li>
            <li>&quot;The foundation of&quot; and &quot;the pillars of&quot; anything unbuilt</li>
            <li>&quot;Unlocking opportunities&quot;, &quot;bridging the gap&quot;, &quot;moving the needle&quot;</li>
            <li>&quot;The path forward&quot;, &quot;the space between&quot;, &quot;the lens through which&quot;</li>
            <li>&quot;An ecosystem of&quot; tools, partners, or solutions</li>
          </ul>
          <p>The fix is to write the plain claim first and let a metaphor in only when it explains something the plain sentence could not. Most of the time it cannot, and the plain sentence wins on its own.</p>
          <h2>Tell 4: One-pace rhythm and the filler vocabulary</h2>
          <p>Two smaller tells travel together. The first is rhythm: human writing varies sentence length a lot, short fragments next to long rolling sentences. Writing researchers call this variation burstiness. AI defaults to medium-length sentences in a steady row, which reads like a metronome and tires the eye. The second is vocabulary: delve, tapestry, testament to, furthermore, moreover, in today&apos;s fast-paced world. Any one of these can appear in honest writing. Three in a paragraph is a signature.</p>
          <p>The fuller banned list: delve, tapestry, testament to, game-changer, revolutionize, ever-evolving, look no further, we have got you covered, when it comes to, it is important to note, rest assured, furthermore, moreover, additionally, in conclusion. Stacked into one sentence, the way drafts actually arrive: &quot;In today&apos;s fast-paced world, it is important to note that our ever-evolving ecosystem delves into the landscape of modern work, unlocking opportunities and truly moving the needle.&quot; Every phrase in that sentence is on a list in this article, and the sentence still manages to say nothing.</p>
          <p>The fixes are mechanical. Ask for varied sentence lengths. Ban the filler words by name. Both instructions work because they are concrete enough for a model to follow exactly.</p>
          <h2>Fix it in the prompt, once</h2>
          <p>Every fix above is an instruction, and instructions can ride along with your prompt. Put them at the end, after your request, because models weight the last instructions heavily. One starter block looks like this:</p>
          <ul>
            <li>State the point directly. Skip contrast formulas like &quot;It is not X, it is Y&quot;.</li>
            <li>Use concrete nouns and direct verbs. Say what something does, what it costs, and who it is for.</li>
            <li>Vary sentence length. Mix short sentences with longer ones.</li>
            <li>Do not use: delve, tapestry, testament to, game-changer, furthermore, moreover.</li>
          </ul>
          <p>We keep a copy-ready set of these as a free tool: <a href="/tools/sound-human/">Sound Human</a> holds seven prompt add-ons, each one aimed at a habit in this article, with a combiner that stacks your picks onto your own prompt. Copy a block once, save it in your notes, and your future prompts start from human-sounding by default. Your facts still come from you. The habits are the part the instructions can carry.</p>
          <h2>Frequently asked questions</h2>
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
