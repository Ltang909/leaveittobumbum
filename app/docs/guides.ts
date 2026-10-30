// Guide documentation content: the non-tool docs pages.
// CADENCE RULE: a new feature does not ship without its guide here (or a
// tool entry in tool-docs.ts), in the same change. The docs sidebar, docs
// home, sitemap entries, and the page itself are generated from this file.
// Inline markup supported in text: **bold** and [label](/path/).
export type Block =
  | { h: string }
  | { p: string }
  | { ul: string[] }
  | { note: string }
  | { faq: Array<[string, string]> };

export interface GuideDoc {
  slug: string;
  section: string;
  title: string;
  summary: string;
  updated: string;
  blocks: Block[];
}

const U = "October 27, 2026";

export const GUIDES: GuideDoc[] = [
  {
    slug: "how-bum-bum-works",
    section: "Getting started",
    title: "How Bum Bum works",
    summary:
      "Leave It to Bum Bum is a toolbox of tiny tools that each do one annoying business task well. Tell the front desk what you need, or pick a tool yourself, hand over the messy input, and get back a finished result.",
    updated: U,
    blocks: [
      { h: "Two ways in" },
      { p: "The front door is **the desk** on the [homepage](/). Describe the task in your own words, drop in a file or a photo, and Bum Bum routes it to the right tool. If you already know the tool, pin it with an @-mention and your words go straight there, no routing involved." },
      { p: "The second way is the [toolbox](/tools/): every tool on its own page, each doing exactly one job. Most people start at the desk and end up with two or three favorite tools they visit directly." },
      { h: "What happens to your input" },
      { p: "Most tools run **right in your browser**: your video, images, and receipts never leave your device. Tools that think with AI (a poster read, a content pack) send just that input to be processed and discard it after. Every tool's docs page says plainly which kind it is, under Your data." },
      { h: "Guests and accounts" },
      { p: "You can poke around as a guest, no signup, and finished results spend from a small lifetime guest allowance. A free account raises that to a monthly bucket of actions and, more importantly, keeps your stuff: trackers, logs, and packs live in your account and follow you between devices. See [What is an action?](/docs/actions/) and [Plans and pricing](/docs/plans/)." },
      { note: "The one-sentence version: mess in, magic out. You hand over the fiddly task; Bum Bum hands back the finished thing." },
    ],
  },
  {
    slug: "actions",
    section: "Getting started",
    title: "What is an action?",
    summary:
      "An action is one completed result: a trimmed video, a read receipt, a chased invoice. Clicking around, previewing, and setting things up are free. You only spend an action when Bum Bum finishes something useful, and if a job fails, it costs nothing.",
    updated: "October 30, 2026",
    blocks: [
      { h: "The rules of the meter" },
      { ul: [
        "**One bucket for everything.** Your whole team and every tool share the same monthly actions. No per-tool limits, no per-seat math.",
        "**Finished work only.** A job that fails or that you abandon costs nothing. The meter moves when a result lands.",
        "**Monthly reset.** Actions reset on your billing date and do not roll over.",
        "**Fair warning.** You get a nudge at 80% and again at 100%, so the meter never surprises you mid-task.",
      ] },
      { h: "Guests" },
      { p: "Guests get a small lifetime allowance of actions to feel the tools out, no card and no signup. When it runs out, a free account refills you monthly. Creating the account is the whole pitch: your trackers and logs need somewhere to live." },
      { h: "Running out mid-month" },
      { p: "Tools pause politely and tell you. On Poke around you can wait for the reset, or move to Operator, which never runs out. Your data and settings stay put either way. Plan allowances are on the [plans page](/docs/plans/)." },
    ],
  },
  {
    slug: "plans",
    section: "Getting started",
    title: "Plans and pricing",
    summary:
      "Two plans, one shared meter. Poke around is free with 75 actions a month. Operator is $49 a month with unlimited actions, unlimited team members, and the 36-hour custom tool promise.",
    updated: "October 30, 2026",
    blocks: [
      { h: "The plans" },
      { ul: [
        "**Poke around, $0.** 75 actions a month, all current tools, 1 workspace, and the community request queue. No card needed.",
                "**Operator, $49/mo.** Unlimited actions, everything in Poke around, unlimited team members, and one scoped tool request each month with the [36-hour guarantee](/docs/the-36-hour-promise/).",
      ] },
      { h: "Switching, pausing, cancelling" },
      { p: "Upgrades apply immediately; downgrades take effect at the next billing date. Pause or cancel anytime from your account page, through the billing portal, in about two clicks. No contracts, no cancellation maze, and you keep what you paid for until the end of the billing period. The full pricing page, with the meter explained, is [here](/pricing/)." },
    ],
  },
  {
    slug: "teams",
    section: "Getting started",
    title: "Teams and seats",
    summary:
      "Operator workspaces fit unlimited team members, and Operator actions are unlimited too. The plan is billed to the team owner.",
    updated: "October 30, 2026",
    blocks: [
      { h: "How teams work" },
      { ul: [
        "**Shared meter.** Every member's finished work draws from the owner's plan: one monthly allowance on Poke around, unlimited on Operator. There is no per-seat action math.",
        "**Invite by link.** Invite people from your account page with a shareable link. No email back-and-forth required.",
        "**One bill.** The plan is billed to the team owner. Members never see a payment screen.",
        "**Shared work.** Trackers and logs belong to the workspace, so the team sees the same subscription list, the same follow-ups, the same receipt log.",
      ] },
      { p: "Free (Poke around) workspaces are solo: 1 workspace, just you. Upgrading to Operator is what opens the seats up." },
    ],
  },
  {
    slug: "the-four-trackers",
    section: "Trackers and email",
    title: "The four trackers",
    summary:
      "Four of the tools are trackers that remember things so you do not have to: subscriptions, client follow-ups, job applications, and invoices. Together they feed the Monday note, your weekly roundup email.",
    updated: U,
    blocks: [
      { h: "Meet the trackers" },
      { ul: [
        "**[Subscription Tracker](/docs/tools/cutline/)** keeps every recurring charge, what it costs, and when it renews. Renewal dates roll forward on their own, because a recurring subscription is assumed to have renewed.",
        "**[Client Follow-Up Tracker](/docs/tools/purrsuit/)** is a tiny CRM: who you are talking to, where it stands, and who needs a nudge today.",
        "**[Job Application Tracker](/docs/tools/corporate-bum-bum/)** tracks roles through the pipeline and tells you who to follow up with each morning.",
        "**[Invoice Reminder](/docs/tools/invoice-chaser/)** tracks who owes you what, drafts the chase email, and learns when each client usually pays.",
      ] },
      { h: "What they share" },
      { p: "Adding an entry to any tracker uses one action; everything after that, editing, moving, checking, is free. And whatever comes due in a tracker shows up in [the Monday note](/docs/the-monday-note/) for that week, so one email covers all four." },
    ],
  },
  {
    slug: "the-monday-note",
    section: "Trackers and email",
    title: "The Monday note",
    summary:
      "The Monday note is a weekly email for account holders, sent Monday mornings. If your trackers have anything renewing, due, or waiting on a follow-up that week (Monday through Sunday), it rounds it all up in one place. If nothing is coming up, you get a short quiet-week note instead, at most every other week.",
    updated: U,
    blocks: [
      { h: "What is in it" },
      { ul: [
        "**Subscriptions renewing** that week, with the total about to go out.",
        "**Invoices to chase**: due this week, plus any already overdue.",
        "**Client follow-ups** that are due, including the overdue ones you have been avoiding.",
        "**Job application follow-ups** that come due that week.",
      ] },
      { p: "Each section links straight to its tracker, so the email is a starting line, not a report you file away." },
      { h: "Quiet weeks" },
      { p: "Nothing coming up? Most weeks like that you will hear nothing at all. Now and then, at most every other week, a one-line quiet note confirms Bum Bum checked and your week is genuinely clear. No streaks, no content marketing in disguise, just the all-clear." },
      { h: "Who gets it, and how to stop it" },
      { p: "The Monday note goes to account holders who use the trackers. Guests never get email. Turn it off any time from the **Monday note panel on your [account page](/account/)**, or from the unsubscribe link at the bottom of any note. Turning it off changes nothing else about your account." },
      { note: "One note a week, only from your own trackers. Nothing else, ever. That is the whole email program." },
    ],
  },
  {
    slug: "the-day-log-recap",
    section: "Trackers and email",
    title: "The Day Log recap",
    summary:
      "The Day Log recap is a weekday-morning email. Once you have filed a log in Day Log, the next weekday morning brings your most recent log back as clean jot notes: the previous list scored, what got done, and your next top priorities. Monday's recap covers Friday.",
    updated: U,
    blocks: [
      { h: "How the loop starts" },
      { p: "No log, no emails, ever. The loop starts the first time you file a day in [Day Log](/docs/tools/day-log/), and every recap is built from your own filed logs, never from anything else." },
      { h: "When it sends" },
      { ul: [
        "**Weekday mornings only**, Monday to Friday. Nothing sends on weekends.",
        "**Monday recaps Friday.** Each run covers your most recent log that has not been emailed yet, so Friday's log lands in your inbox on Monday morning.",
        "**The latest log wins.** If two logs pile up before a weekday morning (say, Saturday and Sunday), the recap covers the newest one. The other stays in Past logs on the site; it just is not emailed.",
      ] },
      { h: "Missing a day" },
      { p: "Miss a day and the next weekday morning brings one short nudge instead of a recap. If you still have not logged by that evening, the emails stop entirely. No streak guilt, no daily nagging. File a log any night and the recaps pick right back up the next weekday morning." },
      { h: "How to stop it" },
      { p: "There is no separate switch, because there is nothing to switch off: stop filing logs and the emails stop on their own, after the single nudge. The recap goes only to your own account address." },
      { note: "Your words only, on weekday mornings, driven entirely by what you filed. That is the whole program." },
    ],
  },
  {
    slug: "requesting-a-tool",
    section: "Getting a tool built",
    title: "Requesting a tool",
    summary:
      "Missing a tool? There are two lanes. The community queue is free: your request joins a public list where everyone can upvote it, and the most-wanted tools get built. Operator members get a private lane: one scoped request a month, built within 36 hours.",
    updated: U,
    blocks: [
      { h: "The community queue" },
      { p: "Hit **Request a tool** anywhere on the site, or browse the [public queue](/requests/). Requests are visible to everyone, anyone can upvote, and we build the most-wanted tools as fast as we can. There is no timeline promise on this lane and no cost. If you are not signed in, we will nudge you to peek at the toolbox first (your task might already be solved) and make a free account so your request has a home." },
      { h: "The Operator lane" },
      { p: "Operator members can send one scoped request a month down the fast lane: agreed scope, built within 36 hours, or the next month is free. Operator requests also appear in the public queue with an operator badge, so everyone can follow along. The promise, its clock rules, and what fits in it are spelled out on [the 36-hour promise page](/docs/the-36-hour-promise/)." },
      { h: "What makes a good request" },
      { ul: [
        "Name the annoying task, not the software you imagine solving it. 'Turn my supplier PDF invoices into a spreadsheet' beats 'build an invoice app'.",
        "Say what goes in and what should come out. One example input and output is worth a page of description.",
        "One task per request. Two tasks means two requests, and both move faster for it.",
      ] },
    ],
  },
  {
    slug: "the-36-hour-promise",
    section: "Getting a tool built",
    title: "The 36-hour promise",
    summary:
      "Operator members get one scoped tool request each month. Once the tiny, useful version is agreed, Bum Bum ships it within 36 hours, or the next month is on us, credited automatically.",
    updated: U,
    blocks: [
      { h: "How it runs" },
      { ul: [
        "Tell us the one annoying task, from your account page or the request form.",
        "We reply with the tiny, useful version we can actually build. Nothing vague ships.",
        "The 36-hour clock starts when scope **and** access are confirmed.",
        "The tool lands in your toolbox, tested and working.",
      ] },
      { h: "Clock rules, in plain English" },
      { ul: [
        "Weekends and US federal holidays do not count.",
        "The clock pauses while we are waiting on you, for an answer, a login, or an example file.",
        "Miss the window and your next month is free, credited automatically. No forms, no begging; the credit shows up on your account page.",
      ] },
      { h: "What fits in 36 hours" },
      { p: "One focused workflow that can be built in about four working hours, using approved existing services where it makes sense. It cannot include regulated data (health records, finance credentials, that neighborhood), complex data migrations, mobile app store review timelines, or work stuck waiting on a third party." },
      { p: "Bigger idea? We will carve out the most useful slice, ship that inside the promise, and tell you honestly what the rest would take." },
    ],
  },
  {
    slug: "account-and-billing",
    section: "Account and help",
    title: "Your account and billing",
    summary:
      "Your account page is mission control: your action meter, your plan, your team, your trackers' attention list, and billing, all in one place.",
    updated: U,
    blocks: [
      { h: "What lives on the account page" },
      { ul: [
        "**Your meter:** actions used and remaining this month, across the whole toolbox.",
        "**Needs your attention:** the tracker items asking for you soonest, pulled from all four trackers.",
        "**The Monday note panel:** see whether the weekly email is on for you, and turn it off or back on. More on [the Monday note](/docs/the-monday-note/).",
        "**Plan and billing:** change plans, manage payment, or cancel, through the billing portal.",
      ] },
      { h: "Billing changes" },
      { p: "Upgrades apply immediately. Downgrades take effect at the next billing date. Cancelling takes about two clicks through the billing portal, and you keep what you paid for until the end of the billing period. Action pack top-ups ($6 per 1,000) apply right away and draw down after your monthly bucket." },
      { h: "Signing in" },
      { p: "Your account is your email. Keep the email current, because the Monday note and billing receipts go there, and because your trackers, logs, and packs are all attached to it." },
    ],
  },
  {
    slug: "privacy-and-your-data",
    section: "Account and help",
    title: "Privacy and your data",
    summary:
      "The short version: most tools run in your browser so your files never reach us at all. Tools that process with AI receive only that input and discard it after. What you save in trackers and logs is yours, stored in your account, and never used to train AI models.",
    updated: U,
    blocks: [
      { h: "Files and photos" },
      { ul: [
        "**In-browser tools** (Recorder, Trimmer, Converter, Cropper, Sketchpad, Receipt Scanner, Resume Gap Checker, Video MP3 Overlay): files are processed on your device and never uploaded.",
        "**AI-read tools** (Poster to Calendar, Meal Scanner): your photo is sent to the reader, read, and discarded. It is never stored.",
        "**Audio tools** (Voice to Content, Audiogram Maker): your audio is transcribed and never kept. Voice Notes recordings stay in your browser, except on iPhone, where transcription needs a server round-trip.",
      ] },
      { h: "What we do store" },
      { p: "What you deliberately save: tracker entries, your receipt log (the typed-up numbers, never the photos), transcripts you keep, content packs, and your scans. That data lives in your account so it is there on every device, and it is how the Monday note knows what to round up." },
      { h: "What we never do" },
      { ul: [
        "Your workspace contents are never used to train AI models.",
        "Your data is not sold or shared for advertising.",
        "No tool contacts your clients, employers, or anyone else without you reviewing and approving it first.",
      ] },
      { p: "This page is the plain-English summary. The legal version lives at [Privacy](/privacy/), and questions reach a human (and a cat) at hello@leaveittobumbum.com." },
    ],
  },
  {
    slug: "faq",
    section: "Account and help",
    title: "Questions, probably answered",
    summary:
      "The questions everybody asks, answered the way we would want them answered: shortly, and with the actual number in it.",
    updated: "October 30, 2026",
    blocks: [
      { faq: [
        ["Do I need an account?", "No. Guests can use the tools right away with a small lifetime allowance of actions. A free account gives you 75 actions a month and somewhere for your trackers, logs, and packs to live."],
        ["Do unused actions roll over?", "No. The bucket refills fresh each month, which is how we keep the prices this low."],
        ["What happens if I hit 100% mid-month?", "Tools pause politely and tell you. Wait for the reset, or move to Operator, which never runs out. Your data and settings stay put either way."],
        ["Can I switch plans later?", "Yes. Upgrades apply immediately, downgrades at the next billing date. Either way takes about ten seconds."],
        ["Is my data used to train AI models?", "No. Your workspace contents are yours. Most files never even leave your device."],
        ["How do I cancel?", "From your account page, through the billing portal: two clicks. You keep what you paid for until the end of the billing period."],
        ["Can Bum Bum cancel my subscriptions for me?", "No tool cancels things with outside services. The Subscription Tracker makes sure you remember in time; the cancelling click is yours."],
        ["What if the tool I need does not exist?", "Request it. The community queue is free and public, and Operator members get one scoped build a month inside the 36-hour promise."],
      ] },
    ],
  },
  {
    slug: "troubleshooting",
    section: "Account and help",
    title: "When something goes sideways",
    summary:
      "Most Bum Bum problems have boring causes and quick fixes. Here are the ones we actually see, in the order we see them.",
    updated: "October 30, 2026",
    blocks: [
      { h: "“Out of actions”" },
      { p: "As a guest, you have used up the lifetime guest allowance; a free account refills you with 75 actions a month. On an account, your monthly bucket is spent: wait for your billing-date reset, or move to Operator and stop counting. Your data is never locked or deleted for this. See [What is an action?](/docs/actions/)." },
      { h: "A photo will not read (receipts, posters, meals)" },
      { ul: [
        "Flat surface, good light, and the subject filling the frame fixes most failed reads.",
        "Glare across the important numbers is the usual culprit. Move the light, not the camera settings.",
        "Test with the sample on the tool's page (Receipt Scanner has one). If the sample reads and yours does not, it is the photo, not the tool.",
        "Still stuck? Crop the photo square-on with [Image Cropper](/docs/tools/image-cropper/) and try again.",
      ] },
      { h: "A file will not convert or trim" },
      { p: "In-browser media tools can only handle formats your browser can play. An exotic codec in an .mp4 costume is the usual suspect; converting the file once on a desktop browser, or re-exporting it from the app that made it, sorts it out. Very large files are also limited by your device's memory, not by us." },
      { h: "Screen Recorder says it needs a desktop browser" },
      { p: "Mobile browsers do not let any website capture the screen. Record on a laptop or desktop; the downloaded clip works everywhere after that." },
      { h: "Voice Notes on iPhone behaves differently" },
      { p: "iPhone browsers cannot transcribe live, so on iPhone your audio is sent to our server to transcribe instead of happening on-device. The transcript lands in your library the same way. Details on the [Voice Notes to Text](/docs/tools/notes/) page." },
      { note: "None of that it? Email hello@leaveittobumbum.com with what you were doing and what the tool said. Error screens in the tools are written to be quoted, so copy the words." },
    ],
  },
];
