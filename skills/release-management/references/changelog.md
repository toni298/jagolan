# Changelog

A changelog tells users what changed and whether they must act. Keep it
human-written and grouped by intent (Keep a Changelog style).

Sections per release:

- **Added** — new features.
- **Changed** — changes in existing behavior.
- **Deprecated** — soon-to-be removed features.
- **Removed** — removed features.
- **Fixed** — bug fixes.
- **Security** — vulnerability fixes.

Example:

```
## [2.0.0] - 2026-06-11
### Changed (BREAKING)
- `createUser` now requires `email`; see MIGRATION.md.
### Added
- Bulk import endpoint.
### Fixed
- Race condition on concurrent checkout.
```

Guidelines:

- Write for the consumer; describe impact, not internal commit noise.
- Mark breaking changes prominently and link a migration note.
- Keep entries in present/imperative tense and dated, newest first.
- Generate a draft from commits, then edit for clarity — don't ship raw logs.
