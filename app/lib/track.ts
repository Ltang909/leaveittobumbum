// PostHog for the static marketing pages. The PHP pages track through the
// bbTrack() helper in includes/analytics.php; these pages have no PHP, so
// events go straight to the same /capture/ endpoint the server-side
// posthogCapture() uses. The public project key comes from /api/session.php
// (it is the same key printed into every PHP page). Until it is known,
// events are dropped silently, exactly like bbTrack() with no key configured.

type SessionLike = {
  posthog?: { key: string; host: string } | null;
  guest?: { id: string } | null;
  user?: { id: number | string } | null;
};

let phKey = "";
let phHost = "https://us.i.posthog.com";
let distinctId = "";

function anonId(): string {
  try {
    let id = window.localStorage.getItem("bb_anon_id");
    if (!id) {
      id = window.crypto && "randomUUID" in window.crypto
        ? window.crypto.randomUUID()
        : "anon-" + Date.now().toString(36) + "-" + Math.random().toString(36).slice(2, 12);
      window.localStorage.setItem("bb_anon_id", id);
    }
    return id;
  } catch {
    return "anon";
  }
}

export function initTracking(session: SessionLike | null): void {
  if (session?.posthog?.key) {
    phKey = session.posthog.key;
    if (session.posthog.host) phHost = session.posthog.host.replace(/\/$/, "");
  }
  if (session?.guest?.id) distinctId = session.guest.id;
  else if (session?.user?.id != null) distinctId = "user-" + session.user.id;
}

export function bbTrack(event: string, props: Record<string, unknown> = {}): void {
  if (typeof window === "undefined" || !phKey) return;
  const properties: Record<string, unknown> = {
    ...props,
    env: window.location.hostname.startsWith("staging.") ? "staging" : "production",
    $current_url: window.location.href,
  };
  try {
    void fetch(phHost + "/capture/", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        api_key: phKey,
        event,
        distinct_id: distinctId || anonId(),
        properties,
      }),
      keepalive: true,
    }).catch(() => {});
  } catch {
    // Tracking never breaks the page.
  }
}
