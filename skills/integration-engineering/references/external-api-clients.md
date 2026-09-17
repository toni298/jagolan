# External API clients

Treat every external call as untrusted and potentially slow or down.

## Resilience checklist

- **Timeouts:** always set connect + read timeouts. No unbounded calls.
- **Retries:** only for transient errors (network, 429, 5xx). Use exponential
  backoff with jitter and a max attempt cap.
- **Idempotency:** send an idempotency key for state-changing calls so a retry
  cannot double-apply (e.g., double charge).
- **Circuit breaker:** stop hammering a failing dependency; fail fast and
  recover gradually.
- **Fallback/degrade:** define behavior when the dependency is unavailable
  (cache, queue for later, partial response).
- **Rate limits:** respect the provider's limits; throttle on your side.

## Design

- Wrap the vendor behind your own interface (anti-corruption layer) so vendor
  shapes and quirks don't spread through your code.
- Map vendor errors to your error model; preserve enough detail to debug.
- Never block a user request on a slow non-critical integration — do it async.
- Keep credentials in config/secrets, never in code.
