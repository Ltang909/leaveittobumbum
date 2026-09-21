<?php
require __DIR__ . '/_bootstrap.php';
$user = currentUser();
jsonResponse(['authenticated' => (bool) $user, 'user' => $user, 'usage' => $user ? usageFor(billingUser($user)) : null, 'csrf' => csrfToken()]);
