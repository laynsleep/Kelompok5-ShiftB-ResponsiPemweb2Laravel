<?php

namespace Tests\Feature\Api;

use App\Enums\UserRole;
use App\Models\Aspiration;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AspirationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_401_when_creating_aspiration_without_token(): void
    {
        $this->postJson('/api/aspirations', [
            'title' => 'Improve the library',
            'description' => 'Extend library opening hours.',
        ])->assertUnauthorized();

        $this->assertDatabaseCount('aspirations', 0);
    }

    public function test_authenticated_user_becomes_aspiration_author_instead_of_request_user_id(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $token = $author->createToken('test-device')->plainTextToken;

        $response = $this->withToken($token)->postJson('/api/aspirations', [
            'title' => 'Improve the library',
            'description' => 'Extend library opening hours.',
            'user_id' => $otherUser->id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('author.id', $author->id)
            ->assertJsonPath('status', 'pending');

        $this->assertDatabaseHas('aspirations', [
            'title' => 'Improve the library',
            'user_id' => $author->id,
        ]);
    }

    public function test_returns_403_when_updating_another_users_aspiration(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $aspiration = Aspiration::create([
            'title' => 'Original title',
            'description' => 'Original description.',
            'user_id' => $owner->id,
        ]);
        $token = $otherUser->createToken('test-device')->plainTextToken;

        $this->withToken($token)
            ->patchJson('/api/aspirations/'.$aspiration->id, ['title' => 'Changed title'])
            ->assertForbidden();

        $this->assertDatabaseHas('aspirations', [
            'id' => $aspiration->id,
            'title' => 'Original title',
        ]);
    }

    public function test_admin_can_update_and_delete_another_users_aspiration(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create();
        DB::table('users')->where('id', $admin->id)->update([
            'role' => UserRole::Admin->value,
        ]);
        $aspiration = Aspiration::create([
            'title' => 'Original title',
            'description' => 'Original description.',
            'user_id' => $owner->id,
        ]);
        $token = $admin->createToken('admin-device')->plainTextToken;

        $this->withToken($token)
            ->patchJson('/api/aspirations/'.$aspiration->id, ['title' => 'Admin updated'])
            ->assertOk()
            ->assertJsonPath('title', 'Admin updated');

        $this->withToken($token)
            ->deleteJson('/api/aspirations/'.$aspiration->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('aspirations', ['id' => $aspiration->id]);
    }

    public function test_owner_can_update_own_aspiration(): void
    {
        $owner = User::factory()->create();
        $aspiration = Aspiration::create([
            'title' => 'Original title',
            'description' => 'Original description.',
            'user_id' => $owner->id,
        ]);
        $token = $owner->createToken('test-device')->plainTextToken;

        $this->withToken($token)
            ->patchJson('/api/aspirations/'.$aspiration->id, ['title' => 'Owner updated'])
            ->assertOk()
            ->assertJsonPath('title', 'Owner updated');

        $this->assertDatabaseHas('aspirations', [
            'id' => $aspiration->id,
            'title' => 'Owner updated',
        ]);
    }

    public function test_guest_can_filter_aspirations_by_category(): void
    {
        $author = User::factory()->create();
        $requestedCategory = Category::create(['name' => 'Facilities']);
        $otherCategory = Category::create(['name' => 'Events']);
        $matchingAspiration = Aspiration::create([
            'title' => 'Improve the library',
            'description' => 'Extend library opening hours.',
            'user_id' => $author->id,
        ]);
        $otherAspiration = Aspiration::create([
            'title' => 'Add a campus event',
            'description' => 'Organize more community events.',
            'user_id' => $author->id,
        ]);
        $matchingAspiration->categories()->attach($requestedCategory);
        $otherAspiration->categories()->attach($otherCategory);

        $this->getJson('/api/aspirations?category_id='.$requestedCategory->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $matchingAspiration->id);
    }

    public function test_guest_cannot_filter_aspirations_by_unknown_category(): void
    {
        $this->getJson('/api/aspirations?category_id=999')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['category_id']);
    }

    public function test_owner_cannot_change_aspiration_status_but_admin_can(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $aspiration = Aspiration::create([
            'title' => 'Judul',
            'description' => 'Deskripsi.',
            'user_id' => $owner->id,
        ]);

        $this->withToken($owner->createToken('t')->plainTextToken)
            ->putJson("/api/aspirations/{$aspiration->id}", ['status' => 'resolved'])
            ->assertForbidden();

        $this->app['auth']->forgetGuards();

        $this->withToken($admin->createToken('t')->plainTextToken)
            ->putJson("/api/aspirations/{$aspiration->id}", ['status' => 'resolved'])
            ->assertOk()
            ->assertJsonPath('status', 'resolved');
    }
}
