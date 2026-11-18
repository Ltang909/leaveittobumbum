import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";
import ToolCallout from "../../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/blog/pdf-to-png/" },
  openGraph: { url: "/blog/pdf-to-png/" },
  title: "How to Convert PDF Pages to PNG (Crispest Text, In Your Browser) | Leave It to Bum Bum",
  description: "Turn PDF pages into crisp PNG images in your browser: lossless pages for documents, diagrams, and screenshots of pages. No uploads, ZIP bundle included.",
};

const SLUG = "pdf-to-png";
const HEADLINE = "How to Convert PDF Pages to PNG (Crispest Text, In Your Browser)";
const DESC = "Turn PDF pages into crisp PNG images in your browser: lossless pages for documents, diagrams, and screenshots of pages. No uploads, ZIP bundle included.";

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
    { "@type": "Question", "name": "Why choose PNG over JPG for PDF pages?", "acceptedAnswer": { "@type": "Answer", "text": "PNG is lossless: text, lines, and diagrams render exactly, with none of JPG's soft edges around letters. For pages that exist to be read closely, that crispness is worth the bigger files." } },
    { "@type": "Question", "name": "How much bigger are PNG pages than JPG?", "acceptedAnswer": { "@type": "Answer", "text": "Often several times bigger, especially at the larger render sizes. That is the standing PNG trade: perfect fidelity, heavier files. Portals with tight size caps are usually happier with JPG." } },
    { "@type": "Question", "name": "Does each page become its own PNG?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Every page renders as its own PNG image in page order, downloadable one by one or together in a ZIP bundle." } },
    { "@type": "Question", "name": "Do I need an account to convert a PDF to PNG?", "acceptedAnswer": { "@type": "Answer", "text": "No. You can convert as a guest. It is free, with no limits." } }
  ]
};

export default function PdfToPng() {
  return (
    <main>
      <SiteHeader />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(postingLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqLd) }} />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>How to Convert PDF Pages to PNG (Crispest Text, In Your Browser)</h1>
        <p className="lede">Updated November 3, 2026 · By the LeaveItToBumBum team</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="PDF to Image"
            href="/tools/pdf-to-image/"
            heading="Just want your PDF pages as PNGs?"
            blurb="PDF to Image renders every page of your PDF as a PNG in your browser, with no upload. Free, with no limits."
            cta="Open PDF to Image"
          />

          <h2>What to do</h2>

          <h3>Drop the PDF in</h3>
          <p>Open the tool and drop in the PDF. Every page gets converted, in order. If the PDF is password-protected, unlock it first in your usual PDF app and drop in the unprotected copy.</p>

          <h3>Pick PNG and a render size</h3>
          <p>Choose PNG in the format list. PNG is lossless, so there is no quality slider to set: every page renders exactly. What you do pick is the render size. Standard suits most uses; the larger sizes render at two or three times the resolution and are worth it when the PNG will be zoomed, printed, or dropped into a document of its own.</p>

          <h3>Convert and download</h3>
          <p>Convert, then download the pages individually or as one ZIP bundle. For a long document the bundle is the only civilized option.</p>

          <h2>Cost</h2>
          <ul>
          <li>PDF to Image: free, with no limits, and no account needed.</li>
          </ul>

          <h2>FAQs</h2>
          <h3>Why choose PNG over JPG for PDF pages?</h3>
          <p>PNG is lossless: text, lines, and diagrams render exactly, with none of JPG's soft edges around letters. For pages that exist to be read closely, that crispness is worth the bigger files.</p>
          <h3>How much bigger are PNG pages than JPG?</h3>
          <p>Often several times bigger, especially at the larger render sizes. That is the standing PNG trade: perfect fidelity, heavier files. Portals with tight size caps are usually happier with JPG.</p>
          <h3>Does each page become its own PNG?</h3>
          <p>Yes. Every page renders as its own PNG image in page order, downloadable one by one or together in a ZIP bundle.</p>
          <h3>Do I need an account to convert a PDF to PNG?</h3>
          <p>No. You can convert as a guest. It is free, with no limits.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
