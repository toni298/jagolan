# Severity Levels

Use these levels for reviews, audits, bugs, and risk reports.

## CRITICAL

Immediate severe risk.

Examples:

- data loss
- authentication bypass
- remote code execution
- privilege escalation
- exposed production secrets
- financial or critical state corruption

Expected action: stop release or fix immediately.

## HIGH

Serious production risk.

Examples:

- broken authorization
- exploitable injection
- major race condition
- persistent XSS
- severe performance bottleneck
- unsafe migration on large table

Expected action: fix before release unless explicitly accepted.

## MEDIUM

Meaningful risk.

Examples:

- reliability issue
- missing validation
- moderate performance issue
- maintainability problem likely to cause bugs
- missing tests around important behavior

Expected action: fix soon or track with owner.

## LOW

Minor issue.

Examples:

- naming clarity
- small duplication
- style inconsistency
- non-critical cleanup

Expected action: fix opportunistically.
