import { expect, test } from '@playwright/test';
import { desk, fixture, pinToolViaMention, seedDeskChips } from '../../helpers';

/**
 * Front-desk routing (homepage capture bar -> /api/capture.php).
 * Runs against STAGING only — these tests file real (tiny) records as a
 * guest, which is the small guest-filing set the data rules allow.
 *
 * The desk is TOOL-FIRST since Nov 2026 (Leon's call): bare words file
 * nothing. A tool is pinned first (via @, a task chip, or the tool list
 * the desk opens over unpinned words), and only then do words file —
 * natively for the log-style tools, or as a handoff into the tool page.
 * Assertions target the user-visible outcome (success line / lesson
 * note / tool list / navigation), never internals.
 *
 * Submits here press Enter in the composer: Enter is the desk's own
 * submit key (same submit path as the Do it! button), and at the
 * desktop test viewport the hero cat art overlays the Do it! button's
 * click point, so a centre click never lands.
 */

test.describe('front desk routing', () => {
  test('pinned subscription text files to Subscription Tracker', async ({ page }) => {
    await page.goto('/');
    await pinToolViaMention(page, 'sub', 'Subscription Tracker');
    await expect(desk.box(page)).toHaveAttribute('placeholder', 'e.g. Netflix, $15.99, monthly');
    await desk.box(page).fill('Netflix $15.99 a month');
    await desk.box(page).press('Enter');
    await expect(desk.success(page)).toBeVisible({ timeout: 45_000 });
    await expect(desk.success(page)).toContainText('Netflix');
    await expect(desk.success(page)).toContainText('Subscription Tracker');
  });

  test('keyword route: "add music to my video" reaches Video MP3 Overlay via the tool list', async ({
    page,
  }) => {
    await page.goto('/');
    // Real keystrokes: the desk's search/picker state is keystroke-driven.
    await desk.box(page).pressSequentially('add music to my video', { delay: 20 });
    await desk.box(page).press('Enter');
    // Bare words file nothing: the desk says so, and opens the full
    // tool list over the words so the visitor can pick the destination.
    await expect(desk.note(page)).toContainText('Pick the tool first, then give it your words');
    await expect(desk.note(page)).toContainText('Nothing was logged');
    const item = page.locator('.desk-mention-item', { hasText: 'Video MP3 Overlay' });
    await expect(item.first()).toBeVisible({ timeout: 30_000 });
    await item.first().click();
    await expect(desk.pin(page)).toContainText('@Video MP3 Overlay');
    // The words ride along; submitting again opens the pinned tool.
    await desk.box(page).press('Enter');
    await page.waitForURL('**/tools/video-mp3-overlay/**', {
      timeout: 90_000,
      waitUntil: 'domcontentloaded',
    });
    await expect(page.locator('#vmMp3Drop')).toBeAttached();
  });

  test('@ pin is absolute: pinned Invoice Reminder files the invoice', async ({ page }) => {
    await page.goto('/');
    await pinToolViaMention(page, 'inv', 'Invoice Reminder');
    await expect(desk.box(page)).toHaveAttribute('placeholder', 'e.g. hud.io, $20.00, due in 2 weeks');
    await desk.box(page).fill('log an invoice due in 2 weeks for hud.io $20.00');
    await desk.box(page).press('Enter');
    await expect(desk.success(page)).toBeVisible({ timeout: 45_000 });
    await expect(desk.success(page)).toContainText('Invoice Reminder');
    await expect(desk.success(page)).toContainText('hud.io');
  });

  test('unpinned gibberish files nothing: the pick-a-tool lesson and the tool list', async ({
    page,
  }) => {
    await page.goto('/');
    await desk.box(page).pressSequentially('zxqv jibberwock snizzlefrax quondle', { delay: 20 });
    await desk.box(page).press('Enter');
    await expect(desk.note(page)).toContainText('Pick the tool first, then give it your words');
    await expect(desk.note(page)).toContainText('Nothing was logged');
    // The full tool list opens over the words, real tools included.
    const list = page.locator('.desk-mention-list');
    await expect(list).toBeVisible();
    await expect(
      list.locator('.desk-mention-item', { hasText: 'Subscription Tracker' }).first(),
    ).toBeVisible();
    // Nothing filed, nothing navigated.
    await expect(desk.success(page)).toHaveCount(0);
    expect(page.url()).not.toContain('/tools/');
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
    await desk.box(page).press('Enter');
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
