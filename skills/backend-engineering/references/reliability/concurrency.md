# Concurrency

## Goal

Prevent incorrect results when multiple requests, workers, or processes operate
on the same data at the same time.

Concurrency bugs are usually invisible in local development.

## Common Problems

- lost update
- duplicate execution
- stale read
- inconsistent state
- invalid state transition
- oversubscription of limited resources
- duplicate unique business values
- concurrent edit conflicts

## Core Strategies

- atomic updates
- database constraints
- transactions
- row-level locking
- optimistic locking
- idempotency
- distributed locks
- explicit state machines

## Read-Check-Write Race

Bad:

```ts
const item = await getItem(id)
if (item.available > 0) {
  await updateItem(id, { available: item.available - 1 })
}
```

Two requests can both read the same value.

Better:

```sql
UPDATE resources
SET available = available - 1
WHERE id = ?
  AND available > 0;
```

Check affected row count.

## Unique Constraint Race

Bad:

```ts
const exists = await findByEmail(email)
if (!exists) await createUser(email)
```

Good:

- add unique constraint
- attempt insert
- handle duplicate error

## Optimistic Locking

Add a version column.

```sql
UPDATE documents
SET content = ?, version = version + 1
WHERE id = ?
  AND version = ?;
```

If zero rows updated, the record changed concurrently.

## Pessimistic Locking

Use row lock when correctness is more important than concurrency.

```sql
SELECT *
FROM resources
WHERE id = ?
FOR UPDATE;
```

Inside transaction.

## Distributed Locks

Use when multiple processes must coordinate across instances.

Rules:

- lock has TTL
- lock has owner token
- always release safely
- handle lock acquisition failure
- do not rely on lock alone if DB constraint can enforce invariant

## Checklist

- [ ] Critical read-check-write paths are identified.
- [ ] Atomic updates or locks protect limited resources.
- [ ] Unique constraints enforce uniqueness.
- [ ] Optimistic locking protects concurrent edits.
- [ ] Distributed locks are used only when necessary.
- [ ] Duplicate execution is safe or prevented.
