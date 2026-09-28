<?php
// Bum Bum Ghostwriter API. Voice ramble in, content pack out.
//
// The browser records audio and transcribes it (SpeechRecognition live,
// or the /api/voice-transcribe.php relay on iPhone). The transcript is sent
// here, Groq turns it into three hooks, a 60-second script, and a caption,
// and the finished pack is saved to the user's library. The audio is raw
// material and is never stored anywhere.
//
// Auth: signed-in session + CSRF (same as the other tool endpoints).
// Server config: 'groq_api_key' in leaveittobumbum-config.php
// (free key from https://console.groq.com/keys). Optional 'ghostwriter_model'
// overrides the Groq chat model (default openai/gpt-oss-120b).
// Metering: one action covers one finished pack. The usage pre-check means
// a failed generation never costs the user an action; the idempotency key
// means a retried generation never costs two.
//
//   POST /api/tools/ghostwriter.php  action=generate  transcript=<text>  idempotencyKey=<uuid>
//   -> { "ok": true, "entry": {...}, "pack": {...}, "usage": {...} }
require dirname(__DIR__) . '/_bootstrap.php';

function ensureGhostwriterSchema(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS ghostwriter_entries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(191) NOT NULL,
        transcript MEDIUMTEXT NOT NULL,
        pack MEDIUMTEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        KEY idx_user (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function ghostwriter_idempotency(array $input): string {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
    return $key;
}

function ghostwriter_public_row(array $row, bool $full): array {
    $out = [
        'id' => (int) $row['id'],
        'title' => $row['title'],
        'created_at' => $row['created_at'],
    ];
    $pack = json_decode((string) $row['pack'], true);
    if (!is_array($pack)) $pack = [];
    if ($full) {
        $out['transcript'] = (string) $row['transcript'];
        $out['pack'] = $pack;
    } else {
        $hooks = $pack['hooks'] ?? [];
        // Hooks used to be plain strings; now they are {hook, theme} objects. Handle both.
        $first = is_array($hooks) ? ($hooks[0] ?? null) : null;
        $previewHook = is_array($first) ? (string) ($first['hook'] ?? '') : (string) $first;
        $out['preview'] = $previewHook !== '' ? $previewHook : mb_substr((string) $row['transcript'], 0, 140);
    }
    return $out;
}

function ghostwriter_clean(string $s): string {
    // Guardrail: no em dashes, ever. " — " becomes ", ", strays become commas, en dashes become hyphens.
    $s = str_replace(' — ', ', ', $s);
    $s = str_replace('—', ',', $s);
    $s = str_replace('–', '-', $s);
    return $s;
}

function ghostwriter_call_groq(string $transcript, string $tone): array {
    $key = (string) (config()['groq_api_key'] ?? '');
    if ($key === '' || $key === 'replace_me') {
        jsonResponse(['error' => 'The ghostwriter is not set up on the server yet.'], 503);
    }
    $tones = [
        'professional' => 'polished and professional, like a trusted advisor. No slang, no hype',
        'friendly' => 'warm and friendly, like a helpful peer',
        'playful' => 'playful and cheeky, full of personality, but never cringe',
        'bold' => 'bold and direct with strong opinions, built to stop the scroll',
    ];
    $toneDesc = $tones[$tone] ?? $tones['professional'];
    $system = 'You are Bum Bum\'s ghostwriter, writing for Gen Z solopreneurs and small creators: lash techs, nail artists, barbers, photographers, fitness coaches, freelancers, and online sellers. '
        . 'The user rambled into their phone about their day or their work. Turn the ramble into a ready-to-post content pack. '
        . 'Rules: '
        . '1) hooks: exactly 3 opening lines for a short video, ranked strongest first. Punchy, specific, under 12 words each. No emojis in hooks. Each hook must test a DIFFERENT angle, and you must label each hook with the theme it is testing, a short label like "curiosity gap", "contrarian take", "specific number", or "relatable pain". '
        . '2) script: a conversational 60-second talking-head script, 130 to 160 words, in the creator\'s own voice, based ONLY on what they actually said. '
        . '3) caption: 1 to 3 sentences plus one soft call to action. A couple of emojis are fine here. '
        . '4) hashtags: 5 to 8 niche-relevant hashtags, no generic spam like #love or #instagood. '
        . '5) Never invent facts, offers, prices, results, or credentials the user did not mention. If the ramble is vague, write around the feeling, not fake specifics. '
        . '6) Tone of voice: ' . $toneDesc . '. '
        . '7) NEVER use em dashes or en dashes anywhere in the output. Use commas, colons, or parentheses instead. '
        . 'Output STRICT JSON only, no other text: {"hooks": [{"hook": "...", "theme": "..."}, {"hook": "...", "theme": "..."}, {"hook": "...", "theme": "..."}], "script": "...", "caption": "...", "hashtags": ["#...", "..."]}';
    // Model is overridable from the server config ('ghostwriter_model') so the
    // next Groq retirement is a config tweak, not a code deploy.
    $model = trim((string) (config()['ghostwriter_model'] ?? ''));
    if ($model === '') $model = 'openai/gpt-oss-120b';
    $payload = [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $transcript],
        ],
        'response_format' => ['type' => 'json_object'],
        'temperature' => 0.8,
        'max_tokens' => 1600,
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
        jsonResponse(['error' => 'The ghostwriter lost the plot. Try again in a moment.'], 502);
    }
    if ($code === 429) {
        jsonResponse(['error' => 'The ghostwriter is swamped right now. Try again in a minute.'], 429);
    }
    if ($code < 200 || $code >= 300) {
        jsonResponse(['error' => 'The ghostwriter tripped over its own paws. Try again.'], 502);
    }
    $data = json_decode((string) $raw, true);
    $content = $data['choices'][0]['message']['content'] ?? '';
    // Some models wrap the JSON in markdown fences despite strict instructions.
    if (is_string($content) && preg_match('/```(?:json)?\s*(\{.*\})\s*```/s', $content, $m)) {
        $content = $m[1];
    }
    $pack = is_string($content) ? json_decode($content, true) : null;
    if (!is_array($pack)) {
        jsonResponse(['error' => 'The ghostwriter mumbled. Try again.'], 502);
    }
    $hooks = $pack['hooks'] ?? null;
    $hashtags = $pack['hashtags'] ?? null;
    $valid = is_array($hooks) && count($hooks) === 3
        && is_string($pack['script'] ?? null) && trim((string) $pack['script']) !== ''
        && is_string($pack['caption'] ?? null) && trim((string) $pack['caption']) !== ''
        && is_array($hashtags) && count($hashtags) >= 3;
    if ($valid) {
        foreach ($hooks as $h) {
            if (!is_array($h) || trim((string) ($h['hook'] ?? '')) === '' || trim((string) ($h['theme'] ?? '')) === '') { $valid = false; break; }
        }
    }
    if (!$valid) {
        jsonResponse(['error' => 'The ghostwriter mumbled. Try again.'], 502);
    }
    $pack['hooks'] = array_values(array_map(function ($h) {
        return [
            'hook' => ghostwriter_clean((string) ($h['hook'] ?? '')),
            'theme' => ghostwriter_clean(mb_substr((string) ($h['theme'] ?? ''), 0, 48)),
        ];
    }, array_slice($hooks, 0, 3)));
    $pack['script'] = ghostwriter_clean((string) $pack['script']);
    $pack['caption'] = ghostwriter_clean((string) $pack['caption']);
    $pack['hashtags'] = array_values(array_map('ghostwriter_clean', array_slice($hashtags, 0, 10)));
    return $pack;
}

