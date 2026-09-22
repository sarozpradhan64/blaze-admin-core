<x-layouts.admin title="Gallery Items">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>Gallery Items</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold tracking-tight">Gallery Items</h2>
        <x-ui.button href="{{ route('admin.gallery-items.create', ['album_id' => request('album_id')]) }}">
            <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
            Add Image
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Image</x-ui.table-head>
                        <x-ui.table-head>Title</x-ui.table-head>
                        <x-ui.table-head>Album</x-ui.table-head>
                        <x-ui.table-head>Tags</x-ui.table-head>
                        <x-ui.table-head>Sort Order</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($items as $item)
                        <x-ui.table-row>
                            <x-ui.table-cell>
                                <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->title }}"
                                    class="size-10 rounded object-cover" />
                            </x-ui.table-cell>
                            <x-ui.table-cell class="font-medium">{{ $item->title ?: '—' }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <a href="{{ route('admin.gallery-items.index', ['album_id' => $item->gallery_album_id]) }}"
                                    class="text-primary hover:underline">{{ $item->album?->title }}</a>
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
                            <x-ui.table-cell>{{ $item->sort_order }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button variant="ghost" size="icon"
                                        href="{{ route('admin.gallery-items.edit', $item) }}">
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form action="{{ route('admin.gallery-items.destroy', $item) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf @method('DELETE')
                                        <x-ui.button type="submit" variant="ghost" size="icon"
                                            class="text-destructive hover:text-destructive hover:bg-destructive/10">
                                            <x-lucide-trash-2 class="size-4" />
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="6" class="text-center py-6 text-muted-foreground">No gallery
                                items found.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
    </x-ui.card>
</x-layouts.admin>
