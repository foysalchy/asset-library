<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class CustomVerifyEmailNotification extends VerifyEmailBase
{
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',                          // ✅ actual verify route
            Carbon::now()->addMinutes(60),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    public function toMail($notifiable)
    {
        $url = $this->verificationUrl($notifiable);          // ✅ eituku theke actual signed URL asবে

        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->view('emails.verify-email', [
                'url'  => $url,                               // ✅ view-e pass hচ্ছে
                'name' => $notifiable->name,
            ]);
    }
}
