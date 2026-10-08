<?php

namespace Tests\Feature\Api;

use App\Models\Aspiration;
use App\Models\Upvote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpvoteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_upvote_once(): void
    {
        $user = User::factory()->create();
        $aspiration = Aspiration::create([
            'title' => 'Improve the library',
            'description' => 'Extend library opening hours.',
            'user_id' => $user->id,
        ]);
        $token = $user->createToken('test-device')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/upvotes', [
                'aspiration_id' => $aspiration->id,
                'user_id' => 999,
            ])
            ->assertCreated()
            ->assertJsonPath('user_id', $user->id)
            ->assertJsonPath('aspiration_id', $aspiration->id);

        $this->withToken($token)
            ->postJson('/api/upvotes', ['aspiration_id' => $aspiration->id])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['aspiration_id']);

        $this->assertDatabaseCount('upvotes', 1);
        $this->assertNotNull(Upvote::firstOrFail()->voted_at);
    }

    public function test_returns_403_when_deleting_another_users_upvote(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $aspiration = Aspiration::create([
            'title' => 'Improve the library',
            'description' => 'Extend library opening hours.',
            'user_id' => $owner->id,
        ]);
        $upvote = Upvote::create([
            'user_id' => $owner->id,
            'aspiration_id' => $aspiration->id,
            'voted_at' => now(),
        ]);
        $token = $otherUser->createToken('test-device')->plainTextToken;

        $this->withToken($token)
            ->deleteJson('/api/upvotes/'.$upvote->id)
            ->assertForbidden();

        $this->assertModelExists($upvote);
    }
}
