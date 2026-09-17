# Authorization

## Goal

Ensure authenticated actors can only access allowed resources and actions.

## Rules

- Check authorization on the backend.
- Do not rely on hidden frontend buttons.
- Scope resources by owner, tenant, organization, or permission.
- Centralize policy logic.
- Use least privilege.
- Audit sensitive actions.

## Common Models

- RBAC: role-based access control
- ABAC: attribute-based access control
- PBAC: policy-based access control
- ownership-based access
- tenant-based access

Most real systems combine several.

## Insecure Direct Object Reference

Bad:

```ts
const resource = await db.resource.findUnique({ where: { id } })
```

Good:

```ts
const resource = await db.resource.findFirst({
  where: {
    id,
    ownerId: currentUser.id
  }
})
```

or tenant-scoped:

```ts
where: {
  id,
  tenantId: currentTenant.id
}
```

## Policy Function

Prefer:

```ts
await policy.require(user, "resource.update", resource)
```

over scattered role string checks.

## Checklist

- [ ] Every protected endpoint authenticates.
- [ ] Every resource access authorizes.
- [ ] Object queries are owner/tenant scoped.
- [ ] Admin actions are audited.
- [ ] Policy logic is centralized.
- [ ] Least privilege is applied.
