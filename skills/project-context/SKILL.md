---
name: project-context
description: >
  Load and maintain project context at the start of every session or task.
  Use this skill immediately whenever: a new conversation begins, the user mentions
  a project by name, the user asks Claude to "continue", "pick up where we left off",
  or starts giving instructions that assume prior knowledge. Also trigger when the
  user seems to assume Claude already knows the codebase, stack, or conventions.
  Never skip this — missing context is the root cause of inconsistent outputs.
---

# Project Context

## Purpose

Establish shared understanding of the project before any planning, coding, or analysis.
This skill prevents the two most common failure modes for solo developers:

- Claude forgetting what was built last session
- Claude making inconsistent decisions because it doesn't know the project's conventions

---

## Step 1 — Look for Context File

Check for a context file in this priority order:

1. `.claude/context.md` (Claude Code standard location)
2. `CLAUDE.md` (project root)
3. `docs/context.md`
4. `.context.md`

**If found:** Read it fully. Confirm to the user:

> "Loaded context for **[project name]**. Stack: [stack]. Last task: [last task]. Ready."

**If not found:** Go to Step 2 — create one together.

---

## Step 2 — Create Context File (first time only)

Ask the user these questions **in one message**, not one by one:

```
Saya belum punya context file untuk project ini. Bantu saya isi beberapa hal:

1. Nama project dan deskripsi singkatnya apa?
2. Stack utama? (framework, database, runtime environment)
3. Di mana project ini berjalan? (Node.js versi berapa, browser, edge, dll)
4. Ada konvensi khusus yang harus saya ikuti?
   - Penamaan file/folder
   - Gaya kode (spaces/tabs, quote style, dll)
   - Pattern arsitektur yang sudah dipakai
5. Apa yang sedang dikerjakan sekarang?
```

Setelah user menjawab, buat file `.claude/context.md` dengan format di bawah.

---

## Context File Format

Simpan di `.claude/context.md`:

```markdown
# [Project Name]

## Overview

[1–2 kalimat deskripsi project]

## Stack

- Runtime: [Node.js vX / Browser / Edge]
- Framework: [Next.js / Express / Fastify / dll]
- Language: TypeScript [versi] / JavaScript
- Database: [PostgreSQL / MongoDB / SQLite / dll]
- Testing: [Vitest / Jest / dll]
- Package manager: [npm / pnpm / yarn]

## Conventions

- Formatting: [Prettier config, spaces/tabs, dll]
- Naming: [camelCase untuk variable, PascalCase untuk komponen, dll]
- File structure: [deskripsi singkat struktur folder]
- Imports: [absolute vs relative, path aliases, dll]
- Error handling: [pattern yang dipakai]

## Architecture Notes

[Keputusan arsitektur penting yang harus diingat.
Contoh: "Semua API calls lewat services/, bukan langsung dari components/"]

## Current Focus

[Apa yang sedang dikerjakan sekarang — update ini setiap sesi]

## Recent Decisions

[Keputusan teknis penting yang sudah dibuat — agar tidak diulang debatnya]

- [YYYY-MM-DD] [Keputusan]: [Alasan singkat]

## Known Issues / Constraints

[Hal-hal yang perlu diingat tapi belum diperbaiki]
```

---

## Step 3 — Confirm & Continue

Setelah context dimuat atau dibuat:

1. **Tampilkan ringkasan 3 baris** — project name, stack utama, current focus
2. **Tanya task sekarang** jika belum jelas
3. **Jangan mulai coding atau planning** sebelum context dikonfirmasi user

---

## Step 4 — Update Context After Each Session

Di akhir setiap sesi yang menghasilkan perubahan signifikan, **perbarui** `.claude/context.md`:

- Update bagian `## Current Focus`
- Tambahkan keputusan baru ke `## Recent Decisions`
- Catat constraint baru di `## Known Issues`

Beritahu user:

> "Saya update context file — [ringkasan perubahan]. Sesi berikutnya saya langsung tahu di mana kita tinggalkan."

---

## Rules

- **Selalu baca context file sebelum task apapun** — jangan andalkan memory dari conversation history
- **Jangan assume stack atau konvensi** — kalau tidak ada di context file, tanya
- **Update setelah perubahan besar** — context file yang stale lebih berbahaya dari tidak ada
- **Kalau context file ada tapi isinya incomplete**, isi yang kosong sambil jalan — jangan minta user isi ulang semua
- **Satu context file per project** — kalau monorepo, buat sub-context di masing-masing package jika perlu

---

## Reference Files

- `references/context-template-ts.md` — Template lengkap untuk TypeScript project
- `references/context-template-fullstack.md` — Template untuk fullstack Next.js / Express

Baca reference file yang relevan jika user butuh template lebih detail.
