"use client";

import { useEffect, useState } from "react";

type Usage = { unlimited?: boolean; remaining?: number; limit?: number };

export function AuthCta() {
  const [state, setState] = useState<"loading" | "in" | "out">("loading");
  const [usage, setUsage] = useState<Usage | null>(null);

  useEffect(() => {
    fetch("/api/session.php", { credentials: "same-origin" })
      .then((r) => r.json())
      .then((s) => setState(s && s.authenticated ? "in" : "out"))
      .catch(() => setState("out"));
  }, []);

  useEffect(() => {
    if (state !== "in") return;
    fetch("/api/usage.php", { credentials: "same-origin" })
      .then((r) => (r.ok ? r.json() : null))
      .then((d) => {
        if (d && d.usage && (d.usage.unlimited || typeof d.usage.remaining === "number")) setUsage(d.usage);
      })
      .catch(() => {});
    const onUsage = (e: Event) => {
      const u = (e as CustomEvent).detail as Usage | undefined;
      if (u && (u.unlimited || (typeof u.remaining === "number" && typeof u.limit === "number"))) setUsage(u);
    };
    document.addEventListener("bb:usage", onUsage);
    return () => document.removeEventListener("bb:usage", onUsage);
  }, [state]);

  async function signOut() {
    try {
      const s = await fetch("/api/session.php", { credentials: "same-origin" }).then((r) => r.json());
      await fetch("/api/auth.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "logout", csrf: s.csrf }),
      });
    } catch {
      /* fall through to reload */
    }
    window.location.reload();
  }

  if (state === "loading") {
    return (
      <span className="button button-small" aria-hidden="true" style={{ opacity: 0.4 }}>
        …
      </span>
    );
  }
  if (state === "in") {
    const meta = usage
      ? usage.unlimited
        ? "Unlimited actions"
        : typeof usage.remaining === "number" && typeof usage.limit === "number"
          ? `${usage.remaining} of ${usage.limit} left`
          : null
      : null;
    return (
      <span style={{ display: "inline-flex", alignItems: "center", gap: 10 }}>
        <a className="button button-small" href="/account/">
          My workspace{meta ? ` · ${meta}` : ""} <span aria-hidden="true">→</span>
        </a>
        <button type="button" className="text-button" onClick={signOut}>
          Sign out
        </button>
      </span>
    );
  }
  return (
    <a className="button button-small" href="/account/">
      Sign in
    </a>
  );
}
