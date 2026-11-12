import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";

export const metadata: Metadata = {
  alternates: { canonical: "/blog/png-to-jpg/" },
  openGraph: { url: "/blog/png-to-jpg/" },
  title: "How to Convert PNG to JPG (Smaller Files, Same Picture) | Leave It to Bum Bum",
  description: "Convert PNG to JPG in your browser to shrink photos for email, uploads, and forms. What changes, what flattens, and how to keep the quality high.",
};

const SLUG = "png-to-jpg";
const HEADLINE = "How to Convert PNG to JPG (Smaller Files, Same Picture)";
const DESC = "Convert PNG to JPG in your browser to shrink photos for email, uploads, and forms. What changes, what flattens, and how to keep the quality high.";

const postingLd = {
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": HEADLINE,
  "description": DESC,
  "datePublished": "2026-11-03",
  "dateModified": "2026-11-12",
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
    { "@type": "Question", "name": "Does converting PNG to JPG lose quality?", "acceptedAnswer": { "@type": "Answer", "text": "A little, by design: JPG is a lossy format. At high quality settings the difference is invisible in photos. Crisp text and hard edges in screenshots soften the most, so for images you need to read, keep the quality slider at the top." } },
    { "@type": "Question", "name": "What happens to transparent parts of my PNG?", "acceptedAnswer": { "@type": "Answer", "text": "JPG does not support transparency, so transparent areas are flattened onto a solid background. If the transparency matters, convert the PNG to WebP instead, which keeps it." } },
    { "@type": "Question", "name": "How much smaller will the JPG be?", "acceptedAnswer": { "@type": "Answer", "text": "For photos, often a fifth to a tenth of the PNG's size at high quality. Screenshots with lots of flat color shrink less dramatically, but still noticeably." } },
    { "@type": "Question", "name": "Do I need an account to convert PNG to JPG?", "acceptedAnswer": { "@type": "Answer", "text": "No. Picking and previewing are free, and a finished conversion uses one action. You can convert as a guest with 15 free actions, or create a free account for 75 actions every month." } },
    { "@type": "Question", "name": "Can I convert a batch of PNGs at once?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Drop in several PNGs and they all convert to JPG with the same settings in one pass." } }
  ]
};

export default function PngToJpg() {
  return (
    <main>
      <SiteHeader />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(postingLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqLd) }} />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>How to Convert PNG to JPG (Smaller Files, Same Picture)</h1>
        <p className="lede">Updated November 12, 2026 · By the LeaveItToBumBum team</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <p>PNG is a wonderful format that produces enormous photos. A phone screenshot or camera image saved as PNG can be five to ten times the size of the same picture as a JPG, which is how you end up staring at an upload form's file size error or an email that refuses to send.</p>
          <p>For photos, JPG is the fix: nearly the same picture, a fraction of the bytes. Here is how to convert PNG to JPG in your browser, and the two things worth knowing before you do.</p>
          <h2>Why JPG wins for photos</h2>
          <p>PNG stores every pixel perfectly, which is exactly right for text, logos, and diagrams, and wildly wasteful for a photo of your dog. JPG throws away detail the eye barely notices and keeps the file small. That is why every camera, marketplace, and listing site on earth prefers it.</p>
          <p>A browser-based converter like the <a href="/tools/png-to-jpg/">PNG to JPG Converter</a> from Leave It to Bum Bum does the work on your own device, so the photo never gets uploaded to a stranger's server on the way through.</p>
          <h2>Step 1: Drop the PNG in</h2>
          <p>Open the <a href="/tools/png-to-jpg/">PNG to JPG Converter</a> and drop in your PNG. If you have a pile of them, drop the pile: they can all convert to JPG in one pass.</p>
          <h2>Step 2: Pick JPG, and mind the quality slider</h2>
          <p>Choose JPG in the Convert to list. JPG output comes with a quality slider: higher quality means a bigger file that looks closer to the original, lower means smaller with softer edges. For photos you plan to share or upload, keep quality high; the size savings versus PNG are still dramatic. Text in screenshots is where low quality shows first, so screenshots you need to read deserve the top of the slider.</p>
          <h2>Step 3: Convert and download</h2>
          <p>Convert and download the JPG. Compare the file size against the PNG for a small moment of satisfaction, then delete or archive the PNG if the big original was only taking up space.</p>
          <h2>The one real warning: transparency flattens</h2>
          <p>JPG has no transparency. If your PNG has transparent areas, a logo on a clear background, say, those areas get flattened onto a solid background in the JPG. For graphics that need to stay transparent, convert to <a href="/blog/png-to-webp/">WebP instead</a>: it keeps transparency and still shrinks the file. JPG is for photos; keep PNG or WebP for graphics with clear backgrounds.</p>
          <h2>Convert a PNG now</h2>
          <p>The <a href="/tools/png-to-jpg/">PNG to JPG Converter</a> runs in your browser and handles batches. Drop the PNG in, pick JPG, keep the quality up, done. Converting between other formats instead? The <a href="/tools/image-converter/">Image Converter</a> covers the rest.</p>
          <h2>Frequently asked questions</h2>
          <h3>Does converting PNG to JPG lose quality?</h3>
          <p>A little, by design: JPG is a lossy format. At high quality settings the difference is invisible in photos. Crisp text and hard edges in screenshots soften the most, so for images you need to read, keep the quality slider at the top.</p>
          <h3>What happens to transparent parts of my PNG?</h3>
          <p>JPG does not support transparency, so transparent areas are flattened onto a solid background. If the transparency matters, convert the PNG to WebP instead, which keeps it.</p>
          <h3>How much smaller will the JPG be?</h3>
          <p>For photos, often a fifth to a tenth of the PNG's size at high quality. Screenshots with lots of flat color shrink less dramatically, but still noticeably.</p>
          <h3>Do I need an account to convert PNG to JPG?</h3>
          <p>No. Picking and previewing are free, and a finished conversion uses one action. You can convert as a guest with 15 free actions, or create a free account for 75 actions every month.</p>
          <h3>Can I convert a batch of PNGs at once?</h3>
          <p>Yes. Drop in several PNGs and they all convert to JPG with the same settings in one pass.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
