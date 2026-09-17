# Large Tables

## Rules

For tables that may reach millions of rows:

- avoid OFFSET pagination
- use cursor pagination
- keep rows narrow
- archive old data when appropriate
- partition only with clear query pattern
- avoid synchronous full-table counts
- add indexes for actual access patterns

## Checklist

- [ ] Pagination strategy scales.
- [ ] Archival/retention is considered.
- [ ] Reporting does not overload hot tables.
