import { expect, test } from '@playwright/test';
import { TOOLS } from '../../helpers';

/**
 * Per-tool coverage, default tier: every one of the 21 tool pages loads
 * and exposes its primary control. AI/Groq-backed tools additionally get
 * validation-state checks here (no provider call is made); their happy
 * paths live in the gated billable project.
 */

test.describe('all 21 tool pages load with their primary control', () => {
  for (const tool of TOOLS) {
    test(`${tool.name} (${tool.key})`, async ({ page }) => {
      const res = await page.goto(tool.url);
      expect(res?.status(), `${tool.url} should be 200`).toBe(200);
      await expect(page.locator(tool.primary).first()).toBeAttached();
      await expect(page.locator('body')).not.toContainText('Fatal error');
      await expect(page.locator('h1').first()).toBeVisible();
    });
  }
});

test.describe('validation states (no provider spend)', () => {
  test('ghostwriter: generate stays disabled until there is something to work with', async ({ page }) => {
    await page.goto('/tools/ghostwriter/');
    await expect(page.locator('#gwGen')).toBeDisabled();
  });

  test('sop-ify: a too-short dump gets the "give me more" status', async ({ page }) => {
    await page.goto('/tools/sop-ify/');
    await page.locator('#sopDump').fill('too short');
    await page.locator('#sopGo').click();
    await expect(page.locator('#sopStatus')).toHaveText(
      'Give me a little more to work with, at least a few sentences.',
    );
  });

  test('hearsay: running without a brand asks for the brand first', async ({ page }) => {
    await page.goto('/tools/hearsay/');
    await page.locator('#hsGo').click();
    await expect(page.locator('#hsStatus')).toHaveText('Tell me your brand name first.');
  });

  test('gap-scout: analyze stays disabled until a resume and a JD exist', async ({ page }) => {
    await page.goto('/tools/gap-scout/');
    await expect(page.locator('#analyzeBtn')).toBeDisabled();
  });

  test('receipt-reader: process button stays hidden with an empty queue', async ({ page }) => {
    await page.goto('/tools/receipt-reader/');
    await expect(page.locator('#processBtn')).toBeHidden();
  });

  test('invoice-chaser: the add form refuses an empty amount natively', async ({ page }) => {
    await page.goto('/tools/invoice-chaser/');
    await page.locator('#addForm input[name="client_name"]').fill('E2E Nobody');
    await page.locator('#addForm button[type="submit"], #addForm button:not([type])').first().click();
    const missing = await page
      .locator('#addForm input[name="amount"]')
      .evaluate((el) => (el as HTMLInputElement).validity.valueMissing);
    expect(missing).toBe(true);
    // Nothing was filed: the list did not gain our test client.
    await expect(page.locator('#invoiceList')).not.toContainText('E2E Nobody');
  });
});
