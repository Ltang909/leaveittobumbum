import { expect, test } from '@playwright/test';
import { fixture } from '../../helpers';

/**
 * Video tools run ffmpeg.wasm in the browser: real, but slow and heavy.
 * These carry the @slow tag — the weekly workflow includes them, quick
 * local runs can skip them with --grep-invert @slow.
 */

test.describe('video tools @slow', () => {
  test('video-trimmer: trim the tiny clip end to end', async ({ page }) => {
    test.slow();
    await page.goto('/tools/video-trimmer/');
    await page.locator('#fileInput').setInputFiles(fixture('video.mp4'));
    await expect(page.locator('#trimPanel')).toBeVisible({ timeout: 30_000 });
    await page.locator('#trimBtn').click();
    await expect(page.locator('#resultCard')).toBeVisible({ timeout: 240_000 });
    await expect(page.locator('#dlLink')).toBeVisible();
  });

  test('video-converter: MP4 to MP4 round trip', async ({ page }) => {
    test.slow();
    await page.goto('/tools/video-converter/');
    await page.locator('#vcFile').setInputFiles(fixture('video.mp4'));
    await expect(page.locator('#vcToFmt')).toBeVisible({ timeout: 30_000 });
    await page.locator('#vcToFmt').click();
    await expect(page.locator('#vcConvert')).toBeVisible({ timeout: 30_000 });
    await page.locator('#vcConvert').click();
    // The result panel unhides the moment Convert is clicked (it hosts
    // the progress bar), so it proves nothing on its own. The real
    // end state: the status line announces the finished MP4 and the
    // download anchor unhides, named for the converted file. The whole
    // pipeline (engine load, encode, metering call) gets the slow
    // tier's full window.
    await expect(page.locator('#vcResult')).toBeVisible({ timeout: 240_000 });
    await expect(page.locator('#vcProgText')).toHaveText('Your MP4 is ready.', {
      timeout: 240_000,
    });
    await expect(page.locator('#vcDl')).toBeVisible();
    await expect(page.locator('#vcDl')).toContainText('Download MP4');
    await expect(page.locator('#vcDl')).toHaveAttribute('download', /converted\.mp4$/);
  });

  test('video-mp3-overlay: place a snippet and render', async ({ page }) => {
    test.slow();
    await page.goto('/tools/video-mp3-overlay/');
    // Step 1: the video and the MP3, then the Continue gate.
    await page.locator('#vmVideoFile').setInputFiles(fixture('video.mp4'));
    await page.locator('#vmMp3File').setInputFiles(fixture('speech.mp3'));
    await expect(page.locator('#vmMp3Info')).not.toHaveText('', { timeout: 30_000 });
    await expect(page.locator('#vmToArrange')).toBeEnabled({ timeout: 30_000 });
    await page.locator('#vmToArrange').click();
    await expect(page.locator('#vmArrange')).toBeVisible();
    // Step 2: a one-second snippet at the start of the video.
    await page.locator('#vmInText').fill('0:00.0');
    await page.locator('#vmOutText').fill('0:01.0');
    await page.locator('#vmAtText').fill('0:00.0');
    await page.locator('#vmAdd').click();
    await expect(page.locator('#vmList')).not.toHaveText('', { timeout: 30_000 });
    await page.locator('#vmRender').click();
    await expect(page.locator('#vmDl')).toBeVisible({ timeout: 300_000 });
  });
});
