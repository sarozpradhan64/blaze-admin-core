<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\AdminCore\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function manage(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $tags = Tag::query()
            ->when($search !== '', fn($query) => $query->where('name', 'like', '%' . $search . '%'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin-core::tags.index', compact('tags', 'search'));
    }

    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('search', ''));

        $tags = Tag::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', $search . '%')
                        ->orWhere('name', 'like', '% ' . $search . '%');
                });
            })
            ->orderByDesc('usage_count')
            ->orderBy('name')
            ->limit(12)
            ->get(['id', 'name', 'slug', 'usage_count']);

        return response()->json($tags);
    }

    public function show(Tag $tag)
    {
        $tag->load([
            'services:id,title,slug',
            'projects:id,title,slug',
        ]);

        $usedItems = collect()
            ->merge($tag->services->map(fn($service): array => [
                'type' => 'Service',
                'title' => $service->title,
                'slug' => $service->slug,
                'url' => route('admin.services.edit', $service),
            ]))
            ->merge($tag->projects->map(fn($project): array => [
                'type' => 'Project',
                'title' => $project->title,
                'slug' => $project->slug,
                'url' => route('admin.projects.edit', $project),
            ]));

        return view('admin-core::tags.show', compact('tag', 'usedItems'));
    }

    public function edit(Tag $tag)
    {
        return view('admin-core::tags.edit', compact('tag'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $slug = Str::slug($validated['name']);

        if (Tag::where('slug', $slug)->exists()) {
            return back()->withErrors(['name' => 'A tag with this name already exists.'])->withInput();
        }

        Tag::create(['name' => $validated['name'], 'slug' => $slug]);

        return redirect()->route('admin.tags.manage')->with('success', 'Tag created successfully.');
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $slug = Str::slug($validated['name']);
        $slugExists = Tag::where('slug', $slug)->whereKeyNot($tag->id)->exists();

        if ($slugExists) {
            return back()->withErrors(['name' => 'A tag with this name already exists.'])->withInput();
        }

        $tag->update([
            'name' => $validated['name'],
            'slug' => $slug,
        ]);

        return redirect()->route('admin.tags.manage')->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        if ($tag->usage_count > 0) {
            return back()->with('error', 'Tags in use cannot be deleted. Remove it from content first.');
        }

        DB::table('taggables')->where('tag_id', $tag->id)->delete();
        $tag->delete();

        return back()->with('success', 'Tag deleted successfully.');
    }
}
