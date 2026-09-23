"use client";

import { useMemo, useState } from "react";
import { SiteHeader, SiteFooter } from "../components/chrome";
import { useRequestTool } from "../components/request-tool";
import { tools, type Tool } from "../lib/tools";

function Arrow() {
  return <span aria-hidden="true">↗</span>;
}

export default function ToolsView() {
  const [category, setCategory] = useState("All");
  const [query, setQuery] = useState("");
  const { openRequest, requestModal } = useRequestTool();

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
            <input value={query} onChange={(event) => setQuery(event.target.value)} placeholder="What are you trying to get done?" />
            <span>⌕</span>
          </label>
          <div className="filters" aria-label="Tool categories">
            {categories.map((item) => (
              <button key={item} className={category === item ? "active" : ""} onClick={() => setCategory(item)}>
                {item}
              </button>
            ))}
          </div>
        </div>

        <div className="tool-grid">
          {filtered.map((tool, index) => (
            <article className={`tool-card card-${(index % 8) + 1}`} key={tool.key}>
              <div className="tool-top">
                <span className="tool-icon">{tool.icon}</span>
                <span className="tool-tag">{tool.tag}</span>
                {tool.mascot && <img className="tool-mascot" src={tool.mascot} alt="" aria-hidden="true" />}
              </div>
              <h3>{tool.name}</h3>
              <p>{tool.description}</p>
              <button onClick={() => openTool(tool)}>{tool.cta} <Arrow /></button>
            </article>
          ))}
        </div>

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
