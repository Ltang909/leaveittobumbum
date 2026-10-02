"use client";

import { useEffect, useState } from "react";

export function AuthCta() {
  const [state, setState] = useState<"loading" | "in" | "out">("loading");

  useEffect(() => {
    fetch("/api/session.php", { credentials: "same-origin" })
      .then((r) => r.json())
      .then((s) => setState(s && s.authenticated ? "in" : "out"))
      .catch(() => setState("out"));
  }, []);

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
    return (
      <span style={{ display: "inline-flex", alignItems: "center", gap: 10 }}>
        <a className="button button-small" href="/account/">
          My workspace <span aria-hidden="true">→</span>
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
