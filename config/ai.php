<?php

return [
    'timeout' => (int) env('AI_TIMEOUT_SECONDS', 45),
    // Providers must implement the OpenAI-compatible chat-completions API.
    // Configured providers are attempted in priority order.
    'providers' => array_values(array_filter([
        [
            'name' => env('AI_PRIMARY_NAME', 'primary'),
            'base_url' => env('AI_PRIMARY_BASE_URL'),
            'api_key' => env('AI_PRIMARY_API_KEY'),
            'model' => env('AI_PRIMARY_MODEL'),
        ],
        [
            'name' => env('AI_FALLBACK_NAME', 'fallback'),
            'base_url' => env('AI_FALLBACK_BASE_URL'),
            'api_key' => env('AI_FALLBACK_API_KEY'),
            'model' => env('AI_FALLBACK_MODEL'),
        ],
    ], static fn (array $provider): bool => filled($provider['base_url'])
        && filled($provider['api_key'])
        && filled($provider['model']))),
];
