<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssetTypeController extends Controller
{
    public function __construct(
        private ActivityLogService $activityLog
    ) {}
    public function index()
    {
        // Get all asset types, paginated by 10
        $assetTypes = AssetType::latest()->paginate(10);

        return view('asset-types.index', compact('assetTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:asset_types,name'
            ],
        ]);

        $assetType = AssetType::create($validated);

        $this->activityLog->log('created', $assetType, "Created asset type: {$assetType->name}");

        return redirect()->route('asset-types.index')
            ->with('success', 'Asset Type created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssetType $assetType)
    {
        return view('asset-types.edit', compact('assetType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssetType $assetType)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('asset_types', 'name')->ignore($assetType->id)
            ],
        ]);
        
        $assetType->update($validated);

        $this->activityLog->log('updated', $assetType, "Updated asset type: {$assetType->name}");

        return redirect()->route('asset-types.index')
            ->with('success', 'Asset Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetType $assetType)
    {
        $assetType->delete();
        $this->activityLog->log('deleted', $assetType, "Deleted asset type: {$assetType->name}");

        return redirect()->route('asset-types.index')
            ->with('success', 'Asset Type deleted successfully.');
    }
}
