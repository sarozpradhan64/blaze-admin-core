<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\Service;
use Blaze\AdminCore\Models\ServiceFeature;
use Illuminate\Http\Request;

class ServiceFeatureController extends Controller
{
    public function __construct(protected \Blaze\AdminCore\AdminCoreConfiguration $config)
    {
    }

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
        $extraFields = $this->config->serviceFeatureFormFields();

        return view('admin-core::service_features.form', compact('services', 'selectedService', 'extraFields'));
    }

    public function store(Request $request)
    {
        $extraFields = $this->config->serviceFeatureFormFields();

        $validated = $request->validate(array_merge([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
        ], $this->extraValidationRules($extraFields)));

        ServiceFeature::create($validated);

        return redirect()->route('admin.service-features.index')->with('success', 'Service feature created successfully.');
    }

    public function edit(ServiceFeature $serviceFeature)
    {
        $services = Service::all();
        $extraFields = $this->config->serviceFeatureFormFields();

        return view('admin-core::service_features.form', ['feature' => $serviceFeature, 'services' => $services, 'selectedService' => $serviceFeature->service_id, 'extraFields' => $extraFields]);
    }

    public function update(Request $request, ServiceFeature $serviceFeature)
    {
        $extraFields = $this->config->serviceFeatureFormFields();

        $validated = $request->validate(array_merge([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
        ], $this->extraValidationRules($extraFields)));

        $serviceFeature->update($validated);

        return redirect()->route('admin.service-features.index')->with('success', 'Service feature updated successfully.');
    }

    protected function extraValidationRules(array $extraFields): array
    {
        $rules = [];
        foreach ($extraFields as $field) {
            if ($field->validationRule) {
                $rules[$field->name] = $field->validationRule;
            }
        }
        return $rules;
    }

    public function destroy(ServiceFeature $serviceFeature)
    {
        $serviceFeature->delete();

        return redirect()->route('admin.service-features.index')->with('success', 'Service feature deleted successfully.');
    }
}
