---
name: incident-response
description: >
  Respond to production incidents: triage, mitigate, communicate, and run a
  blameless postmortem. Trigger this skill whenever the user says "outage",
  "incident", "down", "production is broken", "users affected", "sev", "on-call",
  or reports an active production failure. Prioritize restoring service over
  finding the cause; mitigate first, diagnose fully afterward, and never skip
  the postmortem for a real incident.
---

# Incident Response

## Purpose

Restore service quickly and safely during a production incident, keep
stakeholders informed, and turn the event into durable prevention.

## When to use

- A user-facing system is down, degraded, or losing/corrupting data.
- A security or data-exposure event is suspected.
- After resolution, to run the postmortem.

## Workflow

1. Declare and size the incident (severity, scope, user impact, start time).
2. Assign roles: incident lead, comms, and a person investigating.
3. **Mitigate first** — roll back, feature-flag off, scale, or fail over.
4. Communicate status to stakeholders at a steady cadence.
5. Once stable, capture a timeline and evidence while it's fresh.
6. Diagnose the root cause (hand to bug-investigation/debugging).
7. Run a blameless postmortem with concrete, owned action items.

## Anti-patterns

- Hunting for root cause while users stay down (mitigate first).
- Risky "hero" fixes straight to prod without a rollback.
- Silence — no status updates to stakeholders during the incident.
- Blame-focused postmortems, or none at all, so the incident recurs.

## Output

- A mitigated incident, a clear timeline, and a blameless postmortem with
  owned, dated action items.

## Reference selection

Read only what is relevant:

- references/triage-and-severity.md — declaring and sizing incidents.
- references/mitigation.md — fast, reversible ways to restore service.
- references/postmortem.md — running a blameless review.

## Shared standards

- ../shared/severity-levels.md
- ../shared/engineering-principles.md
- ../shared/templates/incident-postmortem-template.md
