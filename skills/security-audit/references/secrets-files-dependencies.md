# Secrets, Files, and Dependencies

## Secrets

- never commit secrets
- rotate exposed keys
- validate env config
- avoid logging tokens

## Files

- validate size, type, extension, and content signature
- store outside executable path
- generate safe names
- prevent path traversal

## Dependencies

- audit dependencies
- avoid abandoned packages
- patch critical vulnerabilities
