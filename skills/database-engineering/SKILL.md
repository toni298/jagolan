---
name: database-engineering
description: >
  Design reliable, scalable databases for any domain: modeling, constraints,
  indexing, query patterns, migrations, transactions, and growth. Trigger this
  skill whenever the user designs a schema, adds a table/column, writes a
  migration, reports a slow query, or says "database", "schema", "migration",
  "index", "query". Model from domain invariants, not UI screens, and never
  ship a migration that can lose data or lock a large table unsafely.
---

# Database Engineering

## Purpose

Design and evolve data models that protect invariants, perform well at scale,
and can be migrated safely without downtime or data loss.

## When to use

- Designing or changing a schema, or adding relationships.
- Writing or reviewing a migration.
- Diagnosing slow queries or planning for large-table growth.

## Workflow

1. Understand data ownership, lifecycle, and consistency requirements.
2. Model entities and relationships from domain concepts, not screens.
3. Enforce invariants with constraints (PK/FK/unique/check/not-null).
4. Design indexes for real query patterns; avoid speculative indexes.
5. Plan migrations to be backward-compatible and reversible (expand/contract).
6. Use transactions for multi-step invariants; keep them short.
7. Plan for growth: partitioning, archival, and hot/cold data paths.

## Anti-patterns

- Modeling tables to mirror UI forms instead of domain invariants.
- Enforcing invariants only in application code, not in the schema.
- Destructive, non-reversible migrations run in one irreversible step.
- Adding/dropping columns or indexes on large tables without a safe strategy.

## Output

- A schema with constraints, an indexing rationale, and a safe migration plan.

## Reference selection

Read only what is relevant:

- references/data-modeling.md — entities, relationships, normalization.
- references/constraints.md — protecting invariants in the schema.
- references/indexing.md — indexes for real query patterns.
- references/query-optimization.md — diagnosing slow queries.
- references/migrations.md — safe, reversible schema change.
- references/large-tables.md — growth, partitioning, archival.

## Shared standards

- ../shared/engineering-principles.md
- ../shared/severity-levels.md
- ../shared/templates/migration-plan-template.md
