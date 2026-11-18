import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";
import ToolCallout from "../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/how-to-follow-up-with-a-client-who-hasnt-responded/" },
  openGraph: { url: "/how-to-follow-up-with-a-client-who-hasnt-responded/" },
  title: "How to Follow Up With a Client Who Hasn't Responded | Leave It to Bum Bum",
  description: "A client went quiet on your proposal, meeting, or quote? Wait three business days, send a short nudge, then one closing message. Copy-paste templates for the three silences that happen most.",
};

export default function FollowUpWithClientGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell blog-head">
        <p className="kicker">Guide</p>
        <h1>How to Follow Up With a Client Who Hasn't Responded</h1>
        <p className="lede">Silence usually means busy. A short message, sent on the right day, with one easy question, gets the reply.</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="Client Follow-Up Tracker"
            href="/tools/purrsuit/"
            heading="Want the dates and the wording handled?"
            blurb="Client Follow-Up Tracker keeps each client's follow-up date straight and drafts what to say. Free, with no limits."
            cta="Open Client Follow-Up Tracker"
          />

          <h2>What to do</h2>

          <h3>Follow the timing ladder</h3>
          <ul>
          <li><strong>First nudge:</strong> about three business days after you sent the thing. Any sooner reads as impatient, and many clients batch their replies twice a week.</li>
          <li><strong>Second nudge:</strong> about a week after the first. Add one small piece of value or clarity, like a shorter summary or an answer to a question they raised earlier.</li>
          <li><strong>Closing message:</strong> about a week after the second. Say plainly that you will close the file unless they say otherwise. Closing messages earn replies at a surprising rate, because they turn a vague someday into a small decision today.</li>
          <li><strong>After that:</strong> stop. Log the client, set a follow-up date a month or two out if the relationship matters, and let it rest.</li>
          </ul>

          <h3>Template 1: The proposal with no reply</h3>
          <blockquote>
          <p>Hi [Name], following up on the proposal I sent on [day]. Two pages carry most of it: the scope on page [x] and the start date on page [y]. Happy to walk through either one on a short call. If the timing has moved, that is fine too. Just tell me when to check back.</p>
          </blockquote>
          <p>Shorter version, for the second nudge:</p>
          <blockquote>
          <p>Hi [Name], quick check on the proposal. Still useful, or has the timing moved? Either answer helps me plan.</p>
          </blockquote>

          <h3>Template 2: The meeting that never got booked</h3>
          <blockquote>
          <p>Hi [Name], we left [topic] open last time we spoke. I can do [two specific times], or send me a time that suits you better and I will make it work. Fifteen minutes is enough to close it out.</p>
          </blockquote>

          <h3>Template 3: The quote waiting on a yes or no</h3>
          <blockquote>
          <p>Hi [Name], the quote for [job] holds until [date]. A yes lets me book you in for [start window]. A no is fine as well, and I will release the slot. Which way is it leaning?</p>
          </blockquote>

          <h3>The closing message (works for all three)</h3>
          <blockquote>
          <p>Hi [Name], I will close the file on [thing] on my end so it stops sitting in both our inboxes. If it comes back to life later, reply to this thread and we will pick it up from there.</p>
          </blockquote>
          <p>Send it once, mean it, and stop. If they reply, you have a live conversation again. If they do not, you have a clean list and a door left open.</p>

          <h3>Keep the dates from slipping</h3>
          <p>In the <a href="/tools/purrsuit/">Client Follow-Up Tracker</a>, each client carries a follow-up date and a stage (new, talking, quoted, won, lost), and the morning view shows who is due and who has gone quiet, with a draft of what to say. Existing lists import by CSV, up to 200 rows at a time.</p>

          <h2>Cost</h2>
          <ul>
          <li>Sending the messages from your own email: free.</li>
          <li>Client Follow-Up Tracker: free, with no limits, for guests and free accounts alike.</li>
          </ul>

          <h2>FAQs</h2>
          <h3>How long should I wait before following up on no response?</h3>
          <p>About three business days for the first nudge, about a week for the second, then one closing message about a week later. After the closing message, stop and log a later check-in date if the relationship matters.</p>
          <h3>How many times should I follow up before giving up?</h3>
          <p>Three messages after the original is a solid default: two nudges and one closing message. More than that rarely changes the answer and it costs goodwill you may want later.</p>
          <h3>What should a follow-up email say when a client ignores you?</h3>
          <p>Name the thing you sent, point at the one part that matters, ask one question, and make "not now" an acceptable answer. Keep it under five lines so it can be answered from a phone.</p>
          <h3>Is it rude to send a closing or break-up email?</h3>
          <p>No. A polite closing message respects the client's time and yours. It often gets the fastest reply of the whole sequence, because it asks for a small decision instead of an open-ended one.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
