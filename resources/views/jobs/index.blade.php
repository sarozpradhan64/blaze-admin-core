<x-layouts.admin title="Jobs">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Jobs</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold tracking-tight">Jobs</h2>
        <x-ui.button href="{{ route('admin.jobs.create') }}">
            <x-slot:before>
                <x-lucide-plus class="size-4" />
            </x-slot:before>
            Add Job
        </x-ui.button>
    </div>

    <x-ui.filter-bar action="{{ route('admin.jobs.index') }}">
        <div class="flex-1 min-w-[300px] relative">
            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
            <x-ui.input name="search" value="{{ request('search') }}" placeholder="Search jobs..." class="pl-9 h-10" />
            @if(request('search'))
                <a href="{{ route('admin.jobs.index', request()->except('search')) }}" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <x-lucide-x class="size-4 text-muted-foreground hover:text-foreground" />
                </a>
            @endif
        </div>

        <div class="w-[200px]">
            <x-ui.select name="category_id" value="{{ request('category_id') }}">
                <x-ui.select-trigger class="h-10">
                    <x-slot:icon><x-lucide-layout-grid class="size-4" /></x-slot:icon>
                    <div class="flex flex-col items-start text-xs">
                        <span class="font-medium text-foreground">Category</span>
                        <x-ui.select-value placeholder="All Categories" />
                    </div>
                </x-ui.select-trigger>
                <x-ui.select-content>
                    <x-ui.select-item value="all">All Categories</x-ui.select-item>
                    @foreach($categories as $category)
                        <x-ui.select-item value="{{ $category->id }}">{{ $category->title }}</x-ui.select-item>
                    @endforeach
                </x-ui.select-content>
            </x-ui.select>
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
                    <x-ui.select-item value="draft">Draft</x-ui.select-item>
                </x-ui.select-content>
            </x-ui.select>
        </div>
    </x-ui.filter-bar>

    <x-ui.card>
        <div class="flex items-center justify-between p-4 pb-0 border-b-0">
            <div class="text-sm text-muted-foreground">
                <span class="font-medium text-foreground">{{ $jobs->total() }}</span> jobs found
            </div>
            
            <form action="{{ route('admin.jobs.index') }}" method="GET" class="flex items-center gap-2 text-sm text-muted-foreground">
                @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                @if(request('category_id'))<input type="hidden" name="category_id" value="{{ request('category_id') }}">@endif
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
                        <x-ui.table-head class="w-8"></x-ui.table-head>
                        <x-ui.sortable-th column="title">Title</x-ui.sortable-th>
                        <x-ui.sortable-th column="job_category_id">Category</x-ui.sortable-th>
                        <x-ui.sortable-th column="location">Location</x-ui.sortable-th>
                        <x-ui.sortable-th column="status">Status</x-ui.sortable-th>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-admin::sortable-tbody resource="jobs">
                    @forelse ($jobs as $job)
                        <x-ui.table-row data-id="{{ $job->id }}">
                            <x-ui.table-cell class="w-8">
                                <button
                                    type="button"
                                    data-drag-handle
                                    class="text-muted-foreground hover:text-foreground cursor-grab"
                                >
                                    <x-lucide-grip-vertical class="size-4" />
                                </button>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="font-medium">{{ $job->title }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $job->category?->title ?? '-' }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $job->location ?? '-' }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ $job->status ? 'default' : 'secondary' }}">
                                    {{ $job->status ? 'Active' : 'Draft' }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        variant="ghost"
                                        size="icon"
                                        href="{{ route('admin.jobs.edit', $job) }}"
                                    >
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form
                                        action="{{ route('admin.jobs.destroy', $job) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure?');"
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
                            <x-ui.table-cell colspan="6" class="text-muted-foreground py-6 text-center">
                                No jobs found.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-admin::sortable-tbody>
            </x-ui.table>
        </x-ui.card-content>
        @if ($jobs->hasPages())
            <x-ui.card-footer class="border-t p-4"> {{ $jobs->links() }} </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
