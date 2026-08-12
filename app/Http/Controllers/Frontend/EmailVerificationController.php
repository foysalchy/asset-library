<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailVerificationController extends Controller
{
    /**
     * "Please verify your email" page দেখাও
     */
    public function notice()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('home.index');
        }

        return view('frontend.auth.verify-email');
    }

    /**
     * Email-er link click korle verify koro
     */
    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home.index')->with('success', 'Email already verified.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('home.index')->with('success', 'Email verified successfully! Welcome aboard.');
    }

    /**
     * Notun verification email resend koro (custom Mailer API diye)
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home.index');
        }

        $this->sendVerificationEmail($user);

        return back()->with('success', 'A new verification link has been sent to your email address.');
    }

    /**
     * Signup theke o call kora jabe eta - custom Mailer API diye
     * verification email pathay
     */
    public function sendVerificationEmail(User $user): void
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id'   => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'X-API-Key' => config('services.mailer.key'),
                ])
                ->post(config('services.mailer.url'), [
                    'type'    => 'verification',
                    'to'      => $user->email,
                    'subject' => 'Verify Your Account',
                    'data'    => [
                        'eyebrow'           => 'Account Security',
                        'heading'           => 'Verify your account',
                        'name'              => $user->name,
                        'message'           => 'Thanks for signing up! Click the button below to verify your email address and activate your account.',
                        'button_text'       => 'Verify Account',
                        'verification_link' => $verificationUrl,
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Mailer API failed for email verification: ' . $response->body());
            }
        } catch (\Throwable $e) {
            Log::error('Mailer API exception for email verification: ' . $e->getMessage());
        }
    }
}
