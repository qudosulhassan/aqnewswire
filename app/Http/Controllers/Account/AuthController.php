<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('account.dashboard');
        }

        if (!\App\Models\Setting::get('enable_registration', true)) {
            return redirect()->route('login')->with('info', 'Reader registration is currently closed by site administration.');
        }

        return view('account.auth.register');
    }

    public function register(Request $request)
    {
        if (!\App\Models\Setting::get('enable_registration', true)) {
            return redirect()->route('login')->with('error', 'Reader registration is currently closed.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'reader',
            'slug' => Str::slug($validated['name']) . '-' . Str::random(4),
        ]);

        AuditLog::record('user_registered', 'User', $user->id, "Public registration for {$user->email}");

        Auth::login($user);

        return redirect()->route('account.dashboard')->with('success', 'Welcome to your AQ NEWSWIRE Executive Reader account!');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('account.dashboard');
        }
        return view('account.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Please contact support.',
                ])->onlyInput('email');
            }

            $user->update(['last_login_at' => now()]);
            $request->session()->regenerate();
            AuditLog::record('user_login', 'User', $user->id, 'Public reader logged in');
            return redirect()->intended(route('account.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid email or password credentials.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        AuditLog::record('user_logout', 'User', Auth::id(), 'Public reader logged out');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been successfully signed out.');
    }
}
