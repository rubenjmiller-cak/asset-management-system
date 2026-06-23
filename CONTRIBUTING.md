# Contributing to AMS

## Development Environment

**Requirements**: PHP 8.2+, Composer, MariaDB 10.6+, Node 20+

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
# Edit .env with your database credentials

php artisan migrate
php artisan serve --port=8100
```

## Database Conventions

- All existing tables use the `tbl` prefix (e.g. `tblAssets`, `tblLocations`)
- Eloquent models set `protected $table` explicitly — never rely on Laravel's auto-naming
- Primary keys are not always `id` — check and set `protected $primaryKey`
- No `updated_at` on most legacy tables — set `public const UPDATED_AT = null`
- Passwords in `accounts` are plaintext — `App\Auth\PlaintextUserProvider` handles this; do NOT cast `password` as `hashed`

## Adding a Filament Resource

1. `php artisan make:filament-resource <Name>` (no `--generate` for legacy tables)
2. Create the corresponding Eloquent model in `app/Models/` with the correct `$table` and `$primaryKey`
3. Build the table columns using actual column names from the DB schema
4. Phase 1 resources are read-only: use `ViewAction`, not `EditAction`; remove `CreateAction` from header

## Coding Standards

- PSR-12 style enforced via Laravel Pint: `./vendor/bin/pint`
- No unnecessary comments — name things clearly instead
- Keep migrations additive; never alter existing `tbl*` tables without discussion

## Branching

- `main` — production-ready
- `develop` — integration branch
- Feature branches: `feature/<short-description>`

## Pull Requests

- Keep PRs focused; one resource or feature per PR
- Include a brief description of which tables/columns are affected
- Confirm the PHPMaker system still loads on port 80 after your changes (it reads the same DB)
