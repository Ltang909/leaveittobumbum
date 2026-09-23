<?php
// Gone: the Profit Peek tool was retired on 2026-09-23.
http_response_code(410);
header('Content-Type: application/json');
echo json_encode(['error' => 'This tool has been retired.']);
