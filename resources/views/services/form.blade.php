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

    <div>
        <div class="sticky top-0 z-10 -mx-4 mb-6 flex items-center justify-between border-b bg-background/95 px-4 py-3 backdrop-blur supports-[backdrop-filter]:bg-background/60 md:-mx-6 md:px-6">
            <div class="flex items-center gap-2">
                <h2 class="text-2xl font-bold tracking-tight">{{ isset($service) ? 'Edit Service' : 'Add New Service' }}</h2>
                @if(isset($service) && $service->url)
                    <a
                        href="{{ $service->url }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-muted-foreground hover:text-primary transition-colors inline-flex items-center"
                        title="View on site"
                    >
                        <x-lucide-external-link class="size-5 shrink-0" />
                    </a>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <x-ui.button variant="outline" href="{{ route('admin.services.index') }}">Cancel</x-ui.button>
                <x-ui.button type="submit" name="action" value="continue" variant="outline" form="service-general-form">Save & Continue</x-ui.button>
                <x-ui.button type="submit" name="action" value="save" form="service-general-form">Save Service</x-ui.button>
            </div>
        </div>

        <div class="max-w-4xl">

            <x-ui.tabs value="{{ request('tab', 'general') }}">
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
                        <x-ui.tabs-trigger value="gallery">
                            <x-lucide-images class="size-4 mr-2" />
                            Gallery / Cover Images
                        </x-ui.tabs-trigger>
                        <x-ui.tabs-trigger value="seo">
                            <x-lucide-search class="size-4 mr-2" />
                            SEO & Metadata
                        </x-ui.tabs-trigger>
                    @endif
                </x-ui.tabs-list>

                <x-ui.tabs-content value="general" class="space-y-6">
                    <form id="service-general-form" action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
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
                                        <x-ui.field-label for="title" required>Title</x-ui.field-label>
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
                                        <x-ui.field-label for="service_category_id" required>Category</x-ui.field-label>
                                        <x-ui.select name="service_category_id" :value="old('service_category_id', $service->service_category_id ?? '')" required>
                                            <x-ui.select-trigger>
                                                <x-ui.select-value placeholder="Select a category" />
                                            </x-ui.select-trigger>
                                            <x-ui.select-content>
                                                @foreach ($categories as $cat)
                                                    <x-ui.select-item value="{{ $cat->id }}">
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

                    <x-ui.tabs-content value="gallery" class="space-y-6">
                        <x-ui.card>
                            <x-ui.card-header>
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <x-ui.card-title>Cover &amp; Detail Page Gallery</x-ui.card-title>
                                        <x-ui.card-description>
                                            Upload cover and showcase photos. These images automatically sync to the site's Gallery and display in the service detail mosaic (first 3 images with "+N" card for additional photos).
                                        </x-ui.card-description>
                                    </div>
                                    @if($service->galleryAlbum)
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-muted-foreground">Linked Album:</span>
                                            <span class="text-xs font-semibold px-2 py-1 bg-muted rounded-md">{{ $service->galleryAlbum->title }}</span>
                                        </div>
                                    @endif
                                </div>
                            </x-ui.card-header>
                            <x-ui.card-content class="space-y-6">
                                {{-- Upload Multiple Images Form --}}
                                <form action="{{ route('admin.services.gallery.upload', $service) }}" method="POST" enctype="multipart/form-data" class="space-y-4 rounded-xl border border-dashed border-border p-6 bg-muted/20">
                                    @csrf
                                    <div class="flex flex-col items-center justify-center text-center space-y-3">
                                        <div class="p-3 bg-primary/10 text-primary rounded-full">
                                            <x-lucide-upload-cloud class="size-8" />
                                        </div>
                                        <div class="space-y-1">
                                            <label for="gallery-file-input" class="text-sm font-semibold cursor-pointer text-primary hover:underline">
                                                Choose images to upload
                                            </label>
                                            <input id="gallery-file-input" type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp,image/gif" class="block w-full text-xs text-muted-foreground file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-primary-foreground hover:file:opacity-90 cursor-pointer" required />
                                            <p class="text-xs text-muted-foreground">PNG, JPG, WEBP or GIF up to 10MB each</p>
                                        </div>
                                        <x-ui.button type="submit" size="sm">
                                            <x-slot:before><x-lucide-upload class="size-4" /></x-slot:before>
                                            Upload Selected Photos
                                        </x-ui.button>
                                    </div>
                                </form>

                                {{-- Current Gallery Images Grid with Drag-to-Reorder --}}
                                <div>
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-sm font-semibold text-foreground">
                                                Current Photos ({{ $service->galleryAlbum?->items?->count() ?? 0 }})
                                            </h3>
                                            <span class="text-xs text-muted-foreground/80 font-normal">(Drag cards to reorder)</span>
                                        </div>
                                        <span class="text-xs text-muted-foreground">The first 3 photos (#1, #2, #3) will display on the service detail page header.</span>
                                    </div>

                                    @if($service->galleryAlbum && $service->galleryAlbum->items->isNotEmpty())
                                        <div 
                                            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4"
                                            x-data="sortableList({
                                                reorderUrl: '{{ route('admin.reorder', 'gallery-items') }}',
                                                csrfToken: '{{ csrf_token() }}'
                                            })"
                                        >
                                            @foreach($service->galleryAlbum->items as $idx => $item)
                                                <div 
                                                    class="group relative rounded-lg overflow-hidden border border-border bg-card shadow-sm transition-all hover:shadow-md hover:border-primary/50"
                                                    data-id="{{ $item->id }}"
                                                >
                                                    <div 
                                                        class="aspect-video w-full bg-muted relative cursor-grab active:cursor-grabbing select-none"
                                                        data-drag-handle
                                                        title="Drag to reorder"
                                                    >
                                                        <img src="{{ Storage::disk('public')->url($item->file_path) }}" alt="{{ $item->title }}" class="h-full w-full object-cover pointer-events-none" />
                                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors pointer-events-none"></div>
                                                        <span class="absolute top-1.5 left-1.5 bg-black/75 text-white text-[10px] font-bold px-1.5 py-0.5 rounded flex items-center gap-1 shadow-sm">
                                                            <x-lucide-grip-vertical class="size-3 text-white/70" />
                                                            <span data-sortable-index>#{{ $idx + 1 }}</span>
                                                        </span>
                                                    </div>
                                                    <div class="p-2 flex items-center justify-between gap-1 text-xs">
                                                        <span class="truncate font-medium text-foreground text-[11px]" title="{{ $item->title }}">{{ $item->title ?: 'Photo #' . ($idx + 1) }}</span>
                                                        <form action="{{ route('admin.services.gallery.delete', ['service' => $service, 'galleryItem' => $item]) }}" method="POST" onsubmit="return confirm('Remove this image from gallery?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-destructive hover:bg-destructive/10 p-1 rounded transition-colors cursor-pointer" title="Delete photo">
                                                                <x-lucide-trash-2 class="size-3.5" />
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="p-8 text-center border rounded-lg bg-muted/10 text-muted-foreground text-sm">
                                            No gallery images uploaded yet for this service. Upload photos above to automatically create the linked album.
                                        </div>
                                    @endif
                                </div>

                                {{-- Optional: Link Existing Gallery Album --}}
                                @if(isset($albums) && $albums->isNotEmpty())
                                    <div class="pt-4 border-t">
                                        <form action="{{ route('admin.services.gallery-album.link', $service) }}" method="POST" class="flex flex-col sm:flex-row items-end gap-3">
                                            @csrf
                                            @method('PUT')
                                            <div class="flex-1 w-full">
                                                <label class="text-xs font-semibold text-muted-foreground block mb-1">Or Link to Existing Gallery Album</label>
                                                <select name="gallery_album_id" class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm">
                                                    <option value="">— Select Album —</option>
                                                    @foreach($albums as $alb)
                                                        <option value="{{ $alb->id }}" {{ $service->gallery_album_id == $alb->id ? 'selected' : '' }}>
                                                            {{ $alb->title }} ({{ $alb->items->count() }} items)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <x-ui.button type="submit" variant="outline" size="sm">Update Album Link</x-ui.button>
                                        </form>
                                    </div>
                                @endif
                            </x-ui.card-content>
                        </x-ui.card>
                    </x-ui.tabs-content>

                    <x-ui.tabs-content value="seo">
                        <form action="{{ route('admin.services.seo.update', $service) }}" method="POST" enctype="multipart/form-data">
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
    </div>
    </x-layouts.admin>
