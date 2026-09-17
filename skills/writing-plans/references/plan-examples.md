# Plan Examples

## Contoh 1 — CRUD Endpoint (REST API)

**Task:** Tambah fitur create dan list untuk resource `Post`

```markdown
## Plan: Post CRUD — Create & List

**Files affected:** 3 created, 2 modified
**Tasks:** 5 tasks (~45 minutes total)

Tasks:

1. Tambah Post type & Zod schema
2. Tambah PostRepository (DB queries)
3. Tambah PostService (business logic)
4. Tambah POST /posts dan GET /posts routes
5. Tulis integration tests

---

### Task 1 — Tambah Post type & Zod schema

File: `src/types/post.ts`
Action: CREATE

What to do:
Buat TypeScript interface untuk Post dan Zod schema untuk validasi input.
Pisahkan antara tipe DB (Post) dan tipe input (CreatePostInput).

Key details:

- `Post` interface: id, title, content, authorId, createdAt, updatedAt
- `CreatePostInput` schema (Zod): title (min 3, max 100), content (min 1)
- Export keduanya sebagai named export

Verify:

- [ ] `tsc --noEmit` tidak ada error
- [ ] Schema Zod bisa dipanggil `.parse()` tanpa error untuk input valid

---

### Task 2 — Tambah PostRepository

File: `src/repositories/post-repository.ts`
Action: CREATE

What to do:
Buat class PostRepository dengan method untuk query database.
Gunakan Prisma client (sudah ada di `src/lib/prisma.ts`).

Key details:

- `create(data: CreatePostInput & { authorId: string }): Promise<Post>`
- `findAll(page?: number, limit?: number): Promise<Post[]>`
- Default pagination: page=1, limit=20
- Jangan ada business logic di sini — hanya query

Verify:

- [ ] Import Prisma tidak ada error TypeScript
- [ ] Method signatures sesuai dengan tipe di task 1

---

### Task 3 — Tambah PostService

File: `src/services/post-service.ts`
Action: CREATE

What to do:
Buat PostService yang wraps PostRepository. Di sinilah business logic seperti
permission check dan data transformation.

Key details:

- `createPost(input: CreatePostInput, userId: string): Promise<Post>`
- `listPosts(page?: number): Promise<{ posts: Post[], total: number }>`
- Throw `ForbiddenError` jika userId kosong
- Import PostRepository dan inject di constructor

Verify:

- [ ] `tsc --noEmit` tidak ada error
- [ ] Method sudah meng-handle kasus userId kosong

---

### Task 4 — Tambah Routes

File: `src/routes/posts/index.ts`
Action: CREATE

File: `src/routes/index.ts`
Action: MODIFY (register route `/posts`)

What to do:
Buat route file untuk POST /posts dan GET /posts.
Register di router utama.

Key details:

- `POST /posts`: auth middleware → validate body dengan Zod → panggil PostService
- `GET /posts`: auth middleware → query param `page` (optional, default 1)
- Response format: `{ data: Post | Post[], meta?: { page, total } }`
- Error handling: Zod error → 400, ForbiddenError → 403, lainnya → 500

Verify:

- [ ] `POST /posts` dengan body valid → 201 + post object
- [ ] `POST /posts` tanpa auth → 401
- [ ] `GET /posts` → 200 + array + meta

---

### Task 5 — Integration Tests

File: `src/routes/posts/posts.test.ts`
Action: CREATE

What to do:
Tulis integration tests menggunakan Vitest + Supertest.
Setup test DB menggunakan database URL dari `.env.test`.

Key details:

- beforeEach: reset tabel posts
- Test: POST berhasil, POST tanpa auth, POST body invalid, GET list
- Gunakan helper `createTestUser()` dari `src/test/helpers.ts`

Verify:

- [ ] `pnpm test` semua green
- [ ] Coverage POST dan GET minimal 80%
```

---

## Contoh 2 — React Component

**Task:** Buat komponen `UserCard` yang menampilkan info user

```markdown
## Plan: UserCard Component

**Files affected:** 2 created, 1 modified
**Tasks:** 3 tasks (~25 minutes total)

Tasks:

1. Buat UserCard component
2. Buat UserCard.test.tsx
3. Export dari components/index.ts

---

### Task 1 — Buat UserCard Component

File: `src/components/UserCard/UserCard.tsx`
Action: CREATE

What to do:
Buat functional component UserCard yang menerima props user dan menampilkan
avatar, nama, dan email. Gunakan Tailwind untuk styling.

Key details:

- Props: `{ user: Pick<User, 'id' | 'name' | 'email' | 'avatarUrl'>, onClick?: () => void }`
- Avatar: tampilkan `avatarUrl` jika ada, fallback ke inisial nama
- Accessible: gunakan `<article>` dengan aria-label
- Skeleton loading state jika prop `isLoading` true

Verify:

- [ ] Render tanpa error di Storybook atau dev server
- [ ] Props TypeScript valid — tidak ada `any`

---

### Task 2 — Unit Test

File: `src/components/UserCard/UserCard.test.tsx`
Action: CREATE

What to do:
Tulis unit tests menggunakan Vitest + React Testing Library.

Key details:

- Test: render dengan data lengkap, render dengan avatarUrl null (fallback inisial),
  render state loading, onClick dipanggil saat diklik
- Gunakan `screen.getByRole` bukan `getByTestId`

Verify:

- [ ] `pnpm test` semua green

---

### Task 3 — Export

File: `src/components/index.ts`
Action: MODIFY

What to do:
Tambahkan export UserCard ke barrel file.

Key details:

- `export { UserCard } from './UserCard/UserCard'`
- Letakkan sesuai urutan alfabetis

Verify:

- [ ] Import `{ UserCard } from '@/components'` bekerja tanpa error
```
