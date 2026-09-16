<x-layouts.admin title="Service Categories">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.services.index') }}">Services</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Categories</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold tracking-tight">Service Categories</h2>
        <x-ui.button href="{{ route('admin.service-categories.create') }}">
            <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
            Add Category
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head class="w-8"></x-ui.table-head>
                        <x-ui.table-head>Name</x-ui.table-head>
                        <x-ui.table-head>Slug</x-ui.table-head>
                        <x-ui.table-head>Services</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-admin::sortable-tbody resource="service-categories">
                    @forelse($categories as $category)
                        <x-ui.table-row data-id="{{ $category->id }}">
                            <x-ui.table-cell class="w-8">
                                <button type="button" data-drag-handle
                                    class="cursor-grab text-muted-foreground hover:text-foreground">
                                    <x-lucide-grip-vertical class="size-4" />
                                </button>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="font-medium">{{ $category->name }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-muted-foreground">{{ $category->slug }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $category->services_count }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ $category->status ? 'default' : 'secondary' }}">
                                    {{ $category->status ? 'Active' : 'Draft' }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button variant="ghost" size="icon"
                                        href="{{ route('admin.service-categories.edit', $category) }}">
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form action="{{ route('admin.service-categories.destroy', $category) }}"
                                        method="POST" onsubmit="return confirm('Are you sure?');">
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
                            <x-ui.table-cell colspan="6" class="text-center py-6 text-muted-foreground">
                                No categories found.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-admin::sortable-tbody>
            </x-ui.table>
        </x-ui.card-content>
        @if ($categories->hasPages())
            <x-ui.card-footer class="border-t p-4">
                {{ $categories->links() }}
            </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
