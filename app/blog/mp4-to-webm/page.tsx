import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";
import ToolCallout from "../../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/blog/mp4-to-webm/" },
  openGraph: { url: "/blog/mp4-to-webm/" },
  title: "How to Convert MP4 to WebM (Smaller Video for the Web) | Leave It to Bum Bum",
  description: "Convert MP4 to WebM in your browser for lighter web pages and faster-loading embeds. What WebM buys you, and where MP4 still wins.",
};

const SLUG = "mp4-to-webm";
const HEADLINE = "How to Convert MP4 to WebM (Smaller Video for the Web)";
const DESC = "Convert MP4 to WebM in your browser for lighter web pages and faster-loading embeds. What WebM buys you, and where MP4 still wins.";

const postingLd = {
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": HEADLINE,
  "description": DESC,
  "datePublished": "2026-11-03",
  "dateModified": "2026-11-03",
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
    { "@type": "Question", "name": "How much smaller is WebM than MP4?", "acceptedAnswer": { "@type": "Answer", "text": "It depends on the clip, but WebM commonly lands meaningfully smaller than an MP4 of the same video at similar visual quality, which is why it exists. Busy, detailed footage shows the biggest savings." } },
    { "@type": "Question", "name": "Does converting MP4 to WebM lose quality?", "acceptedAnswer": { "@type": "Answer", "text": "The converter re-encodes the video, which is a lossy step, and aims to keep the result visually the same. Videos taller than 720 pixels are converted at 720 tall, which is plenty for web embeds and keeps files lean." } },
    { "@type": "Question", "name": "Does WebM play in every browser?", "acceptedAnswer": { "@type": "Answer", "text": "Every current major browser plays WebM in web pages. The weak spots are older software and some older Safari versions, which is why keeping an MP4 fallback for important embeds is a sensible habit." } },
    { "@type": "Question", "name": "How big a video can I convert?", "acceptedAnswer": { "@type": "Answer", "text": "Up to 100 MB, in MP4, WebM, MOV, MKV, or AVI. The work happens on your device, so big files convert at your computer's speed." } },
    { "@type": "Question", "name": "Do I need an account to convert MP4 to WebM?", "acceptedAnswer": { "@type": "Answer", "text": "No. You can convert as a guest. It is free, with no limits." } }
  ]
};

export default function Mp4ToWebm() {
  return (
    <main>
      <SiteHeader />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(postingLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqLd) }} />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>How to Convert MP4 to WebM (Smaller Video for the Web)</h1>
        <p className="lede">Updated November 3, 2026 · By the LeaveItToBumBum team</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="MP4 to WebM Converter"
            href="/tools/mp4-to-webm/"
            heading="Just want your MP4 as a WebM?"
            blurb="MP4 to WebM Converter converts your video in your browser, with no upload. Free, with no limits."
            cta="Open MP4 to WebM Converter"
          />

          <h2>What to do</h2>

          <h3>Drop the MP4 in</h3>
          <p>Open the converter and drop in your MP4. Files up to 100 MB are fine, and WebM, MOV, MKV, and AVI sources work too if the clip did not start life as an MP4.</p>

          <h3>Pick WebM as the format</h3>
          <p>Choose the WebM format card. One pleasant surprise: if a video is already in a WebM-friendly encoding, the converter repacks it without re-encoding, which is much faster than a full conversion.</p>

          <h3>Convert and download</h3>
          <p>Convert and download the WebM, then swap it into your page in place of the MP4. Compare the two file sizes side by side; on video, the savings tend to announce themselves.</p>

          <h2>Cost</h2>
          <ul>
          <li>MP4 to WebM Converter: free, with no limits, and no account needed.</li>
          </ul>

          <h2>FAQs</h2>
          <h3>How much smaller is WebM than MP4?</h3>
          <p>It depends on the clip, but WebM commonly lands meaningfully smaller than an MP4 of the same video at similar visual quality, which is why it exists. Busy, detailed footage shows the biggest savings.</p>
          <h3>Does converting MP4 to WebM lose quality?</h3>
          <p>The converter re-encodes the video, which is a lossy step, and aims to keep the result visually the same. Videos taller than 720 pixels are converted at 720 tall, which is plenty for web embeds and keeps files lean.</p>
          <h3>Does WebM play in every browser?</h3>
          <p>Every current major browser plays WebM in web pages. The weak spots are older software and some older Safari versions, which is why keeping an MP4 fallback for important embeds is a sensible habit.</p>
          <h3>How big a video can I convert?</h3>
          <p>Up to 100 MB, in MP4, WebM, MOV, MKV, or AVI. The work happens on your device, so big files convert at your computer's speed.</p>
          <h3>Do I need an account to convert MP4 to WebM?</h3>
          <p>No. You can convert as a guest. It is free, with no limits.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
