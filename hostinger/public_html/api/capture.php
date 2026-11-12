<?php
declare(strict_types=1);

/**
 * Bum Bum universal capture: the "log anything" router.
 *
 * One box takes text (or a voice transcript) and routes it to the right
 * tool in the whole catalog. Record requests (a note, a receipt, a
 * subscription, a job application, an invoice to chase) are filed into
 * the five record tools. Every other request is a job for one of the
 * doing tools, and the box points at that tool in the suggest shape.
 * The user never picks a tool; Bum Bum does.
 *
 * How it works:
 *   1. Session + CSRF (same as every other tool endpoint). The subject may
 *      be a signed-in user or a guest; destinations meter either, exactly
 *      as if the visitor had used the tool directly.
 *   2. Session rate limit: 30 captures/hour (bounds LLM spend).
 *   3. Keyword fast path for obvious receipts ("Starbucks $6.40"): no LLM.
 *   3b. Task-intent fast path ("convert mp4 to wav", "crop a picture"):
 *      the box can't run tools, so it points at the right one. No LLM.
 *   3c. Keyword layer (tool-keywords.json, the user's own keyword table):
 *      decisive phrase matches against the whole catalog route without
 *      the router LLM. Native destinations file via the same extraction
 *      path a pin uses; the other tools get the suggest shape so the
 *      client opens them with the words in hand. Weak, tied, or no
 *      matches fall through to the LLM router, which also receives the
 *      keyword layer's top scores as hints.
 *   4. Otherwise a Groq model (default openai/gpt-oss-120b) routes to
 *      any tool in the catalog and extracts fields as strict JSON.
 *      Record tools file; doing tools get the suggest shape. A router
 *      failure answers honestly that the desk could not sort the
 *      request; it never files the words as a note.
 *   5. Low confidence (< 0.6) or a forced `tool` mismatch returns options
 *      for the user to pick; nothing is charged.
 *   6. Execution is a server-side loopback POST to the tool's own API with
 *      the user's session cookie, so validation, metering, and storage all
 *      stay in the tool. One capture costs exactly one action: the tool's.
 *
 * Billing: the router itself is free; the tool API consumes its normal
 * single action. Ambiguous inputs cost nothing. File handoffs cost nothing
 * here; the tool meters its own action when the user finishes there.
 *
 *   POST /api/capture.php  {text, csrf, idempotencyKey, tool?}
 *   -> {ok:true, tool, tool_name, summary, url, usage}
 *   -> {ok:true, logged:false, message}          (action request, declined)
 *   -> {ok:true, logged:false, handoff:true, tool_name, tool_url}
 *      (file attached: browser stashes the file in IndexedDB and navigates
 *      to tool_url; the tool page picks it up via /tools/capture-handoff.js)
 *   -> {ok:false, ambiguous:true, options:[{tool,tool_name}]}
 *
 *   POST /api/capture.php  {text, fileName, fileMime, fileSize, csrf, ...}
 *   -> handoff / ask / decline shapes above (file bytes never uploaded)
 */

require __DIR__ . '/_bootstrap.php';

/* ------------------------------------------------------------------ */
/* routing table                                                       */
/* ------------------------------------------------------------------ */

function capture_targets(): array {
    return [
        'notes' => [
            'endpoint' => '/api/tools/notes.php',
            'action' => 'finalize',
            'name' => 'Voice Notes to Text',
            'url' => '/tools/notes/',
        ],
        'receipt-reader' => [
            'endpoint' => '/api/tools/receipt-reader.php',
            'action' => 'save',
            'name' => 'Receipt Scanner',
            'url' => '/tools/receipt-reader/',
        ],
        'corporate-bum-bum' => [
            'endpoint' => '/api/tools/corporate-bum-bum.php',
            'action' => 'add',
            'name' => 'Job Application Tracker',
            'url' => '/tools/corporate-bum-bum/',
        ],
        'invoice-chaser' => [
            'endpoint' => '/api/tools/invoice-chaser.php',
            'action' => 'add',
            'name' => 'Invoice Reminder',
            'url' => '/tools/invoice-chaser/',
        ],
        'cutline' => [
            'endpoint' => '/api/tools/cutline.php',
            'action' => 'add',
            'name' => 'Subscription Tracker',
            'url' => '/tools/cutline/',
        ],
    ];
}

/* ------------------------------------------------------------------ */
/* keyword fast path: obvious receipts skip the LLM entirely           */
/* ------------------------------------------------------------------ */

function capture_fast_path(string $text): ?array {
    $t = trim(preg_replace('/\s+/', ' ', $text));
    // Subscription language always goes to the LLM router (Subscription Tracker), even
    // with a dollar amount present. Receipts are one-time purchases only.
    if (preg_match('/subscri|membership|recurring|\bmonth(ly)?\b|\/\s*mo\b/i', $t)) return null;
    // "Starbucks $6.40" or "$6.40 at Starbucks"
    if (preg_match('/^(.*?)\$\s*([\d,]+(?:\.\d{1,2})?)\s*(.*)$/', $t, $m)) {
        $vendor = trim($m[1] . ' ' . $m[3]);
        $vendor = (string) preg_replace('/^(paid|bought|spent|at|for)\s+/i', '', $vendor);
        $vendor = trim((string) preg_replace('/\s+/', ' ', $vendor));
        $total = (float) str_replace(',', '', $m[2]);
        if ($total > 0 && $total < 1000000) {
            return [
                'tool' => 'receipt-reader',
                'fields' => [
                    'vendor' => mb_substr($vendor !== '' ? $vendor : 'Receipt', 0, 191),
                    'total' => number_format($total, 2, '.', ''),
                    'date' => date('Y-m-d'),
                    'currency' => 'USD',
                ],
                'confidence' => 0.95,
                'via' => 'keyword',
            ];
        }
    }
    return null;
}

/* ------------------------------------------------------------------ */
/* Task intents: the box only files text, it can't run tools. But when the
   intent clearly matches a tool, point the user straight at it instead of
   guessing or declining flat. Runs after the receipt fast path so
   "crop top $30" still files as a receipt.                                  */
function capture_task_suggest(string $text): ?array {    if (preg_match('/\bconvert\b/i', $text)) {
        if (preg_match('/\b(mp4|mov|avi|mkv|webm|m4v|wav|mp3|m4a|flac|ogg|opus|aac|video|audio|song|voice)\b/i', $text)) {
            return ['name' => 'Video Converter', 'url' => '/tools/video-converter/'];
        }
        if (preg_match('/\b(png|jpe?g|webp|gif|avif|heic|svg|bmp|tiff?|image|picture|photo)\b/i', $text)) {
            return ['name' => 'Image Converter', 'url' => '/tools/image-converter/'];
        }
    }
    if (preg_match('/\bcrop\b/i', $text)) {
        return ['name' => 'Image Cropper', 'url' => '/tools/image-cropper/'];
    }
    if (preg_match('/\btrim\b/i', $text) && preg_match('/\bvideo\b|\bmp4\b|\bmov\b/i', $text)) {
        return ['name' => 'Video Trimmer', 'url' => '/tools/video-trimmer/'];
    }
    if (preg_match('/\bqr\b/i', $text)) {
        return ['name' => 'QR Code Generator', 'url' => '/tools/purr-code/'];
    }
    return null;
}

