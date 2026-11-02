import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";

export const metadata: Metadata = {
  title: "How to Make an Audiogram for Your Podcast Clips | Leave It to Bum Bum",
  description: "An audiogram turns your best podcast moment into a video people can actually stop scrolling for. Here is how to make one, from picking the clip to posting it.",
};

const SLUG = "how-to-make-an-audiogram";
const HEADLINE = "How to Make an Audiogram for Your Podcast Clips";
const DESC = "An audiogram turns your best podcast moment into a video people can actually stop scrolling for. Here is how to make one, from picking the clip to posting it.";

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
    { "@type": "Question", "name": "What is an audiogram?", "acceptedAnswer": { "@type": "Answer", "text": "An audiogram is a short video made from an audio clip, usually a podcast moment, with a moving waveform and captions added. It exists because social platforms host video, not audio, so it is how podcast highlights get shared." } },
    { "@type": "Question", "name": "How long should an audiogram be?", "acceptedAnswer": { "@type": "Answer", "text": "Thirty to sixty seconds is the range that works for most feeds. Long enough to land one complete idea, short enough that a stranger finishes it. If the moment cannot stand alone inside a minute, pick a different moment." } },
    { "@type": "Question", "name": "Do audiograms need captions?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Most social video is watched with the sound off, so captions carry the message. They also make the clip accessible. Large, accurate captions matter more than any visual effect you can add." } },
    { "@type": "Question", "name": "What size should I export an audiogram?", "acceptedAnswer": { "@type": "Answer", "text": "Match the destination: square for general feeds, vertical for Reels, TikTok, and Shorts. Exporting in the right shape from the start keeps your captions and waveform placed properly instead of cropped after the fact." } },
    { "@type": "Question", "name": "Can I make an audiogram without video editing software?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Browser tools like Bum Bum's Audiogram Maker are built for exactly this job: choose the audio moment, add the waveform and captions, and export the finished video, without a timeline editor." } }
  ]
};

export default function HowToMakeAnAudiogram() {
  return (
    <main>
      <SiteHeader />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(postingLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqLd) }} />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>How to Make an Audiogram for Your Podcast Clips</h1>
        <p className="lede">Updated November 2, 2026 · By the LeaveItToBumBum team</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <p>Social feeds do not play audio files. So the best thirty seconds of your episode, the moment that would win a new listener, sits in an MP3 nobody can post. An audiogram fixes that: it wraps the audio in a video with a moving waveform and captions, so the clip can travel where people actually are.</p>
          <h2>Step 1: Pick a moment that works alone</h2>
          <p>The clip has to make sense to someone who has never heard your show. Look for a strong claim, a funny exchange, a story with a turn, or a question answered completely. Thirty to sixty seconds is the sweet spot. If the moment needs two minutes of setup, it is the wrong moment.</p>
          <h2>Step 2: Trim tightly</h2>
          <p>Cut the dead air at both ends. The clip should start mid-energy, on the first interesting word, not on a greeting or a wind-up. Every second of throat-clearing costs you viewers, because the scroll decision happens almost immediately.</p>
          <h2>Step 3: Add the waveform and captions</h2>
          <p>The waveform tells a scrolling thumb that this video has sound worth unmuting. Captions do the real work: most social video is watched silently, so the words on screen are the product. Keep the text large, highlight it as it is spoken if your tool supports it, and proofread the captions. Auto-captions mangle names, and a mangled name in a shareable clip is forever.</p>
          <h2>Step 4: Export for the feed you are posting to</h2>
          <p>Square suits most feeds; vertical suits Reels, TikTok, and Shorts. Export in the shape of the destination rather than cropping later. The <a href="/tools/audiogram/">Audiogram Maker</a> from Leave It to Bum Bum handles the whole job in the browser: pick the clip, style the waveform and captions, and download a finished video, with no editing suite to learn.</p>
          <h2>Step 5: Post it like a trailer, not an announcement</h2>
          <p>The caption should sell the moment, not the episode number. Quote the strongest line, say what the listener will learn or feel, and link the full episode where the platform allows it. One sharp clip a week beats a graphic per episode that nobody clicks.</p>
          <h2>Frequently asked questions</h2>
          <h3>What is an audiogram?</h3>
          <p>An audiogram is a short video made from an audio clip, usually a podcast moment, with a moving waveform and captions added. It exists because social platforms host video, not audio, so it is how podcast highlights get shared.</p>
          <h3>How long should an audiogram be?</h3>
          <p>Thirty to sixty seconds is the range that works for most feeds. Long enough to land one complete idea, short enough that a stranger finishes it. If the moment cannot stand alone inside a minute, pick a different moment.</p>
          <h3>Do audiograms need captions?</h3>
          <p>Yes. Most social video is watched with the sound off, so captions carry the message. They also make the clip accessible. Large, accurate captions matter more than any visual effect you can add.</p>
          <h3>What size should I export an audiogram?</h3>
          <p>Match the destination: square for general feeds, vertical for Reels, TikTok, and Shorts. Exporting in the right shape from the start keeps your captions and waveform placed properly instead of cropped after the fact.</p>
          <h3>Can I make an audiogram without video editing software?</h3>
          <p>Yes. Browser tools like Bum Bum's Audiogram Maker are built for exactly this job: choose the audio moment, add the waveform and captions, and export the finished video, without a timeline editor.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
