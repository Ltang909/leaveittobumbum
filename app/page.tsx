"use client";

import { useEffect, useState } from "react";
import { tools, Tool } from "./lib/tools";
import { SiteHeader, SiteFooter } from "./components/chrome";
import { useRequestTool } from "./components/request-tool";
import { PlanCta } from "./components/plan-ctas";
import CaptureBar from "./components/capture-bar";

const plans = [
  { name: "Poke around", price: "$0", note: "No card needed", credits: "75 actions each month", features: ["All current tools", "1 workspace", "Community request queue"], cta: "Start free", plan: "free" },
  { name: "Helper", price: "$12", note: "per month", credits: "1,500 actions each month", features: ["Everything in Poke around", "3 team members", "Email support", "$6 per extra 1,000 actions"], cta: "Choose Helper", plan: "helper" },
  { name: "Operator", price: "$49", note: "per month", credits: "6,000 actions each month", features: ["Everything in Helper", "10 team members", "1 scoped tool request each month", "36-hour turnaround guarantee"], cta: "Choose Operator", plan: "operator", featured: true },
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

// Homepage order: money jobs first, then the magical ones.
const HOME_TOOL_ORDER = ["invoice-chaser", "receipt-reader", "purrsuit", "cutline", "clips", "notes", "sop-ify", "audiogram"];
const homeTools: Tool[] = [];
for (const key of HOME_TOOL_ORDER) {
  const tool = tools.find((t) => t.key === key);
  if (tool) homeTools.push(tool);
}

export default function Home() {
  const { openRequest, requestModal } = useRequestTool();
  const [party, setParty] = useState(false);
  const [napZoom, setNapZoom] = useState(false);
  const [napping, setNapping] = useState(false);

  useEffect(() => {
    console.log("%c🐈 psst, Bum Bum sees you.\n%cOpened devtools, huh? Respect. If you're snooping for fun: hello@leaveittobumbum.com", "font-weight:bold;font-size:14px", "font-size:12px");
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

  function openTool(tool: { url: string }) {
    window.location.href = tool.url;
  }

  return (
    <main>
      <SiteHeader />

      <section className="hero hero-desk shell" id="top">
        <div className="hero-copy hero-copy-desk">
          <p className="eyebrow"><span>●</span> Small business busywork, handled</p>
          <h1><span className="h1-line">Don’t wanna do it?</span><br /><em>Leave it to Bum Bum.</em></h1>
          <p className="lede">Little tools that chase unpaid invoices, sort your receipts, nudge your follow-ups, and handle the fiddly stuff stealing your afternoon. Start at the front desk: hand him the messy bit, he takes it from there.</p>
          <p className="fine">Start free. No card. No call with a guy named Chad.</p>
        </div>
        <CaptureBar />
      </section>

      <section className={`ticker${napZoom ? " zoomies" : ""}`} aria-label="Examples"><div className="ticker-track">{[0, 1].map((copy) => (<div className="ticker-chunk" key={copy} aria-hidden={copy === 1 || undefined}>{["READ MY RECEIPTS", "CHASE THAT INVOICE", "CLIP THE GOOD PART", "FILE THE VOICE NOTE", "POSTER IN, EVENTS OUT", "LEAVE IT TO BUM BUM"].map((t) => (<span key={t} className="ticker-item">{t}<i>✦</i></span>))}</div>))}</div></section>

      <section className="toolbox shell" id="toolbox">
        <div className="section-heading"><div><p className="kicker">Bum Bum’s toolbox</p><h2>One cat. Twenty jobs.<br />All of them learned properly.</h2></div><p>Every finished result uses one action. The shelf keeps growing, so poke around.</p></div>
        <div className="tool-grid">{homeTools.map((tool, index) => <article className={`tool-card card-${(index % 8) + 1}`} key={tool.key}><div className="tool-top"><span className="tool-icon">{tool.icon}</span><span className="tool-tag">{tool.tag}</span>{(tool.iconImg || tool.mascot) && <img className="tool-mascot" src={tool.iconImg || tool.mascot} alt="" aria-hidden="true" />}</div><h3>{tool.name}</h3><p>{tool.description}</p><button onClick={() => openTool(tool)}>{tool.cta} <Arrow /></button></article>)}</div>
        <div className="toolbox-more"><a className="button" href="/tools/">Browse the full toolbox <Arrow /></a></div>
        <p className="section-more section-more-left"><a href="#guarantee">Missing something? Bum Bum builds it in 36 hours <Arrow /></a></p>
      </section>

      <section className="how">
        <div className="shell"><div className="section-heading light"><div><p className="kicker">No software degree required</p><h2>Three steps.<br />Then, nap.</h2></div></div><div className="steps"><article><b>01</b><h3>Pick the annoying task</h3><p>Find the busywork with your name on it.</p></article><article><b>02</b><h3>Hand over the messy bits</h3><p>Paste in rough notes, numbers, or files. Bum Bum sorts it out.</p></article><article><b>03</b><h3>Get the finished thing</h3><p>Copy it, send it, save it. One action spent, afternoon reclaimed.</p></article></div></div>
      </section>

      <section className="pricing shell" id="pricing">
        <div className="section-heading"><div><p className="kicker">One shared meter</p><h2>Pay for useful work,<br />not a maze of limits.</h2></div><p>Every completed result uses one action: a finished clip, a batch of receipts, a chased invoice, a playbook. Your whole team and every tool share the same monthly bucket.</p></div>
        <div className="plan-grid">{plans.map((plan) => <article className={`plan ${plan.featured ? "featured" : ""}`} key={plan.name}>{plan.featured && <span className="popular">BUM BUM’S PICK</span>}<h3>{plan.name}</h3><div className="price">{plan.price}<small>{plan.note}</small></div><p className="credits">{plan.credits}</p><ul>{plan.features.map((feature) => <li key={feature}>✓ {feature}</li>)}</ul><PlanCta plan={plan.plan} label={plan.cta} href={plan.plan === "free" ? "/tools/" : `/checkout/?plan=${plan.plan}`} featured={plan.featured} /></article>)}</div>
        <p className="pricing-note">Actions reset monthly and do not roll over. We warn you at 80% and 100%. Paid plans can keep going with simple action packs, or you can pause until the reset.</p>
        <p className="section-more"><a href="/pricing/">How pricing works, in plain English <Arrow /></a></p>
      </section>

      <section className="guarantee" id="guarantee"><div className="shell guarantee-inner"><div className="guarantee-number" title="psst, triple-click me" onClick={(event) => { if (event.detail === 3) { setNapping(true); window.setTimeout(() => setNapping(false), 3200); } }}>36<span>HRS</span>{napping && <div className="nap-bubble">😴 Bum Bum is napping. The 36-hour clock respects nap time.</div>}</div><div><p className="kicker">The Operator promise</p><h2>A missing tool should not become a six-month project.</h2><p>Operator members get one scoped request each month. Once we agree on the tiny, useful version, Bum Bum ships it within 36 hours.</p><details><summary>What counts as a scoped request? <span>+</span></summary><p>One focused workflow that can be built in about four working hours. It can use approved existing services, but it cannot include regulated data, complex migrations, mobile app store review, or work waiting on a third party. The clock begins when scope and access are confirmed. Weekends and US federal holidays are excluded. If we miss the window, your next month is on us.</p></details><p className="section-more section-more-left"><a href="/36-hours/">Read the full 36-hour promise <Arrow /></a></p><p className="section-more section-more-left"><a href="/build-vs-buy/">Could you just build it yourself? We did the math <Arrow /></a></p></div></div></section>

      <section className="closing shell"><p className="kicker">Your to-don’t list starts here</p><h2>There has to be one thing<br />you would happily never do again.</h2><button className="button" onClick={() => openRequest()}>Tell Bum Bum <Arrow /></button></section>

      <SiteFooter />

      {requestModal}

      {party && <div className="confetti" aria-hidden="true">{CONFETTI.map((piece, i) => <span key={i} style={{ left: `${piece.left}%`, background: piece.color, width: piece.size, height: piece.size, borderRadius: piece.round ? "50%" : "2px", animationDelay: `${piece.delay}s`, animationDuration: `${piece.dur}s` }}>{piece.sparkle ? "✦" : ""}</span>)}</div>}
    </main>
  );
}
