# Full web modules and setup

BiếtTuốt is a Laravel 11/PHP 8.2 web app with Vietnamese interface: registration/login, AI chat, AI content drafts, contributor applications, points/rewards, moderation, admin operations, analytics, and CSV reports.

## Run the site

Install PHP 8.2+, Composer 2, MySQL 8+, and PHP extensions including `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, and `fileinfo`. Then, in the project folder:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Create a MySQL database, set its credentials in `.env`, then set the real administrator and moderator email addresses in `ADMIN_EMAILS` and `MODERATOR_EMAILS`. Configure AI keys only if live AI calls are needed. Never commit `.env` or expose API keys in the browser.

```bash
php artisan migrate --seed
php artisan serve
```

Open <http://127.0.0.1:8000>, register with the admin email configured in `.env`, and use the navigation to access modules. Admin/moderator accounts are selected through the server-side email allowlists. This MVP does not send verification email; restrict public registration until verification and abuse controls are enabled.

## Modules

- `/chat`: private AI conversations. AI keys stay server-side; provider calls are logged.
- `/content`: AI-assisted drafts saved privately and routed for staff review.
- `/contributors`: application form; profiles begin pending, admins approve/reject under `/admin/contributors`.
- `/points`: append-only point history.
- `/rewards`: catalog and redemption. Redemption checks and deducts balance in a transaction.
- `/admin/rewards`: reward catalog management and processing claims. Cancelling a claim refunds points.
- `/moderation`: staff review queue, review history, and one-time 50-point award on approval.
- `/admin/analytics`: staff-only metrics with date filters and AI/moderation CSV exports.

## Verify

```bash
php artisan route:list
php artisan test
```

Tests use SQLite in memory and mocked AI responses; enable `pdo_sqlite`. They do not prove live MySQL/provider credentials or production hosting. Production must use `APP_ENV=production`, `APP_DEBUG=false`, HTTPS, a strong key, database backups, writable `storage`/`bootstrap/cache`, and document root set to `public/`.
