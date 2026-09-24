<?php
// Bum Bum Gap Scout fetcher. Fetches a job posting URL server-side and returns
// its readable text, so users can analyze postings that block cross-origin
// fetches from the browser. SSRF-guarded: http/https only, no private or
// reserved IP ranges, bounded size and time. Free (no action metering).
require dirname(__DIR__) . '/_bootstrap.php';

requirePost();
$input = body();
requireCsrf($input);
requireUser();

$url = trim((string) ($input['url'] ?? ''));
if ($url === '') jsonResponse(['ok' => false, 'error' => 'Paste a job posting link first.'], 422);
if (mb_strlen($url) > 2000) jsonResponse(['ok' => false, 'error' => 'That link is too long.'], 422);

$parts = parse_url($url);
if (!$parts || !isset($parts['scheme'], $parts['host'])) jsonResponse(['ok' => false, 'error' => 'That does not look like a valid link.'], 422);
$scheme = strtolower($parts['scheme']);
if (!in_array($scheme, ['http', 'https'], true)) jsonResponse(['ok' => false, 'error' => 'Only http and https links are supported.'], 422);
$host = strtolower($parts['host']);
if ($host === 'localhost' || str_starts_with($host, 'localhost.')) jsonResponse(['ok' => false, 'error' => 'That host is not allowed.'], 422);

// Resolve and block private/reserved ranges (SSRF guard).
$ips = @gethostbynamel($host);
if (!$ips) jsonResponse(['ok' => false, 'error' => 'Could not reach that host.'], 422);
foreach ($ips as $ip) {
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
        jsonResponse(['ok' => false, 'error' => 'That host is not allowed.'], 422);
    }
}

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 5,
    CURLOPT_TIMEOUT => 12,
    CURLOPT_CONNECTTIMEOUT => 6,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0 Safari/537.36',
    CURLOPT_HTTPHEADER => ['Accept: text/html,application/xhtml+xml'],
    CURLOPT_ENCODING => '',
]);
// Stream in with a size cap instead of trusting Content-Length.
$buf = '';
$maxBytes = 1500000;
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $chunk) use (&$buf, $maxBytes) {
    $buf .= $chunk;
    return strlen($buf) > $maxBytes ? 0 : strlen($chunk);
});
$html = curl_exec($ch);
$httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$err = curl_error($ch);
curl_close($ch);

if ($html === false || $html === '') {
    jsonResponse(['ok' => false, 'error' => 'Could not fetch that page' . ($err ? ': ' . $err : '.') . ' Try pasting the description text instead.'], 422);
}
if ($httpCode >= 400) {
    jsonResponse(['ok' => false, 'error' => 'That page returned an error (' . $httpCode . '). The site may block automated fetching. Try pasting the description text instead.'], 422);
}
if ($contentType !== '' && !preg_match('/html|text/i', $contentType)) {
    jsonResponse(['ok' => false, 'error' => 'That link is not a web page. Try pasting the description text instead.'], 422);
}

// Extract title and readable text.
$title = '';
if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
    $title = trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}
// Drop scripts, styles, nav-ish boilerplate before extracting text.
$clean = preg_replace('/<(script|style|noscript|header|footer|nav)[^>]*>.*?<\/\1>/is', ' ', $html);
$text = html_entity_decode(strip_tags($clean), ENT_QUOTES | ENT_HTML5, 'UTF-8');
$text = preg_replace('/[ \t\x{00A0}]+/u', ' ', $text);
$text = preg_replace('/\n{3,}/', "\n\n", $text);
$text = trim($text);
if (mb_strlen($text) < 200) {
    jsonResponse(['ok' => false, 'error' => 'That page had almost no readable text (it may need JavaScript to load). Try pasting the description text instead.'], 422);
}
if (mb_strlen($text) > 60000) $text = mb_substr($text, 0, 60000);

jsonResponse(['ok' => true, 'title' => mb_substr($title, 0, 160), 'text' => $text]);
