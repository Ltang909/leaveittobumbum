import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";

export const metadata: Metadata = {
  title: "How to Convert PDF Pages to JPG (In Your Browser) | Leave It to Bum Bum",
  description: "Turn every page of a PDF into JPG images, right in your browser: no uploads, per-page downloads or one ZIP. For the forms and platforms that demand pictures.",
};

const SLUG = "pdf-to-jpg";
const HEADLINE = "How to Convert PDF Pages to JPG (In Your Browser)";
const DESC = "Turn every page of a PDF into JPG images, right in your browser: no uploads, per-page downloads or one ZIP. For the forms and platforms that demand pictures.";

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
    { "@type": "Question", "name": "Does each page become its own JPG?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Every page of the PDF is rendered as a separate JPG image, in page order. Download them one at a time or all together in a ZIP bundle." } },
    { "@type": "Question", "name": "Will the text stay sharp?", "acceptedAnswer": { "@type": "Answer", "text": "At the standard size it is sharp enough for screens and uploads. If the JPGs will be printed or zoomed into, pick one of the larger render sizes, which render pages at two or three times the resolution." } },
    { "@type": "Question", "name": "Do I pay an action per page?", "acceptedAnswer": { "@type": "Answer", "text": "No. One action covers the whole PDF, whether it has two pages or sixty-two." } },
    { "@type": "Question", "name": "Can I convert a password-protected PDF?", "acceptedAnswer": { "@type": "Answer", "text": "Not directly. Unlock it with its password in your usual PDF app, save an unprotected copy, and convert that copy." } },
    { "@type": "Question", "name": "Do I need an account to convert a PDF to JPG?", "acceptedAnswer": { "@type": "Answer", "text": "No. You can convert as a guest with 15 free actions, or create a free account for 75 actions every month." } }
  ]
};

export default function PdfToJpg() {
  return (
    <main>
      <SiteHeader />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(postingLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqLd) }} />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>How to Convert PDF Pages to JPG (In Your Browser)</h1>
        <p className="lede">Updated November 3, 2026 · By the LeaveItToBumBum team</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <p>The PDF is finished, and the destination wants pictures of it. A government portal that only accepts image uploads. A marketplace that wants the spec sheet as photos. A client whose whole system is built around JPGs. It feels backwards, because it is, but converting the PDF's pages to JPGs takes about a minute.</p>
          <p>Here is how to turn PDF pages into JPGs in your browser, with your files staying on your device.</p>
          <h2>Why the browser part matters here</h2>
          <p>PDFs that get converted for portals and clients tend to be the sensitive kind: applications, statements, signed documents. Most online converters upload the file to their server to do the work. A browser-based converter like <a href="/tools/pdf-to-image/">PDF to Image</a> from Leave It to Bum Bum renders the pages on your own device instead, so the document never leaves your computer.</p>
          <h2>Step 1: Drop the PDF in</h2>
          <p>Open the tool and drop in your PDF. It works page by page through the whole document, however many pages that is. Password-protected PDFs need to be unlocked first: open yours in your usual PDF app, save an unprotected copy, and drop that in.</p>
          <h2>Step 2: Pick JPG, a size, and a quality</h2>
          <p>JPG is the preselected format. Then pick a render size: the standard size suits most uploads, while the larger sizes render pages at two or three times the resolution, which keeps small text crisp when the JPG will be zoomed or printed. Quality sits at 90 percent by default, the sweet spot between sharp pages and sane file sizes.</p>
          <h2>Step 3: Convert and download</h2>
          <p>Convert, then download each page's JPG individually, or grab them all at once as a single ZIP bundle, which is the sane choice for a long document. Each page arrives as its own image, in page order.</p>
          <h2>JPG or PNG for PDF pages?</h2>
          <p>JPG is the accepted-everywhere default and makes the smallest files, which is what upload portals want. Pages that are mostly text and line art stay crisper as PNG, at a bigger file size. If the destination is fussy about readability, <a href="/blog/pdf-to-png/">PDF to PNG</a> is the sharper trip; if it is fussy about file size, JPG wins.</p>
          <h2>Convert a PDF now</h2>
          <p><a href="/tools/pdf-to-image/">PDF to Image</a> runs in your browser: drop the PDF in, pick JPG, and take the pages home as pictures. One action covers the whole PDF, not one per page.</p>
          <h2>Frequently asked questions</h2>
          <h3>Does each page become its own JPG?</h3>
          <p>Yes. Every page of the PDF is rendered as a separate JPG image, in page order. Download them one at a time or all together in a ZIP bundle.</p>
          <h3>Will the text stay sharp?</h3>
          <p>At the standard size it is sharp enough for screens and uploads. If the JPGs will be printed or zoomed into, pick one of the larger render sizes, which render pages at two or three times the resolution.</p>
          <h3>Do I pay an action per page?</h3>
          <p>No. One action covers the whole PDF, whether it has two pages or sixty-two.</p>
          <h3>Can I convert a password-protected PDF?</h3>
          <p>Not directly. Unlock it with its password in your usual PDF app, save an unprotected copy, and convert that copy.</p>
          <h3>Do I need an account to convert a PDF to JPG?</h3>
          <p>No. You can convert as a guest with 15 free actions, or create a free account for 75 actions every month.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
