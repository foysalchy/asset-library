<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Models\Asset;
use App\Models\AssetMedia;
use App\Models\AssetType;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{
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

        $assets     = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate(10)->withQueryString();
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
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $asset = Asset::create($validated);

        $this->syncMedia($request, $asset);

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

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            FileUploadHelper::deleteFile($asset->file_path);
            $validated['file_path']          = FileUploadHelper::uploadFile($file);
            $validated['file_original_name'] = $file->getClientOriginalName();
            $validated['file_mime_type']     = $file->getMimeType();
            $validated['file_size']          = $file->getSize();
        } elseif ($request->boolean('remove_file')) {
            FileUploadHelper::deleteFile($asset->file_path);
            $validated['file_path']          = null;
            $validated['file_original_name'] = null;
            $validated['file_mime_type']     = null;
            $validated['file_size']          = null;
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $asset->update($validated);

        $this->syncMedia($request, $asset);

        return redirect()->route('assets.show', $asset)->with('success', 'Asset updated successfully.');
    }

    public function destroy(Asset $asset)
    {
        foreach ($asset->media as $media) {
            FileUploadHelper::deleteFile($media->file_path);
        }

        FileUploadHelper::deleteFile($asset->file_path);
        $asset->delete();

        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }

    // ── Media delete (AJAX) ──────────────────────────────────────────────────

    public function destroyMedia(AssetMedia $media)
    {
        FileUploadHelper::deleteFile($media->file_path);
        $media->delete();

        return response()->json(['success' => true]);
    }

    // ── Private Helpers ──────────────────────────────────────────────────────

    private function syncMedia(Request $request, Asset $asset): void
    {
        // Delete marked existing media
        if ($request->filled('delete_media')) {
            foreach ((array) $request->delete_media as $mediaId) {
                $media = AssetMedia::find($mediaId);
                if ($media && $media->asset_id === $asset->id) {
                    $mime = $media->mime_type ?? '';
                    str_starts_with($mime, 'video') || str_starts_with($mime, 'image')
                        ? FileUploadHelper::deleteImage($media->file_path)
                        : FileUploadHelper::deleteFile($media->file_path);
                    $media->delete();
                }
            }
        }

        // Upload new media
        if ($request->hasFile('media')) {
            $order = $asset->media()->max('sort_order') ?? 0;
            foreach ($request->file('media') as $file) {
                $mime      = $file->getMimeType();
                $isLocal   = str_starts_with($mime, 'image') || str_starts_with($mime, 'video');
                $mediaType = str_starts_with($mime, 'video') ? 'video' : 'image';

         
                $path = $isLocal
                    ? FileUploadHelper::uploadImage($file, 'assets/media')
                    : FileUploadHelper::uploadFile($file, 'assets/media');

                $asset->media()->create([
                    'file_path'          => $path,
                    'file_original_name' => $file->getClientOriginalName(),
                    'media_type'         => $mediaType,
                    'mime_type'          => $mime,
                    'file_size'          => $file->getSize(),
                    'sort_order'         => ++$order,
                ]);
            }
        }
    }

    private function validateAsset(Request $request, ?string $ignoreId = null): array
    {
        return $request->validate([
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
            'uploaded_at'         => ['nullable', 'date'],
            'file'                => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,txt,csv', 'max:102400'],
            'media'               => ['nullable', 'array'],
            'media.*'             => ['file', 'mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,webm', 'max:204800'],
            'delete_media'        => ['nullable', 'array'],
            'delete_media.*'      => ['string'],
        ]);
    }
}
