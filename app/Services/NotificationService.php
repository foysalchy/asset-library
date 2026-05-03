<?php

namespace App\Services;

use App\Mail\NewContentMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public static function notifyAll(string $type, int $typeId, string $title, string $slug): void
    {
        $url = $type === 'asset'
            ? route('asset.details', $slug)
            : route('campaign.details', $slug);

        // একটাই notification row
        Notification::create([
            'title'   => 'New ' . ucfirst($type) . ' Added',
            'message' => $title,
            'type'    => $type,
            'type_id' => $typeId,
            'url'     => $url,
            'read_by' => [],
        ]);

        // সব user এ mail
        foreach (User::all() as $user) {
            Mail::to($user->email)->send(new NewContentMail($type, $title, $url));
        }
    }
}
