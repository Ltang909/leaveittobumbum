import type { Metadata } from "next";
import ToolsView from "./tools-view";

export const metadata: Metadata = {
  title: "The Toolbox | Leave It to Bum Bum",
  description: "Every tiny Bum Bum tool in one place. Pick the thing you don't want to do.",
};

export default function ToolsPage() {
  return <ToolsView />;
}
