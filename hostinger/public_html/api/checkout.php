<?php
require __DIR__ . '/_bootstrap.php';
requirePost();
// Pricing reset (2026-11-17): the Operator subscription sale is retired.
// Every tool is free with no limits, and the only paid offer (the 36-hour
// Promise, $49 one-time per request) is arranged when a request is scoped,
// not through this checkout. This endpoint stays in place so stale clients
// get a clear answer instead of a Stripe session.
jsonResponse(['error' => 'Checkout is closed. Every tool is free now, and the 36-hour Promise ($49 one-time per request) is arranged when you send a request.'], 410);
