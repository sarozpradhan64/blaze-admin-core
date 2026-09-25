<x-layouts.admin title="Manage FAQs">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>FAQs</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Manage FAQs</h2>
            <p class="text-sm text-muted-foreground">Create, edit, and review FAQs used across your content.</p>
        </div>
        <x-ui.button href="{{ route('admin.faqs.create') }}">
            <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
            Add FAQ
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <x-ui.card-title>All FAQs</x-ui.card-title>
                    <x-ui.card-description>{{ $faqs->total() }} {{ Str::plural('FAQ', $faqs->total()) }}</x-ui.card-description>
                </div>
                <form method="GET" action="{{ route('admin.faqs.index') }}" class="w-full max-w-xs">
                    <x-ui.input name="search" value="{{ $search }}" placeholder="Search FAQs" aria-label="Search FAQs" />
                </form>
            </div>
        </x-ui.card-header>
        <x-ui.card-content class="p-0">
            <div class="overflow-x-auto">
                <x-ui.table>
                    <x-ui.table-header>
                        <x-ui.table-row>
                            <x-ui.table-head class="w-16">Sort</x-ui.table-head>
                            <x-ui.table-head>Question</x-ui.table-head>
                            <x-ui.table-head>Status</x-ui.table-head>
                            <x-ui.table-head>Usage</x-ui.table-head>
                            <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                        </x-ui.table-row>
                    </x-ui.table-header>
                    <x-ui.table-body>
                        @forelse ($faqs as $faq)
                            <x-ui.table-row>
                                <x-ui.table-cell class="font-mono text-muted-foreground">{{ $faq->sort_order }}</x-ui.table-cell>
                                <x-ui.table-cell class="min-w-64">
                                    <p class="font-medium truncate">{{ $faq->question }}</p>
                                    <p class="truncate text-xs text-muted-foreground max-w-sm">{{ strip_tags($faq->answer) }}</p>
                                </x-ui.table-cell>
                                <x-ui.table-cell>
                                    <x-ui.badge variant="{{ $faq->status ? 'success' : 'secondary' }}">
                                        {{ $faq->status ? 'Active' : 'Draft' }}
                                    </x-ui.badge>
                                </x-ui.table-cell>
                                <x-ui.table-cell class="whitespace-nowrap">
                                    {{ $faq->usage_count }} {{ Str::plural('item', $faq->usage_count) }}
                                </x-ui.table-cell>
                                <x-ui.table-cell class="whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-1">
                                        <x-ui.button variant="ghost" size="icon" href="{{ route('admin.faqs.edit', $faq) }}" title="Edit FAQ">
                                            <x-lucide-edit class="size-4" />
                                        </x-ui.button>
                                        <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Delete this unused FAQ?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button type="submit" variant="ghost" size="icon" class="text-destructive hover:bg-destructive/10 hover:text-destructive" title="Delete FAQ" :disabled="$faq->usage_count > 0">
                                                <x-lucide-trash-2 class="size-4" />
                                            </x-ui.button>
                                        </form>
                                    </div>
                                </x-ui.table-cell>
                            </x-ui.table-row>
                        @empty
                            <x-ui.table-row>
                                <x-ui.table-cell colspan="5" class="py-8 text-center text-muted-foreground">No FAQs found.</x-ui.table-cell>
                            </x-ui.table-row>
                        @endforelse
                    </x-ui.table-body>
                </x-ui.table>
            </div>
        </x-ui.card-content>
        @if ($faqs->hasPages())
            <x-ui.card-footer class="border-t p-4">
                {{ $faqs->links() }}
            </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
