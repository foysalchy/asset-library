<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class EmailVerificationController extends Controller
{
    /**
     * "Please verify your email" page dেখাও
     */
    public function notice()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        return view('frontend.auth.verify-email');
    }

    /**
     * Email-er link click korলে verify koro
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
     * Notun verification email resend koro
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home.index');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'A new verification link has been sent to your email address.');
    }
}