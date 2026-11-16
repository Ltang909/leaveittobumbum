<!-- BEGIN:nextjs-agent-rules -->

# This is NOT the Next.js you know

This version has breaking changes. APIs, conventions, and file structure may all differ from your training data. Read the relevant guide in `node_modules/next/dist/docs/` (resolved from this file's directory; in monorepos the `next` package may not be visible from the repo root) before writing any code. Heed deprecation notices.

This block is written and re-added by `next dev`. Verify at `node_modules/next/dist/server/lib/generate-agent-files.js`. Removing it from a diff only re-creates the uncommitted change; committing it with your work keeps the tree clean.

<!-- END:nextjs-agent-rules -->


# Docs cadence (standing rule, set 2026-10-27)

Every new tool or user-facing feature ships with documentation in the same change. No exceptions, no "docs later" tickets.

- New tool: add its entry to `app/docs/tool-docs.ts` (summary, steps, good-to-know, data note, tips, FAQ). The docs page, sidebar entry, docs-home card, and prev/next links are generated from that one entry. Sitemap: add `/docs/tools/<key>/` to `hostinger/public_html/sitemap.xml`.
- New feature: add or update a guide in `app/docs/guides.ts` (same deal: sidebar/home/sitemap flow from the manifest, plus a sitemap line for a new slug).
- Changed behavior: update the affected tool/guide entry and its "Last updated" date in the same commit.
- Copy rules for docs: no em dashes, no tool counts in prose, display names from the tool registry, never invent limits or numbers (take them from the tool's own page or code).

# Container width (standing rule, set 2026-11-16)

Container width: article/guide pages use the full shell width like the blog (prose blog-wide, max-width none inside the 1180px shell). Never ship a content page with the narrow 780px prose container.

Headline rule: the page head carries the title as the H1. The article body opens with body text, never with the same title repeated as an H2.

Page head rule: guide pages use the blog page head (page-head shell blog-head) so the lede runs the full width. The plain .lede style caps at 610px; never ship a guide head with that cap.
