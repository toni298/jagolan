# Constraints

Use database constraints to protect data even when application code has bugs.

## Use

- NOT NULL
- UNIQUE
- FOREIGN KEY
- CHECK
- composite unique indexes

Application validation is not a replacement for constraints.

## Examples

- unique email per tenant
- one active subscription per account
- non-negative quantity when required
- valid enum/status values where supported

## Checklist

- [ ] Critical invariants exist in DB.
- [ ] Foreign keys are intentional.
- [ ] Unique business rules are enforced.
- [ ] Nullable fields are justified.
