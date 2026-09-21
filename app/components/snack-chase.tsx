"use client";

import { useRef, useState } from "react";

function playMeow() {
  try {
    const Ctx = window.AudioContext || (window as unknown as { webkitAudioContext: typeof AudioContext }).webkitAudioContext;
    const ctx = new Ctx();
    const t0 = ctx.currentTime;

    function meow(start: number, base: number, dur: number) {
      const osc = ctx.createOscillator();
      const filter = ctx.createBiquadFilter();
      const gain = ctx.createGain();
      const lfo = ctx.createOscillator();
      const lfoGain = ctx.createGain();
      osc.type = "sawtooth";
      filter.type = "lowpass";
      filter.frequency.value = 1700;
      filter.Q.value = 5;
      osc.frequency.setValueAtTime(base, start);
      osc.frequency.linearRampToValueAtTime(base * 1.7, start + dur * 0.3);
      osc.frequency.linearRampToValueAtTime(base * 0.7, start + dur);
      lfo.frequency.value = 9;
      lfoGain.gain.value = base * 0.08;
      lfo.connect(lfoGain);
      lfoGain.connect(osc.frequency);
      gain.gain.setValueAtTime(0.0001, start);
      gain.gain.exponentialRampToValueAtTime(0.4, start + 0.05);
      gain.gain.exponentialRampToValueAtTime(0.0001, start + dur);
      osc.connect(filter);
      filter.connect(gain);
      gain.connect(ctx.destination);
      osc.start(start);
      lfo.start(start);
      osc.stop(start + dur + 0.05);
      lfo.stop(start + dur + 0.05);
    }

    meow(t0, 520, 0.5);
    meow(t0 + 0.55, 720, 0.32);
    window.setTimeout(() => ctx.close(), 1600);
  } catch {
    /* no audio, still a fine chase */
  }
}

export function SnackChase() {
  const [phase, setPhase] = useState<"idle" | "run" | "caught">("idle");
  const timers = useRef<number[]>([]);

  function start() {
    if (phase !== "idle") return;
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
    timers.current.forEach((t) => window.clearTimeout(t));
    timers.current = [];
    playMeow();
    setPhase("run");
    timers.current.push(window.setTimeout(() => setPhase("caught"), 2200));
    timers.current.push(window.setTimeout(() => setPhase("idle"), 3600));
  }

  return (
    <>
      <button type="button" className="text-button snack-link" onClick={start}>
        Throw Bum Bum a snack
      </button>
      {phase !== "idle" && (
        <div className="snack-stage" aria-hidden="true">
          {phase === "run" && <span className="snack-treat">🐟</span>}
          <img className="snack-cat" src="/bum/cat-butt.png" alt="" />
          {phase === "caught" && <span className="snack-nom">nom.</span>}
        </div>
      )}
    </>
  );
}
