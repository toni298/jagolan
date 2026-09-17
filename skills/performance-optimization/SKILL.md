---
name: performance-optimization
description: >
  Diagnose and fix performance problems across backend, database, frontend,
  network, memory, and external services. Trigger this skill whenever the user
  says "slow", "lambat", "lemot", "high latency", "timeout", "high memory",
  "optimize", "performance issue", or reports degraded throughput. Never
  optimize without measuring first — guessing at bottlenecks wastes effort and
  often makes code worse.
---

# Performance Optimization

## Purpose

Improve performance using a measurement-first method: find the real
bottleneck, fix the highest-impact one, and verify the gain.

## When to use

- A user-visible operation is slow or times out.
- Resource usage (CPU, memory, connections) is too high.
- Throughput does not meet a stated target or SLO.

## Workflow

1. Define the target metric and acceptable threshold (p95 latency, RPS, memory).
2. Reproduce and measure the current baseline under realistic load.
3. Profile to locate the dominant bottleneck (don't assume).
4. Classify it: CPU, memory, DB/query, network, external API, render, or bundle.
5. Apply the highest-impact fix; change one thing at a time.
6. Re-measure against the baseline; keep only changes that demonstrably help.
7. Add a guardrail (benchmark, budget, or alert) to prevent regression.

## Anti-patterns

- Optimizing without a profile or baseline ("premature optimization").
- Micro-optimizing code while an N+1 query or network round-trip dominates.
- Caching to hide a problem instead of fixing the underlying cost.
- Reporting improvements without before/after numbers.

## Output

- Baseline vs after metrics, the bottleneck found, the fix, and a regression guard.

## Reference selection

Read only what is relevant:

- references/profiling.md — measuring and finding the bottleneck.
- references/database-performance.md — query/index/connection issues.
- references/api-performance.md — backend and external-call latency.
- references/frontend-performance.md — render, bundle, and network on the client.

## Shared standards

- ../shared/engineering-principles.md
- ../shared/severity-levels.md
- ../shared/decision-framework.md
