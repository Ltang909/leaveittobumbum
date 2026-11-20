// After the Next.js static export, Hostinger/LiteSpeed serves /page/ URLs from
// /page/index.html, so copy every top-level out/*.html into its own folder.
const fs = require("fs");
const path = require("path");

const SKIP = new Set(["index.html", "404.html", "_not-found.html"]);

for (const f of fs.readdirSync("out")) {
  if (!f.endsWith(".html") || SKIP.has(f)) continue;
  const p = path.basename(f, ".html");
  fs.mkdirSync("out/" + p, { recursive: true });
  fs.copyFileSync("out/" + f, "out/" + p + "/index.html");
  console.log("postbuild: " + p + "/index.html ready");
}

// Agent text versions (added Nov 12, 2026, after a Coframe agent check
// flagged that no page had a text version or pointed agents to one):
// every exported page gets a plain markdown twin at <route>.md and a
// <link rel="alternate" type="text/markdown"> in its head pointing at
// it, so an AI agent can read the page in one request. Runs over the
// final tree, after the folder-copy step above. PHP tool pages are not
// part of the export and do not get twins here.
const SITE = "https://leaveittobumbum.com";
const AGENT_SKIP = new Set(["404.html", "_not-found.html"]);

const ENTITIES = {
  amp: "&", lt: "<", gt: ">", quot: '"', apos: "'", nbsp: " ",
  rsquo: "’", lsquo: "‘", rdquo: "”", ldquo: "“",
  mdash: "—", ndash: "–", hellip: "…", copy: "©",
};

function decodeEntities(s) {
  return s.replace(/&(#x?[0-9a-fA-F]+|[a-zA-Z]+);/g, (m, g) => {
    if (g[0] === "#") {
      const n = g[1] === "x" || g[1] === "X" ? parseInt(g.slice(2), 16) : parseInt(g.slice(1), 10);
      return Number.isFinite(n) ? String.fromCodePoint(n) : m;
    }
    return ENTITIES[g] !== undefined ? ENTITIES[g] : m;
  });
}

function htmlToMd(html, pageUrl) {
  const titleM = html.match(/<title[^>]*>([\s\S]*?)<\/title>/i);
  const title = titleM ? decodeEntities(titleM[1].replace(/<[^>]+>/g, "")).trim() : "Leave It to Bum Bum";
  const mainM = html.match(/<main[^>]*>([\s\S]*?)<\/main>/i);
  const bodyM = html.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
  let s = mainM ? mainM[1] : bodyM ? bodyM[1] : html;
  s = s
    .replace(/<script[\s\S]*?<\/script>/gi, "")
    .replace(/<style[\s\S]*?<\/style>/gi, "")
    .replace(/<noscript[\s\S]*?<\/noscript>/gi, "")
    .replace(/<svg[\s\S]*?<\/svg>/gi, "")
    .replace(/<template[\s\S]*?<\/template>/gi, "")
    .replace(/<!--[\s\S]*?-->/g, "");
  s = s.replace(/<a\s[^>]*href="([^"]*)"[^>]*>([\s\S]*?)<\/a>/gi, (m, href, txt) => {
    const t = txt.replace(/<[^>]+>/g, " ").replace(/\s+/g, " ").trim();
    if (!t) return "";
    const h = href.startsWith("/") ? SITE + href : href;
    return " [" + t + "](" + h + ") ";
  });
  s = s.replace(/<img[^>]*>/gi, (tag) => {
    const alt = (tag.match(/alt="([^"]*)"/i) || [])[1] || "";
    const src = (tag.match(/src="([^"]*)"/i) || [])[1] || "";
    if (!alt || !src) return " ";
    return " ![" + alt + "](" + (src.startsWith("/") ? SITE + src : src) + ") ";
  });
  s = s
    .replace(/<h1[^>]*>/gi, "\n\n# ")
    .replace(/<h2[^>]*>/gi, "\n\n## ")
    .replace(/<h3[^>]*>/gi, "\n\n### ")
    .replace(/<h4[^>]*>/gi, "\n\n#### ")
    .replace(/<h5[^>]*>/gi, "\n\n##### ")
    .replace(/<h6[^>]*>/gi, "\n\n###### ")
    .replace(/<\/h[1-6]>/gi, "\n")
    .replace(/<li[^>]*>/gi, "\n- ")
    .replace(/<\/li>/gi, "")
    .replace(/<br\s*\/?>/gi, "\n")
    .replace(/<\/(p|div|section|article|header|footer|main|ul|ol|table|tr|blockquote|details|summary|figure|figcaption)>/gi, "\n\n")
    .replace(/<(strong|b)>/gi, "**")
    .replace(/<\/(strong|b)>/gi, "**")
    .replace(/<(em|i)>/gi, "*")
    .replace(/<\/(em|i)>/gi, "*")
    .replace(/<[^>]+>/g, "");
  s = decodeEntities(s);
  s = s
    .split("\n")
    .map((l) => l.replace(/[ \t]+/g, " ").trim())
    .join("\n")
    .replace(/\n{3,}/g, "\n\n")
    .trim();
  return "# " + title + "\n\nSource: " + pageUrl + "\n\n" + s + "\n";
}

function routeFor(rel) {
  const r = rel.replace(/\\/g, "/");
  if (r === "index.html") return "/";
  if (r.endsWith("/index.html")) return "/" + r.slice(0, -"index.html".length);
  if (r.endsWith(".html")) return "/" + r.slice(0, -".html".length) + "/";
  return null;
}

