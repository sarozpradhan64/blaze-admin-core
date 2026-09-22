<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\Project;
use Blaze\AdminCore\Models\ProjectStatistic;
use Illuminate\Http\Request;

class ProjectStatisticController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectStatistic::with('project')->orderBy('project_id')->orderBy('sort_order');
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        $statistics = $query->paginate(15);

        return view('admin-core::project_statistics.index', compact('statistics'));
    }

    public function create(Request $request)
    {
        $projects = Project::all();
        $selectedProject = $request->get('project_id');

        return view('admin-core::project_statistics.form', compact('projects', 'selectedProject'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'label' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        ProjectStatistic::create($validated);

        return redirect()->route('admin.project-statistics.index', ['project_id' => $validated['project_id']])->with('success', 'Statistic added successfully.');
    }

    public function edit(ProjectStatistic $projectStatistic)
    {
        $projects = Project::all();

        return view('admin-core::project_statistics.form', ['statistic' => $projectStatistic, 'projects' => $projects, 'selectedProject' => $projectStatistic->project_id]);
    }

    public function update(Request $request, ProjectStatistic $projectStatistic)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'label' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $projectStatistic->update($validated);

        return redirect()->route('admin.project-statistics.index', ['project_id' => $validated['project_id']])->with('success', 'Statistic updated successfully.');
    }

    public function destroy(ProjectStatistic $projectStatistic)
    {
        $projectStatistic->delete();

        return back()->with('success', 'Statistic deleted successfully.');
    }
}
