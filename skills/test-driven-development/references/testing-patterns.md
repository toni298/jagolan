# Testing Patterns — JavaScript / TypeScript

Pattern yang sering dibutuhkan saat TDD di JS/TS project.

---

## Mock Patterns (Vitest)

### Mock sebuah module

```typescript
// Mock seluruh module
vi.mock('../lib/email', () => ({
  sendEmail: vi.fn().mockResolvedValue({ success: true }),
}))

// Di test — akses mock-nya
import { sendEmail } from '../lib/email'
const mockSendEmail = vi.mocked(sendEmail)

it('should send welcome email on register', async () => {
  await registerUser({ email: 'x@y.com', password: '...' })
  expect(mockSendEmail).toHaveBeenCalledWith({
    to: 'x@y.com',
    subject: expect.stringContaining('Welcome'),
  })
})
```

### Mock class / repository

```typescript
vi.mock('../repositories/user-repository')

const MockUserRepo = vi.mocked(UserRepository)

beforeEach(() => {
  MockUserRepo.mockClear()
})

it('should call repo.findById with correct id', async () => {
  const mockFindById = vi.fn().mockResolvedValue({ id: '1', name: 'Alice' })
  MockUserRepo.mockImplementation(() => ({ findById: mockFindById }) as any)

  const service = new UserService(new UserRepository())
  await service.getUser('1')

  expect(mockFindById).toHaveBeenCalledWith('1')
})
```

### Mock partial — hanya beberapa method

```typescript
const mockRepo = {
  findById: vi.fn().mockResolvedValue({ id: '1', name: 'Alice' }),
  create: vi.fn(),
  update: vi.fn(),
} satisfies UserRepository // TypeScript checks semua method ada
```

---

## Test Data Patterns

### Builder pattern untuk test data

```typescript
// src/test/builders.ts

type UserOverrides = Partial<User>

export function buildUser(overrides: UserOverrides = {}): User {
  return {
    id: 'user-1',
    email: 'alice@example.com',
    name: 'Alice',
    createdAt: new Date('2024-01-01'),
    ...overrides,
  }
}

export function buildPost(overrides: Partial<Post> = {}): Post {
  return {
    id: 'post-1',
    title: 'Test Post',
    content: 'Test content',
    authorId: 'user-1',
    createdAt: new Date('2024-01-01'),
    ...overrides,
  }
}

// Penggunaan:
const user = buildUser({ email: 'bob@example.com' })
const post = buildPost({ authorId: user.id })
```

---

## Assertion Patterns

### Error assertions

```typescript
// Jangan test exact error message — test tipe/class-nya
await expect(service.getUser('invalid-id')).rejects.toThrow(NotFoundError)

// Atau kalau perlu check message juga:
await expect(service.getUser('invalid-id')).rejects.toThrow(
  expect.objectContaining({
    message: expect.stringContaining('not found'),
    statusCode: 404,
  }),
)
```

### Array assertions

```typescript
// Jangan expect urutan kalau tidak relevan
expect(result).toEqual(
  expect.arrayContaining([
    expect.objectContaining({ id: '1' }),
    expect.objectContaining({ id: '2' }),
  ]),
)

// Kalau urutan relevan
expect(result.map((p) => p.id)).toEqual(['2', '1']) // sorted desc
```

### Partial object match

```typescript
// Kalau tidak peduli semua field
expect(result).toMatchObject({
  title: 'Hello',
  authorId: 'user-1',
})
// id, createdAt, dll tidak perlu disebutkan
```

---

## Async Patterns

### Test yang butuh delay / timer

```typescript
import { vi } from 'vitest'

it('should retry after 1 second', async () => {
  vi.useFakeTimers()

  const mockFetch = vi
    .fn()
    .mockRejectedValueOnce(new Error('timeout'))
    .mockResolvedValueOnce({ data: 'ok' })

  const promise = fetchWithRetry(mockFetch)
  await vi.advanceTimersByTimeAsync(1000)
  const result = await promise

  expect(mockFetch).toHaveBeenCalledTimes(2)
  expect(result.data).toBe('ok')

  vi.useRealTimers()
})
```

### Test concurrent operations

```typescript
it('should handle concurrent requests without race condition', async () => {
  const results = await Promise.all([
    service.incrementCounter('user-1'),
    service.incrementCounter('user-1'),
    service.incrementCounter('user-1'),
  ])

  const finalCount = await service.getCount('user-1')
  expect(finalCount).toBe(3) // bukan 1 atau 2
})
```

---

## React Component Patterns (React Testing Library)

### Test behavior, bukan implementasi

```typescript
import { render, screen, fireEvent } from '@testing-library/react'

// Jangan test: "apakah state isOpen === true"
// Test: "apakah dropdown muncul saat button diklik"

it('should show dropdown when button is clicked', () => {
  render(<Dropdown label="Options" items={['A', 'B']} />)

  // Sebelum klik — dropdown tidak ada
  expect(screen.queryByRole('listbox')).not.toBeInTheDocument()

  // Klik button
  fireEvent.click(screen.getByRole('button', { name: 'Options' }))

  // Dropdown muncul
  expect(screen.getByRole('listbox')).toBeInTheDocument()
  expect(screen.getByText('A')).toBeInTheDocument()
})
```

### Query priority (RTL best practices)

Gunakan dalam urutan ini:

1. `getByRole` — paling accessible-friendly
2. `getByLabelText` — untuk form inputs
3. `getByPlaceholderText`
4. `getByText`
5. `getByDisplayValue`
6. `getByAltText` — untuk images
7. `getByTitle`
8. `getByTestId` — **last resort** saja

### Async rendering

```typescript
import { render, screen, waitFor } from '@testing-library/react'

it('should show user name after data loads', async () => {
  render(<UserProfile userId="1" />)

  // Loading state dulu
  expect(screen.getByText('Loading...')).toBeInTheDocument()

  // Tunggu data muncul
  await waitFor(() => {
    expect(screen.getByText('Alice')).toBeInTheDocument()
  })

  // Loading hilang
  expect(screen.queryByText('Loading...')).not.toBeInTheDocument()
})
```
