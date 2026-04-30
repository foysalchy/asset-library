<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Models\Campaign;
use App\Models\Project;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{

    public function __construct(
        private ActivityLogService $activityLog
    ) {}

    public function index(Request $request)
    {
        $query = Campaign::query()->with('creator', 'project');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('slug', 'like', '%' . $request->search . '%')
                    ->orWhereHas('project', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        if ($request->filled('status'))     $query->where('status', $request->status);
        if ($request->filled('project_id')) $query->where('project_id', $request->project_id);

        $campaigns = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $projects = Project::where('status', 'active')->orderBy('name')->get();
        return view('campaigns.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCampaign($request);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = FileUploadHelper::uploadImage($request->file('thumbnail'));
        }

        if ($request->hasFile('file')) {
            $validated['file'] = FileUploadHelper::uploadFile($request->file('file'));
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['created_by']  = auth()->id();
        $validated['sort_order']  = $validated['sort_order'] ?? 0;

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $campaign = Campaign::create($validated);
        $this->activityLog->log('created', $campaign, "Created campaign: {$campaign->title}");


        return redirect()->route('campaigns.index')->with('success', 'Campaign created successfully.');
    }

    public function show(Campaign $campaign)
    {
        return view('campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        $projects = Project::where('status', 'active')->orderBy('name')->get();
        return view('campaigns.edit', compact('campaign', 'projects'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $this->validateCampaign($request, $campaign->id);

        // Thumbnail — local
        if ($request->hasFile('thumbnail')) {
            FileUploadHelper::deleteImage($campaign->thumbnail);
            $validated['thumbnail'] = FileUploadHelper::uploadImage($request->file('thumbnail'));
        } elseif ($request->boolean('remove_thumbnail')) {
            FileUploadHelper::deleteImage($campaign->thumbnail);
            $validated['thumbnail'] = null;
        }

        // File — Google Drive
        if ($request->hasFile('file')) {
            FileUploadHelper::deleteFile($campaign->file);
            $validated['file'] = FileUploadHelper::uploadFile($request->file('file'));
        } elseif ($request->boolean('remove_file')) {
            FileUploadHelper::deleteFile($campaign->file);
            $validated['file'] = null;
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order']  = $validated['sort_order'] ?? 0;

        $old = $campaign->only(['title', 'status', 'is_featured']);
        $campaign->update($validated);
        $new = $campaign->only(['title', 'status', 'is_featured']);
        $this->activityLog->log('updated', $campaign, "Updated campaign: {$campaign->title}", [
            'before' => $old,
            'after'  => $new,
        ]);

        return redirect()->route('campaigns.index')->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        FileUploadHelper::deleteImage($campaign->thumbnail);
        FileUploadHelper::deleteFile($campaign->file);

        $campaign->delete();
        $this->activityLog->log('deleted', $campaign, "Deleted campaign: {$campaign->title}");

        return redirect()->route('campaigns.index')->with('success', 'Campaign deleted successfully.');
    }

    private function validateCampaign(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['nullable', 'string', 'max:255', Rule::unique('campaigns', 'slug')->ignore($ignoreId)],
            'project_id'   => ['required', 'exists:projects,id'],
            'description'  => ['nullable', 'string'],
            'status'       => ['required', Rule::in(['draft', 'active', 'expired'])],
            'is_featured'  => ['boolean'],
            'thumbnail'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:20480'],
            'file'         => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,txt,csv', 'max:3145728'],
            'languages'    => ['required', 'array', 'min:1'],
            'languages.*'  => ['string', Rule::in(['en', 'bn'])],
            'sort_order'   => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
            'expired_at'   => ['nullable', 'date', 'after_or_equal:published_at'],
        ]);
    }
}
