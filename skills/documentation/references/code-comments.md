# Code comments

Comments explain what code cannot say by itself: intent, constraints, and the
reasons behind non-obvious choices.

Comment when:

- The **why** is non-obvious (business rule, workaround, performance tradeoff).
- There is an **invariant** the reader must not break.
- A choice looks wrong but is intentional (link to the issue/ADR).
- Behavior is subtle: ordering, concurrency, units, edge cases.

Do not comment when:

- The code already says it (`i++ // increment i`).
- A better name or smaller function would remove the need.

Guidelines:

- Prefer self-explanatory names over comments.
- Keep comments next to the code they describe and update them together.
- Use TODO/FIXME with an owner or issue link, not as a graveyard.
- For public APIs, use doc comments (JSDoc/TSDoc) so tools can surface them.
