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
    updated: "November 17, 2026",
    blocks: [
      { h: "Two ways in" },
      { p: "The front door is **the desk** on the [homepage](/). Describe the task in your own words, drop in a file or a photo, and Bum Bum routes it to the right tool. If you already know the tool, pin it with an @-mention and your words go straight there, no routing involved." },
      { p: "The second way is the [toolbox](/tools/): every tool on its own page, each doing exactly one job. Most people start at the desk and end up with two or three favorite tools they visit directly." },
      { h: "What happens to your input" },
      { p: "Most tools run **right in your browser**: your video, images, and receipts never leave your device. Tools that think with AI (a poster read, a content pack) send just that input to be processed and discard it after. Every tool's docs page says plainly which kind it is, under Your data." },
      { h: "Guests and accounts" },
      { p: "You can poke around as a guest, no signup, and every tool is free with no limits either way. A free account matters for a different reason: it keeps your stuff. Trackers, logs, and packs live in your account and follow you between devices. See [Free, with no meter](/docs/actions/) and [Pricing](/docs/plans/)." },
      { note: "The one-sentence version: mess in, magic out. You hand over the fiddly task; Bum Bum hands back the finished thing." },
    ],
  },
  {
    slug: "chaining-tools",
    section: "Getting started",
    title: "Chaining tools together",
    summary:
      "Most jobs are really two or three tiny tasks in a trench coat. Every tool page ends with the natural next steps, so a merged PDF becomes page images, a receipt becomes a tracked subscription, and a voice note becomes a content pack without a trip back to the toolbox.",
    updated: "November 17, 2026",
    blocks: [
      { h: "Why tools suggest a next step" },
      { p: "Real tasks rarely end where they start. You merge a contract packet, then someone asks for the pages as images. You scan a receipt, then the subscription hiding on it needs tracking. Rather than send you back to the [toolbox](/tools/) to hunt, every tool page finishes with a **next task** strip: three follow-on tools picked for the job you just did, in the order people usually need them." },
      { h: "How the suggestions are picked" },
      { p: "They are chosen by hand: the follow-ons are the tools that take the output of the one you are using as their input, or that finish the job it started. [PDF Merger](/tools/pdf-merger/) points at [PDF to Image](/tools/pdf-to-image/), the [PDF Compressor](/tools/pdf-compressor/) and the [Receipt Scanner](/tools/receipt-reader/); the [Receipt Scanner](/tools/receipt-reader/) points at the [Subscription Tracker](/tools/cutline/) and the [Invoice Reminder](/tools/invoice-chaser/). If none of them fit, the whole toolbox is one click away in the header." },
      { h: "It costs nothing to look" },
      { p: "Opening a suggested tool is free, and so is finishing the job in it: every tool is [free with no limits](/docs/actions/). Following a chain just to see where it leads costs nothing but the clicks." },
      { note: "The strip only suggests. Your work stays in the tool you put it in until you hand it to the next one yourself." },
    ],
  },
  {
    slug: "actions",
    section: "Getting started",
    title: "Free, with no meter",
    summary:
      "There is no meter. Every tool is free, for guests and accounts alike, with no usage counts, no monthly bucket, and no limits. This page keeps the old address because the old model had a name you might still see in older posts: actions.",
    updated: "November 17, 2026",
    blocks: [
      { h: "The short version" },
      { p: "Use any tool as many times as you like. Nothing runs out, nothing resets, and nothing warns you at 80%. Guests and account holders get the same tools at the same price: free." },
      { h: "What changed" },
      { p: "Until November 17, 2026, finished jobs spent units called actions: guests had a small lifetime allowance, free accounts had a monthly bucket, and a paid plan removed the cap. That model is retired. If an older post or page still talks about action limits, it is out of date; the [pricing page](/pricing/) is the current word." },
      { h: "So what is an account for?" },
      { p: "A home for your stuff. Trackers, logs, packs, and saved work live in your account and follow you between devices. Guests' work lives in their browser session instead. The tools themselves work the same either way." },
      { h: "The one paid thing" },
      { p: "Asking Bum Bum to build a tool that does not exist yet. That is the [36-hour Promise](/docs/the-36-hour-promise/): one scoped request, $49 one-time, or the free [community queue](/requests/) if you are not in a hurry." },
    ],
  },
  {
    slug: "plans",
    section: "Getting started",
    title: "Pricing",
    summary:
      "There are no plans. Every tool is free with no limits, for guests and accounts alike. The only paid offer is the 36-hour Promise: one scoped custom tool built on request, $49 one-time per request.",
    updated: "November 17, 2026",
    blocks: [
      { h: "Tool use: free" },
      { p: "Every tool, unlimited use, no card, no trial clock, no subscription. An account is optional and also free; it exists to hold your trackers, logs, and saved work. The full pricing page is [here](/pricing/)." },
      { h: "The 36-hour Promise" },
      { p: "When the tool you need does not exist yet, one scoped request down the [Promise lane](/docs/the-36-hour-promise/) gets it built within 36 hours of agreed scope, for a one-time $49. Or use the free [community queue](/requests/) and let the votes pile up." },
      { h: "Existing subscriptions" },
      { p: "Paid plans (Helper, Operator) were retired on November 17, 2026 and are no longer sold. If you still hold one, your tools are free either way; manage or cancel the subscription from your account page, through the billing portal, and it runs until the end of its billing period." },
    ],
  },
  {
    slug: "teams",
    section: "Getting started",
    title: "Teams and seats",
    summary:
      "Team members share one workspace: the same trackers and logs, every tool free for everyone, and no seat caps.",
    updated: "November 17, 2026",
    blocks: [
      { h: "How teams work" },
      { ul: [
        "**No meter to share.** Every tool is free with no limits, for the owner and every member alike. There is no per-seat math of any kind.",
        "**Invite by link.** Invite people from your account page with a shareable link. No email back-and-forth required.",
        "**No bill.** Tool use costs nothing, so there is nothing to bill to the owner, and members never see a payment screen.",
        "**Shared work.** Trackers and logs belong to the workspace, so the team sees the same subscription list, the same follow-ups, the same receipt log.",
      ] },
      { p: "Under the old plans, free workspaces were solo and seats were a paid feature. Seats are open now: invite your team from your account page whenever you like." },
    ],
  },
  {
    slug: "the-four-trackers",
    section: "Trackers and email",
    title: "The four trackers",
    summary:
      "Four of the tools are trackers that remember things so you do not have to: subscriptions, client follow-ups, job applications, and invoices. Together they feed the Monday note, your weekly roundup email.",
    updated: "November 17, 2026",
    blocks: [
      { h: "Meet the trackers" },
      { ul: [
        "**[Subscription Tracker](/docs/tools/cutline/)** keeps every recurring charge, what it costs, and when it renews. Renewal dates roll forward on their own, because a recurring subscription is assumed to have renewed.",
        "**[Client Follow-Up Tracker](/docs/tools/purrsuit/)** is a tiny CRM: who you are talking to, where it stands, and who needs a nudge today.",
        "**[Job Application Tracker](/docs/tools/corporate-bum-bum/)** tracks roles through the pipeline and tells you who to follow up with each morning.",
        "**[Invoice Reminder](/docs/tools/invoice-chaser/)** tracks who owes you what, drafts the chase email, and learns when each client usually pays.",
      ] },
      { h: "What they share" },
      { p: "Adding an entry to any tracker is free, and so is everything after that: editing, moving, checking. And whatever comes due in a tracker shows up in [the Monday note](/docs/the-monday-note/) for that week, so one email covers all four." },
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
      { p: "Each section links straight to its tracker, so you can act on the email right away." },
      { h: "Quiet weeks" },
      { p: "Nothing coming up? Most weeks like that you will hear nothing at all. Now and then, at most every other week, a one-line quiet note confirms Bum Bum checked and your week is genuinely clear. No streaks, no content marketing in disguise, just the all-clear." },
      { h: "Who gets it, and how to stop it" },
      { p: "The Monday note goes to account holders who use the trackers. Guests never get email. One note per email address: if you somehow have two accounts under the same email, their trackers round up into a single note. Turn it off any time from the **Monday note panel on your [account page](/account/)**, or from the unsubscribe link at the bottom of any note. Turning it off changes nothing else about your account." },
      { note: "One note a week, only from your own trackers. Nothing else, ever. That is the whole email program." },
    ],
  },
  {
    slug: "the-day-log-recap",
    section: "Trackers and email",
    title: "The Day Log recap",
    summary:
      "The Day Log recap is a morning email. Once you have filed a log in Day Log, the next morning brings your most recent log back as clean jot notes: the previous list scored, what got done, and your next top priorities.",
    updated: U,
    blocks: [
      { h: "How the loop starts" },
      { p: "The loop starts the first time you file a day in [Day Log](/docs/tools/day-log/), and every recap is built from your own filed logs. If you never file a log, you never get an email." },
      { h: "When it sends" },
      { ul: [
        "**Every morning.** A log filed on Friday recaps on Saturday; the rhythm runs seven days a week.",
        "**One recap per new log.** Each run covers your most recent log that has not been emailed yet.",
        "**The latest log wins.** If two logs pile up before a morning (say, Saturday and Sunday), the recap covers the newest one. The other stays in Past logs on the site; it just is not emailed.",
      ] },
      { h: "Missing a day" },
      { p: "Miss a day and the next morning brings one short nudge instead of a recap. If you still have not logged by that evening, the emails stop entirely, with no streak guilt or daily nagging. File a log any night and the recaps pick right back up the next morning." },
      { h: "How to stop it" },
      { p: "There is no separate switch, because there is nothing to switch off: stop filing logs and the emails stop on their own, after the single nudge. The recap goes only to your own account address." },
      { note: "Your words only, every morning, driven entirely by what you filed. That is the whole program." },
    ],
  },
  {
    slug: "requesting-a-tool",
    section: "Getting a tool built",
    title: "Requesting a tool",
    summary:
      "Missing a tool? There are two lanes. The community queue is free: your request joins a public list where everyone can upvote it, and the most-wanted tools get built. The fast lane is the 36-hour Promise: one scoped request, $49 one-time, built within 36 hours.",
    updated: "November 17, 2026",
    blocks: [
      { h: "The community queue" },
      { p: "Hit **Request a tool** anywhere on the site, or browse the [public queue](/requests/). Requests are visible to everyone, anyone can upvote, and we build the most-wanted tools as fast as we can. There is no timeline promise on this lane and no cost. If you are not signed in, we will nudge you to peek at the toolbox first (your task might already be solved) and make a free account so your request has a home." },
      { h: "The Promise lane" },
      { p: "For a one-time $49, one scoped request goes down the fast lane: agreed scope, built within 36 hours, or the request is on us. Say in your request that it is a Promise request; we agree the scope with you, payment is arranged, and the clock starts when scope and access are confirmed. Promise requests also appear in the public queue with a promise badge, so everyone can follow along. The promise, its clock rules, and what fits in it are spelled out on [the 36-hour promise page](/docs/the-36-hour-promise/)." },
      { h: "What makes a good request" },
      { ul: [
        "Describe the annoying task in plain words. 'Turn my supplier PDF invoices into a spreadsheet' tells us what to build far better than 'build an invoice app'.",
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
      "One scoped tool request, $49 one-time. Once the tiny, useful version is agreed, Bum Bum ships it within 36 hours, or the request is on us.",
    updated: "November 17, 2026",
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
        "Miss the window and the request is on us: you do not pay for that request, with no forms to file.",
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
      "Your account page is mission control: your saved work, your team, your trackers' attention list, and any billing left over from the old plans, all in one place.",
    updated: "November 17, 2026",
    blocks: [
      { h: "What lives on the account page" },
      { ul: [
        "**Your tally:** jobs finished this period, kept as an activity history. There is no limit attached to it; every tool is free.",
        "**Needs your attention:** the tracker items asking for you soonest, pulled from all four trackers.",
        "**The Monday note panel:** see whether the weekly email is on for you, and turn it off or back on. More on [the Monday note](/docs/the-monday-note/).",
        "**Billing:** only relevant if you hold a subscription from before November 17, 2026: manage payment or cancel through the billing portal.",
      ] },
      { h: "Billing changes" },
      { p: "There is nothing to upgrade to: tool use has no tiers and no fees. A subscription from the old plans can be cancelled in about two clicks through the billing portal, and it runs until the end of its billing period." },
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
    updated: "November 17, 2026",
    blocks: [
      { faq: [
        ["Do I need an account?", "No. Guests can use every tool right away, free with no limits. A free account gives your trackers, logs, and packs somewhere to live, and they follow you across devices."],
        ["Is it really free?", "Yes. Every tool, unlimited use, no card and no trial clock. The only paid offer is the 36-hour Promise: a custom tool built on request for a one-time $49."],
        ["Are there any limits at all?", "No usage limits. Individual tools have practical caps that come from the job itself, like batch sizes or file sizes, and each tool's page states them plainly."],
        ["I had a paid plan. What happens to it?", "It is retired, and your tools are free now either way. Manage or cancel the subscription from your account page, through the billing portal, whenever you like; it runs until the end of its billing period."],
        ["Is my data used to train AI models?", "No. Your workspace contents are yours. Most files never even leave your device."],
        ["How do I cancel?", "There is no subscription to cancel for tool use. If you hold one from the old plans, cancel from your account page through the billing portal: two clicks."],
        ["Can Bum Bum cancel my subscriptions for me?", "No tool cancels things with outside services. The Subscription Tracker makes sure you remember in time; the cancelling click is yours."],
        ["What if the tool I need does not exist?", "Request it. The community queue is free and public, and the 36-hour Promise builds one scoped tool on request for a one-time $49."],
      ] },
    ],
  },
  {
    slug: "troubleshooting",
    section: "Account and help",
    title: "When something goes sideways",
    summary:
      "Most Bum Bum problems have boring causes and quick fixes. Here are the ones we actually see, in the order we see them.",
    updated: "November 17, 2026",
    blocks: [
      { h: "A tool stopped mid-job" },
      { p: "There is no usage limit anymore, so a stopped job is never about a quota. The usual causes are a dropped connection or a file your browser could not read. Try again; if it keeps happening on the same file, the file is the place to look. Background on the old limits lives at [Free, with no meter](/docs/actions/)." },
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
