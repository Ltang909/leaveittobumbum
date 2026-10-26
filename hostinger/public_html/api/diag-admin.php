<?php
// Gone: temporary admin diagnostic, retired after use.
http_response_code(410);
header('Content-Type: application/json');
echo json_encode(['error' => 'This diagnostic endpoint has been retired.']);
