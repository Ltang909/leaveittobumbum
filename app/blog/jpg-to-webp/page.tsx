import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";

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
    { "@type": "Question", "name": "Do I need an account to convert JPG to WebP?", "acceptedAnswer": { "@type": "Answer", "text": "No. Picking and previewing are free, and a finished conversion uses one action. You can convert as a guest with 15 free actions, or create a free account for 75 actions every month." } },
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
          <p>Images are usually the heaviest thing on a web page, and JPGs are usually most of the images. WebP was built to fix exactly that: the same picture, visibly the same quality, in a notably smaller file. Faster pages, happier visitors, better search rankings. Converting your JPGs is one of the cheapest speed wins a website has.</p>
          <p>Here is how to convert JPG to WebP in your browser, plus the honest limits of the format so you do not convert the wrong files.</p>
          <h2>What WebP actually buys you</h2>
          <p>WebP compresses photos harder than JPG at the same visual quality, so page images commonly land a quarter to a third smaller. On an image-heavy page, a product gallery or a portfolio, that is the difference between a page that feels instant and one that assembles itself while your visitor watches.</p>
          <p>The conversion itself can happen right on your device with a browser-based tool like the <a href="/tools/image-converter/">Image Converter</a> from Leave It to Bum Bum: no uploads, no server queue, and the originals stay exactly where they were.</p>
          <h2>Step 1: Drop the JPG in</h2>
          <p>Open the converter and drop in the JPG. Website work usually means a batch: drop the whole set of page images and convert them all the same way in one pass.</p>
          <h2>Step 2: Pick WebP and set quality</h2>
          <p>Choose WebP in the Convert to list. WebP output has a quality slider; for web photos, the upper-middle of the slider is the sweet spot where files shrink hard and eyes cannot tell the difference. Push it lower only for thumbnails.</p>
          <h2>Step 3: Convert, download, and swap the files</h2>
          <p>Convert and download the WebPs, then swap them into your page in place of the JPGs. Keep the original JPGs in a folder somewhere: WebP is a one-way door for a few uses, covered next.</p>
          <h2>When not to convert</h2>
          <p>WebP is a web format. Email attachments, print jobs, office documents, and older software can still refuse it, and social platforms are inconsistent about accepting uploads. Rule of thumb: images that live on web pages become WebP; images you send to people and other software stay JPG. And if a WebP ever lands on you and will not open, the reverse trip is easy: <a href="/blog/webp-to-png/">WebP to PNG</a> fixes it in a minute.</p>
          <h2>Convert a JPG now</h2>
          <p>The <a href="/tools/image-converter/">Image Converter</a> runs in your browser and converts batches at once. Drop the JPGs in, pick WebP, download the lighter set.</p>
          <h2>Frequently asked questions</h2>
          <h3>How much smaller is WebP than JPG?</h3>
          <p>At the same visual quality, WebP photos typically come out about a quarter to a third smaller than JPG. Exact numbers depend on the image; busy detailed photos save the most.</p>
          <h3>Does converting JPG to WebP lose quality?</h3>
          <p>JPG is already lossy, and converting adds one more lossy step. At a high quality setting the result is visually the same for web use. Keep your original JPGs archived in case you ever need the untouched version.</p>
          <h3>Will WebP work in every browser?</h3>
          <p>Every current major browser displays WebP. The holdouts are older software, some email clients, and desktop apps, which is why WebP is for web pages rather than for files you send around.</p>
          <h3>Do I need an account to convert JPG to WebP?</h3>
          <p>No. Picking and previewing are free, and a finished conversion uses one action. You can convert as a guest with 15 free actions, or create a free account for 75 actions every month.</p>
          <h3>Can I convert a whole folder of JPGs at once?</h3>
          <p>Yes. Drop in several JPGs and they convert to WebP with the same settings in one pass, ready to download together.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
