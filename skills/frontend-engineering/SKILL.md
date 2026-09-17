---
name: frontend-engineering
description: >
  Build maintainable, accessible, performant frontend applications. Trigger
  this skill whenever the user works on UI, components, pages, forms, client
  state, data fetching, or says "frontend", "komponen", "halaman", "UI",
  "React", "render", or "a11y". This skill is domain-agnostic. Never put
  business rules only in the client, and never ship UI that is unusable by
  keyboard or screen reader.
---

# Frontend Engineering

## Purpose

Design and implement UI that is correct, accessible, performant, and easy to
change, with clear component boundaries and well-located state.

## When to use

- Building or refactoring components, pages, or forms.
- Deciding where state lives or how to fetch/cache server data.
- Fixing UX reliability, accessibility, or rendering performance issues.

## Workflow

1. Understand the user flow and the UI states (loading, empty, error, success).
2. Design component boundaries around responsibilities, not visuals.
3. Choose the narrowest state location (local > lifted > shared > server cache).
4. Handle async states explicitly; never assume data is present.
5. Build for accessibility from the start (semantics, focus, keyboard, labels).
6. Validate inputs on the client for UX, but never trust the client for security.
7. Measure and budget render/bundle cost for hot paths.

## Anti-patterns

- Treating client validation as a security boundary.
- Global state for data that belongs to one component or to the server cache.
- Ignoring loading/error/empty states.
- Inaccessible custom controls (div-as-button, no focus management).

## Output

- Components with explicit states, accessible markup, and justified state location.

## Reference selection

Read only what is relevant:

- references/component-design.md — boundaries and composition.
- references/state-management.md — where state should live.
- references/accessibility.md — a11y essentials.
- references/performance.md — render and bundle performance.

## Shared standards

- ../shared/engineering-principles.md
- ../shared/severity-levels.md
- ../shared/decision-framework.md
