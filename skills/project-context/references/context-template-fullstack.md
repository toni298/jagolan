# Context Template — Fullstack Project

Gunakan template ini untuk fullstack project: Next.js, SvelteKit, Nuxt, atau kombinasi
frontend + backend terpisah (misalnya React + Express/Hono).

```markdown
# [Project Name]

## Overview

[Deskripsi singkat — apa yang dilakukan project ini dan untuk siapa]

## Stack

### Frontend

- Framework: [Next.js 14 App Router / Next.js Pages / SvelteKit / Nuxt]
- Styling: [Tailwind CSS / CSS Modules / styled-components]
- State management: [Zustand / Jotai / Redux Toolkit / Context API]
- Data fetching: [TanStack Query / SWR / built-in fetch]
- UI components: [shadcn/ui / Radix / Headless UI / custom]

### Backend

- Runtime: Node.js v[XX]
- Framework: [Next.js API Routes / Express / Hono / Fastify]
- Database: [PostgreSQL / MySQL / SQLite / MongoDB]
- ORM: [Prisma / Drizzle]
- Auth: [NextAuth / Lucia / Clerk / custom JWT]

### Shared

- Language: TypeScript [5.x]
- Package manager: [pnpm / npm]
- Monorepo: [ya/tidak — jika ya, sebutkan tool: Turborepo / pnpm workspaces]

## Project Structure

### Next.js App Router:
```

app/
├── (auth)/ # Route groups untuk auth pages
├── (dashboard)/ # Route groups untuk protected pages
├── api/ # API routes
└── layout.tsx
src/
├── components/ # Reusable UI components
├── lib/ # Utilities, helpers, config
├── hooks/ # Custom React hooks
├── services/ # API call functions (client-side)
├── types/ # Shared TypeScript types
└── server/ # Server-only code (DB, auth)

```

## Conventions
- Formatting: Prettier — 2 spaces, single quotes
- Components: PascalCase, satu file per komponen
- Hooks: prefix `use`, camelCase
- Server actions: suffix `Action` (misal: `createUserAction`)
- API routes: RESTful, versioned di `/api/v1/`
- CSS: utility-first dengan Tailwind, komponen custom di `components/ui/`

## Architecture Notes
[Isi dengan keputusan arsitektur]
Contoh:
- "Data fetching di server components, interaktivitas di client components"
- "Semua form pakai react-hook-form + Zod validation"
- "Database hanya boleh diakses dari server/ directory"
- "Auth check di middleware.ts untuk semua route /dashboard/"

## Environment Variables
Frontend (.env.local):
- `NEXT_PUBLIC_API_URL` — URL backend API
Backend / shared:
- `DATABASE_URL` — Connection string
- `AUTH_SECRET` — Secret untuk JWT/session

## Scripts
- `pnpm dev` — Development server
- `pnpm build` — Production build
- `pnpm test` — Run tests
- `pnpm db:migrate` — Run database migrations
- `pnpm db:studio` — Buka Prisma Studio

## Current Focus
[Update setiap sesi — apa yang sedang dikerjakan]

## Recent Decisions
- [YYYY-MM-DD] [Keputusan]: [Alasan]

## Known Issues / Constraints
- [Hal yang perlu diingat]
```
