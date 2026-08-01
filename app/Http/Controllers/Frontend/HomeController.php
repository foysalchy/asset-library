<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\Campaign;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $latestAssets = Asset::with(['media' => fn($q) => $q->where('media_type', 'image')->orderBy('sort_order')->limit(1)])
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->limit(8)
            ->select('id', 'title', 'slug', 'status', 'sort_order')
            ->get();

        $concerns = Project::CONCERNS;

        $projects = Project::where('status', 'active')
          
            ->orderBy('name')
            ->get();

        $assetTypes = AssetType::select('id', 'name')
            ->orderBy('name')
            ->get();



        return view('frontend.index', compact(
            'latestAssets',
            'concerns',
            'projects',
            'assetTypes',
            'user'
        ));
    }
    public function campaignDetails($slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();

        return view('frontend.campaignDetails', compact('campaign'));
    }
    public function assetDetails($slug)
    {
        $asset = Asset::with(['media', 'project', 'assetType'])
            ->where('status', 'active')
            ->where('slug', $slug)
            ->firstOrFail();

        //          dd($asset->media->map(fn($m) => [
        //     'id'         => $m->id,
        //     'file_path'  => $m->file_path,
        //     'media_type' => $m->media_type,
        //     'url'        => $m->url,
        //     'stream_url' => $m->stream_url,
        //     'embed_url'  => $m->embed_url,
        // ]));

        return view('frontend.assetDetails', compact('asset'));
    }

    public function filter(Request $request)
    {
        $assetQuery = Asset::where('status', 'active');
        $campaignQuery = Campaign::query();

        if ($request->filled('share_ids') && $request->filled('types')) {
            $ids = explode(',', $request->share_ids);
            $types = explode(',', $request->types);

            $assetIds = [];
            $campaignIds = [];

            foreach ($ids as $index => $id) {
                if (isset($types[$index])) {
                    if ($types[$index] === 'asset') {
                        $assetIds[] = $id;
                    } elseif ($types[$index] === 'campaign') {
                        $campaignIds[] = $id;
                    }
                }
            }

            $assetQuery->whereIn('id', $assetIds);
            $campaignQuery->whereIn('id', $campaignIds);

            if (empty($assetIds)) {
                $assetQuery->whereRaw('1 = 0');
            }
            if (empty($campaignIds)) {
                $campaignQuery->whereRaw('1 = 0');
            }
        } else {
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $assetQuery->where('title', 'like', "%{$searchTerm}%");
                $campaignQuery->where('title', 'like', "%{$searchTerm}%");
            }

            if ($request->filled('concern')) {
                $assetQuery->whereHas('project', fn($q) => $q->where('concern', $request->concern));
                $campaignQuery->whereHas('project', fn($q) => $q->where('concern', $request->concern));
            }

            if ($request->filled('type')) {
                $assetQuery->where('asset_type_id', $request->type);
                $campaignQuery->whereRaw('1 = 0');
            }

            if ($request->filled('project')) {
                $assetQuery->where('project_id', $request->project);
                $campaignQuery->where('project_id', $request->project);
            }

            if ($request->filled('lang')) {
                $campaignQuery->whereJsonContains('languages', $request->lang);
                $assetQuery->whereRaw('1 = 0');
            }
        }

        $sort = $request->get('sort', 'latest');

        if ($sort === 'az') {
            $assetQuery->orderBy('title', 'asc');
            $campaignQuery->orderBy('title', 'asc');
        } elseif ($sort === 'oldest') {
            $assetQuery->orderBy('created_at', 'asc');
            $campaignQuery->orderBy('created_at', 'asc');
        } else {
            $assetQuery->orderBy('created_at', 'desc');
            $campaignQuery->orderBy('created_at', 'desc');
        }

        $perPage = (int) $request->get('per_page', 6);

        $assets = $assetQuery->with(['project', 'media'])->paginate($perPage, ['*'], 'assets_page')->withQueryString();
        $campaigns = $campaignQuery->with('project')->paginate($perPage, ['*'], 'campaigns_page')->withQueryString();

        // Data for Sidebar
        $projects = Project::all();
        $assetTypes = AssetType::all();
        $allLanguages = Campaign::all()->pluck('languages')->flatten()->unique()->sort()->values();

        return view('frontend.filter', compact('assets', 'campaigns', 'projects', 'assetTypes', 'allLanguages'));
    }
    public function brand()
    {
        $projects = Project::orderBy('name', 'asc')
            ->get()
            ->groupBy('concern');
        return view('frontend.brandAsset', compact('projects'));
    }
}
