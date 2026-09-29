<?php
// Bum Bum Vibe Check API. URL in, kind roast plus three fixes out.
//
// Fetches the page server-side with SSRF guards (scheme allowlist, no
// userinfo, ports 80/443 only, DNS resolved and pinned with CURLOPT_RESOLVE,
// private and reserved IP ranges rejected, redirects re-validated hop by
// hop), extracts simple on-page signals, then asks Groq for a playful
// verdict, a kind roast, and exactly three fixes ordered by impact. The roast
// is affectionate, never cruel: it roasts the website, never the person.
//
// Auth: signed-in session + CSRF, guests welcome (same as the other tools).
// Server config: 'groq_api_key' (shared with Ghostwriter). Optional
// 'vibecheck_model' overrides the Groq chat model (default openai/gpt-oss-120b).
// Metering: one action covers one finished check. The usage pre-check means
// a failed check never costs the user an action; the idempotency key means
// a retried check never costs two.
//
//   POST /api/tools/vibe-check.php  action=check  url=<url>  idempotencyKey=<uuid>
//   -> { "ok": true, "report": {...}, "usage": {...} }
require dirname(__DIR__) . '/_bootstrap.php';

function vibecheck_clean(string $s): string {
    // Guardrail: no em dashes, ever.
    $s = str_replace(' — ', ', ', $s);
    $s = str_replace('—', ',', $s);
    $s = str_replace('–', '-', $s);
    return $s;
}

function vibecheck_fail(string $msg, int $code = 422): void {
    jsonResponse(['error' => $msg], $code);
}

function vibecheck_idempotency(array $input): string {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) vibecheck_fail('Invalid request identifier.');
    return $key;
}

// Validate a user-supplied URL for safe server-side fetching. Returns
// ['url' => normalized, 'host' => host, 'port' => port, 'ip' => pinnedIp].
function vibecheck_validate_url(string $raw): array {
    $raw = trim($raw);
    if ($raw === '' || strlen($raw) > 2048) vibecheck_fail('Give me a URL to check, like yoursite.com.');
    if (!preg_match('#^https?://#i', $raw)) {
        if (preg_match('#^[a-z0-9]([a-z0-9.-]*[a-z0-9])?(:\d+)?(/.*)?$#i', $raw)) {
            $raw = 'https://' . $raw;
        } else {
            vibecheck_fail('That does not look like a website address. Try something like yoursite.com.');
        }
    }
    $parts = parse_url($raw);
    if (!$parts || empty($parts['host'])) vibecheck_fail('That does not look like a website address. Try something like yoursite.com.');
    $scheme = strtolower($parts['scheme'] ?? '');
    if (!in_array($scheme, ['http', 'https'], true)) vibecheck_fail('I can only visit http and https pages.');
    if (isset($parts['user']) || isset($parts['pass'])) vibecheck_fail('URLs with logins in them are not allowed.');
    $port = (int) ($parts['port'] ?? ($scheme === 'https' ? 443 : 80));
    if (!in_array($port, [80, 443], true)) vibecheck_fail('I can only visit the standard web ports.');
    $host = strtolower($parts['host']);
    if ($host === 'localhost' || substr($host, -6) === '.local' || substr($host, -10) === '.localhost') {
        vibecheck_fail('Nice try. I only visit public websites.');
    }
    // Resolve and pin one IP; reject private and reserved ranges (SSRF guard).
    // IPv4 is preferred to keep the curl pin format simple.
    $v4 = [];
    $v6 = [];
    foreach ((array) @dns_get_record($host, DNS_A + DNS_AAAA) as $rec) {
        if (!empty($rec['ip'])) $v4[] = $rec['ip'];
        elseif (!empty($rec['ipv6'])) $v6[] = $rec['ipv6'];
    }
    if (!$v4 && !$v6) {
        $single = @gethostbyname($host);
        if ($single && $single !== $host) $v4[] = $single;
    }
    $pinned = null;
    foreach (array_merge($v4, $v6) as $ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            $pinned = $ip;
            break;
        }
    }
    if ($pinned === null) vibecheck_fail('I could not reach that address, or it is not a public website.');
    $path = $parts['path'] ?? '/';
    if (($parts['query'] ?? '') !== '') $path .= '?' . $parts['query'];
    return ['url' => $scheme . '://' . $host . ':' . $port . $path, 'host' => $host, 'port' => $port, 'ip' => $pinned];
}

