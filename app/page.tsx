"use client";

import { FormEvent, useMemo, useState } from "react";

const tools = [
  { icon: "✦", name: "Quick Quote", tag: "Sales", description: "Turn a few job details into a clean estimate your customer can understand.", action: "Build a quote" },
  { icon: "↗", name: "Friendly Follow-up", tag: "Customers", description: "Write the message you have been putting off, without sounding like a robot.", action: "Write a follow-up" },
  { icon: "✓", name: "Job Notes", tag: "Operations", description: "Turn messy field notes into a tidy summary and a clear next-step list.", action: "Clean up notes" },
  { icon: "$", name: "Profit Peek", tag: "Money", description: "Check the rough profit on a job before you send the quote.", action: "Check a job" },
];

const plans = [
  { name: "Poke around", price: "$0", note: "No card needed", credits: "75 actions each month", features: ["3 active tools", "1 workspace", "Community request queue"], cta: "Start free", plan: "free" },
  { name: "Helper", price: "$12", note: "per month", credits: "1,500 actions each month", features: ["All current tools", "3 team members", "Email support", "$6 per extra 1,000 actions"], cta: "Choose Helper", plan: "helper" },
  { name: "Operator", price: "$49", note: "per month", credits: "6,000 actions each month", features: ["Everything in Helper", "10 team members", "1 scoped tool request each month", "36-hour turnaround guarantee"], cta: "Choose Operator", plan: "operator", featured: true },
];

function Arrow() { return <span aria-hidden="true">↗</span>; }

