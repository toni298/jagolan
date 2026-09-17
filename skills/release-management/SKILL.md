---
name: release-management
description: >
  Plan and ship releases safely: semantic versioning, changelogs, dependency
  upgrades, and rollout/rollback. Trigger this skill whenever the user says
  "release", "version", "changelog", "bump", "publish", "upgrade dependencies",
  "update packages", "semver", or prepares to deliver a new build. Make releases
  reversible and communicated; never ship a version bump without a changelog or
  a rollback path.
---

# Release Management

## Purpose

Deliver changes to users predictably: correct versioning, clear release notes,
safe dependency upgrades, and a rollback plan.

## When to use

- Cutting a release or publishing a package.
- Deciding a version number for a set of changes.
- Upgrading dependencies or reacting to a vulnerability advisory.

## Workflow

1. Determine the version bump from the change set using semver.
2. Compile a changelog grouped by Added/Changed/Fixed/Removed/Security.
3. Call out breaking changes and provide a migration note.
4. For dependency upgrades, review changelogs, run tests, and upgrade in batches.
5. Roll out with a strategy that allows rollback (tag, canary, feature flag).
6. Verify health after release; be ready to revert quickly.
7. Tag the release and keep the changelog as the source of truth.

## Anti-patterns

- Version bumps with no changelog or unclear breaking-change notes.
- Upgrading many dependencies at once with no tests between batches.
- Releasing on Friday/before being unavailable with no rollback plan.
- Treating a breaking change as a minor/patch bump.

## Output

- A correct version, a clear changelog, and a tested rollout/rollback plan.

## Reference selection

Read only what is relevant:

- references/semver.md — choosing the right version bump.
- references/changelog.md — writing useful release notes.
- references/dependency-upgrades.md — upgrading and patching safely.

## Shared standards

- ../shared/decision-framework.md
- ../shared/severity-levels.md
- ../shared/templates/migration-plan-template.md
