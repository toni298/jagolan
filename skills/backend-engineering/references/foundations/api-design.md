# API Design

## Goal

Design APIs that are predictable, secure, versionable, and easy to consume.

APIs are contracts. A working endpoint is not enough; it must remain stable under
client usage, retries, invalid input, and future changes.

## Principles

- Use consistent resource naming.
- Validate every input server-side.
- Return consistent response shapes.
- Use correct HTTP status codes.
- Paginate list endpoints by default.
- Hide internal fields.
- Scope every protected resource by user, tenant, or permission.
- Make dangerous operations idempotent.
- Keep side effects explicit.

## Good REST Shape

```http
GET    /api/v1/resources
GET    /api/v1/resources/:id
POST   /api/v1/resources
PATCH  /api/v1/resources/:id
DELETE /api/v1/resources/:id
```

Avoid inconsistent action endpoints unless the operation is truly not CRUD:

```http
POST /api/v1/resources/:id/archive
POST /api/v1/resources/:id/restore
```

## Response Shape

Use a consistent format:

```json
{
  "data": {},
  "meta": {},
  "error": null
}
```

For errors:

```json
{
  "data": null,
  "meta": {},
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Invalid request",
    "details": {}
  }
}
```

## Status Codes

Use:

- `200` for successful read/update
- `201` for created
- `202` for accepted async work
- `204` for successful no-body response
- `400` for malformed request
- `401` for unauthenticated
- `403` for authenticated but forbidden
- `404` for not found
- `409` for conflict
- `422` for semantic validation failure
- `429` for rate limit
- `500` for unexpected server error

## List Endpoints

Never return unbounded lists.

Good:

```http
GET /api/v1/resources?limit=50&cursor=...
```

Support:

- limit
- cursor
- filters
- safe sorting

Whitelisted sorting only:

```ts
const sortMap = {
  createdAt: "created_at",
  name: "name"
}
```

Never interpolate raw sort parameters into SQL.

## Dangerous Write Operations

For operations that create irreversible or expensive effects:

- authenticate
- authorize
- validate
- use idempotency key
- use transaction when multiple writes must succeed together
- log request ID
- return traceable operation ID

Examples of dangerous operations are domain-dependent, but usually include:
state transitions, external side effects, critical data mutation, expensive jobs,
and anything that must not happen twice.

## Anti-Patterns

### Returning Database Models Directly

Risk: leaks private fields.

Prefer serializers or explicit DTOs.

### Authentication Without Authorization

Logged in does not mean allowed.

Always check the resource boundary.

### Overloaded Endpoint

If one endpoint handles validation, authorization, persistence, external calls,
and response formatting, split responsibilities.

## Checklist

- [ ] Inputs are validated.
- [ ] Errors are consistent.
- [ ] Protected resources check authorization.
- [ ] Lists are paginated.
- [ ] Sort/filter fields are whitelisted.
- [ ] Sensitive fields are hidden.
- [ ] Dangerous writes are idempotent where needed.
- [ ] External side effects are explicit.
