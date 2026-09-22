<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::with('parent')->withCount('services')->orderBy('sort_order')->latest()->paginate(10);

        return view('admin-core::service_categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = ServiceCategory::orderBy('name')->get();

        return view('admin-core::service_categories.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => ['nullable', 'exists:service_categories,id'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('service-categories', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->has('status');

        ServiceCategory::create($validated);

        return redirect()->route('admin.service-categories.index')->with('success', 'Service Category created successfully.');
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        $categories = ServiceCategory::where('id', '!=', $serviceCategory->id)->orderBy('name')->get();

        return view('admin-core::service_categories.form', ['category' => $serviceCategory, 'categories' => $categories]);
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => [
                'nullable',
                'exists:service_categories,id',
                Rule::notIn([$serviceCategory->id]),
            ],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($serviceCategory->thumbnail) {
                Storage::disk('public')->delete($serviceCategory->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')->store('service-categories', 'public');
        } else {
            unset($validated['thumbnail']);
        }

        if ($serviceCategory->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        $validated['status'] = $request->has('status');

        $serviceCategory->update($validated);

        return redirect()->route('admin.service-categories.index')->with('success', 'Service Category updated successfully.');
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        if ($serviceCategory->services()->count() > 0) {
            return redirect()->route('admin.service-categories.index')->with('error', 'Cannot delete category with attached services.');
        }

        if ($serviceCategory->thumbnail) {
            Storage::disk('public')->delete($serviceCategory->thumbnail);
        }

        $serviceCategory->delete();

        return redirect()->route('admin.service-categories.index')->with('success', 'Service Category deleted successfully.');
    }
}
