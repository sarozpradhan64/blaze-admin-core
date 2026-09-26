<x-layouts.admin title="Manage Tags">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>Tags</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Manage Tags</h2>
            <p class="text-muted-foreground text-sm">Create, rename, and review tags used across your content.</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_2fr]">
        <x-ui.card class="h-fit">
            <x-ui.card-header>
                <x-ui.card-title>Add Tag</x-ui.card-title>
                <x-ui.card-description>New tags become available in every tag picker.</x-ui.card-description>
            </x-ui.card-header>
            <form action="{{ route('admin.tags.store') }}" method="POST">
                @csrf
                <x-ui.card-content class="space-y-4">
                    <x-ui.field>
                        <x-ui.field-label for="name">Name</x-ui.field-label>
                        <x-ui.input
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. Sustainability"
                            autofocus
                        />
                        <x-ui.field-error name="name" />
                    </x-ui.field>
                </x-ui.card-content>
                <x-ui.card-footer class="bg-muted/50 flex justify-end border-t p-4">
                    <x-ui.button type="submit">
                        <x-slot:before>
                            <x-lucide-plus class="size-4" />
                        </x-slot:before>
                        Add Tag</x-ui.button>
                </x-ui.card-footer>
            </form>
        </x-ui.card>

        <x-ui.card>
            <x-ui.card-header>
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <x-ui.card-title>All Tags</x-ui.card-title>
                        <x-ui.card-description>
                            {{ $tags->total() }} {{ Str::plural('tag', $tags->total()) }}</x-ui.card-description>
                    </div>
                    <form method="GET" action="{{ route('admin.tags.manage') }}" class="w-full max-w-xs">
                        <x-ui.input
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search tags"
                            aria-label="Search tags"
                        />
                    </form>
                </div>
            </x-ui.card-header>
            <x-ui.card-content class="p-0">
                <div class="overflow-x-auto">
                    <x-ui.table>
                        <x-ui.table-header>
                            <x-ui.table-row>
                                <x-ui.table-head>Tag</x-ui.table-head>
                                <x-ui.table-head>Usage</x-ui.table-head>
                                <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                            </x-ui.table-row>
                        </x-ui.table-header>
                        <x-ui.table-body>
                            @forelse ($tags as $tag)
                                <x-ui.table-row>
                                    <x-ui.table-cell class="min-w-64">
                                        <div class="flex items-center gap-3">
                                            <span class="bg-muted text-muted-foreground flex size-8 shrink-0 items-center justify-center rounded-md">
                                                <x-lucide-tag class="size-4" />
                                            </span>
                                            <div class="min-w-0">
                                                <p class="truncate font-medium">{{ $tag->name }}</p>
                                                <p class="text-muted-foreground truncate text-xs">{{ $tag->slug }}</p>
                                            </div>
                                        </div>
                                    </x-ui.table-cell>
                                    <x-ui.table-cell class="whitespace-nowrap">
                                        <x-ui.button
                                            variant="link"
                                            size="sm"
                                            href="{{ route('admin.tags.show', $tag) }}"
                                        >
                                            {{ $tag->usage_count }} {{ Str::plural('item', $tag->usage_count) }}
                                        </x-ui.button>
                                    </x-ui.table-cell>
                                    <x-ui.table-cell class="text-right whitespace-nowrap">
                                        <div class="flex justify-end gap-1">
                                            <x-ui.button
                                                variant="ghost"
                                                size="icon"
                                                href="{{ route('admin.tags.edit', $tag) }}"
                                                title="Edit tag"
                                            >
                                                <x-lucide-edit class="size-4" />
                                            </x-ui.button>
                                            <form
                                                action="{{ route('admin.tags.destroy', $tag) }}"
                                                method="POST"
                                                onsubmit="return confirm('Delete this unused tag?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <x-ui.button
                                                    type="submit"
                                                    variant="ghost"
                                                    size="icon"
                                                    class="text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                    title="Delete tag"
                                                    :disabled="$tag->usage_count > 0"
                                                >
                                                    <x-lucide-trash-2 class="size-4" />
                                                </x-ui.button>
                                            </form>
                                        </div>
                                    </x-ui.table-cell>
                                </x-ui.table-row>
                            @empty
                                <x-ui.table-row>
                                    <x-ui.table-cell colspan="3" class="text-muted-foreground py-8 text-center">
                                        No tags found.</x-ui.table-cell>
                                </x-ui.table-row>
                            @endforelse
                        </x-ui.table-body>
                    </x-ui.table>
                </div>
            </x-ui.card-content>
            @if ($tags->hasPages())
                <x-ui.card-footer class="border-t p-4"> {{ $tags->links() }} </x-ui.card-footer>
            @endif
        </x-ui.card>
    </div>
</x-layouts.admin>