function vibecheck_resolve_location(string $base, string $loc): string {
    $loc = trim($loc);
    if (preg_match('#^https?://#i', $loc)) return $loc;
    $parts = parse_url($base);
    $origin = strtolower($parts['scheme'] ?? 'https') . '://' . strtolower($parts['host'] ?? '');
    if (strpos($loc, '//') === 0) return ($parts['scheme'] ?? 'https') . ':' . $loc;
    if (strpos($loc, '/') === 0) return $origin . $loc;
    $dir = rtrim(dirname($parts['path'] ?? '/'), '/');
    return $origin . ($dir === '' ? '' : $dir) . '/' . $loc;
}

// One HTTP GET with the DNS pin applied. Returns [code, headers, body].
function vibecheck_http_get(array $v): array {
    $maxBytes = 1536 * 1024;
    $body = '';
    $headers = [];
    $ch = curl_init($v['url']);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_TIMEOUT => 12,
        CURLOPT_CONNECTTIMEOUT => 6,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_MAXREDIRS => 0,
        CURLOPT_RESOLVE => [$v['host'] . ':' . $v['port'] . ':' . $v['ip']],
        CURLOPT_USERAGENT => 'BumBumVibeCheck/1.0 (+https://leaveittobumbum.com)',
        CURLOPT_HTTPHEADER => ['Accept: text/html,application/xhtml+xml'],
        CURLOPT_HEADERFUNCTION => function ($ch, $line) use (&$headers) {
            $len = strlen($line);
            $line = trim($line);
            if (strpos($line, ':') !== false) {
                [$k, $val] = explode(':', $line, 2);
                $headers[strtolower(trim($k))] = trim($val);
            }
            return $len;
        },
        CURLOPT_WRITEFUNCTION => function ($ch, $chunk) use (&$body, $maxBytes) {
            $body .= $chunk;
            if (strlen($body) > $maxBytes) return -1;
            return strlen($chunk);
        },
    ]);
    $ok = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $ct = strtolower((string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE));
    curl_close($ch);
    if ($ok === false) return [0, [], ''];
    return [$code, $headers + ['_content_type' => $ct], $body];
}

function vibecheck_fetch(string $rawUrl): array {
    $url = $rawUrl;
    $bytes = 0;
    $started = microtime(true);
    for ($hop = 0; $hop < 4; $hop++) {
        $v = vibecheck_validate_url($url);
        [$code, $headers, $body] = vibecheck_http_get($v);
        if ($code === 0) vibecheck_fail('I could not reach that site. Check the address and try again.');
        if (in_array($code, [301, 302, 303, 307, 308], true) && !empty($headers['location'])) {
            $url = vibecheck_resolve_location($v['url'], $headers['location']);
            continue;
        }
        if ($code === 403 || $code === 401) vibecheck_fail('That site would not let me in (it blocked the visit). Try another URL.');
        if ($code === 404) vibecheck_fail('That page does not exist (404). Check the address and try again.');
        if ($code < 200 || $code >= 300) vibecheck_fail('That site answered with an error. Try again in a bit.');
        $ct = $headers['_content_type'] ?? '';
        if (strpos($ct, 'text/html') === false && strpos($ct, 'application/xhtml') === false) {
            vibecheck_fail('That link did not serve a web page I can read. Try a regular site URL.');
        }
        if (trim($body) === '') vibecheck_fail('That page came back empty. Try another URL.');
        $bytes = strlen($body);
        $ms = (int) round((microtime(true) - $started) * 1000);
        return ['html' => $body, 'url' => $v['url'], 'host' => $v['host'], 'ms' => $ms, 'bytes' => $bytes];
    }
    vibecheck_fail('That URL bounced me around too many redirects. Try the final address directly.');
}

