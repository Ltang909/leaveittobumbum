import { expect, test } from '@playwright/test';
import { hasTestAccount, login } from '../../helpers';

/**
 * Deep coverage for the form-driven tools that shipped after the
 * original 21 (default weekly tier):
 * - invoice-generator and email-signature-generator are fully
 *   client-side, so these assert the real output (PDF / HTML download,
 *   live preview maths).
 * - sound-human combines a prompt with add-ons entirely in the browser.
 * - cutline and corporate-bum-bum are trackers whose writes go to the
 *   shared staging/production database, so — exactly like the
 *   invoice-chaser spec in tools.spec.ts — they get native-validation
 *   coverage that deliberately files NOTHING (test-data rules, §5).
 * - day-log is accounts-only: guests get the gate, and the signed-in
 *   panel is covered only when the dedicated test account exists.
 */

test.describe('invoice-generator: build an invoice and download the PDF', () => {
  test('line items drive the preview total, and the PDF downloads', async ({ page }) => {
    await page.goto('/tools/invoice-generator/');
    await page.locator('#fromName').fill('E2E Studio');
    await page.locator('#toName').fill('E2E Client');
    const row = page.locator('#itemsBody').locator('.it-desc').first();
    await row.fill('Design work');
    await page.locator('#itemsBody .it-qty').first().fill('2');
    await page.locator('#itemsBody .it-rate').first().fill('50');
    await expect(page.locator('#pvToName')).toContainText('E2E Client');
    await expect(page.locator('#pvTotal')).toContainText('100');
    // A second line item can be added.
    await page.locator('#addItemBtn').click();
    await expect(page.locator('#itemsBody .it-desc')).toHaveCount(2);
    const downloadPromise = page.waitForEvent('download', { timeout: 45_000 });
    await page.locator('#downloadBtn').click();
    const download = await downloadPromise;
    expect(download.suggestedFilename()).toMatch(/\.pdf$/i);
  });
});

test.describe('email-signature-generator: details flow into the preview', () => {
  test('name, title and company render, and the HTML downloads', async ({ page }) => {
    await page.goto('/tools/email-signature-generator/');
    await page.locator('#fName').fill('E2E Person');
    await page.locator('#fTitle').fill('Chief Tester');
    await page.locator('#fCompany').fill('E2E Co');
    await expect(page.locator('#sigPreview')).toContainText('E2E Person');
    await expect(page.locator('#sigPreview')).toContainText('Chief Tester');
    await expect(page.locator('#sigPreview')).toContainText('E2E Co');
    const downloadPromise = page.waitForEvent('download', { timeout: 30_000 });
    await page.locator('#dlBtn').click();
    const download = await downloadPromise;
    expect(download.suggestedFilename()).toMatch(/\.html?$/i);
  });
});

test.describe('sound-human: prompt plus add-ons combine in the browser', () => {
  test('typing a prompt mirrors it into the combined output', async ({ page }) => {
    await page.goto('/tools/sound-human/');
    await page.locator('#promptIn').fill('Write a caption for my bakery.');
    await expect(page.locator('#combinedOut')).toHaveValue(/Write a caption for my bakery\./);
    // Ticking an add-on appends the instructions block.
    await page.locator('#chipRow input[type="checkbox"]').first().check();
    await expect(page.locator('#combinedOut')).toHaveValue(/Writing instructions:/);
    // Clear empties the prompt (the ticked add-ons stay, by design), so
    // the combined output keeps the instructions but loses the prompt.
    await page.locator('#clearBtn').click();
    await expect(page.locator('#promptIn')).toHaveValue('');
    await expect(page.locator('#combinedOut')).not.toHaveValue(/bakery/);
  });
});

test.describe('tracker validation states (nothing is filed)', () => {
  test('cutline: the add form refuses a missing price natively', async ({ page }) => {
    await page.goto('/tools/cutline/');
    await page.locator('#add-form input[name="custom_name"]').fill('E2E Stream');
    await page.locator('#add-form button[type="submit"], #add-form button:not([type])').first().click();
    const missing = await page
      .locator('#add-form input[name="price"]')
      .evaluate((el) => (el as HTMLInputElement).validity.valueMissing);
    expect(missing).toBe(true);
    await expect(page.locator('#sub-list')).not.toContainText('E2E Stream');
  });

  test('corporate-bum-bum: the add form refuses a missing role natively', async ({ page }) => {
    await page.goto('/tools/corporate-bum-bum/');
    await page.locator('#addForm input[name="company"]').fill('E2E Corp');
    await page.locator('#addForm button[type="submit"], #addForm button:not([type])').first().click();
    const missing = await page
      .locator('#addForm input[name="role"]')
      .evaluate((el) => (el as HTMLInputElement).validity.valueMissing);
    expect(missing).toBe(true);
  });
});

test.describe('day-log: accounts-only gate', () => {
  test('a guest gets the sign-in gate, never the filing UI', async ({ page }) => {
    const res = await page.goto('/tools/day-log/');
    expect(res?.status(), '/tools/day-log/ should be 200').toBe(200);
    await expect(page.locator('h1')).toHaveText('Day Log');
    await expect(page.getByText("Day Log lives in your account")).toBeVisible();
    const cta = page.locator('a[href*="/account/?next="]').first();
    await expect(cta).toBeVisible();
    await expect(cta).toHaveAttribute('href', /day-log/);
    // The filing surface is account-side only.
    await expect(page.locator('#logText')).toHaveCount(0);
    await expect(page.locator('#jotText')).toHaveCount(0);
  });

  test('a signed-in user reaches the filing panel', async ({ page }) => {
    test.skip(
      !hasTestAccount,
      'Set E2E_TEST_EMAIL and E2E_TEST_PASSWORD to run signed-in Day Log specs (dedicated test account only).',
    );
    await login(page);
    await page.goto('/tools/day-log/');
    await expect(page.locator('#logText')).toBeVisible({ timeout: 20_000 });
  });
});
