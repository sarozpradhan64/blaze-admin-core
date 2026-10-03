<x-layouts.admin title="Services">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Services</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold tracking-tight">Services</h2>
        <x-ui.button href="{{ route('admin.services.create') }}">
            <x-slot:before>
                <x-lucide-plus class="size-4" />
            </x-slot:before>
            Add Service
        </x-ui.button>
    </div>

    <x-ui.filter-bar action="{{ route('admin.services.index') }}">
            <div class="flex-1 min-w-[300px] relative">
                <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                <x-ui.input name="search" value="{{ request('search') }}" placeholder="Search services..." class="pl-9 h-10" />
                @if(request('search'))
                    <a href="{{ route('admin.services.index', request()->except('search')) }}" class="absolute right-3 top-1/2 -translate-y-1/2">
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
                            <x-ui.select-item value="{{ $category->id }}">{{ $category->name }}</x-ui.select-item>
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

            <div class="w-[200px]">
                <x-ui.select name="tag" value="{{ request('tag') }}">
                    <x-ui.select-trigger class="h-10">
                        <x-slot:icon><x-lucide-tag class="size-4" /></x-slot:icon>
                        <div class="flex flex-col items-start text-xs">
                            <span class="font-medium text-foreground">Tags</span>
                            <x-ui.select-value placeholder="All Tags" />
                        </div>
                    </x-ui.select-trigger>
                    <x-ui.select-content>
                        <x-ui.select-item value="all">All Tags</x-ui.select-item>
                        @foreach($tags as $tag)
                            <x-ui.select-item value="{{ $tag }}">{{ $tag }}</x-ui.select-item>
                        @endforeach
                    </x-ui.select-content>
                </x-ui.select>
            </div>

    </x-ui.filter-bar>

    <x-ui.card>
        <div class="flex items-center justify-between p-4 pb-0 border-b-0">
            <div class="text-sm text-muted-foreground">
                <span class="font-medium text-foreground">{{ $services->total() }}</span> services found
            </div>
            
            <form action="{{ route('admin.services.index') }}" method="GET" class="flex items-center gap-2 text-sm text-muted-foreground">
                @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                @if(request('category_id'))<input type="hidden" name="category_id" value="{{ request('category_id') }}">@endif
                @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                @if(request('tag'))<input type="hidden" name="tag" value="{{ request('tag') }}">@endif
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
                        <x-ui.table-head>
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'title', 'sort_dir' => request('sort_by') === 'title' && request('sort_dir') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-foreground">
                                Title
                                <x-lucide-arrow-up-down class="size-3 text-muted-foreground" />
                            </a>
                        </x-ui.table-head>
                        <x-ui.table-head>
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'service_category_id', 'sort_dir' => request('sort_by') === 'service_category_id' && request('sort_dir') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-foreground">
                                Category
                                <x-lucide-arrow-up-down class="size-3 text-muted-foreground" />
                            </a>
                        </x-ui.table-head>
                        <x-ui.table-head>Tags</x-ui.table-head>
                        <x-ui.table-head>
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_dir' => request('sort_by') === 'status' && request('sort_dir') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-foreground">
                                Status
                                <x-lucide-arrow-up-down class="size-3 text-muted-foreground" />
                            </a>
                        </x-ui.table-head>
                        <x-ui.table-head>
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'is_featured', 'sort_dir' => request('sort_by') === 'is_featured' && request('sort_dir') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-foreground">
                                Featured
                                <x-lucide-arrow-up-down class="size-3 text-muted-foreground" />
                            </a>
                        </x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-admin::sortable-tbody resource="services">
                    @forelse ($services as $service)
                        <x-ui.table-row data-id="{{ $service->id }}">
                            <x-ui.table-cell class="w-8">
                                <button
                                    type="button"
                                    data-drag-handle
                                    class="text-muted-foreground hover:text-foreground cursor-grab"
                                >
                                    <x-lucide-grip-vertical class="size-4" />
                                </button>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="font-medium">{{ $service->title }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $service->category?->name ?? 'Uncategorized' }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($service->tags->take(3) as $tag)
                                        <x-ui.badge variant="outline">{{ $tag->name }}</x-ui.badge>
                                    @endforeach
                                    @if ($service->tags->count() > 3)
                                        <x-ui.badge variant="secondary">+{{ $service->tags->count() - 3 }}
                                            more</x-ui.badge>
                                    @endif
                                </div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ $service->status ? 'default' : 'secondary' }}">
                                    {{ $service->status ? 'Active' : 'Draft' }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @if ($service->is_featured)
                                    <x-lucide-check-circle class="text-primary size-4" />
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        variant="ghost"
                                        size="icon"
                                        href="{{ route('admin.services.edit', $service) }}"
                                    >
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form
                                        action="{{ route('admin.services.destroy', $service) }}"
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
                                No services found.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-admin::sortable-tbody>
            </x-ui.table>
        </x-ui.card-content>
        @if ($services->hasPages())
            <x-ui.card-footer class="border-t p-4"> {{ $services->links() }} </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
