<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private function tokenFor(User $user): string
    {
        return $user->createToken('test-device')->plainTextToken;
    }

    public function test_categories_can_be_listed_publicly_with_search(): void
    {
        Category::create(['name' => 'Fasilitas']);
        Category::create(['name' => 'Akademik']);

        $this->getJson('/api/categories?search=Fasi')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Fasilitas');
    }

    public function test_returns_401_when_creating_category_without_token(): void
    {
        $this->postJson('/api/categories', ['name' => 'Facilities'])
            ->assertUnauthorized();

        $this->assertDatabaseCount('categories', 0);
    }

    public function test_regular_user_cannot_create_category(): void
    {
        $user = User::factory()->create();

        $this->withToken($this->tokenFor($user))
            ->postJson('/api/categories', ['name' => 'Facilities'])
            ->assertForbidden();

        $this->assertDatabaseCount('categories', 0);
    }

    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->admin()->create();

        $this->withToken($this->tokenFor($admin))->postJson('/api/categories', [
            'name' => 'Facilities',
            'description' => 'Campus facilities and maintenance.',
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Facilities');

        $this->assertDatabaseHas('categories', ['name' => 'Facilities']);
    }

    public function test_category_name_must_be_unique(): void
    {
        $admin = User::factory()->admin()->create();
        Category::create(['name' => 'Facilities']);

        $this->withToken($this->tokenFor($admin))
            ->postJson('/api/categories', ['name' => 'Facilities'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name');
    }

    public function test_admin_can_update_and_delete_category(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::create(['name' => 'Old']);
        $token = $this->tokenFor($admin);

        $this->withToken($token)->putJson("/api/categories/{$category->id}", ['name' => 'New'])
            ->assertOk()
            ->assertJsonPath('data.name', 'New');

        $this->withToken($token)->deleteJson("/api/categories/{$category->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_regular_user_cannot_update_or_delete_category(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Keep']);
        $token = $this->tokenFor($user);

        $this->withToken($token)->putJson("/api/categories/{$category->id}", ['name' => 'Hack'])
            ->assertForbidden();
        $this->withToken($token)->deleteJson("/api/categories/{$category->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Keep']);
    }
}
