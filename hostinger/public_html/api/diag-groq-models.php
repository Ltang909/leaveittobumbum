<?php
// Tombstone: temporary Groq model diag endpoint, retired 2026-09-30.
http_response_code(410);
header('Content-Type: application/json');
echo json_encode(['error' => 'Gone.']);