requirePost();
$input = body();
$action = (string) ($input['action'] ?? 'generate');
requireCsrf($input);
$user = requireUser();
$bill = billingUser($user);
$userId = (int) $user['id'];
ensureGhostwriterSchema();
$pdo = db();

if ($action === 'list') {
    $stmt = $pdo->prepare('SELECT id, title, transcript, pack, created_at FROM ghostwriter_entries WHERE user_id = ? ORDER BY id DESC LIMIT 200');
    $stmt->execute([$userId]);
    $entries = [];
    foreach ($stmt->fetchAll() as $row) {
        $entries[] = ghostwriter_public_row($row, false);
    }
    jsonResponse(['entries' => $entries]);
}

if ($action === 'get') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT id, title, transcript, pack, created_at FROM ghostwriter_entries WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    $row = $stmt->fetch();
    if (!$row) jsonResponse(['error' => 'Pack not found.'], 404);
    jsonResponse(['entry' => ghostwriter_public_row($row, true)]);
}

if ($action === 'delete') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM ghostwriter_entries WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Pack not found.'], 404);
    jsonResponse(['ok' => true]);
}

if ($action === 'generate') {
    $transcript = trim((string) ($input['transcript'] ?? ''));
    if (mb_strlen($transcript) < 20) jsonResponse(['error' => 'Give me a little more to work with, at least a sentence or two.'], 422);
    if (mb_strlen($transcript) > 6000) jsonResponse(['error' => 'That ramble is a bit long. Keep it under a few minutes.'], 422);
    $idempotency = ghostwriter_idempotency($input);
    $tone = strtolower(trim((string) ($input['tone'] ?? 'professional')));
    if (!in_array($tone, ['professional', 'friendly', 'playful', 'bold'], true)) $tone = 'professional';
    // Pre-check so a failed generation never costs an action.
    $pre = usageFor($bill);
    if (empty($pre['unlimited']) && ($pre['remaining'] ?? 0) <= 0) {
        jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $pre], 402);
    }
    $pack = ghostwriter_call_groq($transcript, $tone);
    $count = consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), 'ghostwriter', $idempotency);
    if (!empty($count['limit_reached'])) jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $count], 402);
    $entry = null;
    if (empty($count['duplicate'])) {
        $title = mb_substr(preg_replace('/\s+/', ' ', $transcript), 0, 60);
        $stmt = $pdo->prepare('INSERT INTO ghostwriter_entries (user_id, title, transcript, pack) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $title, $transcript, json_encode($pack, JSON_UNESCAPED_UNICODE)]);
        $entry = ['id' => (int) $pdo->lastInsertId(), 'title' => $title];
    }
    jsonResponse([
        'ok' => true,
        'entry' => $entry,
        'pack' => $pack,
        'usage' => usageFor($bill),
        'duplicate' => (bool) ($count['duplicate'] ?? false),
    ]);
}

jsonResponse(['error' => 'Unknown action.'], 400);
