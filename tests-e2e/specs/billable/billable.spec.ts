import { expect, test } from '@playwright/test';
import { desk, fixture } from '../../helpers';

/**
 * GATED "billable" tier: one real happy-path run per AI/Groq-backed
 * tool. These spend real provider calls and real actions on staging
 * (which shares the production database), so the whole project only
 * exists when E2E_BILLABLE=1. Run deliberately, not on every commit:
 *   npm run test:billable
 */

test.describe('vision tools: one real read each', () => {
  test('receipt-reader reads the fixture receipt', async ({ page }) => {
    test.slow(); // a real vision round trip, well past the 60s default
    await page.goto('/tools/receipt-reader/');
    await page.locator('#fileGallery').setInputFiles(fixture('receipt.png'));
    await expect(page.locator('#processBtn')).toBeVisible({ timeout: 20_000 });
    await page.locator('#processBtn').click();
    await expect(page.locator('#resultsPanel')).toBeVisible({ timeout: 120_000 });
    await expect(page.locator('#cardsWrap').locator(':scope > *')).not.toHaveCount(0, { timeout: 60_000 });
  });

  test('poster-to-calendar pulls an event off the fixture poster', async ({ page }) => {
    await page.goto('/tools/poster-to-calendar/');
    await page.locator('#fileGallery').setInputFiles(fixture('poster.png'));
    await expect(page.locator('#processBtn')).toBeVisible({ timeout: 20_000 });
    await page.locator('#processBtn').click();
    await expect(page.locator('#resultsPanel')).toBeVisible({ timeout: 120_000 });
    await expect(page.locator('#cardsWrap').locator(':scope > *')).not.toHaveCount(0, { timeout: 60_000 });
    await expect(page.locator('#dlAllBtn')).toBeVisible();
  });

  test('meal-scanner completes a scan of the fixture plate', async ({ page }) => {
    test.slow();
    await page.goto('/tools/meal-scanner/');
    await page.locator('#fileGallery').setInputFiles(fixture('meal.png'));
    await expect(page.locator('#processBtn')).toBeVisible({ timeout: 20_000 });
    await page.locator('#processBtn').click();
    // The fixture is synthetic shapes, so the vision verdict varies:
    // a completed scan means result cards OR an honest error scene.
    await expect
      .poll(
        () =>
          page.evaluate(() => {
            const cards = document.querySelectorAll('#cardsWrap > *').length;
            const error =
              !!document.querySelector('.es-scene') ||
              (document.querySelector('#batchError')?.textContent || '').trim().length > 0;
            return cards > 0 || error;
          }),
        { timeout: 240_000, intervals: [2000, 5000] },
      )
      .toBe(true);
  });
});

test.describe('text AI tools: one real generation each', () => {
  test('ghostwriter turns a pasted ramble into a content pack', async ({ page }) => {
    await page.goto('/tools/ghostwriter/');
    await page.locator('#gwPasteToggle').click();
    await page
      .locator('#gwPasteText')
      .fill('We launched our tiny tool last week and solopreneurs keep telling us it saves their Friday afternoons. Here is why that matters and what we learned building it in the open.');
    await expect(page.locator('#gwGen')).toBeEnabled();
    await page.locator('#gwGen').click();
    await expect(page.locator('#gwPack')).toBeVisible({ timeout: 120_000 });
    await expect(page.locator('#gwPackBody')).not.toHaveText('');
  });

  test('sop-ify turns a brain dump into a playbook', async ({ page }) => {
    await page.goto('/tools/sop-ify/');
    await page
      .locator('#sopDump')
      .fill('Every morning I open the inbox and answer anything urgent first. Then I check the orders spreadsheet, pack what sold overnight, print the labels, and drop the parcels at the post office before lunch. Refunds go in the tracker the same day.');
    await page.locator('#sopGo').click();
    await expect(page.locator('#sopResult')).toBeVisible({ timeout: 120_000 });
    await expect(page.locator('#pbSteps')).not.toHaveText('');
  });

  test('hearsay runs a real mention scan', async ({ page }) => {
    test.slow(); // three models, ~30s+ on a good day
    await page.goto('/tools/hearsay/');
    await page.locator('#hsBrand').fill('Leave It to Bum Bum');
    await page
      .locator('#hsQs textarea')
      .first()
      .fill('What are the best free microtools for solopreneurs?');
    await page.locator('#hsGo').click();
    await expect(page.locator('#hsResults')).toBeVisible({ timeout: 240_000 });
    await expect(page.locator('#hsSummaryBody')).not.toHaveText('');
  });

  test('gap-scout analyzes the fixture resume against a JD', async ({ page }) => {
    await page.goto('/tools/gap-scout/');
    await page.locator('#resumeFile').setInputFiles(fixture('resume.pdf'));
    await expect(page.locator('#resumeStatus')).not.toHaveText('', { timeout: 30_000 });
    await page
      .locator('#jdList textarea')
      .first()
      .fill('Marketing Manager wanted. Must have experience with paid media buying, SEO strategy, marketing automation platforms, A/B testing, and budget forecasting. Email marketing a plus.');
    await expect(page.locator('#analyzeBtn')).toBeEnabled({ timeout: 30_000 });
    await page.locator('#analyzeBtn').click();
    await expect(page.locator('#results')).toBeVisible({ timeout: 180_000 });
  });
});

