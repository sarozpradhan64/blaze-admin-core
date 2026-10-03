<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = JobApplication::with('job')->latest();

        if ($request->has('job_id') && $request->job_id) {
            $query->where('job_id', $request->job_id);
        }

        $applications = $query->paginate(20);

        return view('admin-core::job_applications.index', compact('applications'));
    }

    public function show(JobApplication $jobApplication)
    {
        $jobApplication->load('job');

        return view('admin-core::job_applications.show', compact('jobApplication'));
    }

    public function updateStatus(Request $request, JobApplication $jobApplication)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,rejected,hired',
        ]);

        $jobApplication->update($validated);

        return redirect()->back()->with('success', 'Application status updated.');
    }

    public function destroy(JobApplication $jobApplication)
    {
        if ($jobApplication->cv) {
            Storage::disk('public')->delete($jobApplication->cv);
        }
        if ($jobApplication->cover_letter) {
            Storage::disk('public')->delete($jobApplication->cover_letter);
        }
        if ($jobApplication->license) {
            Storage::disk('public')->delete($jobApplication->license);
        }
        if ($jobApplication->training_experience_doc) {
            Storage::disk('public')->delete($jobApplication->training_experience_doc);
        }

        $jobApplication->delete();

        return redirect()->route('admin.job-applications.index')->with('success', 'Application deleted successfully.');
    }
}
