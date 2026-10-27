import { expect, test, type Page } from '@playwright/test';
import { desk, fixture, pinToolViaMention, sawWorkingIndicator, watchWorkingIndicator } from '../../helpers';

/**
 * Desk -> tool handoffs (sessionStorage marker + /tools/drop-strip.js).
 * Text drop-ins: the desk marker carries the words and the tool page
 * drops them into its primary field under the full-width banner.
 * File handoffs: the file rides IndexedDB and the tool auto-runs.
 */

async function expectFullWidthBanner(page: Page) {
  const banner = page.locator('.bb-handoff');
  await expect(banner).toBeVisible();
  await expect(banner).toContainText('Bum Bum carried this over from the front desk');
  const widths = await page.evaluate(() => {
    const b = document.querySelector('.bb-handoff');
    const main = document.querySelector('main.shell') || document.querySelector('main');
    return {
      banner: b ? b.getBoundingClientRect().width : 0,
      main: main ? main.getBoundingClientRect().width : 0,
    };
  });
  expect(widths.main).toBeGreaterThan(0);
  // Full-width band: the banner spans (nearly) the whole content column,
  // it is never a cell inside the tool's own grid.
  expect(widths.banner).toBeGreaterThanOrEqual(widths.main * 0.85);
}

const FILLS: { query: string; toolName: string; urlPart: string; field: string; text: string }[] = [
  {
    query: 'qr',
    toolName: 'QR Code Generator',
    urlPart: '/tools/purr-code/',
    field: '#pcUrl',
    text: 'https://example.com/e2e-drop-in',
  },
  {
    query: 'voice',
    toolName: 'Voice to Content',
    urlPart: '/tools/ghostwriter/',
    field: '#gwPasteText',
    text: 'E2E ramble: we launched the thing, people loved the thing, tell everyone about the thing.',
  },
  {
    query: 'sop',
    toolName: 'SOP Generator',
    urlPart: '/tools/sop-ify/',
    field: '#sopDump',
    text: 'E2E process: first check the inbox, then chase anything older than a day, then file the rest.',
  },
  {
    query: 'mention',
    toolName: 'AI Mention Tracker',
    urlPart: '/tools/hearsay/',
    field: '#hsBrand',
    text: 'Acme Widgets E2E',
  },
];

test.describe('desk text drop-ins', () => {
  for (const fill of FILLS) {
    test(`${fill.toolName}: words dropped into ${fill.field} under the banner`, async ({ page }) => {
      await page.goto('/');
      await pinToolViaMention(page, fill.query, fill.toolName);
      await desk.box(page).fill(fill.text);
      await desk.doIt(page).click();
      // domcontentloaded + a generous ceiling: staging tool pages keep
      // long-lived resources trickling (see the file-handoff note below),
      // and through a slow proxy the document lifecycle can take well
      // over 30s even though the page has navigated and rendered. The
      // assertions below poll the DOM, so DCL is all this wait needs.
      await page.waitForURL(`**${fill.urlPart}**`, { timeout: 90_000, waitUntil: 'domcontentloaded' });
      await expectFullWidthBanner(page);
      await expect(page.locator('.bb-handoff-line b')).toContainText('dropped it in for you');
      await expect(page.locator(fill.field)).toHaveValue(fill.text);
    });
  }
});

test.describe('desk file handoff auto-run', () => {
  test('receipt photo handed off from the desk auto-runs in Receipt Scanner', async ({ page }) => {
    await watchWorkingIndicator(page);
    await page.goto('/');
    await pinToolViaMention(page, 'rec', 'Receipt Scanner');
    await desk.fileInput(page).setInputFiles(fixture('receipt.png'));
    // The crossing animation (first handoff of the session) delays the
    // hop by up to ~4s; waitForURL absorbs it without a fixed sleep.
    // domcontentloaded, not load: the tool page keeps long-lived
    // resources trickling, and the assertions below poll the DOM anyway.
    // 90s ceiling: through a slow proxy the lifecycle event itself can
    // take well over 30s even after the page has navigated and rendered.
    await page.waitForURL('**/tools/receipt-reader/**', { timeout: 90_000, waitUntil: 'domcontentloaded' });
    expect(page.url()).toContain('from=capture');
    // Proof the auto-run fired: the strip's working indicator appeared,
    // or the run already resolved into results or an error scene.
    await expect
      .poll(
        async () => {
          const resolved = await page.evaluate(() => ({
            results: !!document.querySelector('#resultsPanel:not(.hidden)'),
            error: !!document.querySelector('.es-scene'),
          }));
          return (await sawWorkingIndicator(page)) || resolved.results || resolved.error;
        },
        { timeout: 90_000, intervals: [1000, 2000, 5000] },
      )
      .toBe(true);
  });
});
