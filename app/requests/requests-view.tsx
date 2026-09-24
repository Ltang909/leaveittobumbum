"use client";

import { useEffect, useState } from "react";
import { useRequestTool } from "../components/request-tool";

type ToolRequest = {
  id: number;
  problem: string;
  outcome: string;
  status: "requested" | "planned" | "building" | "shipped";
  votes: number;
  created_at: string;
};

const STATUS_LABEL: Record<ToolRequest["status"], string> = {
  requested: "Requested",
  planned: "Planned",
  building: "Building",
  shipped: "Shipped",
};

const STATUS_CLASS: Record<ToolRequest["status"], string> = {
  requested: "st-requested",
  planned: "st-planned",
  building: "st-building",
  shipped: "st-shipped",
};

export default function RequestsView() {
  const [requests, setRequests] = useState<ToolRequest[] | null>(null);
  const [voted, setVoted] = useState<Set<number>>(new Set());
  const { openRequest, requestModal } = useRequestTool();

  useEffect(() => {
    fetch("/api/tool-requests.php?action=list")
      .then((r) => r.json())
      .then((d) => setRequests(Array.isArray(d.requests) ? d.requests : []))
      .catch(() => setRequests([]));
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
            </article>
          ))}
        </div>
      )}
      {requestModal}
    </section>
  );
}
