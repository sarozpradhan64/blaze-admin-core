<x-layouts.admin title="Gallery Albums">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>Gallery Albums</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold tracking-tight">Gallery Albums</h2>
            <span class="text-xs text-muted-foreground/80">(Drag rows to reorder)</span>
        </div>
        <x-ui.button href="{{ route('admin.gallery-albums.create') }}">
            <x-slot:before>
                <x-lucide-plus class="size-4" />
            </x-slot:before>
            Add Album
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head class="w-10"></x-ui.table-head>
                        <x-ui.table-head>Title</x-ui.table-head>
                        <x-ui.table-head>Items</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-admin::sortable-tbody resource="gallery-albums">
                    @forelse ($albums as $album)
                        <x-ui.table-row data-id="{{ $album->id }}">
                            <x-ui.table-cell class="text-muted-foreground cursor-grab" data-drag-handle>
                                <x-lucide-grip-vertical class="size-4" />
                            </x-ui.table-cell>
                            <x-ui.table-cell class="font-medium">{{ $album->title }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <a
                                    href="{{ route('admin.gallery-items.index', ['album_id' => $album->id]) }}"
                                    class="text-primary hover:underline"
                                >{{ $album->items_count }} items</a>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ $album->status ? 'default' : 'secondary' }}">
                                    {{ $album->status ? 'Active' : 'Draft' }}</x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        variant="ghost"
                                        size="icon"
                                        href="{{ route('admin.gallery-albums.edit', $album) }}"
                                    >
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form
                                        action="{{ route('admin.gallery-albums.destroy', $album) }}"
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
                            <x-ui.table-cell colspan="5" class="text-muted-foreground py-6 text-center">
                                No albums found.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-admin::sortable-tbody>
            </x-ui.table>
        </x-ui.card-content>
        <x-ui.card-footer class="border-t p-4">{{ $albums->links() }}</x-ui.card-footer>
    </x-ui.card>
</x-layouts.admin>
