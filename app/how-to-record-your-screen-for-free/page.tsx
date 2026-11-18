import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";
import ToolCallout from "../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/how-to-record-your-screen-for-free/" },
  openGraph: { url: "/how-to-record-your-screen-for-free/" },
  title: "How to record your screen for free (no software to install) | Leave It to Bum Bum",
  description: "Record your screen right in the browser: a tab, a window, or the full screen, with mic and camera bubble. Practical tips for a clean one-take recording you can send anywhere.",
};

export default function RecordScreenGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell blog-head">
        <p className="kicker">Guide</p>
        <h1>How to record your screen for free (no software to install)</h1>
        <p className="lede">A short screen recording can explain a process faster than writing it out. A two-minute screen recording replaces the meeting, the screenshot chain, and the long thread of confused replies.</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="Screen Recorder"
            href="/tools/clips/"
            heading="Just want to record your screen?"
            blurb="Screen Recorder records a tab, a window, or your full screen in your browser, with mic and camera bubble. Free, with no limits."
            cta="Open Screen Recorder"
          />

          <h2>What to do</h2>

          <h3>Pick what to record</h3>
          <p>When you start a browser recording, you pick what the camera sees. Each option has a job.</p>
          <ul>
          <li><strong>One browser tab.</strong> Pick this for anything that lives in your browser: a dashboard, a doc, a web app, your store backend. The recording follows that tab, so your other tabs and their embarrassing titles stay out of it.</li>
          <li><strong>One window.</strong> Pick this for a desktop app, like a spreadsheet, a code editor, or a design file. The recording follows that app while you work in it.</li>
          <li><strong>Your full screen.</strong> Pick this when the task jumps between apps, like copying numbers from a spreadsheet into a browser tool. Everything on screen can end up in the video, so treat it like company is coming over and tidy up first.</li>
          </ul>

          <h3>Set up your mic and camera bubble first</h3>
          <ul>
          <li><strong>Mic on, unless the clip explains itself.</strong> Most screen recordings need your voice. Talk through what you are clicking and why. A quiet room beats a good microphone, so close the door and move away from the dishwasher.</li>
          <li><strong>Camera bubble on for people stuff.</strong> The little circle with your face in the corner builds trust fast. Use it for client updates, team messages, and anything where a human face softens the news. Skip it for quick bug reports where the screen is the whole story.</li>
          <li><strong>Do a five second test.</strong> Record a few seconds, play it back, and listen. You want to catch a muted mic or a fan blowing straight into it now, not after the full take.</li>
          </ul>

          <h3>Get a clean take in one shot</h3>
          <ul>
          <li><strong>Close the tabs you do not need.</strong> Fewer distractions for you, fewer questions from the person watching.</li>
          <li><strong>Silence notifications.</strong> Turn on Do Not Disturb or Focus mode. A message popping up mid-recording will end up in the clip, and it always picks the worst moment.</li>
          <li><strong>Write your first line down.</strong> The opening sentence is where most recordings wobble. Know yours before you start. Something plain works: "Here is how to export your monthly report."</li>
          <li><strong>Pause, then start.</strong> Begin the recording, wait two full seconds, then talk. That pause gives you clean room to trim later.</li>
          <li><strong>Stumble small, not big.</strong> If you trip over a sentence, pause and say that sentence again. Fixing one sentence in the edit is easy. Restarting the whole recording every time you say "um" is how a five minute job eats an afternoon.</li>
          </ul>

          <h3>Trim the dead air at the start and end</h3>
          <p>Almost every recording begins with a few seconds of fumbling and ends with you hunting for the stop button. Cut both. Trim the head until your voice or your first click shows up, and trim the tail right after your last useful word. The clip gets shorter, tighter, and much easier to watch. Your viewer will never know the messy parts existed.</p>

          <h3>Download the clip and share it anywhere</h3>
          <p>When the recording looks right, download the video file to your device. From there it behaves like any other file. Drop it into an email, a chat thread, a help doc, or a shared drive. The person on the other end presses play. They do not need an account, an app, or a meeting invite to watch it. Your recording stays on your device.</p>

          <h2>Cost</h2>
          <ul>
          <li>Screen Recorder: free, with no limits.</li>
          </ul>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
