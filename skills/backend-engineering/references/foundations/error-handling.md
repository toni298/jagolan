# Error Handling

## Goal

Return safe, consistent errors to clients while preserving enough internal detail
for debugging.

## Rules

- Do not expose stack traces in production.
- Do not leak secrets, SQL, filesystem paths, or internal service names.
- Use stable error codes.
- Log internal details with request ID.
- Return user-safe messages.
- Preserve original error cause internally.

## Error Categories

- validation error
- authentication error
- authorization error
- not found
- conflict
- rate limit
- dependency failure
- unexpected server error

## Safe Client Error

```json
{
  "error": {
    "code": "RESOURCE_NOT_FOUND",
    "message": "Resource not found"
  }
}
```

## Internal Log

Log:

- request_id
- user_id when available
- tenant_id when available
- route
- error class
- stack trace
- dependency involved
- duration
- relevant safe metadata

## Retryable vs Non-Retryable

Classify errors:

Retryable:

- timeout
- temporary dependency outage
- deadlock
- rate-limited external service

Non-retryable:

- validation failure
- forbidden
- invalid state transition
- duplicate unique constraint when expected

## Checklist

- [ ] Client errors are consistent.
- [ ] Production responses do not leak internals.
- [ ] Logs include request IDs.
- [ ] Retryable errors are classified.
- [ ] Dependency errors are handled explicitly.
