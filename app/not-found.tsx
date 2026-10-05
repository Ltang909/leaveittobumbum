"use client";

import { useState } from "react";
import { SiteHeader, SiteFooter } from "./components/chrome";

const BUM_QUIPS = [
  "I sat on the URL. It's warm now.",
  "Have you tried turning the page off and on again?",
  "This one's on me. I'll expense it.",
  "404? I only count to 3. Then nap.",
  "It was here a minute ago. Probably.",
  "I licked it and now it won't load. Science.",
];

const PET_QUIPS = [
  "I've opened a ticket. Priority: low. Morale: lower.",
  "Per my last email, the page was supposed to be here.",
  "I'm going to need you to circle back to the homepage.",
  "This incident has been added to the retro deck.",
  "Let's take this offline. Everything is offline. The page is gone.",
  "I don't lose pages. Pages lose themselves around me.",
];

const EXCUSES = [
  "Bum Bum sat on the URL and now it's warm and unreadable.",
  "Pet Pet filed it under \u201CM\u201D for \u201Cmissing,\u201D which felt on the nose.",
  "The page left to pursue a solo career as a 404.",
  "It was last seen boarding a bus with the TV remote.",
  "A redirect ate it. Redirects do that.",
  "It achieved inbox zero and ascended to a higher plane.",
  "Bum Bum knocked it off the table. Classic.",
  "It's on a coffee break. Pages get those now.",
];

const LOST_AND_FOUND = [
  "One (1) TV remote. Still missing, somehow.",
  "A single sock. Its partner remains at large.",
  "Three bottle caps and a paperclip. Not a page.",
  "Pet Pet's spare headset. He wants it back.",
  "A note reading \u201Cgone fishin\u2019.\u201D The page cannot fish.",
  "That's the whole box. The page is not in the box.",
];

function Detective({
  name,
  img,
  alt,
  quip,
  onPoke,
}: {
  name: string;
  img: string;
  alt: string;
  quip: string | null;
  onPoke: () => void;
}) {
  return (
    <button type="button" className="detective" onClick={onPoke} aria-label={`Poke ${name}`}>
      {quip && <span className="speech">{quip}</span>}
      <img src={img} alt={alt} />
      <b>{name}</b>
      <i>poke for testimony</i>
    </button>
  );
}

export default function NotFound() {
  const [bumQuip, setBumQuip] = useState<string | null>(null);
  const [petQuip, setPetQuip] = useState<string | null>(null);
  const [bumClicks, setBumClicks] = useState(0);
  const [petClicks, setPetClicks] = useState(0);
  const [excuse, setExcuse] = useState(EXCUSES[0]);
  const [excuseClicks, setExcuseClicks] = useState(1);
  const [found, setFound] = useState<string | null>(null);
  const [foundClicks, setFoundClicks] = useState(0);
  const [crowned, setCrowned] = useState(false);

  return (
    <main>
      <SiteHeader />
      <section className="shell page-404">
        <p
          className="kicker"
          title="psst, triple-click me"
          onClick={(event) => {
            if (event.detail === 3) setCrowned(true);
          }}
        >
          404
        </p>
        <h1>The page pulled a disappearing act.</h1>
        <p className="lede">
          Bum Bum and Pet Pet are on the case. Poke them for testimony, file an excuse,
          or check the lost&nbsp;&amp;&nbsp;found.
        </p>

        <div className="detectives">
          <Detective
            name="Bum Bum"
            img={crowned ? "/bum/cat-crown.png" : "/bum/cat-curious.png"}
            alt={crowned ? "Bum Bum wearing a crown" : "Bum Bum looking curious"}
            quip={crowned ? "\uD83D\uDC51 Page Finder General. Self-appointed." : bumQuip}
            onPoke={() => {
              setBumQuip(BUM_QUIPS[bumClicks % BUM_QUIPS.length]);
              setBumClicks((c) => c + 1);
            }}
          />
          <Detective
            name="Pet Pet"
            img="/bum/petpet/headset.png"
            alt="Pet Pet wearing a headset, middle manager on duty"
            quip={petQuip}
            onPoke={() => {
              setPetQuip(PET_QUIPS[petClicks % PET_QUIPS.length]);
              setPetClicks((c) => c + 1);
            }}
          />
        </div>

        <div className="incident">
          <p className="kicker">Incident report #{String(excuseClicks).padStart(3, "0")}</p>
          <p className="excuse">{excuse}</p>
          <button
            type="button"
            className="button secondary"
            onClick={() => {
              const next = excuseClicks % EXCUSES.length;
              setExcuse(EXCUSES[next]);
              setExcuseClicks((c) => c + 1);
            }}
          >
            File another excuse
          </button>
        </div>

        <div className="lostfound">
          <button
            type="button"
            className="lostfound-box"
            onClick={() => {
              setFound(LOST_AND_FOUND[foundClicks % LOST_AND_FOUND.length]);
              setFoundClicks((c) => c + 1);
            }}
            aria-label="Rummage through the lost and found box"
          >
            <img src="/bum/petpet/box.png" alt="Pet Pet's lost and found box" />
            <span>Rummage the lost&nbsp;&amp;&nbsp;found</span>
          </button>
          {found && <p className="found-item">{found}</p>}
        </div>

        <p>
          <a className="button" href="/">Back home</a>{" "}
          <a className="button secondary" href="/tools/">Browse the toolbox</a>
        </p>
        <p className="fine">If I fits, I ships. This one did not fit.</p>
      </section>
      <SiteFooter />
    </main>
  );
}
