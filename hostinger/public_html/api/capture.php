<?php
declare(strict_types=1);

/**
 * Bum Bum universal capture: the "log anything" router.
 *
 * One box on the dashboard takes text (or a voice transcript) and files it
 * into the right tool: a note, a receipt, a subscription, a job application,
 * or an invoice. The user never picks a tool; Bum Bum does.
 * Action requests ("crop a picture") are declined honestly instead of being
 * misfiled: the box records things, it doesn't perform tasks.
 *
 * How it works:
 *   1. Session + CSRF (same as every other tool endpoint). The subject may
 *      be a signed-in user or a guest; destinations meter either, exactly
 *      as if the visitor had used the tool directly.
 *   2. Session rate limit: 30 captures/hour (bounds LLM spend).
 *   3. Keyword fast path for obvious receipts ("Starbucks $6.40"): no LLM.
 *   3b. Task-intent fast path ("convert mp4 to wav", "crop a picture"):
 *      the box can't run tools, so it points at the right one. No LLM.
 *   4. Otherwise a cheap Groq model (default openai/gpt-oss-20b) routes to
 *      one tool and extracts fields as strict JSON.
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

/* LLM router                                                          */
/* ------------------------------------------------------------------ */

function capture_llm_route(string $text): array {
    $key = (string) (config()['groq_api_key'] ?? '');
    if ($key === '' || $key === 'replace_me') {
        return ['tool' => 'notes', 'fields' => ['text' => $text], 'confidence' => 0.5, 'via' => 'fallback'];
    }
    $model = trim((string) (config()['capture_model'] ?? ''));
    if ($model === '') $model = 'openai/gpt-oss-20b';

    $system = 'You are Bum Bum\'s capture router. The user typed or dictated a quick log entry. '
        . 'Pick the ONE tool it belongs to and extract its fields. '
        . 'TOOLS: '
        . 'notes: a thought, idea, reminder, or journal entry. fields: {"text"}. '
        . 'receipt-reader: a ONE-TIME purchase or expense with an amount. fields: {"vendor","total","date","currency"}. '
        . 'corporate-bum-bum: a job application or career move. fields: {"company","role","job_url","location","salary"}. '
        . 'invoice-chaser: an invoice to chase, a client who owes money. fields: {"client_name","amount","due_date","invoice_no","notes"}. '
        . 'cutline: a RECURRING subscription or membership (Netflix, Spotify, a SaaS plan). fields: {"name","price","cadence","category"}. '
        . 'cadence must be one of: weekly, monthly, quarterly, semiannual, annual. '
        . 'category must be one of: streaming, music, software, cloud_storage, fitness, reading, gaming, food_delivery, other. '
        . 'unsupported: the user wants Bum Bum to DO, MAKE, or TRANSFORM something (crop, edit, resize, build, generate, create, summarize, remind) rather than record information. fields: {"reason": "short verb phrase, e.g. crop a picture"}. '
        . 'EXAMPLES: "idea: neon backgrounds for every tiktok" -> notes. '
        . '"lunch with Priya $24" -> receipt-reader. '
        . '"applied to Stripe for growth lead" -> corporate-bum-bum. '
        . '"invoice Acme $2000 due Friday" -> invoice-chaser. '
        . '"log a subscription" -> cutline. '
        . '"Netflix $15.99 a month" -> cutline. '
        . '"crop a picture" -> unsupported with reason "crop a picture". '
        . '"make me a logo" -> unsupported. '
        . 'RULES: Output STRICT JSON only: {"tool":"<key>","fields":{...},"confidence":0.0-1.0}. '
        . '"tool" must be exactly one of: notes, receipt-reader, corporate-bum-bum, invoice-chaser, cutline, unsupported. '
        . 'If the input asks Bum Bum to perform, create, or transform something instead of recording information, use unsupported with confidence 1.0. '
        . 'Never file an action request as a note. '
        . 'The words subscription, membership, recurring, per month, or /month ALWAYS mean cutline, never receipt-reader. '
        . 'Receipt-reader is only for one-time purchases. '
        . 'If a cutline entry has no name, set confidence below 0.6 so the user is asked. '
        . 'Never invent amounts, dates, or names the user did not mention. Omit unknown optional fields. '
        . 'Use YYYY-MM-DD for dates; assume the current year. No em dashes anywhere.';

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

    $fallback = ['tool' => 'notes', 'fields' => ['text' => $text], 'confidence' => 0.5, 'via' => 'fallback'];
    if ($raw === false || $err !== '' || $code < 200 || $code >= 300) return $fallback;
    $data = json_decode((string) $raw, true);
    $content = $data['choices'][0]['message']['content'] ?? '';
    if (is_string($content) && preg_match('/```(?:json)?\s*(\{.*\})\s*```/s', $content, $m)) $content = $m[1];
    $parsed = is_string($content) ? json_decode($content, true) : null;
    if (!is_array($parsed)) return $fallback;
    $tool = (string) ($parsed['tool'] ?? '');
    if (!isset(capture_targets()[$tool])) return $fallback;
    $fields = is_array($parsed['fields'] ?? null) ? $parsed['fields'] : [];
    $confidence = (float) ($parsed['confidence'] ?? 0.5);
    $confidence = max(0.0, min(1.0, $confidence));
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
            $total = (string) ($fields['total'] ?? '');
            return 'Logged ' . ($total !== '' ? '$' . $total . ' at ' : '') . $v . ' to Receipt Scanner';
        }
        case 'corporate-bum-bum': {
            $c = trim((string) ($fields['company'] ?? ''));
            $r = trim((string) ($fields['role'] ?? ''));
            return 'Added ' . ($r !== '' ? $r . ' at ' : '') . ($c !== '' ? $c : 'new application') . ' to Job Application Tracker';
        }
        case 'invoice-chaser': {
            $c = trim((string) ($fields['client_name'] ?? 'client'));
            $a = (string) ($fields['amount'] ?? '');
            return 'Added ' . ($a !== '' ? '$' . $a . ' ' : '') . 'invoice for ' . $c . ' to Invoice Reminder';
        }
        case 'cutline': {
            $n = trim((string) ($fields['name'] ?? 'subscription'));
            $p = trim((string) ($fields['price'] ?? ''));
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
        $fields = capture_pin_fallback_fields($text, $forced);
        $extracted = capture_llm_extract($text, $forced);
        if (is_array($extracted)) {
            foreach ($extracted as $k => $v) {
                if ($v !== '' && $v !== null) $fields[$k] = $v;
            }
        }
        if ($forced === 'notes') {
            // A note is the raw words, never an LLM rewrite of them.
            $fields['text'] = $text;
        }
        if ($forced === 'invoice-chaser') {
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
        $route = capture_llm_route($text);
    }
}

