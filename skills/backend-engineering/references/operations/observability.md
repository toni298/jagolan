# Observability

## Goal

Make production behavior visible and debuggable.

## Signals

- logs
- metrics
- traces
- alerts
- audit records

## Structured Logs

Include:

- timestamp
- level
- request_id
- user_id when safe
- tenant_id when applicable
- route
- status_code
- latency_ms
- error_code

Never log secrets.

## Metrics

Track:

- request count
- latency
- error rate
- DB latency
- queue depth
- job failures
- dependency failures
- cache hit ratio

## Alerts

Alert on actionable failures:

- high error rate
- queue backlog
- database saturation
- missed cron
- backup failure
- external dependency outage

## Checklist

- [ ] Logs are structured.
- [ ] Request IDs propagate.
- [ ] Metrics cover API, DB, queue, dependencies.
- [ ] Alerts are actionable.
- [ ] Sensitive actions are audited.
