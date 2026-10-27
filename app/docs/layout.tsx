import type { ReactNode } from "react";
import { SiteHeader, SiteFooter } from "../components/chrome";
import DocsNav from "./docs-nav";

export default function DocsLayout({ children }: { children: ReactNode }) {
  return (
    <main>
      <SiteHeader />
      <div className="shell docs-shell">
        <DocsNav />
        <div className="docs-main">{children}</div>
      </div>
      <SiteFooter />
    </main>
  );
}