function vibecheck_extract(string $html): array {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    libxml_clear_errors();
    $xp = new DOMXPath($dom);
    $text = function ($q) use ($xp) {
        $n = $xp->query($q)->item(0);
        return $n ? trim(preg_replace('/\s+/', ' ', $n->textContent)) : '';
    };
    $meta = function (string $name) use ($xp) {
        foreach ($xp->query('//meta') as $m) {
            $n = strtolower(trim($m->getAttribute('name')));
            $p = strtolower(trim($m->getAttribute('property')));
            if ($n === $name || $p === $name) return trim($m->getAttribute('content'));
        }
        return '';
    };
    $title = $text('//title');
    $h1s = $xp->query('//h1');
    $h1Count = $h1s->length;
    $firstH1 = $h1Count ? trim(preg_replace('/\s+/', ' ', $h1s->item(0)->textContent)) : '';
    $imgs = $xp->query('//img');
    $imgCount = $imgs->length;
    $noAlt = 0;
    foreach ($imgs as $img) {
        $alt = trim($img->getAttribute('alt'));
        if ($alt === '') $noAlt++;
    }
    $hasViewport = $meta('viewport') !== '';
    $hasFavicon = $xp->query("//link[contains(translate(@rel,'ICON','icon'),'icon')]")->length > 0;
    $ogCount = 0;
    foreach ($xp->query('//meta') as $m) {
        if (strpos(strtolower(trim($m->getAttribute('property'))), 'og:') === 0) $ogCount++;
    }
    foreach (['script', 'style', 'noscript'] as $tag) {
        foreach (iterator_to_array($xp->query('//' . $tag)) as $n) $n->parentNode->removeChild($n);
    }
    $bodyNode = $xp->query('//body')->item(0);
    $bodyText = $bodyNode ? trim(preg_replace('/\s+/', ' ', $bodyNode->textContent)) : '';
    $words = $bodyText === '' ? 0 : count(preg_split('/\s+/', $bodyText));
    return [
        'title' => mb_substr($title, 0, 200),
        'meta_description' => mb_substr($meta('description'), 0, 300),
        'h1_count' => $h1Count,
        'first_h1' => mb_substr($firstH1, 0, 200),
        'img_count' => $imgCount,
        'img_no_alt' => $noAlt,
        'has_viewport' => $hasViewport,
        'has_favicon' => $hasFavicon,
        'og_count' => $ogCount,
        'word_count' => $words,
    ];
}