/* File routing: pick a media tool from the file kind + the user's words.
   Returns ['name','url'], ['ask'=>true] when the task is unclear, or null
   when the combination can't be used. The file itself never reaches this
   server; only name/mime/size travel here for routing.                     */
function capture_file_target(string $kind, string $text): ?array {
    if ($kind === 'video') {
        if (preg_match('/\btrim\b|\bcut\b|\bsnip\b/i', $text)) {
            return ['name' => 'Video Trimmer', 'url' => '/tools/video-trimmer/'];
        }
        if (preg_match('/\bconvert\b/i', $text)) {
            return ['name' => 'Video Converter', 'url' => '/tools/video-converter/'];
        }
        return ['ask' => true];
    }
    if (preg_match('/\bcrop\b/i', $text)) {
        return ['name' => 'Image Cropper', 'url' => '/tools/image-cropper/'];
    }
    if (preg_match('/\bconvert\b/i', $text)) {
        return ['name' => 'Image Converter', 'url' => '/tools/image-converter/'];
    }
    return ['ask' => true];
}

/* ------------------------------------------------------------------ */
/* Keyword layer: decisive matches against the user's own keyword      */
/* table (tool-keywords.json, synced from his Notion page "Fuzzy match  */
/* against tools") route unpinned text without the router LLM. Runs     */
/* after the receipt fast path and the task-intent suggest, before the  */
/* LLM router. Anything less than decisive falls through untouched, so  */
/* the worst case is exactly today's behaviour. If the table file is    */
/* missing or unparseable the layer silently no-ops.                    */
/* ------------------------------------------------------------------ */

/* Destinations the box cannot file into itself: it points at these,     */
/* and the client opens them with the user's words in hand (the same     */
/* suggest shape the task-intent path uses). Names and URLs mirror       */
/* tools/registry.json.                                                 */
function capture_nav_destinations(): array {
    return [
        'clips' => ['name' => 'Screen Recorder', 'url' => '/tools/clips/'],
        'purrsuit' => ['name' => 'Client Follow-Up Tracker', 'url' => '/tools/purrsuit/'],
        'doodle' => ['name' => 'Online Sketchpad', 'url' => '/tools/doodle/'],
        'poster-to-calendar' => ['name' => 'Poster to Calendar', 'url' => '/tools/poster-to-calendar/'],
        'meal-scanner' => ['name' => 'Meal Scanner', 'url' => '/tools/meal-scanner/'],
        'gap-scout' => ['name' => 'Resume Gap Checker', 'url' => '/tools/gap-scout/'],
        'video-trimmer' => ['name' => 'Video Trimmer', 'url' => '/tools/video-trimmer/'],
        'image-converter' => ['name' => 'Image Converter', 'url' => '/tools/image-converter/'],
        'image-cropper' => ['name' => 'Image Cropper', 'url' => '/tools/image-cropper/'],
        'ghostwriter' => ['name' => 'Voice to Content', 'url' => '/tools/ghostwriter/'],
        'audiogram' => ['name' => 'Audiogram Maker', 'url' => '/tools/audiogram/'],
        'purr-code' => ['name' => 'QR Code Generator', 'url' => '/tools/purr-code/'],
        'sop-ify' => ['name' => 'SOP Generator', 'url' => '/tools/sop-ify/'],
        'hearsay' => ['name' => 'AI Mention Tracker', 'url' => '/tools/hearsay/'],
        'video-converter' => ['name' => 'Video Converter', 'url' => '/tools/video-converter/'],
        'video-mp3-overlay' => ['name' => 'Video MP3 Overlay', 'url' => '/tools/video-mp3-overlay/'],
        'day-log' => ['name' => 'Day Log', 'url' => '/tools/day-log/'],
        'email-signature-generator' => ['name' => 'Email Signature Maker', 'url' => '/tools/email-signature-generator/'],
        'pdf-merger' => ['name' => 'PDF Merger', 'url' => '/tools/pdf-merger/'],
        'pdf-to-image' => ['name' => 'PDF to Image', 'url' => '/tools/pdf-to-image/'],
        'pdf-compressor' => ['name' => 'PDF Compressor', 'url' => '/tools/pdf-compressor/'],
        'sound-human' => ['name' => 'Sound Human', 'url' => '/tools/sound-human/'],
        'heic-to-jpg' => ['name' => 'HEIC to JPG', 'url' => '/tools/heic-to-jpg/'],
        'invoice-generator' => ['name' => 'Invoice Generator', 'url' => '/tools/invoice-generator/'],
        'jpg-to-pdf' => ['name' => 'JPG to PDF', 'url' => '/tools/jpg-to-pdf/'],
        'split-pdf' => ['name' => 'Split PDF', 'url' => '/tools/split-pdf/'],
        'png-to-jpg' => ['name' => 'PNG to JPG Converter', 'url' => '/tools/png-to-jpg/'],
    ];
}

/* Normalize for matching: lowercase, punctuation and whitespace         */
/* collapse to single spaces, split into words. A trailing plural "s"    */
/* folds on words longer than 3 chars (never "...ss"), applied to BOTH   */
/* the table and the input, so "invoice" matches "invoices" while        */
/* identical words always still match identically.                       */
function capture_kw_tokens(string $s): array {
    $s = mb_strtolower($s, 'UTF-8');
    $s = (string) preg_replace('/[^a-z0-9]+/', ' ', $s);
    $out = [];
    foreach (explode(' ', $s) as $w) {
        if ($w === '') continue;
        if (strlen($w) > 3 && substr($w, -1) === 's' && substr($w, -2) !== 'ss') {
            $w = substr($w, 0, -1);
        }
        $out[] = $w;
    }
    return $out;
}

/* Load and pre-tokenize the keyword table once per request. Each entry: */
/* tokens + points + whether the hit counts as strong evidence. A        */
/* multi-word entry is a phrase: 3 points, strong. A single-word entry   */
/* is 2 points and strong, except the Subscription Tracker's brand list, */
/* whose single words are 1 point and weak (they can pad a score but     */
/* can never make one decisive on their own). Unknown tool keys in the   */
/* file are ignored. Returns null when the table is unavailable.         */
function capture_kw_table(): ?array {
    static $table = false;
    if ($table !== false) return $table;
    $table = null;
    $raw = @file_get_contents(__DIR__ . '/tool-keywords.json');
    if ($raw === false) return null;
    $data = json_decode($raw, true);
    if (!is_array($data)) return null;
    $known = capture_targets() + capture_nav_destinations();
    $out = [];
    foreach ($data as $tool => $list) {
        if (!is_string($tool) || !isset($known[$tool]) || !is_array($list)) continue;
        $entries = [];
        foreach ($list as $kw) {
            if (!is_string($kw)) continue;
            $tokens = capture_kw_tokens($kw);
            if ($tokens === []) continue;
            if (count($tokens) === 1) {
                $weak = $tool === 'cutline';
                $entries[] = ['tokens' => $tokens, 'points' => $weak ? 1 : 2, 'strong' => !$weak];
            } else {
                $entries[] = ['tokens' => $tokens, 'points' => 3, 'strong' => true];
            }
        }
        if ($entries !== []) $out[$tool] = $entries;
    }
    $table = $out === [] ? null : $out;
    return $table;
}

