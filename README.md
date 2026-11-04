# BiếtTuốt MVP

This branch sets up the initial Laravel foundation for the platform core:
- authentication
- AI gateway with provider fallback
- chatbot conversation history
- role-based access using Laravel session auth

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Then open `http://127.0.0.1:8000` and register an account.

## Notes

- Keep API keys in `.env` only.
- Use an OpenAI-compatible endpoint. The `AiGateway` calls `/chat/completions` and supports a fallback provider.
- The default app uses MySQL and is ready for extension into content moderation, contributor workflows, and reward modules.