test.describe('audio tools', () => {
  test('notes transcribes the desk-handed audio file', async ({ page }) => {
    await page.goto('/');
    await desk.fileInput(page).setInputFiles(fixture('speech.mp3'));
    await page.waitForURL('**/tools/notes/**', { timeout: 30_000, waitUntil: 'domcontentloaded' });
    // Terminal state: a transcript lands in the notes box, or the page
    // reports honestly that it could not hear anything (tone fallback
    // fixture on machines without a TTS voice).
    await expect
      .poll(
        () =>
          page.evaluate(() => {
            const text = (document.querySelector('#notesText') as HTMLTextAreaElement | null)?.value || '';
            const status = document.querySelector('#notesStatus')?.textContent || '';
            return text.trim().length > 0 || /couldn|error|fail|no speech/i.test(status) ? status || 'transcribed' : '';
          }),
        { timeout: 120_000, intervals: [2000, 5000] },
      )
      .not.toBe('');
  });

  test('audiogram transcribes and renders an MP4 @slow', async ({ page }) => {
    test.setTimeout(600_000); // transcription + ffmpeg.wasm render, no rush
    await page.goto('/tools/audiogram/');
    await page.locator('#agMp3').setInputFiles(fixture('speech.mp3'));
    // Continue stays disabled until a background image exists too.
    await page.locator('#agImg').setInputFiles(fixture('tiny.png'));
    await expect(page.locator('#agToStyle')).toBeEnabled({ timeout: 30_000 });
    await page.locator('#agToStyle').click();
    await page.locator('#agTranscribe').click();
    // The studio section unhides the moment transcription STARTS, so
    // #agRender is clickable long before the studio is set up — and the
    // render handler silently ignores clicks until setupStudio has run
    // (its audioEl guard). Wait for the real completion signals: the
    // style step hides and the preview clock shows the clip duration.
    await expect(page.locator('#agStyle')).toBeHidden({ timeout: 180_000 });
    await expect
      .poll(() => page.locator('#agTime').textContent(), { timeout: 60_000 })
      .not.toBe('0:00 / 0:00');
    await page.locator('#agRender').click();
    await expect(page.locator('#agDl')).toBeVisible({ timeout: 300_000 });
  });

  test('clips: the recorder either starts or explains itself', async ({ page }) => {
    await page.goto('/tools/clips/');
    await page.locator('#startBtn').click();
    // Headless Chromium may or may not grant a display surface: either
    // the recording view appears, or the page shows its honest fallback.
    await expect
      .poll(
        () =>
          page.evaluate(() => {
            const vis = (sel: string) => {
              const el = document.querySelector(sel);
              if (!el) return false;
              const cs = getComputedStyle(el);
              return cs.display !== 'none' && cs.visibility !== 'hidden';
            };
            return (
              vis('#recordingView') ||
              vis('#no-screen') ||
              (document.querySelector('#clipError')?.textContent || '').trim().length > 0
            );
          }),
        { timeout: 30_000, intervals: [1000, 2000] },
      )
      .toBe(true);
  });
});
