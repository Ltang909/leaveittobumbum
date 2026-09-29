# Guest actions: use tools without signing up

Status: spec (not built)
Date: 2026-09-29
Related goal: Bum Bum pricing funnel — free-to-paid loop (free use with no card
required, visible meter from the first action, a real upgrade offer at the
limit, PostHog funnel tracking).

## Problem

Every tool endpoint currently calls `requireUser()`, which 401s with "Sign in
to continue" for anyone without an account. Anonymous visitors bounce before
they ever feel the product. We want: try the tools with zero signup, and only
ask for an account once they've used a set number of actions.

The tracking question: how do we count a stranger's actions without an account?
Answer: we give every stranger a lightweight server-side identity — a shadow
guest account — and meter them exactly like a signed-in user.

## Non-goals

- Bulletproof abuse prevention. Cookie-clearers get a fresh allowance; that's
  accepted. The goal is conversion, not DRM. (Determined abusers were never
    going to pay; the engineering cost of stopping them exceeds their value.)
- Cross-device guest history. Guests are device-bound by design — "sign up to
  sync across devices" is a conversion lever, not a bug.
- Browser fingerprinting. Creepy, unreliable, and buys nothing over a random
  ID. We do not fingerprint.

## Current state (grounded in `hostinger/public_html/`)

- Auth: `startSecureSession()` — 30-day persistent PHP session,
  `$_SESSION['user_id']`. `currentUser()` / `requireUser()` in
  `api/_bootstrap.php`.
- Metering: `usage_periods(user_id, period_key, used_actions, included_actions)`
  and `action_ledger(user_id, period_key, tool_key, idempotency_key)`.
- `consumeAction(int $userId, string $plan, string $period, string $tool,
  string $idempotency)` — transactional, idempotent, enforces `PLAN_LIMITS`
  (`free => 75, helper => 1500, operator => 6000`). Returns
  `limit_reached` when the cap is hit.
- `usageFor(array $user)` feeds the header pill (`includes/meter.php`).
- Tool pattern (e.g. audiogram): expensive work is free (transcription), the
  action is consumed on `action=complete` after the client reports a finished
  render. Failed renders cost nothing; idempotency keys prevent double-counts.
- Lazy table creation via `CREATE TABLE IF NOT EXISTS` (see
  `ensureToolRequestTables()`).

## Design

### 1. Guest identity: server-minted, cookie-carried, HMAC-signed

- On first tool use (or first visit to a tool page), the server mints a
  UUIDv4 guest ID and sets a long-lived cookie `bb_guest=<id>.<sig>` where
  `sig = HMAC-SHA256(id, guest_secret)`, `Secure`, `HttpOnly`, `SameSite=Lax`,
  `Path=/`, 1-year expiry.
- `guest_secret` is a new value in the server config
  (`leaveittobumbum-config.php`). If it is missing, guest mode is disabled and
  endpoints fall back to `requireUser()` (fail closed).
- The signature means clients can't forge or swap guest IDs. They *can* delete
  the cookie and get a fresh guest — accepted (see Non-goals).
- No PII is ever attached to a guest. Store a salted SHA-256 of the IP, not
  the raw IP.

### 2. Schema (additive only — no changes to existing rows)

```sql
CREATE TABLE IF NOT EXISTS guests (
    id CHAR(36) NOT NULL PRIMARY KEY,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_seen_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ip_hash CHAR(64) NOT NULL DEFAULT '',
    ua_hash CHAR(64) NOT NULL DEFAULT '',
    converted_user_id INT UNSIGNED NULL DEFAULT NULL,
    KEY idx_converted (converted_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE usage_periods
    ADD COLUMN guest_id CHAR(36) NULL DEFAULT NULL,
    ADD UNIQUE KEY uq_guest_period (guest_id, period_key);

ALTER TABLE action_ledger
    ADD COLUMN guest_id CHAR(36) NULL DEFAULT NULL,
    ADD UNIQUE KEY uq_guest_idem (guest_id, idempotency_key);
```

(MySQL permits multiple NULLs in a UNIQUE key, so existing user rows are
unaffected. Tables are created lazily on first guest use, following the
existing `ensureToolRequestTables()` pattern.)

Guest period key: the fixed string `lifetime` — guests get 15 lifetime
actions, never a monthly reset. Guest limit is a named constant next to
`PLAN_LIMITS`:

```php
const GUEST_ACTION_LIMIT = 15;
```

### 3. The subject abstraction

Introduce one code path for "who is being metered":

- `currentSubject(): array` → `['type' => 'user'|'guest', 'id' => ..., 'plan' => ...]`
  - Signed in → user path, unchanged behavior (team billing via
    `billingUserId()` still applies).
  - Not signed in + valid `bb_guest` cookie → guest path.
  - Not signed in + no/invalid cookie → mint a guest, set the cookie.
