<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceFeature;
use Illuminate\Http\Request;

class ServiceFeatureController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceFeature::with('service')->orderBy('service_id')->orderBy('sort_order');
        
        if ($request->has('service_id')) {
            $query->where('service_id', $request->service_id);
        }
        
        $features = $query->paginate(15);
        return view('admin-core::service_features.index', compact('features'));
    }

    public function create(Request $request)
    {
        $services = Service::all();
        $selectedService = $request->get('service_id');
        return view('admin-core::service_features.form', compact('services', 'selectedService'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
        ]);

        ServiceFeature::create($validated);

        return redirect()->route('admin.service-features.index')->with('success', 'Service feature created successfully.');
    }

    public function edit(ServiceFeature $serviceFeature)
    {
        $services = Service::all();
        return view('admin-core::service_features.form', ['feature' => $serviceFeature, 'services' => $services, 'selectedService' => $serviceFeature->service_id]);
    }

    public function update(Request $request, ServiceFeature $serviceFeature)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
        ]);

        $serviceFeature->update($validated);

        return redirect()->route('admin.service-features.index')->with('success', 'Service feature updated successfully.');
    }

    public function destroy(ServiceFeature $serviceFeature)
    {
        $serviceFeature->delete();
        return redirect()->route('admin.service-features.index')->with('success', 'Service feature deleted successfully.');
    }
}


