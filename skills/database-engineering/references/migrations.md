# Migrations

## Rules

- Migrations must be safe for existing data.
- Avoid long locks on large tables.
- Backfill in batches.
- Deploy backward-compatible changes first.
- Add constraints after data is clean.
- Always have rollback or recovery plan.

## Expand-Contract Pattern

1. Add new structure.
2. Write to both old and new if needed.
3. Backfill.
4. Read from new.
5. Remove old later.

## Checklist

- [ ] Migration is backward compatible.
- [ ] Large backfills are batched.
- [ ] Rollback/recovery is planned.