export default function Home() {
  const [category, setCategory] = useState("All");
  const [query, setQuery] = useState("");
  const [requestOpen, setRequestOpen] = useState(false);
  const [requestState, setRequestState] = useState<"idle" | "sending" | "sent" | "error">("idle");
  const [billingBusy, setBillingBusy] = useState<string | null>(null);
  const filtered = useMemo(() => tools.filter((tool) => (category === "All" || tool.tag === category) && `${tool.name} ${tool.description}`.toLowerCase().includes(query.toLowerCase())), [category, query]);

  async function checkout(plan: string) {
    if (plan === "free") { document.querySelector("#toolbox")?.scrollIntoView({ behavior: "smooth" }); return; }
    setBillingBusy(plan);
    try {
      const response = await fetch("/api/stripe/checkout", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ plan }) });
      const data = await response.json();
      if (!response.ok || !data.url) throw new Error(data.error || "Checkout is unavailable.");
      window.location.href = data.url;
    } catch (error) {
      alert(error instanceof Error ? error.message : "Checkout is unavailable right now.");
      setBillingBusy(null);
    }
  }

  async function submitRequest(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setRequestState("sending");
    const form = new FormData(event.currentTarget);
    const response = await fetch("/api/tool-requests", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(Object.fromEntries(form)) });
    setRequestState(response.ok ? "sent" : "error");
  }

  return (
    <main>
      <header className="nav shell">
        <a className="brand" href="#top" aria-label="Leave It to Bum Bum home"><span className="brand-mark">BB</span><span>Leave It to<br /><b>Bum Bum</b></span></a>
        <nav aria-label="Main navigation"><a href="#toolbox">Tools</a><a href="#pricing">Pricing</a><a href="#guarantee">36 hours</a></nav>
        <button className="button button-small" onClick={() => setRequestOpen(true)}>Ask Bum Bum <Arrow /></button>
      </header>

      <section className="hero shell" id="top">
        <div className="hero-copy">
          <p className="eyebrow"><span>●</span> Small business busywork, handled</p>
          <h1>Don’t wanna do it?<br /><em>Leave it to Bum Bum.</em></h1>
          <p className="lede">Useful little tools for quotes, follow-ups, job notes, and all the fiddly stuff stealing your afternoon.</p>
          <div className="hero-actions"><a className="button" href="#toolbox">Open the toolbox <Arrow /></a><button className="text-button" onClick={() => setRequestOpen(true)}>Request a tool</button></div>
          <p className="fine">Start free. No card. No call with a guy named Chad.</p>
        </div>
        <div className="hero-portrait" aria-label="Bum Bum, chief tiny-tool operator">
          <div className="portrait-burst"></div>
          <div className="portrait-frame">
            <img src="/bum-bum.png" alt="Bum Bum the cat" onError={(event) => { event.currentTarget.style.display = "none"; }} />
            <div className="photo-fallback"><span>🐈</span><small>Bum Bum’s portrait<br />is clocking in</small></div>
          </div>
          <div className="scribble scribble-one">Chief operator</div>
          <div className="scribble scribble-two">curious<br />capable<br />cat</div>
          <div className="stamp">BUILT FOR<br /><b>REAL WORK</b></div>
        </div>
      </section>

      <section className="ticker" aria-label="Examples"><div>QUOTE IT <span>✦</span> CHASE IT <span>✦</span> SORT IT <span>✦</span> PRICE IT <span>✦</span> SEND IT <span>✦</span> LEAVE IT TO BUM BUM <span>✦</span></div></section>

      <section className="toolbox shell" id="toolbox">
        <div className="section-heading"><div><p className="kicker">Bum Bum’s toolbox</p><h2>Pick the thing you<br />don’t want to do.</h2></div><p>Each action is one useful result: one quote, one cleaned-up note, one follow-up. Simple.</p></div>
        <div className="finder">
          <label><span className="sr-only">Find a tool</span><input value={query} onChange={(event) => setQuery(event.target.value)} placeholder="What are you trying to get done?" /><span>⌕</span></label>
          <div className="filters" aria-label="Tool categories">{["All", "Sales", "Customers", "Operations", "Money"].map((item) => <button key={item} className={category === item ? "active" : ""} onClick={() => setCategory(item)}>{item}</button>)}</div>
        </div>
        <div className="tool-grid">{filtered.map((tool, index) => <article className={`tool-card card-${index + 1}`} key={tool.name}><div className="tool-top"><span className="tool-icon">{tool.icon}</span><span className="tool-tag">{tool.tag}</span></div><h3>{tool.name}</h3><p>{tool.description}</p><button onClick={() => alert(`${tool.name} is next in Bum Bum’s build queue. Request early access and we will let you know when it opens.`)}>{tool.action} <Arrow /></button></article>)}</div>
        {!filtered.length && <p className="empty">Bum Bum could not find that one. Sounds like a good tool request.</p>}
        <button className="request-strip" onClick={() => setRequestOpen(true)}><span><b>Can’t find your oddly specific problem?</b><small>Tell Bum Bum what keeps eating your time.</small></span><span>Request a tool <Arrow /></span></button>
      </section>

      <section className="how">
        <div className="shell"><div className="section-heading light"><div><p className="kicker">No software degree required</p><h2>Three steps.<br />Then, nap.</h2></div></div><div className="steps"><article><b>01</b><h3>Pick a tool</h3><p>Find the annoying task with your name on it.</p></article><article><b>02</b><h3>Give it the messy bits</h3><p>A few details, rough notes, or numbers will do.</p></article><article><b>03</b><h3>Get something useful</h3><p>Copy it, send it, save it, and move on.</p></article></div></div>
      </section>

      <section className="pricing shell" id="pricing">
        <div className="section-heading"><div><p className="kicker">One shared meter</p><h2>Pay for useful work,<br />not a maze of limits.</h2></div><p>Every completed result uses one action. Your whole team and every tool share the same monthly bucket.</p></div>
        <div className="plan-grid">{plans.map((plan) => <article className={`plan ${plan.featured ? "featured" : ""}`} key={plan.name}>{plan.featured && <span className="popular">BUM BUM’S PICK</span>}<h3>{plan.name}</h3><div className="price">{plan.price}<small>{plan.note}</small></div><p className="credits">{plan.credits}</p><ul>{plan.features.map((feature) => <li key={feature}>✓ {feature}</li>)}</ul><button className={plan.featured ? "button" : "button outline"} disabled={billingBusy === plan.plan} onClick={() => checkout(plan.plan)}>{billingBusy === plan.plan ? "Opening…" : plan.cta} <Arrow /></button></article>)}</div>
        <p className="pricing-note">Actions reset monthly and do not roll over. We warn you at 80% and 100%. Paid plans can keep going with simple action packs, or you can pause until the reset.</p>
      </section>

      <section className="guarantee" id="guarantee"><div className="shell guarantee-inner"><div className="guarantee-number">36<span>HRS</span></div><div><p className="kicker">The Operator promise</p><h2>A missing tool should not become a six-month project.</h2><p>Operator members get one scoped request each month. Once we agree on the tiny, useful version, Bum Bum ships it within 36 hours.</p><details><summary>What counts as a scoped request? <span>+</span></summary><p>One focused workflow that can be built in about four working hours. It can use approved existing services, but it cannot include regulated data, complex migrations, mobile app store review, or work waiting on a third party. The clock begins when scope and access are confirmed. Weekends and US federal holidays are excluded. If we miss the window, your next month is on us.</p></details></div></div></section>

      <section className="closing shell"><p className="kicker">Your to-don’t list starts here</p><h2>There has to be one thing<br />you would happily never do again.</h2><button className="button" onClick={() => setRequestOpen(true)}>Tell Bum Bum <Arrow /></button></section>

      <footer className="footer shell"><a className="brand" href="#top"><span className="brand-mark">BB</span><span>Leave It to<br /><b>Bum Bum</b></span></a><p>Useful little tools for busy little businesses.<br />© {new Date().getFullYear()} Leave It to Bum Bum</p><div><a href="mailto:hello@leaveittobumbum.com">hello@leaveittobumbum.com</a><a href="#pricing">Pricing</a></div></footer>

      {requestOpen && <div className="modal-backdrop" onMouseDown={(event) => event.target === event.currentTarget && setRequestOpen(false)}><section className="modal" role="dialog" aria-modal="true" aria-labelledby="request-title"><button className="modal-close" aria-label="Close" onClick={() => setRequestOpen(false)}>×</button>{requestState === "sent" ? <div className="success"><span>✓</span><h2>Bum Bum is on it.</h2><p>We have your request and will follow up with a sensible tiny version.</p><button className="button" onClick={() => { setRequestOpen(false); setRequestState("idle"); }}>Done</button></div> : <><p className="kicker">Request a tool</p><h2 id="request-title">What do you wish would just do itself?</h2><form onSubmit={submitRequest}><label>Your name<input name="name" required autoFocus /></label><label>Work email<input name="email" type="email" required /></label><label>The annoying task<textarea name="problem" required placeholder="Every Friday I copy..." rows={4}></textarea></label><label>What would “done” look like?<textarea name="outcome" required placeholder="I want to click once and get..." rows={3}></textarea></label><button className="button" disabled={requestState === "sending"}>{requestState === "sending" ? "Sending…" : "Send to Bum Bum"} <Arrow /></button>{requestState === "error" && <p className="form-error">That did not go through. Email hello@leaveittobumbum.com and we will pick it up.</p>}</form></>}</section></div>}
    </main>
  );
}

