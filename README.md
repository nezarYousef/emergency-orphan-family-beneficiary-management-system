# Emergency Orphan & Family Beneficiary Management System

Humanitarian Case Management & Aid Tracking Platform.

## Overview

A Laravel/PostgreSQL portfolio application for secure internal management of vulnerable families, beneficiaries, orphan records, and aid distributions.

## Features

- Session authentication with admin, data-entry, and viewer roles
- PostgreSQL relational schema with foreign keys, indexes, and soft deletes
- Family case search, filters, pagination, CRUD, relationships, and audit trail
- Beneficiary, orphan, aid, reports, CSV export, and administrative modules are structured for extension
- Bootstrap Blade interface and GitHub Actions PostgreSQL CI

## Tech Stack

PHP 8.4, Laravel 13, PostgreSQL 16, Eloquent, Blade, Bootstrap 5, PHPUnit, GitHub Actions, FrankenPHP.

## Installation

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan test
```

Set `DB_CONNECTION=pgsql` and the PostgreSQL connection variables in `.env`.

## Demo Accounts

These are fictional demo credentials only:

| Role | Email | Password |
|---|---|---|
| Admin | admin@example.com | password |
| Data Entry | dataentry@example.com | password |
| Viewer | viewer@example.com | password |

## Database Architecture

See [docs/ERD.md](docs/ERD.md). Tables include users, families, beneficiaries, orphans, aid_distributions, and audit_logs.

## CI and Deployment

GitHub Actions runs Composer, PostgreSQL migrations/seeding, route validation, and tests on every push. `Dockerfile.vercel` provides a production FrankenPHP image; production requires secure Vercel environment variables and managed PostgreSQL.

## Security

All demonstration records are fictional. Before real humanitarian use, add HTTPS, managed secrets, encrypted backups, stronger password policy, restricted database access, monitoring, retention policy, and formal privacy controls. Never commit `.env` or credentials.

## Disclaimer

This is a portfolio/demo implementation and is not a substitute for an operational humanitarian information-security review.
