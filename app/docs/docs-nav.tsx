"use client";

import { useState } from "react";
import { usePathname } from "next/navigation";
import { DOCS_SECTIONS } from "./manifest";

export default function DocsNav() {
  const raw = usePathname() || "/docs/";
  const path = raw.endsWith("/") ? raw : raw + "/";
  const [open, setOpen] = useState(false);
  return (
    <aside className={"docs-side" + (open ? " docs-open" : "")}>
      <button
        type="button"
        className="docs-nav-toggle"
        aria-expanded={open}
        onClick={() => setOpen((v) => !v)}
      >
        <span>Docs menu</span>
        <span aria-hidden="true">{open ? "\u25B4" : "\u25BE"}</span>
      </button>
      <nav aria-label="Docs">
        <a href="/docs/" className={"docs-home-link" + (path === "/docs/" ? " on" : "")}>
          Docs home
        </a>
        {DOCS_SECTIONS.map((s) => (
          <div key={s.title} className="docs-nav-group">
            <h3>{s.title}</h3>
            {s.entries.map((e) => (
              <a key={e.href} href={e.href} className={path === e.href ? "on" : ""}>
                {e.title}
              </a>
            ))}
          </div>
        ))}
      </nav>
    </aside>
  );
}
