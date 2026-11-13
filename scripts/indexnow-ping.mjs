#!/usr/bin/env node
/**
 * IndexNow ping: submits every URL in the production sitemap to
 * api.indexnow.org so Bing (and other IndexNow engines) pick up new and
 * changed pages right after a production deploy instead of waiting for a
 * crawl.
 *
 * Key discovery: the key file lives at hostinger/public_html/<key>.txt and
 * is served at https://leaveittobumbum.com/<key>.txt (the IndexNow
 * keyLocation). Set INDEXNOW_KEY to override discovery.
 *
 * Usage: node scripts/indexnow-ping.mjs [--dry-run]
 * Uses Node 18+ built-ins only.
 */
import { readdirSync, existsSync } from "node:fs";
import { dirname, join } from "node:path";
import { fileURLToPath } from "node:url";

const HOST = "leaveittobumbum.com";
const SITEMAP_URL = `https://${HOST}/sitemap.xml`;
const ENDPOINT = "https://api.indexnow.org/indexnow";
const BATCH = 10000; // IndexNow per-request URL limit

const root = join(dirname(fileURLToPath(import.meta.url)), "..");

function discoverKey() {
  if (process.env.INDEXNOW_KEY) return process.env.INDEXNOW_KEY.trim();
  const dir = join(root, "hostinger", "public_html");
  if (!existsSync(dir)) return null;
  const hit = readdirSync(dir).find((f) => /^[a-f0-9]{32}\.txt$/.test(f));
  return hit ? hit.replace(/\.txt$/, "") : null;
}

async function sitemapUrls() {
  const res = await fetch(SITEMAP_URL, { headers: { "user-agent": "bumbum-indexnow-ping/1.0" } });
  if (!res.ok) throw new Error(`sitemap fetch failed: HTTP ${res.status}`);
  const xml = await res.text();
  const urls = [...xml.matchAll(/<loc>([^<]+)<\/loc>/g)].map((m) => m[1].trim());
  return [...new Set(urls)].filter((u) => u.startsWith(`https://${HOST}/`));
}

async function main() {
  const dryRun = process.argv.includes("--dry-run");
  const key = discoverKey();
  if (!key) {
    console.error("IndexNow key not found: no <32-hex>.txt in hostinger/public_html and INDEXNOW_KEY unset.");
    process.exit(1);
  }
  const keyLocation = `https://${HOST}/${key}.txt`;
  const urls = await sitemapUrls();
  console.log(`IndexNow: ${urls.length} sitemap URLs for ${HOST}, key ${key.slice(0, 6)}... keyLocation ${keyLocation}`);
  if (dryRun) {
    console.log("Dry run: nothing submitted.");
    return;
  }
  for (let i = 0; i < urls.length; i += BATCH) {
    const urlList = urls.slice(i, i + BATCH);
    const res = await fetch(ENDPOINT, {
      method: "POST",
      headers: { "content-type": "application/json; charset=utf-8" },
      body: JSON.stringify({ host: HOST, key, keyLocation, urlList }),
    });
    const body = await res.text();
    console.log(`Batch ${i / BATCH + 1}: submitted ${urlList.length} URLs -> HTTP ${res.status}${body ? ` ${body.slice(0, 200)}` : ""}`);
    // 200 = accepted, 202 = accepted but key validation pending. Both are fine.
    if (res.status !== 200 && res.status !== 202) {
      throw new Error(`IndexNow rejected the submission: HTTP ${res.status}`);
    }
  }
  console.log("IndexNow: done.");
}

main().catch((err) => {
  console.error(`IndexNow ping failed: ${err.message}`);
  process.exit(1);
});
