<?php
// Bum Bum Hearsay API. An AEO (answer engine optimization) tracker.
//
// The user gives a brand name (plus optional domain) and up to 5 questions
// their customers ask. We ask 3 AI models each question, the way a curious
// customer would, then report back: how often the brand gets mentioned, the
// vibe (sentiment), direct quotes, and which competitors get cited instead.
//
// Models are queried in parallel via curl_multi. A second parallel batch
// asks one fast model to extract brand names from each question's answers,
// so we can list competitors. All mention/sentiment/quote analysis after
// that is plain PHP: case-insensitive matching plus small keyword lists.
// Results are directional, and the UI says so.
//
// Auth: signed-in session + CSRF (same as the other tool endpoints).
// Server config: 'groq_api_key' in leaveittobumbum-config.php
// (free key from https://console.groq.com/keys). Optional 'hearsay_models'
// overrides the comma-separated model list.
// Metering: one action covers one finished scan. The usage pre-check means
// a failed scan never costs the user an action; the idempotency key means
// a retried scan never costs two.
//
//   POST /api/tools/hearsay.php  action=scan  brand=<name>  domain=<opt>
//       questions=[...]  idempotencyKey=<uuid>
//   -> { "ok": true, "entry": {...}, "scan": {...}, "usage": {...} }
require dirname(__DIR__) . '/_bootstrap.php';

function hearsay_models(): array {
    $raw = trim((string) (config()['hearsay_models'] ?? ''));
    $models = $raw === '' ? [] : array_values(array_filter(array_map('trim', explode(',', $raw))));
    if (empty($models)) $models = ['openai/gpt-oss-120b', 'openai/gpt-oss-20b', 'qwen/qwen3.8-27b'];
    return array_slice($models, 0, 4);
}

function hearsay_model_label(string $model): string {
    $labels = [
        'openai/gpt-oss-120b' => 'GPT-OSS 120B',
        'openai/gpt-oss-20b' => 'GPT-OSS 20B',
        'qwen/qwen3.8-27b' => 'Qwen 3.8 27B',
    ];
    return $labels[$model] ?? $model;
}

function hearsay_idempotency(array $input): string {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
    return $key;
}

function hearsay_clean(string $s): string {
    // Guardrail: no em dashes, ever.
    $s = str_replace(' — ', ', ', $s);
    $s = str_replace('—', ',', $s);
    $s = str_replace('–', '-', $s);
    return $s;
}

// Run many Groq calls in parallel. Each job: ['model', 'messages', 'maxTokens'].
// Returns array of text|null in the same order.
function hearsay_groq_parallel(string $key, array $jobs, int $timeout): array {
    $mh = curl_multi_init();
    $handles = [];
    foreach ($jobs as $i => $job) {
        $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: ' . 'Bearer ' . $key],
            CURLOPT_POSTFIELDS => json_encode([
                'model' => $job['model'],
                'messages' => $job['messages'],
                'temperature' => 0.7,
                'max_tokens' => $job['maxTokens'],
            ]),
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => 10,
        ]);
        curl_multi_add_handle($mh, $ch);
        $handles[$i] = $ch;
    }
    $running = 0;
    do {
        $status = curl_multi_exec($mh, $running);
        if ($running) curl_multi_select($mh, 1.0);
    } while ($running && $status === CURLM_OK);
    $out = [];
    foreach ($handles as $i => $ch) {
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $raw = curl_multi_getcontent($ch);
        $text = null;
        if ($code >= 200 && $code < 300 && is_string($raw)) {
            $data = json_decode($raw, true);
            $content = $data['choices'][0]['message']['content'] ?? null;
            if (is_string($content) && trim($content) !== '') $text = $content;
        }
        $out[$i] = $text;
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }
    curl_multi_close($mh);
    ksort($out);
    return array_values($out);
}

function hearsay_variants(string $brand, string $domain): array {
    $variants = [];
    $b = trim($brand);
    if ($b !== '') $variants[] = $b;
    $d = strtolower(trim($domain));
    $d = preg_replace('#^https?://#', '', $d);
    $d = preg_replace('#^www\.#', '', $d);
    $d = preg_replace('#/.*$#', '', $d);
    if ($d !== '') {
        $variants[] = $d;
        $root = preg_replace('#\.(com|net|org|io|co|app|dev|ai|ca|us|uk|biz|info)$#', '', $d);
        if ($root !== '' && $root !== $d) $variants[] = $root;
    }
    // Dedupe case-insensitively, longest first so "acme tools" beats "acme".
    $seen = [];
    $uniq = [];
    foreach ($variants as $v) {
        $l = mb_strtolower($v);
        if (!isset($seen[$l])) { $seen[$l] = true; $uniq[] = $v; }
    }
    usort($uniq, fn($a, $b) => mb_strlen($b) <=> mb_strlen($a));
    return $uniq;
}

