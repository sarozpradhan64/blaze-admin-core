<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\Project;
use Blaze\AdminCore\Models\ProjectCategory;
use Blaze\AdminCore\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['category', 'tags'])
            ->search($request->get('search'), ['title', 'client_name', 'location'])
            ->filterStatus($request->get('status'))
            ->sort($request->get('sort_by'), $request->get('sort_dir'));

        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('project_category_id', $request->category_id);
        }

        if ($request->filled('tag') && $request->tag !== 'all') {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }

        $projects = $query->paginate($request->get('per_page', 10))->withQueryString();

        $categories = ProjectCategory::all();
        $tags = Tag::whereHas('projects')->pluck('name', 'name');

        return view('admin-core::projects.index', compact('projects', 'categories', 'tags'));
    }

    public function create()
    {
        $categories = ProjectCategory::all();

        return view('admin-core::projects.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_category_id' => 'nullable|exists:project_categories,id',
            'client_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'project_type' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'project_status' => 'required|in:upcoming,ongoing,completed',
            'short_description' => 'nullable|string',
            'description' => 'required|string',
            'website_url' => 'nullable|url|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'tags' => 'nullable|string|max:1000',
        ]);

        $tags = $validated['tags'] ?? null;
        unset($validated['tags']);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('projects', 'public');
        }

        $validated['slug'] = $this->uniqueSlug($validated['title'], 'projects');
        $validated['status'] = $request->has('status');
        $validated['is_featured'] = $request->has('is_featured');

        $project = Project::create($validated);
        $project->syncTagsFromString($tags);

        if ($request->input('action') === 'continue') {
            return redirect()->route('admin.projects.edit', $project)->with('success', 'Project created successfully.');
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $categories = ProjectCategory::all();

        return view('admin-core::projects.form', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_category_id' => 'nullable|exists:project_categories,id',
            'client_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'project_type' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'project_status' => 'required|in:upcoming,ongoing,completed',
            'short_description' => 'nullable|string',
            'description' => 'required|string',
            'website_url' => 'nullable|url|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'tags' => 'nullable|string|max:1000',
        ]);

        $tags = $validated['tags'] ?? null;
        unset($validated['tags']);

        if ($request->hasFile('featured_image')) {
            if ($project->featured_image) {
                Storage::disk('public')->delete($project->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('projects', 'public');
        } else {
            unset($validated['featured_image']);
        }

        if ($project->title !== $validated['title']) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], 'projects', $project->id);
        }
        $validated['status'] = $request->has('status');
        $validated['is_featured'] = $request->has('is_featured');

        $project->update($validated);
        $project->syncTagsFromString($tags);

        if ($request->input('action') === 'continue') {
            return redirect()->route('admin.projects.edit', $project)->with('success', 'Project updated successfully.');
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        if ($project->featured_image) {
            Storage::disk('public')->delete($project->featured_image);
        }
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }
}
