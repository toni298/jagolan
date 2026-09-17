# Common JS/TS Runtime Errors

Quick lookup untuk error yang sering muncul. Setiap entry: penyebab paling
umum + cara verifikasi + fix.

---

## TypeError: Cannot read properties of undefined (reading 'X')

**Artinya:** Kamu mengakses properti `X` dari sesuatu yang `undefined`.

**Penyebab umum:**

- Data dari DB/API yang diasumsikan selalu ada, ternyata tidak
- Async function yang return-nya belum di-await
- Destructuring dari object yang mungkin null/undefined

**Verifikasi:**

```typescript
console.log('[DEBUG] value sebelum akses:', value)
// Kalau output: undefined → kamu tahu masalahnya
```

**Fix:**

```typescript
// Optional chaining untuk akses aman
const name = user?.profile?.name

// Null coalescing untuk default value
const title = post?.title ?? 'Untitled'

// Guard clause untuk early return
if (!user) throw new NotFoundError('User not found')
```

---

## TypeError: X is not a function

**Penyebab umum:**

- Import salah (default vs named export)
- Property yang diharapkan function ternyata undefined
- Method dipanggil sebelum object diinisialisasi

**Verifikasi:**

```typescript
console.log('[DEBUG] type of X:', typeof X)
console.log('[DEBUG] X value:', X)
```

**Fix umum:**

```typescript
// Cek import
import { sendEmail } from './email' // named
import sendEmail from './email' // default — pilih yang sesuai ekspor

// Cek apakah method ada sebelum dipanggil
if (typeof service.process === 'function') {
  service.process()
}
```

---

## ReferenceError: X is not defined

**Penyebab umum:**

- Variable belum dideklarasikan di scope ini
- Typo nama variable
- Environment variable tidak di-load

**Verifikasi:**

```bash
# Untuk env vars
node -e "console.log(process.env.MY_VAR)"
```

---

## UnhandledPromiseRejection

**Artinya:** Ada Promise yang rejected tapi tidak ada `.catch()` atau `try/catch`.

**Penyebab umum:**

- Async function dipanggil tanpa `await`
- Event handler async tidak di-wrap

**Fix:**

```typescript
// Selalu await atau catch
await riskyOperation() // bukan riskyOperation() tanpa await

// Untuk Express async handlers
router.get('/path', async (req, res, next) => {
  try {
    const result = await service.getData()
    res.json(result)
  } catch (err) {
    next(err) // pass ke error handler
  }
})
```

---

## SyntaxError: Cannot use import statement outside a module

**Penyebab:** Node.js menjalankan ESM file sebagai CommonJS.

**Fix:**

```json
// package.json — pilih salah satu:
{ "type": "module" }    // semua .js jadi ESM
// atau rename file ke .mjs

// tsconfig.json
{
  "compilerOptions": {
    "module": "NodeNext",
    "moduleResolution": "NodeNext"
  }
}
```

---

## Prisma: PrismaClientKnownRequestError P2002 (Unique constraint)

**Artinya:** Insert data yang sudah ada (duplicate pada unique field).

**Fix:**

```typescript
try {
  await prisma.user.create({ data: { email } })
} catch (e) {
  if (e instanceof Prisma.PrismaClientKnownRequestError && e.code === 'P2002') {
    throw new ConflictError('Email already exists')
  }
  throw e
}
```

---

## Prisma: PrismaClientKnownRequestError P2025 (Record not found)

**Artinya:** `update()` atau `delete()` pada record yang tidak ada.

**Fix:** Gunakan `updateMany()` dan cek count, atau find dulu sebelum update:

```typescript
const user = await prisma.user.findUnique({ where: { id } })
if (!user) throw new NotFoundError('User not found')
await prisma.user.update({ where: { id }, data })
```

---

## JWT: JsonWebTokenError: invalid signature

**Penyebab umum:** Secret yang dipakai untuk sign berbeda dengan yang dipakai verify.

**Verifikasi:**

```typescript
console.log('[DEBUG] sign secret:', process.env.JWT_SECRET?.slice(0, 10))
console.log('[DEBUG] verify secret:', process.env.JWT_SECRET?.slice(0, 10))
// Cek apakah .env vs .env.test berbeda
```

---

## JWT: TokenExpiredError

**Fix:** Cek `expiresIn` saat sign, atau refresh token jika expire adalah expected behavior.

---

## CORS: Access-Control-Allow-Origin missing

**Ini bukan bug di kode** — ini config issue.

```typescript
// Di Express, pastikan cors() dipasang SEBELUM routes
app.use(
  cors({
    origin: process.env.FRONTEND_URL,
    credentials: true,
  }),
)
app.use('/api', router) // bukan sebaliknya
```
