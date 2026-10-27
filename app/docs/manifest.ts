import { GUIDES } from "./guides";
import { TOOL_DOCS } from "./tool-docs";

// The docs table of contents. The sidebar, the docs home, prev/next
// links, and search all read from here, so a page added to guides.ts or
// tool-docs.ts appears everywhere at once. That is the cadence mechanism:
// one content entry, no other wiring.
export interface DocsEntry {
  href: string;
  title: string;
  summary: string;
}

export interface DocsSection {
  title: string;
  entries: DocsEntry[];
}

const guideEntries = (section: string): DocsEntry[] =>
  GUIDES.filter((g) => g.section === section).map((g) => ({
    href: `/docs/${g.slug}/`,
    title: g.title,
    summary: g.summary,
  }));

export const DOCS_SECTIONS: DocsSection[] = [
  { title: "Getting started", entries: guideEntries("Getting started") },
  {
    title: "The toolbox",
    entries: TOOL_DOCS.map((t) => ({
      href: `/docs/tools/${t.key}/`,
      title: t.name,
      summary: t.summary,
    })),
  },
  { title: "Trackers and email", entries: guideEntries("Trackers and email") },
  { title: "Getting a tool built", entries: guideEntries("Getting a tool built") },
  { title: "Account and help", entries: guideEntries("Account and help") },
];

export const DOCS_FLAT: DocsEntry[] = DOCS_SECTIONS.flatMap((s) => s.entries);

export function prevNext(href: string): { prev?: DocsEntry; next?: DocsEntry } {
  const i = DOCS_FLAT.findIndex((e) => e.href === href);
  if (i < 0) return {};
  return { prev: DOCS_FLAT[i - 1], next: DOCS_FLAT[i + 1] };
}
