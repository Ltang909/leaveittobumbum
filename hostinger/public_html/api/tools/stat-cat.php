<?php
// Bum Bum Stat Cat API. Connect Google (Analytics 4 + Search Console,
// read-only), pick a property and a site, get a traffic digest: sessions,
// top pages, top search queries, clicks/impressions, plus a plain-English
// summary written by Stat Cat.
//
// Auth: signed-in Bum Bum account + CSRF. Tokens are stored encrypted,
// per user. Connecting and picking sites is free; each generated digest
// costs 1 action (failed digests never cost an action; the idempotency key
// means a retried digest never costs two).
//
//   POST /api/tools/stat-cat.php  action=status
//   POST /api/tools/stat-cat.php  action=properties | action=sites
//   POST /api/tools/stat-cat.php  action=save  ga4_property_id=<id>  sc_site_url=<url>
//   POST /api/tools/stat-cat.php  action=digest  idempotencyKey=<uuid>
//   POST /api/tools/stat-cat.php  action=digests | action=get id=<n> | action=delete id=<n>
//   POST /api/tools/stat-cat.php  action=disconnect
require dirname(__DIR__) . '/_bootstrap.php';
require dirname(__DIR__, 2) . '/includes/stat-cat-lib.php';

requirePost();
$input = body();
$action = (string) ($input['action'] ?? 'status');
requireCsrf($input);
[$user, $bill] = statcat_require_user();
$userId = (int) $user['id'];
$subject = statcat_user_subject($user, $bill);
statcat_ensure_schema();

function statcat_status_payload(int $userId): array {
    $row = statcat_token_row($userId);
    return [
        'oauth_configured' => statcat_oauth_configured(),
        'connected' => $row !== null,
        'ga4_property_id' => $row ? (string) ($row['ga4_property_id'] ?? '') : '',
        'ga4_property_name' => $row ? (string) ($row['ga4_property_name'] ?? '') : '',
        'sc_site_url' => $row ? (string) ($row['sc_site_url'] ?? '') : '',
        'setup_complete' => $row !== null && ((string) ($row['ga4_property_id'] ?? '') !== '' || (string) ($row['sc_site_url'] ?? '') !== ''),
    ];
}

if ($action === 'status') {
    jsonResponse(statcat_status_payload($userId) + ['usage' => subjectUsage($subject)]);
}

if ($action === 'properties' || $action === 'sites') {
    $token = statcat_access_token($userId);
    if ($token === null) jsonResponse(['error' => 'Google is not connected. Connect Google first.'], 400);
    $res = $action === 'properties' ? statcat_list_ga4_properties($token) : statcat_list_sc_sites($token);
    if (isset($res['error'])) jsonResponse(['error' => $res['error']], 502);
    jsonResponse($res + statcat_status_payload($userId));
}

if ($action === 'save') {
    $ga4Id = trim((string) ($input['ga4_property_id'] ?? ''));
    $scSite = trim((string) ($input['sc_site_url'] ?? ''));
    if ($ga4Id === '' && $scSite === '') jsonResponse(['error' => 'Pick at least one: an Analytics property or a Search Console site.'], 422);
    $token = statcat_access_token($userId);
    if ($token === null) jsonResponse(['error' => 'Google is not connected. Connect Google first.'], 400);
    // Validate the picks against what Google actually lists for this account.
    $ga4Name = '';
    if ($ga4Id !== '') {
        $list = statcat_list_ga4_properties($token);
        if (isset($list['error'])) jsonResponse(['error' => $list['error']], 502);
        $ok = false;
        foreach ($list['properties'] as $p) {
            if ($p['id'] === $ga4Id || statcat_ga4_property_number($p['id']) === statcat_ga4_property_number($ga4Id)) {
                $ok = true; $ga4Id = $p['id']; $ga4Name = $p['name'];
                break;
            }
        }
        if (!$ok) jsonResponse(['error' => 'That Analytics property is not on this Google account.'], 422);
    }
    if ($scSite !== '') {
        $list = statcat_list_sc_sites($token);
        if (isset($list['error'])) jsonResponse(['error' => $list['error']], 502);
        $ok = false;
        foreach ($list['sites'] as $s) {
            if ($s['siteUrl'] === $scSite) { $ok = true; break; }
        }
        if (!$ok) jsonResponse(['error' => 'That Search Console site is not on this Google account.'], 422);
    }
    statcat_save_selection($userId, $ga4Id !== '' ? $ga4Id : null, $ga4Name !== '' ? $ga4Name : null, $scSite !== '' ? $scSite : null);
    jsonResponse(['ok' => true] + statcat_status_payload($userId));
}

