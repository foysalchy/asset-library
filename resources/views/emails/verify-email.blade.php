@extends('emails.layout')

@section('content')
<div class="icon-circle" style="margin: 0 auto 20px; width:56px; height:56px; background:#eff6ff; border-radius:50%; display:flex; align-items:center; justify-content:center;">
    <svg width="26" height="26" viewBox="0 0 20 20" fill="none" stroke="#0071c5" stroke-width="1.8">
        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" fill="#0071c5" stroke="none"/>
        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" fill="#0071c5" stroke="none"/>
    </svg>
</div>

<h1>Verify Your Email Address</h1>
<p>Hi {{ $name }},</p>
<p>
    Welcome to {{ $settings->site_name ?? 'Bhaiya Asset' }}! Please confirm your email address to activate your account and get full access to our platform.
</p>

<div class="btn-wrap">
    <a href="{{ $url }}" class="btn">Verify Email Address</a>
</div>

<div class="expiry-wrap">
    <span class="expiry-note">
        ⏱ This link expires in 60 minutes
    </span>
</div>

<div class="divider"></div>

<p style="font-size: 13px;">If you did not create this account, no further action is required.</p>

<p class="link-fallback">
    Trouble clicking the button? Copy and paste this URL into your browser:<br>
    <a href="{{ $url }}">{{ $url }}</a>
</p>
@endsection