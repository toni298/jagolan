# Common Blockers — JavaScript / TypeScript

Blocker yang sering muncul saat executing plans di JS/TS project, dan cara
handle-nya tanpa keluar dari scope task.

---

## TypeScript Errors

### "Type X is not assignable to type Y"

**Kenapa muncul:** Tipe yang dibuat di task sebelumnya tidak cocok dengan
yang dipakai di task sekarang.

**Handle:**

1. Cek apakah tipe di task sebelumnya sudah benar
2. Jika tipe perlu diubah, itu perubahan di task sebelumnya — laporkan sebagai blocker
3. Jangan gunakan `as any` atau `// @ts-ignore` untuk melewatinya

**Template laporan blocker:**

```
⚠️ TypeScript mismatch — Task [N]

PostRepository.create() mengembalikan `Post | null` tapi PostService
mengharapkan `Post`. Tipe di task 2 perlu diupdate.

Opsi:
A) Update return type PostRepository.create() ke Promise<Post> dan throw jika null
B) Handle null di PostService

Rekomendasi: A — lebih clean di layer service
```

---

### "Cannot find module" / Import error

**Kenapa muncul:** File yang diimport belum dibuat (task sebelumnya belum
selesai), atau path salah.

**Handle:**

- Cek apakah file yang diimport sudah ada
- Cek path alias di `tsconfig.json` (misalnya `@/` → `src/`)
- Jika file belum ada → blocker, task dikerjakan tidak berurutan

---

### Circular imports

**Kenapa muncul:** A import B, B import A.

**Handle:**

1. Identifikasi siapa yang harus jadi "leaf" (tidak import siapa-siapa)
2. Biasanya solved dengan memindahkan tipe ke file terpisah (`types/`)
3. Laporkan sebagai blocker jika butuh restructure

---

## Database / Prisma

### Schema tidak sync

**Tanda:** `PrismaClientKnownRequestError` atau kolom tidak ditemukan.

**Handle:**

```bash
npx prisma generate    # regenerate client
npx prisma db push     # sync schema ke dev DB (tanpa migration)
# atau
npx prisma migrate dev --name nama-migration
```

Jika butuh migration baru → ini task tersendiri, laporkan sebagai blocker
jika tidak ada di plan.

---

### "Unique constraint failed"

**Tanda:** Error saat insert data yang sudah ada.

**Handle di tests:** Pastikan `beforeEach` reset data test dengan benar:

```typescript
beforeEach(async () => {
  await prisma.post.deleteMany()
  await prisma.user.deleteMany()
})
```

---

## Testing

### Test timeout

**Kenapa muncul:** Test menunggu sesuatu yang tidak pernah resolve —
biasanya koneksi DB atau async operation yang tidak di-await.

**Handle:**

1. Pastikan semua async/await konsisten
2. Pastikan `beforeAll` setup DB connection sudah di-await
3. Tambah `afterAll(() => prisma.$disconnect())` jika belum ada

---

### "Cannot use import statement" di test

**Kenapa muncul:** Vitest/Jest config tidak handle ESM dengan benar.

**Handle (Vitest):**

```typescript
// vitest.config.ts
export default defineConfig({
  test: {
    environment: 'node',
  },
})
```

**Handle (Jest):** Tambah transform config di `jest.config.js` atau
gunakan `ts-jest`.

---

### Mock tidak bekerja

**Handle:**

```typescript
// Vitest
vi.mock('../repositories/post-repository')
const mockCreate = vi.fn()
vi.mocked(PostRepository).mockImplementation(() => ({
  create: mockCreate,
  findAll: vi.fn(),
}))
```

Pastikan `vi.mock()` dipanggil sebelum import modul yang di-mock.

---

## Runtime / Express / Hono

### Middleware tidak berjalan

**Kemungkinan penyebab:**

1. Route didaftarkan sebelum middleware
2. `next()` tidak dipanggil di middleware sebelumnya
3. Middleware async tidak di-handle dengan benar

**Handle async middleware di Express:**

```typescript
// Bungkus middleware async
const asyncHandler =
  (fn: RequestHandler): RequestHandler =>
  (req, res, next) =>
    Promise.resolve(fn(req, res, next)).catch(next)

router.post('/posts', asyncHandler(authMiddleware), asyncHandler(createPost))
```

---

### CORS error di development

Ini biasanya bukan blocker task — ini config issue. Laporkan ke user,
jangan perbaiki di tengah task lain.

```typescript
// Quick fix untuk dev — tapi catat sebagai task terpisah untuk production config
app.use(cors({ origin: 'http://localhost:3000' }))
```
