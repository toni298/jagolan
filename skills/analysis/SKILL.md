---
name: analysis
description: >
  Analyze a problem before solving, planning, or writing code. Use this skill
  before solving any non-trivial problem, writing code, creating plans, or
  making decisions. Trigger whenever the user presents a task, request, or
  problem that involves multiple steps, stakeholders, or unclear requirements —
  even if they skip straight to asking for a solution. Also trigger for
  debugging, design decisions, and any situation where jumping straight to
  implementation could waste effort. Skip only for clearly trivial tasks
  (single file, unambiguous scope) or direct continuations of an existing plan.
---

# Analysis

## Purpose

Understand the real problem before proposing solutions. Surface hidden
constraints, unstated assumptions, and missing information early — before
any planning or implementation begins.

---

## Workflow

### 1. Define Objective

State the core goal in one sentence. What does success look like?

### 2. Identify Stakeholders

Who is affected? Who has decision-making power? Whose needs might conflict?
For solo dev projects, this is usually: you + end users + any external systems.

### 3. Identify Constraints

Hard limits (time, budget, tech stack, performance) vs. soft preferences.
Label each clearly — `[HARD]` or `[SOFT]`.

### 4. Identify Assumptions

List everything being taken for granted. Mark each with `[ASUMSI]` so they
are visible and challengeable.

### 5. Identify Risks

What could go wrong? Focus on the top 3–5 most critical risks.
Note impact and likelihood for each.

### 6. Identify Missing Information

List gaps that could significantly change the approach.
Ask at most **3 clarifying questions** — the most critical ones only.
If no answer is available, state the assumption you will proceed with.

### 7. Summarize Findings

Produce this structured summary:

```
OBJECTIVE     : [one sentence]
STAKEHOLDERS  : [list]
CONSTRAINTS   : [hard | soft]
ASSUMPTIONS   : [each marked [ASUMSI]]
RISKS         : [top 3–5]
GAPS          : [open questions, or stated assumptions to fill them]
READY?        : Yes / No — [reason]
```

---

## When to Skip

Analysis adds overhead — don't run it when it's not needed.

**Skip analysis if:**

- Task is a single, unambiguous change ("rename this variable", "add this field to the interface")
- Task is a direct continuation of an already-confirmed plan from `writing-plans`
- User explicitly says "just do it", "langsung aja", or equivalent
- Task is purely mechanical (formatting, dependency update, config change)

**Always run analysis if:**

- Requirements are vague or could be interpreted multiple ways
- Task touches more than 2 files or more than one layer of the stack
- Task involves a breaking change, migration, or irreversible action
- Something feels off — assumptions are being made silently

If in doubt, run a **lightweight analysis** — just steps 1, 3, and 4 (objective,
constraints, assumptions) — instead of the full 7-step workflow.

---

## Rules

- Never jump directly to implementation or planning
- Never assume requirements — make them explicit
- Distinguish facts from assumptions at all times — use `[ASUMSI]` tag
- Ask at most 3 clarifying questions; don't stall indefinitely
- If critical info is truly unavailable, proceed with explicitly stated assumptions
- Keep the summary concise — analysis should enable action, not replace it

---

## Example Output

```
OBJECTIVE     : Add pagination to GET /posts endpoint
STAKEHOLDERS  : Developer (me), API consumers (frontend)
CONSTRAINTS   : [HARD] Must not break existing API clients
                [SOFT] Prefer cursor-based over offset (more scalable)
ASSUMPTIONS   : [ASUMSI] Frontend currently sends no pagination params
                [ASUMSI] 20 items per page is acceptable default
RISKS         : Breaking existing clients if response shape changes (HIGH)
                N+1 query if cursor not indexed (MEDIUM)
GAPS          : Does frontend need total count? → [ASUMSI] yes, include meta.total
READY?        : Yes — with assumptions above
```
