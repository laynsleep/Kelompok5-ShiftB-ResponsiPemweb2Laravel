<?php

namespace App\Http\Controllers;

use App\Enums\AspirationStatus;
use App\Models\Aspiration;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AspirationController extends Controller
{
    /**
     * Display a listing of all aspirations.
     */
    public function index(Request $request): View
    {
        $query = Aspiration::with(['author', 'categories'])
            ->withCount(['upvotes', 'comments'])
            ->latest();

        // 1. Filter Search (Judul atau Deskripsi)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Filter Kategori
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category); // Sesuaikan dengan nama tabel jika butuh prefix
            });
        }

        // 4. Tampilkan hanya aspirasi milik user yang sedang login
        if ($request->boolean('mine')) {
            $query->where('user_id', $request->user()->id);
        }

        // Pertahankan query string di URL ketika berpindah halaman (pagination)
        $aspirations = $query->paginate(10)->withQueryString();

        // Ambil semua kategori untuk di-loop di dropdown filter
        $categories = Category::orderBy('name')->get();

        return view('aspirasi.index', compact('aspirations', 'categories'));
    }

    /**
     * Show the form for creating a new aspiration.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('aspirasi.create', compact('categories'));
    }

    /**
     * Store a newly created aspiration in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->aspirationRules());

        $categoryIds = $validated['categories'] ?? [];
        unset($validated['categories']);

        $aspiration = $request->user()->aspirations()->create([
            ...$validated,
            'status' => AspirationStatus::Pending,
        ]);
        $aspiration->categories()->sync($categoryIds);

        return redirect()->route('aspirasi.index')->with('success', 'Aspirasi berhasil disampaikan.');
    }

    /**
     * Display the specified aspiration with its paginated comments.
     */
    public function show(Aspiration $aspiration): View
    {
        $aspiration->load(['author', 'categories'])
            ->loadCount(['upvotes', 'comments']);

        $comments = $aspiration->comments()
            ->with('author:id,name,username')
            ->latest()
            ->paginate(10);

        return view('aspirasi.show', compact('aspiration', 'comments'));
    }

    /**
     * Show the form for editing the given aspiration.
     */
    public function edit(Aspiration $aspiration): View
    {
        Gate::authorize('update', $aspiration);

        $aspiration->load('categories');
        $categories = Category::orderBy('name')->get();

        return view('aspirasi.edit', compact('aspiration', 'categories'));
    }

    /**
     * Update the given aspiration in storage.
     */
    public function update(Request $request, Aspiration $aspiration): RedirectResponse
    {
        Gate::authorize('update', $aspiration);

        $validated = $request->validate($this->aspirationRules());

        $categoryIds = $validated['categories'] ?? [];
        unset($validated['categories']);

        $aspiration->update($validated);
        $aspiration->categories()->sync($categoryIds);

        return redirect()
            ->route('aspirasi.show', $aspiration)
            ->with('success', 'Aspirasi berhasil diperbarui.');
    }

    /**
     * Delete the given aspiration from storage.
     */
    public function destroy(Aspiration $aspiration): RedirectResponse
    {
        Gate::authorize('delete', $aspiration);

        $aspiration->delete();

        return back()->with('success', 'Aspirasi berhasil dihapus.');
    }

    /**
     * Update the status of the given aspiration (admin only).
     */
    public function updateStatus(Request $request, Aspiration $aspiration): RedirectResponse
    {
        Gate::authorize('updateStatus', $aspiration);

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::enum(AspirationStatus::class)],
        ]);

        $aspiration->update(['status' => $validated['status']]);

        return back()->with('success', 'Status aspirasi berhasil diperbarui.');
    }

    /**
     * Get the validation rules shared by the store and update actions.
     *
     * @return array<string, array<int, mixed>>
     */
    private function aspirationRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'categories' => ['sometimes', 'array'],
            'categories.*' => ['integer', 'distinct', 'exists:categories,id'],
        ];
    }
}
