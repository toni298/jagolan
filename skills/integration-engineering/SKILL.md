---
name: integration-engineering
description: >
  Integrate with third-party and external APIs reliably: clients, retries,
  timeouts, webhooks, idempotency, and failure isolation. Trigger this skill
  whenever the user says "integrate", "third-party API", "webhook", "external
  service", "payment gateway", "sync with", or consumes someone else's API.
  Treat external systems as untrusted and unreliable; never let a slow or
  failing dependency take down your own system.
---

# Integration Engineering

## Purpose

Consume external services so that their slowness, failures, and quirks do not
break your system or corrupt your data.

## When to use

- Calling a third-party/external API or SDK.
- Receiving webhooks from an external provider.
- Syncing data with another system.

## Workflow

1. Read the provider's contract: auth, rate limits, error codes, idempotency.
2. Wrap the integration behind your own interface (anti-corruption layer).
3. Set explicit timeouts; never make an unbounded external call.
4. Add retries with backoff + jitter for transient failures only.
5. Make calls idempotent (idempotency keys) so retries don't double-charge.
6. Isolate failures with circuit breakers/fallbacks so one dependency can't
   cascade.
7. For webhooks: verify signatures, dedupe deliveries, respond fast, process
   async.
8. Map external errors into your own error model; log enough to debug.

## Anti-patterns

- External calls with no timeout (one slow vendor stalls everything).
- Retrying non-idempotent operations and causing duplicates.
- Trusting webhook payloads without verifying signatures or deduping.
- Leaking the vendor's data shapes throughout your codebase.

## Output

- A wrapped, timed, retry-safe, idempotent integration that fails in isolation.

## Reference selection

Read only what is relevant:

- references/external-api-clients.md — timeouts, retries, resilience.
- references/webhooks.md — receiving events safely.

## Shared standards

- ../shared/engineering-principles.md
- ../shared/severity-levels.md
- ../shared/decision-framework.md
