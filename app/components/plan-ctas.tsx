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

  useEffect(() => {
    fetch("/api/session.php", { credentials: "same-origin" })
      .then((r) => r.json())
      .then((s) => setCurrent(s && s.authenticated ? String(s.user?.plan || "free") : "guest"))
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
      <span className={className} aria-disabled="true" style={{ opacity: 0.55, cursor: "default" }}>
        You&rsquo;re on this plan
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
