<x-layouts.admin title="Affiliations">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>Affiliations</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Our Affiliations</h2>
            <p class="text-muted-foreground mt-1 text-sm">Manage partner and affiliation logos displayed on the website.</p>
        </div>
        <x-ui.button href="{{ route('admin.affiliations.create') }}">
            <x-slot:before>
                <x-lucide-plus class="size-4" />
            </x-slot:before>
            Add Affiliation
        </x-ui.button>
    </div>

    @if (session('success'))
        <div class="flex items-center gap-2 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-600 dark:text-green-400">
            <x-lucide-check-circle class="size-4 shrink-0" />
            {{ session('success') }}
        </div>
    @endif

    <x-ui.filter-bar action="{{ route('admin.affiliations.index') }}">
        <div class="flex-1 min-w-[280px] relative">
            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
            <x-ui.input name="search" value="{{ request('search') }}" placeholder="Search affiliations..." class="pl-9 h-10" />
            @if(request('search'))
                <a href="{{ route('admin.affiliations.index', request()->except('search')) }}" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <x-lucide-x class="size-4 text-muted-foreground hover:text-foreground" />
                </a>
            @endif
        </div>

        <div class="w-[200px]">
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
                    <x-ui.select-item value="active">Active (Visible)</x-ui.select-item>
                    <x-ui.select-item value="draft">Hidden</x-ui.select-item>
                </x-ui.select-content>
            </x-ui.select>
        </div>
    </x-ui.filter-bar>

    <x-ui.card>
        <div class="flex items-center justify-between p-4 pb-0 border-b-0">
            <div class="text-sm text-muted-foreground">
                <span class="font-medium text-foreground">{{ $affiliations->total() }}</span> affiliations found
                <span class="text-xs text-muted-foreground/80 ml-2">(Drag rows to reorder)</span>
            </div>
            
            <form action="{{ route('admin.affiliations.index') }}" method="GET" class="flex items-center gap-2 text-sm text-muted-foreground">
                @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                @if(request('sort_by'))<input type="hidden" name="sort_by" value="{{ request('sort_by') }}">@endif
                @if(request('sort_dir'))<input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">@endif
                
                <span>Show</span>
                <x-ui.select name="per_page" value="{{ request('per_page', 20) }}">
                    <x-ui.select-trigger class="h-8 w-[70px]">
                        <x-ui.select-value placeholder="20" />
                    </x-ui.select-trigger>
                    <x-ui.select-content>
                        @foreach([10, 20, 50, 100] as $amount)
                            <x-ui.select-item value="{{ $amount }}">{{ $amount }}</x-ui.select-item>
                        @endforeach
                    </x-ui.select-content>
                </x-ui.select>
                <span>entries</span>
                <x-ui.button type="submit" variant="outline" size="sm" class="h-8">Apply</x-ui.button>
            </form>
        </div>

        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head class="w-10"></x-ui.table-head>
                        <x-ui.table-head class="w-32">Logo</x-ui.table-head>
                        <x-ui.sortable-th column="name">Organization / Name</x-ui.sortable-th>
                        <x-ui.table-head>Website Link</x-ui.table-head>
                        <x-ui.sortable-th column="status" class="w-28 text-center">Status</x-ui.sortable-th>
                        <x-ui.table-head class="text-right w-28">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-admin::sortable-tbody resource="affiliations">
                    @forelse ($affiliations as $affiliation)
                        <x-ui.table-row data-id="{{ $affiliation->id }}">
                            <x-ui.table-cell class="text-muted-foreground cursor-grab" data-drag-handle>
                                <x-lucide-grip-vertical class="size-4" />
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="h-12 w-28 rounded-md border bg-muted/20 p-1 flex items-center justify-center overflow-hidden">
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::url($affiliation->logo) }}"
                                        alt="{{ $affiliation->name }}"
                                        class="max-h-full max-w-full object-contain"
                                    />
                                </div>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="font-medium">
                                {{ $affiliation->name }}
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @if($affiliation->link)
                                    <a href="{{ $affiliation->link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-primary hover:underline text-xs">
                                        <span class="truncate max-w-[200px]">{{ $affiliation->link }}</span>
                                        <x-lucide-external-link class="size-3 shrink-0" />
                                    </a>
                                @else
                                    <span class="text-muted-foreground text-xs">—</span>
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-center">
                                <form action="{{ route('admin.affiliations.toggle', $affiliation) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        title="{{ $affiliation->status ? 'Click to hide' : 'Click to show' }}"
                                        class="inline-flex cursor-pointer items-center transition-transform hover:scale-105"
                                    >
                                        <x-ui.badge variant="{{ $affiliation->status ? 'default' : 'secondary' }}" class="text-xs">
                                            {{ $affiliation->status ? 'Active' : 'Hidden' }}
                                        </x-ui.badge>
                                    </button>
                                </form>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <x-ui.button variant="ghost" size="icon" href="{{ route('admin.affiliations.edit', $affiliation) }}" title="Edit">
                                        <x-lucide-pencil class="size-4" />
                                    </x-ui.button>
                                    <form action="{{ route('admin.affiliations.destroy', $affiliation) }}" method="POST" onsubmit="return confirm('Delete this affiliation?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button variant="ghost" size="icon" type="submit" class="text-destructive hover:text-destructive" title="Delete">
                                            <x-lucide-trash-2 class="size-4" />
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="6" class="text-center py-12 text-muted-foreground">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <x-lucide-badge-check class="size-8 opacity-40" />
                                    <p class="text-sm font-medium">No affiliations found</p>
                                    <p class="text-xs text-muted-foreground">Upload affiliation and accreditation logos to display on the footer.</p>
                                    <x-ui.button href="{{ route('admin.affiliations.create') }}" size="sm" class="mt-2">
                                        <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
                                        Add Affiliation
                                    </x-ui.button>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-admin::sortable-tbody>
            </x-ui.table>
        </x-ui.card-content>
        @if ($affiliations->hasPages())
            <x-ui.card-footer class="border-t p-4">
                {{ $affiliations->links() }}
            </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
