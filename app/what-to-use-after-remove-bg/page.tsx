import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";
import ToolCallout from "../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/what-to-use-after-remove-bg/" },
  openGraph: { url: "/what-to-use-after-remove-bg/" },
  title: "What to use after remove.bg | Leave It to Bum Bum",
  description: "remove.bg ends on December 1, 2026 and its background removal moves into Canva. What to check on your account before then, the free pick for most photos, and the right option for product photos, batches, and the API.",
};

export default function WhatToUseAfterRemoveBgGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell blog-head">
        <p className="kicker">Guide</p>
        <h1>What to use after remove.bg</h1>
        <p className="lede">The standalone remove.bg service ends on December 1, 2026, and its background removal moves into Canva. Here is what to sort out before the deadline, and the replacement that fits the way you actually work.</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="Background Remover"
            href="/tools/background-remover/"
            heading="Just need the background gone?"
            blurb="Background Remover takes the background off a photo right in your browser. Free, with no limits, and your photo never uploads."
            cta="Open Free Background Remover"
          />

          <h2>What to do</h2>

          <h3>First, check your remove.bg account</h3>
          <p>Before you pick a replacement, check the details on the account you already have. <a href="https://www.remove.bg/faq/">remove.bg&apos;s FAQ</a> covers each of these for your specific plan:</p>
          <ul>
          <li><strong>Your subscription.</strong> What happens to it when the standalone service ends, and whether anything carries over to Canva.</li>
          <li><strong>Pay-as-you-go credits.</strong> Whether unused credits survive the move, and by when you would need to use them.</li>
          <li><strong>Your account data.</strong> What happens to your uploads, history, and account details after December 1.</li>
          <li><strong>Bulk removal and the desktop app.</strong> Where those workflows point now, since the standalone versions end with the service.</li>
          <li><strong>API usage.</strong> remove.bg&apos;s stated migration path for API users is Leonardo.Ai, covered below.</li>
          </ul>
          <p>Read the FAQ answers against your own plan rather than assuming. Credit and subscription handling depends on how you paid, and a two-minute check now beats a surprise on December 1.</p>

          <h3>For most photos, use our free Background Remover</h3>
          <p>Full disclosure, since this is our guide: Leave It To Bum Bum has a <a href="/tools/background-remover/">Background Remover</a>, and for the everyday job remove.bg handled, it is the free pick. Drop in a JPG, PNG, or WebP and you get a transparent PNG back at the full size of your photo. There is no sign-up, no watermark, and no usage limit.</p>
          <p>The part people like most: the removal happens in your browser, on your device. Your photo is never uploaded to us or anyone else, which makes it a comfortable pick for client work, product shots you have not launched yet, and family photos.</p>
          <p>The honest tradeoffs, so you can judge for yourself:</p>
          <ul>
          <li><strong>The first visit downloads about 56 MB.</strong> That covers the removal model plus the code that runs it. Your browser caches it, so later visits skip the download and photos process right away.</li>
          <li><strong>Each photo takes a few seconds on a laptop.</strong> Phones are slower, and a big photo on an older phone can take up to half a minute.</li>
          <li><strong>Edges are a notch below remove.bg&apos;s paid cloud model</strong> on the hardest subjects: flyaway hair, fur against a busy background, glass, and anything semi-transparent.</li>
          <li><strong>Product photos, portraits, and pets on plain backgrounds come out clean.</strong> That covers most of what people used remove.bg for day to day.</li>
          </ul>

          <h3>If you want the official replacement, go to Canva</h3>
          <p>remove.bg directs most one-off and batch users to Canva, and its remover now lives inside Canva&apos;s design tools. That suits you if the cutout is one step in a bigger design job: resizing for a social post, adding text, or dropping the image into a template.</p>
          <p>The catch is cost. Canva&apos;s <a href="https://www.canva.com/help/background-remover/">help page</a> and <a href="https://www.canva.com/pricing/">pricing page</a> place Background Remover on paid plans, and Canva prompts anyone who is not signed in to log in or create a free account before using it.</p>
          <p><strong>Pick Canva if:</strong> you already work in Canva and want the path remove.bg itself points to.</p>

          <h3>Two more free options worth a test</h3>
          <p><strong>Adobe Express</strong> has a browser and mobile background remover that produces a transparent PNG, and Adobe describes it as free on its <a href="https://www.adobe.com/express/feature/ai/image/remove-background">remover page</a>. Adobe&apos;s public page does not state a numeric removal limit, so test it with your real photos instead of assuming either way.</p>
          <p><strong>Pixelcut</strong> offers a web and mobile remover aimed at creators and small sellers. Its <a href="https://help.pixelcut.ai/article/34-usage-limits">help center</a> says free users typically get about three uses per day, which suits occasional cutouts and not much more.</p>

          <h3>If you process product photos, look at Photoroom</h3>
          <p>Photoroom builds its workflow around product images: background removal, batch editing, apps, and an API in one place. Its free plan includes background removal and 250 watermark-free exports per month, according to <a href="https://www.photoroom.com/blog/photoroom-vs-picsart">Photoroom&apos;s published comparison</a>, and its free utilities carry a daily limit.</p>
          <p><strong>Pick Photoroom if:</strong> you list products regularly, work in batches, or want cutouts turned into finished listing images.</p>

          <h3>If your app uses the remove.bg API, plan the Leonardo.Ai move</h3>
          <p>remove.bg&apos;s <a href="https://www.remove.bg/tr/api/">API notice</a> names Leonardo.Ai as the transition path for self-serve API users. Leonardo&apos;s <a href="https://docs.leonardo.ai/docs/migrate-from-the-removebg-api-to-leonardoai">migration guide</a> is clear that this is a real integration update rather than a key swap, so budget developer time for it. Leonardo prices API use with API credits through its pricing calculator, so check the live calculator before you estimate costs.</p>
          <p><strong>Pick Leonardo.Ai if:</strong> your app or pipeline depends on the remove.bg API and you want the supported path.</p>

          <h3>If you want a local setup, there is rembg</h3>
          <p><a href="https://github.com/danielgatis/rembg">rembg</a> is an open-source, MIT-licensed tool you run yourself, with Python, command-line, Docker, folder batch processing, and HTTP server options. There is no vendor subscription. You handle the setup and the upkeep, and you check the license of the model you choose, since model licenses can differ from the project&apos;s.</p>
          <p><strong>Pick rembg if:</strong> you are comfortable running your own tools and want full control.</p>

          <h3>Test with your hardest photos before you commit</h3>
          <p>Whichever way you lean, run a few of your own troublemakers through it first: flyaway hair, fur, glass, low contrast, busy backgrounds. Then check the terms that matter for your work, like export limits, sign-in requirements, batch handling, and total cost. Ten minutes of testing with real photos tells you more than any comparison page, this one included.</p>

          <h2>Cost</h2>
          <ul>
          <li>Checking your remove.bg account against the FAQ: free.</li>
          <li>Background Remover: free, with no limits, no sign-up, and no watermark.</li>
          <li>Adobe Express background remover: free, per Adobe&apos;s page; no numeric removal limit stated.</li>
          <li>Pixelcut: free plan covers about three uses per day; paid plans lift the limit.</li>
          <li>Canva Background Remover: included with Canva&apos;s paid plans.</li>
          <li>Photoroom: free plan includes background removal and 250 watermark-free exports a month; paid plans for heavier volume.</li>
          <li>Leonardo.Ai API: paid with API credits, priced through Leonardo&apos;s calculator.</li>
          <li>rembg: free software; you supply the computer and the setup time.</li>
          </ul>

          <h2>FAQs</h2>
          <h3>When does remove.bg shut down?</h3>
          <p>The standalone service ends on December 1, 2026 at 9:00 a.m. CET. remove.bg is moving its background removal into Canva.</p>
          <h3>What happens to my remove.bg subscription and credits?</h3>
          <p>remove.bg answers that in its own FAQ, split by subscriptions, pay-as-you-go credits, account data, bulk removal, the desktop app, and API users. The details depend on your plan, so check the FAQ against your account before December 1 rather than relying on a summary.</p>
          <h3>What is the best free remove.bg alternative?</h3>
          <p>For most single photos, our Background Remover: it is free with no usage limits, runs in your browser, and never uploads your photo. Adobe Express is another free option to test, and Pixelcut&apos;s free plan covers about three uses a day.</p>
          <h3>Does the Background Remover upload my photos?</h3>
          <p>No. The removal model runs on your device, in your browser. Your photo is never uploaded, stored, or sent anywhere. The only download is the model and its runtime on your first visit.</p>
          <h3>Why is the first photo slower in the Background Remover?</h3>
          <p>Your first visit downloads about 56 MB (the removal model plus the code that runs it), and your browser caches it for later visits. After that, a photo takes a few seconds on a laptop and longer on a phone.</p>
          <h3>What should I use for product photos and batches?</h3>
          <p>Photoroom. Its workflow is built for product images, with batch editing and a free plan that includes background removal and 250 watermark-free exports per month.</p>
          <h3>What replaces the remove.bg API?</h3>
          <p>Leonardo.Ai is remove.bg&apos;s stated API transition path. Its migration guide walks through the changes, and it is an integration update rather than a drop-in swap.</p>
          <h3>Can I keep using remove.bg inside Canva instead?</h3>
          <p>Yes, that is the official route. Background removal continues as a Canva feature, and Canva places it on paid plans.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
