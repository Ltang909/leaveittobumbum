# Receipt capture metering leak: diagnosis and fix spec
Prepared 2026-10-26 (evening), from local repo code in ~/workspace/bumbum-build/hostinger/public_html/
Status: diagnosed, NOT built, NOT shipped. Needs Leon's go, then staging first like everything else.

## Symptom (observed live on production, Oct 26)
During the keyword-routing verification, a receipt logged as text through the desk ("Starbucks $6.40") filed fine but did not advance the actions counter, while every other filing in the same session charged exactly 1 action. Flagged in chat by REN at 2026-10-26T20:09:47Z; no follow-up recorded after that.

## Root cause (verified in code)
- api/capture.php states the intended contract in its header: "One capture costs exactly one action: the tool's. Billing: the router itself is free; the tool API consumes its normal single action."
- For receipt-reader, capture_targets() maps the desk to endpoint /api/tools/receipt-reader.php with action 'save'.
- In receipt-reader.php, consumeSubjectAction() is called ONLY in the 'scan' branch (line 69, key 'receipt-scan-...'). The 'save' branch inserts the log (or, for guests, returns signup_required) and never consumes.
- Every other capture-native tool meters inside the action capture actually calls: invoice-chaser 'add', cutline 'add', corporate-bum-bum 'add', notes 'finalize' all call consumeSubjectAction.
- Why 'save' is unmetered in the tool-page flow: on the Receipt Reader page itself, a photo scan charges via 'scan' first, then 'save' stores the result. Charging in 'save' unconditionally would double-charge the normal photo flow. The desk path never calls 'scan', so it falls through the gap.

## Impact
- Anyone can log unlimited text receipts through the homepage desk / mini-desk without spending actions, bypassing the guest limit and the paid tiers for that one tool.
- It also skews the live delayed-prompt before/after test (goal: Bum Bum pricing funnel): text-logged receipts never count toward the 2-action trigger, so desk receipt users are under-counted in the prompt window.

## Fix options
Option A (recommended): meter in receipt-reader 'save' only when the request carries the capture loopback idempotency key. capture.php already sends idempotencyKey = 'cap-' . idem with every loopback payload (capture_tool_payload base). In the 'save' branch: if idempotencyKey starts with 'cap-', call consumeSubjectAction($subject, 'receipt-reader', 'receipt-capture-' . key) before inserting, and return limit_reached the same way capture.php expects (402 handling already exists on the capture side). Tool-page saves do not carry a 'cap-' key, so the photo flow is untouched and cannot double-charge.
- Must check first: confirm the Receipt Reader page's own save calls never send a 'cap-' prefixed key (they should not; the prefix is capture.php's loopKey namespace).
- Guest handling: for guests, 'save' currently returns signup_required without storing. Decide whether a guest text receipt should consume a guest action before that return, to match how the other tools treat guest captures and to keep the delayed-prompt trigger honest. Recommended: consume first, same as Option A, so guest desk receipts count toward the 2-action prompt.

Option B: have capture.php call the 'scan' action before 'save' for receipt-reader. Simpler to read, but 'scan' validates its own key format (16-128 chars, receipt-scan- prefix added server-side) and returns no log; it adds a second loopback round-trip and a second place where a partial failure (charged but not saved) can happen. Option A keeps charge and save in one request.

## Test plan (staging)
1. Desk text receipt as guest: files, actions counter +1, second one +1 again, prompt trigger counts it.
2. Desk text receipt as logged-in user: files, +1, receipt appears in the log.
3. Photo receipt on the tool page: scan + save still charges exactly 1 total (regression check for double-charge).
4. Manual save on the tool page with no scan: match current behaviour unless Leon decides manual entry should also cost 1 (open question, not assumed here).
5. Limit case: at the action limit, desk text receipt returns the limit message instead of filing free.
6. Unpinned controls from the Oct 26 verification still pass: pinned @Invoice Reminder hud.io case, Netflix subscription case, keyword "add music to my video" case.

## Open question for Leon
Manual receipt entry typed directly on the Receipt Reader tool page (no photo, no desk): free today by the same 'save' path. In scope for this fix, or is typed entry intentionally free and only the desk path should meter? Option A as specced only fixes the desk path.
