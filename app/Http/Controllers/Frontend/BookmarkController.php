<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function list()
    {
        $assets = \App\Models\Asset::whereHas('bookmarks', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('media')->latest()->get();

        $campaigns = \App\Models\Campaign::whereHas('bookmarks', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('project')->latest()->get();

        return view('frontend.bookmarks', compact('assets', 'campaigns'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'type' => 'required|in:asset,campaign',
            'id'   => 'required|integer',
        ]);

        $field = $request->type . '_id'; // 'asset_id' or 'campaign_id'

        $existing = Bookmark::where('user_id', auth()->id())
            ->where($field, $request->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $bookmarked = false;
        } else {
            Bookmark::create([
                'user_id' => auth()->id(),
                $field    => $request->id,
            ]);
            $bookmarked = true;
        }

        return response()->json([
            'bookmarked' => $bookmarked,
        ]);
    }
}
