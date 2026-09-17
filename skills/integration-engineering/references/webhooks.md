# Webhooks (receiving events)

Inbound webhooks are untrusted input from the internet. Treat them defensively.

## Receiving safely

1. **Verify authenticity:** check the provider's signature (HMAC) before doing
   anything else. Reject if it fails.
2. **Respond fast:** acknowledge with 2xx quickly; do real work asynchronously
   (enqueue). Slow handlers cause provider retries and duplicates.
3. **Deduplicate:** providers deliver at-least-once. Use the event ID to ignore
   duplicates (idempotent processing).
4. **Tolerate out-of-order and replays:** rely on event timestamps/versioning,
   not arrival order.
5. **Validate payloads:** never trust the body shape; validate before use.

## Operational

- Make processing idempotent so retries are safe.
- Log event id, type, and outcome for debugging and reconciliation.
- Have a reconciliation/backfill path for missed events (polling the API).
- Return non-2xx only when you actually want the provider to retry.