function hearsay_mentioned(string $text, array $variants): bool {
    foreach ($variants as $v) {
        if (preg_match('/' . preg_quote($v, '/') . '/i', $text)) return true;
    }
    return false;
}

function hearsay_sentences(string $text): array {
    $parts = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    return array_values(array_filter(array_map('trim', $parts ?: []), fn($s) => mb_strlen($s) > 12));
}

function hearsay_sentiment(string $text, array $variants): array {
    $positive = ['best', 'great', 'excellent', 'love', 'recommend', 'top', 'leading', 'trusted', 'amazing', 'fantastic', 'outstanding', 'popular', 'reliable', 'awesome', 'perfect', 'favorite', 'favourite', 'impressive', 'solid', 'worth', 'innovative', 'helpful'];
    $negative = ['worst', 'terrible', 'awful', 'avoid', 'horrible', 'poor', 'overpriced', 'scam', 'disappointing', 'disappointed', 'weak', 'mediocre', 'unreliable', 'lawsuit', 'complaint', 'refund'];
    $pos = 0; $neg = 0;
    foreach (hearsay_sentences($text) as $s) {
        if (!hearsay_mentioned($s, $variants)) continue;
        $l = mb_strtolower($s);
        foreach ($positive as $w) { if (preg_match('/\b' . preg_quote($w, '/') . '\b/', $l)) $pos++; }
        foreach ($negative as $w) { if (preg_match('/\b' . preg_quote($w, '/') . '\b/', $l)) $neg++; }
    }
    $label = 'neutral';
    if ($pos > 0 && $neg === 0) $label = 'positive';
    elseif ($neg > 0 && $pos === 0) $label = 'negative';
    elseif ($pos > 0 && $neg > 0) $label = 'mixed';
    return ['label' => $label, 'pos' => $pos, 'neg' => $neg];
}

function hearsay_quotes(string $text, array $variants, int $max = 2): array {
    $quotes = [];
    foreach (hearsay_sentences($text) as $s) {
        if (hearsay_mentioned($s, $variants)) {
            $s = hearsay_clean($s);
            if (mb_strlen($s) > 280) $s = mb_substr($s, 0, 277) . '...';
            $quotes[] = $s;
            if (count($quotes) >= $max) break;
        }
    }
    return $quotes;
}

function hearsay_is_own_brand(string $name, array $variants): bool {
    $l = mb_strtolower(trim($name));
    foreach ($variants as $v) {
        $vl = mb_strtolower($v);
        if ($l === $vl) return true;
        if (mb_strlen($vl) >= 4 && (mb_strpos($l, $vl) !== false || mb_strpos($vl, $l) !== false)) return true;
    }
    return false;
}

