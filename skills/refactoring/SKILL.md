---
name: refactoring
description: >
  Improve code structure without changing observable behavior. Trigger this
  skill whenever the user says "refactor", "clean up", "rapikan", "tidy",
  "simplify", "reduce duplication", "this is messy", or asks to improve
  readability/maintainability without adding features. Also trigger before
  extending code that is hard to change. Never refactor without a safety net
  (tests or a tight manual check) and never mix behavior changes into a
  refactor commit.
---

# Refactoring

## Purpose

Make code easier to read, change, and test while keeping behavior identical.
Refactoring is a disciplined, reversible activity — not a rewrite.

## When to use

- Before adding a feature to code that is hard to understand or extend.
- When you see duplication, long functions, deep nesting, or unclear names.
- After a bug fix, to remove the conditions that allowed the bug.
- Skip when the code is throwaway or about to be deleted.

## Workflow

1. Confirm behavior is covered by tests; if not, add characterization tests first.
2. Identify the dominant code smell (see references/code-smells.md).
3. Choose the smallest safe transformation that improves the smell.
4. Apply it in one isolated step; keep the build/tests green after each step.
5. Commit the refactor separately from any behavior change.
6. Repeat in small increments; stop when readability is "good enough".

## Anti-patterns

- Mixing refactoring and feature/bugfix changes in one commit.
- Large "big bang" rewrites with no intermediate green state.
- Refactoring without tests or a reproducible manual check.
- Renaming/abstracting prematurely before the pattern is clear (rule of three).

## Output

- Behavior-preserving diffs in small steps, each independently reviewable.
- A short note on what smell was removed and why the change is safe.

## Reference selection

Read only what is relevant:

- references/code-smells.md — how to recognize what needs changing.
- references/safe-refactoring.md — keeping changes reversible and green.
- references/patterns.md — concrete refactoring moves.

## Shared standards

- ../shared/engineering-principles.md
- ../shared/decision-framework.md
- ../shared/severity-levels.md
