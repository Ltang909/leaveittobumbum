# Hostinger PHP and MySQL backend

This directory adds account, billing, and usage infrastructure without moving the site away from Hostinger.

## Deploy

1. Import `schema.sql` into the Hostinger MySQL database using phpMyAdmin.
2. Copy `leaveittobumbum-config.example.php` to `leaveittobumbum-config.php`, fill in the values, and upload it one directory above `public_html`. Never commit or place this file inside `public_html`.
3. Upload the contents of `hostinger/public_html` into the live `public_html` folder.
4. Create the Stripe webhook endpoint `https://leaveittobumbum.com/api/webhook.php` for `checkout.session.completed` and `customer.subscription.created`, `.updated`, and `.deleted`.
5. Put the webhook signing secret into the private configuration file.
6. Enable the Stripe customer portal and optionally set its configuration ID.

Use a Stripe restricted key with only the permissions required for Checkout Sessions, Customers, Subscriptions, and Billing Portal Sessions.

## Usage contract

Every real tool endpoint authenticates the user and creates a unique idempotency key for a successful result. It calls `consumeAction()` in the same server-side workflow that returns the finished result. It never trusts a usage count sent by the browser.

`consumeAction()` locks the monthly counter, checks the plan limit, records an immutable ledger entry, and increments the counter. Repeated requests with the same idempotency key never double-count. Failed tool runs do not consume an action.

## Security

- Secure, HTTP-only, SameSite sessions
- CSRF validation on state changes
- Password hashing through PHP's recommended algorithm
- Login throttling by email and IP hash
- Signed, idempotent Stripe webhooks
- Database and Stripe credentials stored outside the web root
