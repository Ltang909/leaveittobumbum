<?php
// Poster to Calendar API.
// The visitor's browser downscales the poster photo and sends it here; the
// image is forwarded to Groq's vision model, which reads the event details
// off the poster. The image is never written to disk or stored anywhere.
//
//   POST /api/tools/poster-to-calendar.php
//     action=parse  image=<data URL>  idempotencyKey=<uuid>
//     todayLocal=<YYYY-MM-DD>  weekdayLocal=<e.g. Wednesday>  tzName=<IANA>
//   -> { "ok": true, "events": [ ... ], "usage": {...} }
//
// Metering: exactly one action per successfully read poster.
// Failed Groq calls never consume an action.
//
// Server config: 'groq_api_key' in leaveittobumbum-config.php
// (free key from https://console.groq.com/keys). Optional 'poster_model'
// overrides the Groq vision model (default meta-llama/llama-4-scout).
require dirname(__DIR__) . '/_bootstrap.php';

function poster_clean(string $s): string {
    // Guardrail: no em dashes, ever.
    $s = str_replace(' — ', ', ', $s);
    $s = str_replace('—', ',', $s);
    $s = str_replace('–', '-', $s);
    return trim($s);
}

function poster_valid_date(string $d): bool {
    if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $d, $m)) return false;
    return checkdate((int) $m[2], (int) $m[3], (int) $m[1]);
}

function poster_valid_time($t): ?string {
    if (!is_string($t)) return null;
    if (!preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $t)) return null;
    return $t;
}

// Turn the model's raw JSON into the event list we hand back. Events missing
// a title or a usable date are dropped; if nothing survives, the read failed.
function poster_clean_events($decoded): array {
    $events = [];
    $raw = is_array($decoded) && is_array($decoded['events'] ?? null) ? $decoded['events'] : [];
    foreach ($raw as $e) {
        if (!is_array($e)) continue;
        $title = poster_clean(mb_substr((string) ($e['title'] ?? ''), 0, 160));
        $date = trim((string) ($e['date'] ?? ''));
        if ($title === '' || !poster_valid_date($date)) continue;
        $start = poster_valid_time($e['startTime'] ?? null);
        $end = poster_valid_time($e['endTime'] ?? null);
        $allDay = !empty($e['allDay']) || $start === null;
        if ($allDay) { $start = null; $end = null; }
        $events[] = [
            'title' => $title,
            'date' => $date,
            'startTime' => $start,
            'endTime' => $end,
            'allDay' => $allDay,
            'location' => poster_clean(mb_substr((string) ($e['location'] ?? ''), 0, 160)),
            'address' => poster_clean(mb_substr((string) ($e['address'] ?? ''), 0, 200)),
            'description' => poster_clean(mb_substr((string) ($e['description'] ?? ''), 0, 800)),
            'url' => poster_clean(mb_substr((string) ($e['url'] ?? ''), 0, 300)),
            'dateGuess' => !empty($e['dateGuess']),
            'note' => poster_clean(mb_substr((string) ($e['note'] ?? ''), 0, 240)),
        ];
        if (count($events) >= 6) break;
    }
    return $events;
}

