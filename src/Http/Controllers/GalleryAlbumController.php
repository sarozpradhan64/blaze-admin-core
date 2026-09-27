<?php

namespace Blaze\AdminCore\Http\Controllers;

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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('gallery/covers', 'public');
        }
        $validated['slug'] = Str::slug($validated['title']);
        $validated['status'] = $request->has('status');
        $validated['is_featured'] = $request->has('is_featured');

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
            'title' => 'required|string|max:255',
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
        $validated['is_featured'] = $request->has('is_featured');

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

    public function updateSeo(Request $request, GalleryAlbum $galleryAlbum)
    {
        $validated = $request->validate([
            'seo' => 'nullable|array',
            'seo.meta_title' => 'nullable|string|max:255',
            'seo.meta_description' => 'nullable|string',
            'seo.og_title' => 'nullable|string|max:255',
            'seo.og_description' => 'nullable|string',
            'seo.og_image' => 'nullable|string|max:1000',
            'seo_og_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $seoData = $validated['seo'] ?? [];

        if ($request->hasFile('seo_og_image_file')) {
            $seoData['og_image'] = $request->file('seo_og_image_file')->store('seo', 'public');
        }

        $galleryAlbum->seo()->updateOrCreate(
            ['seoable_id' => $galleryAlbum->id, 'seoable_type' => get_class($galleryAlbum)],
            $seoData
        );

        return redirect()->back()->with('success', 'Gallery Album SEO updated successfully.');
    }
}
