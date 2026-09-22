<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\Service;
use Blaze\AdminCore\Models\ServiceCategory;
use Blaze\AdminCore\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['category', 'tags'])->orderBy('sort_order')->latest()->paginate(10);

        return view('admin-core::services.index', compact('services'));
    }

    public function create()
    {
        $categories = ServiceCategory::all();
        $seoDefaults = WebsiteSetting::whereIn('key', ['seo_default_title', 'seo_default_description', 'seo_default_keywords'])
            ->pluck('value', 'key');

        return view('admin-core::services.form', compact('categories', 'seoDefaults'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'short_description' => 'nullable|string',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'tags' => 'nullable|string|max:1000',
        ]);

        $tags = $validated['tags'] ?? null;
        unset($validated['tags']);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('services', 'public');
        }

        $validated['slug'] = $this->uniqueSlug($validated['title'], 'services');
        $validated['status'] = $request->has('status');
        $validated['is_featured'] = $request->has('is_featured');

        $service = Service::create($validated);
        $service->syncTagsFromString($tags);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $categories = ServiceCategory::all();

        return view('admin-core::services.form', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'short_description' => 'nullable|string',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'tags' => 'nullable|string|max:1000',
        ]);

        $tags = $validated['tags'] ?? null;
        unset($validated['tags']);

        if ($request->hasFile('featured_image')) {
            if ($service->featured_image) {
                Storage::disk('public')->delete($service->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('services', 'public');
        } else {
            unset($validated['featured_image']);
        }

        if ($service->title !== $validated['title']) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], 'services', $service->id);
        }
        $validated['status'] = $request->has('status');
        $validated['is_featured'] = $request->has('is_featured');

        $service->update($validated);
        $service->syncTagsFromString($tags);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        if ($service->featured_image) {
            Storage::disk('public')->delete($service->featured_image);
        }
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
}
