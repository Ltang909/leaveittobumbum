<?php
// Meal Scanner API.
// The visitor's browser downscales the meal photo and sends it here; the
// image is forwarded to Groq's vision model, which identifies the food and
// estimates calories and macros. The image is never written to disk or
// stored anywhere. Estimates are approximations by design: every item
// carries a plausible calorie range, never a fake-precise single truth.
//
//   POST /api/tools/meal-scanner.php
//     action=parse  image=<data URL>  idempotencyKey=<uuid>
//   -> { "ok": true, "meal": {...}, "usage": {...} }
//
// Metering: exactly one action per successfully scanned meal.
// Failed calls never consume an action.
//
// Server config: 'groq_api_key' in leaveittobumbum-config.php
// (free key from https://console.groq.com/keys). Optional 'meal_model'
// overrides the Groq vision model (default qwen/qwen3.8-27b, the vision
// model this account serves; llama-4-scout 404s, verified 2026-10-21).
require dirname(__DIR__) . '/_bootstrap.php';

function meal_clean(string $s): string {
    // Guardrail: no em dashes, ever.
    $s = str_replace(' — ', ', ', $s);
    $s = str_replace('—', ',', $s);
    $s = str_replace('–', '-', $s);
    return trim($s);
}

function meal_num($v, int $max): int {
    if (!is_numeric($v)) return 0;
    $n = (int) round((float) $v);
    if ($n < 0) return 0;
    return min($n, $max);
}

// Turn the model's raw JSON into the meal we hand back. Items missing a
// name are dropped; calorie ranges are forced to bracket the estimate, and
// a missing range is derived from the estimate.
function meal_clean_result($decoded): array {
    $out = ['mealName' => '', 'items' => [], 'notes' => []];
    if (!is_array($decoded)) return $out;
    $out['mealName'] = meal_clean(mb_substr((string) ($decoded['mealName'] ?? ''), 0, 120));
    $raw = is_array($decoded['items'] ?? null) ? $decoded['items'] : [];
    foreach ($raw as $it) {
        if (!is_array($it)) continue;
        $name = meal_clean(mb_substr((string) ($it['name'] ?? ''), 0, 120));
        if ($name === '') continue;
        $cal = meal_num($it['calories'] ?? null, 4000);
        $low = meal_num($it['caloriesLow'] ?? null, 4000);
        $high = meal_num($it['caloriesHigh'] ?? null, 4000);
        if ($low === 0 && $high === 0 && $cal > 0) {
            $low = (int) round($cal * 0.8);
            $high = (int) round($cal * 1.25);
        }
        if ($low > $cal) $low = $cal;
        if ($high < $cal) $high = $cal;
        $out['items'][] = [
            'name' => $name,
            'portion' => meal_clean(mb_substr((string) ($it['portion'] ?? ''), 0, 80)),
            'calories' => $cal,
            'caloriesLow' => $low,
            'caloriesHigh' => $high,
            'protein' => meal_num($it['protein'] ?? null, 400),
            'carbs' => meal_num($it['carbs'] ?? null, 600),
            'fat' => meal_num($it['fat'] ?? null, 400),
            'uncertain' => !empty($it['uncertain']),
        ];
        if (count($out['items']) >= 12) break;
    }
    $notes = is_array($decoded['notes'] ?? null) ? $decoded['notes'] : [];
    foreach ($notes as $n) {
        $n = meal_clean(mb_substr((string) $n, 0, 200));
        if ($n === '') continue;
        $out['notes'][] = $n;
        if (count($out['notes']) >= 3) break;
    }
    return $out;
}

