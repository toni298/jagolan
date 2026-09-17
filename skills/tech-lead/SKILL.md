---
name: tech-lead
description: >
  Support senior technical decisions: tradeoff analysis, risk assessment,
  buy-vs-build, prioritization, and strategy. Trigger this skill whenever the
  user asks "should we use X or Y", "build or buy", "is this worth it",
  "what are the risks", "how do we prioritize", or needs a defensible
  recommendation across competing options. Never give a recommendation without
  stating the assumptions, tradeoffs, and the conditions under which it changes.
---

# Tech Lead

## Purpose

Turn ambiguous technical questions into clear, defensible decisions with
explicit tradeoffs, risks, and a recommended path.

## When to use

- Choosing between technologies, libraries, patterns, or vendors.
- Deciding build vs buy vs adopt-open-source.
- Sequencing or prioritizing work under constraints.
- Assessing the risk of a significant change before committing.

## Workflow

1. Clarify the goal, constraints, time horizon, and what "good" looks like.
2. Enumerate realistic options (including "do nothing").
3. Analyze tradeoffs against the constraints, not ideal-world assumptions.
4. Surface risks and their mitigations; flag one-way vs two-way-door decisions.
5. Recommend one option and state the conditions that would change it.
6. Record the decision (see ../shared/templates/adr-template.md).

## Anti-patterns

- Presenting options without a recommendation.
- Optimizing for resume/hype over team fit and maintenance cost.
- Treating reversible decisions as if they need perfect analysis.
- Ignoring operational, hiring, and long-term maintenance costs.

## Output

- A concise decision: recommendation, rationale, tradeoffs, risks, and an ADR.

## Reference selection

Read only what is relevant:

- references/tradeoffs.md — structuring tradeoff analysis.
- references/risk-analysis.md — identifying and rating risks.
- references/buy-vs-build.md — evaluating build vs buy vs adopt.
- references/decision-records.md — capturing decisions durably.

## Shared standards

- ../shared/decision-framework.md
- ../shared/engineering-principles.md
- ../shared/templates/adr-template.md
