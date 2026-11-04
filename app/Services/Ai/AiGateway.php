<?php

namespace App\Services\Ai;

use App\Models\AiUsageLog;
use App\Models\ChatSession;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class AiGateway
{
    public function complete(array $messages, User $user, ChatSession $session): array
    {
        $providers = collect(config('ai.providers'))
            ->filter(fn (array $provider) => filled($provider['base_url'] ?? null))
            ->values();

        if ($providers->isEmpty()) {
            throw new RuntimeException('No AI provider is configured.');
        }

        $lastException = null;

        foreach ($providers as $provider) {
            $startedAt = microtime(true);
            try {
                $response = Http::acceptJson()
                    ->withToken($provider['api_key'])
                    ->timeout((int) config('ai.timeout', 45))
                    ->post(rtrim($provider['base_url'], '/').'/chat/completions', [
                        'model' => $provider['model'],
                        'messages' => $messages,
                    ])
                    ->throw()
                    ->json();

                $content = data_get($response, 'choices.0.message.content');
                if (! is_string($content) || trim($content) === '') {
                    throw new RuntimeException('AI provider returned an empty response.');
                }

                $this->recordUsage($user, $session, $provider, 'succeeded', $startedAt, $response);

                return [
                    'content' => trim($content),
                    'provider' => $provider['name'],
                    'model' => $provider['model'],
                ];
            } catch (Throwable $exception) {
                $lastException = $exception;
                $this->recordUsage($user, $session, $provider, 'failed', $startedAt, null);
                Log::warning('AI provider failed', ['provider' => $provider['name'], 'exception' => $exception->getMessage()]);
            }
        }

        throw new RuntimeException('All AI providers failed.', previous: $lastException);
    }

    private function recordUsage(User $user, ChatSession $session, array $provider, string $status, float $startedAt, ?array $response): void
    {
        try {
            AiUsageLog::create([
                'user_id' => $user->id,
                'chat_session_id' => $session->id,
                'provider' => $provider['name'],
                'model' => $provider['model'],
                'status' => $status,
                'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
                'prompt_tokens' => data_get($response, 'usage.prompt_tokens'),
                'completion_tokens' => data_get($response, 'usage.completion_tokens'),
            ]);
        } catch (Throwable $exception) {
            Log::warning('Unable to persist usage log', ['exception' => $exception->getMessage()]);
        }
    }
}
