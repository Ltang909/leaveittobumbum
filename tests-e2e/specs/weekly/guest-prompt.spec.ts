import { expect, test } from '@playwright/test';
import { desk, pinToolViaMention } from '../../helpers';

/**
 * The guest account prompt, as the site works now: there isn't one.
 * The delayed prompt (nudge after 2 actions, header actions pill) was
 * removed with the Nov 17, 2026 pricing reset — every tool is free
 * with no limits, the metering UI is gone, and guests are never
 * prompted, fresh or after filings. These specs pin that behaviour:
 * if a nudge, a pill, or an account pitch comes back, they fail.
 * (History: the Oct 26, 2026 F1 divergence note that used to live here
 * was about the old gated prompt never landing on staging; the reset
 * retired the whole mechanism, on both lines.)
 */

test.describe('guest account prompt (removed: free, no limits)', () => {
  test('fresh guest: no nudge on a tool page', async ({ page }) => {
    await page.goto('/tools/meal-scanner/');
    await expect(page.locator('.bb-guest-nudge')).toHaveCount(0);
  });

  test('fresh guest: no header actions pill', async ({ page }) => {
    await page.goto('/tools/meal-scanner/');
    await expect(page.locator('header .usage-pill')).toHaveCount(0);
  });

  test('after 2 completed filings there is still no account prompt', async ({ page }) => {
    await page.goto('/');
    // Filing #1 (pinned subscription; the desk is tool-first now).
    await pinToolViaMention(page, 'sub', 'Subscription Tracker');
    await desk.box(page).fill('Netflix $15.99 a month');
    await desk.box(page).press('Enter');
    await expect(desk.success(page)).toBeVisible({ timeout: 45_000 });
    // Filing #2 (pinned invoice).
    await pinToolViaMention(page, 'inv', 'Invoice Reminder');
    await desk.box(page).fill('log an invoice due in 2 weeks for hud.io $20.00');
    await desk.box(page).press('Enter');
    await expect(desk.success(page)).toContainText('Invoice Reminder', { timeout: 45_000 });

    // Two completed actions in, a tool page still shows no nudge, no
    // pill, and no account pitch anywhere in the body.
    await page.goto('/tools/meal-scanner/');
    await expect(page.locator('.bb-guest-nudge')).toHaveCount(0);
    await expect(page.locator('header .usage-pill')).toHaveCount(0);
    await expect(page.locator('body')).not.toContainText('Create a free account');
  });
});
