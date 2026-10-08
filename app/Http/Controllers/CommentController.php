<?php

namespace App\Http\Controllers;

use App\Models\Aspiration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a new comment for the given aspiration.
     */
    public function store(Request $request, Aspiration $aspiration): RedirectResponse
    {
        $validated = $request->validate([
            'comment' => ['required', 'string'],
        ]);

        $request->user()->comments()->create([
            ...$validated,
            'aspiration_id' => $aspiration->id,
        ]);

        return redirect()
            ->route('aspirasi.show', $aspiration)
            ->with('success', 'Komentar berhasil ditambahkan.');
    }
}
