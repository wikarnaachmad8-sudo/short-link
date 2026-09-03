<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_categories_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/categories');

        $response->assertStatus(200);
        $response->assertViewIs('categories.index');
    }

    /** @test */
    public function user_can_create_category()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/categories', [
            'name' => 'Marketing',
            'color' => 'primary',
        ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => 'Marketing',
            'slug' => 'marketing',
            'color' => 'primary',
        ]);
    }

    /** @test */
    public function user_cannot_create_category_with_duplicate_name()
    {
        $user = User::factory()->create();

        Category::create([
            'user_id' => $user->id,
            'name' => 'Marketing',
            'slug' => 'marketing',
            'color' => 'primary',
        ]);

        $response = $this->actingAs($user)->post('/categories', [
            'name' => 'Marketing',
            'color' => 'success',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function user_can_edit_and_update_category()
    {
        $user = User::factory()->create();

        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Old Name',
            'slug' => 'old-name',
            'color' => 'primary',
        ]);

        $response = $this->actingAs($user)->put("/categories/{$category->id}", [
            'name' => 'New Name',
            'color' => 'success',
        ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'New Name',
            'slug' => 'new-name',
            'color' => 'success',
        ]);
    }

    /** @test */
    public function user_cannot_edit_or_update_other_users_category()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $category = Category::create([
            'user_id' => $user1->id,
            'name' => 'User1 Category',
            'slug' => 'user1-category',
            'color' => 'primary',
        ]);

        $response = $this->actingAs($user2)->get("/categories/{$category->id}/edit");
        $response->assertStatus(403);

        $response = $this->actingAs($user2)->put("/categories/{$category->id}", [
            'name' => 'Hacked Name',
            'color' => 'danger',
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_delete_category_and_associated_short_links_category_id_becomes_null()
    {
        $user = User::factory()->create();

        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Marketing',
            'slug' => 'marketing',
            'color' => 'primary',
        ]);

        $link = ShortLink::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'original_url' => 'https://www.example.com',
            'short_code' => 'abc123',
        ]);

        $response = $this->actingAs($user)->delete("/categories/{$category->id}");

        $response->assertRedirect('/categories');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        
        $link->refresh();
        $this->assertNull($link->category_id);
    }
}
