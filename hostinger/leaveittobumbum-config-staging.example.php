<?php
// Staging config for staging.leaveittobumbum.com.
// Lives OUTSIDE the web root: ~/domains/leaveittobumbum.com/leaveittobumbum-config-staging.php
// (the staging site itself lives in public_html/staging/).
// Create it once via Hostinger File Manager. Never commit real keys to git.
return [
    'app_url' => 'https://staging.leaveittobumbum.com',
    'db' => [
        // Same database as production (shared by choice). Copy values from the
        // production leaveittobumbum-config.php in your home directory.
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'replace_me',
        'user' => 'replace_me',
        'password' => 'replace_me',
    ],
    'stripe' => [
        // Stripe TEST mode keys. Get them from https://dashboard.stripe.com/test/apikeys
        // Create test prices for the Helper ($12) and Operator ($49) plans in test mode.
        'secret_key' => 'sk_test_replace_me',
        'publishable_key' => 'pk_test_replace_me',
        'webhook_secret' => 'whsec_test_replace_me',
        'helper_price' => 'price_test_replace_me',
        'operator_price' => 'price_test_replace_me',
        'portal_configuration' => '',
    ],
    'posthog' => [
        'key' => 'phc_replace_me', // may reuse the production project; events are tagged env=staging
        'host' => 'https://us.i.posthog.com',
        'api_key' => 'phx_replace_me',
    ],
    'cron_secret' => 'replace_me', // generate with: openssl rand -hex 24
];
