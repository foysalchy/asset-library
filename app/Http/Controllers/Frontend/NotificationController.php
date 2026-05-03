<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markRead(Notification $notification)
    {
        $notification->markAsReadBy(auth()->id());
        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        $userId = auth()->id();
        Notification::latest()->get()->each(function ($n) use ($userId) {
            $n->markAsReadBy($userId);
        });
        return response()->json(['success' => true]);
    }
}
