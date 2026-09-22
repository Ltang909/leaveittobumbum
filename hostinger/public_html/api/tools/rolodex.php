<?php
// Gone: the Rolodex API moved to /api/tools/purrsuit.php when Rolodex was renamed to Purrsuit.
http_response_code(410);
header('Content-Type: application/json');
echo json_encode(['error' => 'This endpoint moved to /api/tools/purrsuit.php.']);
