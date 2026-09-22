<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\Project;
use Blaze\AdminCore\Models\ProjectVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectVideoController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectVideo::with('project')->orderBy('project_id')->orderBy('sort_order');
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        $videos = $query->paginate(15);

        return view('admin-core::project_videos.index', compact('videos'));
    }

    public function create(Request $request)
    {
        $projects = Project::all();
        $selectedProject = $request->get('project_id');

        return view('admin-core::project_videos.form', compact('projects', 'selectedProject'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'nullable|string|max:255',
            'video_url' => 'required|string|max:500',
            'video_type' => 'required|in:youtube,vimeo,uploaded',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        ProjectVideo::create($validated);

        return redirect()->route('admin.project-videos.index', ['project_id' => $validated['project_id']])->with('success', 'Video added successfully.');
    }

    public function edit(ProjectVideo $projectVideo)
    {
        $projects = Project::all();

        return view('admin-core::project_videos.form', ['video' => $projectVideo, 'projects' => $projects, 'selectedProject' => $projectVideo->project_id]);
    }

    public function update(Request $request, ProjectVideo $projectVideo)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'nullable|string|max:255',
            'video_url' => 'required|string|max:500',
            'video_type' => 'required|in:youtube,vimeo,uploaded',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($projectVideo->thumbnail) {
                Storage::disk('public')->delete($projectVideo->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        } else {
            unset($validated['thumbnail']);
        }

        $projectVideo->update($validated);

        return redirect()->route('admin.project-videos.index', ['project_id' => $validated['project_id']])->with('success', 'Video updated successfully.');
    }

    public function destroy(ProjectVideo $projectVideo)
    {
        if ($projectVideo->thumbnail) {
            Storage::disk('public')->delete($projectVideo->thumbnail);
        }
        $projectVideo->delete();

        return back()->with('success', 'Video deleted successfully.');
    }
}
