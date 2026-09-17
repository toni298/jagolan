# Logs, metrics, and traces

The three pillars answer different questions; use them together.

## Logs (discrete events)

- Emit **structured** logs (JSON) with: timestamp, level, message, and context
  (request/correlation ID, user/tenant, operation).
- Use levels deliberately: error (action needed), warn (suspicious), info
  (key business events), debug (diagnostic, off in prod by default).
- Never log secrets, credentials, or PII.
- A correlation ID threaded through a request makes logs joinable.

## Metrics (aggregatable numbers)

Track the four golden signals:

- **Latency** — p50/p95/p99, success vs error separately.
- **Traffic** — requests/sec, throughput.
- **Errors** — rate and ratio.
- **Saturation** — how full the system is (CPU, memory, queue depth, pool).

## Traces (request across services)

- Propagate a trace/span context across service and async boundaries.
- Use traces to find where latency/errors originate in a distributed flow.

Instrument for the questions you'll actually ask during an incident, not
everything imaginable.
