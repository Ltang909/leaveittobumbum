import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";
import RequestsView from "./requests-view";

export const metadata: Metadata = {
  title: "Community request queue | Leave It to Bum Bum",
  description: "See what tools the community has requested, upvote the ones you want, and add your own.",
};

export default function Requests() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell">
        <p className="kicker">Community request queue</p>
        <h1>What should<br />Bum Bum build next?</h1>
        <p className="lede">Every request below came from someone like you. Upvote the ones you want, or add your own. The most-wanted tools get built first.</p>
      </section>
      <RequestsView />
      <SiteFooter />
    </main>
  );
}
