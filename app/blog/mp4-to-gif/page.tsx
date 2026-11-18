import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";
import ToolCallout from "../../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/blog/mp4-to-gif/" },
  openGraph: { url: "/blog/mp4-to-gif/" },
  title: "How to Turn an MP4 Into a GIF (In Your Browser) | Leave It to Bum Bum",
  description: "Turn the first seconds of an MP4 into a looping GIF, right in your browser. Why short clips win, what gets left behind, and how to keep the file lean.",
};

const SLUG = "mp4-to-gif";
const HEADLINE = "How to Turn an MP4 Into a GIF (In Your Browser)";
const DESC = "Turn the first seconds of an MP4 into a looping GIF, right in your browser. Why short clips win, what gets left behind, and how to keep the file lean.";

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
    { "@type": "Question", "name": "How much of my video becomes the GIF?", "acceptedAnswer": { "@type": "Answer", "text": "The first 15 seconds. GIFs are meant to be moments, not movies, and longer than that the file size gets unreasonable. If the good part is later in the video, trim the video first so it starts where you want the GIF to start." } },
    { "@type": "Question", "name": "Does the GIF keep the sound?", "acceptedAnswer": { "@type": "Answer", "text": "No. GIF is a silent format with no audio track at all. If the sound matters, share the video itself, or add an MP3 track to a video with the Video MP3 Overlay tool instead." } },
    { "@type": "Question", "name": "How big a video can I convert?", "acceptedAnswer": { "@type": "Answer", "text": "The Video Converter accepts videos up to 100 MB, in MP4, WebM, MOV, MKV, or AVI. Conversion happens on your device, so very large or long videos convert at the speed of your computer." } },
    { "@type": "Question", "name": "Why is my GIF file so big?", "acceptedAnswer": { "@type": "Answer", "text": "GIF barely compresses. Length, resolution, and constant motion all inflate it. A shorter clip is the reliable fix; videos are also converted at no more than 720 pixels tall, which keeps GIFs sane." } },
    { "@type": "Question", "name": "Do I need an account to make a GIF?", "acceptedAnswer": { "@type": "Answer", "text": "No. You can convert as a guest. It is free, with no limits." } }
  ]
};

export default function Mp4ToGif() {
  return (
    <main>
      <SiteHeader />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(postingLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqLd) }} />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>How to Turn an MP4 Into a GIF (In Your Browser)</h1>
        <p className="lede">Updated November 3, 2026 · By the LeaveItToBumBum team</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="MP4 to GIF Converter"
            href="/tools/mp4-to-gif/"
            heading="Just want your MP4 as a GIF?"
            blurb="MP4 to GIF Converter builds a looping GIF from the first 15 seconds of your video, in your browser. Free, with no limits."
            cta="Open MP4 to GIF Converter"
          />

          <h2>What to do</h2>

          <h3>Drop the MP4 in</h3>
          <p>Open the converter and drop in your MP4. Videos up to 100 MB are welcome; the converter also takes WebM, MOV, MKV, and AVI if your clip arrived in one of those.</p>

          <h3>Pick GIF as the format</h3>
          <p>Choose the GIF format card. The converter will build the animation from the opening stretch of the video, so make sure the story starts at the beginning: a GIF that opens mid-action confuses everyone.</p>

          <h3>Convert and download</h3>
          <p>Convert and download the GIF. Watch it loop once with fresh eyes: does it make sense with no sound, no context, on repeat? If the loop point jolts, trim the source a touch shorter; a clean loop is worth more than an extra second.</p>

          <h2>Cost</h2>
          <ul>
          <li>MP4 to GIF Converter: free, with no limits, and no account needed.</li>
          </ul>

          <h2>FAQs</h2>
          <h3>How much of my video becomes the GIF?</h3>
          <p>The first 15 seconds. GIFs are meant to be moments, not movies, and longer than that the file size gets unreasonable. If the good part is later in the video, trim the video first so it starts where you want the GIF to start.</p>
          <h3>Does the GIF keep the sound?</h3>
          <p>No. GIF is a silent format with no audio track at all. If the sound matters, share the video itself, or add an MP3 track to a video with the Video MP3 Overlay tool instead.</p>
          <h3>How big a video can I convert?</h3>
          <p>The Video Converter accepts videos up to 100 MB, in MP4, WebM, MOV, MKV, or AVI. Conversion happens on your device, so very large or long videos convert at the speed of your computer.</p>
          <h3>Why is my GIF file so big?</h3>
          <p>GIF barely compresses. Length, resolution, and constant motion all inflate it. A shorter clip is the reliable fix; videos are also converted at no more than 720 pixels tall, which keeps GIFs sane.</p>
          <h3>Do I need an account to make a GIF?</h3>
          <p>No. You can convert as a guest. It is free, with no limits.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
