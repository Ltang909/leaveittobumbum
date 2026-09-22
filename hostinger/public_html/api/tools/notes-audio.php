<?php
// Gone: Bum Bum Notes no longer stores audio on the server. Recordings stay
// in the browser for playback and can be downloaded from the Notes page.
http_response_code(410);
header('Content-Type: application/json');
echo json_encode(['error' => 'Audio playback from the server has been removed.']);
