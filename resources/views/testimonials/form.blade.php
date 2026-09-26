<x-layouts.admin title="{{ isset($testimonial) ? 'Edit' : 'Add' }} Testimonial">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.testimonials.index') }}">
                        Testimonials</x-ui.breadcrumb-link
                    ></x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>
                        {{ isset($testimonial) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page
                    ></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-4xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">{{ isset($testimonial) ? 'Edit' : 'Add' }} Testimonial</h2>
        </div>

        <form
            action="{{ isset($testimonial) ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @if (isset($testimonial)) @method('PUT') @endif

            <div class="grid gap-6 md:grid-cols-3">
                <div class="space-y-6 md:col-span-2">
                    <x-ui.card>
                        <x-ui.card-header><x-ui.card-title>Details</x-ui.card-title></x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <x-ui.field>
                                    <x-ui.field-label for="name">Name</x-ui.field-label>
                                    <x-ui.input
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $testimonial->name ?? '') }}"
                                    />
                                    <x-ui.field-error name="name" />
                                </x-ui.field>
                                <x-ui.field>
                                    <x-ui.field-label for="rating">Rating (1–5)</x-ui.field-label>
                                    <x-ui.input
                                        type="number"
                                        id="rating"
                                        name="rating"
                                        min="1"
                                        max="5"
                                        value="{{ old('rating', $testimonial->rating ?? 5) }}"
                                    />
                                    <x-ui.field-error name="rating" />
                                </x-ui.field>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <x-ui.field>
                                    <x-ui.field-label for="role">Role / Position</x-ui.field-label>
                                    <x-ui.input
                                        id="role"
                                        name="role"
                                        value="{{ old('role', $testimonial->role ?? '') }}"
                                    />
                                    <x-ui.field-error name="role" />
                                </x-ui.field>
                                <x-ui.field>
                                    <x-ui.field-label for="company">Company</x-ui.field-label>
                                    <x-ui.input
                                        id="company"
                                        name="company"
                                        value="{{ old('company', $testimonial->company ?? '') }}"
                                    />
                                    <x-ui.field-error name="company" />
                                </x-ui.field>
                            </div>
                            <x-ui.field>
                                <x-ui.field-label for="content">Review Content</x-ui.field-label>
                                <x-ui.rich-text-editor name="content">
                                    {!! old('content', $testimonial->content ?? '') !!}</x-ui.rich-text-editor>
                                <x-ui.field-error name="content" />
                            </x-ui.field>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>

                <div class="space-y-6">
                    <x-ui.card>
                        <x-ui.card-header><x-ui.card-title>Avatar Photo</x-ui.card-title></x-ui.card-header>
                        <x-ui.card-content>
                            <x-ui.file-upload
                                name="image"
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                :current="old('image', $testimonial->image ?? null)"
                            />
                            <x-ui.field-error name="image" />
                        </x-ui.card-content>
                    </x-ui.card>

                    <x-ui.card>
                        <x-ui.card-header><x-ui.card-title>Visibility</x-ui.card-title></x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div class="flex items-center justify-between">
                                <x-ui.label for="status" class="flex flex-col space-y-1">
                                    <span>Active</span>
                                    <span class="text-muted-foreground text-xs font-normal">Show on website</span>
                                </x-ui.label>
                                <x-ui.switch
                                    id="status"
                                    name="status"
                                    value="1"
                                    :checked="old('status', $testimonial->status ?? true)"
                                />
                            </div>
                            <div class="flex items-center justify-between">
                                <x-ui.label for="is_featured" class="flex flex-col space-y-1">
                                    <span>Featured</span>
                                    <span class="text-muted-foreground text-xs font-normal">Highlight on homepage</span>
                                </x-ui.label>
                                <x-ui.switch
                                    id="is_featured"
                                    name="is_featured"
                                    value="1"
                                    :checked="old('is_featured', $testimonial->is_featured ?? false)"
                                />
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-ui.button variant="outline" href="{{ route('admin.testimonials.index') }}">Cancel</x-ui.button>
                <x-ui.button type="submit">Save Testimonial</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.admin>
