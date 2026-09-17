---
name: code-review
description: >
  Review JavaScript/TypeScript changes for correctness, security, performance,
  maintainability, architecture, and test coverage. Trigger this skill whenever
  the user says "review this", "PR review", "cek kode", "is this good", shares
  a diff/branch, or finishes an implementation task. Review against evidence and
  severity — never approve code you have not actually read, and never block on
  pure style when a formatter/linter should handle it.
---

# Code Review

## Purpose

Catch correctness, security, and maintainability problems before they reach
production, and communicate them clearly by severity.

## When to use

- Reviewing a PR, diff, branch, or a just-completed implementation.
- Before merging risky or security-sensitive changes.

## Workflow

1. Understand the change's intent and scope before reading line by line.
2. Check correctness: edge cases, error handling, and async/await correctness.
3. Check security: input validation, authz, secrets, injection (see references).
4. Check tests: do they cover the new behavior and failure paths?
5. Check maintainability and architecture: naming, boundaries, duplication.
6. Check performance only where it matters (hot paths, N+1, large data).
7. Assign each finding a severity and give an actionable fix.

## Anti-patterns

- Approving without reading the actual diff.
- Blocking on style a formatter/linter could fix automatically.
- Mixing critical findings with nitpicks without marking severity.
- Vague comments ("this is wrong") with no suggested fix.

## Output

Use references/output-format.md. Group findings by severity with file/line and
a concrete fix; end with a clear approve / request-changes decision.

## Reference selection

Read only what is relevant:

- references/review-checklist.md — the systematic pass.
- references/severity-levels.md — rating findings.
- references/output-format.md — how to present the review.
- references/security-review.md — security-focused checks.
- references/performance-review.md — performance-focused checks.
- references/testing-review.md — test-coverage checks.
- references/typescript-review.md — TS-specific correctness.
- references/architecture-review.md — structural concerns.

## Shared standards

- ../shared/severity-levels.md
- ../shared/engineering-principles.md
- ../shared/decision-framework.md
