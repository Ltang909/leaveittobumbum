<?php
declare(strict_types=1);

/**
 * Audio transcription for Bum Bum Notes.
 *
 * Bum Bum Notes transcribes live in the browser via SpeechRecognition, which
 * iPhone Safari does not support. On iPhones the Notes page records audio and
 * POSTs it here after the user stops; the audio is forwarded to Groq's
 * Whisper API (free tier, whisper-large-v3-turbo) and only the transcript is
 * returned. The audio is never stored.
 *
 * Auth: signed-in session + CSRF (same as the other tool endpoints).
 * Server config: 'groq_api_key' in leaveittobumbum-config.php
 * (free key from https://console.groq.com/keys).
 * Metering: transcription itself is free; saving the note via
 * /api/tools/notes.php consumes the one metered action.
 *
 *   POST /api/voice-transcribe.php   audio=<file>  language=<bcp47, optional>
 *   -> { "ok": true, "transcript": "..." }
 */

require __DIR__ . '/_bootstrap.php';

requirePost();
requireCsrf($_POST);
requireUser();

startSecureSession();
$today = gmdate('Y-m-d');
if (($_SESSION['vt_day'] ?? '') !== $today) {
    $_SESSION['vt_day'] = $today;
    $_SESSION['vt_count'] = 0;
}
if ((int) ($_SESSION['vt_count'] ?? 0) >= 60) {
    jsonResponse(['error' => 'Transcription is busy right now. Try again in a minute.'], 429);
}

$groqKey = (string) (config()['groq_api_key'] ?? '');
if ($groqKey === '' || $groqKey === 'replace_me') {
    jsonResponse(['error' => 'Transcription is not set up on the server yet.'], 503);
}

$audio = $_FILES['audio'] ?? null;
if (!is_array($audio) || ($audio['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    jsonResponse(['error' => 'No audio file received.'], 422);
}

$size = (int) ($audio['size'] ?? 0);
// Groq free tier caps transcription uploads at 25 MB.
if ($size <= 0 || $size > 25 * 1024 * 1024) {
    jsonResponse(['error' => 'Audio is too long for transcription (25 MB max).'], 413);
}

$mime = strtolower((string) ($audio['type'] ?? ''));
$ext = 'm4a'; // iPhone Safari records audio/mp4
if (strpos($mime, 'webm') !== false) $ext = 'webm';
elseif (strpos($mime, 'ogg') !== false) $ext = 'ogg';
elseif (strpos($mime, 'wav') !== false) $ext = 'wav';
elseif (strpos($mime, 'mpeg') !== false) $ext = 'mp3';

$lang = strtolower((string) ($_POST['language'] ?? 'en'));
if (strpos($lang, 'yue') === 0) $lang = 'zh'; // Whisper has no Cantonese code
$lang = preg_match('/^[a-z]{2}/', $lang) ? substr($lang, 0, 2) : 'en';

$ch = curl_init('https://api.groq.com/openai/v1/audio/transcriptions');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 120,
    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $groqKey],
    CURLOPT_POSTFIELDS => [
        'file' => new CURLFile($audio['tmp_name'], $mime !== '' ? $mime : 'audio/m4a', 'note.' . $ext),
        'model' => 'whisper-large-v3-turbo',
        'language' => $lang,
        'response_format' => 'json',
    ],
]);
$body = curl_exec($ch);
$curlErr = curl_error($ch);
$http = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($body === false) {
    error_log('voice-transcribe: curl error: ' . $curlErr);
    jsonResponse(['error' => 'Transcription service unreachable. Try again.'], 502);
}

$data = json_decode((string) $body, true);
if ($http !== 200 || !is_array($data)) {
    $detail = is_array($data) && isset($data['error']['message']) ? (string) $data['error']['message'] : '';
    error_log('voice-transcribe: groq HTTP ' . $http . ' ' . substr($detail, 0, 200));
    if ($http === 401 || $http === 403) {
        jsonResponse(['error' => 'Transcription key was rejected.'], 502);
    } elseif ($http === 429) {
        jsonResponse(['error' => 'Transcription is busy right now. Try again in a minute.'], 429);
    } else {
        jsonResponse(['error' => 'Transcription failed. Try again.'], 502);
    }
}

$_SESSION['vt_count'] = (int) ($_SESSION['vt_count'] ?? 0) + 1;
$transcript = trim((string) ($data['text'] ?? ''));
jsonResponse(['ok' => true, 'transcript' => $transcript]);
