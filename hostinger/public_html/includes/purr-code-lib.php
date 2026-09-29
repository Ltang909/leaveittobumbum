<?php
// Purr Code shared helpers: tracked short links and scan logging.
// Requires api/_bootstrap.php (for db()).
// All functions degrade gracefully when the purrcode tables do not exist yet.

function purrcode_owner(array $subject): array {
    if ($subject['kind'] === 'user') return ['user', (string) ($subject['user']['id'] ?? '')];
    if ($subject['kind'] === 'guest') return ['guest', (string) ($subject['guest_id'] ?? '')];
    return ['', ''];
}

function purrcode_tables_ready(): bool {
    static $ready = null;
    if ($ready !== null) return $ready;
    try {
        db()->query('SELECT id FROM purrcode_links LIMIT 1');
        $ready = true;
    } catch (Throwable $e) {
        $ready = false;
    }
    return $ready;
}

function purrcode_short_url(string $code): string {
    $base = rtrim((string) (config()['app_url'] ?? ''), '/');
    if ($base === '') {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $base = $scheme . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }
    return $base . '/tools/purr-code/?go=' . $code;
}

function purrcode_mint_code(): string {
    $alpha = 'abcdefghjkmnpqrstuvwxyz23456789';
    $n = strlen($alpha);
    for ($i = 0; $i < 20; $i++) {
        $code = '';
        for ($j = 0; $j < 8; $j++) $code .= $alpha[random_int(0, $n - 1)];
        $st = db()->prepare('SELECT id FROM purrcode_links WHERE code = ?');
        $st->execute([$code]);
        if (!$st->fetch()) return $code;
    }
    jsonResponse(['error' => 'Could not mint a short code. Try again.'], 500);
}

function purrcode_create_link(array $subject, string $target): array {
    [$kind, $oid] = purrcode_owner($subject);
    $code = purrcode_mint_code();
    $st = db()->prepare('INSERT INTO purrcode_links (code, target_url, owner_kind, owner_id) VALUES (?, ?, ?, ?)');
    $st->execute([$code, $target, $kind, $oid]);
    return ['code' => $code, 'target' => $target, 'shortUrl' => purrcode_short_url($code)];
}

function purrcode_get_link(string $code): ?array {
    $code = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $code));
    if ($code === '' || strlen($code) > 16 || !purrcode_tables_ready()) return null;
    $st = db()->prepare('SELECT * FROM purrcode_links WHERE code = ?');
    $st->execute([$code]);
    $row = $st->fetch();
    return $row ?: null;
}

function purrcode_log_scan(int $linkId): void {
    // Scans must never break the redirect, and we never store raw IPs.
    try {
        $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
        $hash = hash('sha256', $ip . '|' . gmdate('Y-m-d') . '|' . $linkId . '|purrcode');
        $ua = mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 200);
        $st = db()->prepare('INSERT INTO purrcode_scans (link_id, ip_hash, ua) VALUES (?, ?, ?)');
        $st->execute([$linkId, $hash, $ua]);
    } catch (Throwable $e) { /* ignore */
    }
}

function purrcode_my_links(array $subject): array {
    [$kind, $oid] = purrcode_owner($subject);
    if ($kind === '' || $oid === '' || !purrcode_tables_ready()) return [];
    try {
        $st = db()->prepare('SELECT l.code, l.target_url, l.created_at, COUNT(s.id) AS scans FROM purrcode_links l LEFT JOIN purrcode_scans s ON s.link_id = l.id WHERE l.owner_kind = ? AND l.owner_id = ? GROUP BY l.id ORDER BY l.created_at DESC LIMIT 50');
        $st->execute([$kind, $oid]);
        $rows = $st->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
    foreach ($rows as &$r) {
        $r['shortUrl'] = purrcode_short_url($r['code']);
        $r['scans'] = (int) $r['scans'];
    }
    return $rows;
}

// Handles /tools/purr-code/?go=CODE. Call at the top of index.php before output.
function purrcode_handle_redirect(): void {
    if (!isset($_GET['go'])) return;
    $link = purrcode_get_link((string) $_GET['go']);
    if (!$link) {
        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
        echo 'Unknown QR code.';
        exit;
    }
    purrcode_log_scan((int) $link['id']);
    header('Location: ' . $link['target_url'], true, 302);
    exit;
}
