<?php
require __DIR__ . '/_bootstrap.php';
$user = requireUser();
jsonResponse(['plan' => $user['plan'], 'status' => $user['subscription_status'], 'usage' => usageFor($user)]);
