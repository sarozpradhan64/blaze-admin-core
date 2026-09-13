<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\AdminCore\Models\GalleryAlbum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryAlbumController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::withCount('items')->orderBy('sort_order')->latest()->paginate(10);
        return view('admin-core::gallery_albums.index', compact('albums'));
    }

    public function create()
    {
        return view('admin-core::gallery_albums.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('gallery/covers', 'public');
        }
        $validated['slug'] = Str::slug($validated['title']);
        $validated['status'] = $request->has('status');

        GalleryAlbum::create($validated);
        return redirect()->route('admin.gallery-albums.index')->with('success', 'Album created.');
    }

    public function edit(GalleryAlbum $galleryAlbum)
    {
        return view('admin-core::gallery_albums.form', ['album' => $galleryAlbum]);
    }

    public function update(Request $request, GalleryAlbum $galleryAlbum)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($galleryAlbum->cover_image) {
                Storage::disk('public')->delete($galleryAlbum->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('gallery/covers', 'public');
        } else {
            unset($validated['cover_image']);
        }
        if ($galleryAlbum->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        $validated['status'] = $request->has('status');

        $galleryAlbum->update($validated);
        return redirect()->route('admin.gallery-albums.index')->with('success', 'Album updated.');
    }

    public function destroy(GalleryAlbum $galleryAlbum)
    {
        if ($galleryAlbum->cover_image) {
            Storage::disk('public')->delete($galleryAlbum->cover_image);
        }
        $galleryAlbum->delete();
        return back()->with('success', 'Album deleted.');
    }
}



