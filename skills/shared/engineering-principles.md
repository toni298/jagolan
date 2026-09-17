# Engineering Principles

Use these principles across all skills.

## 1. Correctness Over Convenience

A solution that is easy but incorrect is not acceptable.

## 2. Security by Default

Authentication, authorization, input validation, secret handling, and safe output
must be considered early.

## 3. Explicit Over Implicit

Make ownership, boundaries, assumptions, and failure modes visible.

## 4. Simplicity Over Cleverness

Prefer understandable systems. Clever code increases maintenance risk.

## 5. Design for Failure

Networks fail, jobs retry, users double-click, providers send duplicate events,
and deployments break. Design accordingly.

## 6. Measure Before Optimizing

Do not guess performance bottlenecks when measurement is possible.

## 7. Prefer Reversible Decisions

When uncertainty is high, choose options that are easy to change later.

## 8. Minimize Blast Radius

Limit the damage of bugs, outages, bad deployments, and compromised credentials.

## 9. Maintain Clear Ownership

Every module, table, API, and responsibility should have an owner.

## 10. Preserve User Trust

Protect data, avoid silent corruption, communicate errors clearly, and recover safely.
