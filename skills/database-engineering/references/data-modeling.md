# Data Modeling

## Principles

- Model domain concepts, not pages or forms.
- Prefer explicit relationships.
- Normalize first, denormalize only with reason.
- Store immutable history for critical events.
- Define ownership and lifecycle for every table.
- Avoid ambiguous generic columns like `type`, `status`, or `data` without clear constraints.

## Questions

- Who owns this record?
- Can it be deleted?
- Should changes be audited?
- What queries will read it?
- What uniqueness must be enforced?
- What happens when related data is removed?

## Checklist

- [ ] Entities represent real domain concepts.
- [ ] Relationships are explicit.
- [ ] Critical uniqueness is enforced.
- [ ] Lifecycle and deletion rules are clear.
