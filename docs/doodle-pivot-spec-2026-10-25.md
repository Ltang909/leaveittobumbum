# Doodle pivot spec — 2026-10-25

Context: Leon paused the Doodle Pinterest pin on Oct 25 after seeing the pattern
in person: lots of opens, people look around, a few dots and strokes, nothing
legit. His framing: it needs to be either more niche or more impressive.
This spec sizes both directions against the actual code so the decision is a
pick, not a debate. Nothing here is built or shipped.

## What the code says today (verified Oct 25)

- The whole tool is one 250-line file: `hostinger/public_html/tools/doodle/index.php`.
  Canvas is fully client-side; strokes live in a JS array; export is PNG / SVG /
  Copy. The server side is a 13-line endpoint (`api/tools/doodle.php`) that only
  charges 1 action per save/copy.
- Telemetry shipped Oct 23 is already in the file: `doodle_health` (canvas
  test-pixel check with device/browser detail), `doodle_first_stroke`
  (pointer type), and `doodle_export` (how + stroke count). So the funnel
  opened -> first stroke -> export, and the stroke-count distribution on
  exports, can be read from PostHog before any build. That read is step zero
  for either pivot: if exports exist but carry 1-3 strokes, people are testing
  the save button, not finishing drawings.

## Direction 1 — More niche: Annotate (draw on your own photo/screenshot)

What it is, fully: an "Add photo" button on the existing canvas. The user
uploads a photo or screenshot, it becomes the canvas background layer, and
they draw on top of it: circle the leak for the landlord, mark up a product
photo, arrow the bug in a screenshot, sign over a document image. Export
flattens photo + strokes into one PNG. The blank page disappears because the
input already exists, which matches every winner in the lineup (receipt,
poster, plate of food: something real in, something better out).

Build size: small, all inside the one existing file.
- File input + FileReader, draw the image as a background layer under the
  stroke redraw loop, scale canvas aspect to the image.
- Export already composites the canvas, so PNG export mostly just works;
  SVG export needs a decision (embed the image or disable SVG in photo mode).
- Copy on the page changes: headline/FAQ move from "pocket sketchpad" to
  "mark up a photo or screenshot". Slug and URL stay `/tools/doodle/`.
- Zero per-use cost (still fully in-browser, photo never leaves the device,
  same privacy line as today). Action charge stays 1 per save/copy.
- Realistic effort: half a day including staging smoke test.

What it proves: whether Doodle's Pinterest traffic had a utility job in mind
and bounced off a toy, or was pure curiosity. Read after ~1 week of the pin
re-pointed at the annotation promise: first-stroke rate should hold, export
rate is the verdict. If export rate does not move, the traffic was curiosity
with no job behind it and Doodle should be retired like Cat Translator was.

Risk: annotation is a crowded utility (every phone OS ships markup). The
Bum Bum angle is no-install, no-account-to-start, in-browser privacy. That is
a real but thin wedge, so this is a cheap test, not a bet.

## Direction 2 — More impressive: Sketch-to-Logo (sketch in, clean mark out)

What it is, fully: the user scribbles a rough logo idea on the same canvas,
hits one button, and an image model returns a cleaned-up version of their
sketch. Before/after is inherently shareable, it aims at the actual ICP
(solopreneurs always need a logo), and it converts Doodle from the lineup's
only creation-from-nothing tool into another transformer.

Build size: a real build, not a polish pass.
- New server endpoint: canvas PNG goes up, image-generation/edit call comes
  back with the cleaned mark. This is the first Doodle feature where user
  content leaves the browser, so the page's current "nothing leaves your
  browser" promise and FAQ must be rewritten honestly for this mode.
- Per-use cost is real: image generation per beautify, priced like the
  creative panel's image calls (order of a few cents per image at
  gpt-image-1 medium), plus latency of seconds, plus a model-quality lottery
  that extraction tools (Receipt, Poster) do not have. Needs the same
  charge-only-on-success pattern the other AI tools use.
- Needs failure handling in the Bum Bum error system (Pet Pet reports,
  Bum Bum handles) for bad generations, and a retry that does not double-charge.
- Realistic effort: 2-3 days including prompt iteration on real sketches,
  because output quality IS the product here.

What it proves: whether a transformation with a real job (a logo a
solopreneur can use) holds the traffic Doodle's promise already attracts.
Success read: beautify completion rate and second-generation rate (people
iterating = people who care about the output).

Risk: quality variance. A receipt extraction is either right or fixable by
editing a field; a generated logo is a lottery ticket. One bad first result
ends the session and there is no edit-the-field fallback.

## Recommended sequence (the cheap test first)

1. Pull the PostHog funnel (opened -> first stroke -> export, export stroke
   counts) before building anything. Ten minutes, settles how bad the cliff
   is and where.
2. Build Annotate on staging (half day, zero per-use cost). Swap the paused
   pin's promise to annotation and re-run it for a week at the same spend
   shape as before.
3. Verdict rule set in advance: if export rate at least doubles versus the
   blank-canvas baseline, Doodle has a job and Sketch-to-Logo becomes worth
   its 2-3 days as the upsell version of the same tool. If export rate stays
   flat, retire Doodle and put the pin budget behind Receipt Scanner /
   Poster to Calendar, which the CTR ranking already voted for.

Either way the paused ad stays paused until step 2 is live; sending more
traffic at the blank canvas buys no new information.
