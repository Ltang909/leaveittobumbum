import { NextResponse } from "next/server";
import { getStripe } from "@/lib/stripe";

export async function POST(request: Request) {
  try {
    const { checkoutSessionId } = await request.json();
    if (typeof checkoutSessionId !== "string" || !checkoutSessionId.startsWith("cs_")) return NextResponse.json({ error: "A valid checkout session is required." }, { status: 400 });
    const checkout = await getStripe().checkout.sessions.retrieve(checkoutSessionId);
    const customerId = typeof checkout.customer === "string" ? checkout.customer : checkout.customer?.id;
    if (!customerId) return NextResponse.json({ error: "No customer was found for this checkout." }, { status: 400 });
    const site = process.env.NEXT_PUBLIC_SITE_URL || new URL(request.url).origin;
    const session = await getStripe().billingPortal.sessions.create({ customer: customerId, return_url: `${site}/`, configuration: process.env.STRIPE_PORTAL_CONFIGURATION_ID || undefined });
    return NextResponse.json({ url: session.url });
  } catch (error) {
    console.error("Portal error", error);
    return NextResponse.json({ error: "Billing settings are unavailable right now." }, { status: 500 });
  }
}