$tool = $route['tool'];
$fields = is_array($route['fields']) ? $route['fields'] : [];
$confidence = (float) ($route['confidence'] ?? 0);

// The box files things into tools; it doesn't perform tasks. Be honest
// about that instead of misfiling an action request as a note.
if ($tool === 'unsupported') {
    $reason = trim((string) ($fields['reason'] ?? ''));
    if ($reason === '') $reason = 'do that';
    jsonResponse(['ok' => true, 'logged' => false,
        'message' => 'Bum Bum can\'t ' . mb_substr($reason, 0, 60) . ' from here. This box files things into your tools, it doesn\'t do the task itself. Nothing was logged.']);
}

// Low confidence and no forced tool: ask, don't guess. Nothing is charged.
// The router's field guess rides along so the user's pick needs one tap.
if ($confidence < 0.6 && $forced === '') {
    $options = [];
    foreach ($targets as $key => $t) {
        $options[] = ['tool' => $key, 'tool_name' => $t['name']];
    }
    jsonResponse(['ok' => false, 'ambiguous' => true, 'guess' => $tool, 'fields' => $fields, 'options' => $options, 'text' => $text]);
}

// Defensive fallbacks only for router-chosen tools, never for explicit picks.
if ($forced === '') {
    if ($tool === 'notes' && empty($fields['text'])) $fields['text'] = $text;
    if ($tool === 'receipt-reader' && empty($fields['total'])) {
        // Router found no amount: not really a receipt. Save as a note instead.
        $tool = 'notes';
        $fields = ['text' => $text];
    }
    if ($tool === 'corporate-bum-bum' && trim((string) ($fields['company'] ?? '')) === '') {
        $tool = 'notes';
        $fields = ['text' => $text];
    }
    if ($tool === 'invoice-chaser' && trim((string) ($fields['client_name'] ?? '')) === '') {
        $tool = 'notes';
        $fields = ['text' => $text];
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
