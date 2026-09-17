# Deployment Strategies

## Common Strategies

- rolling deployment
- blue-green deployment
- canary release
- feature flags
- maintenance window

## Safe Deployment Rules

- validate environment variables
- run migrations safely
- health check before traffic
- monitor error rate after deploy
- support rollback

## Large Schema Change

Use expand-contract:

1. add backward-compatible schema
2. deploy compatible app code
3. backfill data
4. switch reads/writes
5. remove old schema later
