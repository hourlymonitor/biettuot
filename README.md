# BiếtTuốt MVP

Laravel 11 + MySQL application foundation for authentication, AI chat, AI-assisted content drafts, contributors, points/rewards, moderation, and staff analytics.

## Local setup

Requirements: PHP 8.2+, Composer 2, MySQL 8+, and Laravel PHP extensions.

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure the MySQL credentials and AI provider variables in `.env`, then run:

```bash
php artisan migrate
php artisan serve
```

## Staff access

Set comma-separated `ADMIN_EMAILS` and `MODERATOR_EMAILS` in the server environment. Admins access `/admin`; admins and moderators can access `/admin/analytics`, moderation pages, and the CSV report downloads. Keep `.env` and all AI keys out of version control.

## Analytics and reports

The analytics dashboard supports `from` and `to` date filters and reports user registrations, conversations, new content, pending drafts, AI call/failure counts, average latency, token usage, provider/model performance, review outcomes, and moderator activity. CSV downloads are available for AI usage and moderation reviews. Text fields are escaped against spreadsheet formula injection.

## Content and moderation

AI-created content is stored as a draft and requires staff review. Approved content awards the author 50 points; rejected content remains unavailable for public publishing. Review history is retained for reporting.

## Tests

```bash
php artisan test
```

Feature tests require SQLite support (`pdo_sqlite`) when using the in-memory test database.

## Production deployment

Use a host with PHP 8.2+, Composer/SSH, and MySQL. Point the web root to `public/`; set `APP_ENV=production`, `APP_DEBUG=false`, a strong `APP_KEY`, HTTPS, staff allowlists, and provider credentials in host-side environment settings. Ensure `storage/` and `bootstrap/cache/` are writable and back up the DB before `php artisan migrate --force`.
