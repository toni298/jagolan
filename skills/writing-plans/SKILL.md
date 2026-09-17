---
name: writing-plans
description: >
  Create a detailed implementation plan before writing any code.
  Trigger this skill whenever the user asks to build a feature, fix a bug,
  refactor code, add an endpoint, create a component, or make any non-trivial
  change to a codebase. Also trigger when the user says "implement", "add",
  "create", "build", "fix", "refactor", or "change" — even if the request
  sounds simple. Never write code without a plan first. A plan takes 2 minutes
  to write and saves 20 minutes of inconsistent output.
---

# Writing Plans

## Purpose

Turn a vague task into a precise, step-by-step implementation plan before any
code is written. This is the primary defense against inconsistent outputs —
when Claude has a clear plan, every decision is made once, upfront, not
improvised mid-task.

---

## Prerequisites

Before writing a plan, make sure:

- [ ] `project-context` skill has been run — you know the stack and conventions
- [ ] The task is clear enough to break into steps — if not, run `analysis` skill first
- [ ] You know which files will be affected

If any of these are missing, address them before continuing.

---

## Step 1 — Understand the Task

Restate the task in one sentence. If you can't, it's not clear enough — ask
one clarifying question before proceeding.

Example:

> **Task:** Add a `PATCH /users/:id` endpoint that allows updating email and
> display name, with validation and proper error responses.

---

## Step 2 — Map Affected Files

List every file that will be **created**, **modified**, or **deleted**.
Be specific — full paths relative to project root.

```
CREATE  src/routes/users/patch-user.ts
MODIFY  src/routes/users/index.ts       ← register new route
MODIFY  src/services/user-service.ts    ← add updateUser()
MODIFY  src/types/user.ts               ← add UpdateUserInput type
CREATE  src/routes/users/patch-user.test.ts
```

If you're unsure about a path, note it with `[?]` — don't guess silently.

---

## Step 3 — Write the Tasks

Break the work into tasks. Each task must follow these rules:

**Rules for each task:**

- Completable in **5–15 minutes**
- Has a **single, clear outcome** — not "work on auth", but "add `verifyToken()` to `src/lib/auth.ts`"
- Includes the **exact file path**
- Specifies **what to write**, not just what to do
- Has a **verification step** — how do you know it's done?

**Task format:**

```
### Task N — [Title]

File: `path/to/file.ts`
Action: CREATE | MODIFY | DELETE

What to do:
[2–4 sentences describing exactly what to implement]

Key details:
- [Specific function signature, type, or behavior]
- [Edge case to handle]
- [Convention to follow from context.md]

Verify:
- [ ] [How to confirm this task is complete]
- [ ] [Test that should pass]
```

---

## Step 4 — Add a Testing Task

Every plan must include at least one testing task. For JS/TS projects:

- Unit test: test pure functions and services in isolation
- Integration test: test API endpoints with a real (or in-memory) DB
- Use the testing framework from `project-context` (Vitest / Jest)

If the feature is pure UI, note which interactions to manually verify.

---

## Step 5 — Save the Plan

Save to: `docs/plans/YYYY-MM-DD-[feature-name].md`

If `docs/plans/` doesn't exist, create it. Commit the plan file before
starting implementation.

---

## Step 6 — Present & Confirm

Show the plan to the user in this format:

```
## Plan: [Feature Name]

**Files affected:** X created, Y modified
**Tasks:** N tasks (~XX minutes total)

[List of tasks with titles only — not full detail]

---
[Full task detail below]
```

Then ask:

> "Mau saya lanjutkan dengan task 1, atau ada yang perlu diubah dulu?"

**Do not start coding until the user confirms.**

---

## Plan Quality Checklist

Before presenting, check:

- [ ] Every task has an exact file path
- [ ] No task is vague ("update the service" → not acceptable)
- [ ] No task tries to do too much (if >15 min, split it)
- [ ] Conventions from `project-context` are reflected in the plan
- [ ] At least one test task exists
- [ ] Tasks are in dependency order (no task requires output from a later task)

---

## Rules

- **Never write code before the plan is confirmed** — not even "just a small snippet"
- **If the task changes mid-execution**, pause, update the plan, re-confirm
- **If a task turns out to be more complex than expected**, stop, split it, update the plan
- **Plans are living documents** — update them as you go, don't let them become stale
- **One plan per feature** — don't combine unrelated features in one plan

---

## Reference Files

- `references/plan-examples.md` — Contoh plan lengkap untuk fitur CRUD dan auth
- `references/task-sizing.md` — Panduan memecah task yang terlalu besar

Baca jika butuh contoh konkret atau kesulitan memecah task.
