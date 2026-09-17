---
name: debugging
description: >
  Debug errors and unexpected behavior using a structured root-cause process.
  Trigger this skill whenever the user shares an error message, stack trace,
  unexpected output, failing test, or says "this doesn't work", "ada bug",
  "error", "broken", "kenapa ini", or "help me fix". Never guess at a fix
  without first reproducing and isolating the problem — guessing without
  evidence produces fixes that mask symptoms rather than solve root causes.
---

# Debugging

## Purpose

Find the actual root cause of a bug before touching any code.
The most common debugging mistake is jumping straight to a fix based on
a guess — this produces patches that hide symptoms and make future bugs
harder to find.

The process: **Reproduce → Isolate → Identify → Fix → Verify**

---

## Step 1 — Reproduce

Before anything else, confirm you can reliably reproduce the bug.

Ask:

- Does this happen every time, or only sometimes?
- What exact steps trigger it?
- What is the expected behavior vs. what actually happens?

If the bug cannot be reproduced reliably → it's non-deterministic.
Go to `references/non-deterministic-bugs.md` for handling strategy.

**Do not proceed to step 2 until you can reproduce the bug consistently.**

---

## Step 2 — Read the Evidence

Read the full error message and stack trace carefully before forming any hypothesis.

Checklist:

- [ ] What is the **exact error type**? (TypeError, ReferenceError, 404, etc.)
- [ ] What is the **exact line** where it originates? (bottom of stack trace, not top)
- [ ] What are the **values** at that point? (log them if not shown)
- [ ] Has anything **recently changed** near this code?

Write down what the evidence says — not what you think the problem is.

---

## Step 3 — Form One Hypothesis

Based only on the evidence, form **one specific hypothesis**:

```
Hypothesis: [Specific claim about what is wrong and why]
Evidence:   [What in the stack trace / logs supports this]
Test:       [One thing that would confirm or deny this hypothesis]
```

Example:

```
Hypothesis: The JWT middleware is rejecting valid tokens because the
            secret in .env.test differs from the one used to sign tokens in helpers.ts
Evidence:   Error is "invalid signature" at verify() in src/lib/jwt.ts:24,
            and createTestToken() was recently added to helpers.ts
Test:       console.log both secrets side by side before the verify() call
```

**One hypothesis at a time.** Don't list five possible causes and fix them all —
that's guessing, not debugging.

---

## Step 4 — Test the Hypothesis

Add the minimal instrumentation to confirm or deny the hypothesis:

```typescript
// Temporary — remove after debugging
console.log('[DEBUG] jwt secret:', process.env.JWT_SECRET)
console.log('[DEBUG] token payload:', jwt.decode(token))
```

For Claude Code (has terminal):

```bash
# Run with focused output
pnpm test --grep "test name" 2>&1 | head -50

# Or add a focused console.log and rerun
```

For Claude.ai (no terminal):

- Trace the code path mentally, step by step
- Identify the exact line where the value diverges from expectation
- State what the value should be vs. what it likely is

**If hypothesis is confirmed** → go to Step 5.
**If hypothesis is denied** → go back to Step 3 with new evidence.
Do not skip this step and go straight to a fix.

---

## Step 5 — Fix the Root Cause

Fix only what the confirmed hypothesis identified. Nothing more.

Rules:

- Fix the root cause, not the symptom
- Don't add defensive code to hide the bug without fixing it
- Don't refactor while fixing — that's a separate task
- The fix should be small and targeted; if it's large, the hypothesis was too broad

---

## Step 6 — Verify the Fix

```
1. The original bug no longer reproduces
2. Existing tests still pass
3. Remove all debug logging added in Step 4
4. Add a regression test so this bug can't silently return
```

Regression test template:

```typescript
it('should [describe the bug scenario that was fixed]', async () => {
  // Reproduce the exact conditions that caused the bug
  // Assert the correct behavior
})
```

---

## Blocker — Cannot Find Root Cause

If after 2–3 hypothesis cycles you still haven't found the root cause:

1. **Widen the search** — log more values, check inputs earlier in the call chain
2. **Check recent changes** — `git log --oneline -10`, `git diff HEAD~3`
3. **Simplify** — reproduce the bug in the smallest possible isolated case
4. **Check the obvious** — wrong env var, stale build, cached value, wrong branch

If still stuck, go to `references/stuck-debugging.md`.

---

## Rules

- **Never fix without reproducing first** — a fix you can't verify might make things worse
- **One hypothesis at a time** — test it before forming the next one
- **Fix root cause, not symptoms** — wrapping in try/catch without understanding why is not a fix
- **Remove all debug logging before committing**
- **Always add a regression test** — if it broke once, protect it

---

## Reference Files

- `references/non-deterministic-bugs.md` — Race conditions, intermittent failures, timing issues
- `references/stuck-debugging.md` — Systematic approach when you're truly stuck
- `references/common-ts-runtime-errors.md` — Quick lookup for frequent JS/TS runtime errors

Baca reference yang relevan dengan tipe bug yang sedang dihadapi.
