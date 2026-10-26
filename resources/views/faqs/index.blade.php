<x-layouts.admin title="Manage FAQs">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>FAQs</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Manage FAQs</h2>
            <p class="text-muted-foreground text-sm">Create, edit, and review FAQs used across your content.</p>
        </div>
        <x-ui.button href="{{ route('admin.faqs.create') }}">
            <x-slot:before>
                <x-lucide-plus class="size-4" />
            </x-slot:before>
            Add FAQ
        </x-ui.button>
    </div>

    <x-ui.filter-bar action="{{ route('admin.faqs.index') }}" clear-url="{{ route('admin.faqs.index') }}">
        <div class="flex-1 min-w-[280px] relative">
            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
            <x-ui.input
                name="search"
                value="{{ request('search') }}"
                placeholder="Search questions and answers..."
                class="pl-9 h-10"
            />
            @if(request('search'))
                <a href="{{ route('admin.faqs.index', request()->except('search')) }}" class="absolute right-3 top-1/2 -translate-y-1/2" title="Clear search">
                    <x-lucide-x class="size-4 text-muted-foreground hover:text-foreground" />
                </a>
            @endif
        </div>

        <div class="w-[180px]">
            <x-ui.select name="status" value="{{ request('status') }}">
                <x-ui.select-trigger class="h-10">
                    <x-slot:icon><x-lucide-eye class="size-4" /></x-slot:icon>
                    <div class="flex flex-col items-start text-xs">
                        <span class="font-medium text-foreground">Status</span>
                        <x-ui.select-value placeholder="All Statuses" />
                    </div>
                </x-ui.select-trigger>
                <x-ui.select-content>
                    <x-ui.select-item value="all">All Statuses</x-ui.select-item>
                    <x-ui.select-item value="active">Active</x-ui.select-item>
                    <x-ui.select-item value="draft">Draft</x-ui.select-item>
                </x-ui.select-content>
            </x-ui.select>
        </div>

        <div class="w-[190px]">
            <x-ui.select name="contact_page" value="{{ request('contact_page') }}">
                <x-ui.select-trigger class="h-10">
                    <x-slot:icon><x-lucide-send class="size-4" /></x-slot:icon>
                    <div class="flex flex-col items-start text-xs">
                        <span class="font-medium text-foreground">Placement</span>
                        <x-ui.select-value placeholder="All Placements" />
                    </div>
                </x-ui.select-trigger>
                <x-ui.select-content>
                    <x-ui.select-item value="all">All Placements</x-ui.select-item>
                    <x-ui.select-item value="yes">Contact Page</x-ui.select-item>
                    <x-ui.select-item value="no">Content Only</x-ui.select-item>
                </x-ui.select-content>
            </x-ui.select>
        </div>

        <div class="w-[180px]">
            <x-ui.select name="usage" value="{{ request('usage') }}">
                <x-ui.select-trigger class="h-10">
                    <x-slot:icon><x-lucide-layers class="size-4" /></x-slot:icon>
                    <div class="flex flex-col items-start text-xs">
                        <span class="font-medium text-foreground">Usage</span>
                        <x-ui.select-value placeholder="All Usages" />
                    </div>
                </x-ui.select-trigger>
                <x-ui.select-content>
                    <x-ui.select-item value="all">All Usages</x-ui.select-item>
                    <x-ui.select-item value="used">Attached / In Use</x-ui.select-item>
                    <x-ui.select-item value="unused">Unused (0)</x-ui.select-item>
                </x-ui.select-content>
            </x-ui.select>
        </div>

        @if(request('sort_by'))<input type="hidden" name="sort_by" value="{{ request('sort_by') }}">@endif
        @if(request('sort_dir'))<input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">@endif
        @if(request('per_page'))<input type="hidden" name="per_page" value="{{ request('per_page') }}">@endif
    </x-ui.filter-bar>

    <x-ui.card>
        <div class="flex items-center justify-between p-4 pb-0 border-b-0">
            <div class="text-sm text-muted-foreground">
                <span class="font-medium text-foreground">{{ $faqs->total() }}</span> {{ Str::plural('FAQ', $faqs->total()) }} found
            </div>

            <form action="{{ route('admin.faqs.index') }}" method="GET" class="flex items-center gap-2 text-sm text-muted-foreground">
                @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                @if(request('contact_page'))<input type="hidden" name="contact_page" value="{{ request('contact_page') }}">@endif
                @if(request('usage'))<input type="hidden" name="usage" value="{{ request('usage') }}">@endif
                @if(request('sort_by'))<input type="hidden" name="sort_by" value="{{ request('sort_by') }}">@endif
                @if(request('sort_dir'))<input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">@endif

                <span>Show</span>
                <x-ui.select name="per_page" value="{{ request('per_page', 15) }}">
                    <x-ui.select-trigger class="h-8 w-[76px]">
                        <x-ui.select-value placeholder="15" />
                    </x-ui.select-trigger>
                    <x-ui.select-content>
                        @foreach([10, 15, 25, 50, 100] as $amount)
                            <x-ui.select-item value="{{ $amount }}">{{ $amount }}</x-ui.select-item>
                        @endforeach
                    </x-ui.select-content>
                </x-ui.select>
                <span>entries</span>
                <x-ui.button type="submit" variant="outline" size="sm" class="h-8">Apply</x-ui.button>
            </form>
        </div>

        <x-ui.card-content class="p-0">
            <div class="overflow-x-auto">
                <x-ui.table>
                    <x-ui.table-header>
                        <x-ui.table-row>
                            <x-ui.sortable-th column="sort_order" class="w-20">Sort</x-ui.sortable-th>
                            <x-ui.sortable-th column="question">Question &amp; Answer</x-ui.sortable-th>
                            <x-ui.sortable-th column="status">Status</x-ui.sortable-th>
                            <x-ui.sortable-th column="usage_count">Usage</x-ui.sortable-th>
                            <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                        </x-ui.table-row>
                    </x-ui.table-header>
                    <x-ui.table-body>
                        @forelse ($faqs as $faq)
                            <x-ui.table-row>
                                <x-ui.table-cell class="text-muted-foreground font-mono">
                                    {{ $faq->sort_order }}
                                </x-ui.table-cell>
                                <x-ui.table-cell class="min-w-64">
                                    <p class="truncate font-medium">{{ $faq->question }}</p>
                                    <p class="text-muted-foreground max-w-sm truncate text-xs">
                                        {{ strip_tags($faq->answer) }}
                                    </p>
                                </x-ui.table-cell>
                                <x-ui.table-cell>
                                    <div class="flex flex-col gap-1 items-start">
                                        <x-ui.badge variant="{{ $faq->status ? 'success' : 'secondary' }}">
                                            {{ $faq->status ? 'Active' : 'Draft' }}
                                        </x-ui.badge>
                                        @if ($faq->is_contact_page)
                                            <x-ui.badge variant="outline" class="text-[10px]">
                                                Contact Page
                                            </x-ui.badge>
                                        @endif
                                    </div>
                                </x-ui.table-cell>
                                <x-ui.table-cell class="whitespace-nowrap">
                                    {{ $faq->usage_count }} {{ Str::plural('item', $faq->usage_count) }}
                                </x-ui.table-cell>
                                <x-ui.table-cell class="text-right whitespace-nowrap">
                                    <div class="flex justify-end gap-1">
                                        <x-ui.button
                                            variant="ghost"
                                            size="icon"
                                            href="{{ route('admin.faqs.edit', $faq) }}"
                                            title="Edit FAQ"
                                        >
                                            <x-lucide-edit class="size-4" />
                                        </x-ui.button>
                                        <form
                                            action="{{ route('admin.faqs.destroy', $faq) }}"
                                            method="POST"
                                            onsubmit="return confirm('Delete this unused FAQ?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button
                                                type="submit"
                                                variant="ghost"
                                                size="icon"
                                                class="text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                title="Delete FAQ"
                                                :disabled="$faq->usage_count > 0"
                                            >
                                                <x-lucide-trash-2 class="size-4" />
                                            </x-ui.button>
                                        </form>
                                    </div>
                                </x-ui.table-cell>
                            </x-ui.table-row>
                        @empty
                            <x-ui.table-row>
                                <x-ui.table-cell colspan="5" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2 text-muted-foreground">
                                        <x-lucide-help-circle class="size-8 stroke-1 text-muted-foreground/60" />
                                        <p class="font-medium text-foreground">No FAQs found</p>
                                        <p class="text-xs">
                                            @if(request()->anyFilled(['search', 'status', 'contact_page', 'usage']))
                                                Try adjusting or clearing your filters to see more results.
                                            @else
                                                No FAQs have been added yet. Click "Add FAQ" to create one.
                                            @endif
                                        </p>
                                        @if(request()->anyFilled(['search', 'status', 'contact_page', 'usage']))
                                            <x-ui.button variant="outline" size="sm" href="{{ route('admin.faqs.index') }}" class="mt-2">
                                                Clear all filters
                                            </x-ui.button>
                                        @endif
                                    </div>
                                </x-ui.table-cell>
                            </x-ui.table-row>
                        @endforelse
                    </x-ui.table-body>
                </x-ui.table>
            </div>
        </x-ui.card-content>
        @if ($faqs->hasPages())
            <x-ui.card-footer class="border-t p-4"> {{ $faqs->links() }} </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
