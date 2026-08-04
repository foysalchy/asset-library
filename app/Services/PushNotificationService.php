<?php

namespace App\Services;

use App\Models\FcmToken;
use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Firebase\Messaging\WebPushConfig;
use Kreait\Firebase\Contract\Messaging;

class PushNotificationService
{
    protected Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function sendToUser(User $user, string $title, string $body, ?string $url = null, ?string $icon = null)
    {
        $tokens = FcmToken::where('user_id', $user->id)->pluck('token')->toArray();

        if (empty($tokens)) {
            return;
        }

        return $this->sendToTokens($tokens, $title, $body, $url, $icon);
    }

  // app/Services/PushNotificationService.php
public function sendToTokens(array $tokens, string $title, string $body, ?string $url = null, ?string $icon = null)
{
    $message = CloudMessage::new()
        ->withData([
            'title' => $title,
            'body'  => $body,
            'url'   => $url ?? '/',
            'icon'  => $icon ?? asset('/logo.png'), // ✅ full URL app logo
        ]);


    $sendReport = $this->messaging->sendMulticast($message, $tokens);

    foreach ($sendReport->invalidTokens() as $invalidToken) {
        FcmToken::where('token', $invalidToken)->delete();
    }

    return $sendReport;
}
}