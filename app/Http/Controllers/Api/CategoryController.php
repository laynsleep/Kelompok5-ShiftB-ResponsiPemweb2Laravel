<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    /**
     * Publik: daftar kategori (dengan pencarian ?search=).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate(['search' => ['sometimes', 'string', 'max:255']]);

        $categories = Category::query()
            ->withCount('aspirations')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return CategoryResource::collection($categories);
    }

    /**
     * Admin: membuat kategori.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category->loadCount('aspirations'));
    }

    /**
     * Admin: memperbarui kategori.
     */
    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        $category->update($request->validated());

        return new CategoryResource($category->loadCount('aspirations'));
    }

    /**
     * Admin: menghapus kategori (relasi pivot ikut terhapus via cascade).
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(status: 204);
    }
}
