# Validation

## Goal

Reject bad data at system boundaries before it enters business logic.

TypeScript types do not validate external input at runtime.

## Validate All Boundaries

Validate:

- HTTP body
- query parameters
- route parameters
- headers
- cookies
- uploaded files
- webhook payloads
- queue payloads
- environment variables
- data from third-party APIs

## Rules

- Validate at the edge.
- Convert raw input into typed internal objects.
- Reject unknown fields when strictness matters.
- Validate length, format, range, enum values, and relationships.
- Never trust client-provided IDs for ownership.
- Validate again before critical persistence if data crosses trust boundaries.

## Runtime Schema

Prefer runtime schema validation in JavaScript/TypeScript:

- Zod
- Valibot
- TypeBox
- ArkType
- framework validators

Bad:

```ts
const page = req.query.page as number
```

Good:

```ts
const input = schema.parse(req.query)
```

## Semantic Validation

Syntactic validation checks shape.

Semantic validation checks meaning.

Example:

- date format is valid
- start date is before end date
- user has access to referenced resource
- state transition is allowed
- quantity is compatible with business rule

## External Data

Data from external APIs must also be validated.

Do not assume provider responses are stable.

## Environment Validation

Validate required env vars at startup.

Fail fast if missing:

- database URL
- auth secret
- API keys
- object storage config
- environment name

## Checklist

- [ ] Every external input is validated.
- [ ] Validation happens before business logic.
- [ ] Unknown fields are handled intentionally.
- [ ] Semantic validation exists for critical operations.
- [ ] Environment config is validated at startup.
- [ ] External API responses are validated before use.
