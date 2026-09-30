import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";
import CrewCards from "./crew-cards";

export const metadata: Metadata = {
  title: "The Team | Leave It to Bum Bum",
  description: "Meet the elite squad behind Leave It to Bum Bum. One visionary cat, one middle manager, and two humans who keep the snacks coming.",
};

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
        <CrewCards />
        <p className="prose" style={{ marginTop: 48, textAlign: "center" }}>
          Want to work with this elite squad? <a href="/tools/">Grab a tool</a> or <a href="/pricing/">join the plan</a>. Bum Bum approves.
        </p>
      </section>
      <SiteFooter />
    </main>
  );
}