/* Does the phrase appear in the text? Exact contiguous run, or the      */
/* phrase's words in order with at most 2 words between neighbours       */
/* (the desk-style light fuzzy tolerance, kept explainable). The         */
/* recursive matcher backtracks over candidate positions; capture texts  */
/* are short, so this stays cheap.                                       */
function capture_kw_match_from(array $phrase, int $pi, array $text, int $from, int $lastPos): bool {
    if ($pi >= count($phrase)) return true;
    $limit = $pi === 0 ? count($text) - 1 : min(count($text) - 1, $lastPos + 3);
    for ($i = $from; $i <= $limit; $i++) {
        if ($text[$i] === $phrase[$pi] && capture_kw_match_from($phrase, $pi + 1, $text, $i + 1, $i)) {
            return true;
        }
    }
    return false;
}

function capture_kw_phrase_hit(array $phrase, array $text): bool {
    if ($phrase === [] || $text === [] || count($phrase) > count($text)) return false;
    return capture_kw_match_from($phrase, 0, $text, 0, -1);
}

/* Score every tool against the text, then route only on a decisive      */
/* winner: top score >= 3, strictly ahead of the runner-up, and backed   */
/* by at least one strong hit (a phrase or a distinctive single word).   */
/* Returns a route array for a native destination (fields built by the  */
/* same extraction path a pin uses), a 'navigate' marker for one of the */
/* other tools, or null to fall through to the LLM router unchanged.     */
function capture_keyword_route(string $text): ?array {
    $table = capture_kw_table();
    if ($table === null) return null;
    $tokens = capture_kw_tokens($text);
    if ($tokens === []) return null;
    $best = null;
    $bestScore = 0;
    $secondScore = 0;
    $bestStrong = false;
    foreach ($table as $tool => $entries) {
        $score = 0;
        $strong = false;
        $seen = [];
        foreach ($entries as $e) {
            $key = implode(' ', $e['tokens']);
            if (isset($seen[$key])) continue;
            $seen[$key] = true;
            if (capture_kw_phrase_hit($e['tokens'], $tokens)) {
                $score += $e['points'];
                if ($e['strong']) $strong = true;
            }
        }
        if ($score > $bestScore) {
            $secondScore = $bestScore;
            $bestScore = $score;
            $best = $tool;
            $bestStrong = $strong;
        } elseif ($score > $secondScore) {
            $secondScore = $score;
        }
    }
    if ($best === null || $bestScore < 3 || $bestScore <= $secondScore || !$bestStrong) {
        return null;
    }
    if (isset(capture_targets()[$best])) {
        return [
            'tool' => $best,
            'fields' => capture_destination_fields($text, $best),
            'confidence' => 1.0,
            'via' => 'keywords',
        ];
    }
    $nav = capture_nav_destinations();
    if (isset($nav[$best])) {
        return ['navigate' => ['tool' => $best, 'name' => $nav[$best]['name'], 'url' => $nav[$best]['url']]];
    }
    return null;
}

/* ------------------------------------------------------------------ */
/* @ pin support: a pinned destination is ABSOLUTE. The fast path and  */
/* the router's tool choice never run for a pin; the LLM may only      */
/* extract fields for the pinned tool, and if extraction fails the     */
/* raw text is filed to the pinned tool anyway.                        */
/* ------------------------------------------------------------------ */

function capture_parse_amount(string $text): string {
    if (preg_match('/\$\s*([\d,]+(?:\.\d{1,2})?)/', $text, $m)) {
        return str_replace(',', '', $m[1]);
    }
    return '';
}

