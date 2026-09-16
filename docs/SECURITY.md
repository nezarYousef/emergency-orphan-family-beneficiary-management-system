# Security notes

Implemented controls include:

- Laravel CSRF protection, hashed passwords, secure session regeneration, and inactive-account rejection.
- Server-side role middleware for Admin, Data Entry, and Viewer actions; UI hiding is only a usability layer.
- Form Request validation, database uniqueness constraints, PostgreSQL checks, foreign keys, and soft deletes for human-impacting records.
- Transactional writes for records that also create audit events.
- Streaming CSV exports with UTF-8 BOM and formula-injection neutralization for values beginning with `=`, `+`, `-`, or `@`.
- Security response headers, login rate limiting, escaped Blade output, and production `APP_DEBUG=false`.
- No production URL, APP_KEY, database URL, or other secret is stored in Git.

Before real humanitarian use, add a formal privacy impact assessment, least-privilege database credentials, key rotation, encrypted backups, retention/deletion policy, centralized monitoring, stronger password/MFA controls, and an incident-response process. All repository seed records are fictional.
