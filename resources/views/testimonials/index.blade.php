<x-layouts.admin title="Testimonials">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>Testimonials</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold tracking-tight">Testimonials</h2>
        <x-ui.button href="{{ route('admin.testimonials.create') }}">
            <x-slot:before>
                <x-lucide-plus class="size-4" />
            </x-slot:before>
            Add Testimonial
        </x-ui.button>
    </div>

    <x-ui.filter-bar action="{{ route('admin.testimonials.index') }}">
        <div class="flex-1 min-w-[300px] relative">
            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
            <x-ui.input name="search" value="{{ request('search') }}" placeholder="Search testimonials..." class="pl-9 h-10" />
            @if(request('search'))
                <a href="{{ route('admin.testimonials.index', request()->except('search')) }}" class="absolute right-3 top-1/2 -translate-y-1/2">
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
                    <x-ui.select-item value="active">Active</x-ui.select-item>
                    <x-ui.select-item value="draft">Hidden</x-ui.select-item>
                </x-ui.select-content>
            </x-ui.select>
        </div>
    </x-ui.filter-bar>

    <x-ui.card>
        <div class="flex items-center justify-between p-4 pb-0 border-b-0">
            <div class="text-sm text-muted-foreground">
                <span class="font-medium text-foreground">{{ $testimonials->total() }}</span> testimonials found
            </div>
            
            <form action="{{ route('admin.testimonials.index') }}" method="GET" class="flex items-center gap-2 text-sm text-muted-foreground">
                @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                @if(request('sort_by'))<input type="hidden" name="sort_by" value="{{ request('sort_by') }}">@endif
                @if(request('sort_dir'))<input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">@endif
                
                <span>Show</span>
                <x-ui.select name="per_page" value="{{ request('per_page', 10) }}">
                    <x-ui.select-trigger class="h-8 w-[70px]">
                        <x-ui.select-value placeholder="10" />
                    </x-ui.select-trigger>
                    <x-ui.select-content>
                        @foreach([10, 25, 50, 100] as $amount)
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
                        <x-ui.sortable-th column="name">Name</x-ui.sortable-th>
                        <x-ui.table-head>Role / Company</x-ui.table-head>
                        <x-ui.sortable-th column="rating">Rating</x-ui.sortable-th>
                        <x-ui.sortable-th column="status">Status</x-ui.sortable-th>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($testimonials as $testimonial)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">{{ $testimonial->name }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                {{ $testimonial->role }}{{ $testimonial->company ? ' @ '.$testimonial->company : '' }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $testimonial->rating }}/5</x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="flex items-center gap-2">
                                    <x-ui.badge variant="{{ $testimonial->status ? 'default' : 'secondary' }}">
                                        {{ $testimonial->status ? 'Active' : 'Hidden' }}</x-ui.badge>
                                    @if ($testimonial->is_featured)
                                        <x-lucide-star class="text-primary size-4" />
                                    @endif
                                </div>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        variant="ghost"
                                        size="icon"
                                        href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                    >
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form
                                        action="{{ route('admin.testimonials.destroy', $testimonial) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button
                                            type="submit"
                                            variant="ghost"
                                            size="icon"
                                            class="text-destructive hover:text-destructive hover:bg-destructive/10"
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
                                No testimonials found.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
        <x-ui.card-footer class="border-t p-4">{{ $testimonials->links() }}</x-ui.card-footer>
    </x-ui.card>
</x-layouts.admin>
