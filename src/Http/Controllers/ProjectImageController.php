<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectImageController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectImage::with('project')->orderBy('project_id')->orderBy('sort_order');
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        $images = $query->paginate(15);
        return view('admin-core::project_images.index', compact('images'));
    }

    public function create(Request $request)
    {
        $projects = Project::all();
        $selectedProject = $request->get('project_id');
        return view('admin-core::project_images.form', compact('projects', 'selectedProject'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'image'      => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'alt_text'   => 'nullable|string|max:255',
            'caption'    => 'nullable|string|max:500',
        ]);

        $validated['image'] = $request->file('image')->store('projects/images', 'public');
        $validated['is_featured'] = $request->has('is_featured');

        ProjectImage::create($validated);
        return redirect()->route('admin.project-images.index', ['project_id' => $validated['project_id']])->with('success', 'Image added successfully.');
    }

    public function edit(ProjectImage $projectImage)
    {
        $projects = Project::all();
        return view('admin-core::project_images.form', ['image' => $projectImage, 'projects' => $projects, 'selectedProject' => $projectImage->project_id]);
    }

    public function update(Request $request, ProjectImage $projectImage)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'alt_text'   => 'nullable|string|max:255',
            'caption'    => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('image')) {
            if ($projectImage->image) {
                Storage::disk('public')->delete($projectImage->image);
            }
            $validated['image'] = $request->file('image')->store('projects/images', 'public');
        } else {
            unset($validated['image']);
        }
        $validated['is_featured'] = $request->has('is_featured');

        $projectImage->update($validated);
        return redirect()->route('admin.project-images.index', ['project_id' => $validated['project_id']])->with('success', 'Image updated successfully.');
    }

    public function destroy(ProjectImage $projectImage)
    {
        if ($projectImage->image) {
            Storage::disk('public')->delete($projectImage->image);
        }
        $projectImage->delete();
        return back()->with('success', 'Image deleted successfully.');
    }
}


