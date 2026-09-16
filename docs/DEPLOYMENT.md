# Deployment

## Production topology

The application runs as a FrankenPHP container on Vercel with Neon PostgreSQL as the external database. A Node builder stage compiles the Vite assets before the PHP image is started.

Production URL: https://emergency-beneficiary-management-nizar9.vercel.app

## Environment variables

Set these in Vercel Production only. Do not commit their values:

```text
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generated Laravel key>
APP_URL=https://emergency-beneficiary-management-nizar9.vercel.app
DB_CONNECTION=pgsql
DATABASE_URL=<Neon pooled or unpooled connection string>
```

Laravel resolves the PostgreSQL URL in this order: `DATABASE_URL`, `DATABASE_URL_UNPOOLED`, legacy `DB_URL`, then optional `LARAVEL_DATABASE_URL` compatibility.

## Safe database release sequence

Use the production workflow or an approved shell with the production environment loaded:

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan config:clear
```

Never run `migrate:fresh` against production. The CI workflow uses `migrate:fresh --seed` only against an isolated test PostgreSQL service.

## Verification

After deployment, verify the public landing page, login for all three demo roles, dashboard counts, CRUD journeys, role restrictions, CSV downloads, audit logs, and Vercel runtime logs. The seeded credentials are fictional demo credentials and must be replaced before real use.
