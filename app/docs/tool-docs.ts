// Tool documentation content. One entry per tool, in toolbox order.
// CADENCE RULE: a new tool does not ship without its entry here, in the
// same change. The docs sidebar, docs home, sitemap entries, and the
// tool's own docs page are all generated from this file.
export interface ToolDoc {
  key: string;
  name: string;
  href: string;
  icon: string;
  summary: string;
  steps: string[];
  goodToKnow: string[];
  data: string;
  tips: string[];
  faq?: Array<[string, string]>;
  updated: string;
}

const U = "October 27, 2026";

export const TOOL_DOCS: ToolDoc[] = [
  {
    key: "clips",
    name: "Screen Recorder",
    href: "/tools/clips/",
    icon: "/bum/icons/clips.png",
    summary:
      "Screen Recorder records your screen right in your browser. Pick a tab, a window, or your whole screen, add your microphone and a small camera bubble if you want to be in it, then trim the result and tweak the playback speed before you download.",
    steps: [
      "Open Screen Recorder and press record.",
      "In your browser's picker, choose the tab, window, or screen to capture.",
      "Turn on your mic or camera bubble if you want narration or your face in the corner.",
      "Record, then stop when you are done.",
      "Trim the ends, adjust the playback speed if you like, and download your clip.",
    ],
    goodToKnow: [
      "Works on a desktop browser; mobile browsers do not offer screen capture.",
      "One finished recording uses one action. Recording and previewing are free.",
      "Your video is rendered on your device, so long recordings are limited by your computer, not by us.",
    ],
    data: "Nothing uploads anywhere. Your recording never leaves your computer, and Bum Bum keeps no copy.",
    tips: [
      "Close unrelated tabs before you record a single tab; the picker shows everything.",
      "A short test recording first saves you from discovering a muted mic at minute nine.",
    ],
    faq: [
      ["Do I need an account to record my screen?", "You can record as a guest. A free account gives you a monthly bucket of actions for finished recordings and keeps everything under one roof."],
      ["Can I include my webcam?", "Yes. Turn on the camera bubble and it sits in the corner of your recording."],
    ],
    updated: U,
  },
  {
    key: "notes",
    name: "Voice Notes to Text",
    href: "/tools/notes/",
    icon: "/bum/icons/notes.png",
    summary:
      "Voice Notes to Text turns talking into a transcript as you speak. Hit record, talk it out, and your words appear live. When you stop, the transcript is saved to your private library, ready to copy or download.",
    steps: [
      "Open Voice Notes to Text and press record.",
      "Talk. Your words appear on screen as you say them.",
      "Stop when you are done. The transcript lands in your library.",
      "Copy it, download it, or play the recording back first to check a part.",
    ],
    goodToKnow: [
      "Your recording stays in your browser, so you can listen back right away or download the audio to keep it.",
      "On iPhone, live transcription is not available in the browser, so your audio is sent to our server to be transcribed instead.",
      "One finished note uses one action.",
    ],
    data: "Recordings live in your browser and are never saved to our servers. Transcripts are saved to your account library so you can come back to them.",
    tips: [
      "Short notes beat one rambling one. Your future self reads three tidy transcripts faster than one saga.",
      "Say the punctuation you care about out loud, like headings, and tidy up after. The transcript is a draft that is 95% done.",
    ],
    faq: [
      ["Where does my recording go?", "Nowhere, on desktop. It stays in your browser. On iPhone it is sent to our server for transcription because iPhone browsers cannot transcribe live."],
      ["Can I download my transcripts?", "Yes, any note in your library can be copied or downloaded as text, and the audio too when you want it."],
    ],
    updated: U,
  },
  {
    key: "cutline",
    name: "Subscription Tracker",
    href: "/tools/cutline/",
    icon: "/bum/icons/cutline.png",
    summary:
      "Subscription Tracker keeps every subscription you forgot about in one place, with what it costs and when it renews. Renewals show up in the Monday note, Bum Bum's weekly email for account holders, for the week they fall in.",
    steps: [
      "Open Subscription Tracker and add a subscription: name, price, how often it bills, and the next renewal date.",
      "Glance at the list whenever. Anything whose renewal date has passed rolls forward to its next cycle automatically, because a recurring subscription is assumed to have renewed.",
      "Watch your Monday note. Anything renewing that week is rounded up for you.",
      "Cancel the keepers' dead weight with the service itself, then delete the entry here.",
    ],
    goodToKnow: [
      "Adding a subscription uses one action. Editing, deleting, and the list itself are free.",
      "Cadences: weekly, monthly, quarterly, every six months, and yearly.",
      "Free trials count too. Add one with its trial end date and it shows up in the Monday note for that week.",
    ],
    data: "Your subscription list is stored in your account so it is there on every device. Bum Bum cannot see your bank, your cards, or the subscriptions themselves, only what you type in.",
    tips: [
      "Add the free trial the day you start it, not the day you mean to decide. The Monday note only helps if the trial is in the list.",
      "Sort by renewal date before a cash-tight month and cancel in order of soonest charge.",
    ],
    faq: [
      ["How do renewal reminders work?", "There are no per-renewal reminder emails. Renewals ride along in the Monday note, the weekly email that rounds up everything coming due that week, Monday through Sunday."],
      ["Can Subscription Tracker cancel a subscription for me?", "No. Cancelling happens with the service itself. The tracker makes sure you remember in time, and shows you the total of what is about to renew."],
    ],
    updated: U,
  },
  {
    key: "purrsuit",
    name: "Client Follow-Up Tracker",
    href: "/tools/purrsuit/",
    icon: "/bum/icons/purrsuit.png",
    summary:
      "Client Follow-Up Tracker is a tiny CRM for people who hate CRMs. Add the humans, move them down the pipeline, and every morning it tells you exactly who to follow up with and what to say.",
    steps: [
      "Add a person: name, company if they have one, and where things stand.",
      "Set a follow-up date whenever a ball is in their court.",
      "Check the Follow up today list each morning and work it top to bottom.",
      "Move people along the pipeline as conversations progress, and log the follow-ups into the Monday note automatically.",
    ],
    goodToKnow: [
      "Adding a contact uses one action. Everything else, moving, editing, checking the list, is free.",
      "You can import contacts from a CSV instead of typing them one by one.",
      "Follow-ups that come due show up in the Monday note for that week.",
    ],
    data: "Your contacts live in your account. Bum Bum does not email your clients from this tool, and your list is never shared.",
    tips: [
      "Set the follow-up date before you close the tab after every call. Two days is a good default for a warm conversation.",
      "Keep the pipeline honest. Someone who has gone quiet for a month belongs in a later stage, not in today's list forever.",
    ],
    faq: [
      ["How is this different from a big CRM?", "No deals pipeline gymnastics, no required fields, no admin. Just who, where things stand, and who needs a nudge today."],
      ["Can I import my contacts?", "Yes, there is a CSV import on the page. Name and company are enough to start."],
    ],
    updated: U,
  },
  {
    key: "corporate-bum-bum",
    name: "Job Application Tracker",
    href: "/tools/corporate-bum-bum/",
    icon: "/bum/icons/corporate-bum-bum.png",
    summary:
      "Job Application Tracker tracks your applications without the spreadsheet dread. Add the roles, move them down the pipeline, and every morning it tells you exactly who to follow up with and what to say.",
    steps: [
      "Add an application: company, role, and where it stands.",
      "Set a follow-up date when you apply or after an interview.",
      "Check Follow up today each morning and send the nudges it suggests.",
      "Move applications along as stages change, from applied to interview to offer, or close them out honestly.",
    ],
    goodToKnow: [
      "Adding an application uses one action. Everything else is free.",
      "You can import applications from a CSV.",
      "Applications with a follow-up due show up in the Monday note for that week.",
    ],
    data: "Your applications live in your account, visible only to you. The tracker never contacts an employer on your behalf.",
    tips: [
      "Log the application the minute you submit it, while the posting link is still in front of you.",
      "A follow-up five business days after applying is polite, expected, and often the thing that resurfaces your name.",
    ],
    faq: [
      ["How is this different from a spreadsheet?", "A spreadsheet never taps you on the shoulder. This tells you who to follow up with today, and drafts the shape of what to say."],
      ["Can I import my applications?", "Yes, CSV import is on the page."],
    ],
    updated: U,
  },
  {
    key: "doodle",
    name: "Online Sketchpad",
    href: "/tools/doodle/",
    icon: "/bum/icons/doodle.png",
    summary:
      "Online Sketchpad is a pocket sketchpad for signatures, diagrams, and masterpieces. Draw with a finger, stylus, or mouse, right in your browser.",
    steps: [
      "Open Online Sketchpad and start drawing.",
      "Pick your pen, colors, and canvas size as you go.",
      "Save or copy your doodle when it looks right, or download it as a PNG or SVG.",
    ],
    goodToKnow: [
      "Drawing is free and unlimited. Saving or copying a doodle uses one action.",
      "Exports come out as PNG or SVG.",
      "Works with touch, stylus, and mouse.",
    ],
    data: "Nothing leaves your browser while you draw. Your sketch only goes anywhere when you choose to save, copy, or download it.",
    tips: [
      "Signing a document? Draw big, then shrink. Big signatures export cleaner than small careful ones.",
      "SVG export keeps lines crisp at any size, which is the one to pick for anything printed.",
    ],
    faq: [
      ["Does my drawing leave my browser?", "Not while you draw. Only when you save, copy, or download it, and then only where you send it."],
      ["Can I use a stylus?", "Yes, anything your device can draw with works, finger included."],
    ],
    updated: U,
  },
  {
    key: "invoice-chaser",
    name: "Invoice Reminder",
    href: "/tools/invoice-chaser/",
    icon: "/bum/icons/invoice-chaser.png",
    summary:
      "Invoice Reminder tracks who owes you what. Bum Bum writes the chase email in the right tone, sends it as your billing assistant in one tap, and learns how each client actually pays so it knows when to nudge and when to sit tight.",
    steps: [
      "Add an invoice: client, amount, invoice number if you have one, and the due date.",
      "When one needs chasing, open it and read the draft Bum Bum wrote.",
      "Adjust the tone if you like, then send it in one tap. It goes out as your billing assistant.",
      "Mark invoices paid as money lands. Overdue and due-this-week invoices show up in your Monday note.",
    ],
    goodToKnow: [
      "Adding an invoice or sending a chase uses one action each. Everything else is free.",
      "The chaser learns each client's usual payment timing, so a client who always pays on day 20 does not get nudged on day 2.",
      "You always see and approve the email before anything sends.",
    ],
    data: "Your invoice list lives in your account. Chase emails are sent from Bum Bum's mail as your billing assistant, with your details in the signature you set.",
    tips: [
      "Chase the day after the due date, politely. The first nudge is a reminder, not an accusation, and most late invoices are just buried inboxes.",
      "Add the invoice the day you send it, not the day it goes overdue. The learning only works with the real dates.",
    ],
    faq: [
      ["Does it actually send the emails?", "Yes, with your approval each time. Nothing sends itself; you read the draft and tap send."],
      ["Will it annoy my clients?", "The drafts start friendly and firm up gradually, and the timing adapts to when each client usually pays. You can edit every word before sending."],
    ],
    updated: U,
  },
  {
    key: "receipt-reader",
    name: "Receipt Scanner",
    href: "/tools/receipt-reader/",
    icon: "/bum/icons/receipt-reader.png",
    summary:
      "Receipt Scanner turns photos of receipts into clean, editable tables. Snap up to 10 receipts at once; Bum Bum reads them right in your browser and pulls out the vendor, date, line items, tax, and total.",
    steps: [
      "Open Receipt Scanner and drop in receipt photos, or pick them from your gallery or camera.",
      "Press Read. The reading happens in your browser, and each receipt becomes its own editable card.",
      "Fix anything it misread. Every field can be edited.",
      "Save cards to your receipt log, download one as a CSV, or download the whole log as one CSV whenever you like.",
      "No receipt handy? Use the sample receipt on the page and watch a full read happen for free.",
    ],
    goodToKnow: [
      "Up to 10 receipts in one batch. One action per receipt read.",
      "Reading works best with the receipt flat, well lit, and filling the frame.",
      "Saving to your log and CSV exports are free.",
      "If a photo holds no receipt at all, Bum Bum says so and skips it without charging an action, instead of handing back a blank card.",
    ],
    data: "Your photos never leave your device. The reading happens in your browser, and only the typed-up numbers are saved, in your account, when you save a card to your log.",
    tips: [
      "Creased receipt? Smooth it flat and shoot from directly above. Shadows across the total are the top cause of a misread.",
      "The raw text Bum Bum read is on every card, so you can check a doubtful number against exactly what it saw.",
    ],
    faq: [
      ["Do my receipt photos upload anywhere?", "No. The reader runs in your browser. Photos stay on your device; only the extracted numbers are saved when you save to your log."],
      ["How many receipts can I scan at once?", "Ten at a time. Queue more after the batch finishes."],
    ],
    updated: U,
  },
  {
    key: "poster-to-calendar",
    name: "Poster to Calendar",
    href: "/tools/poster-to-calendar/",
    icon: "/bum/icons/poster-to-calendar.png",
    summary:
      "Poster to Calendar turns a photo of an event poster, flyer, or screenshot into a ready-to-save calendar event, with the title, date, time, and place filled in.",
    steps: [
      "Open Poster to Calendar and snap or upload the poster.",
      "Bum Bum reads it and builds the event, or several, if the poster lists a lineup.",
      "Check every detail and fix anything it misread.",
      "Save with one tap as a calendar file, or add it straight to Google Calendar.",
    ],
    goodToKnow: [
      "One poster read uses one action, however many events it holds, up to six.",
      "Screenshots of digital posters work just as well as photos of paper ones.",
      "Relative dates get resolved for you: a poster that says Friday means the coming Friday.",
      "No poster handy? The sample poster on the page drops three editable events in for free, so you can see the whole flow first.",
      "If a photo holds no event at all (a pet, a selfie, a menu), Bum Bum says so instead of guessing. It only reads posters and flyers.",
    ],
    data: "Your photo is sent to the reader, read, and discarded. It is never stored.",
    tips: [
      "Crooked photo, straighten it first with Image Cropper, or just retake it square-on. Dates read far more reliably head-on.",
      "Always eyeball the year on New Year's-adjacent posters. Humans get that wrong too.",
    ],
    faq: [
      ["What if the poster lists more than one event?", "You get an event card for each one, up to six, and you can save them individually or all at once."],
      ["Which calendars does it work with?", "The download is a standard calendar file that works with Apple Calendar, Outlook, and everything else, plus there is a direct Add to Google Calendar option."],
    ],
    updated: U,
  },
  {
    key: "meal-scanner",
    name: "Meal Scanner",
    href: "/tools/meal-scanner/",
    icon: "/bum/icons/meal-scanner.png",
    summary:
      "Meal Scanner estimates the calories, protein, carbs, and fat in a meal from one photo. No database searching, no barcode scanning, no weighing anything, and every estimate comes as an honest range instead of a fake-exact number.",
    steps: [
      "Open Meal Scanner and snap or upload a photo of your plate.",
      "Bum Bum identifies the items and estimates each one, with a low-to-high range.",
      "Adjust portion sizes or edit any item that was misread.",
      "The day total keeps running on the page as you add meals.",
    ],
    goodToKnow: [
      "One photo read uses one action.",
      "Vision estimates of portions typically run 20 to 40 percent off in either direction. The range is the honest answer, not a cop-out.",
      "Your running day total is kept in your browser only.",
      "No meal photo handy? The sample meal on the page shows the full result for free, every number editable.",
    ],
    data: "Your photo is read and discarded, never stored. The day total lives in your browser's local storage, not in your account.",
    tips: [
      "Include something for scale, like cutlery or your hand at the edge of the frame. Portion guesses get better fast.",
      "Sauce-heavy plate? Edit the item after the read and note the sauce. Hidden calories mostly live there.",
    ],
    faq: [
      ["How accurate is it?", "It is an estimate with a stated range, good for staying aware without tracker-app friction. It is not a medical measurement."],
      ["Where does my photo go?", "To the reader and nowhere else. It is read and discarded, never stored."],
    ],
    updated: U,
  },
  {
    key: "gap-scout",
    name: "Resume Gap Checker",
    href: "/tools/gap-scout/",
    icon: "/bum/icons/gap-scout.png",
    summary:
      "Resume Gap Checker compares your resume against up to five job descriptions and shows you the skills they keep asking for that your resume never mentions, with the exact line from each posting as proof.",
    steps: [
      "Open Resume Gap Checker and drop in your resume.",
      "Add up to five job descriptions. Paste the text, or paste a link and Bum Bum fetches it.",
      "Read the gap list: each missing skill, how many postings asked for it, and the line that proves it.",
      "Decide what is a real gap worth closing and what is keyword noise, then update your resume where it counts.",
    ],
    goodToKnow: [
      "Compare against up to five job descriptions at once.",
      "Everything happens in your browser.",
      "One finished check uses one action.",
    ],
    data: "Your resume never leaves your device. The comparison runs in your browser, and nothing is uploaded or stored.",
    tips: [
      "Compare against postings for the same kind of role. Mixing five unrelated jobs produces a gap list for a job that does not exist.",
      "A skill in four of five postings is a pattern. A skill in one is that hiring manager's hobby. Spend your effort in that order.",
    ],
    faq: [
      ["How many job descriptions can I compare?", "Up to five at once, which is enough to see the pattern for a role without drowning in it."],
      ["Does my resume leave my device?", "No. The whole comparison happens in your browser."],
    ],
    updated: U,
  },
  {
    key: "video-trimmer",
    name: "Video Trimmer",
    href: "/tools/video-trimmer/",
    icon: "/bum/icons/video-trimmer.png",
    summary:
      "Video Trimmer snips the good part out of a video, right in your browser. Drop in a video, mark where the good part starts and ends, and download the cut.",
    steps: [
      "Open Video Trimmer and drop in your video.",
      "Play it and mark the start and end of the part you want to keep.",
      "Trim it, preview the cut, and download.",
    ],
    goodToKnow: [
      "One action per trim. Marking and previewing are free.",
      "Common video formats work; if your browser can play it, the trimmer can usually cut it.",
      "Rendering happens on your device, so very large files take a while and a strong sneeze of patience.",
    ],
    data: "Your video never leaves your device. The trim happens in your browser and Bum Bum keeps no copy.",
    tips: [
      "Mark a little wide, then tighten. Cutting a second of dead air is easy; recovering a clipped first word is not.",
      "For a clip headed to social, trim to the moment itself. Context is what the caption is for.",
    ],
    faq: [
      ["Does my video upload anywhere?", "No. Everything happens in your browser."],
      ["What video formats work?", "The common ones your browser can play, like MP4 and WebM."],
    ],
    updated: U,
  },
  {
    key: "image-converter",
    name: "Image Converter",
    href: "/tools/image-converter/",
    icon: "/bum/icons/image-converter.png",
    summary:
      "Image Converter changes image formats right in your browser: WebP to PNG, JPG to WebP, PNG to JPG, whatever to whatever, and hands the files back in the format you actually wanted.",
    steps: [
      "Open Image Converter and pick your images.",
      "Choose the format you want them in.",
      "Convert, then download each file or grab them together.",
    ],
    goodToKnow: [
      "One action per image converted. Picking and previewing are free.",
      "Supports the everyday web formats: PNG, JPG, and WebP.",
      "Batch friendly: line up several images and convert them the same way.",
    ],
    data: "Your files never leave your device. Conversion happens in your browser.",
    tips: [
      "A screenshot stuck as a WebP becomes a friendly PNG here, which is the format every other tool and form on the internet accepts.",
      "Converting to WebP shrinks images for your website while keeping them sharp. Converting from WebP is for when some upload form refuses it.",
    ],
    faq: [
      ["Do my images upload anywhere?", "No. The conversion runs in your browser."],
      ["Is there a size limit?", "Only what your device can comfortably hold in memory. Ordinary photos and screenshots are no problem."],
    ],
    updated: U,
  },
  {
    key: "image-cropper",
    name: "Image Cropper",
    href: "/tools/image-cropper/",
    icon: "/bum/icons/image-cropper.png",
    summary:
      "Image Cropper cuts an image down to the part you actually want. Drag a box around the good part, with aspect presets for profile pics and posts, right in your browser.",
    steps: [
      "Open Image Cropper and pick an image.",
      "Drag the crop box around what matters, or pick an aspect preset and adjust.",
      "Crop it and download the result.",
    ],
    goodToKnow: [
      "One action per crop.",
      "Aspect presets cover the common profile and post shapes, and freeform cropping is always there.",
      "You can set an exact crop area, not just eyeball it.",
    ],
    data: "Your files never leave your device. Cropping happens in your browser.",
    tips: [
      "Cropping a poster photo square-on before Poster to Calendar makes its read noticeably more reliable.",
      "Leave a little breathing room around faces. Platforms crop previews their own way and foreheads pay the price.",
    ],
    faq: [
      ["Do my images upload anywhere?", "No, cropping is fully in your browser."],
      ["Which formats work?", "The everyday image formats your browser can open: PNG, JPG, WebP, and friends."],
    ],
    updated: U,
  },
  {
    key: "ghostwriter",
    name: "Voice to Content",
    href: "/tools/ghostwriter/",
    icon: "/bum/icons/ghostwriter.png",
    summary:
      "Voice to Content turns a minute of talking into a content pack: three hooks, each testing a different angle, a 60-second script, and a caption ready to post.",
    steps: [
      "Open Voice to Content and hit record, or paste in text if you already have it written down.",
      "Ramble for about a minute about your day, your work, whatever is on your mind.",
      "Get your pack: three hooks, the script, and the caption.",
      "Pick the angle you like, tweak the words so they sound like you on a good day, and post.",
    ],
    goodToKnow: [
      "One finished pack uses one action.",
      "Your packs are saved in your account, so a good hook from Tuesday is still there on Friday.",
      "Talking works better than writing here. The tool is built for the ramble.",
    ],
    data: "Your audio is raw material only. It is transcribed, used to build the pack, and never kept.",
    tips: [
      "Start mid-thought. The reason it is interesting is a better hook than a polished introduction.",
      "Generate the pack while the thought is warm, then edit later with fresh eyes. Capturing and polishing are different jobs.",
    ],
    faq: [
      ["What do I get in a content pack?", "Three hooks testing different angles, a script that runs about 60 seconds spoken, and a caption ready to paste."],
      ["What happens to my audio?", "It is transcribed and discarded. Only the resulting pack is saved."],
    ],
    updated: U,
  },
  {
    key: "audiogram",
    name: "Audiogram Maker",
    href: "/tools/audiogram/",
    icon: "/bum/icons/audiogram.png",
    summary:
      "Audiogram Maker turns an MP3 into a captioned video ready for the feed. Upload your audio and a background image, pick a caption style, and download the MP4.",
    steps: [
      "Open Audiogram Maker and add your MP3 and a background image.",
      "Choose captions: Bum Bum can transcribe your audio, or pick No captions to skip them entirely.",
      "Pick a caption style and a video size for the platform you are posting to.",
      "Preview, render, and download the MP4.",
    ],
    goodToKnow: [
      "One finished video uses one action. Setting up and previewing are free.",
      "Caption styles and sizes cover the usual feed formats, square and vertical included.",
      "The transcription is automatic when you want captions, and your audio is never kept after.",
    ],
    data: "Your audio is transcribed to make the captions and nothing more. It is never stored, and the finished video is yours to download.",
    tips: [
      "Vertical for Reels and TikTok, square for feeds. Making the wrong shape twice is the tax everyone pays once.",
      "A background with a calm middle keeps captions readable. Busy photo plus animated captions equals nobody reading anything.",
    ],
    faq: [
      ["What happens to my audio?", "It is transcribed for captions, if you want them, and never kept."],
      ["What video sizes can I make?", "The standard feed shapes, including square and vertical. Pick the one your platform rewards."],
    ],
    updated: U,
  },
  {
    key: "purr-code",
    name: "QR Code Generator",
    href: "/tools/purr-code/",
    icon: "/bum/icons/purr-code.png",
    summary:
      "QR Code Generator makes branded QR codes: your link, soft dots, pretty colors, and a cat in the middle, ready to print.",
    steps: [
      "Open QR Code Generator and paste your link.",
      "Dress it up: colors, dot style, and the cat in the middle.",
      "Preview as much as you like, then export the finished code.",
      "Turn on tracking if you want to see how many times it gets scanned.",
    ],
    goodToKnow: [
      "Previewing is free. Exporting a code uses one action.",
      "Codes do not expire. A printed code keeps working as long as the link does.",
      "Tracking is optional and shows scan counts, so a poster or a menu can prove it is working.",
    ],
    data: "The code encodes your link and nothing else. If you turn on tracking, scan counts are recorded against the code so you can see them.",
    tips: [
      "Test the printed size, not the screen size. Print one, stick it where it will live, and scan it from a customer's distance.",
      "Keep contrast strong between the dots and the background. Pretty colors that are too close together make a code that looks great and scans never.",
    ],
    faq: [
      ["Do the QR codes expire?", "No. The code points at your link for as long as the link lives."],
      ["Can I track how many times my code is scanned?", "Yes, turn tracking on when you make the code and its scan count shows in your account."],
    ],
    updated: U,
  },
  {
    key: "sop-ify",
    name: "SOP Generator",
    href: "/tools/sop-ify/",
    icon: "/bum/icons/sop-ify.png",
    summary:
      "SOP Generator turns a messy brain-dump of how you do a thing into a clean, step-by-step playbook a brand-new helper could follow with zero prior context.",
    steps: [
      "Open SOP Generator and dump how you do the thing: type it, or talk instead of typing.",
      "Ramble freely. Order, polish, and completeness are the tool's problem, not yours.",
      "Get back a structured playbook: steps in order, with the details a newcomer needs.",
      "Skim it, fix anything only you would know, and hand it over.",
    ],
    goodToKnow: [
      "One finished playbook uses one action.",
      "The messier the input, the bigger the transformation. Do not pre-organize; that is the job you are delegating.",
      "You can talk instead of typing if the process lives in your head better than on a keyboard.",
    ],
    data: "Your dump is used to build the playbook and your playbooks are saved in your account. Nothing is shared.",
    tips: [
      "Include the annoying parts: the login that lives in a spreadsheet, the step everyone forgets. Playbooks fail on the steps nobody wrote down.",
      "Do one for the task you dread handing over. That dread is usually undocumented knowledge, and the SOP is how it stops being only yours.",
    ],
    faq: [
      ["How detailed is the playbook?", "Step by step, written for someone with zero context, because that is who usually reads it at 9 AM on their first day."],
      ["Can I talk instead of typing?", "Yes. Dictate the whole mess and let the tool sort it out."],
    ],
    updated: U,
  },
  {
    key: "hearsay",
    name: "AI Mention Tracker",
    href: "/tools/hearsay/",
    icon: "/bum/icons/hearsay.png",
    summary:
      "AI Mention Tracker finds out what AI models say about your brand. It asks three AI models the questions your customers ask, and reports back your mention rate, the sentiment, direct quotes, and which competitors get cited instead of you.",
    steps: [
      "Open AI Mention Tracker and type your brand name.",
      "Add the questions your customers actually ask AI, the ones where you want to be the answer.",
      "Run the scan. Each question goes to three AI models.",
      "Read the verdict, then go question by question to see the quotes and who got cited instead.",
    ],
    goodToKnow: [
      "One finished scan uses one action.",
      "Your scans are saved, so you can rerun the same questions next month and see whether you are gaining or losing ground.",
      "Results are a snapshot of real answers, not a simulation, and answers vary run to run the way real AI answers do.",
    ],
    data: "The scan sends your brand name and your questions to the AI models it checks. That is the whole point of the tool, and nothing else about your account goes along.",
    tips: [
      "Write the questions the way customers type them, not the way your marketing page phrases them. The gap between those two is usually the finding.",
      "A competitor cited on a question you should own is a content assignment, not just a sad statistic. Write the page that answers it.",
    ],
    faq: [
      ["Which AI models does it ask?", "Three of the major ones your customers are most likely to be asking. The scan shows which model said what, per question."],
      ["How accurate are the results?", "They are real answers collected at scan time. AI answers shift over time, which is why rerunning the same scan monthly is the honest way to track it."],
    ],
    updated: U,
  },
  {
    key: "video-converter",
    name: "Video Converter",
    href: "/tools/video-converter/",
    icon: "/bum/icons/video-converter.png",
    summary:
      "Video Converter converts video to MP4, WebM, or animated GIF, right in your browser. Your video never leaves your device.",
    steps: [
      "Open Video Converter and drop in your video.",
      "Pick the format you need: MP4, WebM, or animated GIF.",
      "Convert, preview, and download.",
    ],
    goodToKnow: [
      "One finished conversion uses one action.",
      "Animated GIFs are capped at 15 seconds, because a GIF longer than that is a video wearing a costume.",
      "Rendering happens on your device, so long videos convert at the speed of your computer.",
    ],
    data: "Your video never leaves your device. Conversion happens entirely in your browser.",
    tips: [
      "Making a GIF? Trim the moment first with Video Trimmer, then convert. A tight 4-second GIF beats a wandering 15-second one.",
      "MP4 is the safe choice when the destination is unknown. Everything plays MP4.",
    ],
    faq: [
      ["Does my video get uploaded anywhere?", "No. The whole conversion happens in your browser."],
      ["Why is my GIF only 15 seconds?", "GIFs balloon in size fast. Fifteen seconds keeps the file shareable; for anything longer, convert to MP4 instead."],
    ],
    updated: U,
  },
  {
    key: "video-mp3-overlay",
    name: "Video MP3 Overlay",
    href: "/tools/video-mp3-overlay/",
    icon: "/bum/icons/video-mp3-overlay.png",
    summary:
      "Video MP3 Overlay mixes music into a video, in your browser. Drop in a video and an MP3, snip the good bits of the song, pin each one where it should play, set how loud everything sits, and download the mix.",
    steps: [
      "Open Video MP3 Overlay and add your video and your MP3.",
      "Snip the parts of the song you want; you can use the same part more than once.",
      "Pin each snippet where it should play in the video.",
      "Set the mix: how loud the song sits against the video's own sound.",
      "Render and download your mixed video.",
    ],
    goodToKnow: [
      "One finished mix uses one action. Placing snippets and previewing the mix are free.",
      "Snippets can repeat, overlap the original audio, and each source has its own volume.",
      "Nothing gets uploaded; the render happens in your browser.",
    ],
    data: "Your video and MP3 never leave your device. The mix is rendered in your browser and Bum Bum keeps no copy of either file.",
    tips: [
      "Pull the song down further than feels right under dialogue. If you are noticing the music while someone talks, it is too loud.",
      "Pin a snippet a breath before the moment it is for. Music that lands exactly on the action feels late.",
    ],
    faq: [
      ["Do my video and MP3 get uploaded anywhere?", "No. Everything happens in your browser."],
      ["Can I use the same part of the MP3 more than once?", "Yes. Snip it once, pin it as many times as the video needs."],
    ],
    updated: U,
  },
  {
    key: "day-log",
    name: "Day Log",
    href: "/tools/day-log/",
    icon: "/bum/icons/day-log.png",
    summary:
      "Day Log closes your day in a few minutes of talking. Three prompts above one box cover what got done and what got in the way, what you learned, and what tomorrow needs. Bum Bum turns your ramble into clean jot notes, scores yesterday's top list against what you actually did, files the day in your account, and emails you the notes with tomorrow's priorities on top. When a stretch of days piles up, week and month summaries pull out the accomplishments, learnings, and growth.",
    steps: [
      "Open Day Log at the end of your day. The three prompts sit above one box: talk through all of them in one go, in any order, or type.",
      "If yesterday had a list, it sits above the box. Say honestly what landed and what did not.",
      "Press File my day. Bum Bum structures your words into jot notes and scores yesterday's list.",
      "Read the filed day on screen, and check your inbox: the same jot notes arrive by email with tomorrow's top list.",
      "Come back tomorrow. Today's list becomes the scorecard.",
      "Any time, open Look back and summarize your week or month into highlights.",
    ],
    goodToKnow: [
      "One action per day filed. Re-filing the same day replaces the earlier version and costs no extra action.",
      "Week and month summaries cost one action when freshly generated. Re-opening the same stretch of logs is free.",
      "Day Log needs a free account: the scorecard only works if yesterday's list is filed under you.",
      "Transcription tries your browser's live transcription first. On browsers without it, your recording is transcribed by Whisper and then discarded.",
      "Skip any prompt that does not fit the day. One answered prompt is enough to file.",
    ],
    data: "We keep your words, never your voice. Recordings are transcribed and discarded; only the text of your log is stored, in your account, and it is never shared. The daily email goes only to your own account address.",
    tips: [
      "Make tomorrow's items countable. 'Send the proposal' scores cleanly tomorrow; 'work on business stuff' never does.",
      "Do the brain-dump prompt out loud and unfiltered. The structure step is good at finding the plan inside a ramble.",
      "File before you plan tomorrow anywhere else. The log's list is the one that gets scored.",
    ],
    faq: [
      ["Where does my voice go?", "Nowhere it stays. Recordings are transcribed and discarded; only text is kept."],
      ["What if I skip a day?", "Nothing breaks. The scorecard compares against your most recent filed day, whenever that was."],
      ["Can I type instead of talking?", "Yes. Every prompt has a text box, and you can mix talking and typing in the same log."],
    ],
    updated: "October 28, 2026",
  },
];
