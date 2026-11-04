<?php

namespace Tests\Feature;

use App\Models\ContentPost;
use App\Models\PointLedger;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityModulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_contributor_registration_starts_pending_and_is_not_self_approved(): void
    {
        $user = User::create(['name' => 'Writer', 'email' => 'writer@example.com', 'password' => 'password']);
        $this->actingAs($user)->post('/contributors/apply', ['skills' => 'Writing'])->assertRedirect();
        $this->assertDatabaseHas('contributor_profiles', ['user_id' => $user->id, 'status' => 'pending']);
    }

    public function test_content_approval_awards_points_once(): void
    {
        config(['admin.emails' => ['admin@example.com']]);
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'password']);
        $author = User::create(['name' => 'Writer', 'email' => 'writer@example.com', 'password' => 'password']);
        $post = ContentPost::create(['user_id' => $author->id, 'title' => 'Draft', 'body' => 'Text', 'status' => 'draft']);

        $this->actingAs($admin)->post(route('moderation.approve', $post))->assertRedirect();
        $this->assertSame(50, $author->pointBalance());
        $this->actingAs($admin)->post(route('moderation.approve', $post))->assertStatus(409);
        $this->assertSame(50, $author->fresh()->pointBalance());
    }

    public function test_reward_redemption_debits_points_and_creates_claim(): void
    {
        $user = User::create(['name' => 'Member', 'email' => 'member@example.com', 'password' => 'password']);
        PointLedger::create(['user_id' => $user->id, 'points' => 100, 'reason' => 'Test award', 'source' => 'test']);
        $reward = Reward::create(['name' => 'Sticker', 'description' => 'Test', 'cost_points' => 40, 'active' => true]);

        $this->actingAs($user)->post(route('rewards.redeem', $reward))->assertRedirect();
        $this->assertSame(60, $user->fresh()->pointBalance());
        $this->assertDatabaseHas('reward_claims', ['user_id' => $user->id, 'reward_id' => $reward->id, 'status' => 'pending']);
    }
}
