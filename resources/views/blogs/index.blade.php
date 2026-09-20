<x-layouts.admin title="Blog">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Blog</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold tracking-tight">Blog</h2>
        <x-ui.button href="{{ route('admin.blogs.create') }}">
            <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
            Add Blog Post
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Title</x-ui.table-head>
                        <x-ui.table-head>Category</x-ui.table-head>
                        <x-ui.table-head>Author</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head>Updated</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($blogs as $blog)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">{{ $blog->title }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $blog->category?->name ?? 'Uncategorized' }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $blog->author?->name ?? 'System' }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ $blog->status ? 'default' : 'secondary' }}">
                                    {{ $blog->status ? 'Published' : 'Draft' }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell>{{ $blog->updated_at->format('M d, Y') }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button variant="ghost" size="icon"
                                        href="{{ route('admin.blogs.edit', $blog) }}">
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
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
                            <x-ui.table-cell colspan="6" class="text-center py-6 text-muted-foreground">
                                No blog posts found.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
        @if ($blogs->hasPages())
            <x-ui.card-footer class="border-t p-4">
                {{ $blogs->links() }}
            </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
