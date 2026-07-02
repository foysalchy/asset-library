<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Models\Asset;
use App\Models\AssetMedia;
use App\Models\AssetType;
use App\Models\Project;
use App\Services\ActivityLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{
    public function __construct(
        private ActivityLogService $activityLog
    ) {}
    public function index(Request $request)
    {
        $query = Asset::query()->with('project', 'assetType', 'creator');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('asset_id_code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('project_id'))    $query->where('project_id', $request->project_id);
        if ($request->filled('asset_type_id')) $query->where('asset_type_id', $request->asset_type_id);

        $assets     = $query->orderBy('sort_order', 'asc')->paginate(10)->withQueryString();
        $projects   = Project::orderBy('name')->get();
        $assetTypes = AssetType::orderBy('name')->get();

        return view('assets.index', compact('assets', 'projects', 'assetTypes'));
    }

    public function create(Request $request)
    {
        $projects        = Project::orderBy('name')->get();
        $assetTypes      = AssetType::orderBy('name')->get();
        $selectedProject = $request->filled('project_id') ? $request->project_id : null;

        return view('assets.create', compact('projects', 'assetTypes', 'selectedProject'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateAsset($request);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $validated['file_path']          = FileUploadHelper::uploadFile($file);
            $validated['file_original_name'] = $file->getClientOriginalName();
            $validated['file_mime_type']     = $file->getMimeType();
            $validated['file_size']          = $file->getSize();
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['created_by'] = auth()->id();
        $minSortOrder = Asset::min('sort_order');
        $validated['sort_order'] = ($minSortOrder !== null) ? ($minSortOrder - 1) : 1;
        if ($request->filled('drive_file_id')) {
            $validated['file_path'] = 'drive:' . $request->drive_file_id;
        }
        $asset = Asset::create($validated);

        $this->syncMedia($request, $asset);
        $this->activityLog->log('created', $asset, "Created asset: {$asset->title}");

        NotificationService::notifyAll('asset', $asset->id, $asset->title, $asset->slug);
        return redirect()->route('assets.show', $asset)->with('success', 'Asset created successfully.');
    }


    public function show(Asset $asset)
    {
        $asset->load('project', 'assetType', 'media', 'creator');
        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $projects   = Project::orderBy('name')->get();
        $assetTypes = AssetType::orderBy('name')->get();
        $asset->load('media');

        return view('assets.edit', compact('asset', 'projects', 'assetTypes'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $this->validateAsset($request, $asset->id);

        // File — Google Drive (Resumable Upload)
        if ($request->filled('drive_file_id')) {
            // নতুন file আলাদা হলে পুরনোটা delete করো
            if ($asset->file_path && $asset->file_path !== 'drive:' . $request->drive_file_id) {
                FileUploadHelper::deleteFile($asset->file_path);
            }
            $validated['file_path']          = 'drive:' . $request->drive_file_id;
            $validated['file_original_name'] = $request->drive_file_name;
            $validated['file_mime_type']     = $request->drive_file_mime;
            $validated['file_size']          = $request->drive_file_size;
        } elseif ($request->boolean('remove_file')) {
            FileUploadHelper::deleteFile($asset->file_path);
            $validated['file_path']          = null;
            $validated['file_original_name'] = null;
            $validated['file_mime_type']     = null;
            $validated['file_size']          = null;
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $old = $asset->only(['title', 'asset_id_code', 'project_id', 'asset_type_id']);
        $asset->update($validated);
        $this->syncMedia($request, $asset);
        $new = $asset->only(['title', 'asset_id_code', 'project_id', 'asset_type_id']);

        $this->activityLog->log('updated', $asset, "Updated asset: {$asset->title}", [
            'before' => $old,
            'after'  => $new,
        ]);

        return redirect()->route('assets.show', $asset)->with('success', 'Asset updated successfully.');
    }
    public function editContent(Asset $asset)
    {
        $asset->load('media', 'project', 'assetType');
        return view('frontend.assets.edit-content', compact('asset'));
    }
    public function destroy(Asset $asset)
    {
        foreach ($asset->media as $media) {
            if (str_starts_with($media->file_path ?? '', 'drive:')) {
                FileUploadHelper::deleteFile($media->file_path);
            } else {
                FileUploadHelper::deleteImage($media->file_path);
            }

            if (!empty($media->file_path_compressed)) {
                FileUploadHelper::deleteFile($media->file_path_compressed);
            }
        }

        // Asset file delete — Drive
        FileUploadHelper::deleteFile($asset->file_path);

        $asset->delete();

        $this->activityLog->log('deleted', $asset, "Deleted asset: {$asset->title}");

        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }

    // ── Media delete (AJAX) ──────────────────────────────────────────────────

    public function destroyMedia(AssetMedia $media)
    {
        // file_path — Drive or local
        if (str_starts_with($media->file_path ?? '', 'drive:')) {
            FileUploadHelper::deleteFile($media->file_path);
        } else {
            FileUploadHelper::deleteImage($media->file_path);
        }

        // Compressed version
        if (!empty($media->file_path_compressed)) {
            FileUploadHelper::deleteFile($media->file_path_compressed);
        }

        $media->delete();

        return response()->json(['success' => true]);
    }

    public function destroyAsssetMedia(AssetMedia $media)
    {
        // file_path — Drive or local
        if (str_starts_with($media->file_path ?? '', 'drive:')) {
            FileUploadHelper::deleteFile($media->file_path);
        } else {
            FileUploadHelper::deleteImage($media->file_path);
        }

        // Compressed version
        if (!empty($media->file_path_compressed)) {
            FileUploadHelper::deleteFile($media->file_path_compressed);
        }

        $media->delete();

        return back();
    }
    public function uploadImageImmediate(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:20480'],
        ]);

        try {
            $result = FileUploadHelper::uploadImageWithCompressionToDrive($request->file('image'));

            return response()->json([
                'success'            => true,
                'drive_id'           => $result['original_id'],
                'drive_compressed_id' => $result['compressed_id'],
                'original_name'      => $result['original_name'],
                'mime_type'          => $result['mime_type'],
                'file_size'          => $result['file_size'],
            ]);
        } catch (\Exception $e) {
            Log::error('Image upload error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Upload failed.'], 500);
        }
    }
    public function deleteTempImage(Request $request)
    {
        $request->validate([
            'drive_id'      => ['required', 'string'],
            'compressed_id' => ['nullable', 'string'],
        ]);

        FileUploadHelper::deleteFile('drive:' . $request->drive_id);

        if ($request->filled('compressed_id')) {
            FileUploadHelper::deleteFile('drive:' . $request->compressed_id);
        }

        return response()->json(['success' => true]);
    }
    // ── Private Helpers ──────────────────────────────────────────────────────

    private function syncMedia(Request $request, Asset $asset): void
    {
        if ($request->filled('delete_media')) {
            foreach ((array) $request->delete_media as $mediaId) {
                $media = AssetMedia::find($mediaId);
                if ($media && (int) $media->asset_id === (int) $asset->id) {
                    if (str_starts_with($media->file_path ?? '', 'drive:')) {
                        FileUploadHelper::deleteFile($media->file_path);
                    } else {
                        FileUploadHelper::deleteImage($media->file_path);
                    }
                    if (!empty($media->file_path_compressed)) {
                        FileUploadHelper::deleteFile($media->file_path_compressed);
                    }
                    $media->delete();
                }
            }
        }

        if ($request->filled('drive_images')) {
            $order = $asset->media()->max('sort_order') ?? 0;
            foreach ((array) $request->drive_images as $jsonData) {
                $data = json_decode($jsonData, true);
                if (!$data || empty($data['drive_id'])) continue;

                $asset->media()->create([
                    'file_path'            => 'drive:' . $data['drive_id'],
                    'file_path_compressed' => !empty($data['compressed_id']) ? 'drive:' . $data['compressed_id'] : null,
                    'file_original_name'   => $data['original_name'] ?? 'image',
                    'media_type'           => 'image',
                    'mime_type'            => $data['mime_type'] ?? 'image/jpeg',
                    'file_size'            => $data['file_size'] ?? 0,
                    'sort_order'           => ++$order,
                ]);
            }
        }
        // Video — unchanged
        if ($request->filled('drive_video_ids')) {
            $order = $asset->media()->max('sort_order') ?? 0;
            foreach ((array) $request->drive_video_ids as $fileId) {
                $asset->media()->create([
                    'file_path'          => 'drive:' . $fileId,
                    'file_original_name' => 'video_' . $fileId,
                    'media_type'         => 'video',
                    'mime_type'          => 'video/mp4',
                    'file_size'          => 0,
                    'sort_order'         => ++$order,
                ]);
            }
        }
    }

    private function validateAsset(Request $request, ?string $ignoreId = null): array
    {
        $blockedExtensions = [
            'exe',
            'bat',
            'cmd',
            'com',
            'scr',
            'msi',
            'ps1',
            'vbs',
            'js',
            'jar',
            'php',
            'php3',
            'php4',
            'php5',
            'phtml',
            'asp',
            'aspx',
            'jsp',
            'cgi',
            'pl',
            'py',
            'rb',
            'sh',
            'bash',
            'dll',
            'so',
            'bin',
            'htaccess',
            'htpasswd',
        ];

        return $request->validate([
            'drive_file_id'       => ['nullable', 'string'],
            'project_id'          => ['required', 'exists:projects,id'],
            'asset_type_id'       => ['required', 'exists:asset_types,id'],
            'title'               => ['required', 'string', 'max:255'],
            'slug'                => ['nullable', 'string', 'max:255', Rule::unique('assets', 'slug')->ignore($ignoreId)],
            'asset_id_code'       => ['nullable', 'string', 'max:100'],
            'description'         => ['nullable', 'string'],
            'available_formats'   => ['nullable', 'array'],
            'available_formats.*' => ['string', 'max:20'],
            'dimensions'          => ['nullable', 'array'],
            'dimensions.*'        => ['string', 'max:50'],
            'sort_order'          => ['nullable', 'integer', 'min:0'],

            'status'         =>      ['required'],
            'uploaded_at'         => ['nullable', 'date'],
            'file'                => [
                'nullable',
                'file',
                function ($attribute, $value, $fail) use ($blockedExtensions) {
                    if (in_array(strtolower($value->getClientOriginalExtension()), $blockedExtensions)) {
                        $fail("This file type is not allowed.");
                    }
                },
            ],
            'media'               => ['nullable', 'array'],
            'media.*'             => [
                'file',
                function ($attribute, $value, $fail) use ($blockedExtensions) {
                    if (in_array(strtolower($value->getClientOriginalExtension()), $blockedExtensions)) {
                        $fail("This file type is not allowed.");
                    }
                },
            ],
            'drive_video_ids'        => ['nullable', 'array'],
            'delete_media'        => ['nullable', 'array'],
            'delete_media.*'      => ['string'],
        ]);
    }
    public function sort(Request $request)
    {
        try {
            $validated = $request->validate([
                'assets' => 'required|array',
                'assets.*.id' => 'required|integer|exists:assets,id',
                'assets.*.sort_order' => 'required|integer|min:0',
            ]);

            foreach ($validated['assets'] as $assetData) {
                Asset::where('id', $assetData['id'])
                    ->update(['sort_order' => $assetData['sort_order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Assets sorted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating sort order: ' . $e->getMessage()
            ], 422);
        }
    }


}
