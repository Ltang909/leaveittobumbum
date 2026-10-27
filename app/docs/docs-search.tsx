"use client";

import { useMemo, useState } from "react";
import type { DocsEntry } from "./manifest";

export default function DocsSearch({ entries }: { entries: DocsEntry[] }) {
  const [q, setQ] = useState("");
  const results = useMemo(() => {
    const needle = q.trim().toLowerCase();
    if (needle.length < 2) return [];
    return entries
      .filter(
        (e) =>
          e.title.toLowerCase().includes(needle) ||
          e.summary.toLowerCase().includes(needle),
      )
      .slice(0, 8);
  }, [q, entries]);
  return (
    <div className="docs-search">
      <input
        type="search"
        value={q}
        onChange={(e) => setQ(e.target.value)}
        placeholder="Search the docs: receipts, actions, the Monday note..."
        aria-label="Search the docs"
      />
      {q.trim().length >= 2 && (
        <div className="docs-search-results">
          {results.length === 0 && (
            <p className="docs-search-none">
              Nothing matches that. Try a shorter word, or browse the sections below.
            </p>
          )}
          {results.map((e) => (
            <a key={e.href} href={e.href} className="docs-search-hit">
              <strong>{e.title}</strong>
              <span>{e.summary}</span>
            </a>
          ))}
        </div>
      )}
    </div>
  );
}
