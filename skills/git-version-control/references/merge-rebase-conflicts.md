# Merge, rebase, and conflicts

## Merge vs rebase

- **Merge** preserves history as it happened; safe for shared branches.
- **Rebase** creates a linear history; use only on local/unshared branches.
- Rule of thumb: rebase your private branch onto main before opening a PR;
  never rebase a branch others have based work on.

## Resolving conflicts

1. Understand both sides before editing — read the intent, not just the lines.
2. Keep the change that satisfies both requirements; don't blindly pick a side.
3. Re-run tests/build after resolving; a compiling merge can still be wrong.
4. For repeated conflicts in the same area, consider that the design needs work.

## Safety

- `git status` constantly during a conflicted merge/rebase.
- Abort cleanly if confused: `git merge --abort` / `git rebase --abort`.
- Prefer many small integrations over one giant end-of-feature merge.
