# Deployment

## Goal

Deploy safely without breaking production.

## Rules

- Use environment-specific config.
- Validate env vars at startup.
- Run migrations safely.
- Keep rollback strategy.
- Avoid long locks on large tables.
- Use health checks.
- Monitor after deployment.

## Safe Migration Pattern

For breaking schema changes:

1. deploy backward-compatible code
2. add nullable column/table
3. backfill in batches
4. switch code to new field
5. enforce constraints later
6. remove old field after safe period

## Checklist

- [ ] Environment config is validated.
- [ ] Health checks exist.
- [ ] Migrations are backward compatible where needed.
- [ ] Rollback plan exists.
- [ ] Deployment is monitored.
