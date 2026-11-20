import { execFileSync, spawn } from 'node:child_process';
import { existsSync } from 'node:fs';
import net from 'node:net';
import { join } from 'node:path';

function portOpen(port: number): Promise<boolean> {
  return new Promise((resolve) => {
    const s = net.connect(port, '127.0.0.1');
    s.once('connect', () => {
      s.destroy();
      resolve(true);
    });
    s.once('error', () => resolve(false));
  });
}

async function waitForPort(port: number, tries = 50): Promise<boolean> {
  for (let i = 0; i < tries; i++) {
    if (await portOpen(port)) return true;
    await new Promise((r) => setTimeout(r, 200));
  }
  return false;
}

/** Generates media fixtures on first run, and (only when LOCAL_PROXY is
 *  set) starts the local CONNECT shim scripts/local-proxy.mjs. */
export default async function globalSetup() {
  const marker = join(__dirname, 'fixtures', 'video.mp4');
  if (!existsSync(marker)) {
    execFileSync('node', [join(__dirname, 'scripts', 'make-fixtures.mjs')], { stdio: 'inherit' });
  }

  const localProxy = process.env.LOCAL_PROXY || '';
  if (localProxy) {
    const port = Number(new URL(localProxy).port || 18080);
    if (!(await portOpen(port))) {
      const child = spawn('node', [join(__dirname, 'scripts', 'local-proxy.mjs'), String(port)], {
        detached: true,
        stdio: 'ignore',
      });
      child.unref();
      if (!(await waitForPort(port))) {
        throw new Error(`local-proxy shim did not start on port ${port} (see scripts/local-proxy.mjs)`);
      }
    }
  }
}
