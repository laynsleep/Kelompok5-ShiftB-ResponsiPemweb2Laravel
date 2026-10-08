<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Comment::query()
                ->with(['author:id,name,username', 'aspiration:id,title'])
                ->latest()
                ->paginate(15),
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'aspiration_id' => ['required', 'integer', 'exists:aspirations,id'],
            'comment' => ['required', 'string'],
        ]);

        $comment = $request->user()->comments()->create($validated);

        return response()->json(
            $comment->load(['author:id,name,username', 'aspiration:id,title']),
            201,
        );
    }

    public function show(Comment $comment): JsonResponse
    {
        return response()->json(
            $comment->load(['author:id,name,username', 'aspiration:id,title']),
        );
    }

    public function update(Request $request, Comment $comment): JsonResponse
    {
        abort_unless($comment->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'comment' => ['required', 'string'],
        ]);

        $comment->update($validated);

        return response()->json(
            $comment->load(['author:id,name,username', 'aspiration:id,title']),
        );
    }

    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        abort_unless($comment->user_id === $request->user()->id, 403);

        $comment->delete();

        return response()->json(status: 204);
    }
}
