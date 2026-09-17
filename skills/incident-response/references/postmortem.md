# Blameless postmortem

Run after every real incident. The goal is prevention and learning, not blame.

## Structure

1. **Summary** — what happened, impact, and duration.
2. **Timeline** — detection → mitigation → resolution, with timestamps.
3. **Root cause** — the technical and contributing causes (use 5 Whys).
4. **What went well / what went poorly** — honest, system-focused.
5. **Action items** — concrete, owned, dated, and tracked to completion.

## Principles

- Blameless: focus on systems and conditions, not individuals.
- Distinguish trigger (what set it off) from root cause (why it was possible).
- Action items must prevent recurrence or speed detection/mitigation — and have
  an owner and a due date, or they won't happen.
- Share the postmortem; incidents are a learning asset for the whole team.

Use ../../shared/templates/incident-postmortem-template.md as the starting doc.
