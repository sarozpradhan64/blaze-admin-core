<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\Job;
use Blaze\AdminCore\Models\JobCategory;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('category')
            ->search($request->get('search'), ['title', 'location', 'excerpt'])
            ->filterStatus($request->get('status'))
            ->sort($request->get('sort_by'), $request->get('sort_dir'));

        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('job_category_id', $request->category_id);
        }

        $jobs = $query->paginate($request->get('per_page', 10))->withQueryString();
        $categories = JobCategory::all();

        return view('admin-core::jobs.index', compact('jobs', 'categories'));
    }

    public function create()
    {
        $categories = JobCategory::all();

        return view('admin-core::jobs.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'job_category_id' => 'nullable|exists:job_categories,id',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'positions' => 'nullable|integer',
            'location' => 'nullable|string|max:255',
            'salary' => 'nullable|string|max:255',
            'deadline' => 'nullable|date',
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['title'], 'job_postings');
        $validated['status'] = $request->has('status');

        Job::create($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Job created successfully.');
    }

    public function edit(Job $job)
    {
        $categories = JobCategory::all();

        return view('admin-core::jobs.form', compact('job', 'categories'));
    }

    public function update(Request $request, Job $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'job_category_id' => 'nullable|exists:job_categories,id',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'positions' => 'nullable|integer',
            'location' => 'nullable|string|max:255',
            'salary' => 'nullable|string|max:255',
            'deadline' => 'nullable|date',
        ]);

        if ($job->title !== $validated['title']) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], 'job_postings', $job->id);
        }
        $validated['status'] = $request->has('status');

        $job->update($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }
}
