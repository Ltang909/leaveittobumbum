"use client";

import { useEffect, useRef, useState } from "react";

type Member = {
  img: string;
  alt: string;
  name: string;
  title: string;
  duties: string;
  blurb: string;
  song?: string;
  songLabel?: string;
};

const crew: Member[] = [
  {
    img: "/team/bumbum.jpg",
    alt: "Bum Bum the cat, staring directly into your soul",
    name: "Bum Bum",
    title: "CEO, CTO, COO, CMO, CRO",
    duties: "Responsibilities: all of it.",
    blurb:
      "Founder, visionary, chief nap strategist. Runs product, engineering, operations, marketing, and morale single-pawedly. Has never used a keyboard and honestly, that is the secret. Every tool on this site shipped because Bum Bum sat on the warm laptop until it was done.",
    song: "/team-songs/bum-bum-saves-the-day.mp3",
    songLabel: "Bum Bum Saves the Day",
  },
  {
    img: "/team/petpet.jpg",
    alt: "Pet Pet the white cat, sitting very properly",
    name: "Pet Pet",
    title: "Manager",
    duties: 'Responsibilities: "leave it to bum bum"',
    blurb:
      "Middle management, literally. Pet Pet ensures every decision flows smoothly upward to Bum Bum, delegates flawlessly by doing nothing, and holds the record for longest uninterrupted sit (four hours, one sunbeam). A masterclass in leadership.",
    song: "/team-songs/pet-pet.mp3",
    songLabel: "Pet Pet",
  },
  {
    img: "/team/leon.jpg",
    alt: "Leon, smiling, resident dad",
    name: "Leon",
    title: "Dad",
    duties: "Responsibilities: feeder, poop scooper",
    blurb:
      "Keeps the CEO fed on schedule and the litter box within tolerance. Also allegedly builds the tools on this site with AI assistance, but everyone knows who really runs the standup. Paid in headbutts and the occasional 3am zoomies.",
    song: "/team-songs/i-am-the-feeder.mp3",
    songLabel: "I Am the Feeder",
  },
  {
    img: "/team/chi.webp",
    alt: "Chi, smiling, resident mom",
    name: "Chi",
    title: "Mom",
    duties: 'Responsibilities: "coach"',
    blurb:
      "Head coach. Believes in Bum Bum on the days Bum Bum does not believe in Bum Bum. Morale is up 300% quarter over quarter. Specializes in the pep talk, the treat bribe, and knowing exactly when the laptop needs to be closed for the night.",
    song: "/team-songs/nobody-asked-me.mp3",
    songLabel: "Nobody Asked Me",
  },
];

