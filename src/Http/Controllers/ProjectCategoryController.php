<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectCategoryController extends Controller
{
    public function index()
    {
        $categories = ProjectCategory::withCount('projects')->orderBy('sort_order')->latest()->paginate(10);
        return view('admin-core::project_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin-core::project_categories.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->has('status');

        ProjectCategory::create($validated);

        return redirect()->route('admin.project-categories.index')->with('success', 'Project Category created successfully.');
    }

    public function edit(ProjectCategory $projectCategory)
    {
        return view('admin-core::project_categories.form', ['category' => $projectCategory]);
    }

    public function update(Request $request, ProjectCategory $projectCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($projectCategory->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        $validated['status'] = $request->has('status');

        $projectCategory->update($validated);

        return redirect()->route('admin.project-categories.index')->with('success', 'Project Category updated successfully.');
    }

    public function destroy(ProjectCategory $projectCategory)
    {
        if ($projectCategory->projects()->count() > 0) {
            return redirect()->route('admin.project-categories.index')->with('error', 'Cannot delete category with attached projects.');
        }

        $projectCategory->delete();
        return redirect()->route('admin.project-categories.index')->with('success', 'Project Category deleted successfully.');
    }
}


