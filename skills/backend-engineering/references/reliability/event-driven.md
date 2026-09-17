# Event-Driven Backend Design

## Goal

Separate core state changes from side effects while preserving reliability.

## Use Events For

- notifications
- audit trail
- background processing
- integrations
- cache invalidation
- search indexing
- analytics
- async workflows

## Rules

- Commit core state before side effects.
- Events should be immutable facts.
- Event handlers must be idempotent.
- Use retry and dead-letter handling.
- Avoid hiding critical synchronous requirements behind events.

## Outbox Pattern

When reliable event publishing matters:

1. write business data in transaction
2. write event to outbox table in same transaction
3. background worker publishes/processes outbox
4. mark event processed

This avoids losing events after DB commit.

## Event Naming

Use past-tense facts:

- user.created
- resource.updated
- job.completed
- access.revoked

Avoid command-like event names:

- createUser
- sendEmailNow

## Checklist

- [ ] Events represent facts.
- [ ] Handlers are idempotent.
- [ ] Critical events are not lost on crash.
- [ ] Failed events are retryable.
- [ ] Dead-letter handling exists.
