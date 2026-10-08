<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_user_and_returns_bearer_token(): void
    {
        $response = $this->postJson('/api/register', [
            'username' => 'api_user',
            'name' => 'API User',
            'email' => 'api@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'device_name' => 'test-device',
            'role' => 'admin',
        ]);

        $response->assertCreated()
            ->assertJsonPath('user.username', 'api_user')
            ->assertJsonPath('user.email', 'api@example.com')
            ->assertJsonPath('user.role', 'user')
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonStructure(['access_token']);

        $this->assertDatabaseHas('users', [
            'username' => 'api_user',
            'email' => 'api@example.com',
        ]);
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'api@example.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'incorrect-password',
            'device_name' => 'test-device',
        ]);

        $response->assertUnprocessable()
            ->assertJsonPath('errors.email.0', 'The provided credentials are incorrect.');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_logout_revokes_the_current_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-device')->plainTextToken;

        $this->withToken($token)->postJson('/api/logout')->assertNoContent();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_returns_401_for_user_endpoint_without_token(): void
    {
        $this->getJson('/api/user')->assertUnauthorized();
    }
}
