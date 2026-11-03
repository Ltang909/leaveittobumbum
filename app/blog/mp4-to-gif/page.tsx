import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";

export const metadata: Metadata = {
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
    { "@type": "Question", "name": "Do I need an account to make a GIF?", "acceptedAnswer": { "@type": "Answer", "text": "No. You can convert as a guest with 15 free actions, or create a free account for 75 actions every month. One finished conversion uses one action." } }
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
          <p>A GIF does one thing a video cannot: it just plays. No play button, no player, no sound expectations. It loops in a doc, a chat, a ticket, a listing, quietly demonstrating your point forever. That is why the three-second product demo and the bug report that explains itself are GIFs.</p>
          <p>Here is how to turn an MP4 into a GIF in your browser, and the limits that make a good GIF instead of a heavy one.</p>
          <h2>Know the format's rules before you start</h2>
          <p>A GIF is a slideshow of frames with no sound and very little compression talent. Three consequences follow. First, short wins: a few seconds loop beautifully, a minute becomes a file the size of a small car. Second, there is no audio, ever. Third, the clip has to carry its meaning visually, in motion, fast.</p>
          <p>Bum Bum's <a href="/tools/video-converter/">Video Converter</a> builds the GIF from the first 15 seconds of your video, right in your browser, so the clip never gets uploaded. If the moment you need sits later in the video, <a href="/tools/video-trimmer/">trim the video</a> first so the good part is at the front, then convert the trimmed file.</p>
          <h2>Step 1: Drop the MP4 in</h2>
          <p>Open the converter and drop in your MP4. Videos up to 100 MB are welcome; the converter also takes WebM, MOV, MKV, and AVI if your clip arrived in one of those.</p>
          <h2>Step 2: Pick GIF as the format</h2>
          <p>Choose the GIF format card. The converter will build the animation from the opening stretch of the video, so make sure the story starts at the beginning: a GIF that opens mid-action confuses everyone.</p>
          <h2>Step 3: Convert and download</h2>
          <p>Convert and download the GIF. Watch it loop once with fresh eyes: does it make sense with no sound, no context, on repeat? If the loop point jolts, trim the source a touch shorter; a clean loop is worth more than an extra second.</p>
          <h2>Keeping the file lean</h2>
          <p>GIF weight grows with length, size, and motion. Short clips, steady shots, and simple scenes stay small; long clips full of camera movement balloon. If your GIF comes out heavy, the fix is almost always a shorter clip, not a settings hunt.</p>
          <h2>Make a GIF now</h2>
          <p>The <a href="/tools/video-converter/">Video Converter</a> runs in your browser, so your video stays on your device the whole time. Drop the MP4 in, pick GIF, and let it loop.</p>
          <h2>Frequently asked questions</h2>
          <h3>How much of my video becomes the GIF?</h3>
          <p>The first 15 seconds. GIFs are meant to be moments, not movies, and longer than that the file size gets unreasonable. If the good part is later in the video, trim the video first so it starts where you want the GIF to start.</p>
          <h3>Does the GIF keep the sound?</h3>
          <p>No. GIF is a silent format with no audio track at all. If the sound matters, share the video itself, or add an MP3 track to a video with the Video MP3 Overlay tool instead.</p>
          <h3>How big a video can I convert?</h3>
          <p>The Video Converter accepts videos up to 100 MB, in MP4, WebM, MOV, MKV, or AVI. Conversion happens on your device, so very large or long videos convert at the speed of your computer.</p>
          <h3>Why is my GIF file so big?</h3>
          <p>GIF barely compresses. Length, resolution, and constant motion all inflate it. A shorter clip is the reliable fix; videos are also converted at no more than 720 pixels tall, which keeps GIFs sane.</p>
          <h3>Do I need an account to make a GIF?</h3>
          <p>No. You can convert as a guest with 15 free actions, or create a free account for 75 actions every month. One finished conversion uses one action.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
