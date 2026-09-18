# Leave It to Bum Bum

Initial production website and product shell for leaveittobumbum.com.

## Pricing model

- Free: 75 completed actions each month
- Helper: $12 per month for 1,500 actions
- Operator: $49 per month for 6,000 actions and one scoped tool request each month
- Paid overage: $6 per extra 1,000 actions, offered after clear usage warnings

An action is one completed useful result, regardless of which tool produced it. This keeps metering predictable across the whole toolbox.

## Production setup

Copy `.env.example` to `.env.local` for local development. In production, add the same values through the host's environment settings. Never commit secret values.

Stripe should have two recurring monthly Prices. Add their IDs as `STRIPE_PRICE_HELPER` and `STRIPE_PRICE_OPERATOR`. Register `/api/stripe/webhook` for checkout and subscription lifecycle events. The billing portal endpoint resolves the customer from the completed Checkout Session instead of trusting a customer ID supplied by the browser.

Tool requests are delivered through Resend. Verify `leaveittobumbum.com`, then add the API key and destination email.

## Development

```bash
npm install
npm run dev
```

Before launch, place the supplied Bum Bum portrait at `public/bum-bum.png`. A temporary fallback is visible if the asset is missing.

