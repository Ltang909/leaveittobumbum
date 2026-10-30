import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";

export const metadata: Metadata = {
  title: "How to Add an Email Signature in Gmail, Outlook, and Apple Mail | Leave It to Bum Bum",
  description: "Add a professional email signature in minutes: what to include, how to build one free, the exact steps for Gmail, Outlook, and Apple Mail, and fixes for images that do not show.",
};

const postingLd = {
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "How to add an email signature in Gmail, Outlook, and Apple Mail",
  "description": "Add a professional email signature in minutes: what to include, how to build one free, the exact steps for Gmail, Outlook, and Apple Mail, and fixes for images that do not show.",
  "datePublished": "2026-10-30",
  "dateModified": "2026-10-30",
  "author": {
    "@type": "Organization",
    "name": "LeaveItToBumBum",
    "url": "https://leaveittobumbum.com/"
  },
  "publisher": {
    "@type": "Organization",
    "name": "LeaveItToBumBum",
    "url": "https://leaveittobumbum.com/",
    "logo": {
      "@type": "ImageObject",
      "url": "https://leaveittobumbum.com/bum/favicon-cat.png"
    }
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "https://leaveittobumbum.com/blog/how-to-add-an-email-signature/"
  }
};

const breadcrumbLd = {
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "https://leaveittobumbum.com/"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Blog",
      "item": "https://leaveittobumbum.com/blog/"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "How to add an email signature in Gmail, Outlook, and Apple Mail",
      "item": "https://leaveittobumbum.com/blog/how-to-add-an-email-signature/"
    }
  ]
};

const faqLd = {
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "How do I add an email signature in Gmail?",
      "acceptedAnswer": { "@type": "Answer", "text": "Open Gmail on a computer, click the gear icon, then See all settings. On the General tab, scroll to Signature, click Create new, paste your signature into the box, set it as the default under Signature defaults, and scroll down to Save Changes." }
    },
    {
      "@type": "Question",
      "name": "Can I add an email signature for free?",
      "acceptedAnswer": { "@type": "Answer", "text": "Yes. Build the signature with a free generator like Email Signature Maker from Leave It To Bum Bum, copy it, and paste it into your mail app's signature settings. There is nothing to install and no subscription." }
    },
    {
      "@type": "Question",
      "name": "Why is my signature image not showing?",
      "acceptedAnswer": { "@type": "Answer", "text": "Signature images have to live at a public web address. Email apps cannot carry image files inside a pasted signature, so a photo pasted from your computer goes missing. Use an image link instead, and expect some inboxes to hide images until the reader chooses to show them." }
    },
    {
      "@type": "Question",
      "name": "Should my email signature have a photo?",
      "acceptedAnswer": { "@type": "Answer", "text": "It is optional. A photo or logo helps people recognize you, which is worth it in client-facing work. If you do not have a hosted image link handy, a clean text signature with your name, title, and website does the job fine." }
    },
    {
      "@type": "Question",
      "name": "How long should an email signature be?",
      "acceptedAnswer": { "@type": "Answer", "text": "Three to five short lines plus a row of social links is plenty: name, title and company, one phone number, and your website. If your signature is longer than the emails you usually send, trim it." }
    }
  ]
};

