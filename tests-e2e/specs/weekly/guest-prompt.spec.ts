import { expect, test } from '@playwright/test';
import { desk, pinToolViaMention } from '../../helpers';

/**
 * The delayed account prompt (shipped to production Oct 20, 2026):
 * guests see NO account UI during their first two actions — the header
 * actions pill is hidden and no nudge renders. After the SECOND completed
 * action, a dismissible nudge appears on tool pages; dismissing silences
 * it (until 3 or fewer actions remain).
 *
 * KNOWN DIVERGENCE (found while building this suite, Oct 26, 2026):
 * the gating lives on `main` (commit bcf1d5a). On `staging` only the two
 * newest tool pages (meal-scanner, poster-to-calendar) carry the gated
 * .bb-guest-nudge markup; the header pill gating in meter.php and the
 * dismiss handler in site-header.php never landed on the staging line.
 * These specs encode the intended behaviour, so the pill and dismiss
 * assertions are expected to FAIL on staging until the lines converge —
 * that failure is the bug report, not a test bug.
 */

test.describe('delayed guest account prompt', () => {
  test('fresh guest: no nudge on a gated tool page', async ({ page }) => {
    await page.goto('/tools/meal-scanner/');
    await expect(page.locator('.bb-guest-nudge')).toHaveCount(0);
  });

  test('fresh guest: header actions pill is hidden before 2 actions', async ({ page }) => {
    await page.goto('/tools/meal-scanner/');
    // The PHP header pill (.usage-pill) must not render for a guest with
    // fewer than 2 completed actions.
    await expect(page.locator('header .usage-pill')).toHaveCount(0);
  });

  test('after 2 completed filings the nudge appears and dismisses', async ({ page }) => {
    await page.goto('/');
    // Filing #1 (LLM-routed subscription).
    await desk.box(page).fill('Netflix $15.99 a month');
    await desk.doIt(page).click();
    await expect(desk.success(page)).toBeVisible({ timeout: 45_000 });
    // Filing #2 (pinned invoice).
    await pinToolViaMention(page, 'inv', 'Invoice Reminder');
    await desk.box(page).fill('log an invoice due in 2 weeks for hud.io $20.00');
    await desk.doIt(page).click();
    await expect(desk.success(page)).toContainText('Invoice Reminder', { timeout: 45_000 });

    // The nudge now renders on the gated tool page, with its dismiss ×.
    await page.goto('/tools/meal-scanner/');
    const nudge = page.locator('.bb-guest-nudge');
    await expect(nudge).toBeVisible();
    await expect(nudge).toContainText('free actions left');
    await expect(nudge).toContainText('Create a free account');

    // Dismissing removes it, and it stays gone on the next page load.
    await page.locator('.bb-guest-nudge-dismiss').click();
    await expect(nudge).toHaveCount(0);
    await page.goto('/tools/poster-to-calendar/');
    await expect(page.locator('.bb-guest-nudge')).toHaveCount(0);
  });
});
