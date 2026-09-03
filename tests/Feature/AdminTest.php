<?php

namespace Tests\Feature;

use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    /** @test */
    public function regular_user_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_delete_other_users_short_link()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $link = ShortLink::create([
            'user_id' => $user->id,
            'original_url' => 'https://www.example.com',
            'short_code' => 'admin1',
        ]);

        $response = $this->actingAs($admin)->delete('/admin/short-links/' . $link->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('short_links', ['id' => $link->id]);
    }

    /** @test */
    public function admin_can_view_user_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_short_link_detail()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $link = ShortLink::create([
            'user_id' => $user->id,
            'original_url' => 'https://www.example.com',
            'short_code' => 'admsh1',
        ]);

        $response = $this->actingAs($admin)->get('/admin/short-links/' . $link->id);

        $response->assertStatus(200);
        $response->assertSee('Detail Short Link');
        $response->assertSee('admsh1');
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete('/admin/users/' . $user->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function admin_can_view_create_user_page()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/users/create');

        $response->assertStatus(200);
        $response->assertSee('Tambah User Baru');
    }

    /** @test */
    public function admin_can_create_new_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'New User by Admin',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name' => 'New User by Admin',
            'role' => 'user',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function admin_cannot_create_user_with_duplicate_email()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Duplicate Email User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function regular_user_cannot_create_user()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post('/admin/users', [
            'name' => 'Hacker User',
            'email' => 'hacker@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', ['email' => 'hacker@example.com']);
    }
}
