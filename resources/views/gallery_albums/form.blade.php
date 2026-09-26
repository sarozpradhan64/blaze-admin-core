<x-layouts.admin title="{{ isset($album) ? 'Edit Album' : 'Add Album' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.gallery-albums.index') }}">
                        Gallery Albums</x-ui.breadcrumb-link
                    ></x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>
                        {{ isset($album) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page
                    ></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-4xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">{{ isset($album) ? 'Edit Album' : 'Add New Album' }}</h2>
        </div>

        <form
            action="{{ isset($album) ? route('admin.gallery-albums.update', $album) : route('admin.gallery-albums.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @if (isset($album)) @method('PUT') @endif

            <div class="grid gap-6 md:grid-cols-3">
                <div class="space-y-6 md:col-span-2">
                    <x-ui.card>
                        <x-ui.card-header><x-ui.card-title>Album Details</x-ui.card-title></x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <x-ui.field>
                                <x-ui.field-label>Title</x-ui.field-label>
                                <x-ui.input name="title" value="{{ old('title', $album->title ?? '') }}" />
                                <x-ui.field-error name="title" />
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.field-label>Description</x-ui.field-label>
                                <x-ui.rich-text-editor name="description">
                                    {!! old('description', $album->description ?? '') !!}</x-ui.rich-text-editor>
                                <x-ui.field-error name="description" />
                            </x-ui.field>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>

                <div class="space-y-6">
                    <x-ui.card>
                        <x-ui.card-header><x-ui.card-title>Cover Image</x-ui.card-title></x-ui.card-header>
                        <x-ui.card-content>
                            <x-ui.file-upload
                                name="cover_image"
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                :current="old('cover_image', $album->cover_image ?? null)"
                            />
                            <x-ui.field-error name="cover_image" />
                        </x-ui.card-content>
                    </x-ui.card>

                    <x-ui.card>
                        <x-ui.card-header><x-ui.card-title>Settings</x-ui.card-title></x-ui.card-header>
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
                                    :checked="old('status', $album->status ?? true)"
                                />
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-ui.button variant="outline" href="{{ route('admin.gallery-albums.index') }}">Cancel</x-ui.button>
                <x-ui.button type="submit">Save Album</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.admin>
