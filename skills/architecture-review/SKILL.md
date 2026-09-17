---
name: architecture-review
description: >
  Review software architecture for maintainability, scalability, reliability,
  coupling, boundaries, and long-term evolution. Trigger this skill whenever
  the user asks to "review the architecture", "is this design right",
  "will this scale", proposes a new service/module split, or makes a structural
  decision that is expensive to reverse. Evaluate tradeoffs for this team and
  context — never recommend an ideal pattern without weighing its cost.
---

# Architecture Review

## Purpose

Assess whether a system's structure fits its goals, constraints, and team,
and recommend incremental, justified improvements.

## When to use

- Evaluating a proposed design or an existing system's structure.
- Deciding module/service boundaries or data ownership.
- Before a change that is costly to reverse (splitting services, new datastore).

## Workflow

1. Understand goals, constraints, scale targets, and team size/skill.
2. Map current components, module boundaries, and data ownership.
3. Review coupling and dependency direction (do dependencies point inward?).
4. Check scalability and reliability assumptions against real targets.
5. Evaluate tradeoffs explicitly; prefer the simplest structure that fits.
6. Recommend incremental improvements with a migration path, not a rewrite.

## Anti-patterns

- Recommending microservices/patterns by default without need.
- Reviewing diagrams while ignoring data ownership and failure modes.
- Bidirectional or cyclic dependencies between modules.
- Proposing a big rewrite instead of an incremental path.

## Output

- Findings by severity, key tradeoffs, and a prioritized, incremental plan.

## Reference selection

Read only what is relevant:

- references/architecture-principles.md — boundaries, coupling, cohesion.
- references/modular-monolith.md — when a single deployable is the right call.
- references/microservices.md — when/how to split, and the costs.
- references/tradeoff-analysis.md — structuring the decision.

## Shared standards

- ../shared/decision-framework.md
- ../shared/engineering-principles.md
- ../shared/templates/design-doc-template.md
