<?php

namespace App\Http\Controllers;

use App\Models\Aspiration;
use App\Models\Upvote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpvoteController extends Controller
{
    /**
     * Store a new upvote for the given aspiration.
     */
    public function store(Request $request, Aspiration $aspiration): RedirectResponse
    {
        $request->validate([
            'aspiration_id' => [
                'sometimes',
                'integer',
                'same:' . $aspiration->id,
            ],
        ]);

        $user = $request->user();

        // Prevent duplicate upvotes
        $alreadyVoted = $user->upvotes()->where('aspiration_id', $aspiration->id)->exists();

        if (! $alreadyVoted) {
            $user->upvotes()->create([
                'aspiration_id' => $aspiration->id,
                'voted_at' => now(),
            ]);
        }

        return back()->with('success', 'Aspirasi berhasil di-upvote.');
    }

    /**
     * Remove an upvote from the given aspiration.
     */
    public function destroy(Request $request, Aspiration $aspiration): RedirectResponse
    {
        $user = $request->user();

        $upvote = $user->upvotes()->where('aspiration_id', $aspiration->id)->first();

        if ($upvote) {
            $upvote->delete();
        }

        return back()->with('success', 'Upvote berhasil dibatalkan.');
    }
}
