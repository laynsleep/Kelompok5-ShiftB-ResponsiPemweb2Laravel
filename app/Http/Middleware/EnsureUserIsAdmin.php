<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Hanya izinkan request dari user dengan role admin.
     * API/JSON mendapat 403 JSON, request web mendapat halaman 403.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Aksi ini hanya dapat dilakukan oleh admin.',
                ], 403);
            }

            abort(403, 'Aksi ini hanya dapat dilakukan oleh admin.');
        }

        return $next($request);
    }
}
