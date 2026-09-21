"use client";

import { useEffect, useState } from "react";
import { tools } from "./lib/tools";
import { useRequestTool } from "./components/request-tool";

const plans = [
  { name: "Poke around", price: "$0", note: "No card needed", credits: "75 actions each month", features: ["4 active tools", "1 workspace", "Community request queue"], cta: "Start free", plan: "free" },
  { name: "Helper", price: "$12", note: "per month", credits: "1,500 actions each month", features: ["All current tools", "3 team members", "Email support", "$6 per extra 1,000 actions"], cta: "Choose Helper", plan: "helper" },
  { name: "Operator", price: "$49", note: "per month", credits: "6,000 actions each month", features: ["Everything in Helper", "10 team members", "1 scoped tool request each month", "36-hour turnaround guarantee"], cta: "Choose Operator", plan: "operator", featured: true },
];

const QUIPS = [
  "mrrp.",
  "that click cost 0 actions. you're welcome.",
  "did you try napping on it?",
  "rude. (affectionate)",
  "one (1) boop received.",
  "Bum Bum has logged your curiosity.",
  "my face? it's called range.",
];

const CONFETTI_COLORS = ["#ffd84d", "#ff6b35", "#2864dc", "#ef8ab8", "#83d6b2", "#ffffff"];
const CONFETTI = Array.from({ length: 90 }, (_, i) => ({
  left: Math.random() * 100,
  delay: Math.random() * 1.2,
  dur: 2.4 + Math.random() * 2.2,
  size: 6 + Math.random() * 8,
  color: CONFETTI_COLORS[i % CONFETTI_COLORS.length],
  round: Math.random() > 0.6,
  sparkle: Math.random() > 0.9,
}));

const KONAMI = ["ArrowUp", "ArrowUp", "ArrowDown", "ArrowDown", "ArrowLeft", "ArrowRight", "ArrowLeft", "ArrowRight", "b", "a"];

function Arrow() { return <span aria-hidden="true">↗</span>; }

