<x-layouts.admin title="Services">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Services</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold tracking-tight">Services</h2>
        <x-ui.button href="{{ route('admin.services.create') }}">
            <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
            Add Service
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head class="w-8"></x-ui.table-head>
                        <x-ui.table-head>Title</x-ui.table-head>
                        <x-ui.table-head>Category</x-ui.table-head>
                        <x-ui.table-head>Tags</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head>Featured</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-admin::sortable-tbody resource="services">
                    @forelse($services as $service)
                        <x-ui.table-row data-id="{{ $service->id }}">
                            <x-ui.table-cell class="w-8">
                                <button type="button" data-drag-handle
                                    class="cursor-grab text-muted-foreground hover:text-foreground">
                                    <x-lucide-grip-vertical class="size-4" />
                                </button>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="font-medium">{{ $service->title }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $service->category?->name ?? 'Uncategorized' }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($service->tags->take(3) as $tag)
                                        <x-ui.badge variant="outline">{{ $tag->name }}</x-ui.badge>
                                    @endforeach
                                </div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ $service->status ? 'default' : 'secondary' }}">
                                    {{ $service->status ? 'Active' : 'Draft' }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @if ($service->is_featured)
                                    <x-lucide-check-circle class="size-4 text-primary" />
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button variant="ghost" size="icon"
                                        href="{{ route('admin.services.edit', $service) }}">
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button variant="ghost" size="icon"
                                            class="text-destructive hover:text-destructive hover:bg-destructive/10"
                                            type="submit">
                                            <x-lucide-trash-2 class="size-4" />
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="7" class="text-center py-6 text-muted-foreground">
                                No services found.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-admin::sortable-tbody>
            </x-ui.table>
        </x-ui.card-content>
        @if ($services->hasPages())
            <x-ui.card-footer class="border-t p-4">
                {{ $services->links() }}
            </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
