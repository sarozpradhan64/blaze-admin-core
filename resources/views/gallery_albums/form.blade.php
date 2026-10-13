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

        <x-ui.tabs value="general">
            <x-ui.tabs-list class="mb-6">
                <x-ui.tabs-trigger value="general">
                    <x-lucide-layout-dashboard class="size-4 mr-2" />
                    General
                </x-ui.tabs-trigger>
                @if(isset($album))
                <x-ui.tabs-trigger value="photos">
                    <x-lucide-images class="size-4 mr-2" />
                    Album Photos ({{ $album->items->count() }})
                </x-ui.tabs-trigger>
                <x-ui.tabs-trigger value="seo">
                    <x-lucide-search class="size-4 mr-2" />
                    SEO Metadata
                </x-ui.tabs-trigger>
                @endif
            </x-ui.tabs-list>

            <x-ui.tabs-content value="general">
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
                                    <div class="flex items-center justify-between">
                                        <x-ui.label for="is_featured" class="flex flex-col space-y-1">
                                            <span>Featured</span>
                                            <span class="text-muted-foreground text-xs font-normal">Show in featured section</span>
                                        </x-ui.label>
                                        <x-ui.switch
                                            id="is_featured"
                                            name="is_featured"
                                            value="1"
                                            :checked="old('is_featured', $album->is_featured ?? false)"
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
            </x-ui.tabs-content>

            @if(isset($album))
            <x-ui.tabs-content value="photos" class="space-y-6">
                <x-ui.card>
                    <x-ui.card-header class="flex flex-row items-center justify-between pb-4">
                        <div>
                            <x-ui.card-title>Album Photos ({{ $album->items->count() }})</x-ui.card-title>
                            <x-ui.card-description>Drag photos to reorder them in this album.</x-ui.card-description>
                        </div>
                        <x-ui.button href="{{ route('admin.gallery-items.create', ['album_id' => $album->id]) }}" size="sm">
                            <x-slot:before>
                                <x-lucide-plus class="size-4" />
                            </x-slot:before>
                            Add Image
                        </x-ui.button>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        @if($album->items->isNotEmpty())
                            <div 
                                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4"
                                x-data="sortableList({
                                    reorderUrl: '{{ route('admin.reorder', 'gallery-items') }}',
                                    csrfToken: '{{ csrf_token() }}'
                                })"
                            >
                                @foreach($album->items as $idx => $item)
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
                                            <div class="flex items-center gap-1">
                                                <a href="{{ route('admin.gallery-items.edit', $item) }}" class="text-muted-foreground hover:text-foreground p-1 rounded" title="Edit">
                                                    <x-lucide-edit class="size-3.5" />
                                                </a>
                                                <form action="{{ route('admin.gallery-items.destroy', $item) }}" method="POST" onsubmit="return confirm('Remove this image from album?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-destructive hover:bg-destructive/10 p-1 rounded transition-colors cursor-pointer" title="Delete">
                                                        <x-lucide-trash-2 class="size-3.5" />
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 text-center border rounded-lg bg-muted/10 text-muted-foreground text-sm">
                                No photos in this album yet. <a href="{{ route('admin.gallery-items.create', ['album_id' => $album->id]) }}" class="text-primary hover:underline font-semibold">Add an image</a> to get started.
                            </div>
                        @endif
                    </x-ui.card-content>
                </x-ui.card>
            </x-ui.tabs-content>

            <x-ui.tabs-content value="seo">
                <form action="{{ route('admin.gallery-albums.seo.update', $album) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <x-admin::seo-fields :model="$album" />
                    <div class="mt-6 flex justify-end gap-2">
                        <x-ui.button type="submit">Save SEO</x-ui.button>
                    </div>
                </form>
            </x-ui.tabs-content>
            @endif
        </x-ui.tabs>
    </div>
</x-layouts.admin>
