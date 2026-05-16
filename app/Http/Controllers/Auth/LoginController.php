<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        $turnstileSiteKey = config('services.turnstile.site_key', '');
        return view('auth.login', compact('turnstileSiteKey'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Verify Turnstile if configured
        $turnstileSecret = config('services.turnstile.secret_key', '');
        if ($turnstileSecret) {
            $token = $request->input('cf-turnstile-response');
            if (!$token) {
                return back()->withErrors(['username' => 'Verifikasi CAPTCHA diperlukan.'])
                    ->withInput($request->only('username'));
            }
            $verify = \Illuminate\Support\Facades\Http::asForm()->post(
                'https://challenges.cloudflare.com/turnstile/v0/siteverify',
                ['secret' => $turnstileSecret, 'response' => $token, 'remoteip' => $request->ip()]
            );
            if (!$verify->json('success')) {
                return back()->withErrors(['username' => 'Verifikasi CAPTCHA gagal. Silakan coba lagi.'])
                    ->withInput($request->only('username'));
            }
        }

        $ip = $request->ip();
        $key = 'login:' . $ip;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'username' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ])->withInput($request->only('username'));
        }

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($key);
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($key, 15 * 60);

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
