<?php

namespace App\Http\Controllers;

use App\Models\Aspiration;
use Illuminate\Contracts\View\View;

class AspirationController extends Controller
{
    /**
     * Display a listing of all aspirations.
     */
    public function index(): View
    {
        $aspirations = Aspiration::with(['author', 'categories'])
            ->withCount(['upvotes', 'comments'])
            ->latest()
            ->paginate(10);

        return view('aspirasi.index', compact('aspirations'));
    }
}
