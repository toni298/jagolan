---
name: observability
description: >
  Make systems observable with structured logs, metrics, traces, health checks,
  and actionable alerts. Trigger this skill whenever the user says "logging",
  "metrics", "tracing", "monitoring", "alert", "dashboard", "SLO", "can't tell
  what's happening in prod", or is about to ship a service. Instrument for the
  questions you'll ask during an incident; never alert on noise that humans will
  learn to ignore.
---

# Observability

## Purpose

Ensure you can answer "what is happening and why" in production through logs,
metrics, and traces, and be alerted on real user impact.

## When to use

- Adding logging/metrics/tracing to a service or new feature.
- Defining alerts, dashboards, or SLOs.
- Preparing a system so future incidents are diagnosable.

## Workflow

1. Decide the questions you'll need to answer in an incident; instrument those.
2. Emit **structured logs** with correlation/request IDs and context.
3. Track the key metrics: latency (p50/p95/p99), traffic, errors, saturation.
4. Add **tracing** across service boundaries for request-level diagnosis.
5. Expose health/readiness checks for deploys and load balancers.
6. Alert on user-facing symptoms and SLO burn, not on every internal blip.
7. Make alerts actionable: each links to a runbook and a clear next step.

## Anti-patterns

- Unstructured `console.log` with no IDs, impossible to correlate.
- Logging secrets/PII.
- Alerting on causes/noise (high CPU alone) instead of user impact.
- Dashboards no one reads and alerts everyone mutes.

## Output

- Structured logs, the four golden signals, traces across boundaries, and a
  small set of actionable, symptom-based alerts.

## Reference selection

Read only what is relevant:

- references/logs-metrics-traces.md — the three pillars and when to use each.
- references/alerting-slos.md — actionable alerts and SLOs.

## Shared standards

- ../shared/engineering-principles.md
- ../shared/severity-levels.md
