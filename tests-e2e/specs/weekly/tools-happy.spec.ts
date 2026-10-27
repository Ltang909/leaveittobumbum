import { expect, test } from '@playwright/test';
import { fixture } from '../../helpers';

/**
 * Deterministic client-side happy paths (default weekly tier):
 * the conversion/crop/QR/doodle tools finish in the browser, so these
 * assert the actual output, not just that the page loaded. Export steps
 * spend one of the guest's free actions, exactly as a real visitor's
 * first run would.
 */

test.describe('client-side tool happy paths', () => {
  test('image-converter: PNG in, JPG download out', async ({ page }) => {
    await page.goto('/tools/image-converter/');
    await page.locator('#fileInput').setInputFiles(fixture('tiny.png'));
    await expect(page.locator('#convertBtn')).toBeVisible();
    await page.locator('#formatSel').selectOption('image/jpeg');
    await page.locator('#convertBtn').click();
    const downloadLink = page.locator('#resultList a[download]');
    await expect(downloadLink.first()).toBeVisible({ timeout: 30_000 });
    await expect(downloadLink.first()).toHaveAttribute('download', /\.jpe?g$/i);
  });

  test('image-cropper: load a photo, drag a crop box, export', async ({ page }) => {
    await page.goto('/tools/image-cropper/');
    await page.locator('#fileInput').setInputFiles(fixture('receipt.png'));
    // Wait until the image is fully decoded and laid out (the stage only
    // accepts a drag once its natural size is known).
    await expect
      .poll(() =>
        page.evaluate(() => {
          const img = document.querySelector('#cropImg') as HTMLImageElement | null;
          return !!img && img.naturalWidth > 0 && img.getBoundingClientRect().height > 100;
        }),
      )
      .toBe(true);
    // Measure AFTER load: the stage grows to the image, so a box taken
    // earlier points at the wrong pixels. And scroll it into view first:
    // page.mouse works in viewport coordinates and the stage sits far
    // down the page.
    await page.locator('#cropStage').scrollIntoViewIfNeeded();
    const stage = await page.locator('#cropImg').boundingBox();
    expect(stage).toBeTruthy();
    if (stage) {
      await page.mouse.move(stage.x + stage.width * 0.2, stage.y + stage.height * 0.2);
      await page.mouse.down();
      await page.mouse.move(stage.x + stage.width * 0.7, stage.y + stage.height * 0.7, { steps: 16 });
      await page.mouse.up();
    }
    await expect(page.locator('#cropSize')).not.toHaveText('');
    await expect(page.locator('#cropBtn')).toBeEnabled();
    await page.locator('#cropBtn').click();
    await expect(page.locator('#resultWrap')).toBeVisible({ timeout: 30_000 });
    await expect(page.locator('#dlBtn')).toHaveAttribute('download', /^cropped-/);
  });

  test('purr-code: the QR canvas actually renders for a URL', async ({ page }) => {
    await page.goto('/tools/purr-code/');
    await page.locator('#pcUrl').fill('https://example.com/e2e-qr');
    // The preview canvas must contain a real QR pattern: many dark pixels.
    await expect
      .poll(
        () =>
          page.evaluate(() => {
            const canvas = document.querySelector('#pcCanvas') as HTMLCanvasElement | null;
            if (!canvas) return 0;
            const ctx = canvas.getContext('2d');
            if (!ctx) return 0;
            const data = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
            let dark = 0;
            for (let i = 0; i < data.length; i += 16) {
              if (data[i] < 90 && data[i + 1] < 90 && data[i + 2] < 90) dark++;
            }
            return dark;
          }),
        { timeout: 20_000 },
      )
      .toBeGreaterThan(500);
  });

  test('doodle: draw a stroke and export a PNG download', async ({ page }) => {
    await page.goto('/tools/doodle/');
    const pad = page.locator('#pad');
    await expect(pad).toBeVisible();
    const box = await pad.boundingBox();
    expect(box).toBeTruthy();
    if (box) {
      await page.mouse.move(box.x + box.width * 0.2, box.y + box.height * 0.3);
      await page.mouse.down();
      await page.mouse.move(box.x + box.width * 0.6, box.y + box.height * 0.6, { steps: 14 });
      await page.mouse.up();
    }
    const downloadPromise = page.waitForEvent('download', { timeout: 30_000 });
    await page.locator('#pngBtn').click();
    const download = await downloadPromise;
    expect(download.suggestedFilename()).toMatch(/\.png$/i);
  });
});
