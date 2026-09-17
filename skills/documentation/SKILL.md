---
name: documentation
description: >
  Write and maintain technical documentation: READMEs, API docs, architecture
  notes, runbooks, and code comments. Trigger this skill whenever the user says
  "document", "write docs", "buat dokumentasi", "README", "API docs",
  "explain how this works", or after building something that others must use or
  operate. Document the why and the contract, not a line-by-line restatement of
  the code; keep docs close to the code and update them with the change.
---

# Documentation

## Purpose

Produce documentation that helps a reader use, operate, or change the system
correctly — optimized for the reader's task, not the writer's convenience.

## When to use

- Writing/updating a README, API reference, or architecture overview.
- Creating a runbook for an operational procedure.
- Adding comments that explain intent, invariants, or non-obvious decisions.

## Workflow

1. Identify the audience and the task they need to accomplish.
2. Choose the doc type (README, API, ADR, runbook, inline comment).
3. Lead with what it does and how to use it; put detail/edge cases after.
4. Document contracts: inputs, outputs, errors, side effects, and assumptions.
5. Include a minimal, runnable example for anything used by others.
6. Place the doc next to the code and update it in the same change.

## Anti-patterns

- Comments that restate the code instead of explaining why.
- Docs that drift because they live far from the code.
- API docs without error/edge-case behavior or an example.
- A README that explains internals before how to run/use the thing.

## Output

- Task-oriented docs with examples and contracts, updated alongside the code.

## Reference selection

Read only what is relevant:

- references/readme-structure.md — a README that gets people running fast.
- references/api-docs.md — documenting contracts and errors.
- references/code-comments.md — when and how to comment.

## Shared standards

- ../shared/engineering-principles.md
- ../shared/glossary.md
- ../shared/templates/design-doc-template.md
