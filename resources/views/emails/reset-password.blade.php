{{-- resources/views/emails/reset-password.blade.php --}}
@extends('emails.layout')

@section('content')
<div class="icon-circle" style="margin: 0 auto 20px; width:56px; height:56px; background:#eff6ff; border-radius:50%; display:flex; align-items:center; justify-content:center;">
    <svg width="24" height="24" viewBox="0 0 20 20" fill="none" stroke="#0071c5" stroke-width="1.8">
        <path d="M5 9V6a5 5 0 0110 0v3M5 9h10a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1v-7a1 1 0 011-1z"/>
    </svg>
</div>

<h1>Reset Your Password</h1>
<p>Hi {{ $name }},</p>
<p>
    We received a request to reset the password for your {{ $settings->site_name ?? 'Bhaiya Asset' }} account. Click the button below to choose a new password.
</p>

<div class="btn-wrap">
    <a href="{{ $url }}" class="btn">Reset Password</a>
</div>

<div class="expiry-wrap">
    <span class="expiry-note">
        ⏱ This link expires in 60 minutes
    </span>
</div>

<div class="divider"></div>

<p style="font-size: 13px;">If you didn't request this, you can safely ignore this email — your password will remain unchanged.</p>

<p class="link-fallback">
    Trouble clicking the button? Copy and paste this URL into your browser:<br>
    <a href="{{ $url }}">{{ $url }}</a>
</p>
@endsection