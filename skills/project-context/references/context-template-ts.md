# Context Template — TypeScript Project

Gunakan template ini untuk project TypeScript murni (library, CLI, backend service).
Untuk fullstack, gunakan `context-template-fullstack.md`.

```markdown
# [Project Name]

## Overview

[Deskripsi singkat — apa yang dilakukan project ini dan untuk siapa]

## Stack

- Runtime: Node.js v[XX]
- Language: TypeScript [5.x]
- Framework: [Express / Fastify / Hono / none]
- Database: [PostgreSQL via Prisma / MongoDB via Mongoose / none]
- ORM/Query builder: [Prisma / Drizzle / Knex / none]
- Testing: [Vitest / Jest] + [Supertest untuk integration test]
- Package manager: [pnpm / npm / yarn]
- Build tool: [tsc / esbuild / tsup]

## TypeScript Config

- Target: [ES2022 / ESNext]
- Module: [CommonJS / ESM]
- Strict mode: [true/false]
- Path aliases: [@ → src/, dll]

## Project Structure
```

src/
├── routes/ # Route handlers
├── services/ # Business logic
├── repositories/ # Database access layer
├── middlewares/ # Express/Fastify middlewares
├── types/ # Shared TypeScript types & interfaces
├── utils/ # Pure utility functions
└── index.ts # Entry point

```

## Conventions
- Formatting: Prettier — 2 spaces, single quotes, trailing commas
- Naming:
  - Files: kebab-case (`user-service.ts`)
  - Classes: PascalCase
  - Functions/variables: camelCase
  - Types/Interfaces: PascalCase, Interface prefix `I` tidak dipakai
  - Constants: SCREAMING_SNAKE_CASE
- Imports: absolute dari `src/` menggunakan alias `@/`
- Error handling: custom Error classes di `src/errors/`, semua async handler pakai try/catch
- Exports: named exports, hindari default export kecuali untuk class utama

## Architecture Notes
[Isi dengan keputusan arsitektur yang sudah dibuat]
Contoh:
- "Semua database query lewat repository layer, service tidak boleh query langsung"
- "Validasi input pakai Zod di route level, sebelum masuk service"
- "Semua response mengikuti format `{ data, error, meta }`"

## Environment
- Dev: `pnpm dev` (tsx watch)
- Test: `pnpm test` (vitest)
- Build: `pnpm build` (tsup)
- Env vars: lihat `.env.example`

## Current Focus
[Update setiap sesi — apa yang sedang dikerjakan]

## Recent Decisions
- [YYYY-MM-DD] [Keputusan]: [Alasan]

## Known Issues / Constraints
- [Hal yang perlu diingat]
```
