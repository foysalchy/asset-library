<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FrontendAuthController extends Controller
{
     public function showSignup()
    {
        if (Auth::check()) return redirect()->route('frontend.dashboard');
        return view('frontend.auth.signup');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'status'   => 'active',
        ]);

        // Frontend user role assign
        $role = Role::where('name', 'frontend_user')->first();
        if ($role) $user->roles()->attach($role->id);

        Auth::login($user);

        return redirect('/');
    }

    public function showSignin()
    {
        if (Auth::check()) return redirect()->route('frontend.dashboard');
        return view('frontend.auth.signin');
    }

    public function signin(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withInput()->withErrors(['email' => 'Invalid credentials.']);
        }

        if (Auth::user()->status === 'inactive') {
            Auth::logout();
            return back()->withErrors(['email' => 'Your account is deactivated.']);
        }

        if (Auth::user()->isSuperAdmin()) {
            Auth::logout();
            return back()->withErrors(['email' => 'Please use admin panel.']);
        }

        Auth::user()->update(['last_login_at' => now()]);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('signin');
    }
}
