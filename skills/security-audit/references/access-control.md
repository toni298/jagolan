# Access Control

## Rules

- Backend must enforce authorization.
- Scope object queries by owner/tenant/permission.
- Do not rely on hidden UI.
- Centralize policy checks.
- Audit privileged actions.

## Checklist

- [ ] IDOR is prevented.
- [ ] Admin actions are protected.
- [ ] Tenant boundaries are enforced.
