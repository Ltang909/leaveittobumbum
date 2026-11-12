"use client";

import { useEffect, useState } from "react";
import { tools, Tool } from "./lib/tools";
import { SiteHeader, SiteFooter } from "./components/chrome";
import { useRequestTool } from "./components/request-tool";
import { PlanCta } from "./components/plan-ctas";
import CaptureBar from "./components/capture-bar";
import { DeskFaceProvider } from "./lib/desk-face";

const plans = [
  { name: "Poke around", price: "$0", note: "No card needed", credits: "75 actions each month", features: ["All current tools", "1 workspace", "Community request queue"], cta: "Start free", plan: "free" },
  { name: "Operator", price: "$49", note: "per month", credits: "Unlimited actions", features: ["Everything in Poke around", "Unlimited team members", "1 scoped tool request each month", "36-hour turnaround guarantee"], cta: "Choose Operator", plan: "operator", featured: true },
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

const FACES = [
  { src: "/bum/cat-laptop.png", alt: "Bum Bum at work" },
  { src: "/bum/cat-curious.png", alt: "Bum Bum, just vibing" },
  { src: "/bum/cat-wink-blep.png", alt: "Bum Bum winking" },
  { src: "/bum/cat-licking.png", alt: "Bum Bum blepping" },
  { src: "/bum/cat-sunglasses.png", alt: "Bum Bum looking cool" },
  { src: "/bum/cat-excited-v2.png", alt: "Bum Bum excited" },
  { src: "/bum/cat-bowtie.png", alt: "Bum Bum judging your tabs" },
  { src: "/bum/cat-peeking.png", alt: "Bum Bum peeking in" },
  { src: "/bum/cat-sleepy-v2.png", alt: "Bum Bum napping on the job" },
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

// The hero cat: Bum Bum as a cutout peeking over the desk, art supplied
// by Leon (Nov 11, 2026) with the burst and rays baked in. Booping still
// earns a quip, and the Konami party dance still applies.
function HeroPortrait({ party }: { party: boolean }) {
  const [quip, setQuip] = useState<string | null>(null);
  const [boingKey, setBoingKey] = useState(0);

  function boopBumBum() {
    setBoingKey((k) => k + 1);
    setQuip(QUIPS[Math.floor(Math.random() * QUIPS.length)]);
    window.setTimeout(() => setQuip(null), 2600);
  }

  return (
    <div className="hero-portrait hero-cat" aria-label="Bum Bum, chief tiny-tool operator">
      <div
        key={boingKey}
        className={`hero-cat-boop boopable${boingKey && !party ? " boing" : ""}${party ? " dance" : ""}`}
        onClick={boopBumBum}
        onKeyDown={(event) => { if (event.key === "Enter" || event.key === " ") { event.preventDefault(); boopBumBum(); } }}
        role="button"
        tabIndex={0}
        aria-label="Boop Bum Bum"
      >
        <img className="hero-cat-img" src="/bum/hero-cat.png" alt="Bum Bum the cat peeking over the desk" />
      </div>
      <div className="hero-cat-tag">this is Bum Bum</div>
      <svg className="hero-cat-arrow" viewBox="0 0 60 56" aria-hidden="true"><path d="M54 6C42 24 28 38 12 47" fill="none" stroke="#17150F" strokeWidth="3.5" strokeLinecap="round" /><path d="M21 45 L11 48 L13 37" fill="none" stroke="#17150F" strokeWidth="3.5" strokeLinecap="round" strokeLinejoin="round" /></svg>
      <svg className="hero-cat-arrow-mobile" viewBox="0 0 24 44" aria-hidden="true"><path d="M12 3C10.5 15 13 25 12 36" fill="none" stroke="#17150F" strokeWidth="3.5" strokeLinecap="round" /><path d="M4.5 28.5 L12 38.5 L19.5 28.5" fill="none" stroke="#17150F" strokeWidth="3.5" strokeLinecap="round" strokeLinejoin="round" /></svg>
      {quip && <div className="quip-bubble" role="status">{quip}</div>}
    </div>
  );
}

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
    FACES.forEach((f) => { new Image().src = f.src; });
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

  return (
    <main>
      <SiteHeader />

      <section className="hero hero-desk shell" id="top">
        <DeskFaceProvider>
          <div className="hero-top">
            <div className="hero-copy">
              <div className="hero-headline"><h1><span className="h1-line">Don’t wanna do it?</span><br /><em>Leave it to Bum Bum.</em></h1></div>
              <p className="lede">Little tools that handle the fiddly stuff stealing your afternoon.</p>
            </div>
            <HeroPortrait party={party} />
          </div>
          <CaptureBar />
        </DeskFaceProvider>
      </section>

      <section className={`ticker${napZoom ? " zoomies" : ""}`} aria-label="Examples"><div className="ticker-track">{[0, 1].map((copy) => (<div className="ticker-chunk" key={copy} aria-hidden={copy === 1 || undefined}>{["READ MY RECEIPTS", "CHASE THAT INVOICE", "CLIP THE GOOD PART", "FILE THE VOICE NOTE", "POSTER IN, EVENTS OUT", "LEAVE IT TO BUM BUM"].map((t) => (<span key={t} className="ticker-item">{t}<i>✦</i></span>))}</div>))}</div></section>

      <section className="toolbox shell" id="toolbox">
        <div className="section-heading"><div><p className="kicker">Bum Bum’s toolbox</p><h2>Pick the thing you<br />don’t want to do.</h2></div></div>
        <div className="tool-grid">{homeTools.map((tool, index) => <a className={`tool-card card-${(index % 8) + 1}`} key={tool.key} href={tool.url}><div className="tool-top"><span className="tool-icon">{tool.icon}</span><span className="tool-tag">{tool.tag}</span></div><h3>{tool.cardTitle ? <>{tool.cardTitle[0]}<br />{tool.cardTitle[1]}</> : tool.name}</h3><p>{tool.description}</p><span className="tool-cta">{tool.cta} <Arrow /></span></a>)}</div>
        <div className="toolbox-more"><a className="button" href="/tools/">Browse the full toolbox <Arrow /></a></div>
        <p className="section-more section-more-left"><a href="#guarantee">Missing something? Bum Bum builds it in 36 hours <Arrow /></a></p>
      </section>

      <section className="how">
        <div className="shell"><p className="way-kicker">No software degree required</p><h2 className="way-title">A simpler way to<br /><span className="way-under">get more done.<svg className="way-swoosh" viewBox="0 0 300 22" preserveAspectRatio="none" aria-hidden="true"><path d="M4 15 C 70 5, 200 3, 296 9" fill="none" stroke="#FFD84D" strokeWidth="9" strokeLinecap="round" /></svg></span></h2><div className="way-panels"><article className="way-panel"><div className="way-icon"><svg viewBox="0 0 120 112" aria-hidden="true"><path d="M93 4v11M104 10l-7 9M83 10l6 9" stroke="#FFD84D" strokeWidth="4.5" strokeLinecap="round" fill="none" /><rect x="16" y="16" width="66" height="82" rx="14" fill="#FFF6E3" stroke="#17150F" strokeWidth="3" /><rect x="27" y="29" width="12" height="12" rx="3" fill="#fff" stroke="#17150F" strokeWidth="2.5" /><path d="M29.8 35.2l2.6 2.6 4.4-5" stroke="#F5A800" strokeWidth="2.6" fill="none" strokeLinecap="round" /><rect x="27" y="51" width="12" height="12" rx="3" fill="#fff" stroke="#17150F" strokeWidth="2.5" /><path d="M29.8 57.2l2.6 2.6 4.4-5" stroke="#F5A800" strokeWidth="2.6" fill="none" strokeLinecap="round" /><rect x="27" y="73" width="12" height="12" rx="3" fill="#fff" stroke="#17150F" strokeWidth="2.5" /><path d="M48 35h23M48 57h23M48 79h23" stroke="#17150F" strokeWidth="3" strokeLinecap="round" /></svg></div><div><h3>Turn your to-do list into done.</h3><p>Invoicing, follow ups, receipts, emails and more.</p></div></article><article className="way-panel"><div className="way-icon"><svg viewBox="0 0 120 112" aria-hidden="true"><path d="M99 4v11M110 10l-7 9M89 10l6 9" stroke="#FFD84D" strokeWidth="4.5" strokeLinecap="round" fill="none" /><rect x="13" y="26" width="84" height="56" rx="10" fill="#FFF6E3" stroke="#17150F" strokeWidth="3" /><path d="M42 45l2.3 6.2L50.5 53.5l-6.2 2.3L42 62l-2.3-6.2L33.5 53.5l6.2-2.3Z" fill="#FFD84D" stroke="#17150F" strokeWidth="1.6" /><path d="M65 40l1.7 4.6 4.6 1.7-4.6 1.7L65 52.6l-1.7-4.6-4.6-1.7 4.6-1.7Z" fill="#FFD84D" stroke="#17150F" strokeWidth="1.4" /><path d="M7 88h96l-7 12H14Z" fill="#FFF6E3" stroke="#17150F" strokeWidth="3" /><path d="M47 94h16" stroke="#2864DC" strokeWidth="3" strokeLinecap="round" /></svg></div><div><h3>Tools created to do exactly what you need.</h3><p>Drop in your notes, files, or ideas and Bum Bum handles the rest.</p></div></article><article className="way-panel"><div className="way-icon"><svg viewBox="0 0 120 112" aria-hidden="true"><path d="M45 4c-4 6 4 8 0 14M63 4c-4 6 4 8 0 14" stroke="#FFF6E3" strokeWidth="3" fill="none" strokeLinecap="round" /><path d="M99 8v11M110 14l-7 9M89 14l6 9" stroke="#FFD84D" strokeWidth="4.5" strokeLinecap="round" fill="none" /><rect x="24" y="30" width="58" height="60" rx="11" fill="#FFF6E3" stroke="#17150F" strokeWidth="3" /><path d="M82 44c13 1 13 27 0 30" fill="none" stroke="#17150F" strokeWidth="3" /><g fill="#FFD84D"><ellipse cx="53" cy="68" rx="9" ry="7" /><circle cx="39" cy="56" r="3.4" /><circle cx="48" cy="51.5" r="3.4" /><circle cx="58" cy="51.5" r="3.4" /><circle cx="67" cy="56" r="3.4" /></g></svg></div><div><h3>More time for what you actually like.</h3><p>Go touch grass!</p></div></article></div></div>
      </section>

      <section className="pricing shell" id="pricing">
        <div className="section-heading"><div><p className="kicker">One shared meter</p><h2>One shared meter<br />for all your tools.</h2></div><p>Every completed result uses one action: a finished clip, a batch of receipts, a chased invoice, a playbook. Your whole team and every tool share the same monthly bucket.</p></div>
        <div className="plan-grid">{plans.map((plan) => <article className={`plan ${plan.featured ? "featured" : ""}`} key={plan.name}>{plan.featured && <span className="popular">BUM BUM’S PICK</span>}<h3>{plan.name}</h3><div className="price">{plan.price}<small>{plan.note}</small></div><p className="credits">{plan.credits}</p><ul>{plan.features.map((feature) => <li key={feature}>✓ {feature}</li>)}</ul><PlanCta plan={plan.plan} label={plan.cta} href={plan.plan === "free" ? "/tools/" : `/checkout/?plan=${plan.plan}`} featured={plan.featured} /></article>)}</div>
        <p className="pricing-note">Actions on Poke around reset monthly and do not roll over. We warn you at 80% and 100%. Operator never runs out.</p>
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
