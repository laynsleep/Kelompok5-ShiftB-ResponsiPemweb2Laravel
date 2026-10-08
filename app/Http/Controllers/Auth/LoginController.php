<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if (! str_ends_with($credentials['email'], '@mhs.unsoed.ac.id')) {
            throw ValidationException::withMessages([
                'email' => ['Email harus menggunakan domain @mhs.unsoed.ac.id'],
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

        return redirect('/');
    }
}
