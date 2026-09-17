# Authentication

## Goal

Verify identity securely.

Authentication proves who the actor is. It does not prove what they are allowed to do.

## Rules

- Hash passwords with Argon2id, bcrypt, or scrypt.
- Never store plaintext passwords.
- Never log tokens or passwords.
- Rate-limit login and password reset.
- Expire and revoke sessions.
- Rotate refresh tokens if used.
- Invalidate sessions after password change.
- Use MFA for high-risk accounts when appropriate.

## Sessions

For browser apps, secure HttpOnly cookies are often safer than localStorage tokens.

Cookie settings:

- HttpOnly
- Secure
- SameSite=Lax or Strict
- reasonable expiration

## JWT

JWT is useful but dangerous when misused.

Avoid:

- long-lived JWT without revocation plan
- storing secrets in payload
- accepting unsafe algorithms
- storing JWT in localStorage when XSS risk is high

## API Keys

For API keys:

- generate high entropy
- store hashed key
- show full key only once
- scope permissions
- rate-limit per key
- allow revocation and rotation

## Checklist

- [ ] Passwords are hashed securely.
- [ ] Login is rate-limited.
- [ ] Sessions can expire and be revoked.
- [ ] Refresh token rotation is considered.
- [ ] Tokens are not logged.
- [ ] API keys are hashed and scoped.
