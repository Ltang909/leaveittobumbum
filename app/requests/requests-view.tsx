"use client";

import { useEffect, useState } from "react";
import { useRequestTool } from "../components/request-tool";

type RequestStatus = "requested" | "planned" | "building" | "shipped" | "completed" | "cancelled";

type ToolRequest = {
  id: number;
  problem: string;
  outcome: string;
  status: RequestStatus;
  votes: number;
  is_operator: number;
  created_at: string;
};

const STATUS_LABEL: Record<RequestStatus, string> = {
  requested: "Requested",
  planned: "Planned",
  building: "Building",
  shipped: "Shipped",
  completed: "Completed",
  cancelled: "Cancelled",
};

const STATUS_CLASS: Record<RequestStatus, string> = {
  requested: "st-requested",
  planned: "st-planned",
  building: "st-building",
  shipped: "st-shipped",
  completed: "st-shipped",
  cancelled: "st-cancelled",
};

const ALL_STATUSES: RequestStatus[] = ["requested", "planned", "building", "shipped", "completed", "cancelled"];

export default function RequestsView() {
  const [requests, setRequests] = useState<ToolRequest[] | null>(null);
  const [voted, setVoted] = useState<Set<number>>(new Set());
  const [isAdmin, setIsAdmin] = useState(false);
  const [csrf, setCsrf] = useState("");
  const { openRequest, requestModal } = useRequestTool();

  useEffect(() => {
    fetch("/api/tool-requests.php?action=list")
      .then((r) => r.json())
      .then((d) => setRequests(Array.isArray(d.requests) ? d.requests : []))
      .catch(() => setRequests([]));
    fetch("/api/session.php", { credentials: "same-origin" })
      .then((r) => r.json())
      .then((s) => {
        if (s && s.is_admin) setIsAdmin(true);
        if (s && s.csrf) setCsrf(String(s.csrf));
      })
      .catch(() => {});
    try {
      const saved = JSON.parse(localStorage.getItem("bb-voted") || "[]");
      if (Array.isArray(saved)) setVoted(new Set(saved));
    } catch { /* no saved votes */ }
  }, []);

  async function vote(id: number) {
    if (voted.has(id)) return;
    const res = await fetch("/api/tool-requests.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action: "vote", request_id: id }),
    });
    const data = await res.json().catch(() => ({}));
    if (res.ok) {
      setRequests((rs) => (rs || []).map((r) => (r.id === id ? { ...r, votes: data.votes ?? r.votes + 1 } : r)));
      const next = new Set(voted).add(id);
      setVoted(next);
      try { localStorage.setItem("bb-voted", JSON.stringify([...next])); } catch { /* private mode */ }
    }
  }

  async function setStatus(id: number, status: RequestStatus) {
    const res = await fetch("/api/tool-requests.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action: "set_status", request_id: id, status, csrf }),
    });
    const data = await res.json().catch(() => ({}));
    if (res.ok && data.status) {
      setRequests((rs) => (rs || []).map((r) => (r.id === id ? { ...r, status: data.status as RequestStatus } : r)));
    } else {
      alert(data.error || "Could not update the status.");
    }
  }

  return (
    <section className="shell queue-wrap">
      <div className="queue-actions">
        <button className="button" onClick={() => openRequest()}>Request a tool <span aria-hidden="true">↗</span></button>
        <p className="queue-note">Free forever. In a hurry? <a href="/36-hours/">Operator builds yours in 36 hours</a>.</p>
      </div>
      {requests === null ? (
        <p className="lede">Waking up the queue…</p>
      ) : requests.length === 0 ? (
        <div className="queue-empty">
          <h2>Nothing here yet. Suspiciously quiet.</h2>
          <p className="lede">Be the first to tell Bum Bum what should just do itself.</p>
          <button className="button" onClick={() => openRequest()}>Make the first request <span aria-hidden="true">↗</span></button>
        </div>
      ) : (
        <div className="queue-grid">
          {requests.map((r) => (
            <article className="queue-card" key={r.id}>
              <div className="queue-card-top">
                <span className={`status-badge ${STATUS_CLASS[r.status]}`}>{STATUS_LABEL[r.status]}</span>
                <button
                  type="button"
                  className={`vote-btn${voted.has(r.id) ? " voted" : ""}`}
                  onClick={() => vote(r.id)}
                  disabled={voted.has(r.id)}
                  aria-label={voted.has(r.id) ? "You upvoted this" : "Upvote this request"}
                >
                  ▲ {r.votes}
                </button>
              </div>
              <p className="queue-problem">{r.problem}</p>
              <p className="queue-outcome"><strong>Done looks like:</strong> {r.outcome}</p>
              {r.is_operator ? (
                <p className="queue-operator">
                  <span className="operator-badge">Operator request · 36-hour build</span>
                  <span className="queue-opened">Opened {new Date(r.created_at).toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" })}</span>
                </p>
              ) : null}
              {isAdmin && (
                <label className="admin-status">
                  Status
                  <select value={r.status} onChange={(e) => setStatus(r.id, e.target.value as RequestStatus)}>
                    {ALL_STATUSES.map((s) => (
                      <option key={s} value={s}>{STATUS_LABEL[s]}</option>
                    ))}
                  </select>
                </label>
              )}
            </article>
          ))}
        </div>
      )}
      {requestModal}
    </section>
  );
}
