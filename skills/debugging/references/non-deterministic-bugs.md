# Non-Deterministic Bugs

Bug yang tidak selalu muncul — paling sulit di-debug karena tidak bisa
diandalkan untuk direproduksi. Strategi berbeda dari bug deterministik.

---

## Tipe Umum

### Race Condition

Dua operasi async berjalan bersamaan dan hasilnya bergantung pada siapa
yang selesai duluan.

**Tanda:** Bug muncul lebih sering saat load tinggi atau di CI (lebih lambat
dari dev machine). Jarang muncul saat debugging interaktif.

**Strategi isolasi:**

```typescript
// Tambah artificial delay untuk expose race condition
async function suspiciousFunction() {
  const data = await fetchData()
  await new Promise((r) => setTimeout(r, 100)) // [DEBUG] artificial delay
  return processData(data)
}
```

**Fix pattern umum:**

```typescript
// Jangan: dua operasi yang bisa race
await updateCounter(id)
await sendNotification(id) // bisa baca counter sebelum update selesai

// Tapi: jalankan serial jika order penting
const updated = await updateCounter(id)
await sendNotification(id, updated.newValue)

// Atau: gunakan database transaction untuk atomicity
await prisma.$transaction([
  prisma.counter.update({ where: { id }, data: { value: { increment: 1 } } }),
  prisma.notification.create({ data: { counterId: id } }),
])
```

---

### Stale Closure

Fungsi menangkap nilai lama dari variabel karena closure dibuat sebelum
variabel berubah.

**Tanda:** Nilai yang digunakan dalam callback/setTimeout/event listener
sudah lama, bukan nilai terkini.

**Contoh klasik di React:**

```typescript
// Bug: count selalu 0 di dalam interval
const [count, setCount] = useState(0)
useEffect(() => {
  const interval = setInterval(() => {
    console.log(count) // selalu 0 — stale closure
  }, 1000)
  return () => clearInterval(interval)
}, []) // dependency array kosong = closure dibuat sekali

// Fix: gunakan functional update atau tambah dependency
setInterval(() => {
  setCount((prev) => prev + 1) // gunakan prev, bukan count
}, 1000)
```

---

### Memory / State Leak antara Tests

Test sebelumnya meninggalkan state yang mempengaruhi test berikutnya.

**Tanda:** Test pass kalau dijalankan sendiri, fail kalau dijalankan bersama.

**Strategi:**

```bash
# Jalankan test dalam urutan terbalik
pnpm test --sequence.shuffle

# Jalankan hanya test yang fail
pnpm test --grep "nama test yang fail"
```

**Fix:**

```typescript
beforeEach(async () => {
  await cleanDatabase() // reset DB
  vi.clearAllMocks() // reset mocks
  vi.restoreAllMocks() // restore spies
})

afterAll(async () => {
  await prisma.$disconnect() // tutup koneksi DB
  server.close() // tutup HTTP server jika ada
})
```

---

### Timing / setTimeout Issues

**Tanda:** Test pass di mesin cepat, fail di CI. Atau pass 90% waktu.

**Fix untuk tests — gunakan fake timers:**

```typescript
beforeEach(() => {
  vi.useFakeTimers()
})

afterEach(() => {
  vi.useRealTimers()
})

it('should retry after delay', async () => {
  const promise = retryableOperation()
  await vi.advanceTimersByTimeAsync(5000)
  await expect(promise).resolves.toBe('success')
})
```

**Hindari di production code:**

```typescript
// Jangan: arbitrary timeout sebagai synchronization
await new Promise((r) => setTimeout(r, 500)) // "tunggu sampai selesai"

// Gunakan: explicit signal
await waitForCondition(() => resource.isReady(), { timeout: 5000 })
```