if ($action === 'digest') {
    $idempotency = statcat_idempotency($input);
    $row = statcat_token_row($userId);
    if (!$row) jsonResponse(['error' => 'Google is not connected. Connect Google first.'], 400);
    $ga4Id = (string) ($row['ga4_property_id'] ?? '');
    $scSite = (string) ($row['sc_site_url'] ?? '');
    if ($ga4Id === '' && $scSite === '') jsonResponse(['error' => 'Pick an Analytics property or a Search Console site first.'], 422);
    // Pre-check so a failed digest never costs an action.
    $pre = subjectUsage($subject);
    if (empty($pre['unlimited']) && ($pre['remaining'] ?? 0) <= 0) {
        limitReachedResponse($subject, $pre);
    }
    $token = statcat_access_token($userId);
    if ($token === null) jsonResponse(['error' => 'Google needs reconnecting. Disconnect and connect Google again.'], 400);
    $built = statcat_build_digest($token, $ga4Id !== '' ? $ga4Id : null, (string) ($row['ga4_property_name'] ?? ''), $scSite !== '' ? $scSite : null);
    if (isset($built['error'])) jsonResponse(['error' => $built['error']], 502);
    $summary = statcat_summarize($built['digest']);
    $count = consumeSubjectAction($subject, STATCAT_TOOL_KEY, $idempotency);
    if (!empty($count['limit_reached'])) limitReachedResponse($subject, $count);
    $digestId = null;
    if (empty($count['duplicate'])) {
        $stmt = db()->prepare('INSERT INTO statcat_digests (user_id, env, summary, data_json) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, statcat_env(), $summary, json_encode($built['digest'], JSON_UNESCAPED_SLASHES)]);
        $digestId = (int) db()->lastInsertId();
    }
    jsonResponse([
        'ok' => true,
        'digest_id' => $digestId,
        'digest' => $built['digest'],
        'summary' => $summary,
        'usage' => subjectUsage($subject),
        'duplicate' => (bool) ($count['duplicate'] ?? false),
    ]);
}

if ($action === 'digests') {
    $stmt = db()->prepare('SELECT id, summary, created_at FROM statcat_digests WHERE user_id = ? AND env = ? ORDER BY id DESC LIMIT 50');
    $stmt->execute([$userId, statcat_env()]);
    $rows = [];
    foreach ($stmt->fetchAll() as $r) {
        $rows[] = [
            'id' => (int) $r['id'],
            'created_at' => $r['created_at'],
            'preview' => mb_substr((string) $r['summary'], 0, 140),
        ];
    }
    jsonResponse(['digests' => $rows]);
}

if ($action === 'get') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = db()->prepare('SELECT id, summary, data_json, created_at FROM statcat_digests WHERE id = ? AND user_id = ? AND env = ?');
    $stmt->execute([$id, $userId, statcat_env()]);
    $row = $stmt->fetch();
    if (!$row) jsonResponse(['error' => 'Digest not found.'], 404);
    jsonResponse([
        'digest' => [
            'id' => (int) $row['id'],
            'created_at' => $row['created_at'],
            'summary' => $row['summary'],
            'data' => json_decode((string) $row['data_json'], true) ?: null,
        ],
    ]);
}

if ($action === 'delete') {
    $id = (int) ($input['id'] ?? 0);
    $stmt = db()->prepare('DELETE FROM statcat_digests WHERE id = ? AND user_id = ? AND env = ?');
    $stmt->execute([$id, $userId, statcat_env()]);
    if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Digest not found.'], 404);
    jsonResponse(['ok' => true]);
}

if ($action === 'disconnect') {
    statcat_delete_tokens($userId);
    jsonResponse(['ok' => true] + statcat_status_payload($userId));
}

jsonResponse(['error' => 'Unknown action.'], 400);
