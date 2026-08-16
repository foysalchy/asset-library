<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DownloadLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password as FacadesPassword;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use App\Http\Controllers\Frontend\EmailVerificationController;
use App\Rules\Recaptcha;

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
        $prefixes = \App\Models\Project::CONCERN_PREFIXES;
        $selectedPrefix = $prefixes[$request->concern] ?? '';
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'concern'             => ['required', 'in:' . implode(',', array_keys(\App\Models\Project::CONCERNS))],
            'employee_id_suffix'  => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'recaptcha_token' => ['required', new Recaptcha()],

        ]);
        $fullEmployeeId = $selectedPrefix . $request->employee_id_suffix;
        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'employee_id'   => strtoupper($fullEmployeeId),
            'password'          => Hash::make($request->password),
            'status'            => 'active',

        ]);

        $role = Role::where('name', 'frontend_user')->first();
        if ($role) $user->roles()->attach($role->id);

        auth()->login($user);

        app(EmailVerificationController::class)->sendVerificationEmail($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Account created! Please check your email to verify your account.');
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
            'password' => ['required', 'string'],
            'recaptcha_token' => ['required', new Recaptcha()],

        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['email' => 'Invalid credentials.'])
                ->onlyInput('email');
        }
        if ($user->status !== 'active') {
            return back()
                ->withErrors(['email' => 'Your account is inactive. Please contact support.'])
                ->onlyInput('email');
        }
        $user->update([
            'last_login_at' => now()
        ]);


        auth()->login($user, $request->boolean('remember'));

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return redirect()->intended('/home');
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
     * Reset link email এ পাঠাও - এখন নিজের mailer API ব্যবহার করে,
     * Laravel এর ডিফল্ট SMTP notification এর বদলে।
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $successMessage = 'A password reset link has been sent to your email address. If you don\'t see it in your inbox within a few minutes, please check your Spam or Junk folder.';

        $user = User::where('email', $request->email)->first();

        // Same response whether the user exists or not - avoids leaking
        // which emails are registered in the system.
        if (!$user) {
            return back()->with('success', $successMessage);
        }

        $token = FacadesPassword::createToken($user);

        $resetLink = route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'X-API-Key' => config('services.mailer.key'),
                ])
                ->post(config('services.mailer.url'), [
                    'type'    => 'verification',
                    'to'      => $user->email,
                    'subject' => 'Reset Your Password',
                    'data'    => [
                        'eyebrow'           => 'Password Reset',
                        'heading'           => 'Reset your password',
                        'name'              => $user->name,
                        'message'           => 'We received a request to reset your password. Click the button below to choose a new one. This link is valid for a limited time and can only be used once.',
                        'button_text'       => 'Reset Password',
                        'verification_link' => $resetLink,
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Mailer API failed for password reset: ' . $response->body());
            }
        } catch (\Throwable $e) {
            // Mailer service unreachable/timeout - don't break the user flow,
            // just log it so it can be investigated.
            Log::error('Mailer API exception for password reset: ' . $e->getMessage());
        }

        return back()->with('success', $successMessage);
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
