import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";
import ToolCallout from "../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/how-to-transcribe-iphone-voice-memos-to-text/" },
  openGraph: { url: "/how-to-transcribe-iphone-voice-memos-to-text/" },
  title: "How to Transcribe iPhone Voice Memos to Text | Leave It to Bum Bum",
  description: "Play a voice memo out loud, record it as it plays, and get a transcript you can copy, search, and send. Two methods: transcribe a memo you already recorded, or record the note as text from the start.",
};

export default function TranscribeIphoneVoiceMemosGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell blog-head">
        <p className="kicker">Guide</p>
        <h1>How to Transcribe iPhone Voice Memos to Text</h1>
        <p className="lede">Voice Memos catches every idea you have on the move. Transcription turns those recordings into text you can search, copy, and send.</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="Voice Notes to Text"
            href="/tools/notes/"
            heading="Just want your voice memo as text?"
            blurb="Voice Notes to Text does the transcribing for you. Free, with no limits."
            cta="Open Voice Notes to Text"
          />

          <h2>Methods</h2>

          <h3>Transcribe a memo you already recorded</h3>
          <p>Open the memo on your iPhone, play it out loud, and record it in <a href="/tools/notes/">Voice Notes to Text</a> as it plays. Your words come back as a transcript in your library, ready to copy or download. This is the route for the recordings already sitting in Voice Memos: client call notes, an idea on a walk, a list you meant to send someone.</p>

          <h3>Record the note as text from the start</h3>
          <p>If the note is still in your head, skip the memo app and record straight into Voice Notes to Text instead. The transcript exists from the start and saves to your library, and you can listen back in the browser or download the audio if you want to keep it. This suits new notes you have not recorded yet.</p>

          <h2>Cost</h2>
          <ul>
          <li>Transcribe a memo you already recorded: free, for guests and free accounts alike, with no limits.</li>
          <li>Record the note as text from the start: free, with no limits.</li>
          </ul>

          <h2>What to do</h2>

          <h3>Transcribe a memo you already recorded</h3>
          <ol>
          <li>Open Voice Memos on your iPhone and pick the recording.</li>
          <li>Open <a href="/tools/notes/">Voice Notes to Text</a> in your browser.</li>
          <li>Start recording in the tool, then press play on the memo. Keep the phone near the microphone and the room quiet.</li>
          <li>When the memo ends, stop the recording. Your transcript appears in your private library, ready to copy or download.</li>
          </ol>

          <h3>Record the note as text from the start</h3>
          <p>If you are about to record a new memo, record it in the transcription tool instead:</p>
          <ol>
          <li>Open <a href="/tools/notes/">Voice Notes to Text</a> and hit record.</li>
          <li>Talk the note out. Short notes beat one long ramble: three tidy transcripts are faster to read later than one saga.</li>
          <li>Stop recording. The transcript saves to your library, and you can listen back in the browser or download the audio if you want to keep it.</li>
          </ol>

          <h2>FAQs</h2>
          <h3>Can I transcribe iPhone voice memos for free?</h3>
          <p>Yes. It is free for guests and free accounts alike, with no limits. Recording a memo as it plays, or recording a fresh note, both work the same way.</p>
          <h3>Does my recording get stored on a server?</h3>
          <p>On iPhone, short pieces of audio are sent to the server to be transcribed and the audio is never stored. On a computer, your recording stays in your browser and recognition happens on your device. Either way, you can listen back in the browser or download the audio to keep.</p>
          <h3>Can I download the transcript?</h3>
          <p>Yes. Every transcript in your library is ready to copy or download.</p>
          <h3>How is this different from the general voice notes guide?</h3>
          <p>Our broader guide, <a href="/how-to-turn-voice-notes-into-text/">How to turn voice notes into text</a>, covers voice notes in general. This page covers one specific job: getting the recordings already sitting in your iPhone's Voice Memos app into text you can search, copy, and send.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
