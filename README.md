# BiếtTuốt — Laravel 11 web app

This repository contains the complete first web-app foundation for the agreed modules: account registration/login, AI chat and conversation history, AI content drafts, contributor profiles, points ledger, reward redemption, editorial moderation, staff analytics, and CSV reports. It uses Laravel 11, PHP 8.2+, and MySQL. AI requests are server-side and use OpenAI-compatible chat-completions endpoints.

## Run locally (copy/paste)

Install PHP 8.2+, Composer 2, MySQL 8+, and PHP extensions `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`. For automated tests also enable `pdo_sqlite`.

1. Create a MySQL database named `biettuot` (or choose another name and update `.env`).
2. In the repository directory, run:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

3. Edit `.env` and set your MySQL credentials. Set `ADMIN_EMAILS` to the email address that will administer the site and `MODERATOR_EMAILS` to moderator addresses. Configure an AI provider if you want live AI features:

```dotenv
AI_PRIMARY_NAME=openai
AI_PRIMARY_BASE_URL=https://api.openai.com/v1
AI_PRIMARY_API_KEY=your-provider-secret
AI_PRIMARY_MODEL=your-enabled-model
```

Never commit `.env` or expose provider API keys in browser code. You need an account with the chosen provider for real AI calls; without it, the rest of the site runs but AI generation reports that no provider is configured.

4. Run migrations, seed sample reward catalog entries, and start the site:

```bash
php artisan migrate --seed
php artisan serve
```

Open <http://127.0.0.1:8000>, register the account using the admin email configured above, then sign in. Email verification is not configured in this MVP, so only set `ADMIN_EMAILS` to an address you control and do not deploy public registration without adding verification/anti-abuse controls.

## Modules and routes

- Auth: `/register`, `/login`, `/logout` (session auth, CSRF, password hashing, login/register throttling).
- Chat: `/chat`; messages and sessions are private to their owner.
- AI gateway: primary + fallback provider, timeout, token/duration/status logs, credentials remain server side.
- Content: `/content`; AI-assisted drafts are private and cannot be self-published.
- Contributor: `/contributors`; profile and application status.
- Points/rewards: `/points`, `/rewards`; ledger-based balance and serialized redemption.
- Admin: `/admin`; allowlisted administrator access.
- Moderation: `/moderation`; allowlisted admin/moderator review, audit history, approval points are awarded once.
- Analytics: `/admin/analytics`; date-filtered operations/AI/review metrics and CSV exports.

Set `ADMIN_EMAILS` and `MODERATOR_EMAILS` as comma-separated server environment variables. Admins can use admin and staff pages; moderators can use staff analytics/moderation but not the admin dashboard or manual point-award endpoint.

## Verify

```bash
php artisan route:list
php artisan test
```

Tests use an in-memory SQLite database and mocked AI HTTP responses; they do not call a live provider. Live MySQL, host configuration, and provider credentials must be verified in your environment.

## Deploy

Point the domain document root to `public/`; configure production env values (`APP_ENV=production`, `APP_DEBUG=false`, strong generated `APP_KEY`, HTTPS, MySQL credentials, AI keys and staff allowlists); grant write access to `storage/` and `bootstrap/cache/`; then run `php artisan migrate --seed --force`. Back up MySQL before migrations. Do not expose the repository root or `.env` over HTTP. Add email verification, backups, monitoring and rate limits appropriate to production before public launch.
