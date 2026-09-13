<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\AdminCore\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index()
    {
        $members = TeamMember::orderBy('sort_order')->paginate(10);
        return view('admin-core::team_members.index', compact('members'));
    }

    public function create()
    {
        return view('admin-core::team_members.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'role'          => 'required|string|max:255',
            'bio'           => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'facebook_url'  => 'nullable|url|max:500',
            'twitter_url'   => 'nullable|url|max:500',
            'linkedin_url'  => 'nullable|url|max:500',
            'instagram_url' => 'nullable|url|max:500',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('team', 'public');
        }
        $validated['is_active'] = $request->has('is_active');

        TeamMember::create($validated);
        return redirect()->route('admin.team-members.index')->with('success', 'Team Member added.');
    }

    public function edit(TeamMember $teamMember)
    {
        return view('admin-core::team_members.form', compact('teamMember'));
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'role'          => 'required|string|max:255',
            'bio'           => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'facebook_url'  => 'nullable|url|max:500',
            'twitter_url'   => 'nullable|url|max:500',
            'linkedin_url'  => 'nullable|url|max:500',
            'instagram_url' => 'nullable|url|max:500',
        ]);

        if ($request->hasFile('image')) {
            if ($teamMember->image) {
                Storage::disk('public')->delete($teamMember->image);
            }
            $validated['image'] = $request->file('image')->store('team', 'public');
        } else {
            unset($validated['image']);
        }
        $validated['is_active'] = $request->has('is_active');

        $teamMember->update($validated);
        return redirect()->route('admin.team-members.index')->with('success', 'Team Member updated.');
    }

    public function destroy(TeamMember $teamMember)
    {
        if ($teamMember->image) {
            Storage::disk('public')->delete($teamMember->image);
        }
        $teamMember->delete();
        return back()->with('success', 'Team Member deleted.');
    }
}



