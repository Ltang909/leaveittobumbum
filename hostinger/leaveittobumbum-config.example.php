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
    'guest_secret' => 'replace_me', // signs the guest cookie for no-signup tool use; generate with: openssl rand -hex 32 (use a DIFFERENT value on staging and production)
    'groq_api_key' => 'replace_me', // server-side Groq key for Notes transcription on iPhone (free: https://console.groq.com/keys)
    // OAuth sign-in ("Continue with Google / LinkedIn"). The account page
    // shows each button only when its id + secret are both set.
    'google_client_id' => 'replace_me', // Google Cloud Console -> APIs & Services -> Credentials
    'google_client_secret' => 'replace_me',
    'linkedin_client_id' => 'replace_me', // LinkedIn Developers -> your app -> Auth tab
    'linkedin_client_secret' => 'replace_me',
];
