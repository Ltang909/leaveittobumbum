import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

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
      <section className="page-head shell">
        <p className="kicker">Guide</p>
        <h1>How to Follow Up With a Client Who Hasn't Responded</h1>
        <p className="lede">Silence usually means busy. A short message, sent on the right day, with one easy question, gets the reply.</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          
          <p>Send a short message that names the thing you sent, gives the client one easy reply, and offers a graceful way to say the timing changed. Wait about three business days before the first nudge, about a week before the second, then send one short closing message and stop. Templates for the three silences that happen most often are below: a proposal with no reply, a meeting that never got booked, and a quote waiting on a yes or no. A tracker like <a href="/tools/purrsuit/">Leave It To Bum Bum's Client Follow-Up Tracker</a> keeps the dates straight and drafts the wording, so the message goes out on the day it is due.</p>

          <h2>First, read the silence correctly</h2>
          <p>Most unanswered emails mean busy. A client who wanted to say no usually says it, because no ends the thread. Silence means your message is sitting under forty newer ones, or the decision needs someone else's approval, or the timing slipped and nobody has told you yet. Write for that reader: someone who meant to reply and will be glad the reply is easy.</p>
          <p>That changes the message in three ways. Keep it short enough to answer from a phone. Ask one question only. Make "not now" a welcome answer, because a clear not-now restarts the conversation later, while silence just ends it.</p>

          <h2>The timing ladder</h2>
          <ul>
          <li><strong>First nudge:</strong> about three business days after you sent the thing. Any sooner reads as impatient, and many clients batch their replies twice a week.</li>
          <li><strong>Second nudge:</strong> about a week after the first. Add one small piece of value or clarity, like a shorter summary or an answer to a question they raised earlier.</li>
          <li><strong>Closing message:</strong> about a week after the second. Say plainly that you will close the file unless they say otherwise. Closing messages earn replies at a surprising rate, because they turn a vague someday into a small decision today.</li>
          <li><strong>After that:</strong> stop. Log the client, set a follow-up date a month or two out if the relationship matters, and let it rest.</li>
          </ul>

          <h2>Template 1: The proposal with no reply</h2>
          <blockquote>
          <p>Hi [Name], following up on the proposal I sent on [day]. Two pages carry most of it: the scope on page [x] and the start date on page [y]. Happy to walk through either one on a short call. If the timing has moved, that is fine too. Just tell me when to check back.</p>
          </blockquote>
          <p>Why it works: it points at the exact pages instead of asking them to reread the whole document, and the last two sentences make any reply a good reply.</p>
          <p>Shorter version, for the second nudge:</p>
          <blockquote>
          <p>Hi [Name], quick check on the proposal. Still useful, or has the timing moved? Either answer helps me plan.</p>
          </blockquote>

          <h2>Template 2: The meeting that never got booked</h2>
          <blockquote>
          <p>Hi [Name], we left [topic] open last time we spoke. I can do [two specific times], or send me a time that suits you better and I will make it work. Fifteen minutes is enough to close it out.</p>
          </blockquote>
          <p>Why it works: two named times are easier to answer than "let me know when you are free." If neither time works, most people reply with one that does.</p>

          <h2>Template 3: The quote waiting on a yes or no</h2>
          <blockquote>
          <p>Hi [Name], the quote for [job] holds until [date]. A yes lets me book you in for [start window]. A no is fine as well, and I will release the slot. Which way is it leaning?</p>
          </blockquote>
          <p>Why it works: the hold date is real information, and naming the slot gives the decision a cost for waiting that a plain "checking in" never does.</p>

          <h2>The closing message (works for all three)</h2>
          <blockquote>
          <p>Hi [Name], I will close the file on [thing] on my end so it stops sitting in both our inboxes. If it comes back to life later, reply to this thread and we will pick it up from there.</p>
          </blockquote>
          <p>Send it once, mean it, and stop. If they reply, you have a live conversation again. If they do not, you have a clean list and a door left open.</p>

          <h2>Keep the dates from slipping</h2>
          <p>Templates are the easy half. The half that fails is sending them on time. In the <a href="/tools/purrsuit/">Client Follow-Up Tracker</a>, each client carries a follow-up date and a stage (new, talking, quoted, won, lost), and the morning view shows who is due and who has gone quiet, with a draft of what to say. Adding a contact uses one action, and everything else is free. Guests start with 15 free actions, and a free account gets 75 actions every month. Existing lists import by CSV, up to 200 rows at a time.</p>
          <p>Our companion guide, How to remember to follow up with clients, covers the daily system behind this: capture the next step before you close a conversation, give it a real date, and check one list once a day.</p>

          <h2>Frequently asked questions</h2>
          <h3>How long should I wait before following up on no response?</h3>
          <p>About three business days for the first nudge, about a week for the second, then one closing message about a week later. After the closing message, stop and log a later check-in date if the relationship matters.</p>
          <h3>How many times should I follow up before giving up?</h3>
          <p>Three messages after the original is a solid default: two nudges and one closing message. More than that rarely changes the answer and it costs goodwill you may want later.</p>
          <h3>What should a follow-up email say when a client ignores you?</h3>
          <p>Name the thing you sent, point at the one part that matters, ask one question, and make "not now" an acceptable answer. Keep it under five lines so it can be answered from a phone.</p>
          <h3>Is it rude to send a closing or break-up email?</h3>
          <p>No. A polite closing message respects the client's time and yours. It often gets the fastest reply of the whole sequence, because it asks for a small decision instead of an open-ended one.</p>

          <h2>The bottom line</h2>
          <p>Silence usually means busy, so make replying take ten seconds. Name the thing, ask one question, welcome a not-now, and follow the ladder: three days, a week, a closing message, then stop. The replies you want come from easy messages sent on the right day.</p>
          <p><a href="/tools/purrsuit/">Try the Client Follow-Up Tracker free</a></p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
