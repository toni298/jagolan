# Undo and recovery

Choose the least destructive tool that fixes the problem.

## Common situations

- **Uncommitted changes you want to discard:** `git restore <file>` (or
  `git restore --staged` to unstage). Verify with `git status` first.
- **Amend the last (unpushed) commit:** `git commit --amend`.
- **Undo a commit but keep changes:** `git reset --soft HEAD~1`.
- **Undo a commit and discard changes:** `git reset --hard HEAD~1` (destructive).
- **Undo a *published* commit:** `git revert <sha>` — makes a new commit, safe
  for shared history.

## Recovery

- Lost a commit/branch? `git reflog` shows where HEAD has been; check out or
  cherry-pick the lost SHA.
- Before any `--hard` reset or history rewrite, note the current SHA so you can
  return to it.

## Rules

- Never `reset --hard` or force-push shared branches to "fix" history; revert.
- When unsure, make a backup branch first: `git branch backup/before-fix`.
