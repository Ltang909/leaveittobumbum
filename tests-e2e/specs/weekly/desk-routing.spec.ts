import { expect, test } from '@playwright/test';
import { desk, fixture, pinToolViaMention, seedDeskChips } from '../../helpers';

/**
 * Front-desk routing (homepage capture bar -> /api/capture.php).
 * Runs against STAGING only — these tests file real (tiny) records as a
 * guest, which is the small guest-filing set the data rules allow.
 * The router is an LLM for unpinned text, so assertions target the
 * user-visible outcome (success line / picker / message), never internals.
 */

test.describe('front desk routing', () => {
  test('unpinned subscription text files to Subscription Tracker', async ({ page }) => {
    await page.goto('/');
    await desk.box(page).fill('Netflix $15.99 a month');
    await desk.doIt(page).click();
    await expect(desk.success(page)).toBeVisible({ timeout: 45_000 });
    await expect(desk.success(page)).toContainText('Netflix');
    await expect(desk.success(page)).toContainText('Subscription Tracker');
  });

  test('keyword route: "add music to my video" points at Video MP3 Overlay', async ({ page }) => {
    await page.goto('/');
    await desk.box(page).fill('add music to my video');
    await desk.doIt(page).click();
    const open = page.getByRole('link', { name: /Open Video MP3 Overlay/ });
    await expect(open).toBeVisible({ timeout: 30_000 });
    await expect(open).toHaveAttribute('href', '/tools/video-mp3-overlay/');
    await open.click();
    await page.waitForURL('**/tools/video-mp3-overlay/**');
    await expect(page.locator('#vmMp3Drop')).toBeAttached();
  });

  test('@ pin is absolute: pinned Invoice Reminder files the invoice', async ({ page }) => {
    await page.goto('/');
    await pinToolViaMention(page, 'inv', 'Invoice Reminder');
    await expect(desk.box(page)).toHaveAttribute('placeholder', 'e.g. hud.io, $20.00, due in 2 weeks');
    await desk.box(page).fill('log an invoice due in 2 weeks for hud.io $20.00');
    await desk.doIt(page).click();
    await expect(desk.success(page)).toBeVisible({ timeout: 45_000 });
    await expect(desk.success(page)).toContainText('Invoice Reminder');
    await expect(desk.success(page)).toContainText('hud.io');
  });

  test('gibberish gets the Hmm picker with an Other chip', async ({ page }) => {
    await page.goto('/');
    await desk.box(page).fill('zxqv jibberwock snizzlefrax quondle');
    await desk.doIt(page).click();
    await expect(desk.picker(page)).toBeVisible({ timeout: 45_000 });
    await expect(desk.picker(page)).toContainText('Hmm, where should this go?');
    await expect(desk.picker(page).getByRole('button', { name: 'Other' })).toBeVisible();
    await expect(desk.picker(page).getByRole('button', { name: 'Subscription Tracker' })).toBeVisible();
  });

  test('staged photo (unpinned) asks for a destination and routes nowhere', async ({ page }) => {
    await page.goto('/');
    await desk.fileInput(page).setInputFiles(fixture('tiny.png'));
    // The photo stages in the composer with a preview chip and the
    // guidance line naming the three photo tools by display name.
    await expect(desk.fileChip(page)).toContainText('tiny.png');
    await expect(page.locator('.desk-hint', { hasText: 'Photo ready.' })).toContainText(
      '@Receipt Reader turns it into a spreadsheet, @Poster to Calendar pulls out the events, @Meal Scanner reads the plate',
    );
    await expect(desk.box(page)).toHaveAttribute(
      'placeholder',
      'Photo ready. @Receipt Reader, @Poster to Calendar, or @Meal Scanner…',
    );
    // Submitting without a pin teaches the @ pick; nothing navigates.
    await desk.doIt(page).click();
    await expect(desk.note(page)).toContainText('That photo needs a destination first');
    await expect(desk.note(page)).toContainText('Receipt Reader');
    expect(page.url()).not.toContain('/tools/');
    await expect(desk.fileChip(page)).toContainText('tiny.png');
  });

  test('a task chip pins its tool', async ({ page }) => {
    // Deterministic chip draw: the desk reads this sessionStorage seed.
    await seedDeskChips(page, ['cutline', 'invoice-chaser', 'purr-code', 'doodle']);
    await page.goto('/');
    await page.locator('.desk-task-chips').getByRole('button', { name: 'Track a subscription' }).click();
    await expect(desk.pin(page)).toContainText('@Subscription Tracker');
    await expect(desk.box(page)).toHaveAttribute('placeholder', 'e.g. Netflix, $15.99, monthly');
  });

  test('@ menu opens, fuzzy-matches, and pins', async ({ page }) => {
    await page.goto('/');
    const box = desk.box(page);
    await box.click();
    await box.pressSequentially('@rec', { delay: 30 });
    const menu = page.locator('.desk-mention-list');
    await expect(menu).toBeVisible();
    await expect(menu.locator('.desk-mention-item').first()).toContainText('Receipt Scanner');
    await page.keyboard.press('Enter');
    await expect(desk.pin(page)).toContainText('@Receipt Scanner');
    await expect(box).toHaveAttribute('placeholder', 'e.g. Starbucks, $6.40');
  });
});
