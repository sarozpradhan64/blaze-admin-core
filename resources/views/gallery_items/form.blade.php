<x-layouts.admin title="{{ isset($item) ? 'Edit Image' : 'Add Image' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-link href="{{ route('admin.gallery-items.index') }}">Gallery Items</x-ui.breadcrumb-link></x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>{{ isset($item) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-4xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">{{ isset($item) ? 'Edit Image' : 'Add Gallery Image' }}</h2>
        </div>

        <form action="{{ isset($item) ? route('admin.gallery-items.update', $item) : route('admin.gallery-items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf @if(isset($item)) @method('PUT') @endif

            <div class="grid gap-6 md:grid-cols-3">
                <div class="md:col-span-2 space-y-6">
                    <x-ui.card>
                        <x-ui.card-header><x-ui.card-title>Image Details</x-ui.card-title></x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <x-ui.field>
                                <x-ui.field-label>Album</x-ui.field-label>
                                <x-ui.select name="gallery_album_id">
                                    <x-ui.select-trigger class="w-full"><x-ui.select-value placeholder="Select an album" /></x-ui.select-trigger>
                                    <x-ui.select-content>
                                        @foreach($albums as $a)
                                            <x-ui.select-item value="{{ $a->id }}" :selected="old('gallery_album_id', $item->gallery_album_id ?? $selectedAlbum) == $a->id">{{ $a->title }}</x-ui.select-item>
                                        @endforeach
                                    </x-ui.select-content>
                                </x-ui.select>
                                <x-ui.field-error name="gallery_album_id" />
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.field-label>Title / Caption</x-ui.field-label>
                                <x-ui.input name="title" value="{{ old('title', $item->title ?? '') }}" />
                                <x-ui.field-error name="title" />
                            </x-ui.field>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>

                <div class="space-y-6">
                    <x-ui.card>
                        <x-ui.card-header><x-ui.card-title>Image</x-ui.card-title></x-ui.card-header>
                        <x-ui.card-content>
                            <x-ui.file-upload
                                name="image_path"
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                :current="old('image_path', $item->image_path ?? null)"
                                
                            />
                            <x-ui.field-error name="image_path" />
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-ui.button variant="outline" href="{{ route('admin.gallery-items.index') }}">Cancel</x-ui.button>
                <x-ui.button type="submit">Save Image</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.admin>
