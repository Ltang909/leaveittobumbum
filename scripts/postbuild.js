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
for (const { full, rel } of agentPages) {
  const route = routeFor(rel);
  if (!route) continue;
  const mdPath = route === "/" ? "/index.md" : route.replace(/\/$/, "") + ".md";
  const html = fs.readFileSync(full, "utf8");
  fs.writeFileSync("out" + mdPath, htmlToMd(html, SITE + route));
  if (!html.includes('type="text/markdown"') && html.includes("</head>")) {
    fs.writeFileSync(
      full,
      html.replace("</head>", '<link rel="alternate" type="text/markdown" href="' + mdPath + '"></head>')
    );
  }
  agentCount++;
}
console.log("postbuild: agent markdown written for " + agentCount + " pages");
