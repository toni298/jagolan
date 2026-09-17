# Safe Refactoring

Rules:

- keep behavior unchanged
- add tests before risky changes
- commit small steps
- rename for clarity
- extract functions/services
- reduce duplication
- avoid large rewrites unless requested

Checklist:

- [ ] Behavior preserved.
- [ ] Tests still pass.
- [ ] Change is smaller than rewrite.
