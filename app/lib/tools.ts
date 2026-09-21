import registry from "../../hostinger/public_html/tools/registry.json";

export type Tool = {
  key: string;
  name: string;
  tag: string;
  icon: string;
  description: string;
  url: string;
  cta: string;
  mascot?: string;
};

const data = registry as { tools: Tool[] };

export const tools: Tool[] = data.tools;
