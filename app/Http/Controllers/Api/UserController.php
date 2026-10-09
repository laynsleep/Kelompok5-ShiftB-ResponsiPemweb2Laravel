<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * Admin: daftar user, bisa dicari (?search=) dan difilter (?role=).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->validate([
            'search' => ['sometimes', 'string', 'max:255'],
            'role' => ['sometimes', 'in:user,admin'],
        ]);

        $users = User::query()
            ->withCount('aspirations')
            ->when($filters['search'] ?? null, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$s}%")
                ->orWhere('username', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%")))
            ->when($filters['role'] ?? null, fn ($q, $r) => $q->where('role', $r))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return UserResource::collection($users);
    }

    /**
     * Admin: mengubah role user (promote / demote).
     */
    public function updateRole(UpdateUserRoleRequest $request, User $user): JsonResponse
    {
        if ($user->is($request->user()) && $request->validated('role') !== 'admin') {
            return response()->json([
                'message' => 'Anda tidak dapat menurunkan role akun Anda sendiri.',
            ], 422);
        }

        // 'role' sengaja tidak fillable (anti mass-assignment), jadi di-set eksplisit.
        $user->forceFill(['role' => $request->validated('role')])->save();

        return (new UserResource($user))->response();
    }
}
