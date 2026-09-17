---
name: test-driven-development
description: >
  Write tests before writing implementation code, following RED-GREEN-REFACTOR.
  Trigger this skill during any task in executing-plans that involves writing
  new functions, services, endpoints, or components. Also trigger when the user
  says "write tests", "add tests", "test this", or "TDD". This skill applies
  to every implementation task — not just when explicitly asked. If you are
  about to write implementation code without a failing test first, stop and
  use this skill instead.
---

# Test-Driven Development

## Purpose

Write tests before implementation to produce code that is correct by construction,
not by accident. For a solo developer, TDD also acts as a second reviewer — the
test defines what the code must do before the code is written.

TDD in this system works at the **task level** inside `executing-plans`:
before writing implementation for any task, write a failing test first.

---

## The Cycle — RED → GREEN → REFACTOR

```
RED    → Write a failing test that describes the desired behavior
GREEN  → Write the minimum code to make it pass (nothing more)
REFACTOR → Clean up code while keeping tests green
```

Never skip a step. Never write implementation before RED.

---

## Step 1 — RED: Write the Failing Test

Before any implementation code, write a test that:

- Describes **one specific behavior**
- Is specific enough to fail for the right reason
- Uses realistic inputs and expected outputs

**Template (Vitest/Jest):**

```typescript
import { describe, it, expect } from 'vitest'

describe('[unit under test]', () => {
  it('[should do specific thing given specific condition]', async () => {
    // Arrange
    const input = ...

    // Act
    const result = await functionUnderTest(input)

    // Assert
    expect(result).toEqual(...)
  })
})
```

**Run the test — it must fail:**

```bash
pnpm test [test-file] --run
```

If the test passes without implementation → the test is wrong.
Fix the test before continuing.

---

## Step 2 — GREEN: Write Minimum Implementation

Write the **simplest code** that makes the failing test pass.

Rules:

- No extra features, no future-proofing
- No code that isn't required by the failing test
- If tempted to add "just in case" logic → don't. Write another test first.

Run tests again:

```bash
pnpm test [test-file] --run
```

If tests pass → proceed to REFACTOR.
If tests still fail → fix only what the error message says.

---

## Step 3 — REFACTOR: Clean Up

With tests green, improve the code:

- Remove duplication
- Improve naming
- Simplify logic
- Apply conventions from `project-context`

Rules:

- **Run tests after every change** — even small ones
- Never refactor and add features at the same time
- If tests break during refactor → undo the last change, don't push through

---

## Repeat per Behavior

Each cycle covers **one behavior**. For a function with multiple behaviors,
run multiple RED→GREEN→REFACTOR cycles:

```
Behavior 1: returns user when found
  → RED → GREEN → REFACTOR

Behavior 2: throws NotFoundError when user doesn't exist
  → RED → GREEN → REFACTOR

Behavior 3: throws ValidationError for invalid id format
  → RED → GREEN → REFACTOR
```

---

## What to Test

### Unit tests (pure functions, services)

Test in isolation. Mock all dependencies.

```typescript
// Service test — mock the repository
import { vi } from 'vitest'
import { PostService } from './post-service'
import { PostRepository } from '../repositories/post-repository'

vi.mock('../repositories/post-repository')

describe('PostService.createPost()', () => {
  it('should return created post on valid input', async () => {
    const mockRepo = vi.mocked(new PostRepository())
    mockRepo.create.mockResolvedValue({
      id: '1',
      title: 'Hello',
      content: 'World',
      authorId: 'u1',
    })

    const service = new PostService(mockRepo)
    const result = await service.createPost(
      { title: 'Hello', content: 'World' },
      'u1',
    )

    expect(result.title).toBe('Hello')
    expect(mockRepo.create).toHaveBeenCalledOnce()
  })

  it('should throw ForbiddenError when userId is empty', async () => {
    const service = new PostService(new PostRepository())
    await expect(
      service.createPost({ title: 'x', content: 'y' }, ''),
    ).rejects.toThrow('Forbidden')
  })
})
```

### Integration tests (routes/endpoints)

Test the full stack with a real DB. Use test database from `.env.test`.

```typescript
import { describe, it, expect, beforeEach, afterAll } from 'vitest'
import supertest from 'supertest'
import { app } from '../app'
import { prisma } from '../lib/prisma'

const request = supertest(app)

describe('POST /posts', () => {
  beforeEach(async () => {
    await prisma.post.deleteMany()
  })

  afterAll(async () => {
    await prisma.$disconnect()
  })

  it('should create a post and return 201', async () => {
    const token = await getTestToken() // helper dari src/test/helpers.ts

    const res = await request
      .post('/posts')
      .set('Authorization', `Bearer ${token}`)
      .send({ title: 'Hello', content: 'World' })

    expect(res.status).toBe(201)
    expect(res.body.data.title).toBe('Hello')
  })

  it('should return 401 without auth token', async () => {
    const res = await request.post('/posts').send({ title: 'x', content: 'y' })
    expect(res.status).toBe(401)
  })
})
```

---

## What NOT to Test

Avoid testing these — they produce noise, not signal:

- Implementation details (which internal function was called, in what order)
- Third-party library internals (Prisma, Express internals)
- Trivial getters/setters with no logic
- Types and interfaces (TypeScript handles this)
- Exact error messages (they change; test error type instead)

---

## Test File Location

Follow this convention (mirrors `project-context` structure):

```
src/
├── services/
│   ├── post-service.ts
│   └── post-service.test.ts     ← unit test, co-located
├── routes/
│   ├── posts/
│   │   ├── index.ts
│   │   └── posts.test.ts        ← integration test, co-located
└── test/
    └── helpers.ts               ← shared test utilities
```

Co-locate tests with source — don't put all tests in a separate `__tests__/` folder.

---

## Rules

- **Never write implementation before a failing test** — RED must come first
- **Never skip GREEN** — confirm the test actually passes before refactoring
- **One behavior per test** — if a test has multiple `expect` chains testing
  different things, split it
- **Tests must be independent** — no test should depend on state from another
- **No `// @ts-ignore` in tests** — if TypeScript complains, fix the types
- **Delete implementation written before tests** — if you wrote code without
  RED first, delete it and restart the cycle properly

---

## When TDD is Hard

Some things are genuinely difficult to TDD. Handle them like this:

| Situation             | Approach                                                                     |
| --------------------- | ---------------------------------------------------------------------------- |
| UI components         | Test behavior (click, render, state) not visuals — use React Testing Library |
| Database migrations   | Test the migration result, not the migration itself                          |
| External APIs         | Mock at the service boundary, not inside the function                        |
| One-off scripts       | Skip TDD — these aren't production code                                      |
| Initial project setup | Skip TDD — get something running first, then apply TDD going forward         |

---

## Reference Files

- `references/vitest-setup.md` — Vitest config untuk TypeScript + Prisma project
- `references/testing-patterns.md` — Pattern untuk mock, helper, dan test data

Baca jika butuh setup atau stuck dengan pattern testing spesifik.