function poster_call_groq(string $imageDataUrl, string $todayLocal, string $weekdayLocal, string $tzName): array {
    $key = (string) (config()['groq_api_key'] ?? '');
    if ($key === '' || $key === 'replace_me') {
        jsonResponse(['error' => 'Poster to Calendar is not set up on the server yet.'], 503);
    }
    $todayLine = $todayLocal !== ''
        ? 'Today is ' . ($weekdayLocal !== '' ? $weekdayLocal . ', ' : '') . $todayLocal . ($tzName !== '' ? ' in ' . $tzName : '') . '.'
        : '';
    $system = 'You are Bum Bum\'s poster reader. You look at photos of event posters, flyers, and screenshots and pull out the events they advertise. '
        . $todayLine . ' '
        . 'Rules: '
        . '1) Find every distinct event on the poster. A lineup, market day, or class schedule can hold several; return each one separately, up to 6. '
        . '2) For each event give: title (the event name, cleaned up, not in all caps), date as YYYY-MM-DD, startTime and endTime as 24-hour HH:MM, allDay (true when the poster gives no time), location (venue name), address (street address if printed), description (1-3 short sentences with the useful extras: price, who is playing or hosting, age limits, what to bring), url (a website or ticket link if printed, else empty), dateGuess, and note. '
        . '3) Resolve every date against today. "This Friday", "Nov 3", "the 21st" all become real dates. Posters advertise future events: if the printed date looks like it already passed this year, use its next occurrence and set dateGuess to true. If the year is printed, trust it. Set dateGuess to true whenever you had to infer or roll a date, false when the full date is printed plainly. '
        . '4) Times: use exactly what the poster says. "Doors 7, show 8" means startTime 20:00 and the doors detail goes in the description. If only a start is given, leave endTime null; never invent an end time. If a range is given (7-10pm), fill both. '
        . '5) For a recurring event ("every Friday night"), return the NEXT single occurrence and put the recurrence in the note field, for example "Poster says every Friday; this is the next one." '
        . '6) Read only what is on the poster. Never invent a venue, address, price, or time that is not there; leave the field empty instead. '
        . '7) NEVER use em dashes or en dashes anywhere. '
        . 'Output STRICT JSON only, no other text: {"events": [{"title": "...", "date": "YYYY-MM-DD", "startTime": "HH:MM" or null, "endTime": "HH:MM" or null, "allDay": false, "location": "...", "address": "...", "description": "...", "url": "...", "dateGuess": false, "note": "..."}]}';
    $model = trim((string) (config()['poster_model'] ?? ''));
    if ($model === '') $model = 'meta-llama/llama-4-scout-17b-16e-instruct';
    $payload = [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => [
                ['type' => 'text', 'text' => 'Read this event poster and return the events on it as JSON.'],
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
        jsonResponse(['error' => 'Poster to Calendar lost the plot. Try again in a moment.'], 502);
    }
    if ($code === 429) {
        jsonResponse(['error' => 'Poster to Calendar is swamped right now. Try again in a minute.'], 429);
    }
    if ($code < 200 || $code >= 300) {
        // TEMP DIAGNOSTIC (staging only): surface Groq's own error so the
        // failing vision call can be identified from a smoke test.
        if (isStagingHost()) {
            jsonResponse(['error' => 'Poster to Calendar tripped over its own paws. Try again.', 'groq_status' => $code, 'groq_body' => mb_substr((string) $raw, 0, 600)], 502);
        }
        jsonResponse(['error' => 'Poster to Calendar tripped over its own paws. Try again.'], 502);
    }
    $data = json_decode((string) $raw, true);
    $content = $data['choices'][0]['message']['content'] ?? '';
    if (is_string($content) && preg_match('/```(?:json)?\s*(\{.*\})\s*```/s', $content, $m)) {
        $content = $m[1];
    }
    if (is_string($content) && strpos(trim($content), '{') !== 0) {
        // Some models add a sentence before the JSON; grab the first object.
        $start = strpos($content, '{');
        $end = strrpos($content, '}');
        if ($start !== false && $end !== false && $end > $start) {
            $content = substr($content, $start, $end - $start + 1);
        }
    }
    $decoded = is_string($content) ? json_decode($content, true) : null;
    $events = poster_clean_events($decoded);
    if (count($events) === 0) {
        jsonResponse(['error' => 'Bum Bum could not find an event on that poster. Try a clearer photo, taken straight on.'], 422);
    }
    return $events;
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
$todayLocal = poster_valid_date(trim((string) ($input['todayLocal'] ?? ''))) ? trim((string) $input['todayLocal']) : '';
$weekdayLocal = poster_clean(mb_substr((string) ($input['weekdayLocal'] ?? ''), 0, 20));
$tzName = poster_clean(mb_substr((string) ($input['tzName'] ?? ''), 0, 64));

$pre = subjectUsage($subject);
if (empty($pre['unlimited']) && ($pre['remaining'] ?? 0) <= 0) {
    limitReachedResponse($subject, $pre);
}
$events = poster_call_groq($image, $todayLocal, $weekdayLocal, $tzName);
$count = consumeSubjectAction($subject, 'poster-to-calendar', 'poster-read-' . $key);
if (!empty($count['limit_reached'])) limitReachedResponse($subject, $count);
jsonResponse([
    'ok' => true,
    'events' => $events,
    'usage' => subjectUsage($subject),
    'duplicate' => (bool) ($count['duplicate'] ?? false),
]);