- `requireSubject()` replaces `requireUser()` in tool endpoints. Nothing
  returns 401 for missing accounts anymore.
- `consumeAction()` gains a subject: internally it already keys on
  `(user_id, period_key)` / `(user_id, idempotency_key)`; extend to
  `(guest_id, ...)` when the subject is a guest. Same transaction, same
  idempotency semantics, limit from `GUEST_ACTION_LIMIT`.
- Guest `limit_reached` responses include `signup_required: true` plus
  `used`/`limit` so the frontend can render the upgrade offer.

### 4. Merge on signup (the money step)

When a guest creates an account or logs in while holding an unconverted
`bb_guest` cookie:

1. In a transaction: `UPDATE usage_periods SET user_id = ?, guest_id = NULL
   WHERE guest_id = ?` (and same for `action_ledger`); `UPDATE guests SET
   converted_user_id = ? WHERE id = ?`.
2. If the user already has a `usage_periods` row for the current period, sum
   `used_actions` instead of creating a duplicate.
3. If a guest ledger `idempotency_key` already exists for the user (vanishingly
   rare), keep the user's row and skip the guest's.
4. PostHog: `alias` the guest distinct ID to the new user ID so the funnel
   stays continuous (see Analytics).

Net effect: the guest's used action count merges into the new account's
current period. Note: guest-created records in stateful tools (notes,
cutline, purrsuit, etc.) are ephemeral and NOT persisted for guests, so
conversion copy must not promise that creations carry over — only that the
free actions continue on the new account.

### 5. The threshold moment (UX contract)

When `signup_required: true` comes back, the frontend shows an upgrade offer,
not a dead end:

- Headline with *their* numbers: "You've made N things with Bum Bum."
- What they keep: their remaining free actions continue on the new account
  (guest usage merges into the account's current period). Do NOT promise that
  guest creations carry over: stateful guest output is ephemeral.
- One-field start: email only → magic link (no password). Full signup is a
  second step, not the price of admission.
- Secondary lever in the same screen: "Use Bum Bum on your phone too — sign
  up to sync."

The header pill (`includes/meter.php`) also changes: signed-out visitors see
"N of 15 free actions" from the very first page view (tool pages mint the
guest on view via `pageSubject()`), per the goal's "visible meter from the
first action."

### 6. Abuse & privacy guardrails

- **Per-IP velocity check:** count distinct guest IDs minted per IP-hash per
  24h. Past a threshold (propose 20, tune later), require a CAPTCHA (or block)
  before minting more. This catches cookie-clearing scripts, not humans.
- **Costly-first-step tools:** the audiogram pattern (free transcription,
  consume on complete) lets guests burn Groq budget without spending actions.
  For guests, consume the action at *start* and refund on reported failure —
  or rate-limit the free step per guest. Decision needed before build (lean:
  consume-at-start for guests only).
- **Retention:** purge unconverted guest rows and their ledger entries after
  90 days (cron). Keeps the tables small and is the honest privacy story:
  "we forget strangers after 90 days."
- **No fingerprinting, no raw IPs.** Salted hashes only.

### 7. Analytics (PostHog)

- Guest distinct ID = the guest UUID. All existing `posthogCapture()` calls
  keep working; they just take the guest ID when there's no user.
- New funnel events: `guest_created`, `guest_action_consumed`,
  `guest_limit_reached`, `guest_converted` (with `guest_actions_used`
  property — the single most important number for tuning the threshold).
- On signup, `alias(guest_id → user_id)` so pre/post-signup is one journey.

### 8. Rollout

- `GUEST_ACTIONS_ENABLED` constant (default true). One-line kill switch: when
  false, `requireSubject()` behaves exactly like today's `requireUser()`.
- Staging first (it shares the prod DB — guest tables are additive, and guest
  rows are clearly separable by `converted_user_id IS NULL`, so staging
  traffic is easy to identify and purge).
- Ship order: schema + subject abstraction + guest minting → merge on signup
  → threshold UX → analytics → abuse guardrails.

## Open questions (decisions needed before build)

1. **Threshold number.** DECIDED: 15 lifetime guest actions (2026-09-29).
   The `guest_converted` event with `guest_actions_used` will tell us where
   the real drop-off cliff is — tune with data.
2. **Consume timing for guests** on tools with a free expensive step
   (audiogram transcription): consume-at-start vs consume-on-complete.
   Recommendation: consume-at-start for guests only.
3. **Magic-link-only signup** at the threshold vs full signup form. Leaning
   magic link — lowest friction at the highest-intent moment.
4. **Guest access to downloads/sharing:** allow fully, or require email to
   download after the threshold? Soft-degradation option if the hard paywall
   underperforms.
