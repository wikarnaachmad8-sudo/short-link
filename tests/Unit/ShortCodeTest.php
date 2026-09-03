<?php

namespace Tests\Unit;

use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortCodeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function short_code_is_unique()
    {
        $user = User::factory()->create();

        $codes = [];
        for ($i = 0; $i < 20; $i++) {
            $this->actingAs($user)->post('/short-links', [
                'original_url' => 'https://www.example.com/' . $i,
            ]);
        }

        $allCodes = ShortLink::pluck('short_code')->toArray();
        $uniqueCodes = array_unique($allCodes);

        $this->assertCount(count($allCodes), $uniqueCodes, 'All short codes should be unique');
    }

    /** @test */
    public function short_code_has_valid_length()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/short-links', [
            'original_url' => 'https://www.example.com',
        ]);

        $link = ShortLink::first();

        $this->assertGreaterThanOrEqual(6, strlen($link->short_code));
        $this->assertLessThanOrEqual(8, strlen($link->short_code));
    }

    /** @test */
    public function short_code_contains_only_valid_characters()
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 10; $i++) {
            $this->actingAs($user)->post('/short-links', [
                'original_url' => 'https://www.example.com/' . $i,
            ]);
        }

        $links = ShortLink::all();
        foreach ($links as $link) {
            $this->assertMatchesRegularExpression(
                '/^[A-Za-z0-9]+$/',
                $link->short_code,
                'Short code should only contain alphanumeric characters'
            );
        }
    }
}
