import { NextResponse } from "next/server";
import { getStripe, planPrices } from "@/lib/stripe";

export async function POST(request: Request) {
  try {
    const { plan } = await request.json();
    if (plan !== "helper" && plan !== "operator") return NextResponse.json({ error: "Choose a valid plan." }, { status: 400 });
    const price = planPrices[plan];
    if (!price) return NextResponse.json({ error: "Checkout is being connected. Please email hello@leaveittobumbum.com." }, { status: 503 });
    const site = process.env.NEXT_PUBLIC_SITE_URL || new URL(request.url).origin;
    const session = await getStripe().checkout.sessions.create({
      mode: "subscription",
      line_items: [{ price, quantity: 1 }],
      allow_promotion_codes: true,
      billing_address_collection: "auto",
      customer_creation: "always",
      success_url: `${site}/?checkout=success&session_id={CHECKOUT_SESSION_ID}#toolbox`,
      cancel_url: `${site}/#pricing`,
      subscription_data: { metadata: { plan } },
      metadata: { plan },
    });
    return NextResponse.json({ url: session.url });
  } catch (error) {
    console.error("Checkout error", error);
    return NextResponse.json({ error: "Checkout is unavailable right now." }, { status: 500 });
  }
}

