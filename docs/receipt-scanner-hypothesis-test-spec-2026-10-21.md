# Receipt Scanner hypothesis test: spec for the two test tools

Prepared 2026-10-21 by REN. No tool selected by Leon yet, nothing
built, nothing shipped. This spec exists so the pick is a one-line
decision and the build can start the same day.

## What we know

- Leon reported on Oct 21 that the Receipt Reader (Receipt Scanner)
  post is showing significantly higher CTR than all other Bum Bum
  tool posts.
- His hypothesis, in his own framing: either the audience wants
  image to CSV transformation in general, or they want the
  receipt and bookkeeping side specifically.
- He rejected a bank statement tool outright: nobody trusts Bum Bum
  with full bank statements. That tool is out of this test. The
  browser-only variant (file never leaves the user's tab) is parked
  as a later privacy experiment, not part of this test, because it
  changes format and privacy at the same time and would contaminate
  the read.

## The design: hold one axis still, change the other

Receipt Scanner is photo in, spreadsheet out, in the bookkeeping
domain. Each test tool changes exactly one of those.

### Test tool 1: Business Card to Spreadsheet (format test)

What it is: the user photographs a business card, a stack of cards,
an event badge, or a paper sign-in sheet. The tool reads name,
company, title, email, phone, and website, shows them in an editable
table, and exports a clean CSV. Same interaction as Receipt Scanner,
same mess in, magic out promise, zero bookkeeping.

What it proves: if this pulls CTR and completions anywhere near
Receipt Scanner, the format is the driver. The audience is buying
photo to table transformation, and the roadmap becomes a family of
photo to spreadsheet tools. If it flops while Receipt Scanner keeps
winning, format alone is not the story.

Build notes: reuses the Receipt Reader pipeline (photo upload up to
10 images, extraction into an editable table, save to log, CSV
export). The extraction schema is the only real change. Fields:
first name, last name, company, title, email, phone, website,
notes. Low trust risk: a business card is information its owner
hands out on purpose.

### Test tool 2: Invoice Generator (domain test)

What it is: the user types or pastes the rough version of a job in
plain words, or fills simple line item fields: client name, items,
quantities, rates. The tool returns a finished invoice: numbered,
dated, tax calculated, total added, due date and payment terms set,
downloadable as a clean PDF the user can send.

What it proves: it lives in the same emotional territory as Receipt
Scanner, money admin relief, while sharing almost nothing else with
it. No photo in, no CSV out. If it pulls CTR near Receipt Scanner,
the domain is the driver: this audience clicks on anything that
makes getting paid and tracking money less painful, and the roadmap
goes deep on finance tools instead of wide on photo tools.

Why this one and not another bookkeeping tool: the data is what the
user charges for their own work. Nothing sensitive to steal or leak,
so the trust problem that killed the bank statement idea never
appears. It also pairs with the existing Invoice Chaser: one tool
creates the invoice, the other chases it when it goes unpaid. If
the domain wins, Bum Bum owns both ends of getting paid.

Build notes: no image pipeline needed. Form plus a small plain text
parser for the paste a rough job path, tax and totals calculated in
code (never by the model), PDF rendered from a fixed template.
Invoice numbering per user account, guest use follows the same
free actions economics as every other tool.

## How the test runs, so the read is clean

- Same post format for both tools as the Receipt Scanner posts:
  same card treatment in the feed creative, same hook structure,
  same caption shape, short tool URL printed in the caption.
- Same free actions economics and the same delayed account prompt
  behaviour (prompt after the second completed action) as Receipt
  Scanner has today.
- Judge on the same two numbers Leon is already watching: post CTR
  and tool completion rate. Not signups alone.
- Run each tool's post in the same channel mix Receipt Scanner is
  getting now, and compare against Receipt Scanner's current CTR,
  not against the all tools average.

## How to read the result

- Both pop: transformation itself is the product. Build photo to
  table for everything, and keep the finance line as one branch.
- Only the card tool pops: format wins. Receipt Scanner was a
  spreadsheet tool in a finance costume.
- Only the invoice tool pops: domain wins. Go deep on money admin:
  invoicing, chasing, expense capture, tax time exports.
- Neither pops: the win is receipt specific, the shoebox of guilt
  and tax dread. That is still a useful answer: double down on
  receipt adjacent tools and stop spreading builds across domains.

## What Leon decides

Pick one tool to build first, or both in sequence. REN's
recommendation, from the Oct 21 main chat explanation: build both,
invoice first if only one slot exists this week, because it is the
cleaner domain test and a product worth owning even if it loses.
