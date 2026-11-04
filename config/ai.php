<?php

return [
    'timeout' => 45,
    'providers' => [
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
    ],
];
