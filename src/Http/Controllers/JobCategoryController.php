<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\JobCategory;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    public function index()
    {
        $categories = JobCategory::orderBy('sort_order')->latest()->paginate(10);

        return view('admin-core::job_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin-core::job_categories.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['title'], 'job_categories');
        $validated['status'] = $request->has('status');

        JobCategory::create($validated);

        return redirect()->route('admin.job-categories.index')->with('success', 'Job Category created successfully.');
    }

    public function edit(JobCategory $jobCategory)
    {
        return view('admin-core::job_categories.form', compact('jobCategory'));
    }

    public function update(Request $request, JobCategory $jobCategory)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        if ($jobCategory->title !== $validated['title']) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], 'job_categories', $jobCategory->id);
        }
        $validated['status'] = $request->has('status');

        $jobCategory->update($validated);

        return redirect()->route('admin.job-categories.index')->with('success', 'Job Category updated successfully.');
    }

    public function destroy(JobCategory $jobCategory)
    {
        $jobCategory->delete();

        return redirect()->route('admin.job-categories.index')->with('success', 'Job Category deleted successfully.');
    }
}
