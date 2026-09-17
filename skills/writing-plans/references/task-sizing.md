# Task Sizing Guide

Panduan untuk memecah task yang terlalu besar atau menggabungkan yang terlalu kecil.

---

## Ukuran Ideal

| Ukuran        | Estimasi   | Tanda                                       |
| ------------- | ---------- | ------------------------------------------- |
| Terlalu kecil | < 5 menit  | Satu fungsi sederhana, satu baris perubahan |
| Ideal         | 5–15 menit | Satu unit logis yang bisa diverifikasi      |
| Terlalu besar | > 20 menit | Lebih dari satu concern, sulit diverifikasi |

---

## Tanda Task Terlalu Besar

Pecah jika task-mu mengandung kata-kata ini:

- "dan juga" → kemungkinan dua task
- "sekaligus" → kemungkinan dua task
- "termasuk" diikuti list panjang → pecah per item
- File yang dimodifikasi lebih dari 2 → kemungkinan terlalu luas

**Contoh task terlalu besar:**

> Buat user authentication dengan JWT, refresh token, password hashing, dan email verification

**Seharusnya dipecah menjadi:**

1. Buat `hashPassword()` dan `verifyPassword()` di `src/lib/crypto.ts`
2. Buat `generateToken()` dan `verifyToken()` di `src/lib/jwt.ts`
3. Buat `POST /auth/register` endpoint
4. Buat `POST /auth/login` endpoint
5. Buat refresh token logic di `src/lib/jwt.ts`
6. Buat email verification service (task terpisah jika butuh email provider)

---

## Tanda Task Terlalu Kecil

Gabungkan jika:

- Task hanya menambah satu baris (misal: satu import, satu export)
- Task tidak bisa diverifikasi sendiri
- Task selalu dikerjakan bersamaan dengan task lain

**Contoh task terlalu kecil:**

> Task 3: Tambah import PostService di routes/index.ts
> Task 4: Register route /posts di routes/index.ts

**Seharusnya digabung:**

> Task 3: Register PostService dan route /posts di routes/index.ts

---

## Pola Pemecahan yang Berguna

### Pola 1: Layer by Layer

Untuk fitur backend, pecah per layer arsitektur:

1. Types & validation (Zod schema)
2. Repository (DB layer)
3. Service (business logic)
4. Route/Controller
5. Tests

### Pola 2: Happy Path First

1. Implementasi happy path (tanpa error handling)
2. Tambah error handling & edge cases
3. Tambah tests

### Pola 3: Interface First

1. Definisikan interface/type dulu
2. Implementasikan yang paling dalam (utility/repo)
3. Bangun ke atas (service → route)

### Pola 4: Feature Flag

Untuk fitur besar yang perlu dirilis bertahap:

1. Buat implementasi di balik feature flag
2. Test dengan flag aktif
3. Task terpisah untuk enable flag di production

---

## Aturan Verifikasi

Setiap task harus punya verifikasi yang bisa dicek **tanpa menjalankan task lain**.

| Tipe task        | Verifikasi yang bagus           |
| ---------------- | ------------------------------- |
| Type/Interface   | `tsc --noEmit` clean            |
| Utility function | Unit test green                 |
| Repository       | Query berhasil di test DB       |
| Service          | Unit test dengan mock repo      |
| Route            | Integration test                |
| Component        | Render tanpa error + unit test  |
| Refactor         | Semua test existing masih green |
