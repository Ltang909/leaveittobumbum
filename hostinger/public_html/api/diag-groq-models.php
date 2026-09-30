<?php
// TEMPORARY diagnostic: list available Groq chat models using the server key.
// Returns only public model IDs. Tombstone after use.
require __DIR__ . '/_bootstrap.php';
$key = (string) (config()['groq_api_key'] ?? '');
if ($key === '' || $key === 'replace_me') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'no key configured']);
    exit;
}
$ch = curl_init('https://api.groq.com/openai/v1/models');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $key],
    CURLOPT_TIMEOUT => 30,
]);
$raw = curl_exec($ch);
$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$data = json_decode((string) $raw, true);
$ids = [];
foreach (($data['data'] ?? []) as $m) {
    $ids[] = $m['id'] ?? '';
}
sort($ids);
header('Content-Type: application/json');
$test = $_GET['test'] ?? '';
if ($test !== '') {
    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $key],
        CURLOPT_POSTFIELDS => json_encode([
            'model' => $test,
            'messages' => [['role' => 'user', 'content' => 'Reply with exactly: OK']],
            'max_tokens' => 10,
            'temperature' => 0,
        ]),
        CURLOPT_TIMEOUT => 30,
    ]);
    $raw = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $data = json_decode((string) $raw, true);
    echo json_encode([
        'model' => $test,
        'http' => $code,
        'reply' => $data['choices'][0]['message']['content'] ?? null,
        'error' => $data['error']['message'] ?? null,
    ]);
    exit;
}
echo json_encode(['http' => $code, 'models' => array_values(array_filter($ids))]);
