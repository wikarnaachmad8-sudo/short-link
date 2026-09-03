<?php

namespace Tests\Feature;

use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortLinkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_short_link_with_valid_url()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/short-links', [
            'original_url' => 'https://www.example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('short_links', [
            'user_id' => $user->id,
            'original_url' => 'https://www.example.com',
        ]);
    }

    /** @test */
    public function user_cannot_create_short_link_with_invalid_url()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/short-links', [
            'original_url' => 'not-a-valid-url',
        ]);

        $response->assertSessionHasErrors('original_url');
    }

    /** @test */
    public function user_can_create_short_link_with_custom_alias()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/short-links', [
            'original_url' => 'https://www.example.com',
            'custom_alias' => 'my-link',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('short_links', [
            'short_code' => 'my-link',
        ]);
    }

    /** @test */
    public function user_cannot_create_short_link_with_duplicate_alias()
    {
        $user = User::factory()->create();

        ShortLink::create([
            'user_id' => $user->id,
            'original_url' => 'https://www.example.com',
            'short_code' => 'my-link',
        ]);

        $response = $this->actingAs($user)->post('/short-links', [
            'original_url' => 'https://www.google.com',
            'custom_alias' => 'my-link',
        ]);

        $response->assertSessionHasErrors('custom_alias');
    }

    /** @test */
    public function user_cannot_delete_other_users_short_link()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $link = ShortLink::create([
            'user_id' => $user1->id,
            'original_url' => 'https://www.example.com',
            'short_code' => 'abc123',
        ]);

        $response = $this->actingAs($user2)->delete('/short-links/' . $link->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('short_links', ['id' => $link->id]);
    }

    /** @test */
    public function user_can_delete_own_short_link()
    {
        $user = User::factory()->create();

        $link = ShortLink::create([
            'user_id' => $user->id,
            'original_url' => 'https://www.example.com',
            'short_code' => 'abc123',
        ]);

        $response = $this->actingAs($user)->delete('/short-links/' . $link->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('short_links', ['id' => $link->id]);
    }

    /** @test */
    public function short_code_is_generated_when_no_alias_provided()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/short-links', [
            'original_url' => 'https://www.example.com',
        ]);

        $link = ShortLink::where('user_id', $user->id)->first();
        $this->assertNotNull($link->short_code);
        $this->assertGreaterThanOrEqual(6, strlen($link->short_code));
        $this->assertLessThanOrEqual(8, strlen($link->short_code));
    }

    /** @test */
    public function qr_code_is_not_generated_by_default_on_creation()
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user)->post('/short-links', [
            'original_url' => 'https://www.google.com/search?q=laravel',
        ]);

        $link = ShortLink::where('user_id', $user->id)->first();
        $this->assertFalse($link->qr_generated);
        $this->assertNull($link->qr_code_path);
    }

    /** @test */
    public function qr_code_is_generated_and_saved_when_requested_on_creation()
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user)->post('/short-links', [
            'original_url' => 'https://www.google.com/search?q=laravel',
            'generate_qr' => 1,
        ]);

        $link = ShortLink::where('user_id', $user->id)->first();
        $this->assertTrue($link->qr_generated);
        $this->assertNotNull($link->qr_code_path);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($link->qr_code_path);
    }

    /** @test */
    public function user_cannot_retrieve_qr_code_if_not_generated()
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $user = User::factory()->create();

        $link = ShortLink::create([
            'user_id' => $user->id,
            'original_url' => 'https://www.example.com',
            'short_code' => 'noqr123',
            'qr_generated' => false,
        ]);

        $response = $this->actingAs($user)->get('/short-links/' . $link->id . '/qr-code');

        $response->assertStatus(404);
    }

    /** @test */
    public function user_can_retrieve_stored_qr_code_image()
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user)->post('/short-links', [
            'original_url' => 'https://www.google.com',
            'generate_qr' => 1,
        ]);

        $link = ShortLink::where('user_id', $user->id)->first();

        $response = $this->actingAs($user)->get('/short-links/' . $link->id . '/qr-code');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    /** @test */
    public function deleting_short_link_removes_stored_qr_code()
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user)->post('/short-links', [
            'original_url' => 'https://www.example.com',
            'generate_qr' => 1,
        ]);

        $link = ShortLink::where('user_id', $user->id)->first();
        $qrPath = $link->qr_code_path;
        $this->assertNotNull($qrPath);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($qrPath);

        $this->actingAs($user)->delete('/short-links/' . $link->id);

        \Illuminate\Support\Facades\Storage::disk('public')->assertMissing($qrPath);
    }
}
