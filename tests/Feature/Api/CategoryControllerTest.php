<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_401_when_creating_category_without_token(): void
    {
        $this->postJson('/api/categories', ['name' => 'Facilities'])
            ->assertUnauthorized();

        $this->assertDatabaseCount('categories', 0);
    }

    public function test_authenticated_user_can_create_category(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-device')->plainTextToken;

        $response = $this->withToken($token)->postJson('/api/categories', [
            'name' => 'Facilities',
            'description' => 'Campus facilities and maintenance.',
        ]);

        $response->assertCreated()
            ->assertJsonPath('name', 'Facilities');

        $this->assertDatabaseHas('categories', [
            'name' => 'Facilities',
            'description' => 'Campus facilities and maintenance.',
        ]);
    }
}
