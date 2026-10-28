<?php
// Day Log API.
// End-of-day voice log: the visitor answers six prompts by voice or typing,
// Bum Bum structures the words into jot notes, scores yesterday's planned
// list against today's report, files the day, and emails the jot notes.
//
//   POST /api/tools/day-log.php
//     action=transcribe  audio=<data URL>            -> { "ok": true, "text": "..." }
//     action=context     todayLocal=<YYYY-MM-DD>     -> { "ok": true, "yesterday": {...}|null, "today": {...}|null }
//     action=history                                 -> { "ok": true, "logs": [ ... ] }
//     action=save        answers={...} todayLocal=<YYYY-MM-DD> tzName=<IANA> idempotencyKey=<uuid>
//                        -> { "ok": true, "structured": {...}, "reflection": [...], "email_sent": bool, "usage": {...} }
//
// Storage rule (the product promise): we keep the words, never the voice.
// Audio is transcribed and discarded; only text is stored, in day_logs.
// Metering: one action per day filed (first save of the day). Transcription
// and same-day re-files are free.
// Accounts only: the log is a longitudinal record, so it needs a user.
//
// Server config: 'groq_api_key' (Whisper + structuring). Optional
// 'daylog_model' overrides the structuring model (default
// openai/gpt-oss-120b, chosen for the reflection matching judgment).
require dirname(__DIR__) . '/_bootstrap.php';

function dl_clean(string $s): string {
    // Guardrail: no em dashes, ever.
    $s = str_replace(' — ', ', ', $s);
    $s = str_replace('—', ',', $s);
    $s = str_replace('–', '-', $s);
    return trim($s);
}

function dl_valid_date(string $d): bool {
    if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $d, $m)) return false;
    return checkdate((int) $m[2], (int) $m[3], (int) $m[1]);
}

