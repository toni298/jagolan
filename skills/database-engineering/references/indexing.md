# Indexing

## Goal

Keep queries fast as tables grow.

## Rules

- Index foreign keys.
- Index columns used in WHERE, JOIN, ORDER BY.
- Use composite indexes that match query patterns.
- Avoid indexing everything.
- Check execution plans for important queries.
- Use cursor pagination for deep lists.

## Composite Index Example

Query:

```sql
WHERE tenant_id = ?
  AND status = ?
ORDER BY created_at DESC
```

Index:

```sql
(tenant_id, status, created_at)
```

## Checklist

- [ ] Foreign keys are indexed.
- [ ] Hot list queries have matching indexes.
- [ ] Index order matches filters and sorting.
- [ ] Deep pagination avoids OFFSET.
