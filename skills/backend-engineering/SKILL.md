---
name: backend-engineering
description: >
  Design and implement production-ready JavaScript/TypeScript backend systems
  for any application domain. Focus on API design, validation, auth boundaries,
  database safety, transactions, concurrency, idempotency, queues, caching,
  observability, scalability, and operational resilience.
---

# Backend Engineering

Use this skill when building, reviewing, or improving a backend system.

This skill is global and domain-agnostic. Do not assume the project is POS,
marketplace, finance, inventory, SaaS, CMS, chat, AI, or any other specific
domain unless the user or project context says so.

## Core Goal

Produce backend designs and implementations that are safe under real production
conditions, not just basic CRUD.

A production backend must handle:

- invalid input
- malicious users
- authorization boundaries
- duplicate requests
- concurrent requests
- large datasets
- slow external services
- failed jobs
- retries
- observability
- deployment and recovery

## Workflow

1. Identify the backend context, runtime, framework, and data ownership.
2. Define API boundaries and validation.
3. Define authentication and authorization requirements.
4. Design data access and persistence boundaries.
5. Identify critical write paths and consistency requirements.
6. Apply transactions, locking, idempotency, or constraints when required.
7. Decide which work is synchronous and which belongs in queues/events.
8. Add caching only when key design and invalidation are clear.
9. Add logging, metrics, tracing, and operational failure handling.
10. Document risks, assumptions, and tradeoffs.

## Required Reference Selection

Read only the references relevant to the task:

- references/foundations/api-design.md
- references/foundations/validation.md
- references/foundations/error-handling.md
- references/reliability/transactions.md
- references/reliability/concurrency.md
- references/reliability/idempotency.md
- references/reliability/event-driven.md
- references/security/authentication.md
- references/security/authorization.md
- references/security/rate-limiting.md
- references/performance/caching.md
- references/performance/queue-design.md
- references/operations/observability.md
- references/operations/deployment.md

## Non-Negotiable Rules

- Never rely only on frontend validation.
- Never expose data without authorization checks.
- Never process dangerous writes without considering idempotency.
- Never perform multi-step critical writes without considering transactions.
- Never use unbounded list endpoints.
- Never add caching without a cache key and invalidation strategy.
- Never hide uncertainty; state assumptions clearly.
