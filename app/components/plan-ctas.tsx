"use client";

import { useEffect, useState } from "react";

const ORDER = ["free", "helper", "operator"];
const NAMES: Record<string, string> = { free: "Free", helper: "Helper", operator: "Operator" };

type PlanCtaProps = {
  plan: string;
  label: string;
  href: string;
  featured?: boolean;
};

/**
 * Pricing CTA that adapts to the visitor's current plan.
 * Guests see the default label/href. Signed-in users see
 * "You're on this plan", "Upgrade to …", or "Downgrade to …"
 * (downgrades route through the Stripe billing portal).
 */
export function PlanCta({ plan, label, href, featured }: PlanCtaProps) {
  const [current, setCurrent] = useState<string | null>(null);
  const [renewal, setRenewal] = useState<string | null>(null);

  useEffect(() => {
    fetch("/api/session.php", { credentials: "same-origin" })
      .then((r) => r.json())
      .then((s) => {
        const p = s && s.authenticated ? String(s.user?.plan || "free") : "guest";
        setCurrent(p);
        const end = s && s.authenticated ? s.user?.period_end : null;
        if (p !== "free" && p !== "guest" && end) {
          const d = new Date(String(end).replace(" ", "T") + "Z");
          if (!isNaN(d.getTime())) {
            setRenewal(d.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric", timeZone: "America/Toronto" }));
          }
        }
      })
      .catch(() => setCurrent("guest"));
  }, []);

  const className = featured ? "button" : "button outline";

  async function downgrade() {
    try {
      const s = await fetch("/api/session.php", { credentials: "same-origin" }).then((r) => r.json());
      const res = await fetch("/api/portal.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ csrf: s.csrf }),
      });
      const data = await res.json();
      if (res.ok && data.url) window.location.href = data.url;
    } catch {
      window.location.href = "/account/";
    }
  }

  if (current === null || current === "guest" || !ORDER.includes(current)) {
    return (
      <a className={className} href={href}>
        {label} <span aria-hidden="true">↗</span>
      </a>
    );
  }

  if (current === plan) {
    return (
      <span style={{ display: "block" }}>
        <span className={className} aria-disabled="true" style={{ opacity: 0.55, cursor: "default" }}>
          You&rsquo;re on this plan
        </span>
        {renewal && plan !== "free" && (
          <span style={{ display: "block", marginTop: 8, fontSize: ".85rem", opacity: 0.75 }}>
            Renews {renewal}
          </span>
        )}
      </span>
    );
  }

  const name = NAMES[plan] ?? plan;
  if (ORDER.indexOf(plan) > ORDER.indexOf(current)) {
    return (
      <a className={className} href={href}>
        Upgrade to {name} <span aria-hidden="true">↗</span>
      </a>
    );
  }
  return (
    <button type="button" className={className} onClick={downgrade}>
      Downgrade to {name}
    </button>
  );
}
