<?php

namespace Tests\Feature\Api;

use App\Models\Aspiration;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_comment(): void
    {
        $author = User::factory()->create();
        $aspiration = Aspiration::create([
            'title' => 'Improve the library',
            'description' => 'Extend library opening hours.',
            'user_id' => $author->id,
        ]);

        $this->postJson('/api/comments', [
            'aspiration_id' => $aspiration->id,
            'comment' => 'This would help students.',
        ])->assertUnauthorized();

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_authenticated_user_becomes_comment_author(): void
    {
        $user = User::factory()->create();
        $aspiration = Aspiration::create([
            'title' => 'Improve the library',
            'description' => 'Extend library opening hours.',
            'user_id' => $user->id,
        ]);
        $token = $user->createToken('test-device')->plainTextToken;

        $response = $this->withToken($token)->postJson('/api/comments', [
            'aspiration_id' => $aspiration->id,
            'comment' => 'This would help students.',
            'user_id' => 999,
        ]);

        $response->assertCreated()
            ->assertJsonPath('author.id', $user->id);

        $this->assertDatabaseHas('comments', [
            'aspiration_id' => $aspiration->id,
            'user_id' => $user->id,
            'comment' => 'This would help students.',
        ]);
    }

    public function test_returns_403_when_updating_another_users_comment(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $aspiration = Aspiration::create([
            'title' => 'Improve the library',
            'description' => 'Extend library opening hours.',
            'user_id' => $owner->id,
        ]);
        $comment = Comment::create([
            'user_id' => $owner->id,
            'aspiration_id' => $aspiration->id,
            'comment' => 'Original comment.',
        ]);
        $token = $otherUser->createToken('test-device')->plainTextToken;

        $this->withToken($token)
            ->patchJson('/api/comments/'.$comment->id, ['comment' => 'Changed comment.'])
            ->assertForbidden();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'comment' => 'Original comment.',
        ]);
    }
}
