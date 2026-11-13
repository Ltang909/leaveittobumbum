import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";

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
    { "@type": "Question", "name": "Do I pay an action per page?", "acceptedAnswer": { "@type": "Answer", "text": "No. One action covers the whole PDF, however many pages it has." } },
    { "@type": "Question", "name": "Do I need an account to convert a PDF to PNG?", "acceptedAnswer": { "@type": "Answer", "text": "No. You can convert as a guest with 15 free actions, or create a free account for 75 actions every month." } }
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
          <p>Some PDF pages deserve better than JPG. A page of dense text, a diagram with hairline lines, a form whose tiny print someone will actually need to read: JPG's compression smudges exactly those edges. PNG renders the page losslessly, pixel for pixel, which is why it is the right format when the page is information rather than a photo.</p>
          <p>Here is how to convert PDF pages to PNG in your browser, and how to manage the one trade PNG always makes.</p>
          <h2>Why do it in the browser</h2>
          <p>The PDFs you convert page by page are usually documents with something in them worth protecting: contracts, statements, applications. Server-based converters upload the file to do the work. <a href="/tools/pdf-to-image/">PDF to Image</a> from Leave It to Bum Bum renders every page on your own device, so the document never leaves your computer at any point.</p>
          <h2>Step 1: Drop the PDF in</h2>
          <p>Open the tool and drop in the PDF. Every page gets converted, in order. If the PDF is password-protected, unlock it first in your usual PDF app and drop in the unprotected copy.</p>
          <h2>Step 2: Pick PNG and a render size</h2>
          <p>Choose PNG in the format list. PNG is lossless, so there is no quality slider to set: every page renders exactly. What you do pick is the render size. Standard suits most uses; the larger sizes render at two or three times the resolution and are worth it when the PNG will be zoomed, printed, or dropped into a document of its own.</p>
          <h2>Step 3: Convert and download</h2>
          <p>Convert, then download the pages individually or as one ZIP bundle. For a long document the bundle is the only civilized option.</p>
          <h2>The trade: PNG files are bigger</h2>
          <p>Lossless has a price, and it is file size. A text page as PNG can be several times its JPG weight, and a sixty-page document becomes a serious folder of images. If the destination is an upload portal with a size cap, or the pages are photos rather than text, <a href="/blog/pdf-to-jpg/">PDF to JPG</a> is the leaner trip. PNG is for when crispness is the point.</p>
          <h2>Convert a PDF now</h2>
          <p><a href="/tools/pdf-to-image/">PDF to Image</a> runs in your browser: drop the PDF in, pick PNG, and download pages that read like the original. One action covers the whole PDF.</p>
          <h2>Frequently asked questions</h2>
          <h3>Why choose PNG over JPG for PDF pages?</h3>
          <p>PNG is lossless: text, lines, and diagrams render exactly, with none of JPG's soft edges around letters. For pages that exist to be read closely, that crispness is worth the bigger files.</p>
          <h3>How much bigger are PNG pages than JPG?</h3>
          <p>Often several times bigger, especially at the larger render sizes. That is the standing PNG trade: perfect fidelity, heavier files. Portals with tight size caps are usually happier with JPG.</p>
          <h3>Does each page become its own PNG?</h3>
          <p>Yes. Every page renders as its own PNG image in page order, downloadable one by one or together in a ZIP bundle.</p>
          <h3>Do I pay an action per page?</h3>
          <p>No. One action covers the whole PDF, however many pages it has.</p>
          <h3>Do I need an account to convert a PDF to PNG?</h3>
          <p>No. You can convert as a guest with 15 free actions, or create a free account for 75 actions every month.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
