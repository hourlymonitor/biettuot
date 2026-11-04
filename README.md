# Full web modules and setup

BiếtTuốt is a Vietnamese Laravel 11/PHP 8.2 web application. Modules include registration/login, AI chat, AI content drafts, contributor applications, points ledger, rewards, content moderation, admin operations, analytics, and CSV reports.

## Run locally

Install PHP 8.2+, Composer 2, MySQL 8+, and PHP extensions including `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, and `fileinfo`. In the project folder run:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Create a MySQL database and set its credentials in `.env`. Set `ADMIN_EMAILS` and `MODERATOR_EMAILS` to real, controlled addresses. Add an OpenAI-compatible provider key/model for live AI features (never put API keys in browser code). Then:

```bash
php artisan migrate --seed
php artisan route:list
php artisan test
php artisan serve
```

Open <http://127.0.0.1:8000> and register. If using the admin allowlist, register with the configured admin email. AI features require valid provider credentials. Tests use an in-memory SQLite database and mocked HTTP; enable `pdo_sqlite`.

## Modules

- `/chat`: private conversation history and server-side AI gateway with fallback.
- `/content`: private AI-assisted drafts; new content is always saved as `draft`.
- `/contributors`: applications begin pending and admins can review at `/admin/contributors`.
- `/points`: append-only ledger and balance history.
- `/rewards`: catalog and redemption; balances are checked transactionally.
- `/admin/rewards`: reward catalog and claim management; cancelling a claim refunds points.
- `/moderation`: staff review queue, audit history, and one-time 50-point award on approval.
- `/admin`: administrator dashboard; `/admin/analytics` and CSV reports are available to admins/moderators.

Set `APP_ENV=production`, `APP_DEBUG=false`, HTTPS, a strong `APP_KEY`, server-side provider keys, and writable `storage/`/`bootstrap/cache/` for production. Point the domain document root to `public/`; back up the database before `php artisan migrate --force`. Email verification and production deployment have not been exercised by this implementation task.
