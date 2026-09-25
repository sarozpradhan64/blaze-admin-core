<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\AdminCoreConfiguration;
use Blaze\AdminCore\Models\Service;
use Blaze\AdminCore\Models\ServiceCategory;
use Blaze\AdminCore\Models\WebsiteSetting;
use Blaze\AdminCore\Support\ServiceFormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function __construct(protected AdminCoreConfiguration $config) {}

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

        $extraFields = $this->config->serviceFormFields();
        $extraFeatureFields = $this->config->serviceFeatureFormFields();

        return view('admin-core::services.form', compact('categories', 'seoDefaults', 'extraFields', 'extraFeatureFields'));
    }

    public function store(Request $request)
    {
        $extraFields = $this->config->serviceFormFields();

        $validated = $request->validate(array_merge(
            [
                'title' => 'required|string|max:255',
                'service_category_id' => 'nullable|exists:service_categories,id',
                'short_description' => 'nullable|string',
                'description' => 'required|string',
                'icon' => 'nullable|string|max:255',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'tags' => 'nullable|string|max:1000',
                'faqs' => 'nullable|array',
                'features' => 'nullable|array',
                'features.*.title' => 'required_with:features|string|max:255',
                'features.*.description' => 'nullable|string',
            ],
            $this->extraValidationRules($extraFields),
        ));

        $tags = $validated['tags'] ?? null;
        unset($validated['tags']);

        $faqs = $validated['faqs'] ?? [];
        unset($validated['faqs']);

        $features = $validated['features'] ?? [];
        unset($validated['features']);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('services', 'public');
        }

        $validated['slug'] = $this->uniqueSlug($validated['title'], 'services');
        $validated['status'] = $request->has('status');
        $validated['is_featured'] = $request->has('is_featured');

        $this->applyExtraFields($validated, $request, $extraFields);

        $service = Service::create($validated);
        $service->syncTagsFromString($tags);
        if (method_exists($service, 'syncFaqs')) {
            $service->syncFaqs($faqs);
        }

        $extraFeatureFields = $this->config->serviceFeatureFormFields();
        foreach ($features as $index => $feature) {
            if (!empty($feature['title'])) {
                $data = [
                    'title' => $feature['title'],
                    'description' => $feature['description'] ?? null,
                    'sort_order' => $index,
                ];
                foreach ($extraFeatureFields as $field) {
                    $data[$field->name] = $feature[$field->name] ?? null;
                }
                $service->features()->create($data);
            }
        }

        return redirect()->route('admin.services.edit', $service)->with('success', 'Service created successfully. You can now add features and SEO.');
    }

    public function edit(Service $service)
    {
        $categories = ServiceCategory::all();
        $extraFields = $this->config->serviceFormFields();
        $extraFeatureFields = $this->config->serviceFeatureFormFields();

        return view('admin-core::services.form', compact('service', 'categories', 'extraFields', 'extraFeatureFields'));
    }

    public function update(Request $request, Service $service)
    {
        $extraFields = $this->config->serviceFormFields();

        $validated = $request->validate(array_merge(
            [
                'title' => 'required|string|max:255',
                'service_category_id' => 'nullable|exists:service_categories,id',
                'short_description' => 'nullable|string',
                'description' => 'required|string',
                'icon' => 'nullable|string|max:255',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'tags' => 'nullable|string|max:1000',
                'faqs' => 'nullable|array',
                'features' => 'nullable|array',
                'features.*.title' => 'required_with:features|string|max:255',
                'features.*.description' => 'nullable|string',
            ],
            $this->extraValidationRules($extraFields),
        ));

        $tags = $validated['tags'] ?? null;
        unset($validated['tags']);

        $faqs = $validated['faqs'] ?? [];
        unset($validated['faqs']);

        $features = $validated['features'] ?? [];
        unset($validated['features']);

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

        $this->applyExtraFields($validated, $request, $extraFields);

        $service->update($validated);
        $service->syncTagsFromString($tags);
        if (method_exists($service, 'syncFaqs')) {
            $service->syncFaqs($faqs);
        }

        return redirect()->back()->with('success', 'Service updated successfully.');
    }

    public function updateFeatures(Request $request, Service $service)
    {
        $extraFeatureFields = $this->config->serviceFeatureFormFields();
        $rules = [
            'features' => 'nullable|array',
            'features.*.title' => 'required_with:features|string|max:255',
            'features.*.description' => 'nullable|string',
        ];

        foreach ($extraFeatureFields as $field) {
            if ($field->validationRule !== null) {
                // Remove 'required' and make it nullable for array items if needed, but we'll just prepend 'nullable|' if not required
                $rules['features.*.' . $field->name] = $field->validationRule;
            }
        }

        $validated = $request->validate($rules);

        $features = $validated['features'] ?? [];

        $service->features()->delete();
        foreach ($features as $index => $feature) {
            if (!empty($feature['title'])) {
                $data = [
                    'title' => $feature['title'],
                    'description' => $feature['description'] ?? null,
                    'sort_order' => $index,
                ];
                
                foreach ($extraFeatureFields as $field) {
                    $data[$field->name] = $feature[$field->name] ?? null;
                }

                $service->features()->create($data);
            }
        }

        return redirect()->back()->with('success', 'Service features updated successfully.');
    }

    public function updateSeo(Request $request, Service $service)
    {
        $validated = $request->validate([
            'seo' => 'nullable|array',
            'seo.meta_title' => 'nullable|string|max:255',
            'seo.meta_description' => 'nullable|string',
            'seo.og_title' => 'nullable|string|max:255',
            'seo.og_description' => 'nullable|string',
            'seo.og_image' => 'nullable|url|max:255',
        ]);

        $seoData = $validated['seo'] ?? [];
        $service->seo()->updateOrCreate(
            ['seoable_id' => $service->id, 'seoable_type' => get_class($service)],
            $seoData
        );

        return redirect()->back()->with('success', 'Service SEO updated successfully.');
    }

    public function destroy(Service $service)
    {
        if ($service->featured_image) {
            Storage::disk('public')->delete($service->featured_image);
        }
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Build the validation rule map from the extra field definitions.
     *
     * @param  ServiceFormField[]         $fields
     * @return array<string, string|null>
     */
    private function extraValidationRules(array $fields): array
    {
        $rules = [];

        foreach ($fields as $field) {
            if ($field->validationRule !== null) {
                $rules[$field->name] = $field->validationRule;
            }
        }

        return $rules;
    }

    /**
     * Copy extra field values from the request into the validated data array,
     * applying any type-specific transformations (e.g. json_encode for arrays).
     *
     * @param  array<string, mixed> $validated  Passed by reference so callers see the mutations.
     * @param  ServiceFormField[]   $fields
     */
    private function applyExtraFields(array &$validated, Request $request, array $fields): void
    {
        foreach ($fields as $field) {
            match ($field->type) {
                'checkbox' => $validated[$field->name] = $request->has($field->name),
                'file'     => $this->handleFileField($validated, $request, $field),
                default    => $validated[$field->name] = $request->input($field->name),
            };
        }
    }

    /**
     * Handle file-type extra fields: store the upload and write the path into $validated.
     *
     * @param array<string, mixed> $validated
     */
    private function handleFileField(array &$validated, Request $request, ServiceFormField $field): void
    {
        if ($request->hasFile($field->name)) {
            $validated[$field->name] = $request->file($field->name)->store('services', 'public');
        } else {
            unset($validated[$field->name]);
        }
    }
}