function capture_parse_due_date(string $text): string {
    $t = strtolower($text);
    if (preg_match('/(\d{4})-(\d{2})-(\d{2})/', $text, $m) && checkdate((int) $m[2], (int) $m[3], (int) $m[1])) {
        return $m[1] . '-' . $m[2] . '-' . $m[3];
    }
    if (preg_match('/\bin\s+(\d+)\s*(day|week|month)s?\b/', $t, $m)) {
        $n = max(1, (int) $m[1]);
        $unit = $m[2] === 'day' ? 'days' : ($m[2] === 'week' ? 'weeks' : 'months');
        return date('Y-m-d', strtotime("+{$n} {$unit}"));
    }
    if (preg_match('/\bin\s+a\s+(week|month)\b/', $t, $m)) {
        return date('Y-m-d', strtotime('+1 ' . $m[1]));
    }
    if (strpos($t, 'tomorrow') !== false) return date('Y-m-d', strtotime('+1 day'));
    $days = ['sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6];
    if (preg_match('/\b(monday|tuesday|wednesday|thursday|friday|saturday|sunday)\b/', $t, $m) && isset($days[$m[1]])) {
        $diff = ($days[$m[1]] - (int) date('w') + 7) % 7;
        if ($diff === 0 && strpos($t, 'next') !== false) $diff = 7;
        return date('Y-m-d', strtotime("+{$diff} days"));
    }
    return '';
}

/* Deterministic field guesses for a pinned tool, used when the LLM is
   unavailable and merged UNDER whatever the LLM extracts. Every field a
   destination strictly requires gets a value here, so a pin can always
   be filed: the raw text itself is the last-resort value.              */
function capture_pin_fallback_fields(string $text, string $tool): array {
    $clean = trim((string) preg_replace('/\s+/', ' ', $text));
    switch ($tool) {
        case 'notes':
            return ['text' => $text];
        case 'receipt-reader': {
            $fast = capture_fast_path($text);
            if ($fast !== null) return $fast['fields'];
            $vendor = trim((string) preg_replace('/\$\s*[\d,]+(?:\.\d{1,2})?/', '', $clean));
            $vendor = trim((string) preg_replace('/^(paid|bought|spent|log|at|for)\s+/i', '', $vendor));
            return [
                'vendor' => mb_substr($vendor !== '' ? $vendor : 'Receipt', 0, 191),
                'total' => capture_parse_amount($text),
                'date' => date('Y-m-d'),
                'currency' => 'USD',
            ];
        }
        case 'invoice-chaser': {
            $client = '';
            if (preg_match('/\bfor\s+([A-Za-z0-9][A-Za-z0-9.\-]*\.[A-Za-z]{2,})/', $text, $m)) {
                $client = $m[1];
            } elseif (preg_match('/\bfor\s+([A-Z][A-Za-z0-9&.\-]*)/', $text, $m)) {
                $client = $m[1];
            }
            if ($client === '') $client = mb_substr($clean, 0, 60);
            $due = capture_parse_due_date($text);
            if ($due === '') $due = date('Y-m-d', strtotime('+7 days'));
            return [
                'client_name' => $client,
                'amount' => capture_parse_amount($text),
                'due_date' => $due,
                'invoice_no' => '',
                'notes' => $clean,
            ];
        }
        case 'corporate-bum-bum': {
            $company = '';
            $role = '';
            if (preg_match('/\bapplied\s+(?:to|at)\s+([^,.;]+)/i', $text, $m)) $company = trim($m[1]);
            if ($company === '' && preg_match('/\bapplication\s+(?:to|at)\s+([^,.;]+)/i', $text, $m)) $company = trim($m[1]);
            if (preg_match('/\bfor\s+the\s+([^,.;]+?)\s+(?:role|position|job)\b/i', $text, $m)) $role = trim($m[1]);
            if ($company === '') $company = mb_substr($clean, 0, 60);
            return [
                'company' => mb_substr($company, 0, 191),
                'role' => mb_substr($role, 0, 191),
            ];
        }
        case 'cutline': {
            $name = (string) preg_replace('/\$\s*[\d,]+(?:\.\d{1,2})?/', '', $clean);
            $name = (string) preg_replace('/\b(subscription|membership|recurring|monthly|per month|\/mo|a month|log|add|track)\b/i', '', $name);
            $name = trim((string) preg_replace('/\s+/', ' ', $name), " \t,.");
            if ($name === '') $name = mb_substr($clean, 0, 60);
            $cadence = 'monthly';
            if (preg_match('/\bweek/i', $text)) $cadence = 'weekly';
            elseif (preg_match('/\b(annual|year)/i', $text)) $cadence = 'annual';
            elseif (preg_match('/\bquarter/i', $text)) $cadence = 'quarterly';
            return [
                'name' => mb_substr($name, 0, 191),
                'price' => capture_parse_amount($text),
                'cadence' => $cadence,
                'category' => 'other',
            ];
        }
    }
    return [];
}

/* LLM field extraction for ONE pinned tool. The destination is already
   chosen by the user; the model only fills in that tool's fields.
   Returns the fields array, or null when the call itself failed.      */
function capture_llm_extract(string $text, string $tool): ?array {
    $key = (string) (config()['groq_api_key'] ?? '');
    if ($key === '' || $key === 'replace_me') return null;
    $specs = [
        'notes' => 'a thought, idea, reminder, or journal entry. fields: {"text"}',
        'receipt-reader' => 'a one-time purchase or expense. fields: {"vendor","total","date","currency"}',
        'corporate-bum-bum' => 'a job application or career move. fields: {"company","role","job_url","location","salary"}',
        'invoice-chaser' => 'an invoice to chase, a client who owes money. fields: {"client_name","amount","due_date","invoice_no","notes"}',
        'cutline' => 'a recurring subscription or membership. fields: {"name","price","cadence","category"}. cadence must be one of: weekly, monthly, quarterly, semiannual, annual. category must be one of: streaming, music, software, cloud_storage, fitness, reading, gaming, food_delivery, other',
    ];
    if (!isset($specs[$tool])) return null;
    $model = trim((string) (config()['capture_model'] ?? ''));
    if ($model === '') $model = 'openai/gpt-oss-20b';
    $system = 'You extract fields from a quick log entry for exactly ONE tool: ' . $tool . ', which is ' . $specs[$tool] . '. '
        . 'The destination is already chosen by the user; never pick a different one and never refuse. '
        . 'Today is ' . date('Y-m-d') . ' (' . date('l') . '). Convert relative dates the user mentioned, like "in 2 weeks" or "Friday", into YYYY-MM-DD. '
        . 'Never invent amounts, names, or dates the user did not mention. Omit unknown optional fields. '
        . 'Amounts are plain numbers with no currency symbol. '
        . 'Output STRICT JSON only: {"fields": {...}}. No em dashes anywhere.';
    $payload = [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => mb_substr($text, 0, 2000)],
        ],
        'response_format' => ['type' => 'json_object'],
        'temperature' => 0.2,
        'max_tokens' => 300,
    ];
    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $key],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $raw = curl_exec($ch);
    $err = curl_error($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($raw === false || $err !== '' || $code < 200 || $code >= 300) return null;
    $data = json_decode((string) $raw, true);
    $content = $data['choices'][0]['message']['content'] ?? '';
    if (is_string($content) && preg_match('/```(?:json)?\s*(\{.*\})\s*```/s', $content, $m)) $content = $m[1];
    $parsed = is_string($content) ? json_decode($content, true) : null;
    if (!is_array($parsed)) return null;
    $fields = $parsed['fields'] ?? null;
    return is_array($fields) ? $fields : null;
}

/* Fields for a destination that is already decided (an @ pin, or a
   decisive keyword match): deterministic guesses merged under whatever
   the LLM extracts for that one tool. If extraction fails outright the
   fallback fields still file; the raw text is the last-resort value.
   Invoice fields are sanitized so a malformed extraction can never
   undo the strictly required client, amount, and due date.            */
function capture_destination_fields(string $text, string $tool): array {
    $fields = capture_pin_fallback_fields($text, $tool);
    $extracted = capture_llm_extract($text, $tool);
    if (is_array($extracted)) {
        foreach ($extracted as $k => $v) {
            if ($v !== '' && $v !== null) $fields[$k] = $v;
        }
    }
    if ($tool === 'notes') {
        // A note is the raw words, never an LLM rewrite of them.
        $fields['text'] = $text;
    }
    if ($tool === 'invoice-chaser') {
        // The chaser strictly requires a client, an amount, and a
        // real due date; never let a malformed extraction undo them.
        if (trim((string) ($fields['client_name'] ?? '')) === '') {
            $fields['client_name'] = mb_substr(trim((string) preg_replace('/\s+/', ' ', $text)), 0, 60);
        }
        if (preg_replace('/[^0-9.]/', '', (string) ($fields['amount'] ?? '')) === '') {
            $fields['amount'] = capture_parse_amount($text);
        }
        $due = (string) ($fields['due_date'] ?? '');
        $dueOk = preg_match('/^\d{4}-\d{2}-\d{2}$/', $due) === 1;
        if ($dueOk) {
            $dp = explode('-', $due);
            $dueOk = checkdate((int) $dp[1], (int) $dp[2], (int) $dp[0]);
        }
        if (!$dueOk) {
            $parsedDue = capture_parse_due_date($text);
            $fields['due_date'] = $parsedDue !== '' ? $parsedDue : date('Y-m-d', strtotime('+7 days'));
        }
    }
    return $fields;
}

