# API Performance

Check:

- slow dependencies
- too many round trips
- large payloads
- blocking work in request path
- missing caching
- no timeouts
- no pagination

Move slow retryable work to queues.
