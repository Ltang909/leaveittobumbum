import { expect, test } from '@playwright/test';
import { hasTestAccount, login } from '../../helpers';

/**
 * Billing — STAGING ONLY, Stripe TEST mode.
 *
 * The /checkout/ page refuses guests ("Sign in before checkout"), so
 * this spec needs the dedicated test account:
 *   E2E_TEST_EMAIL / E2E_TEST_PASSWORD  (env or GitHub secrets)
 * Without them the spec skips with a clear message. The card is Stripe's
 * test card 4242 4242 4242 4242 — in test mode it completes a TEST
 * subscription; no real payment is ever made. The spec also refuses to
 * run at all if BASE_URL is not the staging host (defence in depth on
 * top of the config, which only defines this project off-production).
 */

const STAGING_HOST = 'staging.leaveittobumbum.com';

test.describe('billing: Helper checkout with the Stripe test card', () => {
  test.beforeEach(async ({ page }) => {
    test.skip(!hasTestAccount, 'Set E2E_TEST_EMAIL and E2E_TEST_PASSWORD to run billing specs.');
    const base = process.env.BASE_URL || `https://${STAGING_HOST}`;
    test.skip(!base.includes(STAGING_HOST), `Billing specs are staging-only (BASE_URL=${base}).`);
    await login(page);
    void page;
  });

  test('pricing -> Helper -> embedded Stripe checkout -> success', async ({ page }) => {
    await page.goto('/pricing/');
    const helperCta = page.getByRole('link', { name: /Choose Helper|Upgrade to Helper/ });
    await expect(helperCta).toBeVisible();
    await helperCta.click();
    await page.waitForURL('**/checkout/?plan=helper**');

    // Stripe embedded checkout mounts inside an iframe in #checkout.
    const checkoutBox = page.locator('#checkout');
    await expect(checkoutBox).toBeVisible();
    const stripeFrame = page.frameLocator('#checkout iframe').first();
    const cardInput = stripeFrame.locator('input[name="number"], input[autocomplete="cc-number"], [data-testid="card-number-input"] input').first();
    await expect(cardInput).toBeVisible({ timeout: 60_000 });
    await cardInput.fill('4242424242424242');

    const expiry = stripeFrame.locator('input[name="expiry"], input[autocomplete="cc-exp"]').first();
    if (await expiry.count()) await expiry.fill('12 / 34');
    const cvc = stripeFrame.locator('input[name="cvc"], input[autocomplete="cc-csc"]').first();
    if (await cvc.count()) await cvc.fill('424');
    const name = stripeFrame.locator('input[name="name"], input[autocomplete="cc-name"]').first();
    if (await name.count()) await name.fill('E2E Test');
    const postal = stripeFrame.locator('input[name="postalCode"], input[autocomplete="postal-code"]').first();
    if (await postal.count()) await postal.fill('L4G 1A1');

    const pay = stripeFrame
      .getByRole('button', { name: /pay|subscribe|start/i })
      .or(page.getByRole('button', { name: /pay|subscribe|start/i }))
      .first();
    await pay.click();

    // Success lands on the checkout completion page (or shows a
    // confirmation state) — never an error banner.
    await page.waitForURL('**/checkout/complete**', { timeout: 90_000 }).catch(() => null);
    const errorText = await page.locator('#error').textContent().catch(() => '');
    expect((errorText || '').trim(), 'checkout should not end in an error').toBe('');
    expect(page.url(), 'expected the completion page after a test payment').toContain('/checkout/complete');
  });
});
