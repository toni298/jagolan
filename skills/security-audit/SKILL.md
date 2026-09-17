---
name: security-audit
description: >
  Audit applications for common security risks: authentication, authorization,
  input validation, injection, XSS, CSRF, SSRF, secrets, file uploads,
  dependencies, and API abuse. Trigger this skill whenever the user asks to
  "check security", "audit", "is this safe", handles auth/payments/PII, exposes
  a new endpoint, or accepts user input/uploads. Always reason from trust
  boundaries and untrusted input; never assume the client or network is safe.
---

# Security Audit

## Purpose

Find and prioritize security weaknesses before attackers do, focusing on the
most common and highest-impact classes of vulnerability.

## When to use

- Reviewing code that handles auth, money, PII, or external input.
- Before exposing a new endpoint, upload, or integration.
- Periodic review of dependencies and secrets handling.

## Workflow

1. Map trust boundaries and identify all user-controlled input.
2. Review authentication and session handling.
3. Review authorization on every protected action (object- and function-level).
4. Check input handling for injection, XSS, SSRF, and unsafe deserialization.
5. Review secrets, file uploads, and third-party dependency risk.
6. Check for abuse vectors: rate limits, enumeration, mass assignment.
7. Rate each finding by severity and give a concrete remediation.

## Anti-patterns

- Relying on client-side checks for authorization.
- Trusting input because it "came from our own frontend".
- Logging secrets/PII, or storing secrets in code/repo.
- Reporting findings without severity or a fix.

## Output

- A prioritized findings list (severity, impact, location, remediation).

## Reference selection

Read only what is relevant:

- references/owasp-top-10.md — the baseline risk catalog.
- references/access-control.md — authn/authz failures.
- references/injection.md — SQL/NoSQL/command/SSRF injection.
- references/xss-csrf.md — browser-side attacks.
- references/secrets-files-dependencies.md — secrets, uploads, supply chain.

## Shared standards

- ../shared/severity-levels.md
- ../shared/engineering-principles.md
- ../shared/decision-framework.md
