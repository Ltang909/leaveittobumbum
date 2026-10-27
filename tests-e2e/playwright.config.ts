import { defineConfig, devices } from '@playwright/test';

/**
 * Playwright config for leaveittobumbum.com.
 *
 * Environments:
 *   BASE_URL env var selects the target. Default: staging
 *   (https://staging.leaveittobumbum.com). Production
 *   (https://leaveittobumbum.com) is READ-ONLY: when BASE_URL points at
 *   production, ONLY the `smoke` projects are defined — the weekly and
 *   billable projects (which file data and spend actions) do not exist.
 *
 * Tiers (see docs/e2e-test-plan-2026-10-26.md):
 *   smoke    specs/smoke/    public pages, blog, footers. Safe anywhere.
 *   weekly   specs/weekly/   desk routing, handoffs, per-tool coverage,
 *                            guest prompt. Staging only.
 *   billable specs/billable/ AI/Groq happy paths + Stripe test checkout.
 *                            Defined only when E2E_BILLABLE=1. Staging only.
 */

const PROD_URL = 'https://leaveittobumbum.com';
const baseURL = (process.env.BASE_URL || 'https://staging.leaveittobumbum.com').replace(/\/+$/, '');
const isProd = baseURL === PROD_URL;
const billableEnabled = process.env.E2E_BILLABLE === '1';

// Optional local proxy shim (sandboxed VMs whose egress proxy rejects
// Chromium's CONNECT; see scripts/local-proxy.mjs). Set LOCAL_PROXY to
// e.g. http://127.0.0.1:18080 and global-setup starts the shim. Unset on
// normal machines and in CI, where Chromium reaches the internet itself.
const localProxy = process.env.LOCAL_PROXY || '';

const projects: Parameters<typeof defineConfig>[0]['projects'] = [
  {
    name: 'smoke',
    testDir: './specs/smoke',
    use: { ...devices['Desktop Chrome'] },
  },
  {
    name: 'smoke-mobile',
    testDir: './specs/smoke',
    use: { ...devices['Pixel 7'] },
  },
];

if (!isProd) {
  projects.push({
    name: 'weekly',
    testDir: './specs/weekly',
    use: { ...devices['Desktop Chrome'] },
  });
  if (billableEnabled) {
    projects.push({
      name: 'billable',
      testDir: './specs/billable',
      use: { ...devices['Desktop Chrome'] },
    });
  }
}

export default defineConfig({
  testDir: './specs',
  globalSetup: './global-setup.ts',
  timeout: 60_000,
  expect: { timeout: 10_000 },
  fullyParallel: false,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 1,
  workers: process.env.CI ? 2 : undefined,
  reporter: [
    ['list'],
    ['html', { outputFolder: 'playwright-report', open: 'never' }],
    ['json', { outputFile: 'test-results/results.json' }],
  ],
  use: {
    baseURL,
    // Sandbox-only: the local shim's upstream egress proxy MITMs TLS with
    // a CA Chromium does not trust, which would break CDN subresources
    // (pdf.js, fonts, Stripe.js). Never set without LOCAL_PROXY, and
    // never in CI.
    ...(localProxy ? { proxy: { server: localProxy }, ignoreHTTPSErrors: true } : {}),
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
    video: 'off',
    actionTimeout: 15_000,
    navigationTimeout: 45_000,
  },
  projects,
});
