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
    // Chi's anthem has not dropped yet. The portrait still teases it.
  },
];

export default function CrewCards() {
  const audioRefs = useRef<Record<string, HTMLAudioElement>>({});
  const [playing, setPlaying] = useState<string | null>(null);
  const [chiNote, setChiNote] = useState(false);
  const chiTimer = useRef<number | null>(null);

  useEffect(
    () => () => {
      Object.values(audioRefs.current).forEach((a) => a.pause());
      if (chiTimer.current) window.clearTimeout(chiTimer.current);
    },
    []
  );

  function toggle(member: Member) {
    if (!member.song) {
      // Chi: no anthem yet, just a whisper that one is coming.
      setChiNote(true);
      if (chiTimer.current) window.clearTimeout(chiTimer.current);
      chiTimer.current = window.setTimeout(() => setChiNote(false), 2400);
      return;
    }
    const key = member.name;
    let audio = audioRefs.current[key];
    if (!audio) {
      audio = new Audio(member.song);
      audioRefs.current[key] = audio;
      audio.onended = () => setPlaying((p) => (p === key ? null : p));
    }
    if (playing === key) {
      audio.pause();
      setPlaying(null);
      return;
    }
    Object.entries(audioRefs.current).forEach(([k, a]) => {
      if (k !== key) a.pause();
    });
    audio.play().catch(() => {});
    setPlaying(key);
  }

  return (
    <>
      <style>{`
        .team-song-btn { position: relative; display: block; width: 100%; aspect-ratio: 1 / 1; padding: 0; border: 0; background: none; cursor: pointer; overflow: hidden; }
        .team-song-btn img { width: 100%; height: 100%; object-fit: cover; display: block; }
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
          const showHint = isPlaying || (m.name === "Chi" && chiNote);
          const hintText = isPlaying
            ? `\u266A Now playing: ${m.songLabel}`
            : m.song
              ? "\u266A psst \u2014 click for a tune"
              : "\u266A Chi's song is coming!";
          return (
            <article
              key={m.name}
              style={{
                background: "var(--card, #fffdf6)",
                border: "2px solid var(--ink, #2b2118)",
                borderRadius: 18,
                overflow: "hidden",
                boxShadow: "4px 4px 0 var(--ink, #2b2118)",
              }}
            >
              <button
                type="button"
                className="team-song-btn"
                onClick={() => toggle(m)}
                aria-label={
                  m.song
                    ? `Play ${m.name}'s theme song${isPlaying ? " (playing, click to pause)" : ""}`
                    : `${m.name}'s song is coming soon`
                }
                aria-pressed={isPlaying}
              >
                <img src={m.img} alt={m.alt} />
                <span className={`team-song-hint${showHint ? " is-on" : ""}`}>
                  {hintText}
                </span>
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
