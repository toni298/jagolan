# Alerting and SLOs

## Principles

- Alert on **symptoms** (users affected) rather than causes (a CPU spike).
- Every alert must be **actionable**: if there's nothing to do, it's not an
  alert — make it a dashboard.
- Each alert links to a runbook with concrete steps.
- Tune aggressively: alerts that fire on noise get muted, and then real ones
  are missed.

## SLOs

- **SLI** — a measured indicator (e.g., % of requests < 300ms, success rate).
- **SLO** — the target for an SLI (e.g., 99.9% success over 30 days).
- **Error budget** — the allowed failure within the SLO; spend it consciously
  (ship features) and protect it when burning too fast.

## Burn-rate alerting

- Page on fast error-budget burn (something is actively breaking).
- Use slower/ticket-level alerts for gradual burn.
- Severity should map to user impact and budget burn, not internal noise.
