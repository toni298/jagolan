# XSS and CSRF

## XSS

Avoid rendering unsanitized user HTML.
Use output escaping and strict sanitization.

## CSRF

For cookie auth, protect state-changing requests with SameSite, CSRF token, or origin checks.

## Checklist

- [ ] User HTML is sanitized.
- [ ] Cookies are HttpOnly/Secure/SameSite.
- [ ] State-changing cookie-auth endpoints handle CSRF.
