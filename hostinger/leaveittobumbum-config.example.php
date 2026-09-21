<?php
return [
    'app_url' => 'https://leaveittobumbum.com',
    'db' => [
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'replace_me',
        'user' => 'replace_me',
        'password' => 'replace_me',
    ],
    'stripe' => [
        'secret_key' => 'rk_live_replace_me',
        'publishable_key' => 'pk_live_replace_me',
        'webhook_secret' => 'whsec_replace_me',
        'helper_price' => 'price_1UH7nkAknPcpAXyTptBNB3mx',
        'operator_price' => 'price_1UH7nlAknPcpAXyT3oW2EFKX',
        'portal_configuration' => '',
    ],
    'posthog' => [
        'key' => 'phc_replace_me', // client-side project key (safe to expose in page HTML)
        'host' => 'https://us.i.posthog.com',
        'api_key' => 'phx_replace_me', // server-side personal API key, keep secret
    ],
    'cron_secret' => 'replace_me', // shared secret for /api/cron-check-requests.php
    'groq_api_key' => 'replace_me', // server-side Groq key for Notes transcription on iPhone (free: https://console.groq.com/keys)
];
