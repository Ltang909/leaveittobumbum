<?php
// Bum Bum Audiogram API. MP3 in, word-timed captions out, MP4 rendered in the browser.
//
// The browser uploads an MP3; the audio is forwarded to Groq Whisper
// (whisper-large-v3-turbo, verbose_json with word timestamps) and only the
// word timings come back. The audio is never stored and never written to disk.
// Rendering happens entirely client-side (canvas + ffmpeg.wasm); the finished
// MP4 downloads straight from the browser.
//
// Auth: signed-in session + CSRF (same as the other tool endpoints).
// Server config: 'groq_api_key' in leaveittobumbum-config.php
// (free key from https://console.groq.com/keys).
// Metering: one action covers one finished render. Transcription itself is
// free; the browser reports a successful render via action=complete, which is
// where the action is consumed. The usage pre-check means a failed or
// over-limit render never costs an action; the idempotency key means a
// retried report never costs two.
//
//   POST /api/tools/audiogram.php  action=transcribe  audio=<mp3 file>
//   -> { "ok": true, "words": [{"w": "...", "start": 0.0, "end": 0.4}], "duration": 12.3 }
//   POST /api/tools/audiogram.php  action=status
//   -> { "ok": true, "usage": {...} }
//   POST /api/tools/audiogram.php  action=complete  idempotencyKey=<uuid>
//   -> { "ok": true, "usage": {...} }
require dirname(__DIR__) . '/_bootstrap.php';

function audiogram_idempotency(array $input): string {
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
    if (strlen($key) < 16 || strlen($key) > 128) jsonResponse(['error' => 'Invalid request identifier.'], 422);
    return $key;
}

function audiogram_words(array $data): array {
    $words = [];
    // Groq whisper-large-v3-turbo returns word timestamps in a top-level "words" array.
    $top = $data['words'] ?? null;
    if (is_array($top)) {
        foreach ($top as $w) {
            if (!is_array($w)) continue;
            $text = trim((string) ($w['word'] ?? $w['text'] ?? ''));
            if ($text === '') continue;
            $words[] = [
                'w' => $text,
                'start' => round((float) ($w['start'] ?? 0), 2),
                'end' => round((float) ($w['end'] ?? 0), 2),
            ];
            if (count($words) >= 4000) break;
        }
    }
    if ($words) return $words;
    $segments = $data['segments'] ?? null;
    if (is_array($segments)) {
        foreach ($segments as $seg) {
            $ws = $seg['words'] ?? null;
            if (!is_array($ws)) continue;
            foreach ($ws as $w) {
                $text = trim((string) ($w['word'] ?? ''));
                if ($text === '') continue;
                $words[] = [
                    'w' => $text,
                    'start' => round((float) ($w['start'] ?? 0), 2),
                    'end' => round((float) ($w['end'] ?? 0), 2),
                ];
                if (count($words) >= 4000) break 2;
            }
        }
    }
    // Fallback: some responses only carry segment-level timing.
    if (!$words && is_array($segments)) {
        foreach ($segments as $seg) {
            $text = trim((string) ($seg['text'] ?? ''));
            if ($text === '') continue;
            $words[] = [
                'w' => $text,
                'start' => round((float) ($seg['start'] ?? 0), 2),
                'end' => round((float) ($seg['end'] ?? 0), 2),
            ];
            if (count($words) >= 4000) break;
        }
    }
    return $words;
}

requirePost();
$input = body();
$action = (string) ($input['action'] ?? ($_POST['action'] ?? ''));

if ($action === 'transcribe') {
    requireCsrf($_POST);
    requireUser();
    startSecureSession();
    $today = gmdate('Y-m-d');
    if (($_SESSION['ag_day'] ?? '') !== $today) {
        $_SESSION['ag_day'] = $today;
        $_SESSION['ag_count'] = 0;
    }
    if ((int) ($_SESSION['ag_count'] ?? 0) >= 60) {
        jsonResponse(['error' => 'The audiogram is swamped right now. Try again in a minute.'], 429);
    }
    $key = (string) (config()['groq_api_key'] ?? '');
    if ($key === '' || $key === 'replace_me') {
        jsonResponse(['error' => 'The audiogram is not set up on the server yet.'], 503);
    }
    $audio = $_FILES['audio'] ?? null;
    if (!is_array($audio) || ($audio['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        jsonResponse(['error' => 'No audio file received.'], 422);
    }
    $size = (int) ($audio['size'] ?? 0);
    if ($size <= 0 || $size > 25 * 1024 * 1024) {
        jsonResponse(['error' => 'That MP3 is too chunky. Keep it under 25 MB.'], 413);
    }
    $mime = strtolower((string) ($audio['type'] ?? ''));
    $name = strtolower((string) ($audio['name'] ?? ''));
    $looksAudio = strpos($mime, 'audio/') === 0
        || substr($name, -4) === '.mp3' || substr($name, -4) === '.m4a'
        || substr($name, -4) === '.wav' || substr($name, -5) === '.webm';
    if (!$looksAudio) {
        jsonResponse(['error' => 'That does not look like an MP3.'], 422);
    }
    $ch = curl_init('https://api.groq.com/openai/v1/audio/transcriptions');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 180,
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $key],
        CURLOPT_POSTFIELDS => [
            'file' => new CURLFile($audio['tmp_name'], $mime !== '' ? $mime : 'audio/mpeg', 'clip.mp3'),
            'model' => 'whisper-large-v3-turbo',
            'response_format' => 'verbose_json',
            'timestamp_granularities[]' => 'word',
        ],
    ]);
    $raw = curl_exec($ch);
    $curlErr = curl_error($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($raw === false || $curlErr !== '') {
        error_log('audiogram: whisper curl error: ' . $curlErr);
        jsonResponse(['error' => 'The audiogram ate the tape. Try again.'], 502);
    }
    $data = json_decode((string) $raw, true);
    if ($code !== 200 || !is_array($data)) {
        error_log('audiogram: whisper HTTP ' . $code . ' ' . substr((string) $raw, 0, 200));
        if ($code === 429) {
            jsonResponse(['error' => 'The audiogram is swamped right now. Try again in a minute.'], 429);
        }
        jsonResponse(['error' => 'The audiogram ate the tape. Try again.'], 502);
    }
    $words = audiogram_words($data);
    if (!$words) {
        jsonResponse(['error' => 'The audiogram could not hear any words in that MP3.'], 422);
    }
    $_SESSION['ag_count'] = (int) ($_SESSION['ag_count'] ?? 0) + 1;
    jsonResponse([
        'ok' => true,
        'words' => $words,
        'duration' => round((float) ($data['duration'] ?? 0), 2),
    ]);
}

requireCsrf($input);
$user = requireUser();
$bill = billingUser($user);

if ($action === 'status') {
    jsonResponse(['ok' => true, 'usage' => usageFor($bill)]);
}

if ($action === 'complete') {
    $idempotency = audiogram_idempotency($input);
    // Pre-check so a failed render never costs an action.
    $pre = usageFor($bill);
    if (empty($pre['unlimited']) && ($pre['remaining'] ?? 0) <= 0) {
        jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $pre], 402);
    }
    $count = consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), 'audiogram', $idempotency);
    if (!empty($count['limit_reached'])) jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $count], 402);
    jsonResponse([
        'ok' => true,
        'usage' => usageFor($bill),
        'duplicate' => (bool) ($count['duplicate'] ?? false),
    ]);
}

jsonResponse(['error' => 'Unknown action.'], 400);
