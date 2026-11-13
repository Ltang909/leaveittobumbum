import { expect, test } from '@playwright/test';
import { fixture } from '../../helpers';

/**
 * Deep happy paths for the browser-side PDF tools that shipped after the
 * original 21 (default weekly tier): merger, PDF to Image, compressor,
 * splitter, JPG to PDF, and the PDF to JPG / PDF to PNG pair pages.
 * Everything runs client-side with pdf-lib / pdf.js, so these assert the
 * real output (a download or a result link), not just the page load.
 * Each conversion spends one of the guest's free actions, exactly as a
 * real visitor's first run would.
 */

test.describe('PDF tool happy paths', () => {
  test('pdf-merger: two PDFs in, one merged PDF out', async ({ page }) => {
    await page.goto('/tools/pdf-merger/');
    await expect(page.locator('#mergeBtn')).toBeHidden();
    // Files are added one change event at a time (the picker appends).
    await page.locator('#fileInput').setInputFiles(fixture('resume.pdf'));
    await page.locator('#fileInput').setInputFiles(fixture('pages.pdf'));
    await expect(page.locator('#fileList')).toContainText('resume.pdf');
    await expect(page.locator('#fileList')).toContainText('pages.pdf');
    await expect(page.locator('#mergeBtn')).toBeVisible();
    await page.locator('#mergeBtn').click();
    const link = page.locator('#resultList a[download]').first();
    await expect(link).toBeVisible({ timeout: 45_000 });
    await expect(link).toHaveAttribute('download', /\.pdf$/i);
  });

  test('pdf-to-image: a PDF page comes out as a PNG', async ({ page }) => {
    await page.goto('/tools/pdf-to-image/');
    await page.locator('#fileInput').setInputFiles(fixture('resume.pdf'));
    // The page defaults to JPG output; PNG is a format choice.
    await page.locator('#formatSel').selectOption('image/png');
    await expect(page.locator('#convertBtn')).toBeVisible();
    await page.locator('#convertBtn').click();
    const link = page.locator('#resultList a[download]').first();
    await expect(link).toBeVisible({ timeout: 45_000 });
    await expect(link).toHaveAttribute('download', /\.png$/i);
  });

  test('pdf-compressor: a fat photo PDF actually shrinks', async ({ page }) => {
    await page.goto('/tools/pdf-compressor/');
    await page.locator('#fileInput').setInputFiles(fixture('fat.pdf'));
    await expect(page.locator('#compressBtn')).toBeVisible();
    await page.locator('#compressBtn').click();
    const link = page.locator('#resultList a[download]').first();
    await expect(link).toBeVisible({ timeout: 45_000 });
    await expect(link).toHaveAttribute('download', /compressed\.pdf$/i);
    await expect(page.locator('#resultList')).toContainText('saved');
  });

  test('pdf-compressor: an already-lean PDF says so instead of downloading', async ({ page }) => {
    await page.goto('/tools/pdf-compressor/');
    await page.locator('#fileInput').setInputFiles(fixture('resume.pdf'));
    await page.locator('#compressBtn').click();
    await expect(page.locator('#resultList')).toContainText('already lean', { timeout: 45_000 });
    await expect(page.locator('#resultList a[download]')).toHaveCount(0);
  });

  test('split-pdf: page 1 of a 3-page PDF downloads on its own', async ({ page }) => {
    await page.goto('/tools/split-pdf/');
    await page.locator('#fileInput').setInputFiles(fixture('pages.pdf'));
    await expect(page.locator('#statusLine')).toContainText('Loaded: 3 pages.');
    await expect(page.locator('#rangeHint')).toContainText('numbered 1 to 3');
    await page.locator('#rangeInput').fill('1');
    await expect(page.locator('#splitBtn')).toBeEnabled();
    const downloadPromise = page.waitForEvent('download', { timeout: 45_000 });
    await page.locator('#splitBtn').click();
    const download = await downloadPromise;
    expect(download.suggestedFilename()).toMatch(/\.pdf$/i);
    await expect(page.locator('#statusLine')).toContainText('Done');
  });

  test('jpg-to-pdf: a JPG becomes a PDF download', async ({ page }) => {
    await page.goto('/tools/jpg-to-pdf/');
    await page.locator('#fileInput').setInputFiles(fixture('tiny.jpg'));
    await expect(page.locator('#fileList')).toContainText('tiny.jpg');
    const downloadPromise = page.waitForEvent('download', { timeout: 45_000 });
    await page.locator('#buildBtn').click();
    const download = await downloadPromise;
    expect(download.suggestedFilename()).toMatch(/\.pdf$/i);
  });
});

test.describe('PDF pair pages convert in place', () => {
  const pairs = [
    { url: '/tools/pdf-to-jpg/', h1: 'PDF to JPG Converter', out: /\.jpe?g$/i },
    { url: '/tools/pdf-to-png/', h1: 'PDF to PNG Converter', out: /\.png$/i },
  ];
  for (const pair of pairs) {
    test(`${pair.url} renders a page of the resume PDF`, async ({ page }) => {
      await page.goto(pair.url);
      await expect(page.locator('h1')).toHaveText(pair.h1);
      await page.locator('#fileInput').setInputFiles(fixture('resume.pdf'));
      await expect(page.locator('#convertBtn')).toBeVisible();
      await page.locator('#convertBtn').click();
      const link = page.locator('#resultList a[download]').first();
      await expect(link).toBeVisible({ timeout: 45_000 });
      await expect(link).toHaveAttribute('download', pair.out);
    });
  }
});
