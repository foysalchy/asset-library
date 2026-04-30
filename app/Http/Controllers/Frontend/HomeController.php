<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Campaign;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCampaigns = Campaign::with('project')->latest()->take(4)->get();

        $latestAssets = Asset::with('media')->latest()->get();

        $recommendedAssets = Asset::with('media')->inRandomOrder()->take(8)->get();

        // dd($latestAssets);
        return view('frontend.index', compact('featuredCampaigns', 'latestAssets','recommendedAssets'));
    }
    public function campaignDetails($slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();

        return view('frontend.campaignDetails', compact('campaign'));
    }
    public function assetDetails($slug)
    {
        $asset = Asset::with(['media', 'project', 'assetType'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('frontend.assetDetails', compact('asset'));
    }
    public function filter()
    {
        return view('frontend.filter');
    }
}
