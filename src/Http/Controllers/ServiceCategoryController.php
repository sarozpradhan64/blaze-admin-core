<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\AdminCore\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::withCount('services')->orderBy('sort_order')->latest()->paginate(10);

        return view('admin-core::service_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin-core::service_categories.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->has('status');

        ServiceCategory::create($validated);

        return redirect()->route('admin.service-categories.index')->with('success', 'Service Category created successfully.');
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        return view('admin-core::service_categories.form', ['category' => $serviceCategory]);
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
        ]);

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

        $serviceCategory->delete();

        return redirect()->route('admin.service-categories.index')->with('success', 'Service Category deleted successfully.');
    }
}
