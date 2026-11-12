import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";

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
    { "@type": "Question", "name": "Do I need an account to convert PNG to WebP?", "acceptedAnswer": { "@type": "Answer", "text": "No. Picking and previewing are free, and a finished conversion uses one action. You can convert as a guest with 15 free actions, or create a free account for 75 actions every month." } },
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
          <p>PNG is the format graphics are born in and the format that makes web pages heavy. Logos, screenshots, product cutouts, illustrations: all crisp, all transparent where they should be, and all much bigger than they need to be once they live on a website. WebP keeps the two things you love about PNG, the crispness and the transparency, at a fraction of the weight.</p>
          <p>Here is how to convert PNG to WebP in your browser, and how to choose between WebP and JPG for the job.</p>
          <h2>Why WebP is PNG's natural successor on the web</h2>
          <p>WebP supports transparency just like PNG, so a logo or product shot on a clear background stays on a clear background. It compresses far harder, so the same graphic lands much smaller. And unlike converting to JPG, nothing about the image has to be sacrificed to get the savings: no flattened backgrounds, no solid box around your logo.</p>
          <p>With a browser-based converter like the <a href="/tools/image-converter/">Image Converter</a> from Leave It to Bum Bum, the conversion happens on your own device, so your files are never uploaded anywhere in the process.</p>
          <h2>Step 1: Drop the PNG in</h2>
          <p>Open the converter and drop your PNG in. Site graphics usually travel in packs, so drop the whole set and convert them together.</p>
          <h2>Step 2: Pick WebP and set quality</h2>
          <p>Choose WebP in the Convert to list. The quality slider trades size for softness; graphics with hard edges and text look best kept high, while photographic PNGs can sit lower. When in doubt, convert one file first, look at it at full size, then run the batch.</p>
          <h2>Step 3: Convert and download</h2>
          <p>Convert, download, and check the transparency survived: open the WebP over a colored background and the graphic should float on it with no box. Then compare the file size to the PNG. That comparison is the whole argument.</p>
          <h2>WebP or JPG: which conversion is right?</h2>
          <p>If the image has transparency, or hard-edged graphics and text, WebP is the answer. If it is a plain photograph with no transparency and it needs to travel by email or into other software, <a href="/blog/png-to-jpg/">PNG to JPG</a> is the safer destination, because JPG opens absolutely everywhere and WebP still meets the occasional closed door outside web browsers.</p>
          <h2>Convert a PNG now</h2>
          <p>The <a href="/tools/image-converter/">Image Converter</a> runs in your browser and handles batches. Drop the PNG in, pick WebP, keep the quality high, done.</p>
          <h2>Frequently asked questions</h2>
          <h3>Does WebP keep my PNG's transparency?</h3>
          <p>Yes. WebP supports transparency the same way PNG does, so logos and cutouts keep their clear backgrounds after converting. That is the main reason to choose WebP over JPG for graphics.</p>
          <h3>How much smaller will the WebP be?</h3>
          <p>Often dramatically: graphics and screenshots commonly shrink by half or more compared to PNG at high quality settings. Flat-color graphics shrink the most.</p>
          <h3>Does converting PNG to WebP lose quality?</h3>
          <p>WebP conversion is lossy at most settings, but at high quality the difference is not visible in normal web use. Keep your original PNGs archived as the masters.</p>
          <h3>Do I need an account to convert PNG to WebP?</h3>
          <p>No. Picking and previewing are free, and a finished conversion uses one action. You can convert as a guest with 15 free actions, or create a free account for 75 actions every month.</p>
          <h3>Can I convert several PNGs at once?</h3>
          <p>Yes. Drop in a batch and they all convert to WebP with the same settings in one pass.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
