"use client";

import { useEffect, useMemo, useRef, useState } from "react";
import { SiteHeader, SiteFooter } from "../components/chrome";
import { useRequestTool } from "../components/request-tool";
import { tools, type Tool } from "../lib/tools";

function Arrow() {
  return <span aria-hidden="true">↗</span>;
}

const VIEW_KEY = "bum-tools-view";
const PAGE_SIZE = 16;
type View = "cards" | "list";

export default function ToolsView() {
  const [category, setCategory] = useState("All");
  const [query, setQuery] = useState("");
  const [view, setView] = useState<View>("cards");
  const [page, setPage] = useState(1);
  const resultsTopRef = useRef<HTMLParagraphElement>(null);
  const { openRequest, requestModal } = useRequestTool();

  // Read the saved view on mount (not during render) to avoid a hydration mismatch.
  useEffect(() => {
    try {
      const saved = localStorage.getItem(VIEW_KEY);
      if (saved === "cards" || saved === "list") setView(saved);
    } catch {
      /* private mode: cards it is */
    }
  }, []);

  function pickView(next: View) {
    setView(next);
    try {
      localStorage.setItem(VIEW_KEY, next);
    } catch {
      /* private mode: choice just won't stick */
    }
  }

  const categories = useMemo(
    () => ["All", ...Array.from(new Set(tools.map((tool) => tool.tag)))],
    []
  );

  const filtered = useMemo(
    () =>
      tools.filter(
        (tool) =>
          (category === "All" || tool.tag === category) &&
          `${tool.name} ${tool.description}`.toLowerCase().includes(query.toLowerCase())
      ),
    [category, query]
  );

  const pageCount = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));

  // Keep the page in range when a filter shrinks the set underneath it.
  useEffect(() => {
    if (page > pageCount) setPage(pageCount);
  }, [page, pageCount]);

  const paged = filtered.slice((page - 1) * PAGE_SIZE, page * PAGE_SIZE);
  const rangeStart = filtered.length === 0 ? 0 : (page - 1) * PAGE_SIZE + 1;
  const rangeEnd = (page - 1) * PAGE_SIZE + paged.length;

  function goToPage(next: number) {
    setPage(Math.min(Math.max(1, next), pageCount));
    resultsTopRef.current?.scrollIntoView({ behavior: "smooth", block: "start" });
  }

  function openTool(tool: Tool) {
    window.location.href = tool.url;
  }

  return (
    <main>
      <SiteHeader />

      <section className="page-head shell">
        <p className="kicker">The toolbox</p>
        <h1>Every tiny tool,<br />one happy shelf.</h1>
        <p className="lede">This is the whole collection so far, and it grows nearly every day. Find your annoying task, click, done.</p>
      </section>

      <div className="page-body shell">
        <div className="finder">
          <label>
            <span className="sr-only">Find a tool</span>
            <input value={query} onChange={(event) => { setQuery(event.target.value); setPage(1); }} placeholder="What are you trying to get done?" />
            <span>⌕</span>
          </label>
          <label className="cat-filter">
            <span className="sr-only">Filter tools by category</span>
            <select value={category} onChange={(event) => { setCategory(event.target.value); setPage(1); }} aria-label="Filter tools by category">
              {categories.map((item) => (
                <option key={item} value={item}>
                  {item === "All" ? "All types" : item}
                </option>
              ))}
            </select>
          </label>
          <div className="view-toggle" role="group" aria-label="Toolbox view">
            <button
              type="button"
              aria-pressed={view === "cards"}
              onClick={() => pickView("cards")}
            >
              Cards
            </button>
            <button
              type="button"
              aria-pressed={view === "list"}
              onClick={() => pickView("list")}
            >
              List
            </button>
          </div>
        </div>

        {filtered.length > 0 && (
          <p className="tools-count" ref={resultsTopRef}>Showing {rangeStart}–{rangeEnd} of {filtered.length} tools</p>
        )}

        {view === "list" ? (
          <ul className="tool-list">
            {paged.map((tool, index) => (
              <li key={tool.key}>
                <a className="tool-row" href={tool.url}>
                  <span className={`tool-row-icon card-${(((page - 1) * PAGE_SIZE + index) % 8) + 1}`} aria-hidden="true">
                    {tool.icon}
                  </span>
                  <span className="tool-row-main">
                    <span className="tool-row-name">{tool.name}</span>
                    <span className="tool-row-desc">{tool.description}</span>
                  </span>
                  <span className="tool-row-tag">{tool.tag}</span>
                  <Arrow />
                </a>
              </li>
            ))}
          </ul>
        ) : (
        <div className="tool-grid">
          {paged.map((tool, index) => (
            <article className={`tool-card card-${(((page - 1) * PAGE_SIZE + index) % 8) + 1}`} key={tool.key}>
              <div className="tool-top">
                <span className="tool-icon">{tool.icon}</span>
                <span className="tool-tag">{tool.tag}</span>
                {(tool.iconImg || tool.mascot) && <img className="tool-mascot" src={tool.iconImg || tool.mascot} alt="" aria-hidden="true" />}
              </div>
              <h3>{tool.cardTitle ? <>{tool.cardTitle[0]}<br />{tool.cardTitle[1]}</> : tool.name}</h3>
              <p>{tool.description}</p>
              <button onClick={() => openTool(tool)}>{tool.cta} <Arrow /></button>
            </article>
          ))}
        </div>
        )}

        {pageCount > 1 && (
          <nav className="pager" aria-label="Toolbox pages">
            <button type="button" onClick={() => goToPage(page - 1)} disabled={page === 1}>← Previous</button>
            {Array.from({ length: pageCount }, (_, i) => i + 1).map((n) => (
              <button key={n} type="button" aria-current={n === page ? "page" : undefined} onClick={() => goToPage(n)}>{n}</button>
            ))}
            <button type="button" onClick={() => goToPage(page + 1)} disabled={page === pageCount}>Next →</button>
          </nav>
        )}

        {!filtered.length && <p className="empty">Bum Bum could not find that one. Sounds like a good tool request.</p>}

        <button className="request-strip" onClick={() => openRequest()}>
          <span>
            <b>Can’t find your oddly specific problem?</b>
            <small>Tell Bum Bum what keeps eating your time.</small>
          </span>
          <span>Request a tool <Arrow /></span>
        </button>
      </div>

      <SiteFooter />
      {requestModal}
    </main>
  );
}
