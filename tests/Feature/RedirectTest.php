<?php

namespace Tests\Feature;

use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function short_link_redirects_successfully()
    {
        $user = User::factory()->create();

        $link = ShortLink::create([
            'user_id' => $user->id,
            'original_url' => 'https://www.example.com',
            'short_code' => 'test12',
        ]);

        $response = $this->get('/test12');

        $response->assertRedirect('https://www.example.com');
    }

    /** @test */
    public function short_link_not_found_returns_404()
    {
        $response = $this->get('/nonexistent');

        $response->assertStatus(404);
    }

    /** @test */
    public function expired_short_link_shows_expired_page()
    {
        $user = User::factory()->create();

        $link = ShortLink::create([
            'user_id' => $user->id,
            'original_url' => 'https://www.example.com',
            'short_code' => 'expired1',
            'expires_at' => now()->subDay(),
        ]);

        $response = $this->get('/expired1');

        $response->assertStatus(410);
    }

    /** @test */
    public function click_count_increments_on_redirect()
    {
        $user = User::factory()->create();

        $link = ShortLink::create([
            'user_id' => $user->id,
            'original_url' => 'https://www.example.com',
            'short_code' => 'clicks1',
        ]);

        $this->assertEquals(0, $link->click_count);

        $this->get('/clicks1');

        $link->refresh();
        $this->assertEquals(1, $link->click_count);

        $this->get('/clicks1');

        $link->refresh();
        $this->assertEquals(2, $link->click_count);
    }

    /** @test */
    public function click_is_recorded_in_link_clicks_table()
    {
        $user = User::factory()->create();

        $link = ShortLink::create([
            'user_id' => $user->id,
            'original_url' => 'https://www.example.com',
            'short_code' => 'record1',
        ]);

        $this->get('/record1');

        $this->assertDatabaseHas('link_clicks', [
            'short_link_id' => $link->id,
        ]);
    }
}
