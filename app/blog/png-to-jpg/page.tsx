import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";
import ToolCallout from "../../components/tool-callout";

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
    { "@type": "Question", "name": "Do I need an account to convert PNG to JPG?", "acceptedAnswer": { "@type": "Answer", "text": "No. Picking and previewing are free, and a finished conversion is free. You can convert as a guest. It is free, with no limits." } },
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
          <ToolCallout
            variant="band"
            name="PNG to JPG Converter"
            href="/tools/png-to-jpg/"
            heading="Just want your PNG as a JPG?"
            blurb="PNG to JPG Converter converts your PNGs in your browser, in batches, with no upload. Free, with no limits."
            cta="Open PNG to JPG Converter"
          />

          <h2>What to do</h2>

          <h3>Drop the PNG in</h3>
          <p>Open the <a href="/tools/png-to-jpg/">PNG to JPG Converter</a> and drop in your PNG. If you have a pile of them, drop the pile: they can all convert to JPG in one pass.</p>

          <h3>Pick JPG, and mind the quality slider</h3>
          <p>Choose JPG in the Convert to list. JPG output comes with a quality slider: higher quality means a bigger file that looks closer to the original, lower means smaller with softer edges. For photos you plan to share or upload, keep quality high; the size savings versus PNG are still dramatic. Text in screenshots is where low quality shows first, so screenshots you need to read deserve the top of the slider.</p>

          <h3>Convert and download</h3>
          <p>Convert and download the JPG. Compare the file size against the PNG for a small moment of satisfaction, then delete or archive the PNG if the big original was only taking up space.</p>

          <h2>Cost</h2>
          <ul>
          <li>PNG to JPG Converter: free, with no limits, and no account needed.</li>
          </ul>

          <h2>FAQs</h2>
          <h3>Does converting PNG to JPG lose quality?</h3>
          <p>A little, by design: JPG is a lossy format. At high quality settings the difference is invisible in photos. Crisp text and hard edges in screenshots soften the most, so for images you need to read, keep the quality slider at the top.</p>
          <h3>What happens to transparent parts of my PNG?</h3>
          <p>JPG does not support transparency, so transparent areas are flattened onto a solid background. If the transparency matters, convert the PNG to WebP instead, which keeps it.</p>
          <h3>How much smaller will the JPG be?</h3>
          <p>For photos, often a fifth to a tenth of the PNG's size at high quality. Screenshots with lots of flat color shrink less dramatically, but still noticeably.</p>
          <h3>Do I need an account to convert PNG to JPG?</h3>
          <p>No. Picking and previewing are free, and a finished conversion is free. You can convert as a guest. It is free, with no limits.</p>
          <h3>Can I convert a batch of PNGs at once?</h3>
          <p>Yes. Drop in several PNGs and they all convert to JPG with the same settings in one pass.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
