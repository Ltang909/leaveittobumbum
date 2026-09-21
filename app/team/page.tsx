import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";

export const metadata: Metadata = {
  title: "The Team | Leave It to Bum Bum",
  description: "Meet the elite squad behind Leave It to Bum Bum. One visionary cat, one middle manager, and two humans who keep the snacks coming.",
};

const crew = [
  {
    img: "/team/bumbum.jpg",
    alt: "Bum Bum the cat, staring directly into your soul",
    name: "Bum Bum",
    title: "CEO, CTO, COO, CMO, CRO",
    duties: "Responsibilities: all of it.",
    blurb:
      "Founder, visionary, chief nap strategist. Runs product, engineering, operations, marketing, and morale single-pawedly. Has never used a keyboard and honestly, that is the secret. Every tool on this site shipped because Bum Bum sat on the warm laptop until it was done.",
  },
  {
    img: "/team/petpet.jpg",
    alt: "Pet Pet the white cat, sitting very properly",
    name: "Pet Pet",
    title: "Manager",
    duties: 'Responsibilities: "leave it to bum bum"',
    blurb:
      "Middle management, literally. Pet Pet ensures every decision flows smoothly upward to Bum Bum, delegates flawlessly by doing nothing, and holds the record for longest uninterrupted sit (four hours, one sunbeam). A masterclass in leadership.",
  },
  {
    img: "/team/leon.jpg",
    alt: "Leon, smiling, resident dad",
    name: "Leon",
    title: "Dad",
    duties: "Responsibilities: feeder, poop scooper",
    blurb:
      "Keeps the CEO fed on schedule and the litter box within tolerance. Also allegedly builds the tools on this site with AI assistance, but everyone knows who really runs the standup. Paid in headbutts and the occasional 3am zoomies.",
  },
  {
    img: "/team/chi.webp",
    alt: "Chi, smiling, resident mom",
    name: "Chi",
    title: "Mom",
    duties: 'Responsibilities: "coach"',
    blurb:
      "Head coach. Believes in Bum Bum on the days Bum Bum does not believe in Bum Bum. Morale is up 300% quarter over quarter. Specializes in the pep talk, the treat bribe, and knowing exactly when the laptop needs to be closed for the night.",
  },
];

export default function Team() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">The humans (and management)</p>
        <h1>Meet the team.</h1>
        <p className="lede">Two visionary cats. Two humans in supporting roles. Zero meetings that could have been naps.</p>
      </section>
      <section className="shell" style={{ paddingBottom: 110 }}>
        <div
          style={{
            display: "grid",
            gridTemplateColumns: "repeat(auto-fit, minmax(260px, 1fr))",
            gap: 28,
          }}
        >
          {crew.map((m) => (
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
              <div style={{ aspectRatio: "1 / 1", overflow: "hidden" }}>
                <img
                  src={m.img}
                  alt={m.alt}
                  style={{ width: "100%", height: "100%", objectFit: "cover", display: "block" }}
                />
              </div>
              <div style={{ padding: "20px 22px 26px" }}>
                <h2 style={{ margin: "0 0 4px" }}>{m.name}</h2>
                <p style={{ margin: "0 0 10px", fontWeight: 700, opacity: 0.75 }}>{m.title}</p>
                <p style={{ margin: "0 0 12px", fontStyle: "italic" }}>{m.duties}</p>
                <p style={{ margin: 0 }}>{m.blurb}</p>
              </div>
            </article>
          ))}
        </div>
        <p className="prose" style={{ marginTop: 48, textAlign: "center" }}>
          Want to work with this elite squad? <a href="/tools/">Grab a tool</a> or <a href="/pricing/">join the plan</a>. Bum Bum approves.
        </p>
      </section>
      <SiteFooter />
    </main>
  );
}