function dl_ensure_table(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS day_logs (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT UNSIGNED NOT NULL,
        log_date DATE NOT NULL,
        answers MEDIUMTEXT NOT NULL,
        structured MEDIUMTEXT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY uq_user_date (user_id, log_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function dl_fetch_log(int $userId, string $date): ?array {
    $stmt = db()->prepare('SELECT log_date, answers, structured FROM day_logs WHERE user_id = ? AND log_date = ?');
    $stmt->execute([$userId, $date]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function dl_latest_before(int $userId, string $date): ?array {
    $stmt = db()->prepare('SELECT log_date, answers, structured FROM day_logs WHERE user_id = ? AND log_date < ? ORDER BY log_date DESC LIMIT 1');
    $stmt->execute([$userId, $date]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function dl_structured(?array $row): ?array {
    if (!$row || empty($row['structured'])) return null;
    $d = json_decode((string) $row['structured'], true);
    return is_array($d) ? $d : null;
}

function dl_groq_json(string $system, string $userText, int $maxTokens = 6000): ?array {
    $cfg = config();
    $key = (string) ($cfg['groq_api_key'] ?? '');
    if ($key === '') return null;
    $model = (string) ($cfg['daylog_model'] ?? 'openai/gpt-oss-120b');
    $payload = [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $userText],
        ],
        'temperature' => 0.3,
        'max_tokens' => $maxTokens,
    ];
    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $key],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 90,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $raw = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($raw === false || $code < 200 || $code >= 300) return null;
    $data = json_decode((string) $raw, true);
    $content = $data['choices'][0]['message']['content'] ?? '';
    if (!is_string($content) || $content === '') return null;
    if (preg_match('/```(?:json)?\s*(\{.*\})\s*```/s', $content, $m)) $content = $m[1];
    if (strpos(trim($content), '{') !== 0) {
        $start = strpos($content, '{');
        $end = strrpos($content, '}');
        if ($start !== false && $end !== false && $end > $start) $content = substr($content, $start, $end - $start + 1);
    }
    $decoded = json_decode(trim($content), true);
    return is_array($decoded) ? $decoded : null;
}

function dl_clean_list($v, int $cap = 12, int $len = 300): array {
    $out = [];
    if (!is_array($v)) return $out;
    foreach ($v as $item) {
        if (!is_string($item)) continue;
        $s = dl_clean(mb_substr($item, 0, $len));
        if ($s !== '') $out[] = $s;
        if (count($out) >= $cap) break;
    }
    return $out;
}

// Structure the day's single free-form log into jot notes and score
// yesterday's plan. The reflection is rebuilt server-side from yesterday's
// actual list so the model can only assign statuses, never invent items.
function dl_structure(string $text, array $yesterdayPlan): ?array {
    $system = 'You turn a person\'s end-of-day voice note into jot notes for their day log. '
        . 'The note is ONE unstructured ramble covering their whole day in any order: what they did, what they set out to do, challenges, ideas for beating them, plans for tomorrow, lessons. Sorting it is your job. '
        . 'Output STRICT JSON only, no other text: {"done": [], "missed": [], "challenges": [], "overcome": [], "tomorrow": [], "learnings": [], "reflection": [{"item": "...", "status": "done|partial|not_mentioned"}]}. '
        . 'Jot notes: short fragments, the person\'s own words tightened, first person kept. Never invent anything they did not say. If they said nothing for a section, leave it empty. '
        . 'done: what they got done today. missed: what they set out to do that did not happen (only if they said so). '
        . 'challenges: problems they named. overcome: their ideas for beating the challenges. '
        . 'tomorrow: the items they named for tomorrow, 3 to 5, in their words. learnings: what they said they learned. '
        . 'reflection: YESTERDAY\'S PLAN is given in the input. For each planned item, judge from TODAY\'S LOG only: "done" if the log clearly says it happened, "partial" if partly done or in progress, "not_mentioned" whenever the log does not clearly cover it. Never mark anything done from the plan itself. Echo each planned item word for word in "item". If the plan is empty, reflection is []. '
        . 'No em dashes anywhere.';
    $parts = [];
    $parts[] = "YESTERDAY'S PLAN:\n" . (count($yesterdayPlan) ? implode("\n", array_map(fn($s) => '- ' . $s, $yesterdayPlan)) : '(none)');
    $parts[] = "TODAY'S LOG:\n" . $text;
    $decoded = dl_groq_json($system, implode("\n\n", $parts));
    if ($decoded === null) return null;
    $structured = [
        'done' => dl_clean_list($decoded['done'] ?? []),
        'missed' => dl_clean_list($decoded['missed'] ?? []),
        'challenges' => dl_clean_list($decoded['challenges'] ?? []),
        'overcome' => dl_clean_list($decoded['overcome'] ?? []),
        'tomorrow' => dl_clean_list($decoded['tomorrow'] ?? [], 5),
        'learnings' => dl_clean_list($decoded['learnings'] ?? []),
    ];
    // Rebuild reflection from yesterday's plan, matching model statuses by text.
    $statusByItem = [];
    foreach ((array) ($decoded['reflection'] ?? []) as $r) {
        if (!is_array($r)) continue;
        $item = dl_clean((string) ($r['item'] ?? ''));
        $status = (string) ($r['status'] ?? '');
        if ($item !== '' && in_array($status, ['done', 'partial', 'not_mentioned'], true)) {
            $statusByItem[mb_strtolower($item)] = $status;
        }
    }
    $reflection = [];
    foreach ($yesterdayPlan as $item) {
        $reflection[] = ['item' => $item, 'status' => $statusByItem[mb_strtolower($item)] ?? 'not_mentioned'];
    }
    $structured['reflection'] = $reflection;
    return $structured;
}

function dl_email_body(array $structured, string $dateLabel): string {
    $lines = ['Your day, filed: ' . $dateLabel, ''];
    $section = function (string $title, array $items) use (&$lines) {
        if (!count($items)) return;
        $lines[] = $title;
        foreach ($items as $i) $lines[] = '- ' . $i;
        $lines[] = '';
    };
    $refl = $structured['reflection'] ?? [];
    if (count($refl)) {
        $lines[] = 'YESTERDAY\'S LIST, SCORED';
        foreach ($refl as $r) {
            $tag = $r['status'] === 'done' ? 'done' : ($r['status'] === 'partial' ? 'partial' : 'not mentioned');
            $lines[] = '- [' . $tag . '] ' . $r['item'];
        }
        $lines[] = '';
    }
    $section('DONE TODAY', $structured['done'] ?? []);
    $section('SET OUT BUT DID NOT HAPPEN', $structured['missed'] ?? []);
    $section('CHALLENGES', $structured['challenges'] ?? []);
    $section('BEATING THEM', $structured['overcome'] ?? []);
    $section('TOMORROW\'S TOP LIST', $structured['tomorrow'] ?? []);
    $section('LEARNINGS', $structured['learnings'] ?? []);
    $lines[] = 'Filed with Day Log on Leave It To Bum Bum.';
    $lines[] = 'We keep your words, never your voice: recordings are transcribed, then discarded.';
    return implode("\n", $lines);
}

requirePost();
$input = body();
$action = (string) ($input['action'] ?? '');
requireCsrf($input);
$user = currentUser();
if (!$user) {
    jsonResponse(['error' => 'Day Log lives in your account, so it can score yesterday against today. Sign in or create a free account to file your day.', 'code' => 'auth'], 401);
}
$subject = requireSubject();
$userId = (int) $user['id'];

try {
    dl_ensure_table();
} catch (Throwable $e) {
    jsonResponse(['error' => 'Day Log storage is not ready yet. Try again in a bit.'], 500);
}

if ($action === 'context' || $action === 'history') {
    if ($action === 'context') {
        $today = trim((string) ($input['todayLocal'] ?? ''));
        if (!dl_valid_date($today)) $today = gmdate('Y-m-d');
        $prev = dl_latest_before($userId, $today);
        $prevStructured = dl_structured($prev);
        $todayRow = dl_fetch_log($userId, $today);
        jsonResponse([
            'ok' => true,
            'yesterday' => $prev ? [
                'date' => $prev['log_date'],
                'tomorrow' => $prevStructured['tomorrow'] ?? [],
            ] : null,
            'today' => $todayRow ? ['date' => $todayRow['log_date'], 'structured' => dl_structured($todayRow)] : null,
        ]);
    }
    $stmt = db()->prepare('SELECT log_date, structured FROM day_logs WHERE user_id = ? ORDER BY log_date DESC LIMIT 30');
    $stmt->execute([$userId]);
    $logs = [];
    foreach ($stmt->fetchAll() as $row) {
        $s = dl_structured($row);
        if ($s === null) continue;
        $logs[] = ['date' => $row['log_date'], 'structured' => $s];
    }
    jsonResponse(['ok' => true, 'logs' => $logs]);
}

function dl_ensure_summaries(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS day_log_summaries (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT UNSIGNED NOT NULL,
        range_key VARCHAR(8) NOT NULL,
        window_start DATE NOT NULL,
        window_end DATE NOT NULL,
        content_hash CHAR(40) NOT NULL,
        content MEDIUMTEXT NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_user_range (user_id, range_key, created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function dl_window_logs(int $userId, string $start, string $end): array {
    $stmt = db()->prepare('SELECT log_date, structured FROM day_logs WHERE user_id = ? AND log_date >= ? AND log_date <= ? ORDER BY log_date ASC');
    $stmt->execute([$userId, $start, $end]);
    $out = [];
    foreach ($stmt->fetchAll() as $row) {
        $s = dl_structured($row);
        if ($s !== null) $out[] = ['date' => $row['log_date'], 'structured' => $s];
    }
    return $out;
}

function dl_tallies(array $logs): array {
    $t = ['days' => count($logs), 'done_total' => 0, 'scored_total' => 0, 'scored_done' => 0, 'scored_partial' => 0, 'scored_not' => 0];
    foreach ($logs as $l) {
        $s = $l['structured'];
        $t['done_total'] += count($s['done'] ?? []);
        foreach (($s['reflection'] ?? []) as $r) {
            $t['scored_total']++;
            if (($r['status'] ?? '') === 'done') $t['scored_done']++;
            elseif (($r['status'] ?? '') === 'partial') $t['scored_partial']++;
            else $t['scored_not']++;
        }
    }
    return $t;
}

if ($action === 'summaries') {
    $out = [];
    try {
        dl_ensure_summaries();
        $stmt = db()->prepare('SELECT range_key, window_start, window_end, content FROM day_log_summaries WHERE user_id = ? ORDER BY created_at DESC LIMIT 6');
        $stmt->execute([$userId]);
        $seen = [];
        foreach ($stmt->fetchAll() as $row) {
            if (isset($seen[$row['range_key']])) continue;
            $seen[$row['range_key']] = true;
            $c = json_decode((string) $row['content'], true);
            if (!is_array($c)) continue;
            $out[] = ['range' => $row['range_key'], 'window_start' => $row['window_start'], 'window_end' => $row['window_end'], 'summary' => $c['summary'] ?? [], 'tallies' => $c['tallies'] ?? []];
        }
    } catch (Throwable $e) { /* summaries are best-effort */ }
    jsonResponse(['ok' => true, 'summaries' => $out]);
}

if ($action === 'summarize') {
    $range = (string) ($input['range'] ?? '');
    if (!in_array($range, ['week', 'month'], true)) jsonResponse(['error' => 'Unknown range.'], 400);
    $today = trim((string) ($input['todayLocal'] ?? ''));
    if (!dl_valid_date($today)) jsonResponse(['error' => 'Invalid date.'], 422);
    $start = date('Y-m-d', strtotime($today . ' -' . ($range === 'week' ? 6 : 29) . ' days'));
    $logs = dl_window_logs($userId, $start, $today);
    if (count($logs) < 2) {
        jsonResponse(['error' => 'A ' . $range . ' summary needs at least 2 filed days in the window. You have ' . count($logs) . ' so far.'], 422);
    }
    $tallies = dl_tallies($logs);
    $hashSrc = '';
    foreach ($logs as $l) $hashSrc .= $l['date'] . '|' . json_encode($l['structured']) . ';';
    $hash = sha1($hashSrc);
    try { dl_ensure_summaries(); } catch (Throwable $e) { /* cache is best-effort */ }
    try {
        $stmt = db()->prepare('SELECT content FROM day_log_summaries WHERE user_id = ? AND range_key = ? AND content_hash = ? ORDER BY created_at DESC LIMIT 1');
        $stmt->execute([$userId, $range, $hash]);
        $hit = $stmt->fetch();
        if ($hit) {
            $c = json_decode((string) $hit['content'], true);
            jsonResponse(['ok' => true, 'range' => $range, 'window_start' => $start, 'window_end' => $today, 'summary' => $c['summary'] ?? [], 'tallies' => $c['tallies'] ?? $tallies, 'cached' => true]);
        }
    } catch (Throwable $e) { /* fall through to generation */ }
    $pre = subjectUsage($subject);
    if (empty($pre['unlimited']) && ($pre['remaining'] ?? 0) <= 0) {
        limitReachedResponse($subject, $pre);
    }
    $digest = [];
    foreach ($logs as $l) {
        $s = $l['structured'];
        $line = '## ' . $l['date'];
        foreach (['done' => 'Done', 'missed' => 'Missed', 'challenges' => 'Challenges', 'overcome' => 'Beating them', 'tomorrow' => 'Planned next', 'learnings' => 'Learnings'] as $k => $label) {
            if (!empty($s[$k])) $line .= "\n" . $label . ': ' . implode('; ', array_slice($s[$k], 0, 8));
        }
        $digest[] = $line;
    }
    $digestText = implode("\n\n", $digest);
    if (mb_strlen($digestText) > 55000) $digestText = mb_substr($digestText, -55000);
    $system = 'You summarize a stretch of a person\'s day logs into highlights. '
        . 'Output STRICT JSON only, no other text: {"accomplishments": [], "learnings": [], "growth": [], "themes": []}. '
        . 'Jot notes: short fragments, the person\'s own words tightened. Never invent anything the logs do not support. '
        . 'accomplishments: the concrete things they completed across the window, deduped, biggest first, up to 8. '
        . 'learnings: the distinct lessons, distilled, up to 6. '
        . 'growth: how they changed across the window, early days versus late days: follow-through on their own plans, recurring challenges beaten or still open, consistency or scope shifts, up to 5. If the logs show no clear change, say what stayed consistent instead, plainly. '
        . 'themes: topics and challenges that kept coming up, up to 5. '
        . 'No em dashes anywhere.';
    $decoded = dl_groq_json($system, 'WINDOW: ' . $start . ' to ' . $today . ' (' . $tallies['days'] . " days logged)\n\n" . $digestText, 8000);
    if ($decoded === null) $decoded = dl_groq_json($system, 'WINDOW: ' . $start . ' to ' . $today . ' (' . $tallies['days'] . " days logged)\n\n" . $digestText, 8000);
    if ($decoded === null) {
        jsonResponse(['error' => 'Bum Bum could not build that summary. Try once more.'], 502);
    }
    $summary = [
        'accomplishments' => dl_clean_list($decoded['accomplishments'] ?? [], 8),
        'learnings' => dl_clean_list($decoded['learnings'] ?? [], 6),
        'growth' => dl_clean_list($decoded['growth'] ?? [], 5, 340),
        'themes' => dl_clean_list($decoded['themes'] ?? [], 5),
    ];
    $count = consumeSubjectAction($subject, 'day-log', 'day-log-summary-' . $range . '-' . substr($hash, 0, 16));
    if (!empty($count['limit_reached'])) limitReachedResponse($subject, $count);
    try {
        $stmt = db()->prepare('INSERT INTO day_log_summaries (user_id, range_key, window_start, window_end, content_hash, content) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $range, $start, $today, $hash, json_encode(['summary' => $summary, 'tallies' => $tallies])]);
    } catch (Throwable $e) { /* best effort */ }
    jsonResponse(['ok' => true, 'range' => $range, 'window_start' => $start, 'window_end' => $today, 'summary' => $summary, 'tallies' => $tallies, 'cached' => false, 'usage' => subjectUsage($subject)]);
}

/* --- Extra destinations (Advanced settings): a Notion page per filed day --- */

function dl_ensure_destinations(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS tool_destinations (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT UNSIGNED NOT NULL,
        tool VARCHAR(40) NOT NULL,
        kind VARCHAR(20) NOT NULL,
        config MEDIUMTEXT NOT NULL,
        enabled TINYINT NOT NULL DEFAULT 1,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY uq_user_tool_kind (user_id, tool, kind)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    db()->exec("CREATE TABLE IF NOT EXISTS day_log_notion_pushes (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT UNSIGNED NOT NULL,
        log_date DATE NOT NULL,
        block_ids MEDIUMTEXT NOT NULL,
        pushed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY uq_user_date (user_id, log_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function dl_notion_destination(int $userId): ?array {
    try {
        dl_ensure_destinations();
        $stmt = db()->prepare("SELECT config FROM tool_destinations WHERE user_id = ? AND tool = 'day-log' AND kind = 'notion' AND enabled = 1");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        if (!$row) return null;
        $cfg = json_decode((string) $row['config'], true);
        if (!is_array($cfg) || empty($cfg['token']) || empty($cfg['target_id']) || empty($cfg['mode'])) return null;
        return $cfg;
    } catch (Throwable $e) {
        return null;
    }
}

function dl_notion_page_id(string $raw): ?string {
    $raw = trim($raw);
    if (preg_match('/([0-9a-fA-F]{8})-([0-9a-fA-F]{4})-([0-9a-fA-F]{4})-([0-9a-fA-F]{4})-([0-9a-fA-F]{12})/', $raw, $m)) {
        return strtolower($m[1] . $m[2] . $m[3] . $m[4] . $m[5]);
    }
    if (preg_match('/(?:^|[^0-9a-fA-F])([0-9a-fA-F]{32})(?:[^0-9a-fA-F]|$)/', $raw, $m)) return strtolower($m[1]);
    if (preg_match('/^[0-9a-fA-F-]+$/', $raw)) {
        $hex = preg_replace('/[^0-9a-fA-F]/', '', $raw);
        if (strlen($hex) === 32) return strtolower($hex);
    }
    return null;
}

function dl_notion_call(string $method, string $path, string $token, ?array $body = null): array {
    $ch = curl_init('https://api.notion.com' . $path);
    $headers = ['Authorization: Bearer ' . $token, 'Notion-Version: 2022-06-28', 'Content-Type: application/json'];
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 20,
    ]);
    if ($body !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    $raw = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    $decoded = is_string($raw) ? json_decode($raw, true) : null;
    return ['code' => $code, 'data' => is_array($decoded) ? $decoded : []];
}

function dl_notion_rich(string $text): array {
    return [['type' => 'text', 'text' => ['content' => mb_substr($text, 0, 1900)]]];
}

function dl_notion_blocks(array $s): array {
    $blocks = [];
    $addSection = function (string $label, array $items) use (&$blocks): void {
        if (!$items) return;
        $blocks[] = ['object' => 'block', 'type' => 'heading_3', 'heading_3' => ['rich_text' => dl_notion_rich($label)]];
        foreach ($items as $item) {
            $blocks[] = ['object' => 'block', 'type' => 'bulleted_list_item', 'bulleted_list_item' => ['rich_text' => dl_notion_rich($item)]];
        }
    };
    if (!empty($s['reflection'])) {
        $blocks[] = ['object' => 'block', 'type' => 'heading_3', 'heading_3' => ['rich_text' => dl_notion_rich('Yesterday\'s list, scored')]];
        foreach ($s['reflection'] as $r) {
            $mark = $r['status'] === 'done' ? 'Done' : ($r['status'] === 'partial' ? 'Partial' : 'Not mentioned');
            $blocks[] = ['object' => 'block', 'type' => 'bulleted_list_item', 'bulleted_list_item' => ['rich_text' => dl_notion_rich($mark . ': ' . $r['item'])]];
        }
    }
    $addSection('Done today', $s['done'] ?? []);
    $addSection('Set out but did not happen', $s['missed'] ?? []);
    $addSection('Challenges', $s['challenges'] ?? []);
    $addSection('Beating them', $s['overcome'] ?? []);
    $addSection('Tomorrow\'s top list', $s['tomorrow'] ?? []);
    $addSection('Learnings', $s['learnings'] ?? []);
    return $blocks;
}

function dl_notion_append(string $token, string $pageId, array $blocks): bool {
    foreach (array_chunk($blocks, 90) as $chunk) {
        $res = dl_notion_call('PATCH', '/v1/blocks/' . $pageId . '/children', $token, ['children' => $chunk]);
        if ($res['code'] < 200 || $res['code'] >= 300) return false;
    }
    return true;
}

/* One Notion page per filed day, under the chosen parent page or in the chosen
   database. Re-filing a day rewrites that day's page in place. Best-effort:
   never blocks filing. */
function dl_notion_push(int $userId, string $date, array $structured): array {
    $cfg = dl_notion_destination($userId);
    if ($cfg === null) return ['connected' => false];
    $token = (string) $cfg['token'];
    $title = 'Day Log: ' . date('D M j, Y', strtotime($date));
    $blocks = dl_notion_blocks($structured);

    $existingPage = null;
    try {
        $stmt = db()->prepare('SELECT block_ids FROM day_log_notion_pushes WHERE user_id = ? AND log_date = ?');
        $stmt->execute([$userId, $date]);
        $prev = $stmt->fetch();
        if ($prev) {
            $stored = json_decode((string) $prev['block_ids'], true);
            if (is_array($stored) && !empty($stored['page_id'])) $existingPage = (string) $stored['page_id'];
        }
    } catch (Throwable $e) { /* best effort */ }

    $fail = ['connected' => true, 'ok' => false, 'error' => 'Notion said no. Your log is safe here; check the connection in Advanced settings.'];

    if ($existingPage !== null) {
        // Rewrite in place: clear the page's current blocks, then append fresh ones.
        $list = dl_notion_call('GET', '/v1/blocks/' . $existingPage . '/children?page_size=100', $token);
        if ($list['code'] >= 200 && $list['code'] < 300) {
            foreach (($list['data']['results'] ?? []) as $b) {
                if (!empty($b['id'])) dl_notion_call('DELETE', '/v1/blocks/' . $b['id'], $token);
            }
            if (dl_notion_append($token, $existingPage, $blocks)) {
                return ['connected' => true, 'ok' => true, 'created' => false];
            }
        }
        // Fall through and create a fresh page if the stored one is gone.
    }

    if (($cfg['mode'] ?? '') === 'database') {
        $props = [];
        $titleProp = (string) ($cfg['title_prop'] ?? 'Name');
        $props[$titleProp] = ['title' => dl_notion_rich($title)];
        if (!empty($cfg['date_prop'])) {
            $props[(string) $cfg['date_prop']] = ['date' => ['start' => $date]];
        }
        $payload = ['parent' => ['database_id' => (string) $cfg['target_id']], 'properties' => $props];
    } else {
        $payload = ['parent' => ['page_id' => (string) $cfg['target_id']], 'properties' => ['title' => dl_notion_rich($title)]];
    }
    $first = array_slice($blocks, 0, 90);
    if ($first) $payload['children'] = $first;
    $res = dl_notion_call('POST', '/v1/pages', $token, $payload);
    if ($res['code'] < 200 || $res['code'] >= 300 || empty($res['data']['id'])) return $fail;
    $newPage = (string) $res['data']['id'];
    $rest = array_slice($blocks, 90);
    if ($rest && !dl_notion_append($token, $newPage, $rest)) return $fail;
    try {
        $stmt = db()->prepare('INSERT INTO day_log_notion_pushes (user_id, log_date, block_ids) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE block_ids = VALUES(block_ids)');
        $stmt->execute([$userId, $date, json_encode(['page_id' => $newPage])]);
    } catch (Throwable $e) { /* best effort */ }
    return ['connected' => true, 'ok' => true, 'created' => true];
}

if ($action === 'destination_get') {
    $cfg = dl_notion_destination($userId);
    jsonResponse(['ok' => true, 'notion' => $cfg === null ? null : ['target_id' => $cfg['target_id'], 'mode' => $cfg['mode']]]);
}

if ($action === 'destination_save') {
    $token = trim((string) ($input['token'] ?? ''));
    $pageRaw = (string) ($input['page'] ?? '');
    $targetId = dl_notion_page_id($pageRaw);
    if ($token === '') jsonResponse(['error' => 'Paste your Notion integration token first.'], 422);
    if ($targetId === null) jsonResponse(['error' => 'That link does not carry a Notion ID. Copy the full link to the page or database.'], 422);
    $mode = null;
    $titleProp = null;
    $dateProp = null;
    $check = dl_notion_call('GET', '/v1/pages/' . $targetId, $token);
    if ($check['code'] === 401) jsonResponse(['error' => 'Notion rejected that token. Copy the integration secret again.'], 422);
    if ($check['code'] >= 200 && $check['code'] < 300) {
        $mode = 'page';
    } else {
        $dbCheck = dl_notion_call('GET', '/v1/databases/' . $targetId, $token);
        if ($dbCheck['code'] === 401) jsonResponse(['error' => 'Notion rejected that token. Copy the integration secret again.'], 422);
        if ($dbCheck['code'] >= 200 && $dbCheck['code'] < 300) {
            $mode = 'database';
            foreach (($dbCheck['data']['properties'] ?? []) as $name => $prop) {
                if (($prop['type'] ?? '') === 'title' && $titleProp === null) $titleProp = (string) $name;
                if (($prop['type'] ?? '') === 'date' && $dateProp === null && in_array(strtolower((string) $name), ['date', 'recorded', 'day'], true)) $dateProp = (string) $name;
            }
            if ($titleProp === null) jsonResponse(['error' => 'That database has no title property to name each day with. Pick another target.'], 422);
        } elseif ($dbCheck['code'] === 404) {
            jsonResponse(['error' => 'Notion cannot see that page or database. Open it, use its Share or connections menu, and invite your integration first.'], 422);
        } else {
            jsonResponse(['error' => 'Notion check failed (' . $dbCheck['code'] . '). Try once more.'], 502);
        }
    }
    try {
        dl_ensure_destinations();
        $cfg = ['token' => $token, 'target_id' => $targetId, 'mode' => $mode];
        if ($titleProp !== null) $cfg['title_prop'] = $titleProp;
        if ($dateProp !== null) $cfg['date_prop'] = $dateProp;
        $stmt = db()->prepare("INSERT INTO tool_destinations (user_id, tool, kind, config, enabled) VALUES (?, 'day-log', 'notion', ?, 1) ON DUPLICATE KEY UPDATE config = VALUES(config), enabled = 1");
        $stmt->execute([$userId, json_encode($cfg)]);
    } catch (Throwable $e) {
        jsonResponse(['error' => 'Could not save the connection. Try once more.'], 500);
    }
    jsonResponse(['ok' => true, 'notion' => ['target_id' => $targetId, 'mode' => $mode]]);
}

if ($action === 'destination_disconnect') {
    try {
        dl_ensure_destinations();
        $stmt = db()->prepare("DELETE FROM tool_destinations WHERE user_id = ? AND tool = 'day-log' AND kind = 'notion'");
        $stmt->execute([$userId]);
    } catch (Throwable $e) { /* best effort */ }
    jsonResponse(['ok' => true]);
}


if ($action === 'transcribe') {
    $audio = (string) ($input['audio'] ?? '');
    if (!preg_match('#^data:audio/(webm|ogg|mp3|mpeg|m4a|mp4|wav|x-wav);base64,#', $audio, $mm)) {
        jsonResponse(['error' => 'That recording format will not transcribe. Try again.'], 422);
    }
    $b64 = substr($audio, strpos($audio, ',') + 1);
    if ((int) (strlen($b64) * 3 / 4) > 12 * 1024 * 1024) {
        jsonResponse(['error' => 'That recording is too long to transcribe at once. Keep each answer under about 10 minutes.'], 422);
    }
    $bin = base64_decode($b64, true);
    if ($bin === false || $bin === '') {
        jsonResponse(['error' => 'That recording arrived scrambled. Try again.'], 422);
    }
    $cfg = config();
    $key = (string) ($cfg['groq_api_key'] ?? '');
    if ($key === '') jsonResponse(['error' => 'Transcription is not switched on yet. Type your answer instead.'], 503);
    $ext = ['webm' => 'webm', 'ogg' => 'ogg', 'mp3' => 'mp3', 'mpeg' => 'mp3', 'm4a' => 'm4a', 'mp4' => 'm4a', 'wav' => 'wav', 'x-wav' => 'wav'][$mm[1]] ?? 'webm';
    $tmp = tempnam(sys_get_temp_dir(), 'dl_audio_') . '.' . $ext;
    file_put_contents($tmp, $bin);
    $ch = curl_init('https://api.groq.com/openai/v1/audio/transcriptions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $key],
        CURLOPT_POSTFIELDS => [
            'file' => new CURLFile($tmp, 'audio/' . $ext, 'answer.' . $ext),
            'model' => 'whisper-large-v3-turbo',
            'response_format' => 'json',
        ],
        CURLOPT_TIMEOUT => 120,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $raw = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    @unlink($tmp);
    if ($raw === false || $code < 200 || $code >= 300) {
        jsonResponse(['error' => 'Transcription tripped. Your words are still in the box if you typed any; try recording once more.'], 502);
    }
    $data = json_decode((string) $raw, true);
    jsonResponse(['ok' => true, 'text' => trim((string) ($data['text'] ?? ''))]);
}

if ($action === 'save') {
    $text = trim((string) ($input['text'] ?? ''));
    $text = mb_substr($text, 0, 15000);
    if (mb_strlen($text) < 10) {
        jsonResponse(['error' => 'There is nothing to file yet. Talk or type a little about your day first.'], 422);
    }
    $answers = ['log' => $text];
    $today = trim((string) ($input['todayLocal'] ?? ''));
    if (!dl_valid_date($today)) jsonResponse(['error' => 'Invalid log date.'], 422);
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);

    $existing = dl_fetch_log($userId, $today);
    $isFirstToday = $existing === null;
    if ($isFirstToday) {
        $pre = subjectUsage($subject);
        if (empty($pre['unlimited']) && ($pre['remaining'] ?? 0) <= 0) {
            limitReachedResponse($subject, $pre);
        }
    }
    $prev = dl_latest_before($userId, $today);
    $prevStructured = dl_structured($prev);
    $yesterdayPlan = $prevStructured['tomorrow'] ?? [];

    $structured = dl_structure($text, $yesterdayPlan);
    if ($structured === null) {
        // One retry with a bigger runway before giving up (reasoning models
        // can starve their own JSON, the SOP/Ghostwriter lesson).
        $structured = dl_structure($text, $yesterdayPlan);
    }
    if ($structured === null) {
        jsonResponse(['error' => 'Bum Bum could not structure that. Your words are still on the page; try filing once more.'], 502);
    }

    $stmt = db()->prepare('INSERT INTO day_logs (user_id, log_date, answers, structured) VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE answers = VALUES(answers), structured = VALUES(structured)');
    $stmt->execute([$userId, $today, json_encode($answers), json_encode($structured)]);

    $usage = null;
    if ($isFirstToday) {
        $count = consumeSubjectAction($subject, 'day-log', 'day-log-' . $key);
        if (!empty($count['limit_reached'])) limitReachedResponse($subject, $count);
        $usage = subjectUsage($subject);
    }
    // No instant email: recaps go out on weekday mornings via
    // api/cron-day-log-email.php (Monday's run recaps Friday's log).
    $notion = dl_notion_push($userId, $today, $structured);
    jsonResponse([
        'ok' => true,
        'structured' => $structured,
        'reflection' => $structured['reflection'] ?? [],
        'email_sent' => false,
        'first_today' => $isFirstToday,
        'usage' => $usage,
        'notion' => $notion,
    ]);
}

jsonResponse(['error' => 'Unknown action.'], 400);
