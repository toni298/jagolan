# Git Workflow — Solo Developer

Panduan commit conventions dan branching yang praktis untuk solo dev.
Tidak perlu kompleks — cukup konsisten.

---

## Commit Convention (Conventional Commits)

Format: `type(scope): description`

### Types

| Type       | Kapan dipakai                      |
| ---------- | ---------------------------------- |
| `feat`     | Fitur baru                         |
| `fix`      | Bug fix                            |
| `refactor` | Refactor tanpa perubahan behavior  |
| `test`     | Tambah atau perbaiki test          |
| `chore`    | Update dependency, config, tooling |
| `docs`     | Update dokumentasi                 |
| `style`    | Formatting, whitespace (bukan CSS) |
| `perf`     | Peningkatan performance            |

### Contoh commit yang bagus

```bash
feat(posts): add PATCH /posts/:id endpoint
fix(auth): handle expired refresh token correctly
refactor(user-service): extract email validation to lib/validators
test(posts): add integration tests for create and list
chore: upgrade prisma to 5.10.0
```

### Contoh commit yang buruk

```bash
fix stuff           # terlalu vague
WIP                 # jangan commit WIP ke main
update              # tidak ada info apa yang diupdate
feat: add everything # terlalu banyak dalam satu commit
```

---

## Branching untuk Solo Dev

Kamu solo, jadi tidak perlu workflow tim yang kompleks. Tapi tetap berguna
untuk pisahkan fitur besar dari `main`.

### Kapan buat branch baru

Buat branch jika:

- Fitur butuh lebih dari 1 hari kerja
- Fitur berisiko tinggi (menyentuh auth, payment, data migration)
- Eksperimen yang mungkin di-discard

Langsung ke `main` jika:

- Bug fix kecil
- Perubahan < 2 jam
- Tidak ada risk regresi

### Branch naming

```bash
feat/post-crud
fix/expired-token-handling
refactor/user-service-cleanup
chore/upgrade-dependencies
```

### Workflow per fitur

```bash
# Mulai fitur
git checkout -b feat/nama-fitur

# Commit per task (dari executing-plans)
git add src/types/post.ts
git commit -m "feat(posts): add Post type and Zod schema"

git add src/repositories/post-repository.ts
git commit -m "feat(posts): add PostRepository with create and findAll"

# Setelah semua task selesai dan test green
git checkout main
git merge feat/nama-fitur --no-ff
git branch -d feat/nama-fitur
```

### `--no-ff` (no fast-forward)

Selalu gunakan `--no-ff` saat merge branch fitur ke main.
Ini membuat history lebih mudah dibaca — kamu bisa lihat kapan fitur dimulai
dan selesai sebagai satu unit.

---

## Commit per Task

Idealnya: **satu commit per task** dari `writing-plans`.
Ini membuat:

- Mudah untuk `git revert` satu task spesifik
- History yang jelas mencerminkan plan
- Debugging lebih mudah dengan `git bisect`

```bash
# Setelah task 1 selesai dan verified
git add [file yang diubah di task 1 saja]
git commit -m "feat(posts): add Post type and CreatePostInput schema"

# Setelah task 2
git add [file task 2]
git commit -m "feat(posts): add PostRepository"
```

---

## Sebelum Commit — Quick Checklist

```bash
# 1. TypeScript clean?
npx tsc --noEmit

# 2. Tests green?
pnpm test

# 3. Linting clean?
pnpm lint

# 4. Review apa yang akan di-commit
git diff --staged

# 5. Commit
git commit -m "type(scope): description"
```
