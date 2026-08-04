<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DownloadLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class FrontendAuthController extends Controller
{
    //profile
    public function index()
    {
        $downloadLogs = DownloadLog::where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('frontend.auth.profile', compact('downloadLogs'));
    }
    //profile update
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->hasFile('avatar')) {
            if ($user->getAttributes()['avatar']) {
                Storage::disk('public')->delete($user->getAttributes()['avatar']);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
     public function updatePassword(Request $request)
    {
        $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'password-updated');
    }
    public function showSignup()
    {
        if (Auth::check()) return redirect()->back();
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
            'employee_id'    => $request->employee_id,
            'password' => Hash::make($request->password),
            'status'   => 'active',
        ]);

        // Frontend user role assign
        $role = Role::where('name', 'frontend_user')->first();
        if ($role) $user->roles()->attach($role->id);
        return redirect()->route('signin')
            ->with('success', 'Your account has been created successfully.');
    }

    public function showSignin()
    {
        if (Auth::check()) return redirect()->back();
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


        return redirect('/home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('signin');
    }
}
