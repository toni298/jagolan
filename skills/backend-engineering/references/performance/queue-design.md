# Queue Design

## Goal

Move slow or retryable work out of the request path safely.

## Use Queues For

- email and notifications
- file processing
- report generation
- external API sync
- heavy computation
- webhook processing
- scheduled workflows

## Rules

- Jobs must be idempotent.
- Jobs must have retry limits.
- Failed jobs must be inspectable.
- Payloads should be small.
- Jobs should process bounded batches.
- Use dead-letter queues or failed job storage.

## Cron Jobs

Cron jobs must be:

- idempotent
- non-overlapping when dangerous
- monitored
- logged
- bounded

## Checklist

- [ ] Slow work is queued.
- [ ] Jobs are idempotent.
- [ ] Retries have limits.
- [ ] Failed jobs are visible.
- [ ] Cron jobs cannot overlap dangerously.
- [ ] Job payloads are small.
