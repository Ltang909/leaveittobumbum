import { expect, type Page } from '@playwright/test';
import { join } from 'node:path';

export const FIXTURES = join(__dirname, 'fixtures');
export const fixture = (name: string) => join(FIXTURES, name);

/** The 21 live tools, mirroring hostinger/public_html/tools/registry.json.
 *  `primary` is each page's primary control (real id from the page source). */
export type ToolDef = { key: string; name: string; url: string; primary: string };
export const TOOLS: ToolDef[] = [
  { key: 'clips', name: 'Screen Recorder', url: '/tools/clips/', primary: '#startBtn' },
  { key: 'notes', name: 'Voice Notes to Text', url: '/tools/notes/', primary: '#notesRec' },
  { key: 'cutline', name: 'Subscription Tracker', url: '/tools/cutline/', primary: '#add-form' },
  { key: 'purrsuit', name: 'Client Follow-Up Tracker', url: '/tools/purrsuit/', primary: '#addForm' },
  { key: 'corporate-bum-bum', name: 'Job Application Tracker', url: '/tools/corporate-bum-bum/', primary: '#addForm' },
  { key: 'doodle', name: 'Online Sketchpad', url: '/tools/doodle/', primary: '#pad' },
  { key: 'invoice-chaser', name: 'Invoice Reminder', url: '/tools/invoice-chaser/', primary: '#addForm' },
  { key: 'receipt-reader', name: 'Receipt Scanner', url: '/tools/receipt-reader/', primary: '#dropzone' },
  { key: 'poster-to-calendar', name: 'Poster to Calendar', url: '/tools/poster-to-calendar/', primary: '#dropzone' },
  { key: 'meal-scanner', name: 'Meal Scanner', url: '/tools/meal-scanner/', primary: '#dropzone' },
  { key: 'gap-scout', name: 'Resume Gap Checker', url: '/tools/gap-scout/', primary: '#analyzeBtn' },
  { key: 'video-trimmer', name: 'Video Trimmer', url: '/tools/video-trimmer/', primary: '#dropzone' },
  { key: 'image-converter', name: 'Image Converter', url: '/tools/image-converter/', primary: '#dropzone' },
  { key: 'image-cropper', name: 'Image Cropper', url: '/tools/image-cropper/', primary: '#dropzone' },
  { key: 'ghostwriter', name: 'Voice to Content', url: '/tools/ghostwriter/', primary: '#gwPasteText' },
  { key: 'audiogram', name: 'Audiogram Maker', url: '/tools/audiogram/', primary: '#agMp3Drop' },
  { key: 'purr-code', name: 'QR Code Generator', url: '/tools/purr-code/', primary: '#pcUrl' },
  { key: 'sop-ify', name: 'SOP Generator', url: '/tools/sop-ify/', primary: '#sopDump' },
  { key: 'hearsay', name: 'AI Mention Tracker', url: '/tools/hearsay/', primary: '#hsBrand' },
  { key: 'video-converter', name: 'Video Converter', url: '/tools/video-converter/', primary: '#vcDrop' },
  { key: 'video-mp3-overlay', name: 'Video MP3 Overlay', url: '/tools/video-mp3-overlay/', primary: '#vmMp3Drop' },
];

/** Social links every footer (Next chrome AND the PHP tool-page footer)
 *  must carry. Facebook must be gone entirely. */
export const FOOTER_SOCIALS = [
  { label: 'LinkedIn', href: 'https://www.linkedin.com/in/leaveittobumbum/' },
  { label: 'Instagram', href: 'https://www.instagram.com/leaveittobumbum/' },
  { label: 'Pinterest', href: 'https://ca.pinterest.com/0s5nekxyh3lt53n8j9balgylnaznz9/' },
];

export async function assertFooter(page: Page) {
  const footer = page.locator('footer.footer');
  await expect(footer).toBeVisible();
  for (const s of FOOTER_SOCIALS) {
    const link = footer.locator(`.socials a[aria-label="${s.label}"]`);
    await expect(link).toHaveCount(1);
    await expect(link).toHaveAttribute('href', s.href);
  }
  await expect(footer.locator('a[href*="facebook.com"]')).toHaveCount(0);
}

/** The homepage front desk (app/components/capture-bar.tsx). */
export const desk = {
  card: (page: Page) => page.locator('.desk-card'),
  box: (page: Page) => page.getByLabel('Type anything to log it'),
  doIt: (page: Page) => page.getByRole('button', { name: 'Do it!' }),
  pin: (page: Page) => page.locator('.desk-pin'),
  success: (page: Page) => page.locator('.capture-home-success'),
  note: (page: Page) => page.locator('.capture-home-note'),
  picker: (page: Page) => page.locator('.capture-home-picker'),
  fileChip: (page: Page) => page.locator('.capture-home-filechip'),
  fileInput: (page: Page) => page.locator('.desk-card input[type="file"]').first(),
};

/** Make the desk's random per-session chip draw deterministic: seed the
 *  exact sessionStorage value the desk reads before any page script runs. */
export async function seedDeskChips(page: Page, keys: string[]) {
  await page.addInitScript((value) => {
    try {
      sessionStorage.setItem('bb-desk-chips', value);
    } catch {
      /* ignore */
    }
  }, JSON.stringify(keys));
}

/** Pin a tool at the desk through the real @ menu: type the query, click
 *  the named item, and wait for the pin pill. */
export async function pinToolViaMention(page: Page, query: string, toolName: string) {
  const box = desk.box(page);
  await box.click();
  await box.pressSequentially('@' + query, { delay: 30 });
  const item = page.locator('.desk-mention-item', { hasText: toolName });
  await expect(item.first()).toBeVisible();
  await item.first().click();
  await expect(desk.pin(page)).toContainText('@' + toolName);
}

/** Records whether the drop strip's "Bum Bum is on it…" working indicator
 *  ever appears (it retires when the job resolves, so a plain assertion can
 *  miss it). Install BEFORE navigating to the tool page. */
export async function watchWorkingIndicator(page: Page) {
  await page.addInitScript(() => {
    (window as unknown as { __bbSawWorking: boolean }).__bbSawWorking = false;
    const scan = () => {
      if (document.querySelector('.bb-working')) {
        (window as unknown as { __bbSawWorking: boolean }).__bbSawWorking = true;
      }
    };
    const start = () => {
      scan();
      new MutationObserver(scan).observe(document.documentElement, { childList: true, subtree: true });
    };
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
    else start();
  });
}

export async function sawWorkingIndicator(page: Page): Promise<boolean> {
  return page.evaluate(() => !!(window as unknown as { __bbSawWorking?: boolean }).__bbSawWorking);
}

/** Test-account credentials (env only; never committed). */
export const TEST_EMAIL = process.env.E2E_TEST_EMAIL || '';
export const TEST_PASSWORD = process.env.E2E_TEST_PASSWORD || '';
export const hasTestAccount = TEST_EMAIL !== '' && TEST_PASSWORD !== '';

/** Sign in through the real /account/ email form. */
export async function login(page: Page) {
  await page.goto('/account/');
  const form = page.locator('form[data-action="login"]');
  await form.locator('input[name="email"]').fill(TEST_EMAIL);
  await form.locator('input[name="password"]').fill(TEST_PASSWORD);
  await form.getByRole('button', { name: 'Sign in' }).click();
  // Signed-in account page shows the workspace panels.
  await expect(page.locator('#toolbox')).toBeVisible({ timeout: 20_000 });
}
