<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\AdminCore\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function index()
    {
        $downloads = Download::orderBy('sort_order')->latest()->paginate(10);

        return view('admin-core::downloads.index', compact('downloads'));
    }

    public function create()
    {
        return view('admin-core::downloads.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file_path' => 'required|file|max:51200', // 50 MB
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['file_path'] = $request->file('file_path')->store('downloads', 'public');
        $validated['is_public'] = $request->has('is_public');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Download::create($validated);

        return redirect()->route('admin.downloads.index')->with('success', 'Download created.');
    }

    public function edit(Download $download)
    {
        return view('admin-core::downloads.form', compact('download'));
    }

    public function update(Request $request, Download $download)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file_path' => 'nullable|file|max:51200',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('file_path')) {
            if ($download->file_path) {
                Storage::disk('public')->delete($download->file_path);
            }
            $validated['file_path'] = $request->file('file_path')->store('downloads', 'public');
        } else {
            unset($validated['file_path']);
        }

        $validated['is_public'] = $request->has('is_public');

        $download->update($validated);

        return redirect()->route('admin.downloads.index')->with('success', 'Download updated.');
    }

    public function destroy(Download $download)
    {
        if ($download->file_path) {
            Storage::disk('public')->delete($download->file_path);
        }
        $download->delete();

        return back()->with('success', 'Download deleted.');
    }
}
