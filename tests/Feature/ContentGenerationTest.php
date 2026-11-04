<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ContentGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_generated_content_can_be_saved_only_as_draft(): void
    {
        $user = User::create(['name' => 'Member', 'email' => 'member@example.com', 'password' => 'password']);

        $this->actingAs($user)->post('/content', [
            'title' => 'AI trong đời sống',
            'body' => 'Bản nháp cần biên tập.',
            'status' => 'published',
        ])->assertRedirect();

        $this->assertDatabaseHas('content_posts', [
            'user_id' => $user->id,
            'title' => 'AI trong đời sống',
            'status' => 'draft',
        ]);
    }

    public function test_content_generate_fails_gracefully_without_provider_configuration(): void
    {
        config(['ai.providers' => []]);
        $user = User::create(['name' => 'Member', 'email' => 'member@example.com', 'password' => 'password']);

        Http::preventStrayRequests();
        $this->actingAs($user)->post('/content/generate', ['prompt' => 'AI'])
            ->assertRedirect(route('content.create'))
            ->assertSessionHas('error');
    }
}
