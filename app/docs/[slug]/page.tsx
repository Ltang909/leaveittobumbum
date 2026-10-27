import { notFound } from "next/navigation";
import type { Metadata } from "next";
import { GUIDES, type Block } from "../guides";
import { Inline } from "../inline";
import PrevNext from "../prev-next";

export function generateStaticParams() {
  return GUIDES.map((g) => ({ slug: g.slug }));
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>;
}): Promise<Metadata> {
  const { slug } = await params;
  const g = GUIDES.find((x) => x.slug === slug);
  if (!g) return {};
  return {
    title: `${g.title} | Docs | Leave It to Bum Bum`,
    description: g.summary,
  };
}

function Blocks({ blocks }: { blocks: Block[] }) {
  return (
    <>
      {blocks.map((b, i) => {
        if ("h" in b) return <h2 key={i}>{b.h}</h2>;
        if ("p" in b)
          return (
            <p key={i}>
              <Inline text={b.p} />
            </p>
          );
        if ("ul" in b)
          return (
            <ul key={i}>
              {b.ul.map((item, j) => (
                <li key={j}>
                  <Inline text={item} />
                </li>
              ))}
            </ul>
          );
        if ("note" in b)
          return (
            <p key={i} className="docs-note">
              <Inline text={b.note} />
            </p>
          );
        if ("faq" in b)
          return (
            <div key={i}>
              {b.faq.map(([q, a], j) => (
                <details key={j}>
                  <summary>
                    {q} <span>+</span>
                  </summary>
                  <p>
                    <Inline text={a} />
                  </p>
                </details>
              ))}
            </div>
          );
        return null;
      })}
    </>
  );
}

export default async function GuidePage({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;
  const g = GUIDES.find((x) => x.slug === slug);
  if (!g) notFound();
  const faqItems = g.blocks.flatMap((b) => ("faq" in b ? b.faq : []));
  const faqLd =
    faqItems.length > 0
      ? {
          "@context": "https://schema.org",
          "@type": "FAQPage",
          mainEntity: faqItems.map(([q, a]) => ({
            "@type": "Question",
            name: q,
            acceptedAnswer: { "@type": "Answer", text: a },
          })),
        }
      : null;
  const crumbLd = {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    itemListElement: [
      { "@type": "ListItem", position: 1, name: "Docs", item: "https://leaveittobumbum.com/docs/" },
      { "@type": "ListItem", position: 2, name: g.section },
      {
        "@type": "ListItem",
        position: 3,
        name: g.title,
        item: `https://leaveittobumbum.com/docs/${g.slug}/`,
      },
    ],
  };
  return (
    <article>
      <p className="kicker">{g.section}</p>
      <h1 className="docs-title">{g.title}</h1>
      <p className="lede">{g.summary}</p>
      <p className="docs-updated">Last updated {g.updated}</p>
      <div className="prose docs-body">
        <Blocks blocks={g.blocks} />
      </div>
      <PrevNext href={`/docs/${g.slug}/`} />
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(crumbLd) }}
      />
      {faqLd && (
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(faqLd) }}
        />
      )}
    </article>
  );
}
