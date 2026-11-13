import { existsSync } from 'node:fs';
import { expect, test } from '@playwright/test';
import { fixture } from '../../helpers';

/**
 * Deep happy paths for the converter pages that shipped after the
 * original 21 (default weekly tier): the five image pair pages riding
 * the Image Converter engine, and the HEIC family (heic-to-jpg plus its
 * PNG and PDF mode pages). All conversions run in the browser; these
 * assert the real output file, not just the page load.
 */

test.describe('image pair pages convert in place', () => {
  const pairs = [
    { url: '/tools/webp-to-png/', h1: 'WebP to PNG Converter', input: 'tiny.webp', out: /\.png$/i },
    { url: '/tools/webp-to-jpg/', h1: 'WebP to JPG Converter', input: 'tiny.webp', out: /\.jpe?g$/i },
    { url: '/tools/png-to-webp/', h1: 'PNG to WebP Converter', input: 'tiny.png', out: /\.webp$/i },
    { url: '/tools/jpg-to-webp/', h1: 'JPG to WebP Converter', input: 'tiny.jpg', out: /\.webp$/i },
    { url: '/tools/jpg-to-png/', h1: 'JPG to PNG Converter', input: 'tiny.jpg', out: /\.png$/i },
  ];
  for (const pair of pairs) {
    test(`${pair.url} converts its fixture`, async ({ page }) => {
      await page.goto(pair.url);
      await expect(page.locator('h1')).toHaveText(pair.h1);
      await page.locator('#fileInput').setInputFiles(fixture(pair.input));
      await expect(page.locator('#convertBtn')).toBeVisible();
      await page.locator('#convertBtn').click();
      const link = page.locator('#resultList a[download]').first();
      await expect(link).toBeVisible({ timeout: 45_000 });
      await expect(link).toHaveAttribute('download', pair.out);
    });
  }
});

const hasHeicFixture = existsSync(fixture('sample.heic'));

test.describe('HEIC family converts a real iPhone-format photo', () => {
  const modes = [
    { url: '/tools/heic-to-jpg/', h1: 'Free HEIC to JPG Converter', out: /\.jpe?g$/i, pdf: false },
    { url: '/tools/heic-to-png/', h1: 'Free HEIC to PNG Converter', out: /\.png$/i, pdf: false },
    { url: '/tools/heic-to-pdf/', h1: 'Free HEIC to PDF Converter', out: /\.pdf$/i, pdf: true },
  ];
  for (const mode of modes) {
    test(`${mode.url} converts sample.heic`, async ({ page }) => {
      test.skip(
        !hasHeicFixture,
        'fixtures/sample.heic missing — fixture generation needs python3 + pillow_heif (see scripts/make-heic.py); validation-state coverage below still runs.',
      );
      await page.goto(mode.url);
      await expect(page.locator('h1')).toHaveText(mode.h1);
      await page.locator('#fileInput').setInputFiles(fixture('sample.heic'));
      await expect(page.locator('#fileList')).toContainText('Done', { timeout: 45_000 });
      if (mode.pdf) {
        // PDF mode delivers through "Download as one PDF", not a
        // per-photo download link.
        const downloadPromise = page.waitForEvent('download', { timeout: 30_000 });
        await page.locator('#downloadAllBtn').click();
        const download = await downloadPromise;
        expect(download.suggestedFilename()).toMatch(mode.out);
      } else {
        const link = page.locator('#fileList a[download]').first();
        await expect(link).toBeVisible();
        await expect(link).toHaveAttribute('download', mode.out);
      }
    });
  }

  test('heic-to-jpg: a PNG upload is skipped with the HEIC-only message', async ({ page }) => {
    await page.goto('/tools/heic-to-jpg/');
    await page.locator('#fileInput').setInputFiles(fixture('tiny.png'));
    await expect(page.locator('#statusLine')).toHaveText(
      '1 file was skipped: only .heic or .heif photos work here.',
    );
    await expect(page.locator('#fileList > *')).toHaveCount(0);
  });
});
