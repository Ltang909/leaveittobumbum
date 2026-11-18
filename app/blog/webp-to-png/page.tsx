import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";
import ToolCallout from "../../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/blog/webp-to-png/" },
  openGraph: { url: "/blog/webp-to-png/" },
  title: "How to Convert WebP to PNG (Free, In Your Browser) | Leave It to Bum Bum",
  description: "Convert WebP images to PNG right in your browser: no uploads, no quality loss, transparency kept. Here is why WebP files get refused, and the one-minute fix.",
};

const SLUG = "webp-to-png";
const HEADLINE = "How to Convert WebP to PNG (Free, In Your Browser)";
const DESC = "Convert WebP images to PNG right in your browser: no uploads, no quality loss, transparency kept. Here is why WebP files get refused, and the one-minute fix.";

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
    { "@type": "Question", "name": "Does converting WebP to PNG lose quality?", "acceptedAnswer": { "@type": "Answer", "text": "No. PNG is a lossless format, and the WebP is decoded at full quality before being saved as PNG, so the converted image keeps everything the original had. The file size usually grows, because WebP compresses harder, but the picture does not change." } },
    { "@type": "Question", "name": "Does the PNG keep transparent areas?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Both WebP and PNG support transparency, so a WebP with a transparent background converts to a PNG with the same transparent background. Nothing gets flattened onto a color." } },
    { "@type": "Question", "name": "Do I need an account to convert WebP to PNG?", "acceptedAnswer": { "@type": "Answer", "text": "No. Picking and previewing are free, and a finished conversion is free. You can convert as a guest. It is free, with no limits." } },
    { "@type": "Question", "name": "Can I convert several WebP files at once?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Drop in as many as you like and convert them all to PNG in one pass, then download them together." } },
    { "@type": "Question", "name": "Why is my PNG so much bigger than the WebP?", "acceptedAnswer": { "@type": "Answer", "text": "That is expected. WebP was designed to beat PNG on file size, especially for photos. You are trading bytes for acceptance: the PNG opens everywhere, including the places that refused the WebP." } }
  ]
};

export default function WebpToPng() {
  return (
    <main>
      <SiteHeader />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(postingLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqLd) }} />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>How to Convert WebP to PNG (Free, In Your Browser)</h1>
        <p className="lede">Updated November 3, 2026 · By the LeaveItToBumBum team</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="Image Converter"
            href="/tools/image-converter/"
            heading="Just want your WebP as a PNG?"
            blurb="Image Converter turns WebP images into PNG in your browser, in batches, with no upload. Free, with no limits."
            cta="Open Image Converter"
          />

          <h2>What to do</h2>

          <h3>Drop the WebP file in</h3>
          <p>Open the converter and drop your WebP image in, or pick it from your files. You can line up several images at once; batch converting a whole folder of saved WebPs the same way is exactly what it is for.</p>

          <h3>Pick PNG as the output</h3>
          <p>Choose PNG in the Convert to list. PNG is the preselected default, so if that is already showing, you are done with this step. There is no quality slider to worry about for PNG: the format is lossless, so the converted file keeps every pixel the WebP had.</p>

          <h3>Convert and download</h3>
          <p>Convert, then download the PNG. Spot-check it once: the image should look identical, and any transparent areas in the original should still be transparent. WebP and PNG both keep transparency, so nothing flattens or gains a background on the way through.</p>

          <h2>Cost</h2>
          <ul>
          <li>Image Converter: free, with no limits, and no account needed.</li>
          </ul>

          <h2>FAQs</h2>
          <h3>Does converting WebP to PNG lose quality?</h3>
          <p>No. PNG is a lossless format, and the WebP is decoded at full quality before being saved as PNG, so the converted image keeps everything the original had. The file size usually grows, because WebP compresses harder, but the picture does not change.</p>
          <h3>Does the PNG keep transparent areas?</h3>
          <p>Yes. Both WebP and PNG support transparency, so a WebP with a transparent background converts to a PNG with the same transparent background. Nothing gets flattened onto a color.</p>
          <h3>Do I need an account to convert WebP to PNG?</h3>
          <p>No. Picking and previewing are free, and a finished conversion is free. You can convert as a guest. It is free, with no limits.</p>
          <h3>Can I convert several WebP files at once?</h3>
          <p>Yes. Drop in as many as you like and convert them all to PNG in one pass, then download them together.</p>
          <h3>Why is my PNG so much bigger than the WebP?</h3>
          <p>That is expected. WebP was designed to beat PNG on file size, especially for photos. You are trading bytes for acceptance: the PNG opens everywhere, including the places that refused the WebP.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