export default function EmailSignatureGuide() {
  return (
    <main>
      <SiteHeader />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(postingLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqLd) }} />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>How to add an email signature in Gmail, Outlook, and Apple Mail</h1>
        <p className="lede">Updated October 30, 2026 · By the LeaveItToBumBum team</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <p><strong>Quick answer:</strong> Build the signature first so the formatting is done for you, copy it, then paste it into your mail app's signature settings and set it as the default. In Gmail that is Settings, See all settings, General, Signature, Create new. A free tool like <strong><a href="/tools/email-signature-generator/">Email Signature Maker</a></strong> builds the whole block in your browser, so the paste lands formatted and ready.</p>
          <p>An email signature is the small block of contact details that sits at the bottom of every email you send. It looks like a tiny thing, but it is the most-seen piece of branding most businesses have. Here is how to add one properly, in the three mail apps people actually use.</p>
          <h2>What should an email signature include?</h2>
          <p>Less than you think:</p>
          <ul>
            <li><strong>Your name,</strong> in bold, the biggest thing in the block.</li>
            <li><strong>Job title and company,</strong> one short line.</li>
            <li><strong>One phone number</strong> and <strong>your website</strong>. Your email address is usually already in the From field, but including it does no harm.</li>
            <li><strong>Optional:</strong> a photo or logo, and links to your main social profiles.</li>
          </ul>
          <p>Leave out the motivational quotes, the fax number, three different phone numbers, and the long legal disclaimer if you work for yourself. A signature that is longer than your average email works against you.</p>
          <h2>Step 1: Build the signature</h2>
          <p>You can type a plain text signature straight into your mail app, and for a name-and-title sign-off that is enough. The moment you want bold text, a color, a photo, or social icons, you need formatted HTML, and building that by hand is nobody's idea of fun.</p>
          <p><a href="/tools/email-signature-generator/">Email Signature Maker</a> does it in the browser: fill in your details, pick one of four layouts and an accent color, and copy. Social links come out as small linked icons, and the <a href="/docs/tools/email-signature-generator/">docs page</a> covers every option. Copy the signature before you open your mail settings.</p>
          <h2>Step 2: Add it in Gmail</h2>
          <ol>
            <li>Open Gmail on a computer and click the gear icon, then <strong>See all settings</strong>.</li>
            <li>On the <strong>General</strong> tab, scroll down to <strong>Signature</strong> and click <strong>Create new</strong>. Give it a name.</li>
            <li>Click in the signature box and paste.</li>
            <li>Under <strong>Signature defaults</strong>, pick your new signature for new emails and for replies and forwards.</li>
            <li>Scroll to the bottom and click <strong>Save Changes</strong>.</li>
          </ol>
          <p>Send yourself a test email to check it. Set it up on a computer first: the Gmail app uses this same signature unless you give the app a different mobile one.</p>
          <h2>Step 2: Add it in Outlook</h2>
          <ol>
            <li>In Outlook on the web, open <strong>Settings</strong>, then <strong>Mail</strong>, then <strong>Compose and reply</strong>.</li>
            <li>Click <strong>New signature</strong>, name it, and paste into the box.</li>
            <li>Under <strong>Select default signatures</strong>, pick it for new messages and for replies and forwards.</li>
            <li>Click <strong>Save</strong>.</li>
          </ol>
          <p>On the Outlook desktop app the same idea lives under File, Options, Mail, Signatures on Windows, and Settings, Signatures on a Mac.</p>
          <h2>Step 2: Add it in Apple Mail</h2>
          <ol>
            <li>Open Mail and go to <strong>Settings</strong>, then <strong>Signatures</strong>.</li>
            <li>Select your account on the left, then click <strong>+</strong> to add a signature.</li>
            <li>Paste into the signature box.</li>
            <li>If the fonts shift after pasting, untick <strong>Always match my default message font</strong>.</li>
          </ol>
          <p>You can also paste a signature on an iPhone under Settings, Mail, Signature, but the small screen makes a fiddly job of it. Desktop first is easier.</p>
          <h2>When it goes wrong</h2>
          <h3>Images do not show</h3>
          <p>Signature images have to live at a public web address. Email apps cannot carry image files inside a pasted signature, so a photo pasted straight from your computer goes missing at the other end. Use an image link instead. Some inboxes also hide images until the reader taps to show them, which is normal and not something your signature can control.</p>
          <h3>The signature shows on new emails but not replies</h3>
          <p>Every mail app has separate defaults for new messages and for replies and forwards. Go back to the defaults setting and set both.</p>
          <h3>The formatting breaks</h3>
          <p>Copy from the generator, not from a word processor document, which drags in its own formatting. If your app asks for HTML directly, use the signature's downloaded HTML file instead of pasting.</p>
          <h3>It looks fine on desktop and odd on a phone</h3>
          <p>Keep the signature narrow and short, and avoid wide banners. Test by emailing your own phone before you send it to a client.</p>
          <h2>Email signature questions</h2>
          <h3>How do I add an email signature in Gmail?</h3>
          <p>Open Gmail on a computer, click the gear icon, then See all settings. On the General tab, scroll to Signature, click Create new, paste your signature into the box, set it as the default under Signature defaults, and scroll down to Save Changes.</p>
          <h3>Can I add an email signature for free?</h3>
          <p>Yes. Build the signature with a free generator like Email Signature Maker from Leave It To Bum Bum, copy it, and paste it into your mail app's signature settings. There is nothing to install and no subscription.</p>
          <h3>Why is my signature image not showing?</h3>
          <p>Signature images have to live at a public web address. Email apps cannot carry image files inside a pasted signature, so a photo pasted from your computer goes missing. Use an image link instead, and expect some inboxes to hide images until the reader chooses to show them.</p>
          <h3>Should my email signature have a photo?</h3>
          <p>It is optional. A photo or logo helps people recognize you, which is worth it in client-facing work. If you do not have a hosted image link handy, a clean text signature with your name, title, and website does the job fine.</p>
          <h3>How long should an email signature be?</h3>
          <p>Three to five short lines plus a row of social links is plenty: name, title and company, one phone number, and your website. If your signature is longer than the emails you usually send, trim it.</p>
          <p><strong><a href="/tools/email-signature-generator/">Make your signature</a></strong></p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