function vibecheck_call_groq(array $signals, string $host, int $ms, int $bytes): array {
    $key = (string) (config()['groq_api_key'] ?? '');
    if ($key === '' || $key === 'replace_me') {
        vibecheck_fail('Vibe Check is not set up on the server yet.', 503);
    }
    $facts = [];
    $facts[] = 'Site: ' . $host;
    $facts[] = 'Page title: ' . ($signals['title'] !== '' ? '"' . $signals['title'] . '"' : '(missing)');
    $facts[] = 'Meta description: ' . ($signals['meta_description'] !== '' ? '"' . mb_substr($signals['meta_description'], 0, 160) . '"' : '(missing)');
    $facts[] = 'H1 headings: ' . $signals['h1_count'] . ($signals['first_h1'] !== '' ? ', first one reads "' . mb_substr($signals['first_h1'], 0, 120) . '"' : '');
    $facts[] = 'Images: ' . $signals['img_count'] . ', ' . $signals['img_no_alt'] . ' missing alt text';
    $facts[] = 'Mobile viewport tag: ' . ($signals['has_viewport'] ? 'yes' : 'no');
    $facts[] = 'Favicon: ' . ($signals['has_favicon'] ? 'yes' : 'no');
    $facts[] = 'Social preview tags: ' . $signals['og_count'];
    $facts[] = 'Visible words on page: ' . $signals['word_count'];
    $facts[] = 'Page weight: ' . round($bytes / 1024) . ' KB, fetched in ' . $ms . ' ms';
    $system = 'You are Bum Bum, a playful cat executive who roasts small business websites the way a loving friend roasts a questionable haircut: sharp, specific, funny, never cruel. '
        . 'Your audience is Gen Z solopreneurs: lash techs, nail artists, photographers, coaches, freelancers, online sellers. '
        . 'Rules: '
        . '1) Roast the WEBSITE, never the person. No comments about identity, appearance, or anyone\'s worth. Keep it kind. '
        . '2) verdict: a fun 2 to 5 word label for the site\'s overall vibe, like "Certified Cozy" or "Geocities Ghost". '
        . '3) roast: 2 to 4 sentences, specific to the observed facts. Name what you actually saw. Funny and warm, like a friend who wants the site to win. '
        . '4) fixes: exactly 3, ordered by biggest impact first. Each has a short title, one sentence on why it matters, and one or two sentences on how to fix it in plain non-technical language a busy solopreneur can follow. '
        . '5) Never invent facts about the business, its offers, prices, or results. Work only from the observed signals below. If a signal is missing, name what is missing, not what the business does. '
        . '6) NEVER use em dashes or en dashes anywhere in the output. Use commas, colons, or parentheses instead. '
        . 'Output STRICT JSON only, no other text: {"verdict": "...", "roast": "...", "fixes": [{"title": "...", "why": "...", "how": "..."}, {"title": "...", "why": "...", "how": "..."}, {"title": "...", "why": "...", "how": "..."}]}';
    $model = trim((string) (config()['vibecheck_model'] ?? ''));
    if ($model === '') $model = 'openai/gpt-oss-120b';
    $payload = [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => "Here is what I observed on the site:\n" . implode("\n", $facts)],
        ],
        'response_format' => ['type' => 'json_object'],
        'temperature' => 0.85,
        'max_tokens' => 1200,
    ];
    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: ' . 'Bearer ' . $key],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 60,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $raw = curl_exec($ch);
    $err = curl_error($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($raw === false || $err !== '') vibecheck_fail('Vibe Check lost the plot. Try again in a moment.', 502);
    if ($code === 429) vibecheck_fail('Vibe Check is swamped right now. Try again in a minute.', 429);
    if ($code < 200 || $code >= 300) vibecheck_fail('Vibe Check tripped over its own paws. Try again.', 502);
    $data = json_decode((string) $raw, true);
    $content = $data['choices'][0]['message']['content'] ?? '';
    if (is_string($content) && preg_match('/```(?:json)?\s*(\{.*\})\s*```/s', $content, $m)) {
        $content = $m[1];
    }
    $report = is_string($content) ? json_decode($content, true) : null;
    if (!is_array($report)) vibecheck_fail('Vibe Check mumbled. Try again.', 502);
    $verdict = trim((string) ($report['verdict'] ?? ''));
    $roast = trim((string) ($report['roast'] ?? ''));
    $fixes = $report['fixes'] ?? null;
    $valid = $verdict !== '' && mb_strlen($verdict) <= 80
        && $roast !== '' && mb_strlen($roast) <= 800
        && is_array($fixes) && count($fixes) === 3;
    if ($valid) {
        foreach ($fixes as $f) {
            if (!is_array($f) || trim((string) ($f['title'] ?? '')) === ''
                || trim((string) ($f['why'] ?? '')) === '' || trim((string) ($f['how'] ?? '')) === '') {
                $valid = false;
                break;
            }
        }
    }
    if (!$valid) vibecheck_fail('Vibe Check mumbled. Try again.', 502);
    return [
        'verdict' => vibecheck_clean(mb_substr($verdict, 0, 80)),
        'roast' => vibecheck_clean($roast),
        'fixes' => array_values(array_map(function ($f) {
            return [
                'title' => vibecheck_clean(mb_substr(trim((string) $f['title']), 0, 120)),
                'why' => vibecheck_clean(mb_substr(trim((string) $f['why']), 0, 400)),
                'how' => vibecheck_clean(mb_substr(trim((string) $f['how']), 0, 600)),
            ];
        }, array_slice($fixes, 0, 3))),
    ];
}

requirePost();
$input = body();
$action = (string) ($input['action'] ?? 'check');
requireCsrf($input);
$subject = requireSubject();

if ($action !== 'check') vibecheck_fail('Unknown action.', 400);
$url = trim((string) ($input['url'] ?? ''));
$idempotency = vibecheck_idempotency($input);
// Validate the shape before spending any work or actions.
vibecheck_validate_url($url);
// Pre-check so a failed check never costs an action.
$pre = subjectUsage($subject);
if (empty($pre['unlimited']) && ($pre['remaining'] ?? 0) <= 0) {
    limitReachedResponse($subject, $pre);
}
$fetched = vibecheck_fetch($url);
$signals = vibecheck_extract($fetched['html']);
$report = vibecheck_call_groq($signals, $fetched['host'], $fetched['ms'], $fetched['bytes']);
$report['host'] = $fetched['host'];
$count = consumeSubjectAction($subject, 'vibe-check', $idempotency);
if (!empty($count['limit_reached'])) limitReachedResponse($subject, $count);
jsonResponse([
    'ok' => true,
    'report' => $report,
    'usage' => subjectUsage($subject),
    'duplicate' => (bool) ($count['duplicate'] ?? false),
]);