export default function Home() {
  const { openRequest, requestModal } = useRequestTool();
  const [booped, setBooped] = useState(false);
  const [quip, setQuip] = useState<string | null>(null);
  const [boingKey, setBoingKey] = useState(0);
  const [party, setParty] = useState(false);
  const [napZoom, setNapZoom] = useState(false);
  const [napping, setNapping] = useState(false);

  useEffect(() => {
    console.log("%c🐈 psst — Bum Bum sees you.\n%cOpened devtools, huh? Respect. If you're snooping for fun: hello@leaveittobumbum.com", "font-weight:bold;font-size:14px", "font-size:12px");
    new Image().src = "/bum-bum-funny.png";
    let pos = 0;
    let napBuf = "";
    let napTimer: number | undefined;
    let partyTimer: number | undefined;
    function onKey(event: KeyboardEvent) {
      const key = event.key.length === 1 ? event.key.toLowerCase() : event.key;
      pos = key === KONAMI[pos] ? pos + 1 : (key === KONAMI[0] ? 1 : 0);
      if (pos === KONAMI.length) {
        pos = 0;
        setParty(true);
        window.clearTimeout(partyTimer);
        partyTimer = window.setTimeout(() => setParty(false), 9000);
      }
      if (/^[a-z]$/.test(key)) {
        napBuf = (napBuf + key).slice(-3);
        if (napBuf === "nap") {
          napBuf = "";
          setNapZoom(true);
          window.clearTimeout(napTimer);
          napTimer = window.setTimeout(() => setNapZoom(false), 5000);
        }
      }
    }
    window.addEventListener("keydown", onKey);
    return () => {
      window.removeEventListener("keydown", onKey);
      window.clearTimeout(napTimer);
      window.clearTimeout(partyTimer);
    };
  }, []);

  function checkout(plan: string) {
    if (plan === "free") { window.location.href = "/tools/"; return; }
    window.location.href = `/checkout/?plan=${encodeURIComponent(plan)}`;
  }

  function openTool(tool: { url: string }) {
    window.location.href = tool.url;
  }

  function boopBumBum() {
    const next = !booped;
    setBooped(next);
    setBoingKey((k) => k + 1);
    if (next) {
      setQuip(QUIPS[Math.floor(Math.random() * QUIPS.length)]);
      window.setTimeout(() => setQuip(null), 2600);
    } else {
      setQuip(null);
    }
  }

  return (
    <main>
      <header className="nav shell">
        <a className="brand" href="#top" aria-label="Leave It to Bum Bum home"><span className="brand-mark">BB</span><span>Leave It to<br /><b>Bum Bum</b></span></a>
        <nav aria-label="Main navigation"><a href="/tools/">Tools</a><a href="#pricing">Pricing</a><a href="#guarantee">36 hours</a><a href="/account/">Account</a></nav>
        <button className="button button-small" onClick={() => openRequest()}>Ask Bum Bum <Arrow /></button>
      </header>

      <section className="hero shell" id="top">
        <div className="hero-copy">
          <p className="eyebrow"><span>●</span> Small business busywork, handled</p>
          <h1><span className="h1-line">Don’t wanna do it?</span><br /><em>Leave it to Bum Bum.</em></h1>
          <p className="lede">Useful little tools for quotes, follow-ups, job notes, and all the fiddly stuff stealing your afternoon.</p>
          <div className="hero-actions"><a className="button" href="/tools/">Open the toolbox <Arrow /></a><button className="text-button" onClick={() => openRequest()}>Request a tool</button></div>
          <p className="fine">Start free. No card. No call with a guy named Chad.</p>
        </div>
        <div className="hero-portrait" aria-label="Bum Bum, chief tiny-tool operator">
          <div className="portrait-burst"></div>
          <div key={boingKey} className={`portrait-frame boopable${boingKey && !party ? " boing" : ""}${party ? " dance" : ""}`} onClick={boopBumBum} onKeyDown={(event) => { if (event.key === "Enter" || event.key === " ") { event.preventDefault(); boopBumBum(); } }} role="button" tabIndex={0} aria-label="Boop Bum Bum">
            <img src={booped ? "/bum-bum-funny.png" : "/bum-bum.png"} alt={booped ? "Bum Bum making a funny face" : "Bum Bum the cat"} onError={(event) => { event.currentTarget.style.display = "none"; }} />
            <div className="photo-fallback"><span>🐈</span><small>Bum Bum’s portrait<br />is clocking in</small></div>
          </div>
          {quip && <div className="quip-bubble" role="status">{quip}</div>}
          <div className="scribble scribble-one">Chief operator</div>
          <div className="scribble scribble-two">curious<br />capable<br />cat</div>
          <div className="stamp">BUILT FOR<br /><b>REAL WORK</b></div>
        </div>
      </section>

      <section className={`ticker${napZoom ? " zoomies" : ""}`} aria-label="Examples"><div>QUOTE IT <span>✦</span> CHASE IT <span>✦</span> SORT IT <span>✦</span> PRICE IT <span>✦</span> SEND IT <span>✦</span> LEAVE IT TO BUM BUM <span>✦</span></div></section>

      <section className="toolbox shell" id="toolbox">
        <div className="section-heading"><div><p className="kicker">Bum Bum’s toolbox</p><h2>Pick the thing you<br />don’t want to do.</h2></div><p>Every finished result uses one action. The shelf keeps growing, so poke around.</p></div>
        <div className="tool-grid">{tools.slice(0, 3).map((tool, index) => <article className={`tool-card card-${index + 1}`} key={tool.key}><div className="tool-top"><span className="tool-icon">{tool.icon}</span><span className="tool-tag">{tool.tag}</span></div><h3>{tool.name}</h3><p>{tool.description}</p><button onClick={() => openTool(tool)}>{tool.cta} <Arrow /></button></article>)}</div>
        <div className="toolbox-more"><a className="button" href="/tools/">Browse the full toolbox <Arrow /></a></div>
      </section>

      <section className="how">
        <div className="shell"><div className="section-heading light"><div><p className="kicker">No software degree required</p><h2>Three steps.<br />Then, nap.</h2></div></div><div className="steps"><article><b>01</b><h3>Pick a tool</h3><p>Find the annoying task with your name on it.</p></article><article><b>02</b><h3>Give it the messy bits</h3><p>A few details, rough notes, or numbers will do.</p></article><article><b>03</b><h3>Get something useful</h3><p>Copy it, send it, save it, and move on.</p></article></div></div>
      </section>

      <section className="pricing shell" id="pricing">
        <div className="section-heading"><div><p className="kicker">One shared meter</p><h2>Pay for useful work,<br />not a maze of limits.</h2></div><p>Every completed result uses one action. Your whole team and every tool share the same monthly bucket.</p></div>
        <div className="plan-grid">{plans.map((plan) => <article className={`plan ${plan.featured ? "featured" : ""}`} key={plan.name}>{plan.featured && <span className="popular">BUM BUM’S PICK</span>}<h3>{plan.name}</h3><div className="price">{plan.price}<small>{plan.note}</small></div><p className="credits">{plan.credits}</p><ul>{plan.features.map((feature) => <li key={feature}>✓ {feature}</li>)}</ul><button className={plan.featured ? "button" : "button outline"} onClick={() => checkout(plan.plan)}>{plan.cta} <Arrow /></button></article>)}</div>
        <p className="pricing-note">Actions reset monthly and do not roll over. We warn you at 80% and 100%. Paid plans can keep going with simple action packs, or you can pause until the reset.</p>
      </section>

      <section className="guarantee" id="guarantee"><div className="shell guarantee-inner"><div className="guarantee-number" title="psst — triple-click me" onClick={(event) => { if (event.detail === 3) { setNapping(true); window.setTimeout(() => setNapping(false), 3200); } }}>36<span>HRS</span>{napping && <div className="nap-bubble">😴 Bum Bum is napping. The 36-hour clock respects nap time.</div>}</div><div><p className="kicker">The Operator promise</p><h2>A missing tool should not become a six-month project.</h2><p>Operator members get one scoped request each month. Once we agree on the tiny, useful version, Bum Bum ships it within 36 hours.</p><details><summary>What counts as a scoped request? <span>+</span></summary><p>One focused workflow that can be built in about four working hours. It can use approved existing services, but it cannot include regulated data, complex migrations, mobile app store review, or work waiting on a third party. The clock begins when scope and access are confirmed. Weekends and US federal holidays are excluded. If we miss the window, your next month is on us.</p></details></div></div></section>

      <section className="closing shell"><p className="kicker">Your to-don’t list starts here</p><h2>There has to be one thing<br />you would happily never do again.</h2><button className="button" onClick={() => openRequest()}>Tell Bum Bum <Arrow /></button></section>

      <footer className="footer shell"><a className="brand" href="#top"><span className="brand-mark">BB</span><span>Leave It to<br /><b>Bum Bum</b></span></a><p>Useful little tools for busy little businesses.<br />© {new Date().getFullYear()} Leave It to Bum Bum</p><div><a href="mailto:hello@leaveittobumbum.com">hello@leaveittobumbum.com</a><a href="/about/">About</a><a href="/privacy/">Privacy</a><a href="/terms/">Terms</a></div></footer>

      {requestModal}

      {party && <div className="confetti" aria-hidden="true">{CONFETTI.map((piece, i) => <span key={i} style={{ left: `${piece.left}%`, background: piece.color, width: piece.size, height: piece.size, borderRadius: piece.round ? "50%" : "2px", animationDelay: `${piece.delay}s`, animationDuration: `${piece.dur}s` }}>{piece.sparkle ? "✦" : ""}</span>)}</div>}
    </main>
  );
}
