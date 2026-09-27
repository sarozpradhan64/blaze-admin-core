<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\LegalDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LegalDocumentController extends Controller
{
    public function index()
    {
        $legalDocuments = LegalDocument::latest()->paginate(20);
        return view('admin-core::legal_documents.index', compact('legalDocuments'));
    }

    public function create()
    {
        return view('admin-core::legal_documents.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,png,jpg,jpeg,webp|max:10240',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        
        // Ensure unique slug
        $count = LegalDocument::where('slug', 'like', $validated['slug'] . '%')->count();
        if ($count > 0) {
            $validated['slug'] = $validated['slug'] . '-' . ($count + 1);
        }

        $validated['status'] = $request->has('status');

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('legal_documents', 'public');
        }
        
        unset($validated['file']);

        LegalDocument::create($validated);

        return redirect()->route('admin.legal-documents.index')->with('success', 'Legal Document created successfully.');
    }

    public function edit(LegalDocument $legalDocument)
    {
        return view('admin-core::legal_documents.form', compact('legalDocument'));
    }

    public function update(Request $request, LegalDocument $legalDocument)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,png,jpg,jpeg,webp|max:10240',
        ]);

        if ($legalDocument->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
            $count = LegalDocument::where('slug', 'like', $validated['slug'] . '%')->where('id', '!=', $legalDocument->id)->count();
            if ($count > 0) {
                $validated['slug'] = $validated['slug'] . '-' . ($count + 1);
            }
        }

        $validated['status'] = $request->has('status');

        if ($request->hasFile('file')) {
            if ($legalDocument->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($legalDocument->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('legal_documents', 'public');
        }
        
        unset($validated['file']);

        $legalDocument->update($validated);

        return redirect()->route('admin.legal-documents.index')->with('success', 'Legal Document updated successfully.');
    }

    public function destroy(LegalDocument $legalDocument)
    {
        if ($legalDocument->file_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($legalDocument->file_path);
        }
        $legalDocument->delete();
        return redirect()->route('admin.legal-documents.index')->with('success', 'Legal Document deleted successfully.');
    }
}
