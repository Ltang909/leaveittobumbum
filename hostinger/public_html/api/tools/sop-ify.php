<?php
// SOP-ify API. Turn a chaotic process brain-dump into a clean step-by-step
// playbook (SOP) a brand-new helper can follow with zero prior context.
//
//   POST /api/tools/sop-ify.php  action=generate  brainDump=<text>
//                                 helperFor=<optional>  idempotencyKey=<uuid>
//   -> { "ok": true, "playbook": {...}, "usage": {...} }
//
// Metering: exactly one action per successfully generated playbook.
// Failed Groq calls never consume an action.
//
// Server config: 'groq_api_key' in leaveittobumbum-config.php
// (free key from https://console.groq.com/keys). Optional 'sopify_model'
// overrides the Groq chat model (default openai/gpt-oss-120b).
require dirname(__DIR__) . '/_bootstrap.php';

function sopify_clean(string $s): string {
    // Guardrail: no em dashes, ever.
    $s = str_replace(' — ', ', ', $s);
    $s = str_replace('—', ',', $s);
    $s = str_replace('–', '-', $s);
    return $s;
}

function sopify_call_groq(string $dump, string $helperFor): array {
    $key = (string) (config()['groq_api_key'] ?? '');
    if ($key === '' || $key === 'replace_me') {
        jsonResponse(['error' => 'SOP-ify is not set up on the server yet.'], 503);
    }
    $who = $helperFor !== '' ? ' It will be followed by ' . $helperFor . '.' : '';
    $system = 'You are Bum Bum\'s operations helper, writing for small business owners and solopreneurs who are about to hand a task to a helper, VA, or new hire.' . $who
        . ' The user pasted a chaotic brain-dump describing how they do something in their business. Turn it into a clean, complete step-by-step playbook (SOP) that a brand-new helper could follow with zero prior context. '
        . 'Rules: '
        . '1) title: a short, clear name for this process. '
        . '2) purpose: 1-2 sentences on what this process achieves and when to do it. '
        . '3) steps: the full process as numbered steps, in the order they must happen. Each step has a short imperative title ("Confirm the booking") and a 1-2 sentence detail with the specifics. Fill obvious gaps with sensible defaults, but never invent prices, dates, names, passwords, or tools the user did not mention; write "ask the owner" instead of guessing those. '
        . '4) materials: anything the helper needs before starting (tools, logins, supplies). Empty array if none are clear. '
        . '5) tips: 2-4 gotchas or pro tips that only someone who has done this would know. Empty array if none. '
        . '6) checklist: a short checkbox version of the steps, 5-10 items, each under 10 words. '
        . '7) Write for a brand-new helper: define any jargon, be explicit about handoffs and anything needing the owner\'s approval. '
        . '8) NEVER use em dashes or en dashes anywhere in the output. Use commas, colons, or parentheses instead. '
        . 'Output STRICT JSON only, no other text: {"title": "...", "purpose": "...", "steps": [{"step": "...", "detail": "..."}], "materials": ["..."], "tips": ["..."], "checklist": ["..."]}';
    $model = trim((string) (config()['sopify_model'] ?? ''));
    if ($model === '') $model = 'openai/gpt-oss-120b';
    $payload = [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $dump],
        ],
        'response_format' => ['type' => 'json_object'],
        'temperature' => 0.5,
        'max_tokens' => 2000,
    ];
    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $key],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 60,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $raw = curl_exec($ch);
    $err = curl_error($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($raw === false || $err !== '') {
        jsonResponse(['error' => 'SOP-ify lost the plot. Try again in a moment.'], 502);
    }
    if ($code === 429) {
        jsonResponse(['error' => 'SOP-ify is swamped right now. Try again in a minute.'], 429);
    }
    if ($code < 200 || $code >= 300) {
        jsonResponse(['error' => 'SOP-ify tripped over its own paws. Try again.'], 502);
    }
    $data = json_decode((string) $raw, true);
    $content = $data['choices'][0]['message']['content'] ?? '';
    if (is_string($content) && preg_match('/```(?:json)?\s*(\{.*\})\s*```/s', $content, $m)) {
        $content = $m[1];
    }
    $book = is_string($content) ? json_decode($content, true) : null;
    if (!is_array($book)) {
        jsonResponse(['error' => 'SOP-ify mumbled. Try again.'], 502);
    }
    $book['title'] = sopify_clean(trim((string) ($book['title'] ?? '')));
    $book['purpose'] = sopify_clean(trim((string) ($book['purpose'] ?? '')));
    $steps = [];
    foreach ((array) ($book['steps'] ?? []) as $s) {
        if (!is_array($s)) continue;
        $t = sopify_clean(trim((string) ($s['step'] ?? '')));
        $d = sopify_clean(trim((string) ($s['detail'] ?? '')));
        if ($t === '') continue;
        $steps[] = ['step' => mb_substr($t, 0, 120), 'detail' => mb_substr($d, 0, 500)];
        if (count($steps) >= 30) break;
    }
    $cleanList = function ($arr, $max, $len) {
        $out = [];
        foreach ((array) $arr as $v) {
            $v = sopify_clean(trim((string) $v));
            if ($v === '') continue;
            $out[] = mb_substr($v, 0, $len);
            if (count($out) >= $max) break;
        }
        return $out;
    };
    $book['steps'] = $steps;
    $book['materials'] = $cleanList($book['materials'] ?? [], 10, 160);
    $book['tips'] = $cleanList($book['tips'] ?? [], 6, 240);
    $book['checklist'] = $cleanList($book['checklist'] ?? [], 12, 120);
    if ($book['title'] === '' || $book['purpose'] === '' || count($steps) < 2) {
        jsonResponse(['error' => 'SOP-ify mumbled. Try again.'], 502);
    }
    return $book;
}

requirePost();
$input = body();
$action = (string) ($input['action'] ?? 'generate');
requireCsrf($input);
$subject = requireSubject();

if ($action !== 'generate') jsonResponse(['error' => 'Unknown action.'], 400);
$dump = trim((string) ($input['brainDump'] ?? ''));
$helperFor = trim((string) ($input['helperFor'] ?? ''));
if (mb_strlen($dump) < 20) jsonResponse(['error' => 'Give me a little more to work with, at least a few sentences.'], 422);
if (mb_strlen($dump) > 6000) jsonResponse(['error' => 'That brain-dump is a novel. Trim it under 6,000 characters.'], 422);
if (mb_strlen($helperFor) > 120) jsonResponse(['error' => 'Keep the "who is this for" bit short.'], 422);
$key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
if (strlen($key) < 16 || strlen($key) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
$pre = subjectUsage($subject);
if (empty($pre['unlimited']) && ($pre['remaining'] ?? 0) <= 0) {
    limitReachedResponse($subject, $pre);
}
$book = sopify_call_groq($dump, $helperFor);
$count = consumeSubjectAction($subject, 'sop-ify', $key);
if (!empty($count['limit_reached'])) limitReachedResponse($subject, $count);
jsonResponse([
    'ok' => true,
    'playbook' => $book,
    'usage' => subjectUsage($subject),
    'duplicate' => (bool) ($count['duplicate'] ?? false),
]);
