<?php

namespace App\Http\Controllers;

use App\Models\FcmToken;
use Illuminate\Http\Request;

class FcmController extends Controller
{
    public function saveToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        FcmToken::updateOrCreate(
            ['token' => $request->token],
            [
                'user_id' => auth()->id(),
                'device'  => $request->userAgent(),
            ]
        );

        return response()->json(['success' => true]);
    }
}