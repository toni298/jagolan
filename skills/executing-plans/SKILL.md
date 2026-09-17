---
name: executing-plans
description: >
  Execute an existing implementation plan task by task, with checkpoints
  between each task. Trigger this skill whenever the user says "go", "start",
  "lanjut", "kerjakan", "execute the plan", "mulai", or gives approval after
  a plan has been presented. Also trigger when a plan file exists in
  docs/plans/ and the user wants to continue work on it. Never execute
  multiple tasks at once without a checkpoint — doing so is the primary
  cause of hard-to-debug, inconsistent code.
---

# Executing Plans

## Purpose

Run a confirmed plan one task at a time, with a checkpoint after each task
so small mistakes don't compound into big ones. This is the execution
counterpart to `writing-plans`.

---

## Prerequisites

Before executing:

- [ ] A confirmed plan exists — either in `docs/plans/` or confirmed in conversation
- [ ] `project-context` is loaded — you know the stack and conventions
- [ ] A clean starting point — no half-finished work from a previous session

If there's no plan yet, stop and run `writing-plans` first.

---

## Execution Loop

Repeat this loop for every task in the plan:

```
1. Announce task
2. Execute task
3. Run verification
4. Checkpoint with user
5. Move to next task (only after checkpoint passes)
```

---

## Step 1 — Announce Task

Before writing any code, announce what you're about to do:

```
---
**Task [N]/[Total] — [Task Title]**
File: `path/to/file.ts` ([CREATE/MODIFY])

[One sentence: what you're about to implement]
---
```

This gives the user a chance to stop you before wasted effort.

---

## Step 2 — Execute Task

Write the code for this task only. Rules:

- **Stay in scope** — only touch the files listed in this task
- **Follow conventions** — check `project-context` if unsure about naming,
  formatting, or patterns
- **No gold-plating** — implement exactly what the plan says, nothing more
- **No skipping ahead** — don't implement the next task "while you're at it"
- **If something unexpected comes up** → stop, document it, go to Step 5b

---

## Step 3 — Run Verification

After writing code, run the verification steps from the plan:

For Claude Code (has terminal access):

```bash
# TypeScript check
npx tsc --noEmit

# Run relevant tests
pnpm test [test-file-path]

# Or specific test
pnpm test --grep "test name"
```

For Claude.ai (no terminal):

- Review the written code against the plan's verification checklist
- Confirm each checkbox explicitly: `✅ tsc would pass` / `✅ function signature matches`
- Be honest — if you're not certain, say so

---

## Step 4 — Checkpoint

After every task, stop and report:

```
✅ Task [N] done — [Title]

[1–2 sentences: what was implemented]

Verification:
- ✅ [Verification item 1]
- ✅ [Verification item 2]

[Any notes — unexpected findings, small decisions made, deviations from plan]

---
Ready for Task [N+1]: [Next task title]?
Atau ada yang mau dicek dulu?
```

**Wait for user response before continuing.**

---

## Step 5b — Unexpected Complexity (Blocker)

If a task turns out to be more complex than the plan assumed:

1. **Stop immediately** — don't improvise a bigger solution
2. **Document what you found:**

   ```
   ⚠️ Blocker on Task [N] — [Title]

   Expected: [what the plan assumed]
   Found: [what's actually there]
   Impact: [why this changes things]

   Options:
   A) [Simpler approach — less scope]
   B) [Original approach — needs more time/changes]
   C) [Alternative approach]

   Recommendation: [A/B/C] karena [reason]
   ```

3. **Let the user decide** — don't unilaterally expand scope

---

## Step 5c — Plan Deviation

If you need to deviate from the plan (different file, different approach):

- State the deviation explicitly before doing it
- Get confirmation
- Update the plan file after confirmation

Never silently deviate — the plan is the contract.

---

## Final Step — Plan Complete

When all tasks are done:

```
🎉 Plan complete — [Feature Name]

Summary:
- [N] tasks completed
- Files created: [list]
- Files modified: [list]

Next steps:
- [ ] Manual smoke test: [specific things to check]
- [ ] Update project-context if architecture changed
- [ ] Commit: `git add -A && git commit -m "[type]: [description]"`
```

Suggest updating `.claude/context.md` if:

- New patterns were introduced
- Architecture decisions were made
- New dependencies added

---

## Rules

- **One task at a time** — never batch tasks without checkpoints
- **Never skip verification** — a task without verification isn't done
- **Announce before coding** — no surprise file changes
- **Stop on blockers** — unexpected complexity = pause, not improvise
- **Deviations need confirmation** — always
- **Plan file is the source of truth** — if plan and conversation conflict, ask

---

## Reference Files

- `references/git-workflow.md` — Commit conventions dan branching untuk solo dev
- `references/common-blockers.md` — Blocker yang sering muncul di JS/TS dan cara handle-nya

Baca jika menemui blocker yang tidak familiar.
