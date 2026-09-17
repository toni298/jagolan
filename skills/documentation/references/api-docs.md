# API documentation

Document the contract, not the implementation. A caller should be able to use
the endpoint/function correctly without reading its source.

For each endpoint or public function, document:

- **Purpose** — one sentence on what it does.
- **Inputs** — parameters/body with types, required/optional, and constraints.
- **Output** — success shape and status codes.
- **Errors** — each failure mode, its condition, and the error shape/code.
- **Side effects** — what state changes, what it calls, idempotency.
- **Auth** — required permissions/scopes.
- **Example** — a minimal request and response.

Tips:

- Document error and edge-case behavior explicitly — it is the most-used and
  least-documented part of any API.
- Keep examples realistic and runnable (real field names, plausible values).
- Generate from types/schemas where possible so docs cannot drift from code.
- Note pagination, rate limits, and versioning where they apply.
