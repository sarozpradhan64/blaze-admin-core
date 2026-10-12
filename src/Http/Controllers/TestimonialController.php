<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\Service;
use Blaze\AdminCore\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $testimonials = Testimonial::with('services')
            ->search($request->get('search'), ['name', 'company', 'role'])
            ->filterStatus($request->get('status'))
            ->sort($request->get('sort_by'), $request->get('sort_dir'))
            ->paginate($request->get('per_page', 10))
            ->withQueryString();

        return view('admin-core::testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        $services = Service::orderBy('title')->get();

        return view('admin-core::testimonials.form', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'service_id' => 'nullable|array',
            'service_id.*' => 'nullable|integer|exists:services,id',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('testimonials', 'public');
        }
        $validated['is_featured'] = $request->has('is_featured');
        $validated['status'] = $request->has('status');

        $serviceIds = array_filter(array_map('intval', (array) ($request->input('service_id', []))));
        $validated['service_id'] = ! empty($serviceIds) ? reset($serviceIds) : null;

        $testimonial = Testimonial::create($validated);
        $testimonial->services()->sync($serviceIds);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial)
    {
        $testimonial->load('services');
        $services = Service::orderBy('title')->get();

        return view('admin-core::testimonials.form', compact('testimonial', 'services'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'service_id' => 'nullable|array',
            'service_id.*' => 'nullable|integer|exists:services,id',
        ]);

        if ($request->hasFile('image')) {
            if ($testimonial->image) {
                Storage::disk('public')->delete($testimonial->image);
            }
            $validated['image'] = $request->file('image')->store('testimonials', 'public');
        } else {
            unset($validated['image']);
        }
        $validated['is_featured'] = $request->has('is_featured');
        $validated['status'] = $request->has('status');

        $serviceIds = array_filter(array_map('intval', (array) ($request->input('service_id', []))));
        $validated['service_id'] = ! empty($serviceIds) ? reset($serviceIds) : null;

        $testimonial->update($validated);
        $testimonial->services()->sync($serviceIds);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->image) {
            Storage::disk('public')->delete($testimonial->image);
        }
        $testimonial->delete();

        return back()->with('success', 'Testimonial deleted.');
    }
}
