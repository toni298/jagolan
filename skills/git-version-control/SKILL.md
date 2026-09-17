---
name: git-version-control
description: >
  Use Git safely and clearly: branching, commits, merges, rebases, conflict
  resolution, and history hygiene. Trigger this skill whenever the user says
  "git", "commit", "branch", "merge", "rebase", "conflict", "revert", "undo",
  "PR", or needs to recover a repository state. Prefer small, reversible,
  well-described changes; never rewrite shared/published history without an
  explicit, agreed reason.
---

# Git Version Control

## Purpose

Keep version history clean, reviewable, and recoverable, and perform Git
operations without losing work.

## When to use

- Structuring branches, commits, and PRs.
- Resolving merge/rebase conflicts.
- Undoing mistakes or recovering lost commits.

## Workflow

1. Work on a focused branch off the latest main; keep scope small.
2. Make atomic commits: one logical change each, with a clear message.
3. Write messages as `type: imperative summary` plus a why in the body.
4. Keep refactors separate from behavior changes (one concern per commit).
5. Sync with main regularly; resolve conflicts with understanding, not blindly.
6. Before history rewrites, confirm the branch is not shared; prefer revert on
   published history.
7. When unsure, check `git status`/`git reflog` before destructive commands.

## Anti-patterns

- Giant commits mixing many concerns with messages like "fix stuff".
- Force-pushing shared branches and rewriting published history.
- Resolving conflicts by accepting one side without reading both.
- Committing secrets, build output, or large binaries.

## Output

- A clean branch and history that reviewers can follow commit by commit.

## Reference selection

Read only what is relevant:

- references/branching-and-commits.md — branch strategy and commit hygiene.
- references/merge-rebase-conflicts.md — integrating and resolving conflicts.
- references/undo-and-recovery.md — reset/revert/reflog to recover safely.

## Shared standards

- ../shared/engineering-principles.md
- ../shared/decision-framework.md
