<x-layouts.admin title="Project Images">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.projects.index') }}">Projects</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Images</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold tracking-tight">Project Images</h2>
        <x-ui.button href="{{ route('admin.project-images.create', ['project_id' => request('project_id')]) }}">
            <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
            Add Image
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Image Path</x-ui.table-head>
                        <x-ui.table-head>Project</x-ui.table-head>
                        <x-ui.table-head>Alt Text</x-ui.table-head>
                        <x-ui.table-head>Featured</x-ui.table-head>
                        <x-ui.table-head>Order</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($images as $image)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium max-w-xs truncate">{{ $image->image }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <a href="{{ route('admin.projects.edit', $image->project_id) }}" class="hover:underline text-primary">
                                    {{ $image->project?->title }}
                                </a>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-muted-foreground">{{ $image->alt_text ?? '—' }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                @if($image->is_featured)
                                    <x-lucide-check-circle class="size-4 text-primary" />
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell>{{ $image->sort_order }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button variant="ghost" size="icon" href="{{ route('admin.project-images.edit', $image) }}">
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form action="{{ route('admin.project-images.destroy', $image) }}" method="POST" onsubmit="return confirm('Delete this image?');">
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
                            <x-ui.table-cell colspan="6" class="text-center py-6 text-muted-foreground">No images found.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
        @if($images->hasPages())
            <x-ui.card-footer class="border-t p-4">{{ $images->links() }}</x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
