# AMS — Asset Management System

An open-source asset management platform built with **Laravel 11** and **Filament 3**, replacing a PHPMaker 2025 legacy system while sharing the same MariaDB database during the migration period.

## Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 11 |
| Admin UI | Filament v3.3 |
| Database | MariaDB 10.11 |
| Media | Spatie Media Library v11 |
| Permissions | Spatie Laravel Permission v8 |
| PHP | 8.2+ |

## Goals

- Full CMS capabilities — users can manage content without code changes
- Clean, maintainable codebase for future developers
- Gradual migration from PHPMaker alongside the live system
- Media management for the large supporting documents / photo library
- Role-based access control

## Quick Start

```bash
git clone https://github.com/your-org/ams.git
cd ams

composer install
cp .env.example .env
php artisan key:generate

# Configure your database in .env, then:
php artisan migrate

php artisan serve
```

Navigate to `http://localhost:8000/admin` and sign in with your existing AMS credentials.

## Development Setup

See [CONTRIBUTING.md](CONTRIBUTING.md) for the full development guide.

The PHPMaker production instance runs on port 80. This Laravel instance runs on port 8100 during transition.

## Architecture Notes

- Authentication uses the existing `accounts` table (plaintext password field — migration to hashed passwords is a planned Phase 2 task)
- All new Eloquent models map to the existing `tbl*` tables by name
- Filament Resources are added incrementally; the PHPMaker system remains authoritative until a resource is production-ready
- Media files currently reside in `_amsuploads/`; Spatie Media Library will take over in Phase 2

## License

MIT — see [LICENSE](LICENSE)
