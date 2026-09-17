# Vitest Setup — TypeScript + Prisma

Setup minimal yang dibutuhkan untuk TDD di JS/TS project dengan Vitest.

---

## Install

```bash
pnpm add -D vitest @vitest/coverage-v8
# Untuk integration tests dengan HTTP
pnpm add -D supertest @types/supertest
# Untuk React component tests
pnpm add -D @testing-library/react @testing-library/jest-dom jsdom
```

---

## vitest.config.ts

### Backend / Node.js

```typescript
import { defineConfig } from 'vitest/config'
import tsconfigPaths from 'vite-tsconfig-paths'

export default defineConfig({
  plugins: [tsconfigPaths()],
  test: {
    environment: 'node',
    globals: true,
    coverage: {
      provider: 'v8',
      reporter: ['text', 'lcov'],
      exclude: ['node_modules/', 'src/test/', '**/*.test.ts', 'src/index.ts'],
    },
  },
})
```

### Frontend / React (Next.js)

```typescript
import { defineConfig } from 'vitest/config'
import react from '@vitejs/plugin-react'
import tsconfigPaths from 'vite-tsconfig-paths'

export default defineConfig({
  plugins: [react(), tsconfigPaths()],
  test: {
    environment: 'jsdom',
    globals: true,
    setupFiles: ['./src/test/setup.ts'],
  },
})
```

`src/test/setup.ts` untuk React:

```typescript
import '@testing-library/jest-dom'
```

---

## Environment Variables untuk Test

Buat `.env.test`:

```env
DATABASE_URL="postgresql://user:password@localhost:5432/myapp_test"
JWT_SECRET="test-secret-not-for-production"
NODE_ENV="test"
```

Pastikan test DB berbeda dari dev DB — jangan pernah jalankan test di DB yang sama.

---

## Package.json Scripts

```json
{
  "scripts": {
    "test": "vitest run",
    "test:watch": "vitest",
    "test:coverage": "vitest run --coverage",
    "test:ui": "vitest --ui"
  }
}
```

- `pnpm test` → run semua test sekali (untuk CI / setelah task selesai)
- `pnpm test:watch` → watch mode saat TDD aktif
- `pnpm test src/services/post-service.test.ts` → run file spesifik

---

## Prisma Test Setup

Untuk integration test yang butuh database:

```typescript
// src/test/helpers.ts
import { prisma } from '../lib/prisma'
import { hash } from 'bcryptjs'
import jwt from 'jsonwebtoken'

export async function cleanDatabase() {
  // Hapus dalam urutan yang menghormati foreign key constraints
  await prisma.post.deleteMany()
  await prisma.user.deleteMany()
}

export async function createTestUser(overrides = {}) {
  return prisma.user.create({
    data: {
      email: 'test@example.com',
      name: 'Test User',
      passwordHash: await hash('password123', 10),
      ...overrides,
    },
  })
}

export function createTestToken(userId: string): string {
  return jwt.sign({ sub: userId }, process.env.JWT_SECRET!, { expiresIn: '1h' })
}
```

Di test file:

```typescript
import { cleanDatabase, createTestUser, createTestToken } from '../test/helpers'

beforeEach(async () => {
  await cleanDatabase()
})

afterAll(async () => {
  await prisma.$disconnect()
})
```

---

## Vitest Globals

Dengan `globals: true` di config, tidak perlu import describe/it/expect:

```typescript
// Tanpa globals:
import { describe, it, expect, vi, beforeEach } from 'vitest'

// Dengan globals: true — langsung pakai:
describe('...', () => {
  it('...', () => {
    expect(result).toBe(expected)
  })
})
```

Pilih salah satu dan konsisten di seluruh project. Rekomendasi: **explicit import**
(tanpa globals) — lebih jelas dan tidak membingungkan editor.

---

## Troubleshooting Umum

### "Cannot find module" dengan path alias

Pastikan `vite-tsconfig-paths` sudah diinstall dan dipakai di config:

```bash
pnpm add -D vite-tsconfig-paths
```

### Test jalan tapi TypeScript error

```bash
npx tsc --noEmit  # cek error TS terpisah dari test runner
```

### Database connection error saat test

1. Cek `.env.test` ada dan DATABASE_URL benar
2. Cek test DB sudah dibuat: `createdb myapp_test`
3. Cek migration sudah jalan: `DATABASE_URL=... npx prisma migrate deploy`
