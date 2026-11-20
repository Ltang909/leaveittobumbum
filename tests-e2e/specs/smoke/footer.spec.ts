import { test } from '@playwright/test';
import { assertFooter } from '../../helpers';

/**
 * Footer socials: LinkedIn points at the /in/ profile, Pinterest replaced
 * Facebook (Oct 26, 2026), and no facebook.com link survives anywhere in
 * the footer. Checked on BOTH footer implementations: the Next chrome
 * footer (homepage) and the shared PHP footer (tool pages).
 */
test.describe('footer socials', () => {
  test('homepage footer (Next chrome)', async ({ page }) => {
    await page.goto('/');
    await assertFooter(page);
  });

  test('tool page footer (PHP shared footer)', async ({ page }) => {
    await page.goto('/tools/purrsuit/');
    await assertFooter(page);
  });

  test('blog article footer (Next chrome)', async ({ page }) => {
    await page.goto('/blog/best-free-crm-small-business/');
    await assertFooter(page);
  });
});
