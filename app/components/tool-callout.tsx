type ToolCalloutProps = {
  variant?: "card" | "band";
  name: string;
  href: string;
  icon?: string;
  blurb: string;
  cta: string;
  heading?: string;
};

/**
 * High-on-the-page tool callout for how-to guides. Sits right under
 * the page head, before the article body, so the tool the guide
 * teaches is one click away without scrolling. Rollout: one per
 * guide, fed with that guide's tool from the registry/docs data.
 *
 * Variants:
 * - "card" (v1, default): mascot sticker, tool name, blurb, button.
 * - "band" (v2): no sticker. A lean band whose heading speaks to the
 *   guide's H1 task (pass it as `heading`), one short line (the
 *   `blurb`), and one simple arrow link (the `cta`).
 */
export default function ToolCallout({ variant = "card", name, href, icon, blurb, cta, heading }: ToolCalloutProps) {
  if (variant === "band") {
    return (
      <div className="guide-tool-callout guide-tool-callout-band">
        <p className="guide-tool-callout-heading">{heading ?? name}</p>
        <p className="guide-tool-callout-line">{blurb}</p>
        <a className="guide-tool-callout-link" href={href}>
          {cta}
        </a>
      </div>
    );
  }
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
