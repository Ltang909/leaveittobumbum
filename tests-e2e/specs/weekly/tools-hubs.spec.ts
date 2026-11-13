import { expect, test } from '@playwright/test';

/**
 * Format hub pages (default weekly tier): the five converter hubs that
 * shipped with the pair pages. A hub's whole job is listing its pair
 * pages, so each spec asserts the H1 and the pair links the hub exists
 * to point at. Read-only — these file nothing and spend nothing.
 */

test.describe('format hubs list their pair pages', () => {
  const hubs = [
    {
      url: '/tools/png-converter/',
      h1: 'PNG Converter',
      links: ['/tools/png-to-jpg/', '/tools/png-to-webp/', '/tools/webp-to-png/', '/tools/jpg-to-png/', '/tools/heic-to-png/'],
    },
    {
      url: '/tools/jpg-converter/',
      h1: 'JPG Converter',
      links: ['/tools/jpg-to-png/', '/tools/jpg-to-webp/', '/tools/jpg-to-pdf/', '/tools/webp-to-jpg/', '/tools/heic-to-jpg/'],
    },
    {
      url: '/tools/webp-converter/',
      h1: 'WebP Converter',
      links: ['/tools/webp-to-png/', '/tools/webp-to-jpg/', '/tools/png-to-webp/', '/tools/jpg-to-webp/'],
    },
    {
      url: '/tools/heic-converter/',
      h1: 'HEIC Converter',
      links: ['/tools/heic-to-jpg/', '/tools/heic-to-png/', '/tools/heic-to-pdf/'],
    },
    {
      url: '/tools/pdf-converter/',
      h1: 'PDF Converter',
      links: ['/tools/pdf-to-jpg/', '/tools/pdf-to-png/', '/tools/pdf-to-image/', '/tools/pdf-merger/', '/tools/pdf-compressor/', '/tools/split-pdf/'],
    },
  ];
  for (const hub of hubs) {
    test(`${hub.url} (${hub.h1})`, async ({ page }) => {
      const res = await page.goto(hub.url);
      expect(res?.status(), `${hub.url} should be 200`).toBe(200);
      await expect(page.locator('h1')).toHaveText(hub.h1);
      for (const link of hub.links) {
        await expect(
          page.locator(`main a[href="${link}"]`).first(),
          `hub should link ${link}`,
        ).toBeAttached();
      }
    });
  }
});
