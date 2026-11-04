<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        config(['admin.emails' => ['admin@example.com']]);
        $user = User::create(['name' => 'Member', 'email' => 'member@example.com', 'password' => 'password']);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_allowlisted_admin_can_access_dashboard(): void
    {
        config(['admin.emails' => ['admin@example.com']]);
        User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'password']);

        $this->actingAs(User::where('email', 'admin@example.com')->first())
            ->get('/admin')
            ->assertOk();
    }
}
