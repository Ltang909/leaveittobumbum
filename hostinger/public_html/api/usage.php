<?php
require __DIR__ . '/_bootstrap.php';
$user = requireUser();
$bill = billingUser($user);
jsonResponse(['plan' => $bill['plan'], 'status' => $bill['subscription_status'], 'usage' => usageFor($bill), 'team_role' => $bill['team_role'] ?? 'owner']);
