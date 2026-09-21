import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "Pricing | Leave It to Bum Bum",
  description: "One shared meter. Every completed result uses one action — pay for useful work, not a maze of limits.",
};

const plans = [
  { name: "Poke around", price: "$0", note: "No card needed", credits: "75 actions each month", features: ["4 active tools", "1 workspace", "Community request queue"], cta: "Start free", href: "/tools/" },
  { name: "Helper", price: "$12", note: "per month", credits: "1,500 actions each month", features: ["All current tools", "3 team members", "Email support", "$6 per extra 1,000 actions"], cta: "Choose Helper", href: "/checkout/?plan=helper" },
  { name: "Operator", price: "$49", note: "per month", credits: "6,000 actions each month", features: ["Everything in Helper", "10 team members", "1 scoped tool request each month", "36-hour turnaround guarantee"], cta: "Choose Operator", href: "/checkout/?plan=operator", featured: true },
];

export default function Pricing() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">Pricing</p>
        <h1>Pay for useful work,<br />not a maze of limits.</h1>
        <p className="lede">One shared meter. Every completed result uses one action, and your whole team and every tool share the same monthly bucket.</p>
      </section>
      <section className="shell" style={{ paddingBottom: 90 }}>
        <div className="plan-grid">
          {plans.map((plan) => (
            <article className={`plan ${plan.featured ? "featured" : ""}`} key={plan.name}>
              {plan.featured && <span className="popular">BUM BUM&rsquo;S PICK</span>}
              <h3>{plan.name}</h3>
              <div className="price">{plan.price}<small>{plan.note}</small></div>
              <p className="credits">{plan.credits}</p>
              <ul>{plan.features.map((feature) => <li key={feature}>✓ {feature}</li>)}</ul>
              <a className={plan.featured ? "button" : "button outline"} href={plan.href}>{plan.cta} <span aria-hidden="true">↗</span></a>
            </article>
          ))}
        </div>
        <div className="prose" style={{ marginTop: 70 }}>
          <h2>What is an action?</h2>
          <p>An action is one completed result: a trimmed video, an audit report, a cleaned-up subscription list. Clicking around and previewing is free. You only spend when Bum Bum finishes something useful for you.</p>
          <h2>How the meter works</h2>
          <ul>
            <li><strong>Shared bucket:</strong> your whole team and every tool draw from the same monthly actions. No per-tool limits, no per-seat math.</li>
            <li><strong>Monthly reset:</strong> actions reset on your billing date and don&rsquo;t roll over.</li>
            <li><strong>Fair warning:</strong> we nudge you at 80% and again at 100%, so the meter never surprises you.</li>
            <li><strong>Run out early?</strong> Paid plans can top up with simple action packs ($6 per 1,000) or just pause until the reset.</li>
          </ul>
          <h2>Pause or cancel anytime</h2>
          <p>No contracts, no cancellation maze. Downgrade to free or cancel in two clicks from your account page, and you keep what you paid for until the end of the billing period.</p>
          <h2>Where the 36-hour promise fits</h2>
          <p>Operator members get one scoped tool request each month, and Bum Bum ships it within 36 hours or your next month is on us. <a href="/36-hours/"><strong>Read the full promise</strong></a>.</p>
          <h2>Questions, probably answered</h2>
          <details><summary>Do unused actions roll over? <span>+</span></summary><p>No. The bucket refills fresh each month, which is how we keep the prices this low.</p></details>
          <details><summary>What happens if I hit 100% mid-month? <span>+</span></summary><p>Tools pause politely and tell you. Top up with an action pack or wait for the reset &mdash; your data and settings stay put either way.</p></details>
          <details><summary>Can I switch plans later? <span>+</span></summary><p>Yes. Upgrades apply immediately, downgrades take effect at the next billing date. Either way takes about ten seconds.</p></details>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
