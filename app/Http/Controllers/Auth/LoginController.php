<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $key = 'login-attempt:' . Str::lower($request->input('email')) . '|' . $request->ip();
        $emailKey = 'login-email:' . Str::lower($request->input('email'));

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ])->withInput($request->only('email'));
        }

        // Secondary: limit per email regardless of IP (distributed brute force)
        if (RateLimiter::tooManyAttempts($emailKey, 15)) {
            $seconds = RateLimiter::availableIn($emailKey);
            return back()->withErrors([
                'email' => "Akun terkunci sementara. Coba lagi dalam {$seconds} detik.",
            ])->withInput($request->only('email'));
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($key);
            RateLimiter::clear($emailKey);
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($key, 60);
        RateLimiter::hit($emailKey, 300);

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
