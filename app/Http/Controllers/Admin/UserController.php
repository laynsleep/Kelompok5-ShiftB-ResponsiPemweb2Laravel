<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->withCount('aspirations')
            ->when($request->filled('search'), fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('username', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%')))
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user): RedirectResponse
    {
        if ($user->is($request->user()) && $request->validated('role') !== 'admin') {
            return back()->withErrors(['role' => 'Anda tidak dapat menurunkan role akun Anda sendiri.']);
        }

        $user->forceFill(['role' => $request->validated('role')])->save();

        return back()->with('success', "Role {$user->name} berhasil diubah menjadi {$user->role->value}.");
    }
}
