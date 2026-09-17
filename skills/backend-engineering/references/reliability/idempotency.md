# Idempotency

## Goal

Make repeated requests safe.

A repeated operation should not create duplicate irreversible effects.

## Why Repetition Happens

- user double-clicks
- browser retry
- mobile retry
- network timeout
- worker retry
- webhook retry
- client refresh
- server crash after success but before response

## When Required

Use idempotency for dangerous operations:

- critical state transitions
- external side effects
- resource allocation
- long-running job creation
- webhook processing
- bulk operations
- any operation that must not happen twice

## Idempotency Key

A client or server provides a stable operation key.

Store:

- key
- actor/user/tenant scope
- request hash
- status
- response or operation ID
- expiration

Same key + same request = return same result.

Same key + different request = conflict.

## Database Constraint

Use unique constraint:

```sql
UNIQUE(scope_id, idempotency_key)
```

Application checks alone are not enough.

## Checklist

- [ ] Dangerous operations have idempotency.
- [ ] Key is scoped by actor/tenant/context.
- [ ] Request hash prevents accidental reuse.
- [ ] Duplicate calls return safe result.
- [ ] Idempotency records expire when safe.