/* ------------------------------------------------------------------ */
/* Router catalog: every tool the desk can pick. The five record tools  */
/* file directly (fields extracted below); every other tool is a        */
/* destination the user is taken to, in the suggest shape the keyword   */
/* layer already uses. Descriptions are written for the router: when    */
/* to pick the tool, plus the disambiguation that matters (invoice      */
/* maker vs chaser, notes vs Day Log, HEIC vs other image converts).    */
/* Keys, names, and URLs mirror tools/registry.json through             */
/* capture_targets() and capture_nav_destinations().                    */
/* ------------------------------------------------------------------ */

function capture_router_descriptions(): array {
    return [
        'clips' => 'record your screen in the browser and download the clip',
        'purrsuit' => 'a client follow-up tracker: who to follow up with today and what to say',
        'doodle' => 'draw or sketch something, or sign on a picture, and export it as PNG or SVG',
        'poster-to-calendar' => 'turn a photo of an event poster or flyer into a calendar event',
        'meal-scanner' => 'estimate calories and protein, carbs, and fat from a photo of a meal',
        'gap-scout' => 'compare a resume against job postings and list the skills the resume is missing',
        'video-trimmer' => 'trim or cut a video down to the good part',
        'image-converter' => 'convert an image between formats such as PNG, JPG, and WebP (not iPhone HEIC photos)',
        'image-cropper' => 'crop an image to a box or a preset size',
        'ghostwriter' => 'Voice to Content: turn a spoken ramble into hooks, a short video script, and a caption for social posts',
        'audiogram' => 'turn an MP3 audio file into a captioned video',
        'purr-code' => 'make a QR code',
        'sop-ify' => 'turn messy process notes into a clean step-by-step SOP document',
        'hearsay' => 'check what AI models say about a brand',
        'video-converter' => 'convert a video to another format (MP4, WebM, GIF) or pull the audio out of a video',
        'video-mp3-overlay' => 'put an MP3 soundtrack onto a video',
        'day-log' => 'log a whole day: what got done, what was missed, and priorities for tomorrow, with a recap by email',
        'email-signature-generator' => 'build an email signature from a name, title, and contact details',
        'pdf-merger' => 'combine several PDFs into one PDF',
        'pdf-to-image' => 'turn PDF pages into JPG, PNG, or WebP images',
        'pdf-compressor' => 'shrink a PDF so it fits an email or upload limit',
        'sound-human' => 'get a prompt add-on to paste into ChatGPT or Claude so AI writing sounds human',
        'heic-to-jpg' => 'convert iPhone HEIC photos to JPG, PNG, or PDF',
        'invoice-generator' => 'create a brand-new invoice document and download it as a PDF',
        'jpg-to-pdf' => 'turn JPG or PNG images into one PDF, in order',
        'split-pdf' => 'pull pages out of a PDF, or split a PDF into separate files',
        'png-to-jpg' => 'convert a PNG image to JPG (use image-converter for other image conversions)',
    ];
}

/* Non-decisive keyword scores, reused as hints for the router: the     */
/* top few tools the user's own phrase table leaned toward. The         */
/* decisive winner is handled earlier in the flow and never gets here.  */
function capture_kw_hints(string $text, int $limit = 3): array {
    $table = capture_kw_table();
    if ($table === null) return [];
    $tokens = capture_kw_tokens($text);
    if ($tokens === []) return [];
    $scores = [];
    foreach ($table as $tool => $entries) {
        $score = 0;
        $seen = [];
        foreach ($entries as $e) {
            $k = implode(' ', $e['tokens']);
            if (isset($seen[$k])) continue;
            $seen[$k] = true;
            if (capture_kw_phrase_hit($e['tokens'], $tokens)) $score += $e['points'];
        }
        if ($score > 0) $scores[$tool] = $score;
    }
    arsort($scores);
    return array_keys(array_slice($scores, 0, $limit, true));
}

/* LLM router: one model call against the whole catalog. Record tools   */
/* return fields to file; doing tools return a navigate marker the      */
/* caller answers with the suggest shape. A failed call, an unparseable */
/* answer, or an unknown tool key returns the honest could-not-sort     */
/* answer, never a note.                                                */
/* ------------------------------------------------------------------ */