function meal_call_groq(string $imageDataUrl): array {
    $key = (string) (config()['groq_api_key'] ?? '');
    if ($key === '' || $key === 'replace_me') {
        jsonResponse(['error' => 'Meal Scanner is not set up on the server yet.'], 503);
    }
    $system = 'You are Bum Bum\'s meal reader. You look at photos of meals, plates, bowls, snacks, and drinks and estimate their calories and macros for busy people who want a fast approximation, not a lab result. '
        . 'Rules: '
        . '1) Identify every distinct food and drink item in the photo. For each give: name (plain, the way a person would say it), portion (an everyday description with approximate grams, like "about 1 cup (150 g)" or "2 slices"), calories (your best single estimate), caloriesLow and caloriesHigh (the honest plausible range; widen it for fried food, mixed dishes, sauces, and anything whose portion is hard to judge), protein, carbs, and fat in grams (best estimates), and uncertain (true when the item is genuinely hard to eyeball). '
        . '2) Estimate the portion you can actually see, not a standard serving. A heaped plate is not a serving size. '
        . '3) mealName: a short name for the whole meal, like "Burger and fries" or "Salmon rice bowl". '
        . '4) notes: up to 3 short watch-outs a fast scanner would miss, like hidden cooking oil, sugary drinks, or dressing. Facts only; no diet advice, no medical claims, no guilt. '
        . '5) If the photo shows no food or drink at all, return {"mealName": "", "items": [], "notes": [], "noFood": true}. '
        . '6) NEVER use em dashes or en dashes anywhere. '
        . 'Output STRICT JSON only, no other text: {"mealName": "...", "items": [{"name": "...", "portion": "...", "calories": 0, "caloriesLow": 0, "caloriesHigh": 0, "protein": 0, "carbs": 0, "fat": 0, "uncertain": false}], "notes": ["..."]}';
    $model = trim((string) (config()['meal_model'] ?? ''));
    if ($model === '') $model = 'qwen/qwen3.8-27b';
    $payload = [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => [
                ['type' => 'text', 'text' => 'Read this meal photo and return the items and nutrition estimates as JSON.'],
                ['type' => 'image_url', 'image_url' => ['url' => $imageDataUrl]],
            ]],
        ],
        'temperature' => 0.2,
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
        jsonResponse(['error' => 'Meal Scanner lost the plot. Try again in a moment.'], 502);
    }
    if ($code === 429) {
        jsonResponse(['error' => 'Meal Scanner is swamped right now. Try again in a minute.'], 429);
    }
    if ($code < 200 || $code >= 300) {
        jsonResponse(['error' => 'Meal Scanner tripped over its own paws. Try again.'], 502);
    }
    $data = json_decode((string) $raw, true);
    $content = $data['choices'][0]['message']['content'] ?? '';
    if (is_string($content) && preg_match('/```(?:json)?\s*(\{.*\})\s*```/s', $content, $m)) {
        $content = $m[1];
    }
    if (is_string($content) && strpos(trim($content), '{') !== 0) {
        $start = strpos($content, '{');
        $end = strrpos($content, '}');
        if ($start !== false && $end !== false && $end > $start) {
            $content = substr($content, $start, $end - $start + 1);
        }
    }
    $decoded = is_string($content) ? json_decode($content, true) : null;
    if (is_array($decoded) && !empty($decoded['noFood'])) {
        jsonResponse(['error' => 'Bum Bum could not find any food in that photo.', 'code' => 'no_food'], 422);
    }
    $meal = meal_clean_result($decoded);
    if (count($meal['items']) === 0) {
        jsonResponse(['error' => 'Bum Bum could not find any food in that photo. Try a closer photo of the plate.', 'code' => 'no_food'], 422);
    }
    return $meal;
}

requirePost();
$input = body();
$action = (string) ($input['action'] ?? 'parse');
requireCsrf($input);
$subject = requireSubject();

if ($action !== 'parse') jsonResponse(['error' => 'Unknown action.'], 400);

$image = (string) ($input['image'] ?? '');
if (!preg_match('#^data:image/(jpeg|png|webp);base64,#', $image)) {
    jsonResponse(['error' => 'That file is not an image Bum Bum can read. Try a JPG, PNG, or WebP photo.'], 422);
}
$b64 = substr($image, strpos($image, ',') + 1);
$decodedSize = (int) (strlen($b64) * 3 / 4);
if ($decodedSize > 6 * 1024 * 1024) {
    jsonResponse(['error' => 'That photo is too large to read. Try a smaller one.'], 422);
}
if (base64_decode($b64, true) === false) {
    jsonResponse(['error' => 'That image arrived scrambled. Try uploading it once more.'], 422);
}

$key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
if (strlen($key) < 16 || strlen($key) > 128) {
    jsonResponse(['error' => 'Invalid request identifier.'], 422);
}

$pre = subjectUsage($subject);
if (empty($pre['unlimited']) && ($pre['remaining'] ?? 0) <= 0) {
    limitReachedResponse($subject, $pre);
}
$meal = meal_call_groq($image);
$count = consumeSubjectAction($subject, 'meal-scanner', 'meal-scan-' . $key);
if (!empty($count['limit_reached'])) limitReachedResponse($subject, $count);
jsonResponse([
    'ok' => true,
    'meal' => $meal,
    'usage' => subjectUsage($subject),
    'duplicate' => (bool) ($count['duplicate'] ?? false),
]);
