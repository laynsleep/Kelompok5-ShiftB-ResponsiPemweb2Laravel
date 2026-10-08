<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string'],
        ]);

        $allowedDomains = ['@mhs.unsoed.ac.id', '@admin.unsoed.ac.id'];

        if (! Str::endsWith($credentials['email'], $allowedDomains)) {
            throw ValidationException::withMessages([
                'email' => ['Email harus menggunakan domain @mhs.unsoed.ac.id atau @admin.unsoed.ac.id'],
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('aspirasi'));
        }

        throw ValidationException::withMessages([
            'email' => ['Kredensial yang diberikan tidak sesuai.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
