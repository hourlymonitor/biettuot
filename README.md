# BiếtTuốt MVP

Laravel 11 + MySQL starter for authentication, AI Gateway with provider fallback, chatbot history, AI-assisted content drafts, and an admin dashboard.

## Requirements

- PHP 8.2+
- Composer 2
- MySQL 8+ (or SQLite for automated tests)
- Laravel-required PHP extensions, including PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON, and BCMath

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure MySQL and at least one OpenAI-compatible chat-completions provider in `.env`, then run:

```bash
php artisan migrate
php artisan serve
```

Visit `http://127.0.0.1:8000`, register, and start chatting. Example AI settings:

```dotenv
AI_PRIMARY_NAME=openai
AI_PRIMARY_BASE_URL=https://api.openai.com/v1
AI_PRIMARY_API_KEY=your-secret-key
AI_PRIMARY_MODEL=your-enabled-model
```

Optional fallback provider uses the corresponding `AI_FALLBACK_*` variables. Keys are read only by the backend. Do not commit `.env` or paste secrets into frontend code.

## Admin access

Set `ADMIN_EMAILS` to a comma-separated allowlist of administrator email addresses. Only authenticated users whose email is on this list may access `/admin`. The dashboard is read-only at this stage.

## Content workflow

Users can generate and save AI-assisted content drafts. Drafts are private to their owner. Publishing is intentionally disabled until moderation and editorial review are implemented; submitted `status` values cannot bypass this restriction.

## Tests

```bash
php artisan test
```

Feature tests cover registration/login, chat responses through a mocked AI endpoint, draft status enforcement, and admin access control. Tests use an in-memory SQLite database, so PHP's `pdo_sqlite` extension is required.

## Hostinger deployment

Use a plan with PHP 8.2+, Composer/SSH, and MySQL support; a VPS provides the most control. Point the web root at Laravel's `public/` directory, set production environment variables on the host, use `APP_ENV=production` and `APP_DEBUG=false`, enable HTTPS, and ensure `storage/` and `bootstrap/cache/` are writable. Back up the database before `php artisan migrate --force`. Never expose `.env` or the project root publicly.
