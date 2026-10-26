<?php
// Gone: 360 Spin was retired.
http_response_code(410);
header('Content-Type: application/json');
echo json_encode(['error' => '360 Spin has been retired.']);
