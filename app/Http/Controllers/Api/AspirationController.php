<?php

namespace App\Http\Controllers\Api;

use App\Enums\AspirationStatus;
use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AspirationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
        ]);

        $aspirations = Aspiration::query()
            ->when(
                isset($filters['category_id']),
                fn ($query) => $query->whereHas(
                    'categories',
                    fn ($categoryQuery) => $categoryQuery->whereKey($filters['category_id']),
                ),
            )
            ->with(['author:id,name,username', 'categories:id,name'])
            ->withCount(['comments', 'upvotes'])
            ->latest()
            ->paginate(15);

        return response()->json($aspirations);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'categories' => ['sometimes', 'array'],
            'categories.*' => ['integer', 'distinct', 'exists:categories,id'],
        ]);

        $categoryIds = $validated['categories'] ?? [];
        unset($validated['categories']);

        $aspiration = $request->user()->aspirations()->create([
            ...$validated,
            'status' => AspirationStatus::Pending,
        ]);
        $aspiration->categories()->sync($categoryIds);

        return response()->json(
            $aspiration->load(['author:id,name,username', 'categories:id,name'])
                ->loadCount(['comments', 'upvotes']),
            201,
        );
    }

    public function show(Aspiration $aspiration): JsonResponse
    {
        return response()->json(
            $aspiration->load(['author:id,name,username', 'categories:id,name'])
                ->loadCount(['comments', 'upvotes']),
        );
    }

    public function update(Request $request, Aspiration $aspiration): JsonResponse
    {
        Gate::authorize('update', $aspiration);

        // Status hanya boleh diubah admin; pemilik aspirasi tidak boleh menandai aspirasinya "resolved".
        if ($request->has('status')) {
            Gate::authorize('updateStatus', $aspiration);
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'status' => ['sometimes', 'required', Rule::enum(AspirationStatus::class)],
            'categories' => ['sometimes', 'array'],
            'categories.*' => ['integer', 'distinct', 'exists:categories,id'],
        ]);

        $categoryIds = $validated['categories'] ?? null;
        unset($validated['categories']);

        $aspiration->update($validated);

        if ($categoryIds !== null) {
            $aspiration->categories()->sync($categoryIds);
        }

        return response()->json(
            $aspiration->load(['author:id,name,username', 'categories:id,name'])
                ->loadCount(['comments', 'upvotes']),
        );
    }

    public function destroy(Request $request, Aspiration $aspiration): JsonResponse
    {
        Gate::authorize('delete', $aspiration);

        $aspiration->delete();

        return response()->json(status: 204);
    }
}
