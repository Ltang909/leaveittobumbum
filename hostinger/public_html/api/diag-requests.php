<?php
// TEMPORARY diagnostic for the operator-request status sync. Admin only.
// Visit while logged in as admin; delete (tombstone) after diagnosis.
require __DIR__ . '/_bootstrap.php';
$user = requireUser();
requireAdmin($user);
ensureCustomRequestTables();
ensureToolRequestTables();
$crs = db()->query('SELECT id, user_id, title, status, queue_id, requested_at, deadline_at, delivered_at FROM custom_requests ORDER BY requested_at DESC LIMIT 20')->fetchAll();
$trs = db()->query('SELECT id, problem, status, is_operator, created_at FROM tool_requests WHERE is_operator = 1 ORDER BY created_at DESC LIMIT 20')->fetchAll();
function cell($v) { return '<td>' . htmlspecialchars((string) ($v ?? '')) . '</td>'; }
header('Content-Type: text/html; charset=utf-8');
echo '<h2>custom_requests (latest 20)</h2><table border="1" cellpadding="6"><tr><th>id</th><th>user_id</th><th>title</th><th>status</th><th>queue_id</th><th>requested_at</th><th>deadline_at</th><th>delivered_at</th></tr>';
foreach ($crs as $r) { echo '<tr>' . cell($r['id']) . cell($r['user_id']) . cell($r['title']) . cell($r['status']) . cell($r['queue_id']) . cell($r['requested_at']) . cell($r['deadline_at']) . cell($r['delivered_at']) . '</tr>'; }
echo '</table><h2>tool_requests mirrors (is_operator=1, latest 20)</h2><table border="1" cellpadding="6"><tr><th>id</th><th>problem</th><th>status</th><th>is_operator</th><th>created_at</th></tr>';
foreach ($trs as $r) { echo '<tr>' . cell($r['id']) . cell(mb_substr((string) $r['problem'], 0, 60)) . cell($r['status']) . cell($r['is_operator']) . cell($r['created_at']) . '</tr>'; }
echo '</table>';
