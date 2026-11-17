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
            name="Voice Notes to Text"
            href="/tools/notes/"
            icon="/bum/icons/notes.png"
            blurb="Play a voice memo out loud, record it as it plays, and get a transcript you can copy, search, and send. Free, with no limits."
            cta="Transcribe a voice memo free"
          />
          <p>Open the voice memo on your iPhone, play it out loud, and record it in a browser transcription tool at the same time. Your words come back as a transcript you can copy or download. If the memo is still an idea forming in your head, skip the memo app entirely and record straight into the tool instead, so the transcript exists from the start. <a href="/tools/notes/">Bum Bum's Voice Notes to Text</a> handles both routes: play an existing memo and record it as it plays, or record a fresh note in the browser and get the transcript right away.</p>

          <h2>Why voice memos pile up untranscribed</h2>
          <p>Voice Memos is the fastest capture app on the phone, so everything lands there: client call notes in the car, an idea on a walk, a list you meant to send someone. The problem arrives later. Audio cannot be searched, skimmed, quoted in an email, or pasted into a proposal. A two minute memo holds about thirty seconds of reading, and most people never go back and listen. Transcribing the memo turns a recording you have to replay into text you can use anywhere.</p>

          <h2>Method 1: Transcribe a memo you already recorded</h2>
          <ol>
          <li>Open Voice Memos on your iPhone and pick the recording.</li>
          <li>Open <a href="/tools/notes/">Voice Notes to Text</a> in your browser.</li>
          <li>Start recording in the tool, then press play on the memo. Keep the phone near the microphone and the room quiet.</li>
          <li>When the memo ends, stop the recording. Your transcript appears in your private library, ready to copy or download.</li>
          </ol>
          <p>One honest detail about iPhone: live transcription in the browser does not run on iPhone the way it does on a computer. On iPhone, short pieces of your audio are sent to Bum Bum's server as you speak and transcribed on the spot. The audio itself is never stored. On a computer, recognition happens right on your device and the recording never leaves it.</p>
          <p>A tip for cleaner results: play the memo at a steady volume, and if the original recording has two people talking, expect to tidy a few lines by hand afterward. Transcription is good at clear speech and less good at two voices crossing in a noisy car.</p>

          <h2>Method 2: Skip the memo and record the note as text from the start</h2>
          <p>If you are about to record a new memo, record it in the transcription tool instead:</p>
          <ol>
          <li>Open <a href="/tools/notes/">Voice Notes to Text</a> and hit record.</li>
          <li>Talk the note out. Short notes beat one long ramble: three tidy transcripts are faster to read later than one saga.</li>
          <li>Stop recording. The transcript saves to your library, and you can listen back in the browser or download the audio if you want to keep it.</li>
          </ol>
          <p>Say the structure out loud as you go ("first point", "second point"). The transcript arrives close to a finished draft, so the cleanup afterward is small.</p>

          <h2>What it costs</h2>
          <p>Nothing. Finished notes are free, for guests and free accounts alike, with no limits. Every transcript in your library is ready to copy or download at no extra cost.</p>

          <h2>What to do with the transcript once you have it</h2>
          <ul>
          <li>Paste call notes into the email or document where the decision lives, so they stop being trapped in an audio file.</li>
          <li>Search your own words later. Text is findable; audio is not.</li>
          <li>Send the tidy version to a client or a teammate instead of asking them to listen to a recording.</li>
          <li>Keep the original memo until you have read the transcript once. Then delete the memo with a clear conscience.</li>
          </ul>

          <h2>Frequently asked questions</h2>
          <h3>Can I transcribe iPhone voice memos for free?</h3>
          <p>Yes. It is free for guests and free accounts alike, with no limits. Recording a memo as it plays, or recording a fresh note, both work the same way.</p>
          <h3>Does my recording get stored on a server?</h3>
          <p>On iPhone, short pieces of audio are sent to the server to be transcribed and the audio is never stored. On a computer, your recording stays in your browser and recognition happens on your device. Either way, you can listen back in the browser or download the audio to keep.</p>
          <h3>Can I download the transcript?</h3>
          <p>Yes. Every transcript in your library is ready to copy or download.</p>
          <h3>How is this different from the general voice notes guide?</h3>
          <p>Our broader guide, <a href="/how-to-turn-voice-notes-into-text/">How to turn voice notes into text</a>, covers voice notes in general. This page covers one specific job: getting the recordings already sitting in your iPhone's Voice Memos app into text you can search, copy, and send.</p>

          <h2>The bottom line</h2>
          <p>A voice memo you cannot search is a note you will probably never use again. Play it once, record it as it plays, and keep the transcript. Two minutes of audio becomes thirty seconds of reading, in a form you can paste anywhere.</p>
          <p><a href="/tools/notes/">Transcribe a voice memo free</a></p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
