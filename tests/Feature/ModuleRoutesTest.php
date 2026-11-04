<?php

namespace Tests\Feature;

use App\Models\ContentPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_expected_module_routes_are_registered(): void
    {
        $this->get('/')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
    }

    public function test_admin_and_moderator_pages_are_access_controlled(): void
    {
        config(['admin.emails' => ['admin@example.com'], 'admin.moderator_emails' => ['mod@example.com']]);
        $member = User::create(['name' => 'Member', 'email' => 'member@example.com', 'password' => 'password']);
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'password']);
        $moderator = User::create(['name' => 'Mod', 'email' => 'mod@example.com', 'password' => 'password']);

        $this->actingAs($member)->get('/admin')->assertForbidden();
        $this->actingAs($moderator)->get('/moderation')->assertOk();
        $this->actingAs($moderator)->get('/admin')->assertForbidden();
        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_user_cannot_open_another_users_content(): void
    {
        $author = User::create(['name' => 'Author', 'email' => 'author@example.com', 'password' => 'password']);
        $other = User::create(['name' => 'Other', 'email' => 'other@example.com', 'password' => 'password']);
        $post = ContentPost::create(['user_id' => $author->id, 'title' => 'Private', 'body' => 'Draft', 'status' => 'draft']);

        $this->actingAs($other)->get(route('content.show', $post))->assertNotFound();
    }
}
