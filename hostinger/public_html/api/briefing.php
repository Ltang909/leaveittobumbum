<?php
// Retired 2026-10-02: the dashboard briefing no longer mutates records.
// Each item now deep-links into its tool ("Go to record") where the
// tool's own UI provides the correct actions.
http_response_code(410);
header('Content-Type: application/json');
echo json_encode(['ok' => false, 'error' => 'gone']);
