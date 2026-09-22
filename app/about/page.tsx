import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "About | Leave It to Bum Bum",
  description: "Why tiny tools, who Bum Bum is, and the 36-hour Operator promise.",
};

export default function About() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">About</p>
        <h1>Meet Bum Bum.</h1>
        <p className="lede">Chief tiny-tool operator. Curious, capable, cat.</p>
      </section>
      <div className="page-body shell">
        <div className="prose">
          <h2>Don&apos;t wanna do it? Leave it to Bum Bum.</h2>
          <p>Most small-business software wants to be your whole operating system. Bum Bum thinks that&apos;s rude.</p>
          <p>Leave It to Bum Bum is a collection of tiny tools that each do exactly one annoying job: build the quote, write the follow-up, clean up the job notes, peek at the profit. No dashboards to learn. No onboarding call with a guy named Chad. Three steps, then nap.</p>
          <h2>Why &ldquo;tiny tools&rdquo;?</h2>
          <p>Big software makes a big promise and then hands you homework. A tiny tool makes one small promise and keeps it: one click, one useful result. If a missing tool is slowing you down, it shouldn&apos;t become a six-month project &mdash; it should become someone else&apos;s Tuesday afternoon.</p>
          <h2>Who is Bum Bum?</h2>
          <p>Bum Bum is a cat, and the chief tiny-tool operator around here. Every tool we ship has to pass Bum Bum&apos;s one-question review: <em>&ldquo;Would this save a real busy person real time, today?&rdquo;</em> If not, it doesn&apos;t ship.</p>
          <div className="mascot-row">
            <figure><img src="/bum-bum-character.png" alt="Bum Bum, illustrated portrait" /><figcaption>The official portrait</figcaption></figure>
            <figure><img src="/bum-bum-funny.png" alt="Bum Bum making a funny face" /><figcaption>After the third espresso</figcaption></figure>
          </div>
          <h3>The many moods of Bum Bum</h3>
          <div className="moods">
            {[["cat-curious", "Default"], ["cat-wink-blep", "Wink"], ["cat-licking", "Blep"], ["cat-sunglasses", "Cool"], ["cat-excited-v2", "Excited"], ["cat-bowtie", "Judging"], ["cat-peeking", "Peek"], ["cat-sleepy-v2", "Sleepy"]].map(([file, label]) => (
              <figure key={file}><img src={`/bum/${file}.png`} alt={`Bum Bum: ${label}`} /><figcaption>{label}</figcaption></figure>
            ))}
          </div>
          <h2>The Operator promise</h2>
          <p>Operator members can request one scoped custom tool a month, and Bum Bum ships it within 36 hours of agreed scope &mdash; or the next month is on us. A missing tool should not become a six-month project.</p>
          <h2>Say hello</h2>
          <p>Bum Bum reads every email. Probably while sitting on the keyboard.</p>
          <p><a href="mailto:hello@leaveittobumbum.com"><strong>hello@leaveittobumbum.com</strong></a></p>
        </div>
      </div>
      <SiteFooter />
    </main>
  );
}
