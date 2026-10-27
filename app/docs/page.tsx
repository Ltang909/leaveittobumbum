import type { Metadata } from "next";
import { DOCS_SECTIONS, DOCS_FLAT } from "./manifest";
import { TOOL_DOCS } from "./tool-docs";
import DocsSearch from "./docs-search";

export const metadata: Metadata = {
  title: "Docs | Leave It to Bum Bum",
  description:
    "Everything about Bum Bum in plain English: how the tools work, what an action is, plans, the trackers, the Monday note, and the 36-hour promise.",
};

export default function DocsHome() {
  return (
    <>
      <section className="docs-head">
        <p className="kicker">Docs</p>
        <h1>Everything, in plain English.</h1>
        <p className="lede">
          How Bum Bum works, what things cost, and how to get a tool built. One page per
          tool and per idea, kept current as the toolbox grows.
        </p>
        <DocsSearch entries={DOCS_FLAT} />
      </section>

      {DOCS_SECTIONS.map((s) => (
        <section key={s.title} className="docs-section">
          <h2>{s.title}</h2>
          {s.title === "The toolbox" ? (
            <div className="docs-tool-grid">
              {TOOL_DOCS.map((t) => (
                <a key={t.key} href={`/docs/tools/${t.key}/`} className="docs-tool-card">
                  {/* eslint-disable-next-line @next/next/no-img-element */}
                  <img src={t.icon} alt="" width={44} height={44} />
                  <span>
                    <strong>{t.name}</strong>
                    <span className="docs-tool-card-blurb">{t.summary}</span>
                  </span>
                </a>
              ))}
            </div>
          ) : (
            <div className="docs-cards">
              {s.entries.map((e) => (
                <a key={e.href} href={e.href} className="docs-card">
                  <strong>{e.title}</strong>
                  <span>{e.summary}</span>
                </a>
              ))}
            </div>
          )}
        </section>
      ))}

      <section className="docs-section">
        <p className="docs-stuck">
          Still stuck?{" "}
          <a href="mailto:hello@leaveittobumbum.com">
            <strong>hello@leaveittobumbum.com</strong>
          </a>{" "}
          reaches a human (and a cat).
        </p>
      </section>
    </>
  );
}
