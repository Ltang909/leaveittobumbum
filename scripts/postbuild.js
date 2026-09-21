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
