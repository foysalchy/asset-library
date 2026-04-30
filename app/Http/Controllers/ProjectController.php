<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Helpers\FileUploadHelper;


class ProjectController extends Controller
{

    public function index(Request $request)
    {
        $query = Project::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $projects = $query->latest()->paginate(10)->withQueryString();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'concern' => 'required|string',
            'status' => 'required|in:active,inactive',
            'logo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = FileUploadHelper::uploadImage($request->file('logo'), 'projects/logos');
        }

        Project::create($validated);
        return redirect()->route('projects.index')->with('success', 'Project created.');
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'concern' => 'required|string',
            'status' => 'required|in:active,inactive',
            'logo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = FileUploadHelper::uploadImage($request->file('logo'), 'projects/logos');
        } elseif ($request->boolean('remove_logo')) {
            FileUploadHelper::deleteImage($project->logo);
            $validated['logo'] = null;
        }

        $project->update($validated);
        return redirect()->route('projects.index')->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $this->fileUpload->delete($project->logo);
        $project->delete();
        return back()->with('success', 'Project deleted.');
    }
}
