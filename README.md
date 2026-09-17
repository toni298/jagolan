# Agent Engineering Skill Pack v1.1

A progressive-disclosure skill pack for AI coding agents.

## Core Idea

- `SKILL.md` is short and acts as an orchestrator.
- `references/` contains detailed knowledge.
- `manifest.yaml` helps skill routing.
- `registry.yaml` lists available skills.
- `dependency-map.yaml` explains recommended skill composition.
- `shared/` contains global standards and templates.

## Skills (25)

Foundation & planning
01. project-context
02. analysis
03. writing-plans
04. executing-plans

Development & quality
05. debugging
06. bug-investigation
07. git-version-control
08. test-driven-development
09. code-review
10. refactoring

Engineering domains
11. database-engineering
12. backend-engineering
13. frontend-engineering
14. integration-engineering

Advanced & senior
15. security-audit
16. performance-optimization
17. architecture-review
18. tech-lead

Operations
19. devops-engineering
20. observability
21. release-management
22. incident-response

Specialized, product & support
23. ai-engineering
24. product-engineering
25. documentation

## How Agents Should Use This Pack

1. Read `skills/registry.yaml`.
2. Pick the smallest relevant skill set (see `skills/dependency-map.yaml`).
3. Read each selected skill's `SKILL.md`.
4. Read only relevant references.
5. Use shared references for severity, principles, and decision frameworks.
6. Avoid loading all references at once unless explicitly needed.

## Conventions

- Every `SKILL.md` follows the same shape: front matter (name + trigger-rich
  description), Purpose, When to use, Workflow, Anti-patterns, Output,
  Reference selection, and Shared standards.
- All files use LF line endings and UTF-8 encoding.
- Descriptions include explicit trigger phrases (English + common Indonesian
  terms) to aid auto-routing.

## Design Philosophy

This pack is global and domain-agnostic.

It should not assume a project is POS, marketplace, PPOB, SaaS, finance, CMS,
AI, mobile, or any specific product unless the user's project context says so.

## Changelog

### 1.1.0
- Added skills: git-version-control, integration-engineering, observability,
  release-management, incident-response, documentation.
- Rewrote thin SKILL.md files to a consistent, richer structure with explicit
  triggers, anti-patterns, and output sections.
- Normalized all line endings to LF; fixed the AI Engineering title.

### 1.0.0
- Initial 19-skill pack.
