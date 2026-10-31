<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Blaze\AdminCore\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Enquiry::select(['id', 'name', 'email', 'subject', 'status', 'priority', 'created_at'])->latest();

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

        $statsRow = Enquiry::toBase()
            ->selectRaw("
                count(*) as total,
                count(case when status = 'new' then 1 end) as new_count,
                count(case when status = 'in_progress' then 1 end) as in_progress,
                count(case when priority = 'urgent' then 1 end) as urgent
            ")
            ->first();

        $stats = [
            'total' => (int) ($statsRow->total ?? 0),
            'new' => (int) ($statsRow->new_count ?? 0),
            'in_progress' => (int) ($statsRow->in_progress ?? 0),
            'urgent' => (int) ($statsRow->urgent ?? 0),
        ];

        view()->share('newEnquiries', $stats['new']);

        return view('admin-core::enquiries.index', compact('enquiries', 'stats'));
    }

    public function show(Enquiry $enquiry)
    {
        $enquiry->load(['service:id,title', 'project:id,title', 'assignee:id,name']);
        $users = User::orderBy('name')->get(['id', 'name']);

        return view('admin-core::enquiries.show', compact('enquiry', 'users'));
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
