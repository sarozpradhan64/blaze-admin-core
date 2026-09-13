<x-layouts.admin title="Project Categories">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.projects.index') }}">Projects</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Categories</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold tracking-tight">Project Categories</h2>
        <x-ui.button href="{{ route('admin.project-categories.create') }}">
            <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
            Add Category
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Name</x-ui.table-head>
                        <x-ui.table-head>Slug</x-ui.table-head>
                        <x-ui.table-head>Projects</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($categories as $category)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">{{ $category->name }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-muted-foreground">{{ $category->slug }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $category->projects_count }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ $category->status ? 'default' : 'secondary' }}">
                                    {{ $category->status ? 'Active' : 'Draft' }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button variant="ghost" size="icon" href="{{ route('admin.project-categories.edit', $category) }}">
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form action="{{ route('admin.project-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button variant="ghost" size="icon" class="text-destructive hover:text-destructive hover:bg-destructive/10" type="submit">
                                            <x-lucide-trash-2 class="size-4" />
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="5" class="text-center py-6 text-muted-foreground">
                                No categories found.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
        @if($categories->hasPages())
            <x-ui.card-footer class="border-t p-4">
                {{ $categories->links() }}
            </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
