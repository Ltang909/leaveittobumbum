import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "How to record your screen for free (no software to install) | Leave It to Bum Bum",
  description: "Record your screen right in the browser: a tab, a window, or the full screen, with mic and camera bubble. Practical tips for a clean one-take recording you can send anywhere.",
};

export default function RecordScreenGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">Guide</p>
        <h1>How to record your screen for free (no software to install)</h1>
        <p className="lede">Sometimes showing beats explaining. A two-minute screen recording replaces the meeting, the screenshot chain, and the long thread of confused replies.</p>
      </section>
      <section className="shell">
        <div className="prose">
          <h2>How to record your screen for free (no software to install)</h2>
          <p>Someone asks how the report works. You could book a call, share your screen, and walk them through it live. Or you could record it once, send the clip, and get that half hour back. A screen recording answers the same question for the next ten people who ask, too. Here is how to make one that people actually finish watching.</p>

          <h2>Tab, window, or full screen: what each is for</h2>
          <p>When you start a browser recording, you pick what the camera sees. Each option has a job.</p>
          <ul>
          <li><strong>One browser tab.</strong> Pick this for anything that lives in your browser: a dashboard, a doc, a web app, your store backend. The recording follows that tab, so your other tabs and their embarrassing titles stay out of it.</li>
          <li><strong>One window.</strong> Pick this for a desktop app, like a spreadsheet, a code editor, or a design file. The recording follows that app while you work in it.</li>
          <li><strong>Your full screen.</strong> Pick this when the task jumps between apps, like copying numbers from a spreadsheet into a browser tool. Everything on screen can end up in the video, so treat it like company is coming over and tidy up first.</li>
          </ul>

          <h2>Set up your mic and camera bubble first</h2>
          <p>Two small choices before you record will save you a second take.</p>
          <ul>
          <li><strong>Mic on, unless the clip explains itself.</strong> Most screen recordings need your voice. Talk through what you are clicking and why. A quiet room beats a good microphone, so close the door and move away from the dishwasher.</li>
          <li><strong>Camera bubble on for people stuff.</strong> The little circle with your face in the corner builds trust fast. Use it for client updates, team messages, and anything where a human face softens the news. Skip it for quick bug reports where the screen is the whole story.</li>
          <li><strong>Do a five second test.</strong> Record a few seconds, play it back, and listen. You want to catch a muted mic or a fan blowing straight into it now, not after the full take.</li>
          </ul>

          <h2>Get a clean take in one shot</h2>
          <p>You do not need a perfect take. You need a clear one. A few habits make that easy.</p>
          <ul>
          <li><strong>Close the tabs you do not need.</strong> Fewer distractions for you, fewer questions from the person watching.</li>
          <li><strong>Silence notifications.</strong> Turn on Do Not Disturb or Focus mode. A message popping up mid-recording will end up in the clip, and it always picks the worst moment.</li>
          <li><strong>Write your first line down.</strong> The opening sentence is where most recordings wobble. Know yours before you start. Something plain works: "Here is how to export your monthly report."</li>
          <li><strong>Pause, then start.</strong> Begin the recording, wait two full seconds, then talk. That pause gives you clean room to trim later.</li>
          <li><strong>Stumble small, not big.</strong> If you trip over a sentence, pause and say that sentence again. Fixing one sentence in the edit is easy. Restarting the whole recording every time you say "um" is how a five minute job eats an afternoon.</li>
          </ul>

          <h2>Trim the dead air at the start and end</h2>
          <p>Almost every recording begins with a few seconds of fumbling and ends with you hunting for the stop button. Cut both. Trim the head until your voice or your first click shows up, and trim the tail right after your last useful word. The clip gets shorter, tighter, and much easier to watch. Your viewer will never know the messy parts existed.</p>

          <h2>Download the clip and share it anywhere</h2>
          <p>When the recording looks right, download the video file to your device. From there it behaves like any other file. Drop it into an email, a chat thread, a help doc, or a shared drive. The person on the other end presses play. They do not need an account, an app, or a meeting invite to watch it.</p>

          <h2>The easier way</h2>
          <p>Everything above is what <a href="/tools/clips/">Screen Recorder</a> does right in your browser. You pick a tab, window, or full screen, add your mic and camera bubble if you want them, record, trim the ends, and download the file. Your recording stays on your device, ready to send wherever it needs to go.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
