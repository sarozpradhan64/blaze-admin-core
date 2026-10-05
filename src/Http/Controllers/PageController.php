<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Blaze\AdminCore\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::with(['creator', 'updater'])
            ->search($request->get('search'), ['title', 'sub_title', 'excerpt', 'content'])
            ->filterStatus($request->get('status'))
            ->sort($request->get('sort_by', 'created_at'), $request->get('sort_dir', 'desc'));

        $pages = $query->paginate($request->get('per_page', 10))->withQueryString();

        return view('admin-core::pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin-core::pages.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'sub_title' => ['nullable', 'string', 'max:255'],
            'sub_text' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'status' => ['nullable'],
        ]);

        $hasFeaturedImage = $request->hasFile('featured_image');
        if ($hasFeaturedImage) {
            unset($validated['featured_image']);
        }

        $validated['slug'] = $this->uniqueSlug($validated['title'], 'pages');
        $validated['status'] = $request->has('status');
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $validated['type'] = 'page'; // Global scope logic should handle this but it's safe to enforce

        $page = Page::create($validated);

        if ($hasFeaturedImage) {
            $file = $request->file('featured_image');
            $filename = 'featured_image.'.$file->extension();
            $path = $file->storeAs($page->getMediaDirectory().'/'.$page->id, $filename, 'public');
            $page->updateQuietly(['featured_image' => $path]);
        }

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('admin-core::pages.form', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'sub_title' => ['nullable', 'string', 'max:255'],
            'sub_text' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'status' => ['nullable'],
        ]);

        $hasFeaturedImage = $request->hasFile('featured_image');
        if ($hasFeaturedImage) {
            unset($validated['featured_image']);
        }

        if ($page->title !== $validated['title']) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], 'pages', $page->id);
        }

        $validated['status'] = $request->has('status');
        $validated['updated_by'] = auth()->id();

        $page->update($validated);

        if ($hasFeaturedImage) {
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $file = $request->file('featured_image');
            $filename = 'featured_image_'.time().'.'.$file->extension();
            $path = $file->storeAs($page->getMediaDirectory().'/'.$page->id, $filename, 'public');
            $page->updateQuietly(['featured_image' => $path]);
        }

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }

    protected function uniqueSlug(string $title, string $table, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'page';
        $slug = $base;
        $counter = 2;

        while (Page::withoutGlobalScope('type')->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function updateSeo(Request $request, Page $page)
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

        $page->seo()->updateOrCreate(
            ['seoable_id' => $page->id, 'seoable_type' => get_class($page)],
            $seoData
        );

        return redirect()->back()->with('success', 'Page SEO updated successfully.');
    }
}
