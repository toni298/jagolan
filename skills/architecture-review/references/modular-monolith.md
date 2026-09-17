# Modular Monolith

A modular monolith is often the best starting point.

## Rules

- modules own their logic
- avoid cross-module database writes
- expose module APIs/services
- keep dependency direction clear
- extract services only when operational need exists

Avoid premature microservices.
