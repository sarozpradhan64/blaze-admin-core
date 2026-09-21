<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Blaze\AdminCore\Models\Enquiry;
use Blaze\AdminCore\Models\Project;
use Blaze\AdminCore\Models\Service;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Enquiry::with(['service', 'project', 'assignee'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('company', 'like', '%'.$request->search.'%');
            });
        }

        $enquiries = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Enquiry::count(),
            'new' => Enquiry::where('status', 'new')->count(),
            'in_progress' => Enquiry::where('status', 'in_progress')->count(),
            'urgent' => Enquiry::where('priority', 'urgent')->count(),
        ];

        return view('admin-core::enquiries.index', compact('enquiries', 'stats'));
    }

    public function show(Enquiry $enquiry)
    {
        $enquiry->load(['service', 'project', 'assignee']);
        $users = User::all();
        $services = Service::all();
        $projects = Project::all();

        return view('admin-core::enquiries.show', compact('enquiry', 'users', 'services', 'projects'));
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,contacted,converted,closed,spam',
            'priority' => 'required|in:low,normal,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'admin_notes' => 'nullable|string',
        ]);

        if ($enquiry->status !== 'replied' && $validated['status'] === 'contacted') {
            $validated['replied_at'] = now();
        }

        $enquiry->update($validated);

        return redirect()->route('admin.enquiries.show', $enquiry)->with('success', 'Enquiry updated successfully.');
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('success', 'Enquiry deleted.');
    }
}
