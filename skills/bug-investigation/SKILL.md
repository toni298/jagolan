---
name: bug-investigation
description: >
  Investigate unclear, hard-to-reproduce, or intermittent bugs when the root
  cause is unknown. Trigger this skill whenever the user says "sometimes it
  fails", "kadang error", "flaky", "intermittent", "can't reproduce", "only
  in production", or the cause is not obvious from a stack trace. Use this
  before debugging when the problem is fuzzy; never propose a fix until you
  have evidence and, where possible, a reproduction.
---

# Bug Investigation

## Purpose

Narrow an ambiguous failure down to a concrete, reproducible root cause using
evidence rather than guesses.

## When to use

- The symptom is vague, rare, environment-specific, or intermittent.
- There is no clean stack trace pointing at the cause.
- A bug "comes back" or only appears under load/timing/data conditions.

## Workflow

1. Clarify the exact symptom, impact, frequency, and first-seen time.
2. Gather evidence: logs, metrics, errors, recent deploys, and data samples.
3. Form hypotheses ranked by likelihood and ease of testing.
4. Build a minimal reproduction; if impossible, add targeted logging/tracing.
5. Isolate by bisecting changes, inputs, or environment differences.
6. Confirm the root cause by toggling it on/off, then hand off to debugging/TDD.

## Anti-patterns

- Patching the symptom before the cause is understood.
- Changing several variables at once so you can't tell what mattered.
- Trusting "it works on my machine" instead of matching the failing environment.
- Closing as "could not reproduce" without adding observability.

## Output

- A documented root cause with evidence and a reliable (or scripted) repro.

## Reference selection

Read only what is relevant:

- references/reproduction.md — building a minimal, reliable repro.
- references/intermittent-bugs.md — timing, concurrency, and data-dependent faults.
- references/root-cause-analysis.md — from evidence to confirmed cause.

## Shared standards

- ../shared/engineering-principles.md
- ../shared/severity-levels.md
- ../shared/templates/incident-postmortem-template.md
