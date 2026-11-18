import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";
import ToolCallout from "../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/how-to-turn-voice-notes-into-text/" },
  openGraph: { url: "/how-to-turn-voice-notes-into-text/" },
  title: "How to turn voice notes into text | Leave It to Bum Bum",
  description: "Talk it out and get text you can use: how voice note transcription works, how to clean up a raw transcript, and how to turn rambles into docs, posts, and to-do lists.",
};

export default function VoiceNotesToTextGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell blog-head">
        <p className="kicker">Guide</p>
        <h1>How to turn voice notes into text</h1>
        <p className="lede">The idea shows up while you are walking, not while you are sitting at a keyboard. Voice notes catch it. Transcription makes it usable.</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="Voice Notes to Text"
            href="/tools/notes/"
            heading="Just want your voice notes as text?"
            blurb="Voice Notes to Text turns what you say into text you can copy, search, and send. Free, with no limits."
            cta="Open Voice Notes to Text"
          />

          <h2>What to do</h2>

          <h3>Record, and get the transcript</h3>
          <ol>
          <li>Open <a href="/tools/notes/">Voice Notes to Text</a> and record your note in the browser.</li>
          <li>Stop recording. The transcript is on the page in front of you.</li>
          <li>Copy it, edit it, or download it, and paste it where it belongs.</li>
          </ol>
          <p>Your recordings stay in your browser. They are never saved to a server. You can download the audio if you want to keep it, and that choice is yours alone.</p>

          <h3>Clean up the raw transcript</h3>
          <p>A fresh transcript is a draft, not a finished piece. Five minutes of cleanup makes it read like you meant it.</p>
          <ul>
          <li><strong>Fix the names.</strong> Speech tools mangle names first. Check every person, client, and product name and correct them, because a wrong name is the one error everyone notices.</li>
          <li><strong>Check the numbers.</strong> Prices, dates, and quantities deserve a second look. When you record, say amounts slowly and clearly. Your future self will thank you.</li>
          <li><strong>Cut the filler.</strong> Delete the ums, likes, and so-yeahs. While you are at it, delete the first sentence too. Most voice notes start with throat-clearing, and the real point arrives second.</li>
          <li><strong>Read it once out loud.</strong> Your ears catch the clumsy bits your eyes skim past. If a sentence is hard to say, it is hard to read.</li>
          </ul>

          <h3>Turn the transcript into something useful</h3>
          <p>One recording can become several useful things.</p>
          <ul>
          <li><strong>A doc:</strong> paste the cleaned transcript into a document and add headings. You just drafted the brief, the plan, or the first version of the thing.</li>
          <li><strong>A post:</strong> pull the sharpest two or three lines. Spoken language sounds human, which is exactly what most posts are missing.</li>
          <li><strong>A to-do list:</strong> scan the transcript for verbs. Every "I need to" and "remember to" becomes a task. Move them to your list before they sink back into the paragraph.</li>
          <li><strong>An email:</strong> trim the transcript down to the point and send it. Half of your emails wanted to be voice notes first.</li>
          </ul>

          <h2>Cost</h2>
          <ul>
          <li>Voice Notes to Text: free, with no limits.</li>
          </ul>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
