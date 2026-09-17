# Docker

## Rules

- use small base images when practical
- avoid running as root
- copy only required files
- use .dockerignore
- separate build and runtime stages
- expose only needed ports
- keep secrets out of image layers

## Checklist

- [ ] Image does not contain secrets.
- [ ] Container runs as non-root when possible.
- [ ] Health check exists.
