import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";
import ToolCallout from "../../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/blog/jpg-to-webp/" },
  openGraph: { url: "/blog/jpg-to-webp/" },
  title: "How to Convert JPG to WebP (Shrink Images for Your Website) | Leave It to Bum Bum",
  description: "Convert JPG to WebP in your browser and cut image weight on your website without a visible quality drop. When WebP is the right call, and when it is not.",
};

const SLUG = "jpg-to-webp";
const HEADLINE = "How to Convert JPG to WebP (Shrink Images for Your Website)";
const DESC = "Convert JPG to WebP in your browser and cut image weight on your website without a visible quality drop. When WebP is the right call, and when it is not.";

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
    { "@type": "Question", "name": "How much smaller is WebP than JPG?", "acceptedAnswer": { "@type": "Answer", "text": "At the same visual quality, WebP photos typically come out about a quarter to a third smaller than JPG. Exact numbers depend on the image; busy detailed photos save the most." } },
    { "@type": "Question", "name": "Does converting JPG to WebP lose quality?", "acceptedAnswer": { "@type": "Answer", "text": "JPG is already lossy, and converting adds one more lossy step. At a high quality setting the result is visually the same for web use. Keep your original JPGs archived in case you ever need the untouched version." } },
    { "@type": "Question", "name": "Will WebP work in every browser?", "acceptedAnswer": { "@type": "Answer", "text": "Every current major browser displays WebP. The holdouts are older software, some email clients, and desktop apps, which is why WebP is for web pages rather than for files you send around." } },
    { "@type": "Question", "name": "Do I need an account to convert JPG to WebP?", "acceptedAnswer": { "@type": "Answer", "text": "No. Picking and previewing are free, and a finished conversion is free. You can convert as a guest. It is free, with no limits." } },
    { "@type": "Question", "name": "Can I convert a whole folder of JPGs at once?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Drop in several JPGs and they convert to WebP with the same settings in one pass, ready to download together." } }
  ]
};

export default function JpgToWebp() {
  return (
    <main>
      <SiteHeader />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(postingLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqLd) }} />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>How to Convert JPG to WebP (Shrink Images for Your Website)</h1>
        <p className="lede">Updated November 3, 2026 · By the LeaveItToBumBum team</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="Image Converter"
            href="/tools/image-converter/"
            heading="Just want your JPG as a WebP?"
            blurb="Image Converter turns JPGs into WebP in your browser, in batches, with no upload. Free, with no limits."
            cta="Open Image Converter"
          />

          <h2>What to do</h2>

          <h3>Drop the JPG in</h3>
          <p>Open the converter and drop in the JPG. Website work usually means a batch: drop the whole set of page images and convert them all the same way in one pass.</p>

          <h3>Pick WebP and set quality</h3>
          <p>Choose WebP in the Convert to list. WebP output has a quality slider; for web photos, the upper-middle of the slider is the sweet spot where files shrink hard and eyes cannot tell the difference. Push it lower only for thumbnails.</p>

          <h3>Convert, download, and swap the files</h3>
          <p>Convert and download the WebPs, then swap them into your page in place of the JPGs. Keep the original JPGs in a folder somewhere as the untouched masters.</p>

          <h2>Cost</h2>
          <ul>
          <li>Image Converter: free, with no limits, and no account needed.</li>
          </ul>

          <h2>FAQs</h2>
          <h3>How much smaller is WebP than JPG?</h3>
          <p>At the same visual quality, WebP photos typically come out about a quarter to a third smaller than JPG. Exact numbers depend on the image; busy detailed photos save the most.</p>
          <h3>Does converting JPG to WebP lose quality?</h3>
          <p>JPG is already lossy, and converting adds one more lossy step. At a high quality setting the result is visually the same for web use. Keep your original JPGs archived in case you ever need the untouched version.</p>
          <h3>Will WebP work in every browser?</h3>
          <p>Every current major browser displays WebP. The holdouts are older software, some email clients, and desktop apps, which is why WebP is for web pages rather than for files you send around.</p>
          <h3>Do I need an account to convert JPG to WebP?</h3>
          <p>No. Picking and previewing are free, and a finished conversion is free. You can convert as a guest. It is free, with no limits.</p>
          <h3>Can I convert a whole folder of JPGs at once?</h3>
          <p>Yes. Drop in several JPGs and they convert to WebP with the same settings in one pass, ready to download together.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
