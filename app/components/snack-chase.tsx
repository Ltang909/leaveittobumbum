"use client";

import { useEffect, useRef, useState } from "react";

function playMeow() {
  try {
    const Ctx = window.AudioContext || (window as unknown as { webkitAudioContext: typeof AudioContext }).webkitAudioContext;
    const ctx = new Ctx();
    const t0 = ctx.currentTime;

    function voice(start: number, base: number, dur: number, peak: number, lfoHz: number) {
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
      osc.frequency.linearRampToValueAtTime(base * peak, start + dur * 0.3);
      osc.frequency.linearRampToValueAtTime(base * 0.7, start + dur);
      lfo.frequency.value = lfoHz;
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

    voice(t0, 520, 0.5, 1.7, 9);
    voice(t0 + 0.55, 720, 0.32, 1.5, 11);
    window.setTimeout(() => ctx.close(), 1600);
  } catch {
    /* no audio, still a fine chase */
  }
}

function playMrrp() {
  try {
    const Ctx = window.AudioContext || (window as unknown as { webkitAudioContext: typeof AudioContext }).webkitAudioContext;
    const ctx = new Ctx();
    const t0 = ctx.currentTime;
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    const lfo = ctx.createOscillator();
    const lfoGain = ctx.createGain();
    osc.type = "sawtooth";
    osc.frequency.setValueAtTime(880, t0);
    osc.frequency.linearRampToValueAtTime(1420, t0 + 0.16);
    lfo.frequency.value = 22;
    lfoGain.gain.value = 90;
    lfo.connect(lfoGain);
    lfoGain.connect(osc.frequency);
    gain.gain.setValueAtTime(0.0001, t0);
    gain.gain.exponentialRampToValueAtTime(0.3, t0 + 0.03);
    gain.gain.exponentialRampToValueAtTime(0.0001, t0 + 0.2);
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.start(t0);
    lfo.start(t0);
    osc.stop(t0 + 0.25);
    lfo.stop(t0 + 0.25);
    window.setTimeout(() => ctx.close(), 600);
  } catch {
    /* silent snack */
  }
}

export function SnackChase() {
  const [running, setRunning] = useState(false);
  const timers = useRef<number[]>([]);

  useEffect(() => {
    const stash = timers.current;
    return () => stash.forEach((t) => window.clearTimeout(t));
  }, []);

  function start() {
    if (running) return;
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
    timers.current.forEach((t) => window.clearTimeout(t));
    timers.current = [];
    playMeow();
    timers.current.push(window.setTimeout(() => playMrrp(), 1500));
    setRunning(true);
    timers.current.push(window.setTimeout(() => setRunning(false), 3700));
  }

  return (
    <>
      <button type="button" className="text-button snack-link" onClick={start}>
        Throw Bum Bum a snack
      </button>
      {running && (
        <div className="snack-stage" aria-hidden="true">
          <span className="snack-treat">🐟</span>
          <div className="snack-runner">
            <img className="snack-chaser" src="/bum/cat-butt.png" alt="" />
          </div>
          <span className="snack-dust d1" />
          <span className="snack-dust d2" />
          <span className="snack-dust d3" />
          <span className="snack-dust d4" />
          <img className="snack-nommer" src="/bum/cat-excited.png" alt="" />
          <span className="snack-nom">nom nom nom.</span>
        </div>
      )}
    </>
  );
}
