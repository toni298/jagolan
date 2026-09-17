# Rate Limiting

## Goal

Protect the backend from abuse, brute force, accidental loops, and expensive usage.

## Apply Rate Limits To

- login
- register
- password reset
- OTP
- file upload
- search
- AI generation
- expensive reports
- public APIs
- webhook endpoints when appropriate

## Limit Dimensions

Use one or more:

- IP
- user ID
- tenant ID
- API key
- device ID
- route
- action type

## Response

Return:

```http
429 Too Many Requests
```

Include retry info when useful.

## Checklist

- [ ] Auth endpoints are rate-limited.
- [ ] Expensive endpoints are rate-limited.
- [ ] Limits are scoped correctly.
- [ ] Abuse logs are available.
- [ ] Legit users are not easily locked out unfairly.
