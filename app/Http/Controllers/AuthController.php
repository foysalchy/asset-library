<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

  public function login(Request $request)
{
    $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    $credentials = $request->only('email', 'password');
    $remember    = $request->boolean('remember');

    if (!Auth::attempt($credentials, $remember)) {
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    $user = Auth::user();

    // role empty অথবা শুধু 'frontend_user' role থাকলে admin panel এ ঢুকতে পারবে না
    $roleNames = $user->roles->pluck('name');

    if ($roleNames->isEmpty() || $roleNames->contains('frontend_user')) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'You are not authorized to access the admin panel.']);
    }

    // Inactive user check
    if ($user->status === 'inactive') {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Your account has been deactivated. Please contact admin.']);
    }

    $user->update(['last_login_at' => now()]);

    $request->session()->regenerate();

    return redirect()->intended(route('dashboard'));
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
