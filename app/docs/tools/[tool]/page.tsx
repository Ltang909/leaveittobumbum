import { notFound } from "next/navigation";
import type { Metadata } from "next";
import { TOOL_DOCS } from "../../tool-docs";
import PrevNext from "../../prev-next";

export function generateStaticParams() {
  return TOOL_DOCS.map((t) => ({ tool: t.key }));
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ tool: string }>;
}): Promise<Metadata> {
  const { tool } = await params;
  const t = TOOL_DOCS.find((x) => x.key === tool);
  if (!t) return {};
  return {
    title: `${t.name} | Docs | Leave It to Bum Bum`,
    description: t.summary,
  };
}

export default async function ToolDocPage({
  params,
}: {
  params: Promise<{ tool: string }>;
}) {
  const { tool } = await params;
  const t = TOOL_DOCS.find((x) => x.key === tool);
  if (!t) notFound();
  const howToLd = {
    "@context": "https://schema.org",
    "@type": "HowTo",
    name: `How to use ${t.name}`,
    description: t.summary,
    step: t.steps.map((s, i) => ({
      "@type": "HowToStep",
      position: i + 1,
      text: s,
    })),
  };
  const crumbLd = {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    itemListElement: [
      { "@type": "ListItem", position: 1, name: "Docs", item: "https://leaveittobumbum.com/docs/" },
      { "@type": "ListItem", position: 2, name: "The toolbox" },
      {
        "@type": "ListItem",
        position: 3,
        name: t.name,
        item: `https://leaveittobumbum.com/docs/tools/${t.key}/`,
      },
    ],
  };
  return (
    <article>
      <p className="kicker">The toolbox</p>
      <div className="docs-tool-head">
        {/* eslint-disable-next-line @next/next/no-img-element */}
        <img src={t.icon} alt="" width={76} height={76} />
        <h1 className="docs-title">{t.name}</h1>
      </div>
      <p className="lede">{t.summary}</p>
      <p className="docs-tool-cta">
        <a className="button" href={t.href}>
          Open {t.name}
        </a>
      </p>
      <p className="docs-updated">Last updated {t.updated}</p>
      <div className="prose docs-body">
        <h2>How to use it</h2>
        <ol>
          {t.steps.map((s, i) => (
            <li key={i}>{s}</li>
          ))}
        </ol>
        <h2>Good to know</h2>
        <ul>
          {t.goodToKnow.map((s, i) => (
            <li key={i}>{s}</li>
          ))}
        </ul>
        <h2>Your data</h2>
        <p>{t.data}</p>
        <h2>Tips</h2>
        <ul>
          {t.tips.map((s, i) => (
            <li key={i}>{s}</li>
          ))}
        </ul>
        {t.faq && t.faq.length > 0 && (
          <>
            <h2>Questions</h2>
            {t.faq.map(([q, a], i) => (
              <details key={i}>
                <summary>
                  {q} <span>+</span>
                </summary>
                <p>{a}</p>
              </details>
            ))}
          </>
        )}
      </div>
      <PrevNext href={`/docs/tools/${t.key}/`} />
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(howToLd) }}
      />
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(crumbLd) }}
      />
    </article>
  );
}
