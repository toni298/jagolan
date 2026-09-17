# Runtime Configuration

## Rules

- validate env vars on startup
- separate config by environment
- never commit secrets
- document required variables
- use least privilege credentials

## Checklist

- [ ] Missing env vars fail startup.
- [ ] Production secrets are secure.
- [ ] Development and production configs are separated.
