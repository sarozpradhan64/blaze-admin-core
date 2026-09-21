<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Blaze\AdminCore\Models\Blog;
use Blaze\AdminCore\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with(['category', 'author'])->orderBy('created_at', 'desc')->paginate(10);

        return view('admin-core::blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = BlogCategory::orderBy('sort_order')->get();
        $authors = User::orderBy('name')->get();

        return view('admin-core::blogs.form', compact('categories', 'authors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'author_id' => ['nullable', 'exists:users,id'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'status' => ['nullable'],
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        $validated['slug'] = $this->uniqueSlug($validated['title'], 'pages');
        $validated['status'] = $request->has('status');
        $validated['author_id'] = $validated['author_id'] ?? auth()->id();
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully.');
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCategory::orderBy('sort_order')->get();
        $authors = User::orderBy('name')->get();

        return view('admin-core::blogs.form', compact('blog', 'categories', 'authors'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'author_id' => ['nullable', 'exists:users,id'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'status' => ['nullable'],
        ]);

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        } else {
            unset($validated['featured_image']);
        }

        if ($blog->title !== $validated['title']) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], 'pages', $blog->id);
        }

        $validated['status'] = $request->has('status');
        $validated['author_id'] = $validated['author_id'] ?? auth()->id();
        $validated['updated_by'] = auth()->id();

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted successfully.');
    }

    protected function uniqueSlug(string $title, string $table, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'blog-post';
        $slug = $base;
        $counter = 2;

        while (Blog::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
