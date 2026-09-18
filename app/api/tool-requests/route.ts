import { NextResponse } from "next/server";

export async function POST(request: Request) {
  try {
    const { name, email, problem, outcome } = await request.json();
    if (![name, email, problem, outcome].every((value) => typeof value === "string" && value.trim())) return NextResponse.json({ error: "Please complete every field." }, { status: 400 });
    if (!/^\S+@\S+\.\S+$/.test(email)) return NextResponse.json({ error: "Enter a valid email." }, { status: 400 });
    const apiKey = process.env.RESEND_API_KEY;
    const to = process.env.TOOL_REQUEST_EMAIL;
    if (!apiKey || !to) return NextResponse.json({ error: "Tool requests are not configured." }, { status: 503 });
    const response = await fetch("https://api.resend.com/emails", {
      method: "POST",
      headers: { Authorization: `Bearer ${apiKey}`, "Content-Type": "application/json" },
      body: JSON.stringify({ from: "Bum Bum <requests@leaveittobumbum.com>", to: [to], reply_to: email, subject: `Tool request from ${name}`, text: `Name: ${name}\nEmail: ${email}\n\nAnnoying task:\n${problem}\n\nDone looks like:\n${outcome}` }),
    });
    if (!response.ok) throw new Error(`Resend returned ${response.status}`);
    return NextResponse.json({ ok: true });
  } catch (error) {
    console.error("Tool request error", error);
    return NextResponse.json({ error: "The request could not be sent." }, { status: 500 });
  }
}

