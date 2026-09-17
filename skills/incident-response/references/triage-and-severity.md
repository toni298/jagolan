# Triage and severity

Declare early; it is cheaper to downgrade than to under-react.

## Sizing questions

- Who/what is affected and how many users?
- Is data being lost or corrupted? Is there a security/privacy exposure?
- Is there a workaround? Is it getting worse?
- When did it start, and what changed around then?

## Severity guide

- **Sev1 — Critical:** broad outage, data loss, or security breach. All-hands,
  immediate mitigation, frequent comms.
- **Sev2 — Major:** key feature broken or significant degradation; urgent.
- **Sev3 — Minor:** limited impact with a workaround; handle in normal hours.

## Roles

- **Incident lead** — coordinates, decides, owns the timeline.
- **Comms** — updates stakeholders on a cadence.
- **Investigator(s)** — mitigate and diagnose.

Keep a running timestamped log from the moment you declare.