function capture_llm_route(string $text): array {
    $down = ['tool' => 'unsupported',
        'fields' => ['message' => 'Bum Bum could not sort that one. Try picking the tool with the @ menu, or say it another way. Nothing was logged.'],
        'confidence' => 0.0, 'via' => 'fallback'];
    $key = (string) (config()['groq_api_key'] ?? '');
    if ($key === '' || $key === 'replace_me') return $down;
    $model = trim((string) (config()['capture_model'] ?? ''));
    if ($model === '') $model = 'openai/gpt-oss-120b';

    $system = 'You are Bum Bum\'s front desk router. The user typed or dictated a request. '
        . 'Pick the ONE tool it belongs to. There are two kinds of tools. '
        . 'RECORD tools file the words into the user\'s records; extract their fields. '
        . 'DO tools perform a task; the user is taken to the tool, so return the tool key with empty fields. '
        . 'RECORD TOOLS: '
        . 'notes: a thought, idea, reminder, or journal entry the user wants saved as text. fields: {"text"}. '
        . 'receipt-reader: a ONE-TIME purchase or expense with an amount. fields: {"vendor","total","date","currency"}. '
        . 'corporate-bum-bum: a job application or career move to track. fields: {"company","role","job_url","location","salary"}. '
        . 'invoice-chaser: an invoice a client owes, is late on, or needs a reminder about. fields: {"client_name","amount","due_date","invoice_no","notes"}. '
        . 'cutline: a RECURRING subscription or membership (Netflix, Spotify, a SaaS plan). fields: {"name","price","cadence","category"}. '
        . 'cadence must be one of: weekly, monthly, quarterly, semiannual, annual. '
        . 'category must be one of: streaming, music, software, cloud_storage, fitness, reading, gaming, food_delivery, other. '
        . 'DO TOOLS: ';
    foreach (capture_router_descriptions() as $k => $desc) {
        $system .= $k . ': ' . $desc . '. ';
    }
    $system .= 'unsupported: no tool above fits. fields: {"reason": "short verb phrase, e.g. wash a car"}. '
        . 'EXAMPLES: "idea: neon backgrounds for every tiktok" -> notes. '
        . '"lunch with Priya $24" -> receipt-reader. '
        . '"applied to Stripe for growth lead" -> corporate-bum-bum. '
        . '"invoice Acme $2000 due Friday" -> invoice-chaser. '
        . '"make an invoice for Acme, $2000" -> invoice-generator. '
        . '"Netflix $15.99 a month" -> cutline. '
        . '"crop a picture" -> image-cropper. '
        . '"separate the signed pages out of this contract" -> split-pdf. '
        . '"turn this voice memo into a post" -> ghostwriter. '
        . 'RULES: Output STRICT JSON only: {"tool":"<key>","fields":{...},"confidence":0.0-1.0}. '
        . '"tool" must be exactly one of the tool keys listed above, or unsupported. '
        . 'For DO tools, fields must be {}. '
        . 'A request to DO, MAKE, or TRANSFORM something belongs to the DO tool that does it, never to notes, and never to unsupported when a DO tool fits. '
        . 'notes is only for words the user wants recorded as text: a thought, an idea, a reminder to self, a journal entry. '
        . 'A rundown of the user\'s whole day belongs to day-log, not notes. '
        . 'The words subscription, membership, recurring, per month, or /month ALWAYS mean cutline, never receipt-reader. '
        . 'Receipt-reader is only for one-time purchases. '
        . 'If a cutline entry has no name, set confidence below 0.6 so the user is asked. '
        . 'Never invent amounts, dates, or names the user did not mention. Omit unknown optional fields. '
        . 'Use YYYY-MM-DD for dates; assume the current year. No em dashes anywhere.';
    $hints = capture_kw_hints($text);
    if ($hints !== []) {
        $system .= ' KEYWORD HINTS: the user\'s own phrase table leaned toward: ' . implode(', ', $hints)
            . '. Treat these as weak evidence only; meaning wins.';
    }

    $payload = [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => mb_substr($text, 0, 2000)],
        ],
        'response_format' => ['type' => 'json_object'],
        'temperature' => 0.2,
        'max_tokens' => 400,
    ];
    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $key],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $raw = curl_exec($ch);
    $err = curl_error($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($raw === false || $err !== '' || $code < 200 || $code >= 300) return $down;
    $data = json_decode((string) $raw, true);
    $content = $data['choices'][0]['message']['content'] ?? '';
    if (is_string($content) && preg_match('/```(?:json)?\s*(\{.*\})\s*```/s', $content, $m)) $content = $m[1];
    $parsed = is_string($content) ? json_decode($content, true) : null;
    if (!is_array($parsed)) return $down;
    $tool = (string) ($parsed['tool'] ?? '');
    $confidence = (float) ($parsed['confidence'] ?? 0.5);
    $confidence = max(0.0, min(1.0, $confidence));
    $nav = capture_nav_destinations();
    if (isset($nav[$tool])) {
        return ['tool' => $tool, 'fields' => [], 'confidence' => $confidence, 'via' => 'llm',
            'navigate' => ['tool' => $tool, 'name' => $nav[$tool]['name'], 'url' => $nav[$tool]['url']]];
    }
    if ($tool === 'unsupported') {
        $fields = is_array($parsed['fields'] ?? null) ? $parsed['fields'] : [];
        return ['tool' => 'unsupported', 'fields' => $fields, 'confidence' => $confidence, 'via' => 'llm'];
    }
    if (!isset(capture_targets()[$tool])) return $down;
    $fields = is_array($parsed['fields'] ?? null) ? $parsed['fields'] : [];
    return ['tool' => $tool, 'fields' => $fields, 'confidence' => $confidence, 'via' => 'llm'];
}

/* ------------------------------------------------------------------ */
/* loopback execution: POST to the tool's own API as the user          */
/* ------------------------------------------------------------------ */

function capture_loopback(string $endpoint, array $payload): array {
    $host = (string) ($_SERVER['HTTP_HOST'] ?? 'leaveittobumbum.com');
    $url = 'https://' . $host . $endpoint;
    // Release the session lock first: the loopback request needs the same
    // session, and PHP would otherwise block on our own lock forever.
    session_write_close();
    // Forward the guest cookie too: destinations resolve the visitor's guest
    // subject from it, so a guest capture meters (and merges) as that guest.
    $cookie = session_name() . '=' . session_id();
    if (!empty($_COOKIE[GUEST_COOKIE_NAME])) {
        $cookie .= '; ' . GUEST_COOKIE_NAME . '=' . $_COOKIE[GUEST_COOKIE_NAME];
    }
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Cookie: ' . $cookie,
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 45,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $raw = curl_exec($ch);
    $err = curl_error($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($raw === false || $err !== '') return ['ok' => false, 'http' => 0, 'data' => []];
    $data = json_decode((string) $raw, true);
    return ['ok' => $code >= 200 && $code < 300, 'http' => $code, 'data' => is_array($data) ? $data : []];
}

/* ------------------------------------------------------------------ */
/* per-tool payload builders + summaries                               */
/* ------------------------------------------------------------------ */

function capture_tool_payload(string $tool, array $fields, string $text, string $csrf, string $idem): array {
    $base = ['csrf' => $csrf, 'idempotencyKey' => $idem];
    switch ($tool) {
        case 'notes':
            return $base + ['action' => 'finalize', 'transcript' => (string) ($fields['text'] ?? $text)];
        case 'receipt-reader': {
            $total = (string) ($fields['total'] ?? '');
            $total = preg_replace('/[^0-9.]/', '', $total);
            $date = (string) ($fields['date'] ?? '');
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) $date = date('Y-m-d');
            return $base + [
                'action' => 'save',
                'vendor' => mb_substr(trim((string) ($fields['vendor'] ?? 'Receipt')), 0, 191),
                'total' => $total !== '' ? $total : '0.00',
                'date' => $date,
                'currency' => 'USD',
            ];
        }
        case 'corporate-bum-bum': {
            $salaryNote = trim((string) ($fields['salary'] ?? ''));
            return $base + [
                'action' => 'add',
                'company' => mb_substr(trim((string) ($fields['company'] ?? '')), 0, 191),
                'role' => mb_substr(trim((string) ($fields['role'] ?? '')), 0, 191),
                'job_url' => mb_substr(trim((string) ($fields['job_url'] ?? '')), 0, 500),
                'location' => mb_substr(trim((string) ($fields['location'] ?? '')), 0, 191),
                'notes' => $salaryNote !== '' ? 'Salary mentioned: ' . mb_substr($salaryNote, 0, 500) : '',
                'stage' => 'applied',
                'date_applied' => date('Y-m-d'),
                'source' => 'capture box',
            ];
        }
        case 'invoice-chaser': {
            $amount = (string) ($fields['amount'] ?? '');
            $amount = preg_replace('/[^0-9.]/', '', $amount);
            $due = (string) ($fields['due_date'] ?? '');
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $due)) $due = '';
            return $base + [
                'action' => 'add',
                'client_name' => mb_substr(trim((string) ($fields['client_name'] ?? '')), 0, 191),
                'amount' => $amount !== '' ? $amount : '0',
                'currency' => 'USD',
                'invoice_no' => mb_substr(trim((string) ($fields['invoice_no'] ?? '')), 0, 64),
                'due_date' => $due,
                'notes' => mb_substr(trim((string) ($fields['notes'] ?? '')), 0, 1000),
            ];
        }
        case 'cutline': {
            $priceRaw = (string) ($fields['price'] ?? '');
            $priceRaw = preg_replace('/[^0-9.]/', '', $priceRaw);
            $cents = $priceRaw !== '' ? (int) round((float) $priceRaw * 100) : 0;
            $cadRaw = strtolower(trim((string) ($fields['cadence'] ?? 'monthly')));
            $cadence = 'monthly';
            foreach (['weekly' => 'weekly', 'month' => 'monthly', 'quarter' => 'quarterly', 'semiannual' => 'semiannual', 'half' => 'semiannual', 'annual' => 'annual', 'year' => 'annual'] as $needle => $value) {
                if (strpos($cadRaw, $needle) !== false) { $cadence = $value; break; }
            }
            $category = strtolower(trim((string) ($fields['category'] ?? 'other')));
            if (!in_array($category, ['streaming', 'music', 'software', 'cloud_storage', 'fitness', 'reading', 'gaming', 'food_delivery', 'other'], true)) $category = 'other';
            $today = date('Y-m-d');
            return $base + [
                'action' => 'add',
                'custom_name' => mb_substr(trim((string) ($fields['name'] ?? '')), 0, 191),
                'category' => $category,
                'tier_name' => '',
                'price_cents' => $cents,
                'currency' => 'USD',
                'cadence' => $cadence,
                'started_on' => $today,
                'next_renewal_on' => $today,
            ];
        }
    }
    return $base;
}

