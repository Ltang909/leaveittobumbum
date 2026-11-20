#!/usr/bin/env node
/**
 * Tiny local CONNECT shim for sandboxed VMs whose egress proxy rejects
 * Chromium's CONNECT conversation (net::ERR_TUNNEL_CONNECTION_FAILED)
 * while accepting curl/Node. Chromium talks plain CONNECT to
 * 127.0.0.1:<port>; this shim performs the upstream CONNECT (with the
 * credentials from https_proxy, when present) and then pipes bytes, so
 * TLS stays end-to-end.
 *
 * Usage:  node scripts/local-proxy.mjs [port]     (default 18080)
 * Then:   LOCAL_PROXY=http://127.0.0.1:18080 npx playwright test
 * Only needed inside this sandbox; CI runners talk to the internet
 * directly and never use it.
 */
import http from 'node:http';
import net from 'node:net';

const port = Number(process.argv[2] || 18080);
const upstreamRaw = process.env.https_proxy || process.env.HTTPS_PROXY || '';
if (!upstreamRaw) {
  console.error('local-proxy: https_proxy is not set; nothing to shim.');
  process.exit(1);
}
const up = new URL(upstreamRaw);
const upHost = up.hostname;
const upPort = Number(up.port || 3128);
const auth =
  up.username || up.password
    ? 'Proxy-Authorization: Basic ' +
      Buffer.from(`${decodeURIComponent(up.username)}:${decodeURIComponent(up.password)}`).toString('base64') +
      '\r\n'
    : '';

const server = http.createServer();
server.on('connect', (clientReq, clientSocket, head) => {
  const target = clientReq.url || '';
  const upstream = net.connect(upPort, upHost, () => {
    upstream.write(`CONNECT ${target} HTTP/1.1\r\nHost: ${target}\r\n${auth}\r\n`);
  });
  let buf = Buffer.alloc(0);
  const onData = (chunk) => {
    buf = Buffer.concat([buf, chunk]);
    const idx = buf.indexOf('\r\n\r\n');
    if (idx === -1) return;
    const header = buf.slice(0, idx).toString('latin1');
    const rest = buf.slice(idx + 4);
    upstream.removeListener('data', onData);
    if (!/^HTTP\/1\.1 200/.test(header)) {
      clientSocket.end('HTTP/1.1 502 Bad Gateway\r\n\r\n');
      upstream.destroy();
      return;
    }
    clientSocket.write('HTTP/1.1 200 Connection Established\r\n\r\n');
    if (head && head.length) upstream.write(head);
    if (rest.length) clientSocket.write(rest);
    upstream.pipe(clientSocket);
    clientSocket.pipe(upstream);
  };
  upstream.on('data', onData);
  upstream.on('error', () => clientSocket.destroy());
  clientSocket.on('error', () => upstream.destroy());
});
server.listen(port, '127.0.0.1', () => {
  console.log(`local-proxy: listening on 127.0.0.1:${port}, upstream ${upHost}:${upPort}`);
});
