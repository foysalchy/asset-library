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

    public function sendToTokens(array $tokens, string $title, string $body, ?string $url = null, ?string $icon = null)
    {
        $webPushConfig = WebPushConfig::fromArray([
            'notification' => [
                'title' => $title,
                'body'  => $body,
                'icon'  => $icon ?? '/images/logo.png',
            ],
            'fcm_options' => [
                'link' => $url ?? '/',
            ],
        ]);

        $message = CloudMessage::new()
            ->withNotification(FirebaseNotification::create($title, $body))
            ->withWebPushConfig($webPushConfig)
            ->withData(['url' => $url ?? '/']);

        $sendReport = $this->messaging->sendMulticast($message, $tokens);

        // Invalid/expired token গুলা DB থেকে delete করে দাও
        foreach ($sendReport->invalidTokens() as $invalidToken) {
            FcmToken::where('token', $invalidToken)->delete();
        }

        return $sendReport;
    }
}