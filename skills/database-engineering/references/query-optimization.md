# Query Optimization

## Rules

- Avoid SELECT *.
- Limit selected columns.
- Avoid N+1 queries.
- Use pagination.
- Batch repeated lookups.
- Move heavy reports to async jobs or read replicas.
- Review query plans.

## Anti-Patterns

- unbounded exports over HTTP
- LIKE '%keyword%' on huge tables without search index
- counting massive hot tables synchronously
- loading all records into memory

## Checklist

- [ ] Hot queries are bounded.
- [ ] N+1 is avoided.
- [ ] Large reports are async.
- [ ] Query plans are reviewed.
