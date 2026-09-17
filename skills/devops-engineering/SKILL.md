---
name: devops-engineering
description: >
  Design deployment, CI/CD, infrastructure, runtime configuration, backup, and
  operational workflows for production apps. Trigger this skill whenever the
  user mentions "deploy", "CI", "pipeline", "Docker", "infra", "environment",
  "rollback", "backup", "config", or "production". Default to safe,
  reversible, automated changes; never deploy without a rollback path and never
  put secrets in images or version control.
---

# DevOps Engineering

## Purpose

Make software ship and run reliably in production through automated,
reversible delivery, sane configuration, and recoverable infrastructure.

## When to use

- Setting up or changing CI/CD, containers, or deploy strategy.
- Managing runtime configuration and secrets across environments.
- Planning backups, recovery, or rollback procedures.

## Workflow

1. Define environments and the promotion path (dev → staging → prod).
2. Make builds reproducible; pin versions and produce a single artifact.
3. Automate test + build + deploy in CI; fail fast and visibly.
4. Externalize config; inject secrets at runtime, never bake them in.
5. Choose a deploy strategy with a rollback (blue/green, canary, rolling).
6. Ensure backups exist and recovery is tested, not assumed.
7. Confirm health checks and basic observability before/after deploy.

## Anti-patterns

- Manual, non-reproducible deploys with no rollback.
- Secrets in Docker images, repos, or CI logs.
- Backups that are never test-restored.
- Config differences between environments that are not tracked.

## Output

- An automated pipeline, environment/config plan, and a tested rollback path.

## Reference selection

Read only what is relevant:

- references/ci-cd.md — pipeline design.
- references/docker.md — reproducible images.
- references/deployment-strategies.md — blue/green, canary, rolling, rollback.
- references/runtime-config.md — config and secrets across environments.
- references/observability.md — health, logs, metrics for operations.
- references/backup-recovery.md — backups and tested recovery.

## Shared standards

- ../shared/engineering-principles.md
- ../shared/severity-levels.md
- ../shared/templates/incident-postmortem-template.md
