<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upvote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpvoteController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Upvote::query()
                ->with('aspiration:id,title')
                ->latest('voted_at')
                ->paginate(15),
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'aspiration_id' => [
                'required',
                'integer',
                'exists:aspirations,id',
                Rule::unique('upvotes', 'aspiration_id')
                    ->where('user_id', $request->user()->id),
            ],
        ]);

        $upvote = $request->user()->upvotes()->create([
            ...$validated,
            'voted_at' => now(),
        ]);

        return response()->json($upvote->load('aspiration:id,title'), 201);
    }

    public function show(Upvote $upvote): JsonResponse
    {
        return response()->json($upvote->load('aspiration:id,title'));
    }

    public function destroy(Request $request, Upvote $upvote): JsonResponse
    {
        abort_unless($upvote->user_id === $request->user()->id, 403);

        $upvote->delete();

        return response()->json(status: 204);
    }
}
