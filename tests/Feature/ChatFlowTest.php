<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_chat_with_ai_gateway(): void
    {
        config(['ai.providers' => [[
            'name' => 'test-provider',
            'base_url' => 'https://ai.example.test/v1',
            'api_key' => 'secret',
            'model' => 'test-model',
        ]]]);

        Http::fake([
            'https://ai.example.test/v1/chat/completions' => Http::response([
                'choices' => [['message' => ['content' => 'Xin chào! Đây là phản hồi AI.']]],
                'usage' => ['prompt_tokens' => 10, 'completion_tokens' => 12],
            ]),
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'chat@example.com',
            'password' => bcrypt('StrongPass123!'),
        ]);

        $response = $this->actingAs($user)->post('/chat', ['message' => 'Xin chào']);

        $response->assertRedirect();
        $this->assertDatabaseHas('chat_messages', ['role' => 'assistant', 'content' => 'Xin chào! Đây là phản hồi AI.']);
        $this->assertDatabaseHas('ai_usage_logs', ['provider' => 'test-provider', 'status' => 'succeeded']);
    }
}