function capture_summary(string $tool, array $fields, string $text, array $result): string {
    switch ($tool) {
        case 'notes': {
            $t = trim((string) ($fields['text'] ?? $text));
            $preview = mb_substr((string) preg_replace('/\s+/', ' ', $t), 0, 80);
            return 'Saved to Notes: "' . $preview . (mb_strlen($t) > 80 ? '..."' : '"');
        }
        case 'receipt-reader': {
            $v = trim((string) ($fields['vendor'] ?? 'Receipt'));
            $total = ltrim(trim((string) ($fields['total'] ?? '')), '$');
            return 'Logged ' . ($total !== '' ? '$' . $total . ' at ' : '') . $v . ' to Receipt Scanner';
        }
        case 'corporate-bum-bum': {
            $c = trim((string) ($fields['company'] ?? ''));
            $r = trim((string) ($fields['role'] ?? ''));
            return 'Added ' . ($r !== '' ? $r . ' at ' : '') . ($c !== '' ? $c : 'new application') . ' to Job Application Tracker';
        }
        case 'invoice-chaser': {
            $c = trim((string) ($fields['client_name'] ?? 'client'));
            $a = ltrim(trim((string) ($fields['amount'] ?? '')), '$');
            return 'Added ' . ($a !== '' ? '$' . $a . ' ' : '') . 'invoice for ' . $c . ' to Invoice Reminder';
        }
        case 'cutline': {
            $n = trim((string) ($fields['name'] ?? 'subscription'));
            $p = ltrim(trim((string) ($fields['price'] ?? '')), '$');
            $cad = trim((string) ($fields['cadence'] ?? 'monthly'));
            return 'Added ' . $n . ($p !== '' ? ' ($' . $p . '/' . $cad . ')' : '') . ' to Subscription Tracker';
        }
    }
    return 'Logged it';
}

function capture_idempotency(array $input): string {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
    return $key;
}

/* The ask-don't-guess answer: the record tools as tappable options,     */
/* with the router's guess and its fields riding along so a pick needs   */
/* one tap. Nothing is charged.                                          */
function capture_ambiguous(string $guess, array $fields, string $text): void {
    $options = [];
    foreach (capture_targets() as $key => $t) {
        $options[] = ['tool' => $key, 'tool_name' => $t['name']];
    }
    jsonResponse(['ok' => false, 'ambiguous' => true, 'guess' => $guess, 'fields' => $fields, 'options' => $options, 'text' => $text]);
}

/* ------------------------------------------------------------------ */
/* main                                                                */
/* ------------------------------------------------------------------ */

requirePost();
$input = body();
requireCsrf($input);
// Users and guests alike: the box now lives on the homepage and on tool
// pages, not just the dashboard. Destinations meter whoever the subject is
// (a guest capture spends one of the guest's free actions, same as using
// the tool itself), and the delayed account prompt handles the signup ask.
$subject = requireSubject();

// Session rate limit: 30 captures/hour bounds LLM + loopback spend.
startSecureSession();
$hour = gmdate('Y-m-d-H');
if (($_SESSION['cap_hour'] ?? '') !== $hour) {
    $_SESSION['cap_hour'] = $hour;
    $_SESSION['cap_count'] = 0;
}
if ((int) ($_SESSION['cap_count'] ?? 0) >= 30) {
    jsonResponse(['error' => 'Whoa, slow down. The capture box needs a breather, try again in a bit.'], 429);
}
$_SESSION['cap_count'] = (int) ($_SESSION['cap_count'] ?? 0) + 1;

$text = trim((string) ($input['text'] ?? ''));

// File attached: route the file to a media tool. The bytes never touch this
// server; only name/mime/size travel here for routing. The browser hands the
// file to the tool page via IndexedDB after this responds.
$fileName = trim((string) ($input['fileName'] ?? ''));
if ($fileName !== '') {
    $fileMime = strtolower(trim((string) ($input['fileMime'] ?? '')));
    $fileSize = (int) ($input['fileSize'] ?? 0);
    $ext = strtolower((string) pathinfo($fileName, PATHINFO_EXTENSION));
    $isImage = strpos($fileMime, 'image/') === 0 && in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif', 'avif'], true);
    $isVideo = strpos($fileMime, 'video/') === 0 && in_array($ext, ['mp4', 'webm', 'mov', 'mkv', 'avi', 'm4v', '3gp'], true);
    if ($fileSize <= 0 || $fileSize > 100 * 1024 * 1024 || (!$isImage && !$isVideo)) {
        jsonResponse(['error' => 'That file will not work here. Images (PNG, JPG, WebP, GIF, AVIF) or video (MP4, WebM, MOV, MKV, AVI) under 100 MB.'], 422);
    }
    $target = capture_file_target($isImage ? 'image' : 'video', $text);
    if ($target === null) {
        jsonResponse(['ok' => true, 'logged' => false,
            'message' => 'Bum Bum can\'t do that with a file yet. Nothing was logged.']);
    }
    if (!empty($target['ask'])) {
        jsonResponse(['ok' => true, 'logged' => false,
            'message' => 'Got "' . mb_substr($fileName, 0, 60) . '". What should Bum Bum do with it? Try "crop it", "convert it", or "trim it".']);
    }
    jsonResponse(['ok' => true, 'logged' => false, 'handoff' => true,
        'tool_name' => $target['name'],
        'tool_url' => $target['url'] . '?from=capture',
        'message' => 'Sending "' . mb_substr($fileName, 0, 60) . '" to ' . $target['name'] . '. Your file never leaves your device.']);
}

