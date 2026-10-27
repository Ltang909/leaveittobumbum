import { expect, test } from '@playwright/test';
import { TOOLS, desk } from '../../helpers';

/**
 * Public-surface smoke: the pages a first-time visitor (and Google) sees.
 * Read-only — this is the only tier allowed to run against production.
 */

test.describe('public pages', () => {
  test('homepage front desk renders', async ({ page }) => {
    await page.goto('/');
    await expect(page.locator('h1')).toContainText('Leave it to Bum Bum');
    await expect(desk.card(page)).toBeVisible();
    await expect(page.locator('.desk-greeting')).toHaveText('What are we handling today?');
    await expect(page.locator('.desk-hint').first()).toContainText('Psst: type @');
    await expect(desk.box(page)).toBeVisible();
    await expect(desk.box(page)).toHaveAttribute('placeholder', /.+/);
    await expect(desk.doIt(page)).toBeVisible();
    await expect(page.getByRole('button', { name: 'Attach a photo, video, or audio file' })).toBeVisible();
    // Four task chips are drawn under the composer.
    await expect(page.locator('.desk-task-chips .capture-home-chip')).toHaveCount(4);
    await expect(page.locator('.desk-browse')).toHaveAttribute('href', '/tools/');
  });

  test('/tools/ lists all 21 tools', async ({ page }) => {
    await page.goto('/tools/');
    await expect(page.locator('h1')).toContainText('Every tiny tool');
    const cards = page.locator('.tool-grid .tool-card');
    await expect(cards).toHaveCount(TOOLS.length);
    for (const tool of TOOLS) {
      await expect(page.locator('.tool-card', { hasText: tool.name }).first()).toBeVisible();
    }
  });

  test('/team/ carries the crew copy verbatim', async ({ page }) => {
    await page.goto('/team/');
    await expect(page.locator('h1')).toHaveText('Meet the team.');
    const body = page.locator('body');
    await expect(body).toContainText('CEO, CTO, COO, CMO, CRO');
    await expect(body).toContainText('Responsibilities: all of it');
    await expect(body).toContainText('Born Apr 3 2025, talk about a child prodigy.');
    await expect(body).toContainText('Middle Manager');
    await expect(body).toContainText('Born Oct 13 2021, got some experience under her belt!');
    await expect(body).toContainText('Responsibilities: feeder, poop scooper');
    await expect(body).toContainText('Responsibilities: Groomer, Coach');
  });

  test('sitemap lists the blog and not the retired Cat Translator', async ({ request }) => {
    const res = await request.get('/sitemap.xml');
    expect(res.ok()).toBeTruthy();
    const xml = await res.text();
    for (const path of [
      '/blog/',
      '/blog/best-business-account-software/',
      '/blog/best-free-crm-small-business/',
      '/blog/best-small-business-software/',
    ]) {
      expect(xml, `sitemap should contain ${path}`).toContain(`https://leaveittobumbum.com${path}`);
    }
    expect(xml).not.toContain('cat-translator');
  });

  test('retired and diagnostic URLs stay tombstoned (410)', async ({ request }) => {
    for (const path of ['/api/diag-admin.php', '/tools/cat-translator/']) {
      const res = await request.get(path);
      expect(res.status(), `${path} should be 410`).toBe(410);
    }
  });
});
