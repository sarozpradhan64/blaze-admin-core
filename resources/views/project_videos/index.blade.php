<x-layouts.admin title="Project Videos">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.projects.index') }}">Projects</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Videos</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold tracking-tight">Project Videos</h2>
        <x-ui.button href="{{ route('admin.project-videos.create', ['project_id' => request('project_id')]) }}">
            <x-slot:before>
                <x-lucide-plus class="size-4" />
            </x-slot:before>
            Add Video
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Title</x-ui.table-head>
                        <x-ui.table-head>Project</x-ui.table-head>
                        <x-ui.table-head>Type</x-ui.table-head>
                        <x-ui.table-head>Order</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($videos as $video)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">{{ $video->title ?? 'Untitled' }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <a
                                    href="{{ route('admin.projects.edit', $video->project_id) }}"
                                    class="text-primary hover:underline"
                                >
                                    {{ $video->project?->title }}
                                </a>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="outline">{{ ucfirst($video->video_type) }}</x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell>{{ $video->sort_order }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        variant="ghost"
                                        size="icon"
                                        href="{{ route('admin.project-videos.edit', $video) }}"
                                    >
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form
                                        action="{{ route('admin.project-videos.destroy', $video) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this video?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button
                                            variant="ghost"
                                            size="icon"
                                            class="text-destructive hover:text-destructive hover:bg-destructive/10"
                                            type="submit"
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
                                No videos found.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
        @if ($videos->hasPages())
            <x-ui.card-footer class="border-t p-4">{{ $videos->links() }}</x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
