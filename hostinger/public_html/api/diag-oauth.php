<?php
declare(strict_types=1);
// TEMPORARY diagnostic: reports which OAuth providers the server config
// enables. Returns booleans only, never secrets. Tombstone after use.
require __DIR__ . '/_bootstrap.php';
header('Content-Type: application/json');
echo json_encode([
    'host' => $_SERVER['HTTP_HOST'] ?? '',
    'staging' => isStagingHost(),
    'google' => oauthEnabled('google'),
    'linkedin' => oauthEnabled('linkedin'),
]);
