import { expect, test } from '@playwright/test';
import { statSync } from 'node:fs';
import { fixture } from '../../helpers';

/**
 * Audiogram renders in the browser: the track plays in real time while
 * MediaRecorder captures it, then ffmpeg.wasm converts to MP4. On
 * 2026-11-14 a 4:07 song timed out in production ("The conversion timed
 * out. Nothing was rendered.") while the only render spec lived in the
 * billable tier, which scheduled runs never enable. This spec keeps a
 * real render covered on the weekly tier: it picks "No captions", so
 * nothing is transcribed, nothing is uploaded, and no AI cost is spent.
 * The fixture is 75 seconds: long enough to exercise the record and
 * conversion path honestly, short enough for a daily suite.
 */

test.describe('audiogram @slow', () => {
  test('renders an MP4 from a 75-second track with no captions', async ({ page }) => {
    test.setTimeout(600_000); // 75s real-time record + wasm conversion
    test.slow();
    await page.goto('/tools/audiogram/');
    await page.locator('#agMp3').setInputFiles(fixture('song-long.mp3'));
    await page.locator('#agImg').setInputFiles(fixture('tiny.png'));
    await expect(page.locator('#agToStyle')).toBeEnabled({ timeout: 30_000 });
    await page.locator('#agToStyle').click();
    // "No captions" keeps this tier free: no transcription call at all.
    await page.locator('.style-card:has(input[value="none"])').click();
    await page.locator('#agTranscribe').click();
    // Same completion signals as the billable spec: the style step hides
    // and the preview clock shows the real track duration.
    await expect(page.locator('#agStyle')).toBeHidden({ timeout: 60_000 });
    await expect
      .poll(() => page.locator('#agTime').textContent(), { timeout: 60_000 })
      .toContain('1:15');
    await page.locator('#agRender').click();
    await expect(page.locator('#agDl')).toBeVisible({ timeout: 420_000 });
    // The download must be a real MP4, not an empty blob.
    const [download] = await Promise.all([
      page.waitForEvent('download', { timeout: 30_000 }),
      page.locator('#agDl').click(),
    ]);
    expect(download.suggestedFilename()).toMatch(/\.mp4$/i);
    const path = await download.path();
    expect(path).toBeTruthy();
    expect(statSync(path!).size).toBeGreaterThan(100_000);
  });
});
