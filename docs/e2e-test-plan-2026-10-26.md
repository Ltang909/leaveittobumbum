# E2E Test Plan — leaveittobumbum.com

**Date:** 2026-10-26 · **Suite:** `tests-e2e/` (Playwright) · **Workflow:** `.github/workflows/e2e-weekly.yml`
**Status:** Full suite executed against staging on 2026-10-26: 81 passed, 2 failed (both are Finding F1, the known staging divergence the prompt spec exists to catch), 1 skipped (billing, awaiting test-account secrets). See §9–§10.

## 1. What this suite is for

leaveittobumbum.com ships daily, often several times a day, across two
surfaces that deploy independently: a Next.js static export (homepage,
blog, guides, tools index, team, pricing) and PHP tool pages + APIs under
`hostinger/public_html/`. The risky seams are exactly the ones nobody
clicks through by hand every day:

- the homepage front desk routing text, files, and @ pins into 21 tools;
- desk → tool handoffs (drop-in field fills, file auto-runs, banners);
- guest metering and the delayed account prompt;
- the blog/footer/template chrome shared by both stacks;
- each tool's primary control simply existing and working.

This suite encodes those behaviours as executable checks so a Monday
report says "the desk still files invoices" instead of hoping.

## 2. Environments

| Env | URL | Role |
| --- | --- | --- |
| **Staging** (default) | `https://staging.leaveittobumbum.com` | The only place stateful or billable specs run. |
| Production | `https://leaveittobumbum.com` | **Read-only smoke subset only.** |

Enforcement is structural, not by convention: `playwright.config.ts`
reads `BASE_URL` (default staging) and, when it points at production,
only defines the `smoke` projects. The weekly and billable projects —
anything that files a record, spends an action, or calls a provider —
do not exist in a production run. The billing spec additionally refuses
any non-staging host itself.

Staging caveat that shapes everything below: **staging is a subfolder of
the production site and shares the production database.** A "staging
test" that files an invoice writes a production row. Data rules (§5)
exist because of this.

## 3. Tiers

### Tier 1 — PR / on-demand smoke (`specs/smoke/`, projects `smoke`, `smoke-mobile`)

Public, read-only, deterministic. Homepage desk render, tools index,
team copy, blog listicles, footers, sitemap, tombstones. Runs in a couple
of minutes, against staging by default, safe against production. This is
the tier to run after any template/chrome/blog change.

### Tier 2 — Weekly full (`specs/weekly/`, project `weekly`)

Staging only. The interactive core: desk routing end to end, desk → tool
handoffs, all 21 tool pages (load + primary control + validation state),
the deterministic client-side happy paths (image converter, cropper, QR,
doodle), the ffmpeg.wasm video tools (tagged `@slow`), and the delayed
guest prompt. Run by the weekly workflow (§7); runnable locally any time.

### Tier 3 — AI-billable, gated (`specs/billable/`, project `billable`)

Staging only, and the project is only defined when `E2E_BILLABLE=1`.
One real happy-path run per AI/Groq-backed tool (vision reads, content
generation, mention scan, gap analysis, transcription, audiogram render)
plus the Stripe test-mode checkout. These spend real provider calls and
real actions, so they run deliberately — after touching an AI tool, or
from a manual workflow dispatch with the billable toggle on — never as
a default schedule.

## 4. Flakiness rules

- **Retries:** 2 in CI, 1 locally (config). A test that only passes on
  retry is a bug to investigate, not a pass to celebrate; the HTML
  report marks retried tests.
