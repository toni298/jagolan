# Branching and commits

## Branching

- Branch off the latest main; keep a branch focused on one feature/fix.
- Use descriptive names: `feat/checkout-coupons`, `fix/login-race`.
- Short-lived branches reduce conflict pain; integrate often.

## Atomic commits

- One logical change per commit. If you can't describe it in one line, split it.
- Keep refactors in separate commits from behavior changes.
- Commit working states; avoid "WIP" on shared branches.

## Commit messages

Format:

```
type: short imperative summary (<= ~72 chars)

Why this change is needed and any context a reviewer needs.
Reference issues/ADRs where relevant.
```

Common types: `feat`, `fix`, `refactor`, `docs`, `test`, `chore`, `perf`.

Guidelines:

- Summary in the imperative mood ("add", not "added").
- Explain the why in the body; the diff already shows the what.
- Never commit secrets, credentials, or generated artifacts.
