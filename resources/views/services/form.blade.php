<x-layouts.admin title="{{ isset($service) ? 'Edit Service' : 'Add Service' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.services.index') }}">Services</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($service) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-4xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">{{ isset($service) ? 'Edit Service' : 'Add New Service' }}</h2>
        </div>

            <x-ui.tabs value="general">
                <x-ui.tabs-list class="mb-6">
                    <x-ui.tabs-trigger value="general">
                        <x-lucide-layout-dashboard class="size-4 mr-2" />
                        General
                    </x-ui.tabs-trigger>
                    @if(isset($service))
                        @php($featuresLabel = $websiteSettings['label_services_features'] ?? 'Features')
                        <x-ui.tabs-trigger value="features">
                            <x-lucide-list-checks class="size-4 mr-2" />
                            {{ $featuresLabel }}
                        </x-ui.tabs-trigger>
                        <x-ui.tabs-trigger value="seo">
                            <x-lucide-search class="size-4 mr-2" />
                            SEO & Metadata
                        </x-ui.tabs-trigger>
                    @endif
                </x-ui.tabs-list>

                <x-ui.tabs-content value="general" class="space-y-6">
                    <form action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($service))
                            @method('PUT')
                        @endif
                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="md:col-span-2 space-y-6">
                            <x-ui.card>
                                <x-ui.card-header>
                                    <x-ui.card-title>General Information</x-ui.card-title>
                                </x-ui.card-header>
                                <x-ui.card-content class="space-y-4">
                                    <x-ui.field>
                                        <x-ui.field-label for="title">Title</x-ui.field-label>
                                        <x-ui.input id="title" name="title"
                                            value="{{ old('title', $service->title ?? '') }}" />
                                        <x-ui.field-error name="title" />
                                    </x-ui.field>

                                    <x-ui.field>
                                        <x-ui.field-label for="short_description">Short Description</x-ui.field-label>
                                        <x-ui.textarea id="short_description" name="short_description"
                                            rows="3">{{ old('short_description', $service->short_description ?? '') }}</x-ui.textarea>
                                        <x-ui.field-error name="short_description" />
                                    </x-ui.field>

                                    <x-ui.field>
                                        <x-ui.field-label for="description">Full Description</x-ui.field-label>
                                        <x-ui.rich-text-editor name="description" :value="old('description', $service->description ?? '')" />
                                        <x-ui.field-error name="description" />
                                    </x-ui.field>
                                </x-ui.card-content>
                            </x-ui.card>
                        </div>

                        <div class="space-y-6">
                            <x-ui.card>
                                <x-ui.card-header>
                                    <x-ui.card-title>Featured Image</x-ui.card-title>
                                </x-ui.card-header>
                                <x-ui.card-content>
                                    <x-ui.file-upload name="featured_image" accept="image/jpeg,image/png,image/gif,image/webp"
                                        :current="old('featured_image', $service->featured_image ?? null)" />
                                    <x-ui.field-error name="featured_image" />
                                </x-ui.card-content>
                            </x-ui.card>

                            <x-ui.card>
                                <x-ui.card-header>
                                    <x-ui.card-title>Organization</x-ui.card-title>
                                </x-ui.card-header>
                                <x-ui.card-content class="space-y-4">
                                    <x-ui.field>
                                        <x-ui.field-label for="service_category_id">Category</x-ui.field-label>
                                        <x-ui.select name="service_category_id">
                                            <x-ui.select-trigger>
                                                <x-ui.select-value placeholder="Select a category" />
                                            </x-ui.select-trigger>
                                            <x-ui.select-content>
                                                <x-ui.select-item value="">None</x-ui.select-item>
                                                @foreach ($categories as $cat)
                                                    <x-ui.select-item value="{{ $cat->id }}" :selected="old(
                                                        'service_category_id',
                                                        $service->service_category_id ?? '',
                                                    ) == $cat->id">
                                                        {{ $cat->name }}
                                                    </x-ui.select-item>
                                                @endforeach
                                            </x-ui.select-content>
                                        </x-ui.select>
                                        <x-ui.field-error name="service_category_id" />
                                    </x-ui.field>

                                    <x-ui.field>
                                        <x-ui.field-label for="tags">Tags</x-ui.field-label>
                                        <x-admin::tag-picker :value="old('tags', isset($service) ? $service->tagList() : '')" placeholder="Search or create tags" />
                                        <x-ui.field-error name="tags" />
                                    </x-ui.field>

                                    <x-ui.field>
                                        <x-ui.field-label for="faqs">FAQs</x-ui.field-label>
                                        <x-admin::faq-picker :value="old('faqs', isset($service) ? $service->faqs->pluck('id')->toArray() : [])" placeholder="Select FAQs" />
                                        <x-ui.field-error name="faqs" />
                                    </x-ui.field>

                                </x-ui.card-content>
                            </x-ui.card>

                            <x-ui.card>
                                <x-ui.card-header>
                                    <x-ui.card-title>Visibility</x-ui.card-title>
                                </x-ui.card-header>
                                <x-ui.card-content class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <x-ui.label for="status" class="flex flex-col space-y-1">
                                            <span>Active</span>
                                            <span class="font-normal text-xs text-muted-foreground">Publish to website</span>
                                        </x-ui.label>
                                        <x-ui.switch id="status" name="status" value="1" :checked="old('status', $service->status ?? true)" />
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <x-ui.label for="is_featured" class="flex flex-col space-y-1">
                                            <span>Featured</span>
                                            <span class="font-normal text-xs text-muted-foreground">Show on homepage</span>
                                        </x-ui.label>
                                        <x-ui.switch id="is_featured" name="is_featured" value="1" :checked="old('is_featured', $service->is_featured ?? false)" />
                                    </div>
                                </x-ui.card-content>
                            </x-ui.card>
                        </div>
                    </div>

                    {{-- Site-specific extra fields (injected by the project via AdminCoreConfiguration::serviceFormFields()) --}}
                    @if (!empty($extraFields))
                        <div>
                            @include('admin-core::services._extra_fields', ['fields' => $extraFields, 'model' => $service ?? null])
                        </div>
                    @endif

                        <div class="mt-6 flex justify-end gap-2">
                            <x-ui.button variant="outline" href="{{ route('admin.services.index') }}">Cancel</x-ui.button>
                            <x-ui.button type="submit">Save General</x-ui.button>
                        </div>
                    </form>
                </x-ui.tabs-content>

                @if(isset($service))
                    @php($featuresLabel = $websiteSettings['label_services_features'] ?? 'Features')
                    <x-ui.tabs-content value="features">
                        <form action="{{ route('admin.services.features.update', $service) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div x-data="{
                        features: {{ json_encode(old('features', isset($service) ? $service->features->toArray() : [])) }},
                        addFeature() { this.features.push({ title: '', description: '' }) },
                        removeFeature(index) { this.features.splice(index, 1) }
                    }">
                        <x-ui.card>
                            <x-ui.card-header>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-list-checks class="size-5 text-muted-foreground" />
                                        <div>
                                            <x-ui.card-title>{{ $featuresLabel }}</x-ui.card-title>
                                            <x-ui.card-description>Add {{ strtolower($featuresLabel) }} for this service.</x-ui.card-description>
                                        </div>
                                    </div>
                                    <x-ui.button type="button" variant="outline" size="sm" @click="addFeature">
                                        <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
                                        Add {{ rtrim($featuresLabel, 's') }}
                                    </x-ui.button>
                                </div>
                            </x-ui.card-header>
                            <x-ui.card-content class="space-y-4">
                                <template x-if="features.length === 0">
                                    <p class="text-sm text-muted-foreground text-center py-4">No {{ strtolower($featuresLabel) }} added yet. Click 'Add' to start.</p>
                                </template>
                                <div class="space-y-4">
                                    <template x-for="(feature, index) in features" :key="index">
                                        <div class="rounded-lg border border-border bg-muted/30 p-4 space-y-4 relative">
                                            <div class="absolute right-4 top-4">
                                                <button type="button" @click="removeFeature(index)" class="text-destructive hover:bg-destructive/10 p-1.5 rounded-md transition-colors">
                                                    <x-lucide-trash-2 class="size-4" />
                                                </button>
                                            </div>
                                            
                                            <div>
                                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 mb-2 block">Title</label>
                                                <input type="text" :name="`features[${index}][title]`" x-model="feature.title" placeholder="Feature title..." class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" required>
                                            </div>
                                            @if(isset($extraFeatureFields) && !empty($extraFeatureFields))
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @foreach($extraFeatureFields as $field)
                                                    <div>
                                                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 mb-2 block">{{ $field->label }}</label>
                                                        <input type="text" :name="`features[${index}][{{ $field->name }}]`" x-model="feature.{{ $field->name }}" placeholder="{{ $field->placeholder }}" class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                                                    </div>
                                                @endforeach
                                                </div>
                                            @endif
                                            
                                            <div>
                                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 mb-2 block">Description</label>
                                                <x-ui.rich-text-editor 
                                                    x-bind:name="`features[${index}][description]`" 
                                                    @input="feature.description = $event.target.value"
                                                    x-init="$nextTick(() => { $el.querySelector('[contenteditable]').innerHTML = feature.description || '' })"
                                                />
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </x-ui.card-content>
                        </x-ui.card>
                    </div>
                        <div class="mt-6 flex justify-end gap-2">
                            <x-ui.button type="submit">Save Features</x-ui.button>
                        </div>
                        </form>
                    </x-ui.tabs-content>

                    <x-ui.tabs-content value="seo">
                        <form action="{{ route('admin.services.seo.update', $service) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <x-admin::seo-fields :model="$service ?? null" :defaults="$seoDefaults ?? null" />
                            <div class="mt-6 flex justify-end gap-2">
                                <x-ui.button type="submit">Save SEO</x-ui.button>
                            </div>
                        </form>
                    </x-ui.tabs-content>
                @endif
            </x-ui.tabs>
        </div>
    </x-layouts.admin>