- **No fixed sleeps** where avoidable. All synchronisation is
  Playwright auto-waiting (`expect(locator).toBeVisible()`),
  `waitForURL`, download events, or `expect.poll` for state machines
  (the desk's ~4s crossing animation, ffmpeg.wasm renders, LLM latency).
  The suite contains zero `waitForTimeout` calls.
- **Determinism levers:** the desk's random per-session chip draw is
  seeded via `sessionStorage` (`bb-desk-chips`) so chip tests are
  stable; the rotating composer placeholders are asserted by their
  pinned variants, not the rotation.
- **LLM-shaped assertions:** routing specs assert the visitor-visible
  outcome (success line naming the tool, the Hmm picker, the guidance
  message), never router internals or exact model wording beyond the
  product's own summary templates.
- **Isolation:** every test gets a fresh browser context = a fresh
  guest cookie and clean storage. Tests never share a guest (the
  delayed-prompt spec does its two filings inside one test on purpose).
- **Traces** are retained on failure (`trace: retain-on-failure`) and
  uploaded with the report artifact.

## 5. Test-data strategy

Staging shares the production DB, so the rules are strict:

1. **Dedicated test account first.** Anything that needs a signed-in
   subject (billing) uses `E2E_TEST_EMAIL` / `E2E_TEST_PASSWORD` from
   env/GitHub secrets. Specs **skip with a clear message** when the
   credentials are absent — they never fall back to a personal account.
2. **Guest filings are a small, named set.** Only the desk-routing set
   files as a guest: one subscription ("Netflix $15.99 a month"), the
   pinned-invoice anchor ("hud.io $20.00"), and the same pair inside the
   guest-prompt spec. These create tiny, recognisable rows — the same
   filings used to verify routing by hand when the desk shipped.
3. **Guest-mint budget.** New guests are minted per cookie and capped
   per IP per day (20). Fresh-context-per-test keeps the suite at
   roughly a dozen mints per full run; do not add filing tests casually.
4. **No real payments, ever.** Billing runs on staging's Stripe **test**
   keys with the test card `4242 4242 4242 4242`. Test mode completes a
   test subscription; no real money moves. Billing specs never run
   against production (structurally impossible, §2).
5. **No real email, no deletes.** No spec triggers outbound email
   (invoice chases are never sent), and no spec deletes data — there is
   no cleanup API, so the suite simply keeps its footprint to the named
   set in rule 2 plus test-account data.
6. **Fixtures are synthetic.** Generated media (drawn receipt/poster/
   meal, tone/spoken MP3, 2s MP4, one-page PDF resume) carries no real
   personal data. See `tests-e2e/scripts/make-fixtures.mjs`.

## 6. Coverage matrix

| Surface | Spec | Tier | What is asserted |
| --- | --- | --- | --- |
| Homepage front desk render | `specs/smoke/public.spec.ts` | smoke | Desk card, greeting, @ hint, composer, Do it!, attach btn, 4 chips, toolbox link |
| Tools index `/tools/` | `specs/smoke/public.spec.ts` | smoke | All 21 registry tools listed by name |
| Team page | `specs/smoke/public.spec.ts` | smoke | Crew copy verbatim (roles, responsibilities, birthdays) |
| Sitemap | `specs/smoke/public.spec.ts` | smoke | `/blog/` + 3 article URLs present; no `cat-translator` |
| Tombstones | `specs/smoke/public.spec.ts` | smoke | `/api/diag-admin.php` and `/tools/cat-translator/` return 410 |
| Footers (Next + PHP) | `specs/smoke/footer.spec.ts` | smoke | LinkedIn → `/in/leaveittobumbum/`, Pinterest → the ca.pinterest.com profile, Instagram intact, zero facebook.com — on homepage, a tool page, and a blog article |
| Blog index + 3 listicles | `specs/smoke/blog.spec.ts` | smoke | 200, `blog-wide`/`blog-head`, BlogPosting + ItemList JSON-LD, competitor product links are vendor homepages (Source citations exempt; one documented Pipedrive exception), ItemList URLs are roots, zero em/en dashes in prose |
| Desk routing: subscription filing | `specs/weekly/desk-routing.spec.ts` | weekly | "Netflix $15.99 a month" → success naming Netflix + Subscription Tracker |
| Desk routing: keyword layer | `specs/weekly/desk-routing.spec.ts` | weekly | "add music to my video" → Video MP3 Overlay suggestion + navigation |
| Desk routing: @ pin absolute | `specs/weekly/desk-routing.spec.ts` | weekly | Pinned Invoice Reminder + hud.io text files an Invoice Reminder via the pin |
| Desk routing: ambiguity | `specs/weekly/desk-routing.spec.ts` | weekly | Gibberish → "Hmm, where should this go?" picker incl. Other chip |
| Desk routing: staged photo | `specs/weekly/desk-routing.spec.ts` | weekly | Unpinned photo stages with guidance naming Receipt Reader / Poster to Calendar / Meal Scanner; submit without a pin teaches the @ pick; nothing routes |
| Desk chips + @ menu | `specs/weekly/desk-routing.spec.ts` | weekly | A chip pins its tool (seeded draw); typing `@rec` fuzzy-matches and pins Receipt Scanner |
| Handoffs: text drop-ins | `specs/weekly/handoffs.spec.ts` | weekly | Desk → purr-code `#pcUrl`, ghostwriter `#gwPasteText`, sop-ify `#sopDump`, hearsay `#hsBrand` filled; full-width `.bb-handoff` banner with the "dropped it in for you" line |
| Handoffs: file auto-run | `specs/weekly/handoffs.spec.ts` | weekly | Desk-pinned receipt photo lands in Receipt Scanner (`?from=capture`) and auto-runs (working indicator, results, or error scene) |
| All 21 tool pages | `specs/weekly/tools.spec.ts` | weekly | 200, primary control attached, H1 visible, no PHP fatal |
| Validation states | `specs/weekly/tools.spec.ts` | weekly | Ghostwriter generate disabled when empty; SOP short-dump status; Hearsay brand-first status; Gap Scout analyze disabled; Receipt process hidden with empty queue; Invoice form native validation blocks an empty amount |
| Client-side happy paths | `specs/weekly/tools-happy.spec.ts` | weekly | Image Converter PNG→JPG download; Image Cropper drag-crop → result + download; QR canvas renders a real pattern; Doodle stroke → PNG download |
| Video tools | `specs/weekly/tools-video.spec.ts` | weekly `@slow` | Trimmer, Converter, MP3 Overlay complete a real ffmpeg.wasm job on the 2s fixture |
| Delayed guest prompt | `specs/weekly/guest-prompt.spec.ts` | weekly | No nudge/pill before 2 actions; nudge after the 2nd filing; dismiss silences it (see Finding F1) |
| Vision happy paths | `specs/billable/billable.spec.ts` | billable | Receipt Reader, Poster to Calendar, Meal Scanner each complete one real read |
| Text AI happy paths | `specs/billable/billable.spec.ts` | billable | Ghostwriter pack, SOP playbook, Hearsay scan, Gap Scout analysis |
| Audio happy paths | `specs/billable/billable.spec.ts` | billable | Notes transcription via desk audio handoff; Audiogram transcribe + render; Clips recorder starts or explains itself |
| Billing | `specs/billable/billing.spec.ts` | billable | Signed-in pricing → Helper → embedded Stripe checkout → test card 4242… → completion page (skips without test-account env) |

## 7. Cadence and ownership

- **Weekly workflow** (`.github/workflows/e2e-weekly.yml`): Mondays
  14:00 UTC, staging, smoke + weekly projects, HTML report uploaded as
  an artifact (30-day retention). Manual dispatch can choose the suite
  and opt into the billable tier. Not chained to deploys.
- **Local runs:** see `tests-e2e/README.md`.
- **Failures are findings.** A red spec means the site or the spec is
  wrong; decide which from the trace before changing either. Bending a
  spec to a site bug is how suites rot.

## 8. Open setup items

1. **Test-account secrets:** create a dedicated test account on the site
   and set `E2E_TEST_EMAIL` / `E2E_TEST_PASSWORD` as GitHub repo secrets
   (and locally) — billing and any future signed-in specs skip until
   then.
2. **First weekly run review:** confirm the workflow's artifact and the
   guest-mint budget hold up in CI (shared egress IP).
3. **TTS fixture:** machines without ffmpeg's `flite` filter generate a
   tone MP3; transcription specs accept any terminal state. A committed
   spoken-word fixture would tighten the Notes/Audiogram assertions.

## 9. Findings from the first run (2026-10-26)

- **F1 — Delayed guest prompt has not fully landed on staging.**
  Production commit `bcf1d5a` ("Delay guest account prompt until after
  2 actions") lives on `main` only. On the staging line: only
  meal-scanner and poster-to-calendar carry the gated `.bb-guest-nudge`
  markup; the other tool pages still show the old always-on guest
  nudge; `includes/meter.php` lacks the used<2 header-pill gating; and
  `includes/site-header.php` lacks the dismiss handler, so the nudge's
  × is inert on staging. `guest-prompt.spec.ts` encodes the intended
  behaviour and fails on exactly the pill and dismiss assertions until
  the lines converge. (Production is unaffected — it has the full
  commit.)
- **F2 — Audiogram's Render button accepts clicks it silently ignores.**
  The studio section (with the Render button) unhides the moment
  transcription *starts*, but the render handler returns silently until
  `setupStudio()` has run at the end of transcription (its `audioEl`
  guard). A visitor who clicks Render during the ~30s transcription
  gets no feedback and has to click again. Minor, but real: the spec
  now waits for the genuine completion signals (style step hides,
  preview clock shows the duration) before clicking. Worth a disabled
  state or a "still transcribing" note on the site's side.
- **Environment note (not a site finding):** in the REN sandbox VM,
  Chromium cannot use the egress proxy directly
  (`ERR_TUNNEL_CONNECTION_FAILED`) and the proxy's MITM CA breaks CDN
  subresources (Gap Scout's pdf.js). `scripts/local-proxy.mjs` +
  `LOCAL_PROXY` exist for this; CI needs neither.

## 10. First run (2026-10-26, staging, verified)

| Project | Result |
| --- | --- |
| smoke (Desktop Chrome) | **12 passed** |
| smoke-mobile (Pixel 7) | **12 passed** |
| weekly — desk-routing | **7 passed** |
| weekly — handoffs | **5 passed** (incl. the receipt auto-run) |
| weekly — tools (21 pages + 6 validation states) | **27 passed** |
| weekly — tools-happy | **4 passed** |
| weekly — tools-video `@slow` | **3 passed** (real ffmpeg.wasm jobs) |
| weekly — guest-prompt | **1 passed, 2 failed** — both failures are Finding F1, by design |
| billable (E2E_BILLABLE=1) | **10 passed, 1 skipped** — receipt/poster/meal vision reads, ghostwriter, SOP, hearsay, gap-scout, notes transcription, audiogram render, clips; billing skipped (no test-account credentials yet, §8.1) |

**Totals: 81 passed, 2 failed (both = F1), 1 skipped.**
