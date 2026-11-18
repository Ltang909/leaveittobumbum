import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";
import ToolCallout from "../../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/blog/png-to-webp/" },
  openGraph: { url: "/blog/png-to-webp/" },
  title: "How to Convert PNG to WebP (Transparency Kept, File Shrunk) | Leave It to Bum Bum",
  description: "Convert PNG to WebP in your browser: keep transparency, lose most of the file size. The web-ready version of your graphics, in about a minute.",
};

const SLUG = "png-to-webp";
const HEADLINE = "How to Convert PNG to WebP (Transparency Kept, File Shrunk)";
const DESC = "Convert PNG to WebP in your browser: keep transparency, lose most of the file size. The web-ready version of your graphics, in about a minute.";

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
    { "@type": "Question", "name": "Does WebP keep my PNG's transparency?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. WebP supports transparency the same way PNG does, so logos and cutouts keep their clear backgrounds after converting. That is the main reason to choose WebP over JPG for graphics." } },
    { "@type": "Question", "name": "How much smaller will the WebP be?", "acceptedAnswer": { "@type": "Answer", "text": "Often dramatically: graphics and screenshots commonly shrink by half or more compared to PNG at high quality settings. Flat-color graphics shrink the most." } },
    { "@type": "Question", "name": "Does converting PNG to WebP lose quality?", "acceptedAnswer": { "@type": "Answer", "text": "WebP conversion is lossy at most settings, but at high quality the difference is not visible in normal web use. Keep your original PNGs archived as the masters." } },
    { "@type": "Question", "name": "Do I need an account to convert PNG to WebP?", "acceptedAnswer": { "@type": "Answer", "text": "No. Picking and previewing are free, and a finished conversion is free. You can convert as a guest. It is free, with no limits." } },
    { "@type": "Question", "name": "Can I convert several PNGs at once?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Drop in a batch and they all convert to WebP with the same settings in one pass." } }
  ]
};

export default function PngToWebp() {
  return (
    <main>
      <SiteHeader />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(postingLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqLd) }} />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>How to Convert PNG to WebP (Transparency Kept, File Shrunk)</h1>
        <p className="lede">Updated November 3, 2026 · By the LeaveItToBumBum team</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="Image Converter"
            href="/tools/image-converter/"
            heading="Just want your PNG as a WebP?"
            blurb="Image Converter turns PNGs into WebP in your browser, in batches, with no upload. Free, with no limits."
            cta="Open Image Converter"
          />

          <h2>What to do</h2>

          <h3>Drop the PNG in</h3>
          <p>Open the converter and drop your PNG in. Site graphics usually travel in packs, so drop the whole set and convert them together.</p>

          <h3>Pick WebP and set quality</h3>
          <p>Choose WebP in the Convert to list. The quality slider trades size for softness; graphics with hard edges and text look best kept high, while photographic PNGs can sit lower. When in doubt, convert one file first, look at it at full size, then run the batch.</p>

          <h3>Convert and download</h3>
          <p>Convert, download, and check the transparency survived: open the WebP over a colored background and the graphic should float on it with no box. Then compare the file size to the PNG. That comparison is the whole argument.</p>

          <h2>Cost</h2>
          <ul>
          <li>Image Converter: free, with no limits, and no account needed.</li>
          </ul>

          <h2>FAQs</h2>
          <h3>Does WebP keep my PNG's transparency?</h3>
          <p>Yes. WebP supports transparency the same way PNG does, so logos and cutouts keep their clear backgrounds after converting. That is the main reason to choose WebP over JPG for graphics.</p>
          <h3>How much smaller will the WebP be?</h3>
          <p>Often dramatically: graphics and screenshots commonly shrink by half or more compared to PNG at high quality settings. Flat-color graphics shrink the most.</p>
          <h3>Does converting PNG to WebP lose quality?</h3>
          <p>WebP conversion is lossy at most settings, but at high quality the difference is not visible in normal web use. Keep your original PNGs archived as the masters.</p>
          <h3>Do I need an account to convert PNG to WebP?</h3>
          <p>No. Picking and previewing are free, and a finished conversion is free. You can convert as a guest. It is free, with no limits.</p>
          <h3>Can I convert several PNGs at once?</h3>
          <p>Yes. Drop in a batch and they all convert to WebP with the same settings in one pass.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
