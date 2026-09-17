---
name: ai-engineering
description: >
  Build AI features and agentic systems safely: prompt design, RAG, evaluation,
  model selection, tool use, guardrails, and AI security. Trigger this skill
  whenever the user builds with LLMs, embeddings, agents, RAG, or says
  "prompt", "AI feature", "chatbot", "agent", "vector", "embedding", "hallucination".
  Treat model output as untrusted, design evaluation before scaling, and never
  give an agent unbounded tools or trust without guardrails.
---

# AI Engineering

## Purpose

Ship AI features that are useful, evaluated, and safe — with controlled context,
measured quality, and guardrails around tools and untrusted output.

## When to use

- Designing prompts, RAG pipelines, or agentic/tool-using systems.
- Choosing a model or evaluating output quality.
- Adding LLM features that touch user data or take actions.

## Workflow

1. Define the task, success criteria, and an evaluation method up front.
2. Design the prompt/context; keep instructions and untrusted data separated.
3. For knowledge tasks, ground answers with RAG; cite/limit context.
4. Select a model by capability, cost, and latency — not hype.
5. For agents, scope tools narrowly and add guardrails and confirmations.
6. Treat all model and retrieved content as untrusted (prompt-injection risk).
7. Evaluate on a representative set before and after changes; track regressions.

## Anti-patterns

- Shipping without an evaluation set ("looks good in the demo").
- Concatenating untrusted text into instructions (prompt injection).
- Giving agents broad, unconfirmed tool access.
- Choosing the biggest model by default regardless of cost/latency.

## Output

- A grounded, evaluated AI feature with guardrails and a quality baseline.

## Reference selection

Read only what is relevant:

- references/prompt-design.md — structuring prompts and context.
- references/rag.md — retrieval-augmented generation.
- references/evaluation.md — measuring quality and regressions.
- references/model-selection.md — capability vs cost vs latency.
- references/agents-tools.md — tool use and agent guardrails.
- references/ai-security.md — prompt injection and AI-specific risks.

## Shared standards

- ../shared/engineering-principles.md
- ../shared/severity-levels.md
- ../shared/decision-framework.md
