# CI/CD

## Goals

- repeatable builds
- automated tests
- safe releases
- fast rollback

## Pipeline Stages

1. install dependencies
2. lint
3. type check
4. test
5. build
6. security scan
7. deploy to staging
8. smoke test
9. deploy production

## Rules

- Do not deploy untested code.
- Keep secrets out of logs.
- Make deployments reproducible.
- Fail fast on broken build.
- Prefer small, frequent releases.

## Checklist

- [ ] Build is reproducible.
- [ ] Tests run before deployment.
- [ ] Secrets are not printed.
- [ ] Rollback path exists.
