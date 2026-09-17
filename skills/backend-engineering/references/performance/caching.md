# Caching

## Goal

Improve performance without breaking correctness or leaking data.

## Cache When

- data is frequently read
- data changes infrequently
- computation is expensive
- external dependency is slow
- short staleness is acceptable

## Avoid Cache When

- authorization varies and cache key is unsafe
- data must always be fresh
- invalidation is unclear
- source query is already fast

## Cache Key Design

Include every value that changes output:

```text
tenant:{tenantId}:user:{userId}:resource:{filters}:cursor:{cursor}
```

Never share cached private data across users or tenants.

## Invalidation

Strategies:

- TTL
- explicit invalidation on write
- versioned keys
- stale-while-revalidate
- event-based invalidation

## Checklist

- [ ] Cache key includes auth/tenant scope.
- [ ] Invalidation strategy is defined.
- [ ] Sensitive data is not globally cached.
- [ ] Cache stampede is considered.
- [ ] Source of truth remains clear.
