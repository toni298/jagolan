# Semantic versioning

Version format: `MAJOR.MINOR.PATCH`.

- **MAJOR** — incompatible/breaking changes to the public contract.
- **MINOR** — backward-compatible new functionality.
- **PATCH** — backward-compatible bug fixes.

Deciding the bump:

1. Did any public behavior/API break or get removed? → MAJOR.
2. Otherwise, did you add capability? → MINOR.
3. Otherwise (fixes/internal only) → PATCH.

Notes:

- Pre-1.0 (`0.x`): anything may change; treat minor as potentially breaking.
- Pre-releases: `1.4.0-rc.1`, `2.0.0-beta.3`.
- "Breaking" includes changed defaults, removed fields, stricter validation,
  and changed error contracts — not just deleted functions.
- The version must reflect the *consumer-visible* contract, not internal churn.
