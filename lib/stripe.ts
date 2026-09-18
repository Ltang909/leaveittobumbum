import Stripe from "stripe";

export function getStripe() {
  const key = process.env.STRIPE_SECRET_KEY;
  if (!key) throw new Error("Stripe is not configured.");
  return new Stripe(key);
}

export const planPrices: Record<string, string | undefined> = {
  helper: process.env.STRIPE_PRICE_HELPER,
  operator: process.env.STRIPE_PRICE_OPERATOR,
};