if ($text === '') jsonResponse(['error' => 'Give Bum Bum something to file.'], 422);
if (mb_strlen($text) > 100000) jsonResponse(['error' => 'That is a novel, not a log. Try the Notes tool directly.'], 422);
$idem = capture_idempotency($input);
$targets = capture_targets();

// Optional forced tool: an explicit pick, either from the "did you
// mean" chips (fields in hand) or an @ pin at the front desk (no fields).
$forced = (string) ($input['tool'] ?? '');
$route = null;
if ($forced !== '' && isset($targets[$forced])) {
    $forcedFields = $input['fields'] ?? null;
    if (is_array($forcedFields) && $forcedFields !== []) {
        // Chip pick with the router's field guess in hand: honor it
        // exactly as before, never second-guess.
        $route = [
            'tool' => $forced,
            'fields' => $forcedFields,
            'confidence' => 1.0,
            'via' => 'user',
        ];
    } else {
        // @ pin: the pinned destination is ABSOLUTE. The receipt fast
        // path never runs and the router's tool choice is never
        // consulted. The LLM may only extract fields for the pinned
        // tool, merged over deterministic guesses; if extraction fails
        // outright, the raw text is filed to the pinned tool anyway.
        $fields = capture_destination_fields($text, $forced);
        $route = [
            'tool' => $forced,
            'fields' => $fields,
            'confidence' => 1.0,
            'via' => 'pin',
        ];
    }
} else {
    $route = capture_fast_path($text);
    if ($route === null) {
        $suggest = capture_task_suggest($text);
        if ($suggest !== null) {
            jsonResponse(['ok' => true, 'logged' => false,
                'message' => 'That\'s a job for ' . $suggest['name'] . '. The log box only files text into tools, so open it there to get it done. Nothing was logged.',
                'suggest_name' => $suggest['name'], 'suggest_url' => $suggest['url']]);
        }
        // Keyword layer: a decisive match against the user's keyword
        // table routes here. Native destinations file below exactly as
        // an LLM route does; the other tools get the suggest shape so
        // the client opens them with the words in hand, as a task
        // suggestion does, but the payload says via=keywords.
        $kwRoute = capture_keyword_route($text);
        if ($kwRoute !== null && isset($kwRoute['navigate'])) {
            $nav = $kwRoute['navigate'];
            jsonResponse(['ok' => true, 'logged' => false,
                'message' => 'That\'s a job for ' . $nav['name'] . '. The log box only files text into tools, so open it there to get it done. Nothing was logged.',
                'suggest_name' => $nav['name'], 'suggest_url' => $nav['url'],
                'tool' => $nav['tool'], 'via' => 'keywords']);
        }
        $route = $kwRoute ?? capture_llm_route($text);
    }
}

// A router pick of a doing tool: answer with the suggest shape, the
// same way a keyword navigate does, so the client opens the tool with
// the user's words in hand. Nothing is filed and nothing is charged.
if (is_array($route) && !empty($route['navigate'])) {
    $nav = $route['navigate'];
    jsonResponse(['ok' => true, 'logged' => false,
        'message' => 'That\'s a job for ' . $nav['name'] . '. The log box only files text into tools, so open it there to get it done. Nothing was logged.',
        'suggest_name' => $nav['name'], 'suggest_url' => $nav['url'],
        'tool' => $nav['tool'], 'via' => $route['via'] ?? 'llm']);
}

$tool = $route['tool'];
$fields = is_array($route['fields']) ? $route['fields'] : [];
$confidence = (float) ($route['confidence'] ?? 0);

// The box files things into tools; it doesn't perform tasks. Be honest
// about that instead of misfiling an action request as a note.
if ($tool === 'unsupported') {
    if (!empty($fields['message'])) {
        jsonResponse(['ok' => true, 'logged' => false, 'message' => (string) $fields['message']]);
    }
    $reason = trim((string) ($fields['reason'] ?? ''));
    if ($reason === '') $reason = 'do that';
    jsonResponse(['ok' => true, 'logged' => false,
        'message' => 'Bum Bum can\'t ' . mb_substr($reason, 0, 60) . ' from here. This box files things into your tools, it doesn\'t do the task itself. Nothing was logged.']);
}

// Low confidence and no forced tool: ask, don't guess. Nothing is charged.
// The router's field guess rides along so the user's pick needs one tap.
if ($confidence < 0.6 && $forced === '') {
    capture_ambiguous($tool, $fields, $text);
}

// Defensive checks only for router-chosen tools, never for explicit
// picks. A record pick missing the field that makes it that record is
// a guess, not a filing: ask, with the guess in hand. These used to
// refile the words as a note, which is how action requests and
// half-formed records kept landing in Notes.
if ($forced === '') {
    if ($tool === 'notes' && empty($fields['text'])) $fields['text'] = $text;
    if ($tool === 'receipt-reader' && empty($fields['total'])) {
        capture_ambiguous($tool, $fields, $text);
    }
    if ($tool === 'corporate-bum-bum' && trim((string) ($fields['company'] ?? '')) === '') {
        capture_ambiguous($tool, $fields, $text);
    }
    if ($tool === 'invoice-chaser' && trim((string) ($fields['client_name'] ?? '')) === '') {
        capture_ambiguous($tool, $fields, $text);
    }
} elseif ($tool === 'notes' && empty($fields['text'])) {
    $fields['text'] = $text;
}

$target = $targets[$tool];
$loopKey = 'cap-' . $idem;
if (strlen($loopKey) > 128) $loopKey = substr($loopKey, 0, 128);
$payload = capture_tool_payload($tool, $fields, $text, csrfToken(), $loopKey);
$payload['action'] = $target['action'];

$res = capture_loopback($target['endpoint'], $payload);

if (!$res['ok']) {
    $data = $res['data'];
    if ($res['http'] === 402 || !empty($data['limit_reached']) || !empty($data['signup_required'])) {
        jsonResponse(['ok' => false, 'error' => 'Out of actions for this period. Bum Bum needs a refill.', 'limit_reached' => true], 402);
    }
    $msg = (string) ($data['error'] ?? '');
    jsonResponse(['ok' => false, 'error' => $msg !== '' ? $msg : 'Bum Bum dropped it. Try again, or log it in the tool directly.'], $res['http'] >= 400 ? $res['http'] : 502);
}

$data = $res['data'];
$usage = $data['usage'] ?? null;
if ($usage === null) {
    try {
        $usage = subjectUsage($subject);
    } catch (Throwable $e) {
        $usage = null;
    }
}

jsonResponse([
    'ok' => true,
    'tool' => $tool,
    'tool_name' => $target['name'],
    'summary' => capture_summary($tool, $fields, $text, $data),
    'url' => $target['url'],
    'via' => $route['via'] ?? 'llm',
    'duplicate' => (bool) ($data['duplicate'] ?? false),
    'usage' => $usage,
]);
