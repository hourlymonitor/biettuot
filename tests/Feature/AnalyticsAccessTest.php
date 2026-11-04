<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_cannot_access_analytics(): void
    {
        config(['admin.emails' => ['admin@example.com'], 'admin.moderator_emails' => ['mod@example.com']]);
        $user = User::create(['name' => 'Member', 'email' => 'member@example.com', 'password' => 'password']);

        $this->actingAs($user)->get('/admin/analytics')->assertForbidden();
    }

    public function test_moderator_can_view_analytics_and_export_reports(): void
    {
        config(['admin.emails' => ['admin@example.com'], 'admin.moderator_emails' => ['mod@example.com']]);
        $moderator = User::create(['name' => 'Moderator', 'email' => 'mod@example.com', 'password' => 'password']);

        $this->actingAs($moderator)->get('/admin/analytics')->assertOk();
        $this->actingAs($moderator)->get('/admin/reports/ai.csv')->assertOk();
        $this->actingAs($moderator)->get('/admin/reports/moderation.csv')->assertOk();
    }
}
