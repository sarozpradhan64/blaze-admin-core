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
            <h2 class="text-2xl font-bold tracking-tight">
                {{ isset($service) ? 'Edit Service' : 'Add New Service' }}
            </h2>
        </div>

        <form
            action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @if (isset($service))
                @method('PUT')
            @endif

            <div class="grid gap-6 md:grid-cols-3">
                <div class="space-y-6 md:col-span-2">
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>General Information</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <x-ui.field>
                                <x-ui.field-label for="title">Title</x-ui.field-label>
                                <x-ui.input id="title" name="title" value="{{ old('title', $service->title ?? '') }}" />
                                <x-ui.field-error name="title" />
                            </x-ui.field>

                            <x-ui.field>
                                <x-ui.field-label for="short_description">Short Description</x-ui.field-label>
                                <x-ui.textarea
                                    id="short_description"
                                    name="short_description"
                                    rows="3"
                                >
                                    {{ old('short_description', $service->short_description ?? '') }}</x-ui.textarea>
                                <x-ui.field-error name="short_description" />
                            </x-ui.field>

                            <x-ui.field>
                                <x-ui.field-label for="description">Full Description</x-ui.field-label>
                                <x-ui.rich-text-editor
                                    name="description"
                                    :value="old('description', $service->description ?? '')"
                                />
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
                            <x-ui.file-upload
                                name="featured_image"
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                :current="old('featured_image', $service->featured_image ?? null)"
                            />
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
                                            <x-ui.select-item
                                                value="{{ $cat->id }}"
                                                :selected="old(
                                                'service_category_id',
                                                $service->service_category_id ?? '',
                                            ) == $cat->id"
                                            >
                                                {{ $cat->name }}
                                            </x-ui.select-item>
                                        @endforeach
                                    </x-ui.select-content>
                                </x-ui.select>
                                <x-ui.field-error name="service_category_id" />
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
                                    <span class="text-muted-foreground text-xs font-normal">Publish to website</span>
                                </x-ui.label>
                                <x-ui.switch
                                    id="status"
                                    name="status"
                                    value="1"
                                    :checked="old('status', $service->status ?? true)"
                                />
                            </div>

                            <div class="flex items-center justify-between">
                                <x-ui.label for="is_featured" class="flex flex-col space-y-1">
                                    <span>Featured</span>
                                    <span class="text-muted-foreground text-xs font-normal">Show on homepage</span>
                                </x-ui.label>
                                <x-ui.switch
                                    id="is_featured"
                                    name="is_featured"
                                    value="1"
                                    :checked="old('is_featured', $service->is_featured ?? false)"
                                />
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </div>

            {{-- SEO Panel (full width below the grid) --}}
            <div class="mt-6">
                <x-admin::seo-fields :model="$service ?? null" :defaults="$seoDefaults ?? null" />
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-ui.button variant="outline" href="{{ route('admin.services.index') }}">Cancel</x-ui.button>
                <x-ui.button type="submit">Save Service</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.admin>
