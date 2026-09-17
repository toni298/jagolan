# Transactions

## Goal

Ensure groups of writes succeed or fail together.

Use transactions when multiple changes form one logical operation.

## Use Transactions For

- multi-table writes
- state transitions with history
- create parent + children
- modifying counters/balances/quotas
- reserving limited resources
- any critical write where partial success is invalid

## Transaction Rules

- Keep transactions short.
- Lock only what is needed.
- Avoid slow external API calls inside transactions.
- Retry safe transaction blocks on deadlocks.
- Use proper isolation level for the problem.
- Log repeated transaction failures.

## Bad Pattern

```ts
await createParent()
await createChildren()
await updateQuota()
```

If the third write fails, the system is inconsistent.

## Good Pattern

```ts
await db.transaction(async (tx) => {
  await createParent(tx)
  await createChildren(tx)
  await updateQuota(tx)
})
```

## External Side Effects

Avoid doing this inside a long transaction:

- send email
- call payment provider
- upload file
- call AI provider
- wait for user
- heavy computation

Prefer:

1. commit database state
2. enqueue job/event
3. worker performs side effect
4. update status idempotently

## Isolation Levels

Understand:

- read committed
- repeatable read
- serializable

Default isolation may not prevent every race condition.

When correctness matters, combine transactions with row locks, constraints, or
optimistic locking.

## Checklist

- [ ] Multi-step critical writes use transactions.
- [ ] External side effects are not inside long transactions.
- [ ] Transaction scope is short.
- [ ] Deadlock retry strategy exists where needed.
- [ ] Database constraints complement application checks.
