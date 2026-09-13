<x-layouts.admin title="Projects">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Projects</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold tracking-tight">Projects</h2>
        <x-ui.button href="{{ route('admin.projects.create') }}">
            <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
            Add Project
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Title</x-ui.table-head>
                        <x-ui.table-head>Category</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head>Featured</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($projects as $project)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">
                                {{ $project->title }}
                            </x-ui.table-cell>
                            <x-ui.table-cell>{{ $project->category?->name ?? 'Uncategorized' }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ $project->status ? 'default' : 'secondary' }}">
                                    {{ $project->status ? 'Active' : 'Draft' }}
                                </x-ui.badge>
                                <x-ui.badge variant="outline" class="ml-1">
                                    {{ ucfirst($project->project_status) }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @if($project->is_featured)
                                    <x-lucide-check-circle class="size-4 text-primary" />
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button variant="ghost" size="icon" href="{{ route('admin.projects.edit', $project) }}">
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
                                No projects found.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
        @if($projects->hasPages())
            <x-ui.card-footer class="border-t p-4">
                {{ $projects->links() }}
            </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
