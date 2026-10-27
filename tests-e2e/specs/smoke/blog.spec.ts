import { expect, test } from '@playwright/test';

/**
 * The three AEO listicles (published Oct 26, 2026). Rules under test:
 *  - 200 with the wide blog layout classes.
 *  - BlogPosting + ItemList JSON-LD present.
 *  - Every competitor PRODUCT link points at the vendor HOMEPAGE (root
 *    URL). Links that are evidence citations (their own text starts with
 *    "Source:"/"Sources:", or they trail a Source anchor inside the same
 *    block) keep their pricing/help targets on purpose.
 *  - One documented exception: the CRM FAQ sentence whose anchor text
 *    literally names "Pipedrive's current pricing page".
 *  - No em dashes or en dashes anywhere in the article prose.
 */

const ARTICLES = [
  '/blog/best-business-account-software/',
  '/blog/best-free-crm-small-business/',
  '/blog/best-small-business-software/',
];

const KNOWN_EXCEPTIONS = [
  { article: '/blog/best-free-crm-small-business/', href: 'https://www.pipedrive.com/en/pricing' },
];

type LinkReport = { href: string; text: string; citation: boolean };

test.describe('blog listicles', () => {
  test('/blog/ index lists the three articles', async ({ page }) => {
    await page.goto('/blog/');
    for (const path of ARTICLES) {
      await expect(page.locator(`a[href="${path}"]`).first()).toBeVisible();
    }
  });

  for (const path of ARTICLES) {
    test(`${path} structure, schema, links, dashes`, async ({ page }) => {
      const res = await page.goto(path);
      expect(res?.status()).toBe(200);
      await expect(page.locator('.prose.blog-wide')).toBeVisible();
      await expect(page.locator('.page-head.blog-head')).toBeVisible();

      // JSON-LD: BlogPosting + ItemList both present.
      const ldTypes = await page.evaluate(() => {
        const types: string[] = [];
        document.querySelectorAll('script[type="application/ld+json"]').forEach((s) => {
          try {
            const data = JSON.parse(s.textContent || '{}');
            const graph = Array.isArray(data['@graph']) ? data['@graph'] : [data];
            for (const node of graph) if (node && node['@type']) types.push(String(node['@type']));
          } catch {
            /* ignore unparsable blocks */
          }
        });
        return types;
      });
      expect(ldTypes).toContain('BlogPosting');
      expect(ldTypes).toContain('ItemList');

      // Competitor-link rule, evaluated in the page so citation clusters
      // are classified by their real paragraph context.
      const links: LinkReport[] = await page.evaluate(() => {
        const root = document.querySelector('.prose.blog-wide');
        if (!root) return [];
        const out: { href: string; text: string; citation: boolean }[] = [];
        root.querySelectorAll('a[href^="http"]').forEach((a) => {
          const href = a.getAttribute('href') || '';
          if (href.includes('leaveittobumbum.com')) return; // internal in disguise
          const text = (a.textContent || '').trim();
          let citation = /^sources?\b/i.test(text);
          if (!citation) {
            // Citation cluster member: a "Source:"/"Sources:" anchor (or
            // the word itself) appears earlier in the same block.
            const block = a.closest('p, li, td, div');
            if (block) {
              const clone = block.cloneNode(true) as HTMLElement;
              const target = Array.from(clone.querySelectorAll('a')).find(
                (n) => (n.getAttribute('href') || '') === href && (n.textContent || '').trim() === text,
              );
              if (target) {
                const before = document.createElement('div');
                for (const node of Array.from(clone.childNodes)) {
                  if (node === target) break;
                  if (node instanceof Node && target instanceof Node && node.contains(target)) {
                    // Split a wrapping element at the anchor.
                    const inner = (node as HTMLElement).cloneNode(true) as HTMLElement;
                    const innerA = inner.querySelector('a');
                    if (innerA) innerA.remove();
                    before.appendChild(inner);
                    break;
                  }
                  before.appendChild(node.cloneNode(true));
                }
                if (/sources?:/i.test(before.textContent || '')) citation = true;
              }
            }
          }
          out.push({ href, text, citation });
        });
        return out;
      });

      expect(links.length, 'article should link out to competitors').toBeGreaterThan(10);
      const exceptions = KNOWN_EXCEPTIONS.filter((e) => e.article === path).map((e) => e.href);
      for (const link of links) {
        if (link.citation || exceptions.includes(link.href)) continue;
        const url = new URL(link.href);
        expect(
          url.pathname === '/' || url.pathname === '',
          `competitor link "${link.text}" -> ${link.href} should point at the vendor homepage`,
        ).toBeTruthy();
      }

      // ItemList URLs mirror the visible links: vendor roots only.
      const itemListUrls = await page.evaluate(() => {
        const urls: string[] = [];
        document.querySelectorAll('script[type="application/ld+json"]').forEach((s) => {
          try {
            const data = JSON.parse(s.textContent || '{}');
            const graph = Array.isArray(data['@graph']) ? data['@graph'] : [data];
            for (const node of graph) {
              if (node && node['@type'] === 'ItemList' && Array.isArray(node.itemListElement)) {
                for (const item of node.itemListElement) {
                  const u = item?.item?.url || item?.url;
                  if (u) urls.push(String(u));
                }
              }
            }
          } catch {
            /* ignore */
          }
        });
        return urls;
      });
      expect(itemListUrls.length).toBeGreaterThanOrEqual(12);
      for (const u of itemListUrls) {
        if (u.includes('leaveittobumbum.com')) continue;
        const url = new URL(u);
        expect(url.pathname === '/' || url.pathname === '', `ItemList URL ${u} should be a homepage`).toBeTruthy();
      }

      // Zero em/en dashes in the article prose.
      const proseText = await page.locator('.prose.blog-wide').innerText();
      expect(proseText).not.toContain('—');
      expect(proseText).not.toContain('–');
    });
  }
});
