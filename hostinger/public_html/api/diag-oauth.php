<?php
// This endpoint has been retired.
http_response_code(410);
header('Content-Type: application/json');
echo json_encode(['error' => 'gone']);