function collectHtml(dir, base, acc) {
  for (const name of fs.readdirSync(dir)) {
    const full = path.join(dir, name);
    const rel = base ? base + "/" + name : name;
    if (fs.statSync(full).isDirectory()) {
      if (name === "_next") continue;
      collectHtml(full, rel, acc);
    } else if (name.endsWith(".html") && !AGENT_SKIP.has(name)) {
      acc.push({ full, rel });
    }
  }
  return acc;
}

const agentPages = collectHtml("out", "", []);
let agentCount = 0;
const mdByRoute = new Map();
for (const { full, rel } of agentPages) {
  const route = routeFor(rel);
  if (!route) continue;
  const mdPath = route === "/" ? "/index.md" : route.replace(/\/$/, "") + ".md";
  const html = fs.readFileSync(full, "utf8");
  const md = htmlToMd(html, SITE + route);
  mdByRoute.set(route, md);
  fs.writeFileSync("out" + mdPath, md);
  if (!html.includes('type="text/markdown"') && html.includes("</head>")) {
    fs.writeFileSync(
      full,
      html.replace("</head>", '<link rel="alternate" type="text/markdown" href="' + mdPath + '"></head>')
    );
  }
  agentCount++;
}
console.log("postbuild: agent markdown written for " + agentCount + " pages");

// llms-full.txt: the full-content companion to llms.txt, per the llms.txt
// convention. llms.txt is the curated index (hand-kept in
// hostinger/public_html/llms.txt); llms-full.txt concatenates the FULL
// markdown of the site's pages, generated from the same page sources the
// per-page .md twins above are built from, so it regenerates on every
// build and never goes stale. Pages are ordered by the sitemap (the
// site's canonical URL list); any exported page missing from the sitemap
// is appended in route order. PHP tool pages are not part of the export;
// their content is represented in llms.txt and the tool docs pages, which
// are included here. Served at /llms-full.txt.
const llmsHeader = [
  "# Leave It to Bum Bum: full site content",
  "",
  "> The full text of leaveittobumbum.com in one file, for AI agents and search engines. For the short curated index, see https://leaveittobumbum.com/llms.txt. Leave It to Bum Bum is a toolbox of small, single-job tools for solopreneurs and small business owners: every tool is free with no limits, and the one paid offer is the 36-hour Promise, one scoped custom tool built on request for a one-time $49. Operated by Leon Tang, Aurora, Ontario, Canada. Contact: hello@leaveittobumbum.com.",
  "",
].join("\n");
const sitemapXml = fs.readFileSync("hostinger/public_html/sitemap.xml", "utf8");
const sitemapRoutes = [...sitemapXml.matchAll(/<loc>([^<]+)<\/loc>/g)].map((m) => {
  try {
    return new URL(m[1].trim()).pathname;
  } catch {
    return null;
  }
});
const orderedRoutes = [];
const seenRoutes = new Set();
for (const r of sitemapRoutes) {
  if (r && mdByRoute.has(r) && !seenRoutes.has(r)) {
    seenRoutes.add(r);
    orderedRoutes.push(r);
  }
}
for (const r of [...mdByRoute.keys()].sort()) {
  if (!seenRoutes.has(r)) {
    seenRoutes.add(r);
    orderedRoutes.push(r);
  }
}
const llmsFull =
  llmsHeader +
  orderedRoutes.map((r) => mdByRoute.get(r).trim()).join("\n\n---\n\n") +
  "\n";
fs.writeFileSync("out/llms-full.txt", llmsFull, "utf8");
console.log(
  "postbuild: llms-full.txt written with " +
    orderedRoutes.length +
    " pages, " +
    llmsFull.length +
    " chars"
);

// Product feed for ChatGPT merchant onboarding (OpenAI Agentic Commerce
// product feed schema, OpenAI format delivered as TSV, which the hosted-URL
// form accepts uncompressed). One row per free tool, generated from the
// same registry the tools index and OG cards use, so the feed stays
// current as tools ship. Served at /feeds/products.tsv.
// Spec: https://developers.openai.com/commerce/specs/file-upload/products
const feedRegistry = JSON.parse(
  fs.readFileSync("hostinger/public_html/tools/registry.json", "utf8")
);
function tsvCell(v) {
  return String(v).replace(/[\t\r\n]+/g, " ").trim();
}
const FEED_COLUMNS = [
  "item_id",
  "title",
  "description",
  "url",
  "brand",
  "seller_name",
  "image_url",
  "availability",
  "price",
  "target_countries",
  "store_country",
  "is_eligible_search",
];
const feedRows = feedRegistry.tools.map((t) =>
  [
    t.key,
    t.name,
    t.description,
    SITE + t.url,
    "Leave It To Bum Bum",
    "Leave It To Bum Bum",
    SITE + "/bum/og/" + t.key + ".jpg",
    "in_stock",
    "0.00 USD",
    '["US"]',
    "CA",
    "true",
  ]
    .map(tsvCell)
    .join("\t")
);
fs.mkdirSync("out/feeds", { recursive: true });
fs.writeFileSync(
  "out/feeds/products.tsv",
  FEED_COLUMNS.join("\t") + "\n" + feedRows.join("\n") + "\n",
  "utf8"
);
console.log("postbuild: product feed written for " + feedRows.length + " tools");
