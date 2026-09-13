<x-layouts.admin title="Project Statistics">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.projects.index') }}">Projects</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Statistics</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold tracking-tight">Project Statistics</h2>
        <x-ui.button href="{{ route('admin.project-statistics.create', ['project_id' => request('project_id')]) }}">
            <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
            Add Statistic
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Label</x-ui.table-head>
                        <x-ui.table-head>Value</x-ui.table-head>
                        <x-ui.table-head>Project</x-ui.table-head>
                        <x-ui.table-head>Order</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($statistics as $stat)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">{{ $stat->label }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-primary font-semibold">{{ $stat->value }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <a href="{{ route('admin.projects.edit', $stat->project_id) }}" class="hover:underline text-primary">
                                    {{ $stat->project?->title }}
                                </a>
                            </x-ui.table-cell>
                            <x-ui.table-cell>{{ $stat->sort_order }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button variant="ghost" size="icon" href="{{ route('admin.project-statistics.edit', $stat) }}">
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form action="{{ route('admin.project-statistics.destroy', $stat) }}" method="POST" onsubmit="return confirm('Delete this statistic?');">
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
                            <x-ui.table-cell colspan="5" class="text-center py-6 text-muted-foreground">No statistics found.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
        @if($statistics->hasPages())
            <x-ui.card-footer class="border-t p-4">{{ $statistics->links() }}</x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
