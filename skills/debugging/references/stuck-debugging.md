# Stuck Debugging

Kalau sudah 2–3 siklus hypothesis dan belum ketemu root cause, ikuti
langkah-langkah ini secara berurutan.

---

## Langkah 1 — Kembali ke Basics

Cek hal-hal yang sering diabaikan karena "pasti bukan itu":

```bash
# Stale build?
rm -rf dist/ .next/ node_modules/.cache/
pnpm build

# Wrong branch?
git branch --show-current
git status

# Env vars benar?
node -e "require('dotenv').config(); console.log(process.env.DATABASE_URL)"

# Dependencies sync?
pnpm install
npx prisma generate
```

---

## Langkah 2 — Bisect dengan Git

Kalau bug baru muncul dan kamu tahu kapan terakhir kali kode berjalan benar:

```bash
git bisect start
git bisect bad                  # commit sekarang = bug ada
git bisect good v1.2.0          # atau hash commit yang diketahui bersih

# Git akan checkout commit di tengah
# Test manual atau jalankan test suite
# Lalu tandai:
git bisect good  # atau
git bisect bad

# Ulangi sampai git menemukan commit pertama yang introduce bug
git bisect reset  # setelah selesai
```

---

## Langkah 3 — Isolasi Minimal

Buat reproduksi terkecil yang masih menunjukkan bug:

```typescript
// Mulai dari versi paling sederhana yang mungkin
// Hapus semua yang tidak relevan
// Kalau bug hilang saat disederhanakan → kamu tahu apa yang menyebabkannya

// Contoh: dari route handler kompleks
// Sederhanakan jadi fungsi pure yang bisa ditest langsung
async function minimalRepro() {
  const input = { id: 'problematic-value' }
  const result = await suspiciousFunction(input)
  console.log(result)
}
minimalRepro()
```

---

## Langkah 4 — Trace Execution Path

Log setiap langkah di call chain, dari entry point sampai error:

```typescript
// Tambah breadcrumb logging
console.log('[1] input:', JSON.stringify(input))
const validated = validate(input)
console.log('[2] validated:', JSON.stringify(validated))
const result = await repo.findById(validated.id)
console.log('[3] db result:', JSON.stringify(result))
const transformed = transform(result)
console.log('[4] transformed:', JSON.stringify(transformed))
```

Cari di mana nilai pertama kali berbeda dari yang diharapkan.
Masalah ada di langkah sebelum divergensi, bukan sesudahnya.

---

## Langkah 5 — Cek External Dependencies

Kalau bug ada di boundary dengan library atau service eksternal:

```typescript
// Cek versi library
console.log(require('prisma/package.json').version)

// Cek apakah library berperilaku sesuai dokumentasi
// dengan test case super minimal yang tidak melibatkan code kamu sama sekali
import { z } from 'zod'
const schema = z.string().email()
console.log(schema.safeParse('not-an-email')) // should fail
console.log(schema.safeParse('valid@email.com')) // should pass
```

Kalau library tidak berperilaku sesuai docs → cek changelog, issue tracker,
atau coba downgrade versi.

---

## Langkah 6 — Rubber Duck

Jelaskan bug secara verbal (atau tulis), mulai dari:

1. Apa yang seharusnya terjadi
2. Apa yang sebenarnya terjadi
3. Apa yang sudah dicoba
4. Apa yang diketahui pasti bukan penyebabnya

Proses mengartikulasikan masalah sering langsung memunculkan insight
yang tidak terlihat saat debugging diam-diam.
