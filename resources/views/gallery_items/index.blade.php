<x-layouts.admin title="Gallery Items">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>Gallery Items</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold tracking-tight">Gallery Items</h2>
            <span class="text-xs text-muted-foreground/80">(Drag rows to reorder)</span>
        </div>
        <x-ui.button href="{{ route('admin.gallery-items.create', ['album_id' => request('album_id')]) }}">
            <x-slot:before>
                <x-lucide-plus class="size-4" />
            </x-slot:before>
            Add Image
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head class="w-10"></x-ui.table-head>
                        <x-ui.table-head>Image</x-ui.table-head>
                        <x-ui.table-head>Title</x-ui.table-head>
                        <x-ui.table-head>Album</x-ui.table-head>
                        <x-ui.table-head>Tags</x-ui.table-head>
                        <x-ui.table-head>Sort Order</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-admin::sortable-tbody resource="gallery-items">
                    @forelse ($items as $item)
                        <x-ui.table-row data-id="{{ $item->id }}">
                            <x-ui.table-cell class="text-muted-foreground cursor-grab" data-drag-handle>
                                <x-lucide-grip-vertical class="size-4" />
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <img
                                    src="{{ Storage::disk('public')->url($item->file_path) }}"
                                    alt="{{ $item->title }}"
                                    class="size-10 rounded object-cover"
                                />
                            </x-ui.table-cell>
                            <x-ui.table-cell class="font-medium">{{ $item->title ?: '—' }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <a
                                    href="{{ route('admin.gallery-items.index', ['album_id' => $item->gallery_album_id]) }}"
                                    class="text-primary hover:underline"
                                >{{ $item->album?->title }}</a>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($item->tags->take(3) as $tag)
                                        <x-ui.badge variant="outline">{{ $tag->name }}</x-ui.badge>
                                    @endforeach
                                    @if ($item->tags->count() > 3)
                                        <x-ui.badge variant="secondary">+{{ $item->tags->count() - 3 }}
                                            more</x-ui.badge>
                                    @endif
                                </div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <span data-sortable-index>#{{ $item->sort_order }}</span>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        variant="ghost"
                                        size="icon"
                                        href="{{ route('admin.gallery-items.edit', $item) }}"
                                    >
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form
                                        action="{{ route('admin.gallery-items.destroy', $item) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button
                                            type="submit"
                                            variant="ghost"
                                            size="icon"
                                            class="text-destructive hover:text-destructive hover:bg-destructive/10"
                                        >
                                            <x-lucide-trash-2 class="size-4" />
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="7" class="text-muted-foreground py-6 text-center">
                                No gallery items found.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-admin::sortable-tbody>
            </x-ui.table>
        </x-ui.card-content>
        <x-ui.card-footer class="border-t p-4">{{ $items->links() }}</x-ui.card-footer>
    </x-ui.card>
</x-layouts.admin>
