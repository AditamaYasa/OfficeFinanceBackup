<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules;

class UserAuthController extends Controller
{
    private const MAX_ATTEMPTS = 3;
    private const LOCKOUT_MINUTES = 1;

    /**
     * Show user login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $username = $credentials['username'];
        
        if ($this->isLockedOut($username)) {
            $lockoutTime = Cache::get($this->getLockoutKey($username));
            return back()->withErrors([
                'username' => "Akun terkunci. Silakan coba lagi dalam {$lockoutTime} detik.",
            ])->onlyInput('username');
        }

        $credentials['role'] = 'user';

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $this->resetAttempts($username);
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        $this->incrementAttempts($username);
        $attempts = $this->getAttempts($username);
        
        if ($attempts >= self::MAX_ATTEMPTS) {
            $this->lockoutAccount($username);
            return back()->withErrors([
                'username' => 'Akun terkunci karena terlalu banyak percobaan login gagal. Coba lagi dalam 1 menit.',
            ])->onlyInput('username');
        }

        $remainingAttempts = self::MAX_ATTEMPTS - $attempts;
        return back()->withErrors([
            'username' => "Username atau password salah. Sisa percobaan: {$remainingAttempts}",
        ])->onlyInput('username');
    }

    /**
     * Show user register form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registrasi berhasil!');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::guard('')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Get the cache key for login attempts
     */
    private function getAttemptsKey($username)
    {
        return "login_attempts_{$username}";
    }

    /**
     * Get the cache key for lockout
     */
    private function getLockoutKey($username)
    {
        return "login_lockout_{$username}";
    }

    /**
     * Get current number of failed attempts
     */
    private function getAttempts($username)
    {
        return Cache::get($this->getAttemptsKey($username), 0);
    }

    /**
     * Increment failed login attempts
     */
    private function incrementAttempts($username)
    {
        $attemptsKey = $this->getAttemptsKey($username);
        Cache::put($attemptsKey, $this->getAttempts($username) + 1, now()->addMinutes(self::LOCKOUT_MINUTES));
    }

    /**
     * Reset login attempts
     */
    private function resetAttempts($username)
    {
        Cache::forget($this->getAttemptsKey($username));
        Cache::forget($this->getLockoutKey($username));
    }

    /**
     * Lock out the account temporarily
     */
    private function lockoutAccount($username)
    {
        $lockoutKey = $this->getLockoutKey($username);
        Cache::put($lockoutKey, self::LOCKOUT_MINUTES * 60, now()->addMinutes(self::LOCKOUT_MINUTES));
    }

    /**
     * Check if account is currently locked out
     */
    private function isLockedOut($username)
    {
        return Cache::has($this->getLockoutKey($username));
    }
}