export default function CrewCards() {
  const audioRefs = useRef<Record<string, HTMLAudioElement>>({});
  const cardRefs = useRef<Record<string, HTMLElement | null>>({});
  const ctxRef = useRef<AudioContext | null>(null);
  const analyserRef = useRef<AnalyserNode | null>(null);
  const sourcesRef = useRef(new Map<HTMLAudioElement, MediaElementAudioSourceNode>());
  const freqRef = useRef<Uint8Array<ArrayBuffer> | null>(null);
  const rafRef = useRef<number | null>(null);
  const smoothRef = useRef(0);
  const playingRef = useRef<string | null>(null);
  const reduceMotionRef = useRef(false);
  const [playing, setPlaying] = useState<string | null>(null);

  useEffect(() => {
    reduceMotionRef.current = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    return () => {
      stopLoop();
      Object.values(audioRefs.current).forEach((a) => a.pause());
      ctxRef.current?.close().catch(() => {});
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  function setPlayingKey(key: string | null) {
    playingRef.current = key;
    setPlaying(key);
  }

  function stopLoop() {
    if (rafRef.current !== null) cancelAnimationFrame(rafRef.current);
    rafRef.current = null;
    smoothRef.current = 0;
    Object.values(cardRefs.current).forEach((el) => el?.style.setProperty("--beat", "0"));
  }

  function ensureGraph(audio: HTMLAudioElement): boolean {
    const w = window as unknown as { webkitAudioContext?: typeof AudioContext };
    const AC = window.AudioContext ?? w.webkitAudioContext;
    if (!AC) return false;
    if (!ctxRef.current) {
      const ctx = new AC();
      const analyser = ctx.createAnalyser();
      analyser.fftSize = 256;
      analyser.smoothingTimeConstant = 0.72;
      analyser.connect(ctx.destination);
      ctxRef.current = ctx;
      analyserRef.current = analyser;
      freqRef.current = new Uint8Array(new ArrayBuffer(analyser.frequencyBinCount));
    }
    if (ctxRef.current.state === "suspended") void ctxRef.current.resume();
    if (!sourcesRef.current.has(audio)) {
      const src = ctxRef.current.createMediaElementSource(audio);
      src.connect(analyserRef.current!);
      sourcesRef.current.set(audio, src);
    }
    return true;
  }

  function tick() {
    const analyser = analyserRef.current;
    const freq = freqRef.current;
    const key = playingRef.current;
    const card = key ? cardRefs.current[key] : null;
    if (!analyser || !freq || !card) {
      rafRef.current = null;
      return;
    }
    analyser.getByteFrequencyData(freq);
    let sum = 0;
    for (let i = 1; i <= 6; i++) sum += freq[i];
    const target = Math.min(1, (sum / 6 / 255) * 1.9);
    const s = smoothRef.current;
    // Fast attack, slow release: the glow punches with the kick, then breathes out.
    smoothRef.current = target > s ? s + (target - s) * 0.55 : s + (target - s) * 0.14;
    card.style.setProperty("--beat", smoothRef.current.toFixed(3));
    rafRef.current = requestAnimationFrame(tick);
  }

  function startLoop() {
    if (reduceMotionRef.current) return;
    if (rafRef.current !== null) return;
    rafRef.current = requestAnimationFrame(tick);
  }

  function toggle(member: Member) {
    if (!member.song) return;
    const key = member.name;
    let audio = audioRefs.current[key];
    if (!audio) {
      audio = new Audio(member.song);
      audioRefs.current[key] = audio;
      audio.onended = () => {
        setPlayingKey(null);
        stopLoop();
      };
    }
    if (playingRef.current === key) {
      audio.pause();
      setPlayingKey(null);
      stopLoop();
      return;
    }
    Object.entries(audioRefs.current).forEach(([k, a]) => {
      if (k !== key) a.pause();
    });
    stopLoop();
    if (ensureGraph(audio)) startLoop();
    audio
      .play()
      .then(() => setPlayingKey(key))
      .catch(() => {
        setPlayingKey(null);
        stopLoop();
      });
  }

  return (
    <>
      <style>{`
        .team-card { --beat: 0; }
        .team-song-btn { position: relative; display: block; width: 100%; aspect-ratio: 1 / 1; padding: 0; border: 0; background: none; cursor: pointer; overflow: hidden; }
        .team-song-btn img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .beat-wash {
          position: absolute; inset: 0; pointer-events: none;
          opacity: 0; transition: opacity 0.4s ease;
          background: radial-gradient(ellipse at 50% 62%, rgba(255, 205, 110, 0.34), rgba(255, 175, 70, 0.10) 55%, rgba(255, 175, 70, 0) 75%);
        }
        .team-song-btn.is-playing .beat-wash { opacity: calc(0.45 + var(--beat, 0) * 0.55); }
        .team-song-hint {
          position: absolute; left: 50%; bottom: 12px;
          transform: translateX(-50%) translateY(4px);
          background: rgba(255, 253, 246, 0.96);
          border: 1px solid var(--ink, #2b2118); border-radius: 999px;
          padding: 6px 14px; font-size: 13px; font-weight: 700; white-space: nowrap;
          color: var(--ink, #2b2118);
          opacity: 0; transition: opacity 0.25s ease, transform 0.25s ease;
          pointer-events: none; box-shadow: 2px 2px 0 rgba(43, 33, 24, 0.85);
        }
        .team-song-btn:hover .team-song-hint,
        .team-song-btn:focus-visible .team-song-hint,
        .team-song-hint.is-on { opacity: 1; transform: translateX(-50%) translateY(0); }
        .team-song-btn.is-playing .team-song-hint.is-on { transform: translateX(-50%) scale(calc(1 + var(--beat, 0) * 0.05)); }
      `}</style>
      <div
        style={{
          display: "grid",
          gridTemplateColumns: "repeat(auto-fit, minmax(260px, 1fr))",
          gap: 28,
        }}
      >
        {crew.map((m) => {
          const isPlaying = playing === m.name;
          const showHint = isPlaying;
          const hintText = isPlaying
            ? `\u266A Now playing: ${m.songLabel}`
            : "\u266A psst \u2014 click for a tune";
          return (
            <article
              key={m.name}
              ref={(el) => {
                cardRefs.current[m.name] = el;
              }}
              className={isPlaying ? "team-card is-playing" : "team-card"}
              style={{
                background: "var(--card, #fffdf6)",
                border: "2px solid var(--ink, #2b2118)",
                borderRadius: 18,
                overflow: "hidden",
                boxShadow: isPlaying
                  ? "4px 4px 0 var(--ink, #2b2118), 0 0 calc(22px + var(--beat, 0) * 60px) rgba(255, 186, 88, calc(0.35 + var(--beat, 0) * 0.55))"
                  : "4px 4px 0 var(--ink, #2b2118)",
              }}
            >
              <button
                type="button"
                className={isPlaying ? "team-song-btn is-playing" : "team-song-btn"}
                onClick={() => toggle(m)}
                aria-label={`Play ${m.name}'s theme song${isPlaying ? " (playing, click to pause)" : ""}`}
                aria-pressed={isPlaying}
              >
                <img src={m.img} alt={m.alt} />
                <span className="beat-wash" aria-hidden="true" />
                <span className={`team-song-hint${showHint ? " is-on" : ""}`}>{hintText}</span>
              </button>
              <div style={{ padding: "20px 22px 26px" }}>
                <h2 style={{ margin: "0 0 4px" }}>{m.name}</h2>
                <p style={{ margin: "0 0 10px", fontWeight: 700, opacity: 0.75 }}>{m.title}</p>
                <p style={{ margin: "0 0 12px", fontStyle: "italic" }}>{m.duties}</p>
                <p style={{ margin: 0 }}>{m.blurb}</p>
              </div>
            </article>
          );
        })}
      </div>
    </>
  );
}
