<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\Affiliation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AffiliationController extends Controller
{
    public function index(Request $request)
    {
        $affiliations = Affiliation::query()
            ->search($request->get('search'), ['name'])
            ->filterStatus($request->get('status'))
            ->sort($request->get('sort_by'), $request->get('sort_dir'))
            ->paginate($request->get('per_page', 20))
            ->withQueryString();

        return view('admin-core::affiliations.index', compact('affiliations'));
    }

    public function create()
    {
        return view('admin-core::affiliations.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'link' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('affiliation', 'public');
        }

        $validated['status'] = $request->boolean('status');

        Affiliation::create($validated);

        return redirect()->route('admin.affiliations.index')->with('success', 'Affiliation created successfully.');
    }

    public function edit(Affiliation $affiliation)
    {
        return view('admin-core::affiliations.form', compact('affiliation'));
    }

    public function update(Request $request, Affiliation $affiliation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'link' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('logo')) {
            if ($affiliation->logo && Storage::disk('public')->exists($affiliation->logo)) {
                Storage::disk('public')->delete($affiliation->logo);
            }
            $validated['logo'] = $request->file('logo')->store('affiliation', 'public');
        }

        $validated['status'] = $request->boolean('status');

        $affiliation->update($validated);

        return redirect()->route('admin.affiliations.index')->with('success', 'Affiliation updated successfully.');
    }

    public function destroy(Affiliation $affiliation)
    {
        if ($affiliation->logo && Storage::disk('public')->exists($affiliation->logo)) {
            Storage::disk('public')->delete($affiliation->logo);
        }

        $affiliation->delete();

        return redirect()->route('admin.affiliations.index')->with('success', 'Affiliation deleted successfully.');
    }

    public function toggle(Affiliation $affiliation)
    {
        $affiliation->update([
            'status' => ! $affiliation->status,
        ]);

        $statusText = $affiliation->status ? 'shown' : 'hidden';

        return back()->with('success', "Affiliation is now {$statusText}.");
    }
}
