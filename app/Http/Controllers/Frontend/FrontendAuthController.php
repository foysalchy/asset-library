<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DownloadLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as FacadesPassword;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;   // 
use Illuminate\Validation\Rules;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Auth\Events\Registered;

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
            'password' => ['required', PasswordRule::defaults(), 'confirmed'],
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
            'email_verified_at'   => now(),
        ]);

        // Frontend user role assign
        $role = Role::where('name', 'frontend_user')->first();
        if ($role) $user->roles()->attach($role->id);

        auth()->login($user);

        return redirect('/home');
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

        // ✅ Email verification check
        if (!Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
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

    public function showLinkRequestForm()
    {
        return view('frontend.auth.forgot-password');
    }

    /**
     * Reset link email এ পাঠাও
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with(
                'success',
                'A password reset link has been sent to your email address. If you don\'t see it in your inbox within a few minutes, please check your Spam or Junk folder.'
            );
        }

        $token = FacadesPassword::createToken($user); // ✅ ekhon thik Facade use hবে

        $user->notify(new CustomResetPasswordNotification($token));

        return back()->with(
            'success',
            'A password reset link has been sent to your email address. If you don\'t see it in your inbox within a few minutes, please check your Spam or Junk folder.'
        );
    }

    /**
     * Reset password form দেখাও
     */
    public function showResetForm(Request $request, $token)
    {
        return view('frontend.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Password reset koro
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = FacadesPassword::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status === FacadesPassword::PASSWORD_RESET) {
            return redirect()->route('signin')->with('success', 'Your password has been reset successfully. Please sign in.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
