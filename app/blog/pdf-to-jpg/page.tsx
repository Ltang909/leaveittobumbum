import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";
import ToolCallout from "../../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/blog/pdf-to-jpg/" },
  openGraph: { url: "/blog/pdf-to-jpg/" },
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
    { "@type": "Question", "name": "Can I convert a password-protected PDF?", "acceptedAnswer": { "@type": "Answer", "text": "Not directly. Unlock it with its password in your usual PDF app, save an unprotected copy, and convert that copy." } },
    { "@type": "Question", "name": "Do I need an account to convert a PDF to JPG?", "acceptedAnswer": { "@type": "Answer", "text": "No. You can convert as a guest. It is free, with no limits." } }
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
          <ToolCallout
            variant="band"
            name="PDF to Image"
            href="/tools/pdf-to-image/"
            heading="Just want your PDF pages as JPGs?"
            blurb="PDF to Image renders every page of your PDF as a JPG in your browser, with no upload. Free, with no limits."
            cta="Open PDF to Image"
          />

          <h2>What to do</h2>

          <h3>Drop the PDF in</h3>
          <p>Open the tool and drop in your PDF. It works page by page through the whole document, however many pages that is. Password-protected PDFs need to be unlocked first: open yours in your usual PDF app, save an unprotected copy, and drop that in.</p>

          <h3>Pick JPG, a size, and a quality</h3>
          <p>JPG is the preselected format. Then pick a render size: the standard size suits most uploads, while the larger sizes render pages at two or three times the resolution, which keeps small text crisp when the JPG will be zoomed or printed. Quality sits at 90 percent by default, the sweet spot between sharp pages and sane file sizes.</p>

          <h3>Convert and download</h3>
          <p>Convert, then download each page's JPG individually, or grab them all at once as a single ZIP bundle, which is the sane choice for a long document. Each page arrives as its own image, in page order.</p>

          <h2>Cost</h2>
          <ul>
          <li>PDF to Image: free, with no limits, and no account needed.</li>
          </ul>

          <h2>FAQs</h2>
          <h3>Does each page become its own JPG?</h3>
          <p>Yes. Every page of the PDF is rendered as a separate JPG image, in page order. Download them one at a time or all together in a ZIP bundle.</p>
          <h3>Will the text stay sharp?</h3>
          <p>At the standard size it is sharp enough for screens and uploads. If the JPGs will be printed or zoomed into, pick one of the larger render sizes, which render pages at two or three times the resolution.</p>
          <h3>Can I convert a password-protected PDF?</h3>
          <p>Not directly. Unlock it with its password in your usual PDF app, save an unprotected copy, and convert that copy.</p>
          <h3>Do I need an account to convert a PDF to JPG?</h3>
          <p>No. You can convert as a guest. It is free, with no limits.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
