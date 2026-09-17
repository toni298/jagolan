# Mitigation

Goal: restore service fast with the most reversible action available.

## First options (prefer reversible)

- **Roll back** the most recent deploy if the incident started after it.
- **Feature-flag off** the suspect feature.
- **Scale up / add capacity** for load-related failures.
- **Fail over** to a healthy replica/region.
- **Shed load / rate-limit** to protect the core path.
- **Disable a bad integration** that is cascading failures.

## Principles

- Mitigate before diagnosing fully; users first, root cause later.
- Prefer reversible mitigations; avoid risky one-way fixes mid-incident.
- Change one thing at a time and observe; record every action with a timestamp.
- Confirm recovery with real signals (metrics/health), not assumptions.
- If a mitigation doesn't help within a bounded time, revert it and try the next.
