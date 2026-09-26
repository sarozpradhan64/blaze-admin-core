<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\GalleryAlbum;
use Blaze\AdminCore\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryItemController extends Controller
{
    public function index(Request $request)
    {
        $query = GalleryItem::with('album')->orderBy('gallery_album_id')->orderBy('sort_order');
        if ($request->has('album_id')) {
            $query->where('gallery_album_id', $request->album_id);
        }
        $items = $query->paginate(15);

        return view('admin-core::gallery_items.index', compact('items'));
    }

    public function create(Request $request)
    {
        $albums = GalleryAlbum::all();
        $selectedAlbum = $request->get('album_id');

        return view('admin-core::gallery_items.form', compact('albums', 'selectedAlbum'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gallery_album_id' => 'required|exists:gallery_albums,id',
            'title' => 'nullable|string|max:255',
            'file_path' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $validated['file_path'] = $request->file('file_path')->store('gallery', 'public');
        $validated['type'] = 'image';

        GalleryItem::create($validated);

        return redirect()->route('admin.gallery-items.index', ['album_id' => $validated['gallery_album_id']])->with('success', 'Image added.');
    }

    public function edit(GalleryItem $galleryItem)
    {
        $albums = GalleryAlbum::all();

        return view('admin-core::gallery_items.form', ['item' => $galleryItem, 'albums' => $albums, 'selectedAlbum' => $galleryItem->gallery_album_id]);
    }

    public function update(Request $request, GalleryItem $galleryItem)
    {
        $validated = $request->validate([
            'gallery_album_id' => 'required|exists:gallery_albums,id',
            'title' => 'nullable|string|max:255',
            'file_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('file_path')) {
            if ($galleryItem->file_path) {
                Storage::disk('public')->delete($galleryItem->file_path);
            }
            $validated['file_path'] = $request->file('file_path')->store('gallery', 'public');
            $validated['type'] = 'image';
        } else {
            unset($validated['file_path']);
        }

        $galleryItem->update($validated);

        return redirect()->route('admin.gallery-items.index', ['album_id' => $validated['gallery_album_id']])->with('success', 'Image updated.');
    }

    public function destroy(GalleryItem $galleryItem)
    {
        if ($galleryItem->file_path) {
            Storage::disk('public')->delete($galleryItem->file_path);
        }
        $galleryItem->delete();

        return back()->with('success', 'Image deleted.');
    }
}
