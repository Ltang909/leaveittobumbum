type ToolCalloutProps = {
  name: string;
  href: string;
  icon: string;
  blurb: string;
  cta: string;
};

/**
 * High-on-the-page tool card for how-to guides. Sits right under the
 * page head, before the article body, so the tool the guide teaches
 * is one click away without scrolling. Rollout: one per guide, fed
 * with that guide's tool from the registry/docs data.
 */
export default function ToolCallout({ name, href, icon, blurb, cta }: ToolCalloutProps) {
  return (
    <div className="guide-tool-callout">
      {/* eslint-disable-next-line @next/next/no-img-element */}
      <img className="sticker" src={icon} alt="" width={104} height={104} />
      <div>
        <p className="guide-tool-callout-name">{name}</p>
        <p className="guide-tool-callout-blurb">{blurb}</p>
        <a className="button button-small" href={href}>
          {cta}
        </a>
      </div>
    </div>
  );
}
