# Error scenes spec — "Pet Pet reports it. Bum Bum handles it."

Status: copy final, images staged. Nothing deployed.
Assets live in `public/bum/` (served as `/bum/...`), resized to 900px wide (~130-150 KB each).

## The system

Every error state on every tool follows the same two beats:

1. **Pet Pet reports it.** One short line naming the problem, in middle-manager voice. The comedy lives here.
2. **Bum Bum handles it.** One or two sentences: ownership where it is ours ("my side, not yours"), what is safe, and exactly one next step. The trust lives here.

Rules:
- The user always gets three things: the problem named plainly, ownership, one clear next step.
- Never blame the user for a server-side failure. When the input genuinely is the problem, say so warmly and say what to change.
- Retry never clears the user's input. The form state survives the error.
- No em dashes anywhere in this copy. House rule.
- One CTA button per error. The CTA label is the next step, verbatim from the copy table.

## Full scene vs compact

- **Full scene** (image ~420px wide, rounded corners, copy under it, CTA under the copy): blocking errors that stop the task and replace the working area — upload failed, file too large, connection lost mid-task, processing failed.
- **Compact** (96px thumbnail left, one combined line right, inline where the field is): validation moments the user can fix in place without losing flow — empty input, empty link, invalid file type.
- A state starts compact. If the same action fails twice in a row, escalate to the full scene with the same copy. Second failure deserves the full cats.

## State table

### empty_input — compact (full on repeat)
- Trigger: submit with no file, no text, nothing to work on.
- Image: `/bum/error-empty.jpg` (Pet Pet holding up the empty tray)
- Pet Pet: "There is nothing in this."
- Bum Bum: "Nothing in, nothing out. Add your file or text and I will get to work."
- CTA: "Add something"
- Alt: "Pet Pet the white cat holding an empty tray, looking puzzled, while Bum Bum sits beside a stack of folders"

### upload_failed — full
- Trigger: upload request fails, times out, or the file will not transfer.
- Image: `/bum/error-upload.jpg` (folder wedged in the cat flap)
- Pet Pet: "This one would not go through. Not my department."
- Bum Bum: "It is mine. Try the upload once more. If it sulks again, a smaller file or a different format usually fixes it."
- CTA: "Try again"
- Alt: "Bum Bum pushing a thick folder through a cat flap while Pet Pet watches, worried"

### file_too_large — full
- Trigger: selected file exceeds the tool's size limit. Name the limit in the UI next to the CTA when the tool has one.
- Image: `/bum/error-too-large.jpg` (Pet Pet under the giant box)
- Pet Pet: "It is very large. I have concerns."
- Bum Bum: "Fair concern. Shrink it a little and hand it back to me."
- CTA: "Choose a smaller file"
- Alt: "Pet Pet flattened under a huge cardboard box of papers while Bum Bum steadies the box"

### connection_lost — full
- Trigger: fetch/network failure, offline event, or a request dying mid-processing.
- Image: `/bum/error-offline.jpg` (unplugged cable at the router)
- Pet Pet: "The internet has left the building."
- Bum Bum: "Your work is still right here. Reconnect and pick up where you left off."
- CTA: "Reconnect"
- Alt: "Bum Bum holding an unplugged network cable next to a router while Pet Pet gasps"

### processing_failed — full (IMAGE PENDING)
- Trigger: the tool itself errors during processing — the catch-all for our side breaking.
- Image: PENDING, to be generated: Pet Pet sliding a small smoking machine toward Bum Bum. Slot reserved as `/bum/error-processing.jpg`. Until it exists, reuse `/bum/error-upload.jpg`.
- Pet Pet: "Found a problem. Escalating it."
- Bum Bum: "And I have got it from here. Something jammed on my side, not yours. Your file is safe. Give me one more go."
- CTA: "Run it again"
- Alt: (with the pending image) "Pet Pet sliding a smoking machine across the desk to Bum Bum"

### invalid_type — compact
- Trigger: file format the tool cannot read.
- Image: compact reuse of `/bum/error-upload.jpg`
- Pet Pet: "It will not fit. I measured twice."
- Bum Bum: "That format is not one I can open. Try one of these instead: [tool lists its accepted formats]."
- CTA: "Choose a different file"

## Tool-specific mappings

### Purr Code (QR generator)
- Empty link field on submit → **empty_input**, copy swapped:
  - Pet Pet: "There is no link in this."
  - Bum Bum: "A Purr Code needs somewhere to point. Paste a link and I will do the rest."
  - CTA: "Add a link"
- Link resolves to a dead page (only if we check; do not block generation on it, warn after):
  - Pet Pet: "That page does not exist. I checked twice."
  - Bum Bum: "The code would still work, it would just take people to a dead end. Fix the link and I will remake it."
  - CTA: "Fix the link"
  - Compact, shown under the finished code, never instead of it.

## Implementation notes

- One shared error renderer keyed by state: `{ state, size: "full" | "compact" }` looks up image, both lines, and CTA from a single copy map. No tool hand-rolls its own error markup.
- Copy map (JS sketch):

```js
const ERROR_SCENES = {
  empty_input:      { img: "/bum/error-empty.jpg",     petpet: "There is nothing in this.", bumbum: "Nothing in, nothing out. Add your file or text and I will get to work.", cta: "Add something" },
  upload_failed:    { img: "/bum/error-upload.jpg",    petpet: "This one would not go through. Not my department.", bumbum: "It is mine. Try the upload once more. If it sulks again, a smaller file or a different format usually fixes it.", cta: "Try again" },
  file_too_large:   { img: "/bum/error-too-large.jpg", petpet: "It is very large. I have concerns.", bumbum: "Fair concern. Shrink it a little and hand it back to me.", cta: "Choose a smaller file" },
  connection_lost:  { img: "/bum/error-offline.jpg",   petpet: "The internet has left the building.", bumbum: "Your work is still right here. Reconnect and pick up where you left off.", cta: "Reconnect" },
  processing_failed:{ img: "/bum/error-processing.jpg", petpet: "Found a problem. Escalating it.", bumbum: "And I have got it from here. Something jammed on my side, not yours. Your file is safe. Give me one more go.", cta: "Run it again" },
};
```

- Pet Pet's line renders smaller and quieter (the report), Bum Bum's line is the main text (the handler). Speaker names do not need labels; the order teaches it.
- Optional: fire one analytics event per show, `error_scene_shown` with the state key, so we learn which errors users actually hit. Cheap and it tells us where the tools break in the wild.
- Images are decorative-adjacent but carry the joke; alt text above is written for screen readers. Do not lazy-hide them with empty alt.

## Open items

1. Generate the fifth image (processing_failed, smoking machine) to complete the set.
2. Leon's deploy call: files are staged locally in the repo only, nothing committed or pushed.