function ensureHearsaySchema(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS hearsay_scans (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(191) NOT NULL,
        brand VARCHAR(120) NOT NULL,
        scan MEDIUMTEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        KEY idx_user (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function hearsay_public_row(array $row, bool $full): array {
    $out = [
        'id' => (int) $row['id'],
        'title' => $row['title'],
        'brand' => $row['brand'],
        'created_at' => $row['created_at'],
    ];
    $scan = json_decode((string) $row['scan'], true);
    if (!is_array($scan)) $scan = [];
    if ($full) {
        $out['scan'] = $scan;
    } else {
        $summary = $scan['summary'] ?? [];
        $out['preview'] = (string) ($summary['headline'] ?? '');
    }
    return $out;
}

requirePost();
$input = body();
$action = (string) ($input['action'] ?? 'scan');
requireCsrf($input);
$subject = requireSubject();
$isGuest = $subject['kind'] === 'guest';
$userId = $isGuest ? 0 : (int) $subject['user']['id'];
ensureHearsaySchema();
$pdo = db();

if ($action === 'list') {
    if ($isGuest) jsonResponse(['entries' => []]);
    $stmt = $pdo->prepare('SELECT id, title, brand, scan, created_at FROM hearsay_scans WHERE user_id = ? ORDER BY id DESC LIMIT 100');
    $stmt->execute([$userId]);
    $entries = [];
    foreach ($stmt->fetchAll() as $row) $entries[] = hearsay_public_row($row, false);
    jsonResponse(['entries' => $entries]);
}

if ($action === 'get') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT id, title, brand, scan, created_at FROM hearsay_scans WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    $row = $stmt->fetch();
    if (!$row) jsonResponse(['error' => 'Scan not found.'], 404);
    jsonResponse(['entry' => hearsay_public_row($row, true)]);
}

if ($action === 'delete') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM hearsay_scans WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Scan not found.'], 404);
    jsonResponse(['ok' => true]);
}

if ($action === 'scan') {
    $brand = trim((string) ($input['brand'] ?? ''));
    if (mb_strlen($brand) < 2 || mb_strlen($brand) > 60) jsonResponse(['error' => 'Give me a brand name between 2 and 60 characters.'], 422);
    $domain = trim((string) ($input['domain'] ?? ''));
    if ($domain !== '' && mb_strlen($domain) > 120) jsonResponse(['error' => 'That domain looks too long.'], 422);
    $questions = $input['questions'] ?? [];
    if (!is_array($questions)) jsonResponse(['error' => 'Questions must be a list.'], 422);
    $questions = array_values(array_filter(array_map(fn($q) => trim((string) $q), $questions), fn($q) => $q !== ''));
    if (count($questions) < 1) jsonResponse(['error' => 'Ask at least one question your customers would ask.'], 422);
    if (count($questions) > 5) $questions = array_slice($questions, 0, 5);
    foreach ($questions as $q) {
        if (mb_strlen($q) < 10) jsonResponse(['error' => 'Each question should be at least 10 characters. Spell it out like a customer would.'], 422);
        if (mb_strlen($q) > 300) jsonResponse(['error' => 'Keep each question under 300 characters.'], 422);
    }
    $idempotency = hearsay_idempotency($input);
    $key = (string) (config()['groq_api_key'] ?? '');
    if ($key === '' || $key === 'replace_me') {
        jsonResponse(['error' => 'Hearsay is not set up on the server yet.'], 503);
    }
    // Pre-check so a failed scan never costs an action.
    $pre = subjectUsage($subject);
    if (empty($pre['unlimited']) && ($pre['remaining'] ?? 0) <= 0) limitReachedResponse($subject, $pre);

    $models = hearsay_models();
    $variants = hearsay_variants($brand, $domain);
    $answerSystem = 'You are a helpful AI assistant. Answer the user\'s question naturally and concisely, in 2 to 4 sentences, the way you would for any curious customer. If you know relevant brands or businesses, name them. Never mention that you are part of a test.';

    // Batch 1: ask every model every question, all in parallel.
    $jobs = [];
    $jobMap = [];
    foreach ($questions as $qi => $q) {
        foreach ($models as $mi => $m) {
            $jobMap[] = ['q' => $qi, 'm' => $mi];
            $jobs[] = [
                'model' => $m,
                'messages' => [
                    ['role' => 'system', 'content' => $answerSystem],
                    ['role' => 'user', 'content' => $q],
                ],
                'maxTokens' => 600,
            ];
        }
    }
    $texts = hearsay_groq_parallel($key, $jobs, 75);

    $answersByQ = [];
    foreach ($jobMap as $i => $jm) {
        $answersByQ[$jm['q']][$jm['m']] = $texts[$i];
    }
    $anySuccess = false;
    foreach ($answersByQ as $list) {
        foreach ($list as $t) { if ($t !== null) { $anySuccess = true; break 2; } }
    }
    if (!$anySuccess) {
        jsonResponse(['error' => 'The models all stayed quiet. Try again in a moment.'], 502);
    }

    // Batch 2: per question, extract every brand name mentioned across its answers.
    $extractJobs = [];
    $extractMap = [];
    foreach ($questions as $qi => $q) {
        $combined = [];
        foreach (($answersByQ[$qi] ?? []) as $mi => $t) {
            if ($t !== null) $combined[] = '--- Answer from ' . hearsay_model_label($models[$mi]) . " ---\n" . $t;
        }
        if (empty($combined)) continue;
        $extractMap[] = $qi;
        $extractJobs[] = [
            'model' => $models[count($models) - 1],
            'messages' => [
                ['role' => 'system', 'content' => 'List every business, brand, product, or company name mentioned in the text below. Reply with STRICT JSON only: a flat array of names like ["Acme", "Globex"]. No other text. If no brands are mentioned, reply [].'],
                ['role' => 'user', 'content' => 'Question: ' . $q . "\n\n" . implode("\n\n", $combined)],
            ],
            'maxTokens' => 300,
        ];
    }
    $brandsByQ = [];
    if (!empty($extractJobs)) {
        $extractTexts = hearsay_groq_parallel($key, $extractJobs, 60);
        foreach ($extractMap as $i => $qi) {
            $t = $extractTexts[$i];
            $names = [];
            if (is_string($t)) {
                if (preg_match('/```(?:json)?\s*(\[.*\])\s*```/s', $t, $m)) $t = $m[1];
                $decoded = json_decode(trim($t), true);
                if (is_array($decoded)) {
                    foreach ($decoded as $n) {
                        if (is_string($n) && trim($n) !== '') $names[] = trim($n);
                    }
                }
            }
            $brandsByQ[$qi] = $names;
        }
    }

    // Assemble the report.
    $qReports = [];
    $totalMentions = 0; $totalAnswers = 0;
    $sentTotals = ['positive' => 0, 'negative' => 0, 'mixed' => 0, 'neutral' => 0];
    $competitorCounts = [];
    foreach ($questions as $qi => $q) {
        $aReports = [];
        $qMentions = 0; $qAnswers = 0;
        foreach ($models as $mi => $m) {
            $text = $answersByQ[$qi][$mi] ?? null;
            if ($text === null) continue;
            $qAnswers++; $totalAnswers++;
            $mentioned = hearsay_mentioned($text, $variants);
            $sent = $mentioned ? hearsay_sentiment($text, $variants) : ['label' => 'neutral', 'pos' => 0, 'neg' => 0];
            if ($mentioned) { $qMentions++; $totalMentions++; $sentTotals[$sent['label']]++; }
            $aReports[] = [
                'model' => $m,
                'label' => hearsay_model_label($m),
                'mentioned' => $mentioned,
                'sentiment' => $sent['label'],
                'quotes' => $mentioned ? hearsay_quotes($text, $variants) : [],
            ];
        }
        $competitors = [];
        $seenComp = [];
        foreach (($brandsByQ[$qi] ?? []) as $name) {
            if (hearsay_is_own_brand($name, $variants)) continue;
            $l = mb_strtolower($name);
            if (isset($seenComp[$l])) continue;
            $seenComp[$l] = true;
            $competitors[] = hearsay_clean($name);
            if (count($competitors) >= 8) break;
        }
        foreach ($competitors as $c) {
            $l = mb_strtolower($c);
            $competitorCounts[$l] = ['name' => $c, 'count' => ($competitorCounts[$l]['count'] ?? 0) + 1];
        }
        $qReports[] = [
            'question' => hearsay_clean($q),
            'answers' => $aReports,
            'mentions' => $qMentions,
            'answers_count' => $qAnswers,
            'competitors' => $competitors,
        ];
    }

    uasort($competitorCounts, fn($a, $b) => $b['count'] <=> $a['count']);
    $topCompetitors = array_values(array_map(fn($c) => $c['name'], array_slice($competitorCounts, 0, 5)));
    $pct = $totalAnswers > 0 ? (int) round($totalMentions / $totalAnswers * 100) : 0;
    if ($pct >= 67) $verdict = 'loud and clear';
    elseif ($pct >= 34) $verdict = 'getting heard';
    elseif ($pct > 0) $verdict = 'a whisper';
    else $verdict = 'radio silent';
    $headline = $brand . ' is ' . $verdict . ': mentioned in ' . $totalMentions . ' of ' . $totalAnswers . ' AI answers.';

    $scan = [
        'brand' => hearsay_clean($brand),
        'domain' => hearsay_clean($domain),
        'models' => array_map(fn($m) => ['id' => $m, 'label' => hearsay_model_label($m)], $models),
        'questions' => $qReports,
        'summary' => [
            'mentions' => $totalMentions,
            'answers' => $totalAnswers,
            'mention_pct' => $pct,
            'verdict' => $verdict,
            'headline' => $headline,
            'sentiment' => $sentTotals,
            'top_competitors' => $topCompetitors,
            'directional_note' => 'Directional, not gospel: AI answers shift with phrasing, timing, and model updates. Re-run monthly to spot trends.',
        ],
        'scanned_at' => gmdate('Y-m-d H:i:s'),
    ];

    $count = consumeSubjectAction($subject, 'hearsay', $idempotency);
    if (!empty($count['limit_reached'])) limitReachedResponse($subject, $count);
    $entry = null;
    if (empty($count['duplicate']) && !$isGuest) {
        $title = $brand . ' - ' . count($questions) . ' question' . (count($questions) === 1 ? '' : 's');
        $stmt = $pdo->prepare('INSERT INTO hearsay_scans (user_id, title, brand, scan) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $title, $brand, json_encode($scan, JSON_UNESCAPED_UNICODE)]);
        $entry = ['id' => (int) $pdo->lastInsertId(), 'title' => $title, 'brand' => $brand];
    }
    jsonResponse([
        'ok' => true,
        'entry' => $entry,
        'scan' => $scan,
        'usage' => subjectUsage($subject),
        'duplicate' => (bool) ($count['duplicate'] ?? false),
    ]);
}

jsonResponse(['error' => 'Unknown action.'], 400);
