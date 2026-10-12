import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "How to turn voice notes into text | Leave It to Bum Bum",
  description: "Talk it out and get text you can use: how voice note transcription works, how to clean up a raw transcript, and how to turn rambles into docs, posts, and to-do lists.",
};

export default function VoiceNotesToTextGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">Guide</p>
        <h1>How to turn voice notes into text</h1>
        <p className="lede">The idea shows up while you are walking, not while you are sitting at a keyboard. Voice notes catch it. Transcription makes it usable.</p>
      </section>
      <section className="shell">
        <div className="prose">
          <h2>How to turn voice notes into text</h2>
          <p>Your clearest thinking rarely happens at a keyboard. It shows up on a walk, in the car, or halfway through making coffee, and it evaporates if you wait until you are back at your desk. Talking captures it in the moment. Text is what makes it usable later. Here is how to get from one to the other without a pile of unplayed audio files.</p>

          <h2>When talking beats typing</h2>
          <p>Talking is roughly three times faster than typing for most people, and it works in places a keyboard does not.</p>
          <ul>
          <li><strong>Walking.</strong> A lap around the block doubles as a planning session. Talk through the project, the problem, or the email you keep avoiding.</li>
          <li><strong>Driving.</strong> Record while parked, or from the passenger seat. The idea you had at the red light is still there when you arrive, in words instead of a vague feeling.</li>
          <li><strong>Mid-task.</strong> Hands busy, brain busy. When your hands are in a repair, a recipe, or a stockroom, your voice is the only free input you have.</li>
          <li><strong>Tired-brain hours.</strong> At the end of the day, forming sentences out loud is easier than facing a blank doc. The transcript gives tomorrow-you something to work with.</li>
          </ul>

          <h2>Browser recording vs phone voice-memo apps</h2>
          <p>Both record your voice. What happens next is very different.</p>
          <ul>
          <li><strong>Phone voice memos</strong> save an audio file inside the app. The words stay locked in audio until you play them back and type them out yourself, or pay attention to whatever transcript the app buries three taps deep. Most memos die there, unplayed and unsearched.</li>
          <li><strong>Browser recording</strong> puts the transcript on the page in front of you as you finish. The text is the product. You can copy it, edit it, and paste it straight into the doc, post, or list it was always meant to become.</li>
          </ul>
          <p>Rule of thumb: if the thought needs to become words on a page, start with a tool that hands you words. Save the memo app for recording other people, like interviews and meetings you will sit through later.</p>

          <h2>Clean up the raw transcript</h2>
          <p>A fresh transcript is a draft, not a finished piece. Five minutes of cleanup makes it read like you meant it.</p>
          <ul>
          <li><strong>Fix the names.</strong> Speech tools mangle names first. Check every person, client, and product name and correct them, because a wrong name is the one error everyone notices.</li>
          <li><strong>Check the numbers.</strong> Prices, dates, and quantities deserve a second look. When you record, say amounts slowly and clearly. Your future self will thank you.</li>
          <li><strong>Cut the filler.</strong> Delete the ums, likes, and so-yeahs. While you are at it, delete the first sentence too. Most voice notes start with throat-clearing, and the real point arrives second.</li>
          <li><strong>Read it once out loud.</strong> Your ears catch the clumsy bits your eyes skim past. If a sentence is hard to say, it is hard to read.</li>
          </ul>

          <h2>Turn transcripts into docs, posts, and to-do lists</h2>
          <p>This is where the habit pays off. One recording can become several useful things.</p>
          <ul>
          <li><strong>A doc:</strong> paste the cleaned transcript into a document and add headings. You just drafted the brief, the plan, or the first version of the thing.</li>
          <li><strong>A post:</strong> pull the sharpest two or three lines. Spoken language sounds human, which is exactly what most posts are missing.</li>
          <li><strong>A to-do list:</strong> scan the transcript for verbs. Every "I need to" and "remember to" becomes a task. Move them to your list before they sink back into the paragraph.</li>
          <li><strong>An email:</strong> trim the transcript down to the point and send it. Half of your emails wanted to be voice notes first.</li>
          </ul>

          <h2>A quick word on privacy</h2>
          <p>Voice notes hold the raw version of your thinking, including the parts you would never publish. Where they get stored matters. With Bum Bum's tool, your recordings stay in your browser. They are never saved to a server. You can download the audio if you want to keep it, and that choice is yours alone.</p>

          <h2>The easier way</h2>
          <p><a href="/tools/notes/">Voice Notes to Text</a> records you in your browser and hands you a transcript you can copy or download on the spot. Talk it out on your walk, clean up the names, and paste the words wherever they belong. The thinking was always the hard part. This makes the typing disappear.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
