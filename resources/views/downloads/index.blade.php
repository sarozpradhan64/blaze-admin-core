<x-layouts.admin title="Downloads">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>Downloads</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold tracking-tight">Downloads</h2>
        <x-ui.button href="{{ route('admin.downloads.create') }}">
            <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
            Add Download
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Title</x-ui.table-head>
                        <x-ui.table-head>File</x-ui.table-head>
                        <x-ui.table-head>Visibility</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($downloads as $download)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">{{ $download->title }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-muted-foreground max-w-xs truncate">{{ basename($download->file_path) }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ $download->is_public ? 'default' : 'secondary' }}">{{ $download->is_public ? 'Public' : 'Hidden' }}</x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button variant="ghost" size="icon" href="{{ route('admin.downloads.edit', $download) }}">
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form action="{{ route('admin.downloads.destroy', $download) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf @method('DELETE')
                                        <x-ui.button type="submit" variant="ghost" size="icon" class="text-destructive hover:text-destructive hover:bg-destructive/10">
                                            <x-lucide-trash-2 class="size-4" />
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="4" class="text-center py-6 text-muted-foreground">No downloads found.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
    </x-ui.card>
</x-layouts.admin>
