# Dependency upgrades

Upgrades are change — treat them with the same care as code.

## Routine upgrades

1. Upgrade in small batches grouped by risk (patch → minor → major).
2. Read the changelog/release notes for breaking changes before bumping.
3. Run the full test suite (and type check/lint) after each batch.
4. Watch for transitive changes and lockfile drift; commit the lockfile.
5. Pin versions for reproducible builds; avoid floating ranges in production.

## Security patches

- Triage advisories by severity and whether the vulnerable path is reachable.
- Patch reachable high/critical issues promptly; record the decision otherwise.
- Prefer the smallest version that resolves the advisory.

## Major upgrades

- Treat as a project: read the migration guide, do it on a branch, test heavily.
- Update one major dependency at a time so failures are attributable.

## Anti-patterns

- "Update everything" in one commit with no tests between.
- Ignoring the lockfile, producing non-reproducible builds.
- Auto-merging major bumps without reading breaking changes.
